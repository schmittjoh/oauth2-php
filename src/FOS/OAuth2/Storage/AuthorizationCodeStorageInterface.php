<?php

namespace FOS\OAuth2\Storage;

use FOS\OAuth2\Model\AuthorizationCodeInterface;

interface AuthorizationCodeStorageInterface
{
    /**
     * Creates an authorization code.
     *
     * @param string $code
     * @param string $redirectUri
     * @param integer $lifetime in seconds
     *
     * @return AuthorizationCodeInterface
     */
    function createAuthorizationCode($code, $redirectUri, array $scopes, $lifetime);

    /**
     * Finds an authorization code.
     *
     * @param string $code
     * @return AuthorizationCodeInterface|null
     */
    function findAuthorizationCode($code);

    function updateAuthorizationCode(AuthorizationCodeInterface $code);

    /**
     * Deletes an authorization code.
     *
     * @param AuthorizationCodeInterface $code
     */
    function deleteAuthorizationCode(AuthorizationCodeInterface $code);
}