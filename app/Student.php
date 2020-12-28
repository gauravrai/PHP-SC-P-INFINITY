<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Student extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    protected $guard = 'student';

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAuthPassword(){
    	return $this->password;
    }

    public function caste(){
	    return $this->belongsTo('App\Caste');
	}
    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function parents(){
	    return $this->hasMany('App\StudentParent');
	}
    public function fee_balances(){
	    return $this->hasMany('App\FeeBalance');
	}
    public function fee_paid(){
	    return $this->hasMany('App\FeePaid');
	}
    public function fee_student_record(){
	    return $this->hasMany('App\FeeStudentRecord');
	}
    public function attendance(){
	    return $this->hasMany('App\Attendance');
	}
    public function admission(){
	    return $this->hasOne('App\Admission')->where('isactive', 1)->where('status', 2);//2 studying
	}
    public function admission_all(){
	    return $this->hasOne('App\Admission')->where('isactive', 1);
	}
    public function conveyance(){
	    return $this->belongsToMany('App\Conveyance');
	}
    public function fee_concession(){
	    return $this->hasOne('App\FeeConcession');
	}
}
