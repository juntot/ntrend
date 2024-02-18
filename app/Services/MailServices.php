<?php

// namespace App\Repository;
namespace App\Services;

use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormMail;
use App\Mail\DeliverySysMail;
use App\Services\UserSession;
use Swift_Mailer;
use App\Traits\DeliverySystemTrait;

class MailServices{

    use DeliverySystemTrait;
    // header notifications
    public static function sendNotify($email, $from, $formType = null, $subject = ''){

        $result = DB::select('select concat(fname," ",lname) as fullname from employee where empID = :from', [$from]);

        $message = 'Your Approval is Requested by
                    <span style="color:#F97000">'.$result[0]->fullname.'</span>
                    <br><br>Regarding his/her
                    <span style="color:#F97000">'.$formType.'</span>';
        
        // $mail = ['to'=>$email, 'message'=>$message, 'subject' => $subject];
        // $mailcontent = (new self)->formHtmlTemplate($mail['message']);   
        // return (new self)->smtp2goEmailAPI($mail['to'], $mail['subject'], $mailcontent);

        try {
            Mail::to($email)->send(new FormMail($message, $subject));
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        
    }

    public static function sendNotifyReviewed($email, $from, $formType = null, $status = null, $subject = ''){

        $result = DB::select('select concat(fname," ",lname) as fullname from employee where empID = :from', [$from]);

        $message = 'Your <span style="color:#F97000">'.$formType.'</span> has been reviewed
                    <br><br>and it is <span style="color:#F97000">'.$status.'</span> by
                    <span style="color:#F97000">'.$result[0]->fullname.'</span>';
        
        // $mail = ['to'=> $email ,'message'=>$message, 'subject' => $subject];
        // $mailcontent = (new self)->formHtmlTemplate($mail['message']);   
        // return (new self)->smtp2goEmailAPI($mail['to'], $mail['subject'], $mailcontent);

        
        try {
            Mail::to($email)->send(new FormMail($message, $subject));
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }

    // form notifications
    public static function formNotify($email, $from, $formType = null, $formID = null, $type = ''){
        date_default_timezone_set("Asia/Hong_Kong");

        $result = DB::select('select concat(fname," ",lname) as fullname from employee where empID = :from', [$from]);

        $message = '<span style="color:#F97000">'.$result[0]->fullname.'</span>
                    submitted a
                    <span style="color:#F97000">'.$formType.'</span>';

        $str_emails = '';
        foreach($email as $key=>$mail_val){
            $str_emails .= $mail_val .', ';
        }
        $notify[] = ['notify_mail'=> $str_emails, 'notif_msg' => $message, 'dateadded' => date("Y-m-d H:i:s"),
                    'validate_date' => date("Y-m-d"), 'formtype'=> $type, 'formID'=> $formID];
        DB::table('notif_form')->insert($notify);

    }

    public static function formNotifyReviewed($email, $from, $formType = null, $status= '', $formID = null, $type = ''){
        date_default_timezone_set("Asia/Hong_Kong");

        $result = DB::select('select concat(fname," ",lname) as fullname from employee where empID = :from', [$from]);

        $message = '<span style="color:#F97000">'.$result[0]->fullname.'</span>  <span style="color:#F97000">'.$status.'</span>
                    your <span style="color:#F97000">'.$formType.'</span>
                    ';

        // foreach($email as $key=>$mail_val){
            $notify[] = ['notify_mail'=> $email, 'notif_msg' => $message, 'dateadded' => date("Y-m-d H:i:s"),
                        'validate_date' => date("Y-m-d"), 'formtype' => $type, 'formID' => $formID];
        // }
        DB::table('notif_form')->insert($notify);
    }



    public static function postNotify($notfy_users = '', $notify_depts = '', $notify_comps, $message = ''){
        date_default_timezone_set("Asia/Hong_Kong");

        $result = DB::select('select concat(fname," ",lname) as fullname from employee where empID = :from', [UserSession::getSessionID()]);

        $excerpt_msg = substr($message,0, 135);
        $notify[] = [
                        'notifBy'=> $result[0]->fullname,
                        'notify_users'=> $notfy_users,
                        'notify_dept' => $notify_depts,
                        'notify_comp' => $notify_comps,
                        'notif_msg' => $excerpt_msg,
                        'dateadded' => date("Y-m-d H:i:s"),
                        'validate_date' => date("Y-m-d"),
                        'notifby_empID' => UserSession::getSessionID(),
                        'viewedby' => 'user'
                    ];
        DB::table('notif_post')->insert($notify);
    }


    // CONFIRMATION
    public static function send_email_Notify($email, $from, $formType = null, $message = '', $subject = ''){

        $result = DB::select('select concat(fname," ",lname) as fullname from employee where empID = :from', [$from]);

        $message = '<span style="color:#F97000">'.$result[0]->fullname.' </span> '.$message.'
                     <span style="color:#F97000">'.$formType.'</span>';

        // $mail = ['to'=> $email, 'message'=>$message, 'subject' => $subject];
        // $mailcontent = (new self)->formHtmlTemplate($mail['message']);   
        // return (new self)->smtp2goEmailAPI($mail['to'], $mail['subject'], $mailcontent);

        try {
            Mail::to($email)->send(new FormMail($message, $subject));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // check supplmentary for implementation
    public static function form_post_Notify($email, $from, $formType = null, $formID = null, $type = '', $message = ''){
        date_default_timezone_set("Asia/Hong_Kong");

        $result = DB::select('select concat(fname," ",lname) as fullname from employee where empID = :from', [$from]);

        $message = '<span style="color:#F97000">'.$result[0]->fullname.' </span> '.$message.'
                     <span style="color:#F97000">'.$formType.'</span>';

        $str_emails = '';
        foreach($email as $key=>$mail_val){
            $str_emails .= $mail_val .', ';
        }
        $notify[] = ['notify_mail'=> $str_emails, 'notif_msg' => $message, 'dateadded' => date("Y-m-d H:i:s"),
                    'validate_date' => date("Y-m-d"), 'formtype'=> $type, 'formID'=> $formID];
        DB::table('notif_form')->insert($notify);

    }


    public static function getEmailsByEmpId($empID = ''){
        $approverEmail = DB::select('select email from employee where empID = :empID', [$empID]);
        if(count($approverEmail)){
            return $approverEmail[0]->email;
        }else{
            return null;
        }
    }

    // GET APPORVERS EMAILS BASE FROM FORM (SAMPLE OVERRIDE CONTROLLER)
    public static function getApproverEmail($id, $idVal, $form, $colName) { 
        $result = DB::select('select emp.email from employee emp
        right join eformapproverbyemp eform
        on eform.approverID_ = emp.empID  
        join '.$form.' form
        on form.empID_ = eform.empID_  
        where form.'.$id.' = :id
        and eform.'.$colName.' = 1', 
        [$idVal]);
        
        $emails = [];
        foreach ($result as $value) {
            if($value->email)
            $emails[] = $value->email;
        }
        return $emails;
    }

    // GET REQUESTOR EMAIL
    public static function getRequestorEmail($id, $idVal, $form) {
        $result = DB::select('select emp.email from employee emp
        join '.$form.' form
        on form.empID_ = emp.empID
        where form.'.$id.' = :id'
        [$idVal]);

        if($result) {
            return $result[0]->email;
        }
    }


    // delivery system controller
    public static function sendDeliverySysMail($email, $from, $bcc = '', $subject = '', $message = ''){
        try {

            // https://stackoverflow.com/questions/56630267/laravel-multiple-mail-configurations-and-multiple-concurrent-users

            // Backup your default mailer
            $backup = Mail::getSwiftMailer();

            // Setup your gmail mailer
            $transport = \Swift_SmtpTransport::newInstance('mail.smpt2go.com', 465, 'ssl');
            $transport->setUsername('ntmitd@smtp2go.com');
            $transport->setPassword('n0rthr3nd');
            // Any other mailer configuration stuff needed...
            $swift_mailer = new \Swift_Mailer($transport);

            // Set the mailer as gmail
            // Mail::setSwiftMailer($swift_mailer);

            

            // // Send your message
            // Mail::send(new DeliverySysMail($message, $subject), $data, function($message) use($params)
            // {
            //     $message->from($params['email']);
            //     if($bcc)
            //     $message->bcc($bcc);

            //     $message->to($params['toEmail'])
            //             ->subject($params['subject']);
            // });



            // ANOTHER WAY
            // https://laravel-news.com/allowing-users-to-send-email-with-their-own-smtp-settings-in-laravel
            $view = app()->get('view');
            $events = app()->get('events');
            $mailer = new Mailer($view, $swift_mailer, $events);

            $mailer->to($email);
            if($bcc)
            $mailer->bcc($bcc);

            $mailer->send(new DeliverySysMail($message, $subject));


            // Restore your original mailer
            Mail::setSwiftMailer($backup);


            
            // $mailer = Mail::to($email);
            
            // if($bcc)
            // $mailer->bcc($bcc);

            // $mailer->send(new DeliverySysMail($message, $subject));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function formHtmlTemplate($emailBody){
        return '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
            <style>
                body{
                    font-size: 2em;
                    color: #333;
                    /* background: #e9ebee; */
                }
                .white-text{
                    color: white;
                }
                .container{
                    max-width: 600px;
                }
                .dark-purple-bg{
                    background: #303F9F;
                }
                .glyphicon{
                    color:#E3F2FD;
                    font-size: xx-large;
                }
                .intro-text{
                    padding:10px;
        
                }
            </style>
        </head>
        <body style="background: #e9ebee;">
            <center>
            <table style="margin: 0 auto; text-align: center; color: white; width: 800px;" >
                <thead style="background: #303F9F; padding: 60px 0px; display: block;">
                    <tr>
                        <td style="width: 1366px;">
                            <img src="http://dev.northtrend.com/public/images/mailer/mail-info-01.png"
                            style="width: 90px; height: auto;"
                            alt="information">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">
                            <h1>Approval Request</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 20 20 20x; font-size: 14px;">'.date("l\, jS \of F Y").'</td>
                    </tr>
                </thead>
                <tbody style="background: white; padding: 80px 15px; display: block; color: #333; text-align: center;">
                    <tr>
                        <td style="
                            width: 1366px;
                            font-size: 14px;
                        ">
        
                        <div>
                            <h1 style="text-align: center; color: #303f9f; margin-top: 0;"><center>Exceltrend Portal</center></h1>
                            <h1 style="text-align: center;">'.
                                $emailBody
                            .'</h1>
                        </div>
                    </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 50px;">
                            <a style="cursor: pointer;" href="https://e-portal.northtrend.com">
                                <button style="
                                text-decoration: none;
                                cursor: pointer;
                                display: inline-block;
                                margin-bottom: 0;
                                font-weight: 400;
                                text-align: center;
                                white-space: nowrap;
                                vertical-align: middle;
                                -ms-touch-action: manipulation;
                                touch-action: manipulation;
                                cursor: pointer;
                                background-image: none;
                                border: 1px solid transparent;
                                padding: 15px 22px;
                                font-size: 14px;
                                line-height: 1.42857143;
                                border-radius: 8px;
                                background: #303F9F;
                                color: white;
                                font-size: 16px;
                                ">
                                Click here to view <br>
                                the details of the request
                                </button>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            </center>
        </body>
        </html>';
    }
}
