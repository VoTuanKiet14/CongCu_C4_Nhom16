<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';

    public $timestamps = false;

    /**
     * Column mapping between form names and database column names
     */
    protected $fillable = [
        'current_registrations',
        'event_date',
        'event_end_time',
        'event_start_time',
        'max_registrations',
        'name',
        'status',
        'donation_unit_id'
    ];

    /**
     * Property accessors to maintain API compatibility
     */
    public function getEventDateAttribute()
    {
        return $this->attributes['event_date'];
    }

    public function getEventStartTimeAttribute()
    {
        return $this->attributes['event_start_time'];
    }

    public function getEventEndTimeAttribute()
    {
        return $this->attributes['event_end_time'];
    }

    public function getMaxRegistrationsAttribute()
    {
        return $this->attributes['max_registrations'];
    }

    public function getCurrentRegistrationsAttribute()
    {
        return $this->attributes['current_registrations'];
    }

    public function donationUnit()
    {
        return $this->belongsTo(DonationUnit::class, 'donation_unit_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'event_id');
    }
}
