<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;

    public function sms(){
	    return $this->hasMany('App\Sms');
	}
    public function subjects(){
	    return $this->hasMany('App\Subject');
	}
    public function teachers(){
	    return $this->hasMany('App\Teacher');
	}
    public function fee_concession(){
	    return $this->hasMany('App\FeeConcession');
	}
}
