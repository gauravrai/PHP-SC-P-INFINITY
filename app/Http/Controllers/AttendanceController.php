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


class AttendanceController extends Controller
{
    public function attendance_insight(Request $request){
    	if($request->submit){
        	$data['search']=1;
        	$data['year']=$request->year;
        	$data['month']=$request->month;
        	$data['date']=$data['year'] . "-" . $data['month'] . '-01';
        	$data['academic_session_id']=$request->academic_session_id;
            $data['school_class_id']=$request->school_class_id;
            $data['section_id']=$request->section_id;
            
        	$data['students']=Student::where('school_id', Auth::user()->school_id)
                                ->whereHas('admission', function($q) use($request){
                                    $q->where('school_id', Auth::user()->school_id);
                                    if($request->academic_session_id)
                                        $q->where('academic_session_id', $request->academic_session_id);
                                    if($request->school_class_id)
                                        $q->where('school_class_id', $request->school_class_id);
                                    if($request->section_id)
                                        $q->where('section_id', $request->section_id);
                                })
                                ->orderBy('gender', 'desc')
                                ->orderBy('name', 'asc')
            					->get();
            $data['school_class']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->where('id', $data['school_class_id'])
                                    ->first();

	        $data['academic_sessions']=AcademicSession::where('school_id', Auth::user()->school_id)->orderBy('name', 'desc')->get();
	        $data['section']=Section::where('school_id', Auth::user()->school_id)
	        						->where('id', $data['section_id'])
	        						->first();
	    	return view('admin.attendance_insight', $data);

        }
    }
    public function attendance_list(Request $request)
    {
    	$data['side_main']='attendance';
        $data['side_sub']='attendance-list';

        $data['month']=$data['year']=$data['academic_session_id']=$data['school_class_id']=$data['section_id']=$data['search']=0;
        if($request->submit){
        	$data['search']=1;
        	$data['year']=$request->year;
        	$data['month']=$request->month;
        	$data['date']=$data['year'] . "-" . $data['month'] . '01';
        	$data['academic_session_id']=$request->academic_session_id;
            $data['school_class_id']=$request->school_class_id;
            $data['section_id']=$request->section_id;
        	$data['students']=Student::where('school_id', Auth::user()->school_id)
            					->whereHas('admission', function($q) use($request){
                                    $q->where('school_id', Auth::user()->school_id);
                                    if($request->academic_session_id)
                                        $q->where('academic_session_id', $request->academic_session_id);
                                    if($request->school_class_id)
                                        $q->where('school_class_id', $request->school_class_id);
                                    if($request->section_id)
                                        $q->where('section_id', $request->section_id);
                                })
                                ->orderBy('gender', 'desc')
                                ->orderBy('name', 'asc')
            					->get();

        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['academic_sessions']=AcademicSession::where('school_id', Auth::user()->school_id)->orderBy('name', 'desc')->get();
        $data['sections']=[];
    	return view('admin.attendance_list', $data);
    }
    public function index(Request $request)
    {
    	$data['code']=Helper::studentID(Auth::user()->school_id);
        $data['side_main']='attendance';
        $data['side_sub']='attendance';

        $data['search']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->search){
            $data['search']=1;
            $data['for_date']=$request->for_date;
            $data['school_class_id']=$request->school_class_id;
            $data['section_id']=$request->section_id;
            $data['section']=Section::where('id', $data['section_id'])->first();

            $data['students_with']=Student::where('school_id', Auth::user()->school_id)
                                    ->whereHas('admission', function($q) use($request){
                                        $q->where('school_id', Auth::user()->school_id);
                                        if($request->academic_session_id)
                                            $q->where('academic_session_id', $request->academic_session_id);
                                        if($request->school_class_id)
                                            $q->where('school_class_id', $request->school_class_id);
                                        if($request->section_id)
                                            $q->where('section_id', $request->section_id);
                                    })
                                    ->orderBy('gender', 'desc')
                                    ->orderBy('name', 'asc')
            						->get();
            $data['students_without']=Student::where('school_id', Auth::user()->school_id)
            					->with(['attendance'])
                                ->whereHas('admission', function($q) use($request){
                                    $q->where('school_id', Auth::user()->school_id);
                                    if($request->academic_session_id)
                                        $q->where('academic_session_id', $request->academic_session_id);
                                    if($request->school_class_id)
                                        $q->where('school_class_id', $request->school_class_id);
                                    if($request->section_id)
                                        $q->where('section_id', $request->section_id);
                                })
            					->whereDoesntHave('attendance', function($q) use ($data){
								    $q->where('for_date', date("Y-m-d", strtotime($data['for_date'])));
								})
                                ->orderBy('gender', 'desc')
                                ->orderBy('name', 'asc')
            					->get();
            if($request->submit){

            	$for_date=date("Y-m-d", strtotime($request->for_date));
            	//return $request->all();
            	if($request->student && count($request->student)){
            		foreach ($request->student as $s){
            			if(Attendance::where('student_id', $s)
            				->where('for_date', $for_date)
            				->get()
            				->count()==0){
            				$obj=new Attendance;
            				$obj->academic_session_id=$request->academic_session_id;
            				$obj->school_id=Auth::user()->school_id;
            				$obj->student_id=$s;
            				$obj->for_date=$for_date;
            				$obj->school_class_id=$request->school_class_id;
            				$obj->section_id=$request->section_id;
            				$obj->attendance_status_id=$request->status;
            				$obj->save();
						}
            		}
            		Helper::setAdminMessage('Attendance added successfully', 'success');
            		return redirect()->back();
            	}
            }
        }
        
        
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();

        $data['academic_sessions']=AcademicSession::where('school_id', Auth::user()->school_id)->orderBy('name', 'desc')->get();
        $data['attendance_statuses']=AttendanceStatus::orderBy('name', 'asc')->get();
        

        //return $data;
        return view('admin.attendance', $data);
    }

}
