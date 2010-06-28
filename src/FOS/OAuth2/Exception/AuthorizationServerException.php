<?php

namespace FOS\OAuth2\Exception;

class AuthorizationServerException extends \RuntimeException implements Exception
{
    public function isRecoverable()
    {
        return true;
    }

    public function getMessageKey()
    {
        /** @Desc("The authorization error was not able to fulfil your request. Please try again, or contact support if the problem persists.") */
        return 'fos_oauth.general_authorization_error';
    }

    public function getMessageParameters()
    {
        return array();
    }
}