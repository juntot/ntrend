<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\DeliverySystemTrait;
use App\Traits\DeliverySystemTrait;    
use App\Services\SMSService;
use DB;
use Carbon\Carbon;
// use App\Services\MailServices;
// use App\Services\SecondaryMailer;
use App\Mail\DeliverySysMail;
use Illuminate\Support\Facades\Mail;
use Config;

class DeliverySystemController extends Controller
{
    use DeliverySystemTrait;    
    //
    public $companies = [
        'APBW' => 'AP Blue Whale Corp.',
        'NTMC' => 'North Trend Marketing Corp.',
        'PHILSYN' => 'Philcrest Marketing Corp.',
        'PHILCREST' => 'Philcrest Marketing Corp.',
    ];
    public function getSapInvoice(){

        $sapRecords = '';
        
        $maxLoop = 1000/20; // 1000 results, 20 per pages default
        $rec =  [];
        $dataVal = '';
        $count = 0;
        do {
        $skip = (($count+1)-1) * 20;
        $sapRecords = $this->patchPostSAPEndPoint([
            'path'               =>  request('query').''.request('params').'&$'.'skip='.$skip,
            'method'             =>  'GET',
            'params'             =>  '',
        ]);
        

            $tempRec= $sapRecords['data'];
            
            $dataVal = $tempRec->value;
            if($dataVal){
                $tempRec = $tempRec->value;
                foreach ($tempRec as $key=>$value) {
                    // return $value->DocNum;
                    $dbrec = DB::table('formdeliverysys')
                        ->where('DocNum_', $value->DocNum)
                        ->first();
                    
                    // if(!$dbrec)
                    // $tempRec[$key] = (object) array_merge((array) $tempRec[$key], (array) 
                    // [
                    //     'updatedby_' => '',
                    //     'updatedby_' => '',    
                    // ]);
                    
                    $tempRec[$key] = (object) array_merge((array) $tempRec[$key], (array) $dbrec);

                }
                $rec = array_merge($rec, $tempRec);
            }
          $count++;
        } while($dataVal && $count < $maxLoop);
        return property_exists($sapRecords['data'], 'odata.nextLink') ? ['next'=> explode("/",$sapRecords['data']->{'odata.nextLink'})[3], 'data' => $rec] : ['data'=> $rec ];    
    }

    public function getSapDetails(){
        $sapRecords = $this->consumeSAPEndpoint();
        return $sapRecords;
    }

    public function updateRecord(){
        
        // return $this->Send('ubec.creative@gmail.com', 'message', 'subject');
        // return $this->deliverySystemMailer('no-reply@northtrend.com', 'Customer Survey', 'ubec.creative@gmail.com' );
        
        // $sapPartner = $this->patchPostSAPEndPoint([
        //     'path'               =>  'BusinessPartners?$select=CardCode,CardName,Cellular,ContactPerson&$filter=CardCode eq '."'".request('CardCode')."'",
        //     'method'             =>  'GET',
        //     'params'             =>  '',
        // ]);
        

        // // sms notification
        // $dateDelivered = Carbon::createFromFormat('Y-m-d', request('deliverydate'))->format('m/d/Y');
        // $mobile = str_replace('-', '', $sapPartner['data']->value[0]->Cellular);

        // $message = "Greetings from ".$compName."\n\n";
        // $message .= "Hi ".$sapPartner['data']->value[0]->CardName.", \n".
        // "Your order amounting ₱".number_format(request('DocTotal'), 2).
        // " under SI# ".request('DocNum_')." has been delivered successfully on ".$dateDelivered." and was received by ".request('receivedby')." ".
        // "In order to help us improve the quality of our services, we would greatly appreciate your insightful comments
        // on how we handle your order until delivery. Please click the link below. \n\n"."forms-gle/TvurkjRBbSczxrB67"."\n\n"."Kindly replace the dash(-) to period on the link above. Thank you";
        
        
        // $mobilenum_pattern = "/^(09)\d{9}$/";
        // if(preg_match($mobilenum_pattern, $mobile)){
        //     $curl = new SMSService;
        //     // $curl->sendSMS($mobile, $message, 1);
        // }
        


        // patch update SAP
        $sapParams = [
            'path'               =>  request('path'),
            'method'             =>  request('method'),
            'params'             =>  [
                'U_ReceivedBy'       =>  request('receivedby'),
                'U_DRIVER'           =>  request('driver'),
                'U_VHCLENO'          =>  request('vehiclenum'), 
                'U_DELIVERY'         =>  request('deliverydate'),
                'U_RCVREMARKS'       =>  request('remarks'),
            ]
            
        ];
        $sapRecords = $this->patchPostSAPEndPoint($sapParams);
        $sapstatus  = $sapRecords['status'];
        

        if($sapstatus != 200 && $sapstatus != 204){
            
            request()->merge([
                'isSuccess' => 400
            ]);
            // return http_response_code(500);
            
            DB::table('formdeliverysys')
            ->updateOrInsert(
                ['DocNum_' => request('DocNum_')],
                request()->only(['DocNum_','isSuccess'])
            );    
            return response()->json(request()->only(['DocNum_','isSuccess']),$sapstatus);

        }else{
            
            request()->merge([
                'isSuccess' => 200
            ]);
            
        
            DB::table('formdeliverysys')
            ->updateOrInsert(
                ['DocNum_' => request('DocNum_')],
                request()->except(['path', 'method', 'CardCode', 'DocTotal', 'company', 'branchcode'])
            );
            // $this->sendSMSNotif();
            $this->deliverySystemMailer('no-reply@northtrend.com', 'Customer Survey', '');
            
            // $this->deliverySystemMailer('no-reply@northtrend.com', 'chareday.occena@northtrend.com', 'Customer Survey');    
        }
        return response()->json(request()->except(['path', 'method', 'branchcode']), 200);

        // return response()->json(request()->only(['DocNum_','isSuccess']),$sapstatus);
        // update local DB
        // DB::table('formdeliverysys')
        // ->updateOrInsert(
        //     ['DocNum_' => request('DocNum_')],
        //     request()->except(['path', 'method', 'CardCode', 'DocTotal', 'company'])
        // );
        
        
        

        
    }

