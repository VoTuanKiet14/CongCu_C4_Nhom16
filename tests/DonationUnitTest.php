<?php

use PHPUnit\Framework\TestCase;

class DonationUnitTest extends TestCase
{
    protected $donationUnit;

    protected function setUp(): void
    {
        $this->donationUnit = new DonationUnit();
    }

    public function testCanCreateDonationUnit()
    {
        $this->donationUnit->setName("Blood Donation Center");
        $this->donationUnit->setLocation("123 Main St");
        $this->donationUnit->setPhone("123-456-7890");
        $this->donationUnit->setEmail("info@blooddonation.org");
        $this->donationUnit->setUnitPhotoUrl("http://example.com/photo.jpg");

        $this->assertEquals("Blood Donation Center", $this->donationUnit->getName());
        $this->assertEquals("123 Main St", $this->donationUnit->getLocation());
        $this->assertEquals("123-456-7890", $this->donationUnit->getPhone());
        $this->assertEquals("info@blooddonation.org", $this->donationUnit->getEmail());
        $this->assertEquals("http://example.com/photo.jpg", $this->donationUnit->getUnitPhotoUrl());
    }

    public function testCanUpdateDonationUnit()
    {
        $this->donationUnit->setName("New Blood Donation Center");
        $this->donationUnit->setLocation("456 Elm St");
        $this->donationUnit->setPhone("987-654-3210");
        $this->donationUnit->setEmail("contact@blooddonation.org");
        $this->donationUnit->setUnitPhotoUrl("http://example.com/newphoto.jpg");

        $this->assertEquals("New Blood Donation Center", $this->donationUnit->getName());
        $this->assertEquals("456 Elm St", $this->donationUnit->getLocation());
        $this->assertEquals("987-654-3210", $this->donationUnit->getPhone());
        $this->assertEquals("contact@blooddonation.org", $this->donationUnit->getEmail());
        $this->assertEquals("http://example.com/newphoto.jpg", $this->donationUnit->getUnitPhotoUrl());
    }

    public function testCanDeleteDonationUnit()
    {
        // Assuming there's a method to delete a donation unit
        $this->donationUnit->delete();
        $this->assertNull($this->donationUnit->find($this->donationUnit->getId()));
    }
}