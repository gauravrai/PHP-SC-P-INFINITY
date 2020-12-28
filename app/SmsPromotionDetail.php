<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsPromotionDetail extends Model
{
    use SoftDeletes;
    public function sms(){
	    return $this->belongsTo('App\SmsPromotion');
	}
}
