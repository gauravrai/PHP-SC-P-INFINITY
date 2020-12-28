<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
    public function students(){
	    return $this->belongsTo('App\Student');
	}
	public function parent_relationship(){
	    return $this->belongsTo('App\ParentRelationship');
	}
}
