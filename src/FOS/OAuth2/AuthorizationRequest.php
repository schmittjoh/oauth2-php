<?php

namespace FOS\OAuth2;

/**
 * Authorization Request.
 *
 * Its values are assumed to be validated prior to construction.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class AuthorizationRequest
{
    const RESPONSE_TYPE_CODE = 'code';
    const RESPONSE_TYPE_TOKEN = 'token';

    private $responseType;
    private $client;
    private $redirectUri;
    private $accessRanges;
    private $state;

    public function __construct($responseType, ClientInterface $client, $redirectUri = null, array $accessRanges = array(), $state = null)
    {
        $this->responseType = $responseType;
        $this->client = $client;
        $this->redirectUri = $redirectUri;
        $this->accessRanges = $accessRanges;
        $this->state = $state;
    }

    public function getResponseType()
    {
        return $this->responseType;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    public function getAccessRanges()
    {
        return $this->accessRanges;
    }

    public function getState()
    {
        return $this->state;
    }
}