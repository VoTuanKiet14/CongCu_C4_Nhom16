<?php

use PHPUnit\Framework\TestCase;

class HealthcheckTest extends TestCase
{
    protected $healthcheck;

    protected function setUp(): void
    {
        $this->healthcheck = new Healthcheck();
    }

    public function testIsValidHealthCheckPass()
    {
        $this->healthcheck->setHealthMetrics(json_encode([
            'hasChronicDiseases' => false,
            'hasRecentDiseases' => false,
            'hasSymptoms' => false,
            'isPregnantOrNursing' => false,
            'HIVTestAgreement' => true
        ]));

        $result = $this->healthcheck->isValidHealthCheck();
        $this->assertTrue($result);
        $this->assertEquals(HealthCheckResult::PASS, $this->healthcheck->getResult());
    }

    public function testIsValidHealthCheckFail()
    {
        $this->healthcheck->setHealthMetrics(json_encode([
            'hasChronicDiseases' => true,
            'hasRecentDiseases' => false,
            'hasSymptoms' => false,
            'isPregnantOrNursing' => false,
            'HIVTestAgreement' => true
        ]));

        $result = $this->healthcheck->isValidHealthCheck();
        $this->assertFalse($result);
        $this->assertEquals(HealthCheckResult::FAIL, $this->healthcheck->getResult());
    }
}