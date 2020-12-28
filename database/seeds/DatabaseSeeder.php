<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


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
use App\SmsCategory;
use App\Sms;
use App\SmsDetail;
use App\Attendance;
use App\AttendanceStatus;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $obj=new AttendanceStatus;
        $obj->name="Present";
        $obj->initials="P";
        $obj->css="success";
        $obj->save();
        $obj=new AttendanceStatus;
        $obj->name="Absent";
        $obj->initials="A";
        $obj->css="danger";
        $obj->save();
        $obj=new AttendanceStatus;
        $obj->name="Leave";
        $obj->initials="";
        $obj->css="warning";
        $obj->save();

        $obj=new ParentRelationship;
        $obj->name="Father";
        $obj->isrequired=1;
        $obj->save();

        $obj=new ParentRelationship;
        $obj->name="Mother";
        $obj->isrequired=0;
        $obj->save();
        
        $role_array=[
            'Super Admin',
            'Admin',
            'Teacher',
            'Admission Team',
            'Accounts',
            'Parents'
        ];
        $permission_array=[
            [
                'Settings',
                'Attendance',
                'Homework',
                'SMS', 
                'Student',
                'Student Add',
                'Student Fee',
                'Student Edit',
                'Parents Access'
            ],
            [
                'Attendance',
                'Homework',
                'SMS', 
                'Student',
                'Student Add',
                'Student Fee',
                'Student Edit'
            ],
            [
                'Attendance',
                'Homework',
                'Student'
            ],
            [
                'Student',
                'Student Add'
            ],
            [
                'Student Fee'
            ],
            [
                'Parents Access'
            ]
            
        ];

        for($p=0;$p<count($role_array);$p++){
            $role_name=$role_array[$p];
            $role = Role::create(['name'=>$role_name]);
        }
        for($p=0;$p<count($permission_array);$p++){
            if($p>0)
                continue;
            $p_array=$permission_array[$p];
            for($j=0;$j<count($p_array);$j++){
                $p_name=$p_array[$j];
                $permission = Permission::create(['name'=>$p_name]);
            }

        }

        for($p=0;$p<count($role_array);$p++){
            $role_name=$role_array[$p];
            $role = Role::findByName($role_name);
            //dd($role);
            //for($k=0;$k<count($permission_array[$p]);$k++){
                $p_array=$permission_array[$p];
                //print_r($p_array);
                for($j=0;$j<count($p_array);$j++){
                    $p_name=$p_array[$j];
                    //echo "<br>-->".$p_name;
                    $role->givePermissionTo($p_name);

                }                    
            //}
            //exit;
        }

        
        // $this->call(UsersTableSeeder::class);
        $values = [
        			'name' => 'Bethany Convent School',
        			'address_line_1' => '102/12 Viram Khand',
        			'address_line_2' => 'Gomtinagar',
        			'address_line_3' => 'Lucknow',
        			'code' => 'BCS',
                    'pin_code' => '36010',
        			'landline' => '0532-95586',
        			'phone' => '95959595',
        		];
		DB::table('schools')->insert($values);
        $school_id=DB::getPdo()->lastInsertId();
		
        
		
		$values = [
					'school_id'=>$school_id,
        			'name' => '2019-20',
        		];
        DB::table('academic_sessions')->insert($values);
        $values = [
                    'school_id'=>$school_id,
                    'name' => 'Gaurav Rai',
                    'email' => 'bcs@gmail.com',
                    'password'=>bcrypt('123456')
                ];
		DB::table('users')->insert($values);
        $user_id=DB::getPdo()->lastInsertId();
        
        $user=User::find($user_id);
        $user->assignRole('Super Admin');


        $values = [
                'code' => 'JDIC',
                'sms_sender_id' => 'JDRPIC',
                'name' => 'Janak Dulari Inter College',
                'address_line_1' => '1',
                'address_line_2' => 'Aung',
                'address_line_3' => 'Fatehpur',
                'pin_code' => '36010',
                'landline' => '0532-95586',
                'phone' => '7007961315',
            ];
        
        DB::table('schools')->insert($values);
        $school_id=DB::getPdo()->lastInsertId();
        $values = [
                    'school_id'=>$school_id,
                    'name' => 'Dhirendra Patel',
                    'email' => 'dhirendra@gmail.com',
                    'password'=>bcrypt('123456')
                ];
        DB::table('users')->insert($values);
        $user_id=DB::getPdo()->lastInsertId();
        $values = [
                    'school_id'=>$school_id,
                    'name' => '2019-20',
                ];
        DB::table('academic_sessions')->insert($values);


        $user=User::find($user_id);
        $user->assignRole('Super Admin');
        //second school
        $values = [
                'code' => 'ASC',
                'sms_sender_id' => 'ASCENT',
                'name' => 'Ascent Public School',
                'address_line_1' => '2',
                'address_line_2' => 'Aung',
                'address_line_3' => 'Fatehpur',
                'pin_code' => '36010',
                'landline' => '0532-95586',
                'phone' => '6393107150',
            ];
        DB::table('schools')->insert($values);
        $school_id=DB::getPdo()->lastInsertId();
        
        $values = [
                    'school_id'=>$school_id,
                    'name' => 'Kshatrapal Verma',
                    'email' => 'kshatrapal@gmail.com',
                    'password'=>bcrypt('123456')
                ];
        DB::table('users')->insert($values);
        $user_id=DB::getPdo()->lastInsertId();
        $values = [
                    'school_id'=>$school_id,
                    'name' => '2019-20',
                ];
        DB::table('academic_sessions')->insert($values);


        $user=User::find($user_id);
        $user->assignRole('Super Admin');
	}
		    
}
