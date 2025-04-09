<?php

use PHPUnit\Framework\TestCase;

class BloodInventoryTest extends TestCase
{
    protected $bloodInventory;

    protected function setUp(): void
    {
        $this->bloodInventory = new BloodInventory();
    }

    public function testSetBloodType()
    {
        $this->bloodInventory->setBloodType('A+');
        $this->assertEquals('A+', $this->bloodInventory->getBloodType());
    }

    public function testSetQuantity()
    {
        $this->bloodInventory->setQuantity(10);
        $this->assertEquals(10, $this->bloodInventory->getQuantity());
    }

    public function testSetLastUpdated()
    {
        $dateTime = new DateTime();
        $this->bloodInventory->setLastUpdated($dateTime);
        $this->assertEquals($dateTime, $this->bloodInventory->getLastUpdated());
    }

    public function testSetExpirationDate()
    {
        $dateTime = new DateTime();
        $this->bloodInventory->setExpirationDate($dateTime);
        $this->assertEquals($dateTime, $this->bloodInventory->getExpirationDate());
    }

    public function testSetAppointment()
    {
        $appointment = new Appointment();
        $this->bloodInventory->setAppointment($appointment);
        $this->assertEquals($appointment, $this->bloodInventory->getAppointment());
    }
}