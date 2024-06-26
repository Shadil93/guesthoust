<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    use HasFactory;

    public function paymentLog(){
        return $this->belongsTo(PaymentLog::class);
    }

    public function paymentMode(){
        return $this->belongsTo(PaymentMode::class);
    }
}
