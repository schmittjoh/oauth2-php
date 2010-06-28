<?php

namespace FOS\OAuth2\Storage\Doctrine;

use FOS\OAuth2\Model\AccessToken;

use FOS\OAuth2\Model\AuthorizationCodeInterface;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\OAuth2\Storage\AccessTokenStorageInterface;

class AccessTokenStorage implements AccessTokenStorageInterface
{
    private $om;
    private $repository;

    public function __construct(ObjectManager $om, $class)
    {
        $this->om = $om;
        $this->repository = $this->om->getRepository($class);
    }

    public function deleteAccessTokensForAuthCode(AuthorizationCodeInterface $code)
    {
        $tokens = $this->repository->findBy(array(
            'code' => $code,
        ));

        foreach ($tokens as $token) {
            $this->om->remove($token);
        }

        $this->om->flush();
    }

    public function createAccessToken(AuthorizationCodeInterface $code, $value, $lifetime)
    {
        $token = new AccessToken($code, $value, $lifetime);
        $this->om->persist($token);
        $this->om->flush();

        return $token;
    }
}