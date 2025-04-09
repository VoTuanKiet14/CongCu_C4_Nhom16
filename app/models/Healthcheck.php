<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Healthcheck extends Model
{
    protected $table = 'healthcheck';

    public $timestamps = false;

    protected $fillable = [
        'health_metrics',
        'notes',
        'result',
        'appointment_id'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function isValidHealthCheck()
    {
        $metrics = json_decode($this->health_metrics);

        if (!$metrics->hasChronicDiseases && !$metrics->hasRecentDiseases && !$metrics->hasSymptoms && !$metrics->isPregnantOrNursing && $metrics->HIVTestAgreement) {
            $this->result = 'PASS';
            return true;
        } else {
            $this->result = 'FAIL';
            return false;
        }
    }
}
