<?php

use PHPUnit\Framework\TestCase;

class AppointmentTest extends TestCase
{
    protected $appointment;

    protected function setUp(): void
    {
        $this->appointment = new Appointment();
    }

    public function testAppointmentCreation()
    {
        $this->appointment->setAppointmentDateTime(new DateTime());
        $this->appointment->setBloodAmount(450);
        $this->appointment->setStatus(AppointmentStatus::PENDING);

        $this->assertNotNull($this->appointment->getAppointmentDateTime());
        $this->assertEquals(450, $this->appointment->getBloodAmount());
        $this->assertEquals(AppointmentStatus::PENDING, $this->appointment->getStatus());
    }

    public function testAppointmentUpdate()
    {
        $this->appointment->setBloodAmount(500);
        $this->assertEquals(500, $this->appointment->getBloodAmount());
    }

    public function testAppointmentStatusChange()
    {
        $this->appointment->setStatus(AppointmentStatus::CONFIRMED);
        $this->assertEquals(AppointmentStatus::CONFIRMED, $this->appointment->getStatus());
    }

    public function testAppointmentInvalidData()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->appointment->setBloodAmount(-100); // Invalid blood amount
    }
}