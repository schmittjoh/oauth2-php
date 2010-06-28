<?php

namespace FOS\OAuth2\Model;

class AuthorizationCode implements AuthorizationCodeInterface
{
    private $code;
    private $redirectUri;
    private $createdAt;
    private $lifetime;
    private $used = false;

    public function __construct($code, $redirectUri, $lifetime = 30)
    {
        $this->code = $code;
        $this->redirectUri = $redirectUri;
        $this->createdAt = new \DateTime;
        $this->lifetime = (integer) $lifetime;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    public function getExpiresAt()
    {
        $time = clone $this->createdAt;
        $time->add(\DateInterval::createFromDateString($this->lifetime.' seconds'));

        return $time;
    }

    public function isUsed()
    {
        return $this->used;
    }

    public function setUsed()
    {
        $this->used = true;
    }
}