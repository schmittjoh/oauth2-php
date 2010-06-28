<?php

namespace FOS\OAuth2;

class Configuration
{
    private $scope;
    private $authorizationCodeLifetime;
    private $accessTokenLifetime;

    /**
     * Constructor.
     *
     * @param array $scope
     * @param integer $authorizationCodeLifetime longer than 10 minutes are not recommended by the spec
     */
    public function __construct(array $scope = array(), $authorizationCodeLifetime = 600, $accessTokenLifetime = 3600)
    {
        $this->scope = $scope;
        $this->authorizationCodeLifetime = $authorizationCodeLifetime;
        $this->accessTokenLifetime = $accessTokenLifetime;
    }

    public function isSupportedAccessRange($range)
    {
        return isset($this->scope[$range]);
    }

    public function getAuthorizationCodeLifetime()
    {
        return $this->authorizationCodeLifetime;
    }

    public function getAccessTokenLifetime()
    {
        return $this->accessTokenLifetime;
    }
}