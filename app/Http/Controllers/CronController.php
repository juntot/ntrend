<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\CurlService;
use App\Services\SMSService;
use Carbon\Carbon;
use DB;
class CronController extends Controller
{
    //
    public $api_url = '';
    public $_session = '';
    public $postfields = [];
    public $mobile = '09437084841';
    public $smsnotif = false;

    public function testSMS(){
        $sms = new SMSService;
        $message = "This is a test message";

        if($this->smsnotif)
        return $sms->sendSMS($this->mobile, $message);
    }

    /**
     * SALES ORDER CRON JOBS
     */
    public function getSOsettings(){
        $result = DB::table('cron_so_settings')
        ->first();
        return response()->json($result);
    }
    
    public function salesOrder(){
        
        $so_settings = DB::table('cron_so_settings')
        ->first();
        $this->mobile = $so_settings->mobilenum;
        $this->smsnotif = $so_settings->smsnotif;

        if(!$so_settings->isStart)
        return http_response_code(400);

        // dd(explode(",", $so_settings->selectedcomp), (int)$so_settings->dayslimit);

        $result = '';
        $api = DB::table('API_table')
        ->whereNotNull('endpoint')
        ->first();
        // $postfields = [];
        $this->api_url = $api->endpoint;
        

        // check first item first
        // if no result go to next DB connection
        $getData = DB::table('override_setting')
        ->select('json')
        ->where('type', 'company')
        ->first();

        // $count = 0;
        if($getData) {
            // return $getData[0]->json;
            $arr = json_decode($getData->json, true);

            // get random number on what DB to use
            $randDB = $arr[rand(0, count($arr)-1)];
    
            $temp = [];
            // foreach($arr as $item => $val) { //foreach element in $arr
                
                if(in_array($randDB['name'], explode(",", $so_settings->selectedcomp))) {
                // if($count == 0) {
                    $this->postfields = [
                        "CompanyDB" =>  $randDB['name'],
                        "UserName"  =>  $randDB['user'],
                        "Password"  => $randDB['pwd']
                    ];
                }
                
                
                $result = $this->getResultsSO($this->postfields, (int)$so_settings->dayslimit);
                // dd($result);
                if($result){  
                    
                    return $this->initAutoClose($result);
                    // break;
                }
                // $count++;
            // }
        }
        return http_response_code(200);
        // return 'cron-so controller';
    }

    public function getResultsSO($postfields, $numdays){
        // dd($postfields);
        if($numdays < 1)
        return false;

        if(!count($postfields))
        return false;

        try {
            $result = CurlService::httpCurl(
                $this->api_url.'/Login',
                'POST',
                $postfields
            );
            
            /**
             *  GET sales order base from
             *  certain date and status is open
             */

            $this->_session = $result['data'];
            $date_cutoff = Carbon::now()->subDays($numdays)->isoFormat('YYYY-MM-DD');
            // return $date_cutoff;
            //  dd($date_cutoff);
            $path = "/Orders?$"."select=DocEntry,U_REMARKS,DocNum,DocDate,DocumentStatus&$"."filter=DocDate le '$date_cutoff' and DocumentStatus eq 'bost_Open'&$"."orderby=DocDate desc";

            // $path = "/Orders?$"."select=DocEntry,DocDate,DocumentStatus&$"."filter=DocDate le '2023-05-19' and DocDate ge '2023-03-07' and DocumentStatus eq 'bost_Open'&$"."orderby=DocDate desc";
            // dd($this->api_url.''.str_replace(' ', '%20',$path));
            $result = CurlService::httpCurlRequest(
                $this->api_url.''. str_replace(' ', '%20',$path),
                'GET',
                [],
                array(
                    'Cookie: '.$this->_session,
                    'Content-Type: application/json',
                    'Accept: application/json',
                )
            );

            
            if(count($result['data']->value) <= 0)
            return false;

            return $result['data']->value;
        } catch (\Throwable $th) {
            $sms = new SMSService;
            $message = "AUTO CLOSE \n".
                        "company: ".$postfields['CompanyDB']."\n
                        Unable to connect to DB";

            if($this->smsnotif)
            $sms->sendSMS($this->mobile, $message);
            // return $th;
        }
    }
    
