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
use App\Homework;

class HomeworkController extends Controller
{
    
    public function homework_list(Request $request)
    {
    	$data['side_main']='homework';
        $data['side_sub']='homework-list';
        $data['isparent']=0;
        $data['month']=$data['year']=$data['academic_session_id']=$data['school_class_id']=$data['section_id']=$data['search']=0;
        if($request->submit){
        	$data['search']=1;
        	$data['year']=$request->year;
        	$data['month']=$request->month;
        	$data['date']=$data['year'] . "-" . $data['month'] . '01';
        	$data['academic_session_id']=$request->academic_session_id;
            $data['school_class_id']=$request->school_class_id;
            $data['section_id']=$request->section_id;
        	$data['homework']=Homework::where('school_id', Auth::user()->school_id)
        						->where('academic_session_id', $data['academic_session_id'])
        						->where('school_class_id', $data['school_class_id'])
        						->where('section_id', $data['section_id'])
            					->orderBy('id', 'desc')
            					->get();

        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['academic_sessions']=AcademicSession::where('school_id', Auth::user()->school_id)->orderBy('name', 'desc')->get();
        $data['sections']=[];
    	return view('admin.homework_list', $data);
    }
    public function index(Request $request)
    {
    	$data['code']=Helper::studentID(Auth::user()->school_id);
        $data['side_main']='homework';
        $data['side_sub']='homework';

        $data['edit']=$data['search']=$id=0;
        if($request->trash){
            $id=decrypt($request->trash);
            Homework::where('id', $id)
                ->delete();
            Helper::setAdminMessage('Homework deleted successfully', 'success');
            return redirect()->back();
        }
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);

            $data['work']=Homework::where('id', $id)->first();
            $data['for_date']=date("d-m-Y", strtotime($data['work']->for_date));
            $data['section']=Section::where('id', $data['work']->section_id)->first();

            if($request->search && $request->update){
            	$for_date=date("Y-m-d", strtotime($request->for_date));
	            Homework::where('id', $id)
	            	->update([
	            		'academic_session_id'=>$request->academic_session_id,
	            		'for_date'=>$for_date,
	            		'school_class_id'=>$request->school_class_id,
	            		'section_id'=>$request->section_id,
	            		'description'=>$request->description
	            	]);
	            Helper::setAdminMessage('Homework updated successfully', 'success');
	        	return redirect()->back();
            }
        }
        else{
	        if($request->search){
	            $data['search']=1;
	            $data['for_date']=$request->for_date;
	            $data['school_class_id']=$request->school_class_id;
	            $data['section_id']=$request->section_id;
	            $data['academic_session_id']=$request->academic_session_id;
	            
	            
	            if($request->submit){
	            	$for_date=date("Y-m-d", strtotime($request->for_date));
	            	//return $request->all();

	            	if(Homework::where('school_id', Auth::user()->school_id)
        						->where('academic_session_id', $data['academic_session_id'])
        						->where('school_class_id', $data['school_class_id'])
        						->where('section_id', $data['section_id'])
        						->where('for_date', $for_date)
            					->count()==0){

						$obj=new Homework;
						$obj->academic_session_id=$request->academic_session_id;
						$obj->school_id=Auth::user()->school_id;
						$obj->for_date=$for_date;
						$obj->school_class_id=$request->school_class_id;
						$obj->section_id=$request->section_id;
						$obj->description=$request->description;
						$obj->save();
								
		        		Helper::setAdminMessage('Homework added successfully', 'success');
		        	}
		        	else{
		        		Helper::setAdminMessage('Homework already exists for date ' . $for_date, 'danger');
		        	}
	        		return redirect()->back();
	            }
	        }
	    }
        
        
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();

        $data['academic_sessions']=AcademicSession::where('school_id', Auth::user()->school_id)->orderBy('name', 'desc')->get();
        

        //return $data;
        return view('admin.homework', $data);
    }
}
