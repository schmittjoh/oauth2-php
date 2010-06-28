<?php

namespace FOS\OAuth2;

use Symfony\Component\HttpFoundation\Response;
use FOS\OAuth2\Model\RefreshTokenInterface;
use FOS\OAuth2\Model\AccessTokenInterface;
use FOS\OAuth2\Util\String;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\OAuth2\Model\AuthorizationCodeInterface;
use FOS\OAuth2\Storage\AccessTokenStorageInterface;
use FOS\OAuth2\Exception\DenyAuthorizationRequestException;
use FOS\OAuth2\Storage\AuthorizationCodeStorageInterface;
use FOS\OAuth2\Util\SecureRandomInterface;
use FOS\OAuth2\Exception\InvalidRedirectUriException;
use FOS\OAuth2\Exception\AuthorizationServerException;
use FOS\OAuth2\Exception\UnsupportedAccessRangeException;
use FOS\OAuth2\Exception\ClientNotFoundException;
use FOS\OAuth2\Storage\ClientStorageInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Authorization Server.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class AuthorizationServer
{
    private $config;
    private $clientStorage;
    private $redirectUriValidator;
    private $secureRandom;
    private $authCodeStorage;
    private $accessTokenStorage;

    public function __construct(Configuration $config, ClientStorageInterface $clientStorage, RedirectUriValidatorInterface $validator, SecureRandomInterface $secureRandom)
    {
        $this->config = $config;
        $this->clientStorage = $clientStorage;
        $this->redirectUriValidator = $validator;
        $this->secureRandom = $secureRandom;
    }

    public function setAuthCodeStorage(AuthorizationCodeStorageInterface $storage)
    {
        $this->authCodeStorage = $storage;
    }

    public function setAccessTokenStorage(AccessTokenStorageInterface $storage)
    {
        $this->accessTokenStorage = $storage;
    }

    public function createAuthorizationRequestFromRequest(Request $request)
    {
        return $this->createAuthorizationRequest(
            $request->get('response_type'),
            $request->get('client_id'),
            $request->get('scope'),
            $request->get('redirect_uri'),
            $request->get('state'));
    }

    /**
     * Validates the authorization request.
     *
     * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-22#section-4
     *
     * @param string $responseType
     * @param string $clientId
     * @param string $scope
     * @param string $redirectUri
     * @param string $state
     *
     * @return AuthorizationRequest
     */
    public function createAuthorizationRequest($responseType, $clientId, $scope, $redirectUri, $state)
    {
        switch ($responseType) {
            case 'code':
            case 'token':
                if (null === $client = $this->clientStorage->findClient($clientId)) {
                    throw new ClientNotFoundException($clientId);
                }

                $accessRanges = explode(' ', $scope);
                foreach ($accessRanges as $range) {
                    if (!$this->config->isSupportedAccessRange($range)) {
                        throw new UnsupportedAccessRangeException($range);
                    }
                }

                if (empty($redirectUri)) {
                    $redirectUri = $client->getRedirectUri();
                }

                if (!$this->redirectUriValidator->isValidRedirectUri($client, $redirectUri)) {
                    throw new InvalidRedirectUriException($redirectUri);
                }

                return new AuthorizationRequest($requestType, $client, $redirectUri, $accessRanges, $state);

            default:
                throw new AuthorizationServerException(sprintf('The response type "%s" is unknown.', $responseType));
        }
    }

    public function createSuccessfulResponseForAuthorizationRequest(AuthorizationRequest $request)
    {
        switch ($request->getResponseType()) {
            case 'code':
                $code = $this->createAuthorizationCode($request);

                $uri = $request->getRedirectUri()
                    .(false === strpos($uri, '?') ? '?' : '&')
                    .http_build_query(array(
                        'authorization_code' => $code->getCode(),
                        'state' => $request->getState(),
                    ));

                return new RedirectResponse($uri);

            default:
                throw new \RuntimeException('This response type is not supported.');
        }
    }

    /**
     * Grants an authorization request.
     *
     * @param AuthorizationRequest $request
     * @return string
     */
    public function createAuthorizationCode(AuthorizationRequest $request)
    {
        if (AuthorizationRequest::RESPONSE_TYPE_CODE !== $request->getResponseType()) {
            throw new \InvalidArgumentException(sprintf('createAuthorizationCode() expects an authorization request with response type "code", but got "%s".', $request->getResponseType()));
        }

        $code = bin2hex($this->secureRandom->nextBytes(32));

        return $this->authCodeStorage->createAuthorizationCode($code, $request->getClient(), $request->getAccessRanges(), $request->getRedirectUri(), $this->config->getAuthorizationCodeLifetime());
    }

    public function createAccessTokenFromRequest(Request $request)
    {
        if (null === $code = $this->authCodeStorage->findAuthorizationCode($request->get('code'))) {
            throw new \RuntimeException('The given auth code does not exist.');
        }

        return $this->createAccessToken($code, $request->get('redirect_uri'));
    }

    /**
     * Creates an access token.
     *
     * This method MUST only be called by an authorized client which is
     * equal to the client that created the authorization code.
     *
     * In Symfony2, this can be achieved with an access rule such as:
     * "@PreAuthorize("#code.getClient().getIdentifier() == #token.getUsername()")"
     *
     * @param AuthorizationCodeInterface $code
     * @throws \RuntimeException
     *
     * @return AccessTokenInterface
     */
    public function createAccessToken(AuthorizationCodeInterface $code, $redirectUri = null)
    {
        if ($code->isUsed()) {
            $this->accessTokenStorage->deleteAccessTokensForAuthCode($code);

            throw new \RuntimeException('Authorization code was already used.');
        }

        if (false === String::compare($redirectUri, $code->getRedirectUri())) {
            throw new \RuntimeException('The redirect uri does not match.');
        }

        if ($code->getExpiresAt()->getTime() < time()) {
            $this->authCodeStorage->deleteAuthorizationCode($code);

            throw new \RuntimeException('Authorization code has expired.');
        }

        $accessToken = $this->accessTokenStorage->createAccessToken($code, $this->config->getAccessTokenLifetime());
        $code->setUsed();
        $this->authCodeStorage->updateAuthorizationCode($code);

        return $accessToken;
    }

    public function createBearerAccessTokenResponse(AccessTokenInterface $token, RefreshTokenInterface $refreshToken = null)
    {
        $data = array(
            'access_token' => $token->getValue(),
            'token_type'   => 'bearer',
        );

        if ($expiresAt = $token->getExpiresAt()) {
            $data['expires_in'] = $expiresAt->getTime() - time();
        }

        if (null !== $refreshToken) {
            $data['refresh_token'] = $refreshToken->getValue();
        }

        // TODO: Check if granted scope differs from requested scope, if so include granted scope

        $response = new Response(json_encode($data), 200, array('Content-Type' => 'application/json'));
        $response->headers->addCacheControlDirective('no-store');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }
}