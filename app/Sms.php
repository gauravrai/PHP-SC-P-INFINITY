<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sms extends Model
{
    use SoftDeletes;

    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function sms_category(){
	    return $this->belongsTo('App\SmsCategory');
	}
	public function sms_details(){
	    return $this->hasMany('App\SmsDetail');
	}
}
