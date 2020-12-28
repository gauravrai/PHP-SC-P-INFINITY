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
use App\SmsPromotion;
use App\SmsPromotionDetail;

class SmsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function sendPromotionalMessage($sender_id, $message, $numbers){
        $username = 'som.kiransomD';
        $apiKey = '42D41-42617';
        $apiRequest = 'Text';
        $senderID = $sender_id;
        $apiRoute = 'DND';
        // Prepare data for POST request
        $data = 'username=' . $username . '&apikey=' . $apiKey . '&apirequest=' . $apiRequest . '&route=' . $apiRoute . '&mobile=' . $numbers . '&sender=' . $senderID . "&message=" . urlencode($message);
        // Send the GET request with cURL
        $url = 'http://www.alots.in/sms-panel/api/http/index.php?' . $data;

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 10); 

        $server_output = curl_exec($ch);
        curl_close($ch);
        if(isset($server_output) && !empty($server_output)){
            return 1;
        }else{
            return 0;
        }

    }
    public function sendMessage($sender_id, $template, $reciverNumber){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://alotsolutions.in/API/WebSMS/Http/v1.0a/index.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=som.kiransom&password=Bsa@1234&sender=".$sender_id."&to=" . $reciverNumber . "&message= " . $template . "");

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
       //$response =  $server_output;

        if(isset($server_output) && !empty($server_output)){
            return 1;
        }else{
            return 0;
        }
    }
    public function handle()
    {
        $sms=Sms::orderBy('created_at', 'asc')
                ->with(['sms_details', 'school'])
                ->where('iscompleted', 0)
                ->skip(0)
                ->take(10)
                ->get();

        if($sms->count()){
            foreach ($sms as $s) {
                $counter=0;
                if($s->sms_details && $s->sms_details->count()){
                    foreach ($s->sms_details as $sd) {
                        if($sd->issent==0){
                            if($this->sendMessage($s->school->sms_sender_id, $s->content, $sd->contact_number)){
                                SmsDetail::where('id', $sd->id)
                                    ->update([
                                        'issent'=>1,
                                        'sent_at'=>date('Y-m-d H:i:s')
                                    ]);
                                $counter++;
                            }
                        }
                    }
                }
                if($counter==0){
                    Sms::where('id', $s->id)
                        ->update([
                            'iscompleted'=>1,
                            'sent_at'=>date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        
        $sms=SmsPromotion::orderBy('created_at', 'asc')
                ->with(['sms_details', 'school']) 
                ->where('iscompleted', 0)
                ->skip(0)
                ->take(10)
                ->get();
        if($sms->count()){
            foreach ($sms as $s) {
                $counter=0;
                if($s->sms_details && $s->sms_details->count()){
                    foreach ($s->sms_details as $sd) {
                        if($sd->issent==0){
                            if($this->sendPromotionalMessage($s->school->sms_sender_id, $s->content, $sd->contact_number)){
                                SmsPromotionDetail::where('id', $sd->id)
                                    ->update([
                                        'issent'=>1,
                                        'sent_at'=>date('Y-m-d H:i:s')
                                    ]);
                                $counter++;
                            }
                        }
                    }                    
                }
                if($counter==0){
                    SmsPromotion::where('id', $s->id)
                        ->update([
                            'iscompleted'=>1,
                            'sent_at'=>date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        
        
    }
}
