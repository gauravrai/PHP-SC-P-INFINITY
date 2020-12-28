<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
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
    public function attendance_status(){
	    return $this->belongsTo('App\AttendanceStatus');
	}
    public function student(){
	    return $this->belongsTo('App\Student');
	}
}
