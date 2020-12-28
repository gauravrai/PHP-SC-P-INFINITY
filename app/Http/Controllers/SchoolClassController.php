<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Helper;
use App\AcademicSession;
use App\Section;
use App\SchoolClass;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $data['side_main']='settings';
        $data['side_sub']='classes';

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            SchoolClass::where('id', $id)
                ->delete();
            Helper::setAdminMessage('Class deleted successfully', 'success');
            return redirect('admin/classes');
        }
        if($request->submit){
            if($id){
                SchoolClass::where('id', $id)
                        ->update([
                            'name'=>$request->name,
                            'sort_order'=>$request->sort_order
                        ]);
                $obj=SchoolClass::find($id);
                $obj->sections()->sync($request->sections);
                Helper::setAdminMessage('Class updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                    'sort_order' => 'required',
                    'sections' => 'required'
                ]);

                $obj= new SchoolClass;
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->name;
                $obj->sort_order=$request->sort_order;
                $obj->save();
                $school_class_id=$obj->id;
                if($school_class_id){
                    $obj->sections()->attach($request->sections);

                }
                Helper::setAdminMessage('Class added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['school_class']=SchoolClass::where('id', $id)
                                    ->with('sections')
                                    ->first();
        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['sections']=Section::where('school_id', Auth::user()->school_id)->get();
        //return $data;
        return view('admin.manage_classes', $data);
    }
    
}