    public function initAutoClose($result, $close_endpoint = '/Orders'){
        $result_path = '';
        try {
            
            // return $result['data']->value;
            /**
             * Update SO b4 closing
             */
            foreach ($result as $value) {
                /**
                 * Update Sales Order
                 * who's status is open
                 * and add comment for automated close
                 */

                $path = $close_endpoint.'('.$value->DocEntry.')';
                
                $result_path= [];
                $strRemarks = strtolower($value->U_REMARKS);
                $pattern = "/closed/i";
                
                if(preg_match($pattern, $strRemarks))
                {
                    $result_path['status'] = 204;
                }
                else{
                    $result_path = CurlService::httpCurlRequest(
                        $this->api_url.''. str_replace(' ', '%20',$path),
                        'PATCH',
                        [
                            "U_REMARKS" => "Automatic Closed after 30 days"
                        ],
                        array(
                            'Cookie: '.$this->_session,
                            'Content-Type: application/json',
                            'Accept: application/json',
                        )
                    );
                }
                
                
                // dd($result_path, $this->api_url.''. str_replace(' ', '%20',$path));
                /**
                 * Close Sales Order status
                 */
                 
                if(in_array($result_path['status'], [204])){
                    $path = $path.'/Close';
                    
                    $result_close_so = CurlService::httpCurlRequest(
                        $this->api_url.''. str_replace(' ', '%20',$path),
                        'POST',
                        [],
                        array(
                            'Cookie: '.$this->_session,
                            'Content-Type: application/json',
                            'Accept: application/json',
                        )
                    );
                }else{
                    
                    $sms = new SMSService;
                    $message = "AUTO CLOSE ".$close_endpoint." \n".
                    "company: ".$this->postfields['CompanyDB']."\n".
                    "DocEntry: ".$value->DocEntry."\n".
                    "DocNum: ".$value->DocNum."\n".
                    "Response Code: ".$result_path['status']."\n".
                    "Process: Error when updating remarks";
                    
                    if($this->smsnotif)
                    $sms->sendSMS($this->mobile, $message);
                }
            }
            

        } catch (\Throwable $th) {
            //throw $th;
            $sms = new SMSService;
            $message = "AUTO CLOSE ".$close_endpoint." \n".
            "company: ".$this->postfields['CompanyDB']."\n".
            "DocEntry: ".$value->DocEntry."\n".
            "DocNum: ".$value->DocNum."\n".
            "Response Code: ".$result_path['status']."\n".
            "Process: Error when closing/updating remarks";
            
            if($this->smsnotif)
            $sms->sendSMS($this->mobile, $message);
            return http_response_code(500);
        }
        return http_response_code(200);
    }

    public function SOsettingAction(){
        DB::table('cron_so_settings')
        ->updateOrInsert(
            ['id' => '1'],
            request()->all()
        );
        return http_response_code(200);
    }

    public function SOUpdateComp(){
        DB::table('cron_so_settings')
        ->updateOrInsert(
            ['id' => '1'],
            request()->all()
        );
        return http_response_code(200);
    }
    public function SODaysLimit(){

        DB::table('cron_so_settings')
        ->updateOrInsert(
            ['id' => '1'],
            request()->all()
        );
        return http_response_code(200);

    }

    /**
     * END
     */

     /**
      * INVENTORY TRANSFER
      */

    public function InvTransDaysLimit(){

        DB::table('cron_so_settings')
        ->updateOrInsert(
            ['id' => '1'],
            request()->all()
        );
        return http_response_code(200);

    }
    public function autoCloseInventoryTrans(){
        $so_settings = DB::table('cron_so_settings')
        ->first();
        $this->mobile = $so_settings->mobilenum;
        $this->smsnotif = $so_settings->smsnotif;


        if(!$so_settings->isStart)
        return http_response_code(400);

        // dd(explode(",", $so_settings->selectedcomp), (int)$so_settings->dayslimit);

        $result = '';
        $api = DB::table('API_table')
        ->whereNotNull('endpoint')
        ->first();
        // $postfields = [];
        $this->api_url = $api->endpoint;

        // check first item first
        // if no result go to next DB connection
        $getData = DB::table('override_setting')
        ->select('json')
        ->where('type', 'company')
        ->first();

        $count = 0;
        if($getData) {
            // return $getData[0]->json;
            $arr = json_decode($getData->json, true);
            
            // get random number on what DB to use
            $randDB = $arr[rand(0, count($arr)-1)];

            $temp = [];
            // foreach($arr as $item => $val) { //foreach element in $arr
                
                if(in_array($randDB['name'], explode(",", $so_settings->selectedcomp))){
                // if($count == 0) {
                    $this->postfields = [
                        "CompanyDB" =>  $randDB['name'],
                        "UserName"  => $randDB['user'],
                        "Password"  => $randDB['pwd']
                    ];
                }
                
                
                $result = $this->getResultsInventoryTrans($this->postfields, (int)$so_settings->dayslimit_invtrans);
                // dd('$result', $result);
                if($result){    
                    return $this->initAutoClose($result, '/InventoryTransferRequests');
                    // break;
                }
                // $count++;

            // }
        }
        return http_response_code(200);
    }

