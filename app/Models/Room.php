<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'type'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id', 'id');
    }
}
