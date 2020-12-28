<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentRelationship extends Model
{
    public function parents(){
	    return $this->hasMany('App\Parent');
	}
}
