<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caste extends Model
{
    public function student(){
	    return $this->hasMany('App\Student');
	}
}
