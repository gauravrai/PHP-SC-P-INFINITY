<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Helper;
use App\AcademicSession;
use App\Section;
use App\SchoolClass;
use App\Department;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $data['side_main']='settings';
        $data['side_sub']='department';

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            Department::where('id', $id)
                ->delete();
            Helper::setAdminMessage('Department deleted successfully', 'success');
            return redirect('admin/department');
        }
        if($request->submit){
            if($id){
                Department::where('id', $id)
                        ->update([
                            'name'=>$request->name
                        ]);
                Helper::setAdminMessage('Department updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                ]);

                $obj= new Department;
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->name;
                $obj->save();

                Helper::setAdminMessage('Department added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['department']=Department::where('id', $id)
                                    ->first();
        }
        $data['departments']=Department::where('school_id', Auth::user()->school_id)->get();
        //return $data;
        return view('admin.manage_departments', $data);
    }
}
