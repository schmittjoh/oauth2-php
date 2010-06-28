<?php

namespace FOS\OAuth2\Exception;

class ClientNotFoundException extends AuthorizationServerException
{
    private $id;

    public function __construct($id)
    {
        parent::__construct(sprintf('There is no client with id "%s".', $id));

        $this->id = $id;
    }

    public function isRecoverable()
    {
        return false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMessageKey()
    {
        /** @Desc("The given client identifier was not found.") */
        return 'fos_oauth.client_not_found';
    }
}