    public function getResultsInventoryTrans($postfields, $numdays){
        
        if($numdays < 1)
        return false;

        if(!count($postfields))
        return false;

        try {
            $result = CurlService::httpCurl(
                $this->api_url.'/Login',
                'POST',
                $postfields
            );
            
            /**
             *  GET sales order base from
             *  certain date and status is open
             */

            $this->_session = $result['data'];
            $date_cutoff = Carbon::now()->subDays($numdays)->isoFormat('YYYY-MM-DD');
            // return $date_cutoff;
            //  dd($date_cutoff);
            // $path = "/Orders?$"."select=DocEntry,DocDate,DocumentStatus&$"."filter=DocDate le '$date_cutoff' and DocumentStatus eq 'bost_Open'&$"."orderby=DocDate desc";

            $path = "/InventoryTransferRequests?$"."select=DocEntry,U_REMARKS,DocNum,DocDate&$"."filter=DocDate le '$date_cutoff' and DocumentStatus eq 'bost_Open'&$"."orderby=DocDate desc";
            // dd($this->api_url.''.str_replace(' ', '%20',$path));
            $result = CurlService::httpCurlRequest(
                $this->api_url.''. str_replace(' ', '%20',$path),
                'GET',
                [],
                array(
                    'Cookie: '.$this->_session,
                    'Content-Type: application/json',
                    'Accept: application/json',
                )
            );

            
            if(count($result['data']->value) <= 0)
            return false;

            return $result['data']->value;
        } catch (\Throwable $th) {
            $sms = new SMSService;
            $message = "AUTO CLOSE \n".
                        "company: ".$postfields['CompanyDB']."\n
                        Unable to connect to DB";
            
            if($this->smsnotif)
            $sms->sendSMS($this->mobile, $message);
            return false;
        }
    }

     /**
     * END
     */

     /**
      * Return Request
      */

      public function ReturnRequestDaysLimit(){

        DB::table('cron_so_settings')
        ->updateOrInsert(
            ['id' => '1'],
            request()->all()
        );
        return http_response_code(200);

    }
    public function autoCloseReturnRequest(){
        $so_settings = DB::table('cron_so_settings')
        ->first();
        $this->mobile = $so_settings->mobilenum;
        $this->smsnotif = $so_settings->smsnotif;


        if(!$so_settings->isStart)
        return http_response_code(400);

        // dd(explode(",", $so_settings->selectedcomp), (int)$so_settings->dayslimit);

        $result = '';
        $api = DB::table('API_table')
        ->whereNotNull('endpoint')
        ->first();
        // $postfields = [];
        $this->api_url = $api->endpoint;

        // check first item first
        // if no result go to next DB connection
        $getData = DB::table('override_setting')
        ->select('json')
        ->where('type', 'company')
        ->first();

        $count = 0;
        if($getData) {
            // return $getData[0]->json;
            $arr = json_decode($getData->json, true);
            
            // get random number on what DB to use
            $randDB = $arr[rand(0, count($arr)-1)];

            $temp = [];
            // foreach($arr as $item => $val) { //foreach element in $arr
                
                if(in_array($randDB['name'], explode(",", $so_settings->selectedcomp))){
                // if($count == 0) {
                    $this->postfields = [
                        "CompanyDB" =>  $randDB['name'],
                        "UserName"  => $randDB['user'],
                        "Password"  => $randDB['pwd']
                    ];
                }
                
                
                $result = $this->getResultsReturnRequest($this->postfields, (int)$so_settings->dayslimit_returnrequest);
                // dd('$result', $result);
                if($result){    
                    return $this->initAutoClose($result, '/ReturnRequest');
                    // break;
                }
                // $count++;

            // }
        }
        return http_response_code(200);
    }

    public function getResultsReturnRequest($postfields, $numdays){
        
        if($numdays < 1)
        return false;

        if(!count($postfields))
        return false;

        try {
            $result = CurlService::httpCurl(
                $this->api_url.'/Login',
                'POST',
                $postfields
            );
            
            /**
             *  GET sales order base from
             *  certain date and status is open
             */

            $this->_session = $result['data'];
            $date_cutoff = Carbon::now()->subDays($numdays)->isoFormat('YYYY-MM-DD');
            // return $date_cutoff;
            //  dd($date_cutoff);
            // $path = "/Orders?$"."select=DocEntry,DocDate,DocumentStatus&$"."filter=DocDate le '$date_cutoff' and DocumentStatus eq 'bost_Open'&$"."orderby=DocDate desc";

            $path = "/ReturnRequest?$"."select=DocEntry,U_REMARKS,DocNum,DocDate&$"."filter=DocDate le '$date_cutoff' and DocumentStatus eq 'bost_Open'&$"."orderby=DocDate desc";
            // dd($this->api_url.''.str_replace(' ', '%20',$path));
            $result = CurlService::httpCurlRequest(
                $this->api_url.''. str_replace(' ', '%20',$path),
                'GET',
                [],
                array(
                    'Cookie: '.$this->_session,
                    'Content-Type: application/json',
                    'Accept: application/json',
                )
            );

            
            if(count($result['data']->value) <= 0)
            return false;

            return $result['data']->value;
        } catch (\Throwable $th) {
            $sms = new SMSService;
            $message = "AUTO CLOSE \n".
                        "company: ".$postfields['CompanyDB']."\n
                        Unable to connect to DB";
            
            if($this->smsnotif)
            $sms->sendSMS($this->mobile, $message);
            return false;
        }
    }
    // END

    // SMS NOTIF SETTINGS
    public function addSMSnum(){
        DB::table('cron_so_settings')
        ->updateOrInsert(
            ['id' => '1'],
            request()->all()
        );
        return http_response_code(200);
    }

    public function allowSMSnotif(){
        DB::table('cron_so_settings')
        ->updateOrInsert(
            ['id' => '1'],
            request()->all()
        );
        return http_response_code(200);
    }
}