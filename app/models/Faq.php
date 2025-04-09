<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faq';

    protected $fillable = [
        'title',
        'description',
        'timestamp'
    ];

    public $timestamps = false; // Disable automatic timestamps if not needed
}