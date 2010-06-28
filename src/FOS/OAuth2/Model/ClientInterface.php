<?php

namespace FOS\OAuth2\Model;

interface ClientInterface
{
    function getIdentifier();
    function getRedirectUri();
}