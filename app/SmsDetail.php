<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsDetail extends Model
{
    use SoftDeletes;

    public function sms(){
	    return $this->belongsTo('App\Sms');
	}
}
