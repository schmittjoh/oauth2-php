<?php

namespace FOS\OAuth2\Storage;

interface ClientStorageInterface
{
    function findClient($clientId);
}