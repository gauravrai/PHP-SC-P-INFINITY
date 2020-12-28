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
use App\Designation;
use App\Staff;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        //return Auth::user()->roles()->pluck('id');
        $data['side_main']='settings';
        $data['side_sub']='staff';

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
        if($request->submit){

            if($id){
                $validatedData = $request->validate([
                    'name' => 'required|max:255|unique:users,email,'.$id,
                    'email' => 'required|max:255',
                    'phone' => 'required|max:10',
                    'department_id' => 'required',
                    'designation_id' => 'required',
                ]);
                if($request->password && $request->password!=''){
                    $password=bcrypt($request->password);
                }
                else{
                    $password=$request->old_password;
                }
                User::where('id', $id)
                        ->update([
                            'name'=>$request->name,
                            'email'=>$request->email,
                            'password'=>$password
                        ]);


                Staff::where('user_id', $id)
                    ->update([
                        'department_id'=>$request->department_id,
                        'designation_id'=>$request->designation_id,
                        'phone'=>$request->phone,
                    ]);

                $user=User::find($id);
                $roles=$request->role_id;
                $user->syncRoles($roles);

                Helper::setAdminMessage('Staff updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required|max:255|unique:users,email',
                    'email' => 'required|max:255',
                    'phone' => 'required|max:10',
                    'department_id' => 'required',
                    'designation_id' => 'required',
                    'password' => 'required',
                ]);
                $obj= new User;
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->name;
                $obj->email=$request->email;
                $obj->password=bcrypt($request->password);
                $obj->save();

                $last_id=$obj->id;
                if($last_id){
                    $obj= new Staff;
                    $obj->school_id=Auth::user()->school_id;
                    $obj->phone=$request->phone;
                    $obj->user_id=$last_id;
                    $obj->department_id=$request->department_id;
                    $obj->designation_id=$request->designation_id;
                    $obj->save();

                    $user=User::find($last_id);
                    $roles=$request->role_id;
                    $user->assignRole($roles);

                    //$obj->school_classes()->attach($request->school_classes);
                }
                Helper::setAdminMessage('Staff added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['staff']=Staff::where('user_id', $id)
                                    ->with(['user', 'department', 'designation'])
                                    ->first();
            $user=User::find($id);
            $data['role_ids']=$user->roles()->pluck('id')->toArray();
        }
        $data['school_classes']=SchoolClass::where('school_id', Auth::user()->school_id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        $data['records']=Staff::with(['user', 'department', 'designation'])
                            ->where('school_id', Auth::user()->school_id)
                            ->get();
        $data['departments']=Department::where('school_id', Auth::user()->school_id)->get();
        $data['designations']=Designation::where('school_id', Auth::user()->school_id)->get();
        $data['roles']=Role::orderBy('name', 'asc')->get();
//        return $data;
        return view('admin.manage_staff', $data);
    }
}