    public function sendSMSNotif(){
        $sms = DB::table('ar_delivery')
                   ->where('branchcode', request('branchcode'))
                   ->first();

        $compName = $this->companies[request('company')] ? $this->companies[request('company')] : '';
        $sapPartner = $this->patchPostSAPEndPoint([
            'path'               =>  'BusinessPartners?$select=CardCode,CardName,Cellular,ContactPerson&$filter=CardCode eq '."'".request('CardCode')."'",
            'method'             =>  'GET',
            'params'             =>  '',
        ]);
        

        // sms notification
        $dateDelivered = Carbon::createFromFormat('Y-m-d', request('deliverydate'))->format('m/d/Y');
        $mobile = str_replace('-', '', $sapPartner['data']->value[0]->Cellular);

        $compName = $sms->compname ? $sms->compname  : $compName;
        $deliveryType = request('deliverytype') == 'delivery' ? 'delivered': request('deliverytype');
        
$message = "Greetings from ".$compName."!\n\n";

$message .="Hi ".$sapPartner['data']->value[0]->CardName.".,\n
Your order under INV# ".request('DocNum_')." has been ".$deliveryType." successfully last ".$dateDelivered." and was received by ".request('receivedby').".
This message serves as your delivery confirmation. Please contact us if there is any concern with your order at ".$sms->branchnum."
\n\n

Thank you!";

        
        
        // $message .= "Hi ".$sapPartner['data']->value[0]->CardName.", \n".
        // "Your order under INV# ".request('DocNum_')." has been delivered successfully last ".$dateDelivered." and was received by ".request('receivedby')." ".
        // "Please help us improve our ways of serving you by answering our survery sheet that will also serve as your delivery confirmation.\n\n
        // Link: forms-gle/TvurkjRBbSczxrB67".
        // "\n\n"."Kindly replace the dash(-) with(.) to open the survey link\n Thank you";

        $mobilenum_pattern = "/^(09)\d{9}$/";
        if(preg_match($mobilenum_pattern, $mobile)){
            $curl = new SMSService;
            $curl->sendSMS($mobile, $message, $sms->smsport);
        }
    }



    // BRANCHES =========================
    public function getDeliveryBranch(){
        $dbrec = DB::table('ar_delivery')
                ->get();
        return $dbrec;
    }

    // add or update
    public function addUpdateDeliveryBranch(){
        $id = request('deliveryId') ? request('deliveryId'):  Carbon::now()->valueOf();;
        request()->merge([
            'deliveryId' => $id
        ]);
        DB::table('ar_delivery')
            ->updateOrInsert(
                ['deliveryId' => $id],
                request()->except(['_token'])
            );    
        
        return request()->except(['_token']);
    }

