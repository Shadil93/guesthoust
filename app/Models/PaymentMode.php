<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMode extends Model
{
    use HasFactory;
    
    public function paymentMode(){
        return $this->belongsTo(PaymentMode::class);
    }
}
