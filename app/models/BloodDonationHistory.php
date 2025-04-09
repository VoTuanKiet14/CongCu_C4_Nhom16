<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonationHistory extends Model
{
    protected $table = 'blood_donation_history';

    public $timestamps = false;

    protected $fillable = [
        'blood_amount',
        'donation_date_time',
        'donation_location',
        'donation_type',
        'next_donation_eligible_date',
        'notes',
        'reaction_after_donation',
        'appointment_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'cccd');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
