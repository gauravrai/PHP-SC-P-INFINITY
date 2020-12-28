<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Helper;
use App\User;
use App\AcademicSession;
use App\Section;
use App\SchoolClass;
use App\Department;
use App\Designation;
use App\Staff;
use App\Fee;
use App\FeeStudentRecord;
use App\FeeStructure;
use App\FeeBalance;
use App\FeePaid;
use App\Admission;
use App\Student;
use App\ParentRelationship;
use App\StudentParent;
use App\Attendance;
use App\AttendanceStatus;
use App\Homework;

class SiteController extends Controller
{
    public function parents(Request $request)
    {
    	$data['search']=0;
    	if($request->search){
    		$student=Student::where('code', $request->code)
    			->with('admission')
    			->first();
    		if(isset($student->id)){
    			$data['student']=$student;
    			$data['homework']=Homework::where('school_id', $student->school_id)
	        						->where('academic_session_id', $student->admission->academic_session_id)
	        						->where('school_class_id', $student->admission->school_class_id)
	        						->where('section_id', $student->admission->section_id)
	            					->orderBy('id', 'desc')
	            					->get();
	            $data['search']=1;
    		}
    		else{
    			Helper::setAdminMessage('No record found', 'danger');
	        	return redirect()->back();
    		}
    	}
    	return view('site.parents', $data);
    }
}
