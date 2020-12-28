<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Helper;
use App\User;
use App\AcademicSession;
use App\Section;
use App\SchoolClass;
use App\Department;
use App\Subject;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $data['side_main']='settings';
        $data['side_sub']='subjects';

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            Subject::where('id', $id)
                ->delete();
            Helper::setAdminMessage('Subject deleted successfully', 'success');
            return redirect('admin/subjects');
        }
        if($request->submit){
            if($id){
                Subject::where('id', $id)
                        ->update([
                            'name'=>$request->name
                        ]);
                $obj=Subject::find($id);
                $obj->school_classes()->sync($request->school_class_id);
                Helper::setAdminMessage('Subject updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                ]);

                $obj= new Subject;
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->name;
                $obj->save();
                $last_id=$obj->id;
                if($last_id){
                    $obj->school_classes()->attach($request->school_class_id);

                }
                Helper::setAdminMessage('Subject added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['subject']=Subject::where('id', $id)
                                    ->first();
        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['teachers']=User::whereHas("roles", function($q){ $q->where("name", "Teacher"); })->get();
        $data['subjects']=Subject::where('school_id', Auth::user()->school_id)->with('teachers')->get();
        //return $data;
        return view('admin.manage_subjects', $data);
    }
}
