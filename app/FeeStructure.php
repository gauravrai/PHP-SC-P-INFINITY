<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeStructure extends Model
{
    use SoftDeletes;

    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function fee(){
	    return $this->belongsTo('App\Fee');
	}
    public function fee_type(){
	    return $this->belongsTo('App\FeeType');
	}
}
