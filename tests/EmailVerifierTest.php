<?php

use PHPUnit\Framework\TestCase;
use EmailVerifier\EmailVerifier;

class EmailVerifierTest extends TestCase
{
    public function testInvalidEmailFormat()
    {
        $this->assertFalse(EmailVerifier::isValidEmail('invalid-email'));
    }

    public function testNonExistentDomain()
    {
        $this->assertFalse(EmailVerifier::isValidEmail('user@nonexistentdomain.com'));
    }

    public function testValidEmail()
    {
        $this->assertTrue(EmailVerifier::isValidEmail('real@example.com')); // Замените на реальный email для теста
    }
}
