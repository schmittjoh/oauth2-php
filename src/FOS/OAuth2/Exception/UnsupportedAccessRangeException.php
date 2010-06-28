<?php

namespace FOS\OAuth2\Exception;

class UnsupportedAccessRangeException extends AuthorizationServerException
{
    private $range;

    public function __construct($range)
    {
        parent::__construct(sprintf('The access range "%s" is not supported.', $range));

        $this->range = $range;
    }

    public function getRange()
    {
        return $this->range;
    }
}