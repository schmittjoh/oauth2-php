<?php

namespace FOS\OAuth2;

use FOS\OAuth2\Model\ClientInterface;

interface RedirectUriValidatorInterface
{
    function isValidRedirectUri(ClientInterface $client, $redirectUri);
}