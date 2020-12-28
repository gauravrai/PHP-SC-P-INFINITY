<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Helper;
use App\AcademicSession;
use App\Section;
use App\SchoolClass;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $data['side_main']='settings';
        $data['side_sub']='sections';

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            Section::where('id', $id)
                ->delete();
            Helper::setAdminMessage('Section deleted successfully', 'success');
            return redirect('admin/sections');
        }
        if($request->submit){
            if($id){
                Section::where('id', $id)
                        ->update([
                            'name'=>$request->name
                        ]);
                $obj=Section::find($id);
                if($last_id && $request->school_classes && count($request->school_classes)>0){
                    $obj->school_classes()->sync($request->school_classes);
                }
                Helper::setAdminMessage('Section updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                ]);

                $obj= new Section;
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->name;
                $obj->save();

                $last_id=$obj->id;
                if($last_id && $request->school_classes && count($request->school_classes)>0){
                    $obj->school_classes()->attach($request->school_classes);

                }
                Helper::setAdminMessage('Section added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['section']=Section::where('id', $id)
                                    ->with('school_classes')
                                    ->first();
        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['sections']=Section::where('school_id', Auth::user()->school_id)->get();
        //return $data;
        return view('admin.manage_sections', $data);
    }
    
}
