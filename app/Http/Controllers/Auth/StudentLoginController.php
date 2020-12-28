<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Route;
use App\School;

class StudentLoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:student', ['except' => ['logout']]);
    }
    
    public function showLoginForm()
    {
      if(Auth::user()){
        Auth::guard('student')->logout();
      }
      return view('auth.login-student');
    }
    public function username()
    {
        return 'code';
    }
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }
    public function login(Request $request)
    {

      // Validate the form data
      $this->validate($request, [
        'code'   => 'required|string',
        'password' => 'required|min:6'
      ]);
      
      // Attempt to log the user in
      if (Auth::guard('student')->attempt(['code' => $request->code, 'password' => $request->password], $request->remember)) {
        // if successful, then redirect to their intended location
        return redirect('/student/homework');
      } 
      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->withInput($request->only('email', 'remember'));
    }
    
    public function logout(Request $request)
    {
        

        $school=School::where('id', Auth::guard('student')->user()->school_id)->first();
        if($school->website)
          $redirect_url=$school->website;
        else
          $redirect_url='/student/login';

        Auth::guard('student')->logout();
        $request->session()->flush();
        
        return redirect($redirect_url);
    }
}
