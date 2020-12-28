<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
    public function attendance(){
	    return $this->hasMany('App\Attendance')->orderBy('for_date', 'asc');
	}
}
