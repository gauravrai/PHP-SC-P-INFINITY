<?php 
namespace App\Helpers;

use DB;
use Auth;
use Session;
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
use App\Attendance;
use App\AttendanceStatus;

class Helper
{
    public static function getProfilePictureForForm($profile=null){

        if($profile){
            if (file_exists( public_path() . '/student-profile/' . $profile)) {
                return '/student-profile/' . $profile;
            } else {
                return '/student-profile/avatar-def.png';
            }
        }
        else
            return '/student-profile/avatar-def.png';
        
        
    }
    public static function getProfilePicture($profile=null){
        if (Auth::guard('student')->user()) {
            if(!$profile)
                $profile=Auth::guard('student')->user()->profile;
            if($profile){
                if (file_exists( public_path() . '/student-profile/' . $profile)) {
                    return '/student-profile/' . $profile;
                } else {
                    return '/student-profile/avatar-def.png';
                }
            }
            else
                return '/student-profile/avatar-def.png';
        }
        elseif(Auth::user()->email){
            return '/dist/img/avatar5.png';
        }
        
    }
    public static function paymentMethod($mode=''){
        $array=[
            'cheque',
            'cash',
            'dd'
        ];
        return $array;
    }
    public static function fetchAttendanceOnDate($student_id, $date){
        return Attendance::where('student_id', $student_id)
                                    ->with('attendance_status')
                                    ->where('for_date', date("Y-m-d", strtotime($date)))
                                    ->first();
    }
    public static function fetchParent($student_id, $parent_relationship_id){
        return StudentParent::where('student_id', $student_id)->where('parent_relationship_id', $parent_relationship_id)->first();
    }
    public static function studentID($school_id){
        $students=Student::where('school_id', $school_id)
                    ->count();
        return date("y") . str_pad($school_id, 3, '0', STR_PAD_LEFT) . str_pad(($students+1), 4, '0', STR_PAD_LEFT);
    }
    public static function isSidebar($setval, $isval){
    	if($setval==$isval)
    		return true;
    	else
    		return false;
    }
    public static function formatMoney($amount){
        return 'Rs. ' . number_format($amount);
    }
    public static function formatDate($date, $todb=0){
        if($todb){
            return date("Y-m-d", strtotime($date));
        }
        else{
            return date("d-m-Y", strtotime($date));
        }
    }
    public static function setAdminMessage($message, $type){
        Session::put('message', $message);
        Session::put('message_type', $type);
    }

}