<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'author',
        'content',
        'image_url',
        'timestamp',
        'title'
    ];

    public $timestamps = false;
}