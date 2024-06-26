<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CautionVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'caution_amt',
        'voucher_id',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
