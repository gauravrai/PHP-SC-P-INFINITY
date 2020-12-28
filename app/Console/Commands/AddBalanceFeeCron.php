<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
use App\FeeStructure;
use App\FeeBalance;
use App\FeeType;
use App\Admission;
use App\Student;
use App\ParentRelationship;
use App\StudentParent;
use App\FeeStudentRecord;

class AddBalanceFeeCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:fee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add balance fee for student to be paid';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $last_str=strtotime('next month');

        $students=Student::where('isactive', 1)
                    ->where('status', 'studying')
                    ->whereDoesntHave('fee_student_record', function($q) use ($last_str){
                        $q->whereMonth('for_month', date('m', $last_str))
                            ->whereYear('for_month', date('Y', $last_str));
                    })
                    ->with(['admission', 'conveyance', 'fee_concession'])
                    ->take(100)
                    ->get()
                    ;
        $counter=0;
        foreach ($students as $s) {
            $counter++;
            if(!$s->admission){
                echo 'Failed in iteration ' . $counter . " no admission found";
                exit;
            }
            $transport_fee_type=FeeType::where('school_id', $s->school_id)->where('fee_type', 'transport')->first();
            $concession_fee_type=FeeType::where('school_id', $s->school_id)->where('has_concession', 1)->first();
            $academic_fee_structure=FeeStructure::where('fee_id', $s->admission->fee->id)
                                ->where('fee_type_id', '!=', $transport_fee_type->id)
                                ->get();
            foreach ($academic_fee_structure as $f){
                $sum=0;
                if($f->frequency=='daily'){
                    $number_of_days=date('t', $last_str);
                    $sum=$f->amount*$number_of_days;
                }
                else if($f->frequency=='weekly'){
                    $sum=$f->amount*4;
                }
                else if($f->frequency=='monthly'){
                    if(FeeBalance::where('academic_session_id', $s->admission->academic_session_id)
                        ->where('school_id', $s->school_id)
                        ->where('student_id', $s->id)
                        ->whereMonth('for_month', date('m', $last_str))
                        ->where('fee_structure_id', $f->id)
                        ->get()
                        ->count()==0){
                        $sum=$f->amount;
                    }
                }
                else if($f->frequency=='quaterly'){
                    if(date('m', $last_str)==1 || date('m', $last_str)==4 || date('m', $last_str)==7 || date('m', $last_str)==10){
                        if(FeeBalance::where('academic_session_id', $s->admission->academic_session_id)
                            ->where('school_id', $s->school_id)
                            ->where('student_id', $s->id)
                            ->whereMonth('for_month', date('m', $last_str))
                            ->where('fee_structure_id', $f->id)
                            ->get()
                            ->count()==0){
                            $sum=$f->amount;
                        }
                    }
                }
                else if($f->frequency=='biannually'){
                    if(date('m', $last_str)==1 || date('m', $last_str)==6){
                        if(FeeBalance::where('academic_session_id', $s->admission->academic_session_id)
                            ->where('school_id', $s->school_id)
                            ->where('student_id', $s->id)
                            ->whereMonth('for_month', date('m', $last_str))
                            ->where('fee_structure_id', $f->id)
                            ->get()
                            ->count()==0){
                            if(date('m', $last_str)==1 || date('m', $last_str)==6){
                                $sum=$f->amount;
                            }
                        }
                    }
                }
                else if($f->frequency=='annually'){
                    if(FeeBalance::where('academic_session_id', $s->admission->academic_session_id)
                        ->where('school_id', $s->school_id)
                        ->where('student_id', $s->id)
                        ->where('frequency', 'annually')
                        ->where('fee_structure_id', $f->id)
                        ->get()
                        ->count()==0){
                        $sum=$f->amount;
                    }
                }
                if($sum){
                    if(isset($concession_fee_type->id) &&  $concession_fee_type->id==$f->fee_type_id && $s->fee_concession){
                        $sum=round($sum-($sum*$s->fee_concession->amount/100));
                    }

                    $obj= new FeeBalance;
                    $obj->school_id=$s->school_id;
                    $obj->student_id=$s->id;
                    $obj->fee_structure_id=$f->id;
                    $obj->academic_session_id=$s->admission->academic_session_id;
                    $obj->school_class_id=$s->admission->school_class_id;
                    $obj->for_month=date('Y-m-01', $last_str);
                    $obj->name=$f->fee_type->name;
                    $obj->amount=$sum;
                    $obj->frequency=$f->frequency;
                    $obj->save();
                }
            }
            if($s->conveyance->count()){
                $transport=FeeStructure::where('fee_type_id', $transport_fee_type->id)->first();
                if(isset($transport) && $transport->id){
                        $obj= new FeeBalance;
                        $obj->school_id=$s->school_id;
                        $obj->student_id=$s->id;
                        $obj->fee_structure_id=$transport->id;;
                        $obj->academic_session_id=$s->admission->academic_session_id;
                        $obj->school_class_id=$s->admission->school_class_id;
                        $obj->for_month=date('Y-m-01', $last_str);
                        $obj->name=$transport->fee_type->name;//Transport text
                        $obj->amount=$s->conveyance[0]->fee;
                        $obj->frequency='monthly';
                        $obj->save();
                }
            }
            $obj= new FeeStudentRecord;
            $obj->school_id=$s->school_id;
            $obj->student_id=$s->id;
            $obj->academic_session_id=$s->admission->academic_session_id;
            $obj->school_class_id=$s->admission->school_class_id;
            $obj->for_month=date('Y-m-01', $last_str);
            $obj->save();
        }
                    
    }
}
