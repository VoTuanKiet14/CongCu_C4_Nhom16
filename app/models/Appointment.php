<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointment';

    public $timestamps = false;

    protected $fillable = [
        'appointment_date_time',
        'blood_amount',
        'next_donation_eligible_date',
        'status',
        'event_id',
        'user_cccd',
        'blood_inventory_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_cccd', 'cccd');
    }

    public function healthcheck()
    {
        return $this->hasOne(Healthcheck::class, 'appointment_id');
    }

    public function bloodDonationHistory()
    {
        return $this->hasOne(BloodDonationHistory::class, 'appointment_id');
    }

    public function bloodInventory()
    {
        return $this->hasOne(BloodInventory::class, 'appointment_id');
    }
}
