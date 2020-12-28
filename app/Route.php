<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use SoftDeletes;
	
    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function conveyances(){
	    return $this->belongsToMany('App\Conveyance')->orderBy('name', 'asc');
	}
}
