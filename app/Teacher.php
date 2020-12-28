<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function school_class(){
	    return $this->belongsTo('App\SchoolClass');
	}
    public function section(){
	    return $this->belongsTo('App\Section');
	}
    public function staff(){
	    return $this->belongsTo('App\Staff');
	}
    public function user(){
	    return $this->belongsTo('App\User');
	}
	public function subjects(){
	    return $this->hasMany('App\Subject');
	}
}
