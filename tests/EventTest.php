<?php

use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    protected $event;

    protected function setUp(): void
    {
        $this->event = new Event();
    }

    public function testEventCreation()
    {
        $this->event->setName("Blood Donation Drive");
        $this->event->setEventDate(new DateTime('2023-12-01'));
        $this->event->setEventStartTime(new DateTime('09:00:00'));
        $this->event->setEventEndTime(new DateTime('17:00:00'));
        $this->event->setMaxRegistrations(100);
        $this->event->setCurrentRegistrations(0);

        $this->assertEquals("Blood Donation Drive", $this->event->getName());
        $this->assertEquals(100, $this->event->getMaxRegistrations());
        $this->assertEquals(0, $this->event->getCurrentRegistrations());
        $this->assertEquals(EventStatus::ACTIVE, $this->event->getStatus());
    }

    public function testEventRegistration()
    {
        $this->event->setMaxRegistrations(100);
        $this->event->setCurrentRegistrations(50);

        $this->event->setCurrentRegistrations($this->event->getCurrentRegistrations() + 1);

        $this->assertEquals(51, $this->event->getCurrentRegistrations());
    }

    public function testEventFull()
    {
        $this->event->setMaxRegistrations(2);
        $this->event->setCurrentRegistrations(2);

        $this->assertTrue($this->event->getCurrentRegistrations() >= $this->event->getMaxRegistrations());
    }
}