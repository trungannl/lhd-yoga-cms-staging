<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StudioUser extends Model
{
    protected $table = 'studio_user';

    protected $fillable = [
        'studio_id',
        'user_id',
        'start_date',
        'end_date',
        'price',
        'number_of_sessions',
        'approve',
        'is_paid',
        'attend_date'
    ];

    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studio_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
