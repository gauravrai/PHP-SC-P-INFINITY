<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id', 'academic_session_id', 'school_class_id', 'section_id', 'fee_id', 'school_class_id'
    ];

    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function school_class(){
	    return $this->belongsTo('App\SchoolClass');
	}
    public function section(){
	    return $this->belongsTo('App\Section');
	}
    public function fee(){
	    return $this->belongsTo('App\Fee');
	}
    public function student(){
	    return $this->belongsTo('App\Student');
	}

}
