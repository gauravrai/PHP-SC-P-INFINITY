<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
	use SoftDeletes;
	
    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function sections(){
	    return $this->belongsToMany('App\Section')->orderBy('name', 'asc');
	}
	public function active_students(){
	    return $this->hasMany('App\Student')->where('isactive', 1);
	}
	public function all_students(){
	    return $this->hasMany('App\Student');
	}
	public function fees(){
	    return $this->belongsToMany('App\Fee');
	}
}
