<?php

namespace FOS\OAuth2\Model;

class AccessToken implements AccessTokenInterface
{
    private $code;
    private $value;
    private $createdAt;
    private $lifetime;

    public function __construct(AuthorizationCode $code, $value, $lifetime = 3600)
    {
        $this->code = $code;
        $this->value = $value;
        $this->createdAt = new \DateTime;
        $this->lifetime = $lifetime;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getExpiresAt()
    {
        $time = clone $this->createdAt;
        $time->add(\DateInterval::createFromDateString($this->lifetime.' seconds'));

        return $time;
    }
}