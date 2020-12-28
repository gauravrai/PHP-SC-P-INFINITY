<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsCategory extends Model
{
    use SoftDeletes;

    public function sms(){
	    return $this->hasMany('App\Sms');
	}
}
