<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    public function school(){
	    return $this->belongsTo('App\School');
	}
	public function school_classes(){
	    return $this->belongsToMany('App\SchoolClass');
	}
	public function teachers(){
	    return $this->hasMany('App\Teacher');
	}
}
