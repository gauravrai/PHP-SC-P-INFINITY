<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeConcession extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'amount','student_id', 'school_id'
    ];
    public function student(){
	    return $this->belongsTo('App\Student');
	}
	public function school(){
	    return $this->belongsTo('App\School');
	}
}
