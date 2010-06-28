<?php

namespace FOS\OAuth2\Util;

interface SecureRandomInterface
{
    function nextBytes($nbBytes);
}