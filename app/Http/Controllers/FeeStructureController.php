<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Helper;
use App\User;
use App\AcademicSession;
use App\Student;
use App\Section;
use App\SchoolClass;
use App\Department;
use App\Designation;
use App\Staff;
use App\FeeType;
use App\Fee;
use App\FeeStructure;

class FeeStructureController extends Controller
{
    public function fee_type(Request $request)
    {
        //return $request->all();
        $data['side_main']='settings';
        $data['side_sub']='fee-type';

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            FeeType::where('id', $id)->delete();
            Helper::setAdminMessage('Fee name deleted successfully', 'success');
            return redirect()->back();
        }
        if($request->submit){
            if($id){
                $validatedData = $request->validate([
                    'name' => 'required|max:255'
                ]);
                if($request->has_concession)
                    $has_concession=1;
                else
                    $has_concession=0;
                if(FeeType::where('has_concession', 1)
                    ->where('id', '!=', $id)
                    ->where('school_id', Auth::user()->school_id)
                    ->count()){
                    Helper::setAdminMessage('You cannot add concession on more than one fee type.', 'danger');
                    return redirect()->back();
                }
                FeeType::where('id', $id)
                	->update([
                		'name'=>$request->name,
                        'has_concession'=>$has_concession
                	]);
                Helper::setAdminMessage('Fee name updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                ]);
                if($request->has_concession)
                    $has_concession=1;
                else
                    $has_concession=0;

                if(FeeType::where('name', $request->name)->where('school_id', Auth::user()->school_id)
                	->count()){
                	Helper::setAdminMessage('Fee name already exists.', 'danger');
                	return redirect()->back();
                }
                elseif(FeeType::where('has_concession', 1)->where('school_id', Auth::user()->school_id)
                    ->count()){
                    Helper::setAdminMessage('You cannot add concession on more than one fee type.', 'danger');
                    return redirect()->back();
                }

                $obj= new FeeType;
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->name;
                $obj->fee_type='academic';
                $obj->has_concession=$has_concession;
                $obj->save();

                
                Helper::setAdminMessage('Fee name added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['fee']=FeeType::where('id', $id)
                                    ->first();
            $data['edit']=1;
        }
        $data['records']=FeeType::where('school_id', Auth::user()->school_id)
                            ->get();
        //return $data;
        return view('admin.manage_fee_type', $data);
    }
    public function index(Request $request)
    {
        /*$last_str=strtotime('next month');
        return $students=Student::where('isactive', 1)
                    ->where('status', 'studying')
                    ->whereDoesntHave('fee_student_record', function($q) use ($last_str){
                        $q->whereMonth('for_month', date('m', $last_str))
                            ->whereYear('for_month', date('Y', $last_str));
                    })
                    ->with(['admission.fee.fee_strucures', 'admission.fee.transport_fee_strucures', 'conveyance'])
                    ->take(1)
                    ->get()
                    ;*/
        $data['side_main']='settings';
        $data['side_sub']='fee-structure';

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            DB::table('fee_school_class')
                ->where('fee_id', $id)
                ->update([
                    'deleted_at'=>date("Y-m-d H:i:s")
                ]);

            FeeStructure::where('fee_id', $id)
                ->delete();

            Fee::where('id', $id)
                ->delete();

            Helper::setAdminMessage('Fee structure deleted successfully', 'success');
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
            $transport=FeeType::where('school_id', Auth::user()->school_id)
                            ->where('fee_type', 'transport')
                            ->first();
            if(!isset($transport->id)){
                Helper::setAdminMessage('Your account does not have transport setup, kindly contact administrator for further details.', 'danger');
                return redirect()->back();
            }

            if($id){
                $validatedData = $request->validate([
                    'academic_session_id' => 'required',
                    'name' => 'required|max:255',
                    'school_class_id' => 'required',
                    'wef' => 'required',
                    'amount' => 'required',
                    'frequency' => 'required',
                    'sort_order' => 'required'
                ]);

                Fee::where('id', $id)
                    ->update([
                        'academic_session_id'=>$request->academic_session_id,
                        'name'=>$request->name,
                        'wef'=>date("Y-m-d", strtotime($request->wef))
                    ]);
                $obj=Fee::find($id);
                $obj->school_classes()->sync($request->school_class_id);

                FeeStructure::where('fee_id', $id)->delete();

                for($p=0; $p<count($request->amount); $p++){
                    if($request->fee_type_id[$p] != '' && $request->amount[$p]>0){
                        $obj= new FeeStructure;
                        $obj->school_id=Auth::user()->school_id;
                        $obj->fee_id=$id;
                        $obj->fee_type_id=$request->fee_type_id[$p];
                        $obj->amount=$request->amount[$p];
                        $obj->frequency=$request->frequency[$p];
                        $obj->sort_order=$request->sort_order[$p];
                        $obj->save();
                    }
                }

                $obj= new FeeStructure;
                $obj->school_id=Auth::user()->school_id;
                $obj->fee_id=$id;
                $obj->fee_type_id=$transport->id;
                $obj->amount=0;
                $obj->frequency='monthly';
                $obj->sort_order=0;
                $obj->save();

                Helper::setAdminMessage('Fee structure updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required',
                    'academic_session_id' => 'required',
                    'fee_type_id' => 'required',
                    'school_class_id' => 'required',
                    'amount' => 'required',
                    'wef' => 'required',
                    'frequency' => 'required',
                    'sort_order' => 'required'
                ]);

                if(DB::table('fee_school_class')->whereIn('school_class_id', $request->school_class_id)
                    ->whereNull('deleted_at')
                    ->count()){
                    Helper::setAdminMessage('There might exist some class or classes for which fee structure is already created, kindly update or remove that class first.', 'danger');
                    return redirect()->back();
                }

                $obj= new Fee;
                $obj->academic_session_id=$request->academic_session_id;
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->name;
                $obj->wef=date("Y-m-d", strtotime($request->wef));
                $obj->save();

                $last_id=$obj->id;
                if($last_id){
                    $obj->school_classes()->attach($request->school_class_id);

                    for($p=0; $p<count($request->amount); $p++){
                        if($request->fee_type_id[$p] != '' && $request->amount[$p]>0){
                            $obj= new FeeStructure;
                            $obj->school_id=Auth::user()->school_id;
                            $obj->fee_id=$last_id;
                            $obj->fee_type_id=$request->fee_type_id[$p];
                            $obj->amount=$request->amount[$p];
                            $obj->frequency=$request->frequency[$p];
                            $obj->sort_order=$request->sort_order[$p];
                            $obj->save();
                        }
                    }
                    //Insert transport fee type
                    $obj= new FeeStructure;
                    $obj->school_id=Auth::user()->school_id;
                    $obj->fee_id=$last_id;
                    $obj->fee_type_id=$transport->id;
                    $obj->amount=0;
                    $obj->frequency='monthly';
                    $obj->sort_order=0;
                    $obj->save();
                }
                Helper::setAdminMessage('Fee structure added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['fee']=Fee::where('id', $id)
                                    ->with(['fee_strucures', 'school_classes'])
                                    ->first();
        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['records']=Fee::with(['fee_strucures', 'school_classes'])
                            ->where('school_id', Auth::user()->school_id)
                            ->get();
        $data['fee_types']=FeeType::where('school_id', Auth::user()->school_id)
                            ->orderBy('name', 'asc')
                            ->where('fee_type', 'academic')
                            ->get();
        $data['academic_sessions']=AcademicSession::where('school_id', Auth::user()->school_id)->orderBy('name', 'desc')->get();
        $data['frequency']=['annually', 'biannually', 'quaterly', 'monthly'];
        //return $data;
        return view('admin.manage_fee_structure', $data);
    }
}
