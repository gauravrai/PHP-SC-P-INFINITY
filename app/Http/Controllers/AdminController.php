<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
use App\FeeConcession;
use App\FeePaid;
use App\Admission;
use App\Student;
use App\ParentRelationship;
use App\StudentParent;
use App\Transporter;
use App\Route;
use App\Conveyance;
use App\Caste;
use App\Attendance;
use App\AttendanceStatus;
use App\Homework;
use App\SmsPromotion;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $school_id=Auth::user()->school_id;
        $data['total_student_count']= Student::where('status', 'studying')
            ->where('isactive', 1)
            ->where('school_id', $school_id)
            ->count();
        if(date("m")<=3){
            $current_financial_year=(date("Y")-1) . date("-y");
        }
        else{
            $current_financial_year=date("Y-") . (date("y")+1);
        }
        
        $data['academic_session']=AcademicSession::where('name', $current_financial_year)
                            ->where('school_id', $school_id)
                            ->first();

        $data['new_admission']= Admission::where('academic_session_id', $data['academic_session']->id)
            ->where('isactive', 1)
            ->count();

        $data['total_classes']= SchoolClass::where('isactive', 1)
            ->where('school_id', $school_id)
            ->count();

        $data['total_staff']= Staff::where('school_id', $school_id)
            ->count();

        $data['fee_balance']=FeeBalance::where('school_id', $school_id)
                    ->where('academic_session_id', $data['academic_session']->id)
                    ->sum('amount');
        $data['fee_paid']=FeePaid::where('school_id', $school_id)
                    ->where('academic_session_id', $data['academic_session']->id)
                    ->sum('amount');
        
        $data['present_count']=Attendance::where('school_id', $school_id)
                                    ->where('academic_session_id', $data['academic_session']->id)
                                    ->where('for_date', date('Y-m-d'))
                                    ->where('attendance_status_id', 1)
                                    ->count();


        $data['absent_count']=Attendance::where('school_id', $school_id)
                                    ->where('academic_session_id', $data['academic_session']->id)
                                    ->where('for_date', date('Y-m-d'))
                                    ->where('attendance_status_id', 2)
                                    ->count();


        $data['leave_count']=Attendance::where('school_id', $school_id)
                                    ->where('academic_session_id', $data['academic_session']->id)
                                    ->where('for_date', date('Y-m-d'))
                                    ->where('attendance_status_id', 3)
                                    ->count();

        $data['defaulter_id']=DB::raw("SELECT *, sum(fb.amount) - sum(fp.amount) as dif FROM `fee_balances` as fb, fee_paids as fp where fp.student_id=fb.student_id order by dif asc LIMIT 0,5");

        return view('admin.home', $data);
    }
    public function student_action_save(Request $request)
    {
        if($request->take_action){
            //return $request->all();
            $class=SchoolClass::where('id', $request->school_class_id)
                            ->with('fees')
                            ->first();
            $fee_id=$class->fees[0]->id;
            $check=Admission::whereIn('student_id', $request->student_id)
                ->where('academic_session_id', $request->academic_session_id)
                ->get()
                ->count();
            if(!$check){
                for($i=0;$i<count($request->student_id);$i++){
                    $array[]=[
                        'academic_session_id'=>$request->academic_session_id,
                        'school_class_id'=>$request->school_class_id,
                        'section_id'=>$request->section_id,
                        'school_id'=>Auth::user()->school_id,
                        'fee_id'=>$fee_id,
                        'student_id'=>$request->student_id[$i]
                    ];
                }
                if($i>0){
                    if($request->action=='promote'){
                        Admission::whereIn('student_id', $request->student_id)
                            ->update([
                                'status'=>1
                            ]);
                    }
                    Admission::insert($array);
                    Helper::setAdminMessage('Students updated successfully', 'success');
                    
                }
            }
        }
        return redirect()->back();
    }
    public function student_action(Request $request)
    {
        $data['side_main']='student';
        $data['side_sub']='student-list';

        $data['academic_session_id']=$data['school_class_id']=$data['section_id']=$data['search']=0;
        if($request->submit){
            $data['search']=1;
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
                                ->with(['parents', 'admission'])
                                ->get();

        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['academic_sessions']=AcademicSession::where('school_id', Auth::user()->school_id)->orderBy('name', 'desc')->get();
        $data['sections']=Section::where('school_id', Auth::user()->school_id)->orderBy('name', 'asc')->get();
        return view('admin.manage_student_action', $data);
    }
    public function spatie(Request $request){
        exit;
        $st=Student::get();
        foreach ($st as $s) {
            $user=Student::find($s->id);
            $user->assignRole('Parents');
        }
        
        exit;
        $role = Role::create(['guard_name' => 'student', 'name' => 'Parents']);
        exit;
        $role_array=[
            'Super Admin',
            'Admin',
            'Teacher',
            'Admission Team',
            'Accounts',
            //'Parents'
        ];
        $permission_array=[
            [
                'Settings',
                'Attendance',
                'Homework',
                'SMS', 
                'Student',
                'Student Add',
                'Student Fee',
                'Student Edit'
            ],
            [
                'Attendance',
                'Homework',
                'SMS', 
                'Student',
                'Student Add',
                'Student Fee',
                'Student Edit'
            ],
            [
                'Attendance',
                'Homework',
                'Student'
            ],
            [
                'Student',
                'Student Add'
            ],
            [
                'Student Fee'
            ]
            
        ];

        if($request->step1){
            for($p=0;$p<count($role_array);$p++){
                $role_name=$role_array[$p];
                $role = Role::create(['name'=>$role_name]);
            }
            for($p=0;$p<count($permission_array);$p++){
                if($p>0)
                    continue;
                $p_array=$permission_array[$p];
                for($j=0;$j<count($p_array);$j++){
                    $p_name=$p_array[$j];
                    $permission = Permission::create(['name'=>$p_name]);
                }

            }
        }
        if($request->step2){
            for($p=0;$p<count($role_array);$p++){
                $role_name=$role_array[$p];
                $role = Role::findByName($role_name);
                //dd($role);
                //for($k=0;$k<count($permission_array[$p]);$k++){
                    $p_array=$permission_array[$p];
                    //print_r($p_array);
                    for($j=0;$j<count($p_array);$j++){
                        $p_name=$p_array[$j];
                        //echo "<br>-->".$p_name;
                        $role->givePermissionTo($p_name);

                    }                    
                //}
                //exit;
            }
        }
        
        if($request->step3){
            $user=Auth::user();
            $user->assignRole('Super Admin');
        }

    }
    public function create_user(Request $request){
        if($request->step3){
            /*
            $values = [
                    'code' => 'JDIC',
                    'sms_sender_id' => 'JDRPIC',
                    'name' => 'Janak Dulari Inter College',
                    'address_line_1' => '1',
                    'address_line_2' => 'Aung',
                    'address_line_3' => 'Fatehpur',
                    'pin_code' => '36010',
                    'landline' => '0532-95586',
                    'phone' => '7007961315',
                ];
            $values = [
                    'code' => 'JDIC',
                    'sms_sender_id' => 'JDRPIC',
                    'name' => 'Ascent Public School',
                    'address_line_1' => '2',
                    'address_line_2' => 'Aung',
                    'address_line_3' => 'Fatehpur',
                    'pin_code' => '36010',
                    'landline' => '0532-95586',
                    'phone' => '6393107150',
                ];
            DB::table('schools')->insert($values);
            $school_id=DB::getPdo()->lastInsertId();
            $values = [
                        'school_id'=>$school_id,
                        'name' => 'Dhirendra Patel',
                        'email' => 'dhirendra@gmail.com',
                        'password'=>bcrypt('123456')
                    ];
            $values = [
                        'school_id'=>$school_id,
                        'name' => 'Kshatrapal Verma',
                        'email' => 'kshatrapal@gmail.com',
                        'password'=>bcrypt('123456')
                    ];
            DB::table('users')->insert($values);
            $user_id=DB::getPdo()->lastInsertId();
            $values = [
                        'school_id'=>$school_id,
                        'name' => '2019-20',
                    ];
            DB::table('academic_sessions')->insert($values);
            */

            //$user_id=41;
            //$user=User::find($user_id);
            //$user->assignRole('Super Admin');
        }
    }
    

    public function student_fee_receipt(Request $request)
    {

        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }

        if(!$id)
            exit;
        
        $data['fee']=FeePaid::where('id', $id)
                                ->with(['student'])
                                ->first();
        return view('admin.manage_student_fee_receipt', $data);
    }
    public function student_fee(Request $request)
    {
        $data['side_main']='student';
        $data['side_sub']='student-list';

        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }

        if(!$id)
            exit;
        if($request->btn){
            $s=Student::where('isactive', 1)
                    ->where('status', 'studying')
                    ->where('id', $id)
                    ->first();

            $obj=new FeePaid;
            $obj->school_id=$s->school_id;
            $obj->student_id=$s->id;
            $obj->academic_session_id=$s->admission->academic_session_id;
            $obj->school_class_id=$s->admission->school_class_id;
            $obj->mode=$request->mode;
            $obj->amount=$request->amount;
            $obj->save();
            
            Helper::setAdminMessage('Fee added successfully', 'success');
            return redirect()->back();
        }
        $data['student']=Student::where('id', $id)
                            ->with(['parents', 'admission', 'fee_balances', 'fee_paid'])
                            ->first();

        return view('admin.manage_student_fee', $data);
    }
    public function cron(){
        $last_str=strtotime('next month');

        $students=Student::where('isactive', 1)
                    ->where('status', 'studying')
                    ->whereDoesntHave('fee_student_record', function($q) use ($last_str){
                        $q->whereMonth('for_month', date('m', $last_str))
                            ->whereYear('for_month', date('Y', $last_str));
                    })
                    ->with('admission.fee.fee_strucures')
                    ->take(1)
                    ->get()
                    ;
                    
        foreach ($students as $s) {
            foreach ($s->admission->fee->fee_strucures as $f){
                $sum=0;
                if($f->frequency=='daily'){
                    $number_of_days=date('t', $last_str);
                    $sum=$f->amount*$number_of_days;
                }
                else if($f->frequency=='weekly'){
                    $sum=$f->amount*4;
                }
                else if($f->frequency=='monthly'){
                    if(FeeBalance::where('academic_session_id', $s->admission->academic_session_id)
                        ->where('school_id', $s->school_id)
                        ->where('student_id', $s->id)
                        ->whereMonth('for_month', date('m', $last_str))
                        ->where('fee_structure_id', $f->id)
                        ->get()
                        ->count()==0){
                        $sum=$f->amount;
                    }
                }
                else if($f->frequency=='quaterly'){
                    if(date('m', $last_str)==1 || date('m', $last_str)==4 || date('m', $last_str)==7 || date('m', $last_str)==10){
                        if(FeeBalance::where('academic_session_id', $s->admission->academic_session_id)
                            ->where('school_id', $s->school_id)
                            ->where('student_id', $s->id)
                            ->whereMonth('for_month', date('m', $last_str))
                            ->where('fee_structure_id', $f->id)
                            ->get()
                            ->count()==0){
                            $sum=$f->amount;
                        }
                    }
                }
                else if($f->frequency=='biannually'){
                    if(date('m', $last_str)==1 || date('m', $last_str)==6){
                        if(FeeBalance::where('academic_session_id', $s->admission->academic_session_id)
                            ->where('school_id', $s->school_id)
                            ->where('student_id', $s->id)
                            ->whereMonth('for_month', date('m', $last_str))
                            ->where('fee_structure_id', $f->id)
                            ->get()
                            ->count()==0){
                            if(date('m', $last_str)==1 || date('m', $last_str)==6){
                                $sum=$f->amount;
                            }
                        }
                    }
                }
                else if($f->frequency=='annually'){
                    if(FeeBalance::where('academic_session_id', $s->admission->academic_session_id)
                        ->where('school_id', $s->school_id)
                        ->where('student_id', $s->id)
                        ->where('frequency', 'annually')
                        ->where('fee_structure_id', $f->id)
                        ->get()
                        ->count()==0){
                        $sum=$f->amount;
                    }
                }

                if($sum){
                    $obj= new FeeBalance;
                    $obj->school_id=$s->school_id;
                    $obj->student_id=$s->id;
                    $obj->fee_structure_id=$f->id;
                    $obj->academic_session_id=$s->admission->academic_session_id;
                    $obj->school_class_id=$s->admission->school_class_id;
                    $obj->for_month=date('Y-m-01', $last_str);
                    $obj->name=$f->name;
                    $obj->amount=$sum;
                    $obj->frequency=$f->frequency;
                    $obj->save();
                }
            }
            $obj= new FeeStudentRecord;
            $obj->school_id=$s->school_id;
            $obj->student_id=$s->id;
            $obj->academic_session_id=$s->admission->academic_session_id;
            $obj->school_class_id=$s->admission->school_class_id;
            $obj->for_month=date('Y-m-01', $last_str);
            $obj->save();
        }
        //exit;
    }
    public function student_view(Request $request)
    {
        $data['side_main']='student';
        $data['side_sub']='student-list';

        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }

        if(!$id)
            exit;

        $data['student']=Student::where('id', $id)
                ->with(['parents', 'admission'])
                ->first();

        return view('admin.manage_student_view', $data);
    }
    public function student_list(Request $request)
    {
        $data['side_main']='student';
        $data['side_sub']='student-list';

        $data['academic_session_id']=$data['school_class_id']=$data['section_id']=$data['search']=0;
        if($request->submit){
            $data['search']=1;
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
                                ->with(['parents', 'admission'])
                                ->get();

        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['academic_sessions']=AcademicSession::where('school_id', Auth::user()->school_id)->orderBy('name', 'desc')->get();
        $data['sections']=Section::where('school_id', Auth::user()->school_id)->orderBy('name', 'asc')->get();
        return view('admin.manage_student_list', $data);
    }
    public function manage_student(Request $request)
    {
        $data['code']=Helper::studentID(Auth::user()->school_id);
        $data['side_main']='student';
        $data['side_sub']='student';

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            Staff::where('user_id', $id)->delete();
            User::where('id', $id)->delete();
            Helper::setAdminMessage('Staff deleted successfully', 'success');
            return redirect()->back();
        }
        if($request->trash_structure){
            $id=decrypt($request->trash_structure);
            FeeStructure::where('id', $id)
                ->delete();
            Helper::setAdminMessage('Structure deleted successfully', 'success');
            return redirect()->back();
        }
        if($request->submit){

            if($id){
                $validatedData = $request->validate([
                    'academic_session_id' => 'required',
                    'student_name' => 'required|max:255',
                    'school_class_id' => 'required',
                    'section_id' => 'required',
                    'dob' => 'required',
                    'gender' => 'required',
                    'address_line_1' => 'required'
                ]);
                $student=Student::where('id', $id)->first();
                $imageName=$student->profile;
                if($request->profile){

                    if(file_exists(public_path('student-profile/' . $imageName))){
                        unlink(public_path('student-profile/' . $imageName));
                    }

                    $request->validate([
                        'profile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    ]);
                    $imageName = $data['code'].'.'.$request->profile->getClientOriginalExtension();
                    $request->profile->move(public_path('student-profile'), $imageName);
                }
                
                Student::where('id', $id)
                    ->update([
                        'name'=>$request->student_name,
                        'aadhaar'=>$request->aadhar,
                        'dob'=>Helper::formatDate($request->dob, 1),
                        'profile'=>$imageName,
                        'gender'=>$request->gender,
                        'caste_id'=>$request->caste_id,
                        'board_code'=>$request->board_code,
                        'address_line_1'=>$request->address_line_1,
                        'address_line_2'=>$request->address_line_2,
                        'address_line_3'=>$request->address_line_3,
                        'pin_code'=>$request->pin_code,
                    ]);
                if($request->conveyance_id){
                    $obj=Student::find($id);
                    $obj->conveyance()->sync($request->conveyance_id);
                }
                for($p=0; $p<count($request->parent_id); $p++){
                    StudentParent::where('id', $request->parent_id[$p])
                        ->update([
                            'name'=>$request->name[$p],
                            'email'=>$request->email[$p],
                            'phone'=>$request->phone[$p]
                        ]);
                }
                Admission::where('student_id', $id)
                    ->where('isactive', 1)
                    ->update([
                        'school_class_id'=>$request->school_class_id,
                        'section_id'=>$request->section_id,
                        'fee_id'=>$request->fee_id,
                    ]);

                if($request->has_concession){
                    FeeConcession::updateOrCreate([
                        'school_id'=>Auth::user()->school_id,
                        'student_id'=>$id
                    ],[
                        'school_id'=>Auth::user()->school_id,
                        'student_id'=>$id,
                        'amount'=>$request->amount
                    ]);
                    
                }
                else{

                    FeeConcession::where('school_id', Auth::user()->school_id)
                        ->where('student_id', $id)
                        ->delete();
                    ;
                }

                Helper::setAdminMessage('Student record updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'academic_session_id' => 'required',
                    'student_name' => 'required|max:255',
                    'school_class_id' => 'required',
                    'section_id' => 'required',
                    'dob' => 'required',
                    'gender' => 'required',
                    'board_code' => 'required|unique:students',
                    'address_line_1' => 'required'
                ]);

                if($request->profile){
                    $request->validate([
                        'profile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    ]);
                    $imageName = $data['code'].'.'.$request->profile->getClientOriginalExtension();
                    $request->profile->move(public_path('student-profile'), $imageName);
                }
                else{
                    if($request->gender=='male')
                        $imageName='avatar.png';
                    else
                        $imageName='avatar2.png';
                }

                $obj= new Student;
                
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->student_name;
                $obj->password=bcrypt(123456);
                $obj->code=$data['code'];
                $obj->aadhaar=$request->aadhar;
                $obj->board_code=$request->board_code;
                $obj->dob=Helper::formatDate($request->dob, 1);
                $obj->gender=$request->gender;
                $obj->profile=$imageName;
                $obj->address_line_1=$request->address_line_1;
                $obj->caste_id=$request->caste_id;
                $obj->address_line_2=$request->address_line_2;
                $obj->address_line_3=$request->address_line_3;
                $obj->pin_code=$request->pin_code;
                $obj->save();

                $last_id=$obj->id;
                if($last_id){
                    if($request->conveyance_id){
                        $obj->conveyance()->attach($request->conveyance_id);
                    }
                    for($p=0; $p<count($request->name); $p++){
                        $obj= new StudentParent;
                        $obj->student_id=$last_id;
                        $obj->parent_relationship_id=$request->parent_relationship_id[$p];
                        $obj->name=$request->name[$p];
                        $obj->email=$request->email[$p];
                        $obj->phone=$request->phone[$p];
                        $obj->save();
                    }
                }
                $obj= new Admission;
                $obj->student_id=$last_id;
                $obj->school_class_id=$request->school_class_id;
                $obj->section_id=$request->section_id;
                $obj->academic_session_id=$request->academic_session_id;
                $obj->school_id=Auth::user()->school_id;
                $obj->fee_id=$request->fee_id;
                $obj->save();

                
                $user=Student::find($last_id);
                $user->assignRole('Parents');


                $obj=new FeeConcession;
                $obj->school_id=Auth::user()->school_id;
                $obj->student_id=$last_id;
                $obj->amount=$request->amount;
                $obj->save();


                Helper::setAdminMessage('Student added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['student']=Student::where('id', $id)
                                ->with(['parents', 'admission', 'conveyance', 'fee_concession'])
                                ->first();
        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['fees']=Fee::where('school_id', Auth::user()->school_id)
                            ->where('fee_type', 'academic')
                            ->get();
        $data['castes']=Caste::orderBy('name', 'asc')
                            ->get();
        $data['routes']=Route::where('school_id', Auth::user()->school_id)
                                    ->with('conveyances')
                                    ->orderBy('name', 'asc')
                                    ->get();
        $data['academic_sessions']=AcademicSession::where('school_id', Auth::user()->school_id)->orderBy('name', 'desc')->get();
        $data['parent_relationships']=ParentRelationship::get();
        $data['frequency']=['annually', 'bi-annually', 'quaterly', 'monthly', 'weekly', 'daily'];
        //return $data;
        return view('admin.manage_student', $data);
    }
    
}
