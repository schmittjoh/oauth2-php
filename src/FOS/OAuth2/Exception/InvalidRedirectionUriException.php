<?php

namespace FOS\OAuth2\Exception;

class InvalidRedirectUriException extends AuthorizationServerException
{
    public function isRecoverable()
    {
        return false;
    }

    public function getMessageKey()
    {
        /** @Desc("The given redirection uri is invalid.") */
        return 'fos_oauth.invalid_redirect_uri';
    }
}