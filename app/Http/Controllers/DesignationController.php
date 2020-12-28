<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Helper;
use App\AcademicSession;
use App\Section;
use App\SchoolClass;
use App\Designation;


class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $data['side_main']='settings';
        $data['side_sub']='designation';

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            Designation::where('id', $id)
                ->delete();
            Helper::setAdminMessage('Designation deleted successfully', 'success');
            return redirect('admin/designation');
        }
        if($request->submit){
            if($id){
                Designation::where('id', $id)
                        ->update([
                            'name'=>$request->name
                        ]);
                Helper::setAdminMessage('Designation updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                ]);

                $obj= new Designation;
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->name;
                $obj->save();

                Helper::setAdminMessage('Designation added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['designation']=Designation::where('id', $id)
                                    ->first();
        }

        $data['designations']=Designation::where('school_id', Auth::user()->school_id)->get();
        //return $data;
        return view('admin.manage_designations', $data);
    }
}
