<?php

namespace FOS\OAuth2\Model;

interface AccessTokenInterface
{
    function getValue();

    /**
     * @return DateTime
     */
    function getExpiresAt();
}