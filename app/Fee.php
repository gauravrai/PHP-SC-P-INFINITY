<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FeeType;
use Auth;

class Fee extends Model
{
    use SoftDeletes;

    public function school(){
	    return $this->belongsTo('App\School');
	}
    public function all_fee_strucures(){
    	return $this->hasMany('App\FeeStructure')->orderBy('sort_order', 'asc');
    }
    public function fee_strucures(){
    	$transport=FeeType::where('fee_type', 'transport')->where('school_id', Auth::user()->school_id)->first();

	    return $this->hasMany('App\FeeStructure')->where('fee_type_id', '!=', $transport->id)->orderBy('sort_order', 'asc');
	}
    public function transport_fee_strucures(){
    	$transport=FeeType::where('fee_type', 'transport')->where('school_id', Auth::user()->school_id)->first();

	    return $this->hasOne('App\FeeStructure')->where('fee_type_id', $transport->id)->orderBy('sort_order', 'asc');
	}
    public function school_classes(){
	    return $this->belongsToMany('App\SchoolClass')->whereNull('fee_school_class.deleted_at')->orderBy('sort_order', 'asc')->withPivot('created_at', 'deleted_at');
	}

}
