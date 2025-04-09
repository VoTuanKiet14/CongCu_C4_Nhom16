<?php

use PHPUnit\Framework\TestCase;
use App\Models\Event;
use App\Models\DonationUnit;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Capsule\Manager as Capsule;

class EventTest extends TestCase
{
    protected $event;
    protected $mockDonationUnit;
    protected $mockUser;

    protected function setUp(): void
    {
        // Create Event instance for testing
        $this->event = new Event();
        
        // Create mock objects
        $this->mockDonationUnit = $this->createMock(DonationUnit::class);
        $this->mockUser = $this->createMock(User::class);
        
        // Set up test database
        $this->setupTestDatabase();
    }
    
    /**
     * Set up a test database connection for isolated testing
     */
    private function setupTestDatabase()
    {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => 'sqlite',
            'database'  => ':memory:',
            'prefix'    => '',
        ]);
        
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        
        // Create necessary tables for testing
        Capsule::schema()->create('events', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->date('event_date');
            $table->time('event_start_time');
            $table->time('event_end_time');
            $table->integer('max_registrations');
            $table->integer('current_registrations')->default(0);
            $table->integer('donation_unit_id');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        
        Capsule::schema()->create('donation_units', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        
        Capsule::schema()->create('appointments', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('event_id')->nullable();
            $table->timestamps();
        });
    }

    public function testEventCreation()
    {
        // Test setting basic event properties
        $this->event->setName("Blood Donation Drive");
        $this->event->setEventDate(new \DateTime('2025-04-15'));
        $this->event->setEventStartTime(new \DateTime('08:00:00'));
        $this->event->setEventEndTime(new \DateTime('16:00:00'));
        $this->event->setMaxRegistrations(50);
        $this->event->setCurrentRegistrations(0);
        $this->event->setDonationUnitId(1);
        $this->event->setStatus(1);
        
        // Assert properties were set correctly
        $this->assertEquals("Blood Donation Drive", $this->event->getName());
        $this->assertEquals("2025-04-15", $this->event->getEventDate()->format('Y-m-d'));
        $this->assertEquals("08:00:00", $this->event->getEventStartTime()->format('H:i:s'));
        $this->assertEquals("16:00:00", $this->event->getEventEndTime()->format('H:i:s'));
        $this->assertEquals(50, $this->event->getMaxRegistrations());
        $this->assertEquals(0, $this->event->getCurrentRegistrations());
        $this->assertEquals(1, $this->event->getDonationUnitId());
        $this->assertEquals(1, $this->event->getStatus());
    }

    public function testEventRegistration()
    {
        // Set initial event properties
        $this->event->setMaxRegistrations(100);
        $this->event->setCurrentRegistrations(50);
        
        // Test registering a user for the event
        $registrationSuccessful = $this->event->registerUser($this->mockUser);
        
        // Assert registration was successful
        $this->assertTrue($registrationSuccessful);
        
        // Assert current registrations was incremented
        $this->assertEquals(51, $this->event->getCurrentRegistrations());
    }

    public function testEventFull()
    {
        // Set event to be at capacity
        $this->event->setMaxRegistrations(50);
        $this->event->setCurrentRegistrations(50);
        
        // Try to register when event is full
        $registrationSuccessful = $this->event->registerUser($this->mockUser);
        
        // Assert registration failed
        $this->assertFalse($registrationSuccessful);
        
        // Assert current registrations unchanged
        $this->assertEquals(50, $this->event->getCurrentRegistrations());
    }
    
    public function testDatabasePersistence()
    {
        // Create and save an event
        $event = new Event();
        $event->name = "Test Database Event";
        $event->event_date = "2025-05-20";
        $event->event_start_time = "09:00:00";
        $event->event_end_time = "17:00:00";
        $event->max_registrations = 100;
        $event->current_registrations = 0;
        $event->donation_unit_id = 1;
        $event->status = 1;
        $event->save();
        
        // Retrieve from database and verify
        $retrieved = Event::find($event->id);
        $this->assertNotNull($retrieved);
        $this->assertEquals("Test Database Event", $retrieved->name);
    }
    
    public function testEventDates()
    {
        // Test past event
        $this->event->setEventDate(new \DateTime(date('Y-m-d', strtotime('-1 day'))));
        $this->assertTrue($this->event->isPastEvent());
        $this->assertFalse($this->event->isFutureEvent());
        
        // Test future event
        $this->event->setEventDate(new \DateTime(date('Y-m-d', strtotime('+1 day'))));
        $this->assertFalse($this->event->isPastEvent());
        $this->assertTrue($this->event->isFutureEvent());
    }
    
    public function testEventTimeConflict()
    {
        // Create two events on the same day with overlapping times
        $event1 = new Event();
        $event1->setEventDate(new \DateTime("2025-05-20"));
        $event1->setEventStartTime(new \DateTime("09:00:00"));
        $event1->setEventEndTime(new \DateTime("12:00:00"));
        
        $event2 = new Event();
        $event2->setEventDate(new \DateTime("2025-05-20"));
        $event2->setEventStartTime(new \DateTime("11:00:00"));
        $event2->setEventEndTime(new \DateTime("14:00:00"));
        
        // Check for time conflict
        $hasConflict = $event1->hasTimeConflictWith($event2);
        $this->assertTrue($hasConflict);
        
        // Adjust times to avoid conflict
        $event2->setEventStartTime(new \DateTime("13:00:00"));
        $hasConflict = $event1->hasTimeConflictWith($event2);
        $this->assertFalse($hasConflict);
    }
    
    public function testEventAvailability()
    {
        // Set up event that's active but full
        $this->event->setStatus(1);
        $this->event->setMaxRegistrations(50);
        $this->event->setCurrentRegistrations(50);
        
        // Check availability
        $this->assertFalse($this->event->hasAvailableSpots());
        
        // Adjust registrations
        $this->event->setCurrentRegistrations(49);
        $this->assertTrue($this->event->hasAvailableSpots());
        
        // Inactive event
        $this->event->setStatus(0);
        $this->assertFalse($this->event->hasAvailableSpots());
    }
    
    public function testEventRegistrationPercentage()
    {
        // Set values
        $this->event->setMaxRegistrations(50);
        $this->event->setCurrentRegistrations(25);
        
        // Test percentage calculation
        $this->assertEquals(50, $this->event->getRegistrationPercentage());
        
        // Edge case: empty event
        $this->event->setMaxRegistrations(0);
        $this->event->setCurrentRegistrations(0);
        $this->assertEquals(0, $this->event->getRegistrationPercentage());
    }
    
    public function testDonationUnitRelationship()
    {
        // Set up donation unit mock
        $unitId = 1;
        $unitName = "Blood Bank A";
        
        $this->mockDonationUnit->method('getAttribute')
            ->will($this->returnValueMap([
                ['id', $unitId],
                ['name', $unitName]
            ]));
        
        // Set the donation unit
        $this->event->setDonationUnitId($this->mockDonationUnit->getAttribute('id'));
        
        // Verify relationship
        $this->assertEquals($unitId, $this->event->getDonationUnitId());
    }
    
    protected function tearDown(): void
    {
        // Drop test tables
        Capsule::schema()->dropIfExists('appointments');
        Capsule::schema()->dropIfExists('events');
        Capsule::schema()->dropIfExists('donation_units');
    }
}