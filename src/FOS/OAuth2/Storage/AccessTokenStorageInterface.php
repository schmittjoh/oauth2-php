<?php

namespace FOS\OAuth2\Storage;

use FOS\OAuth2\Model\AuthorizationCodeInterface;

interface AccessTokenStorageInterface
{
    /**
     * Creates an access token.
     *
     * @param AuthorizationCodeInterface $code
     * @param string $value
     * @param integer $lifetime
     */
    function createAccessToken(AuthorizationCodeInterface $code, $value, $lifetime);
    function deleteAccessTokensForAuthCode(AuthorizationCodeInterface $code);
}