<?php

use PHPUnit\Framework\TestCase;
use App\Models\Healthcheck;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Capsule\Manager as Capsule;

class HealthcheckTest extends TestCase
{
    protected $healthcheck;
    protected $mockAppointment;
    protected $mockUser;

    protected function setUp(): void
    {
        // Create instance of Healthcheck model
        $this->healthcheck = new Healthcheck();
        
        // Mock dependencies for isolation testing
        $this->mockAppointment = $this->createMock(Appointment::class);
        $this->mockUser = $this->createMock(User::class);
        
        // Setup mock database connection if needed
        $this->setupTestDatabase();
    }
    
    /**
     * Set up a test database connection
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
        Capsule::schema()->create('healthchecks', function ($table) {
            $table->increments('id');
            $table->string('result')->nullable();
            $table->text('notes')->nullable();
            $table->json('health_metrics')->nullable();
            $table->unsignedInteger('appointment_id')->nullable();
            $table->timestamps();
        });
    }

    public function testHealthcheckCreation()
    {
        // Set basic attributes
        $this->healthcheck->result = "PASS";
        $this->healthcheck->notes = "Patient is healthy";
        
        // Test basic attribute setting
        $this->assertEquals("PASS", $this->healthcheck->result);
        $this->assertEquals("Patient is healthy", $this->healthcheck->notes);
    }
    
    public function testHealthMetricsStorage()
    {
        // Test JSON health metrics storage
        $metrics = [
            'hasChronicDiseases' => false,
            'hasRecentDiseases' => false,
            'hasSymptoms' => false,
            'isPregnantOrNursing' => false,
            'HIVTestAgreement' => true
        ];
        
        $this->healthcheck->health_metrics = $metrics;
        
        // Assert metrics are properly stored
        $this->assertIsArray($this->healthcheck->health_metrics);
        $this->assertEquals($metrics, $this->healthcheck->health_metrics);
    }
    
    public function testHealthcheckResultValidation()
    {
        // Test valid results
        $this->healthcheck->result = "PASS";
        $this->assertEquals("PASS", $this->healthcheck->result);
        
        $this->healthcheck->result = "FAIL";
        $this->assertEquals("FAIL", $this->healthcheck->result);
    }
    
    public function testHealthcheckAppointmentRelationship()
    {
        // Test setting up appointment relationship
        $appointmentId = 1;
        
        // Set up expectations on mock
        $this->mockAppointment->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($appointmentId);
        
        // Set the appointment
        $this->healthcheck->appointment_id = $this->mockAppointment->getAttribute('id');
        
        // Verify relationship
        $this->assertEquals($appointmentId, $this->healthcheck->appointment_id);
    }
    
    public function testHealthMetricValidation()
    {
        // Test with invalid metrics structure
        $invalidMetrics = "not an array";
        
        // We expect an exception when setting non-array metrics
        $this->expectException(\InvalidArgumentException::class);
        
        // This should trigger validation in a proper implementation
        $this->healthcheck->setHealthMetrics($invalidMetrics);
    }
    
    /**
     * This test saves a record to the in-memory database
     */
    public function testDatabasePersistence()
    {
        // Create and save a healthcheck record
        $healthcheck = new Healthcheck();
        $healthcheck->result = "PASS";
        $healthcheck->notes = "All tests passed";
        $healthcheck->health_metrics = ['hasChronicDiseases' => false];
        $healthcheck->save();
        
        // Retrieve from database and verify
        $retrieved = Healthcheck::find($healthcheck->id);
        $this->assertNotNull($retrieved);
        $this->assertEquals("PASS", $retrieved->result);
    }
    
    /**
     * Test appointment relationship integrity
     */
    public function testAppointmentIntegrity()
    {
        // Test nonexistent appointment ID
        $this->healthcheck->appointment_id = 999; // Non-existent ID
        
        // Check if validation fails appropriately
        $isValid = $this->healthcheck->validateAppointmentExists();
        $this->assertFalse($isValid);
    }
    
    public function testUserHealthRecordAccess()
    {
        // Test user accessing their health records
        $userId = 1;
        $appointmentId = 1;
        
        // Mock the user and appointment relationship
        $this->mockUser->method('getAttribute')->with('id')->willReturn($userId);
        $this->mockAppointment->method('getAttribute')
            ->will($this->returnValueMap([
                ['id', $appointmentId],
                ['user_id', $userId]
            ]));
        
        // Test the authorization
        $canAccess = Healthcheck::userCanAccess($this->mockUser->getAttribute('id'), $appointmentId);
        
        // User should be able to access their own records
        $this->assertTrue($canAccess);
    }
    
    protected function tearDown(): void
    {
        // Drop test tables
        Capsule::schema()->dropIfExists('healthchecks');
    }
}