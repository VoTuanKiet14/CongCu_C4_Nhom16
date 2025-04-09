<?php

use PHPUnit\Framework\TestCase;

class PasswordResetTest extends TestCase
{
    protected $passwordResetToken;

    protected function setUp(): void
    {
        $this->passwordResetToken = new PasswordResetToken();
    }

    public function testTokenCanBeSetAndRetrieved()
    {
        $token = 'sampleToken123';
        $this->passwordResetToken->setToken($token);
        $this->assertEquals($token, $this->passwordResetToken->getToken());
    }

    public function testUserCanBeSetAndRetrieved()
    {
        $user = new User();
        $this->passwordResetToken->setUser($user);
        $this->assertEquals($user, $this->passwordResetToken->getUser());
    }

    public function testExpiryDateCanBeSetAndRetrieved()
    {
        $expiryDate = new DateTime();
        $this->passwordResetToken->setExpiryDate($expiryDate);
        $this->assertEquals($expiryDate, $this->passwordResetToken->getExpiryDate());
    }
}