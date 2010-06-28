<?php

namespace FOS\OAuth2\Storage\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\OAuth2\Storage\ClientStorageInterface;

class ClientStorage implements ClientStorageInterface
{
    private $om;
    private $repository;

    public function __construct(ObjectManager $om, $class)
    {
        $this->om = $om;
        $this->repository = $this->om->getRepository($class);
    }

    public function findClient($id)
    {
        return $this->repository->findOneBy(array('identifier' => $id));
    }
}