    // delete
    public function delDeliveryBranch(){
        $id = request('id');
        DB::table('ar_delivery')->where('deliveryId', $id)->delete();
        return http_response_code(200);
    }

    public function deliverySystemMailer($from, $subject = '', $cc = ''){
        
        $sapPartner = $this->patchPostSAPEndPoint([
            'path'               =>  'BusinessPartners('."'".request('CardCode')."'".')?$select=EmailAddress',
            'method'             =>  'GET',
            'params'             =>  '',
        ]);
        $to = $sapPartner['data']->EmailAddress;
        
        $message = '
<div>Dear Valued Partner,<br><br>

The first priority of Exceltrend Capital Inc. is to provide you with a service that hopefully meets or exceeds your expectations.<br><br>
    
To determine how we are currently performing in achieving these objectives, we are inviting you to share your experience with us by completing the survey below.<br><br>

Your feedback is very important and will help us provide even higher levels of service to you in the future.<br><br>

Click Survey Link <a href="https://docs.google.com/forms/d/1gCXvNAfRfRTFE1OQE5Ckxe_sCrPKKcxAa_DElnecUJo/viewform?edit_requested=true&edit_requested=true#response=ACYDBNh0pISEnOpidJh5JbMu87hSF5SaLRgrJSskHZY0knYBEhDWFlb1M4Pz5Zvf0iyW4KA">Here</a><br><br>

Should you have any concerns or questions about this survey, please feel free to contact our branch Customer Relations Associates.<br><br> 


Sincerely,<br>
Customer Relations Team<br>
North Trend Marketing Corp | AP Blue Whale Corp. <br>
Solid Trend Trade Sales Inc. | Philcrest Marketing Corp.</div>';

        // MailServices::sendDeliverySysMail($to, $from, $bcc, $subject, $message);
        
        return $this->smtp2goEmailAPI([$to], $subject, $message , $cc);
    }





    public function Send($email, $message, $subject) ///Send($view, array $data, array $params)
    {

        $mailencrypt = Config::get('mail.encryption');
        $mailHost = Config::get('mail.host');
        $mailPort = Config::get('mail.port');
        $mailUser = Config::get('mail.username');
        $mailPass = Config::get('mail.password');
        

        Config::set('mail.encryption','tls');
        Config::set('mail.host','mail.smpt2go.com');
        Config::set('mail.port','465');
        Config::set('mail.username','ntmitd');
        Config::set('mail.password','n0rthr3nd');
        // Config::set('mail.from',  ['address' => 'youraddress@example.com' , 'name' => 'Your Name here']);
return        Mail::to($email)->send(new DeliverySysMail($message, $subject));


        Config::set('mail.encryption', $mailencrypt);
        Config::set('mail.host', $mailHost);
        Config::set('mail.port', $mailPort);
        Config::set('mail.username', $mailUser);
        Config::set('mail.password', $mailPass);




        // MailServices::sendDeliverySysMail($to, $from, $bcc, $subject, $message);

        // Config::get('mail.encryption','ssl');
        // Config::get('mail.host','smtps.example.com');
        // Config::get('mail.port','465');
        // Config::get('mail.username','youraddress@example.com');
        // Config::get('mail.password','password');
        // Config::get('mail.from',  ['address' => 'youraddress@example.com' , 'name' => 'Your Name here']);

        // // Backup your default mailer
        // $backup = Mail::getSwiftMailer();
  
        // // Setup your gmail mailer
        // $transport = \Swift_SmtpTransport('mail.smpt2go.com', 465, 'tls');
        // $transport->setUsername('ntmitd');
        // $transport->setPassword('n0rthr3nd');
        // // Any other mailer configuration stuff needed...
        // $gmail = new Swift_Mailer($transport);
  
        // // Set the mailer as gmail
        // Mail::setSwiftMailer($gmail);
  
        // // Send your message
        // // Mail::send($view, $data, function($message) use($params)
        // // {
        // //     $message->from($params['email'])->to($params['toEmail'])->subject($params['subject']);
        // // });
        // Mail::to($email)->send(new FormMail($message, $subject));
  
        // // Restore your original mailer
        // Mail::setSwiftMailer($backup);
  
    }
}
