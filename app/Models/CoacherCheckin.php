<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoacherCheckin extends Model
{
    use HasFactory;

    protected $table = 'coacher_checkins';

    protected $fillable = [
        'studio_id',
        'coacher_id',
        'date',
        'latitude',
        'longtitude',
    ];
}
