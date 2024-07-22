<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Tablo adını tanımlama
    protected $table = 'events';

    // Fillable özelliklerini tanımlama
    protected $fillable = [
        'calender_id',
        'event_all_day',
        'event_start',
        'event_end',
        'event_location',
        'event_title'
    ];
}
