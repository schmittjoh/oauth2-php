<?php

namespace FOS\Tests\OAuth2\Model;

use FOS\OAuth2\Model\AuthorizationCode;

class AuthorizationCodeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $code = new AuthorizationCode('foo', 'bar');

        $this->assertEquals('foo', $code->getCode());
        $this->assertEquals('bar', $code->getRedirectUri());
        $this->assertGreaterThan(time() + 28, $code->getExpiresAt()->getTimestamp());
        $this->assertLessThan(time() + 32, $code->getExpiresAt()->getTimestamp());
    }
}