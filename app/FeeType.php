<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeType extends Model
{
    use SoftDeletes;
    public function school(){
	    return $this->belongsTo('App\School');
	}
	public function fee_structure(){
	    return $this->hasMany('App\FeeStructure');
	}
}
