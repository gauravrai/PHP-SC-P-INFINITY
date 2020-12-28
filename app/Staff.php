<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function department(){
	    return $this->belongsTo('App\Department');
	}
    public function designation(){
	    return $this->belongsTo('App\Designation');
	}
    public function user(){
	    return $this->belongsTo('App\User');
	}
    public function role(){
	    return $this->belongsTo('App\Role');
	}
}
