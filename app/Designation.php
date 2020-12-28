<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes;

    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function staff(){
	    return $this->hasMany('App\Staff')->orderBy('name', 'asc');
	}
}
