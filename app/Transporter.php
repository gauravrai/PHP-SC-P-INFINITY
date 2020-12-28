<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transporter extends Model
{
    use SoftDeletes;
	
    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function routes(){
	    return $this->belongsToMany('App\Routes')->orderBy('name', 'asc');
	}
}
