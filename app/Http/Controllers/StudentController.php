<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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


class StudentController extends Controller
{
    //use AuthenticatesUsers;
    
    public function __construct()
    {
        $this->middleware('student');
    }

    
    public function index()
    {
        
        return view('student.home');
    }
}
