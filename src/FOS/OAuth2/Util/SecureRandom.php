<?php

namespace FOS\OAuth2\Util;

class SecureRandom implements SecureRandomInterface
{
    public function nextBytes($nbBytes)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);

            if (false !== $bytes && true === $strong) {
                return $bytes;
            }
        }

        $bytes = '';
        while (strlen($bytes) < $nbBytes) {
            $bytes .= hash('sha512', uniqid(mt_rand(), true), true);
        }

        return substr($bytes, 0, $nbBytes);
    }
}