<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Room extends Model {
    use GlobalStatus, Searchable;

    protected $guarded = [''];

    public function roomType() {
        return $this->belongsTo(RoomType::class);
    }

    public function booked() {
        return $this->hasMany(BookedRoom::class, 'room_id');
    }

}
