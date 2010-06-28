<?php

namespace FOS\OAuth2\Model;

class Client implements ClientInterface
{
    private $identifier;
    private $redirectUri;

    public function __construct($identifier, $redirectUri)
    {
        $this->identifier = $identifier;
        $this->redirectUri = $redirectUri;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getRedirectUri()
    {
        return $this->redirectUri;
    }
}