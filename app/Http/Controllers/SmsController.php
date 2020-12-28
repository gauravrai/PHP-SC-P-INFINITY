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
use App\SmsCategory;
use App\Sms;
use App\SmsDetail;
use App\SmsPromotion;
use App\SmsPromotionDetail;


class SmsController extends Controller
{
	public function sendMessage($sms_sender_id, $template, $reciverNumber){
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://alotsolutions.in/API/WebSMS/Http/v1.0a/index.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=som.kiransom&password=Bsa@1234&sender=".$sms_sender_id."&to=" . $reciverNumber . "&message= " . $template . "");

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
       //$response =  $server_output;

        if(isset($server_output) && !empty($server_output)){
            return 1;
        }else{
            return 0;
        }
    }

    
    public function sms_details(Request $request)
    {
    	$data['side_main']='transactional';
        $data['side_sub']='sms-list';
        $id=$request->id;

        $id=decrypt($id);
        if(!$id)
        	exit;

        $data['search']=0;
    	$data['search']=1;
    	$data['sms']=Sms::where('id', $id)
    					->with('sms_details')
    					->first();

    	return view('admin.sms_details', $data);
    }
    public function sms_list(Request $request)
    {
    	$data['side_main']='transactional';
        $data['side_sub']='transactional-list';

        $data['search']=0;
        if($request->submit){
        	$dates=explode('-', $request->date_range);
        	$date_from=date("Y-m-d", strtotime(trim($dates[0])));
        	$date_to=date("Y-m-d", strtotime(trim($dates[1]) . " +1 day"));

        	$data['search']=1;
        	$data['sms']=Sms::where('school_id', Auth::user()->school_id)
        					->with('sms_details')
        					->whereBetween('created_at', [$date_from, $date_to])
        					->get();

        }
    	return view('admin.sms_list', $data);
    }
    
    public function index(Request $request)
    {
    	$data['code']=Helper::studentID(Auth::user()->school_id);
        $data['side_main']='transactional';
        $data['side_sub']='transactional-add';

        $data['section_id']=$data['school_class_id']=$data['edit']=$id=0;
        if($request->id){
            
            $id=decrypt($request->id);
        }
        if($request->search_btn){
        	$data['edit']=1;
        	$data['section_id']=$request->section_id;
        	if($data['section_id']){
        		$data['section']=Section::where('id', $data['section_id'])->first();
        	}
        	$data['school_class_id']=$request->school_class_id;
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
            					->get();
        }
        if($request->submit){
            $validatedData = $request->validate([
                'content' => 'required',
                'school_class_id' => 'required',
                'student' => 'required',
            ]);

            $obj= new Sms;
            $obj->school_id=Auth::user()->school_id;
            $obj->content=$request->content;
            $obj->sms_category_id=1;// 1 is homework
            $obj->message_size=strlen($request->content);
            $obj->save();

            $last_id=$obj->id;
            if($last_id){
            	for($p=0; $p<count($request->student); $p++){
            		$sp=StudentParent::where('student_id', $request->student[$p])->first();
            		if(SmsDetail::where('sms_id', $last_id)->where('contact_number', $sp->phone)->get()->count()==0){
	                    $obj= new SmsDetail;
	                    $obj->sms_id=$last_id;
	                    $obj->contact_number=$sp->phone;
	                    $obj->save();
	                }
                }
            }

            Helper::setAdminMessage('SMS added successfully', 'success');
            return redirect()->back();
            
        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['academic_sessions']=AcademicSession::get();
        //return $data;
        return view('admin.sms', $data);
    }
    public function sms_promotional_list(Request $request)
    {
        $data['side_main']='promotional';
        $data['side_sub']='promotional-list';

        $data['search']=0;
        if($request->submit){
            $dates=explode('-', $request->date_range);
            $date_from=date("Y-m-d", strtotime(trim($dates[0])));
            $date_to=date("Y-m-d", strtotime(trim($dates[1]) . " +1 day"));

            $data['search']=1;
            $data['sms']=SmsPromotion::where('school_id', Auth::user()->school_id)
                            ->with('sms_details')
                            ->whereBetween('created_at', [$date_from, $date_to])
                            ->get();

        }
        return view('admin.sms_promotional_list', $data);
    }
    public function sms_promotional_add(Request $request)
    {
        $data['code']=Helper::studentID(Auth::user()->school_id);
        $data['side_main']='promotional';
        $data['side_sub']='promotional-add';


        if($request->submit){
            $contact_numbers=explode(PHP_EOL, $request->contact_numbers);
            if(count($contact_numbers)==0){
                Helper::setAdminMessage('No numbers found', 'danger');
                return redirect()->back();
            }
            $validatedData = $request->validate([
                'content' => 'required',
                'contact_numbers' => 'required',
            ]);

            $obj= new SmsPromotion;
            $obj->school_id=Auth::user()->school_id;
            $obj->content=$request->content;
            $obj->message_size=strlen($request->content);
            $obj->save();

            $last_id=$obj->id;
            if($last_id){
                $data_array=[];
                for($p=0; $p<count($contact_numbers); $p++){
                    $data_array[]=[
                        'sms_promotion_id'=>$last_id,
                        'contact_number'=>$contact_numbers[$p],
                    ];
                }
                SmsPromotionDetail::insert($data_array);
            }

            Helper::setAdminMessage('SMS added successfully', 'success');
            return redirect()->back();
            
        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['academic_sessions']=AcademicSession::get();
        //return $data;
        return view('admin.sms_promotional_add', $data);
    }
    public function sms_promotional_details(Request $request)
    {
        $data['side_main']='promotional';
        $data['side_sub']='promotional-list';
        $id=$request->id;

        $id=decrypt($id);
        if(!$id)
            exit;

        $data['search']=0;
        $data['search']=1;
        $data['sms']=SmsPromotion::where('id', $id)
                        ->with('sms_details')
                        ->first();

        return view('admin.sms_promotional_details', $data);
    }
}
