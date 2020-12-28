<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Helper;
use App\AcademicSession;
use App\Section;
use App\SchoolClass;
use App\Transporter;
use App\Route;
use App\Conveyance;


class TransportController extends Controller
{
    public function transporters(Request $request)
    {
        $data['side_main']='settings';
        $data['side_sub']='transporters';

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            Transporter::where('id', $id)
                ->delete();
            Helper::setAdminMessage('Transporter deleted successfully', 'success');
            return redirect('admin/transporters');
        }
        if($request->submit){
            if($id){
                Transporter::where('id', $id)
                        ->update([
                            'name'=>$request->name
                        ]);
                Helper::setAdminMessage('Transporter updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                ]);

                $obj= new Transporter;
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->name;
                $obj->save();

                Helper::setAdminMessage('Transporter added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['transporter']=Transporter::where('id', $id)
                                    ->first();
        }
        $data['transporters']=Transporter::where('school_id', Auth::user()->school_id)->get();
        //return $data;
        return view('admin.manage_transporters', $data);
    }
    public function routes(Request $request)
    {
        $data['side_main']='settings';
        $data['side_sub']='routes';

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            Route::where('id', $id)
                ->delete();
            Helper::setAdminMessage('Route deleted successfully', 'success');
            return redirect()->back();
        }
        if($request->submit){
            if($id){
                Route::where('id', $id)
                        ->update([
                            'name'=>$request->name
                        ]);
                Helper::setAdminMessage('Route updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                ]);

                $obj= new Route;
                $obj->school_id=Auth::user()->school_id;
                $obj->name=$request->name;
                $obj->save();

                Helper::setAdminMessage('Route added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['route']=Route::where('id', $id)
                                    ->first();
        }
        $data['routes']=Route::where('school_id', Auth::user()->school_id)
                                    ->orderBy('name', 'asc')
                                    ->get();
        return view('admin.manage_routes', $data);
    }
    public function conveyances(Request $request)
    {
        $data['side_main']='settings';
        $data['side_sub']='transporters';

        $transporter_id=decrypt($request->transporter_id);

        $data['edit']=$id=0;
        if($request->id){
            $data['edit']=1;
            $id=decrypt($request->id);
        }
        if($request->trash){
            $id=decrypt($request->trash);
            Conveyance::where('id', $id)
                ->delete();
            Helper::setAdminMessage('Conveyance deleted successfully', 'success');
            return redirect()->back();
        }
        if($request->submit){
            if($id){
                Conveyance::where('id', $id)
                        ->update([
                            
                            'name'=>$request->name,
                            'fee'=>$request->fee
                        ]);
                $obj=Conveyance::find($id);
                $obj->routes()->sync($request->routes);
                Helper::setAdminMessage('Conveyance updated successfully', 'success');
                return redirect()->back();
                
            }
            else{
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                    'routes' => 'required'
                ]);

                $obj= new Conveyance;
                $obj->school_id=Auth::user()->school_id;
                $obj->transporter_id=$transporter_id;
                $obj->name=$request->name;
                $obj->fee=$request->fee;
                $obj->save();

                $last_id=$obj->id; 
                if($last_id){
                    $obj->routes()->attach($request->routes);

                }
                Helper::setAdminMessage('Conveyance added successfully', 'success');
                return redirect()->back();
            }
        }
        if($id){
            $data['conveyance']=Conveyance::where('id', $id)
                                    ->with('routes')
                                    ->first();
        }
        $data['routes']=Route::where('school_id', Auth::user()->school_id)
                                    ->orderBy('name', 'asc')
                                    ->get();
        $data['conveyances']=Conveyance::where('school_id', Auth::user()->school_id)->get();
        //return $data;
        return view('admin.manage_conveyances', $data);
    }
}
