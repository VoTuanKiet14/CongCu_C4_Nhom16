<?php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testUserCreation()
    {
        $this->user->setUsername('123456789012');
        $this->user->setPassword('password');
        $this->user->setPhone('0123456789');
        $this->user->setEmail('user@example.com');

        $this->assertEquals('123456789012', $this->user->getUsername());
        $this->assertEquals('password', $this->user->getPassword());
        $this->assertEquals('0123456789', $this->user->getPhone());
        $this->assertEquals('user@example.com', $this->user->getEmail());
    }

    public function testUserValidation()
    {
        $this->user->setUsername('123456789012');
        $this->user->setPassword('password');
        
        $this->assertTrue($this->user->isValid());
    }

    public function testUserRoleAssignment()
    {
        $role = new Role();
        $role->setName('Donor');
        $this->user->setRole($role);

        $this->assertEquals('Donor', $this->user->getRole()->getName());
    }

    public function testUserAppointments()
    {
        $appointment = new Appointment();
        $this->user->addAppointment($appointment);

        $this->assertCount(1, $this->user->getAppointments());
    }
}