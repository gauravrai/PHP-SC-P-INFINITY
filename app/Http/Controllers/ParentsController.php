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


class ParentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }
	public function fetchStudent(){
		return Auth::guard('student')->user();
    }
	public function homework(Request $request)
    {
    	$data['student']=$student=$this->fetchStudent();
    	$admission=Admission::where('student_id', $student->id)->first();
    	$data['side_main']='parents';
        $data['side_sub']='homework';
        
    	$data['homework']=Homework::where('school_id', $student->school_id)
    						->where('academic_session_id', $admission->academic_session_id)
    						->where('school_class_id', $admission->school_class_id)
    						->where('section_id', $admission->section_id)
        					->orderBy('id', 'desc')
        					->get();

    	return view('admin.parents_homework_list', $data);
    }
    public function attendance(Request $request)
    {
    	$data['student']=$student=$this->fetchStudent();
        $data['side_main']='parents';
        $data['side_sub']='attendance';
         $data['month']=$data['year']=$data['academic_session_id']=$data['school_class_id']=$data['section_id']=$data['search']=0;
        $data['search']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->submit){
            $data['search']=1;
        	$data['year']=$request->year;
        	$data['month']=$request->month;
        	$data['date']=$data['year'] . "-" . $data['month'] . '-01';
        }
        
        

        //return $data;
        return view('admin.parents_attendance', $data);
    }
}
