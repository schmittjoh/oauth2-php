<?php

namespace FOS\OAuth2\Storage\Doctrine;

use FOS\OAuth2\Model\AuthorizationCode;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\OAuth2\Storage\AuthorizationCodeStorageInterface;

class AuthorizationCodeStorage implements AuthorizationCodeStorageInterface
{
    private $om;
    private $repository;

    public function __construct(ObjectManager $om, $class)
    {
        $this->om = $om;
        $this->repository = $this->om->getRepository($class);
    }

    public function createAuthorizationCode($code, $redirectUri, array $scopes, $lifetime)
    {
        $code = new AuthorizationCode($code, $redirectUri, $lifetime);
        $this->om->persist($code);
        $this->om->flush();

        return $code;
    }

    public function findAuthorizationCode($code)
    {
        return $this->repository->findOneBy(array('code' => $code));
    }

    public function updateAuthorizationCode(AuthorizationCodeInterface $code)
    {
        $this->om->persist($code);
        $this->om->flush();
    }

    public function deleteAuthorizationCode(AuthorizationCodeInterface $code)
    {
        $this->om->remove($code);
        $this->om->flush();
    }
}