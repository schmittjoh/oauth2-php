<?php

namespace FOS\OAuth2\Model;

interface AuthorizationCodeInterface
{
    function getCode();
    function getRedirectUri();

    /**
     * @return DateTime
     */
    function getExpiresAt();
    function isUsed();
    function setUsed();
}