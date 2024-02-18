<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\CurlService;
use App\Services\FormApproverService;
use Carbon\Carbon;

class SalesPorgramEnrollmentController extends Controller
{
    //
    // get override
    function getEnrollment() {
            
        $data = DB::select('select form.*,
        DATE_FORMAT(form.dateenrolled, "%m/%d/%Y %h:%i %p") as dateenrolled,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formsalesprogramenrollment form 
        left join employee emp on
        form.approvedby = emp.empID 
        where form.recstat = 0 and form.empID_ = :empid
        ORDER BY form.enrollmentID desc
        limit 1000
        ', [UserSession::getSessionID()]);
        // return $data;
        return $data;
    }

    // Add enrollment
    function addEnrollment(){
        

        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();
        $carbon_format = date("Y");
        
        request()->merge([
            'dateenrolled' => $now,
            'empID_' => UserSession::getSessionID(),
            'status' => 0,
            'recstat' => 0,
        ]);

        // return request()->all();
        $id = DB::table('formsalesprogramenrollment')->insertGetId(request()->except(['reciever_emails', 'attachment', 'attachtype']));
        $attachment = UserSession::IMG_Attachment('enrollmentprogram/'.$carbon_format.'/'.$id);
        if(request()->has('attachtype')){
            foreach (request('attachtype') as $key => $value) {
                request()->merge([
                    $value => $attachment[$key]
                ]);
            }
        }

        
        DB::table('formsalesprogramenrollment')
        ->where('enrollmentID', $id)
        ->update(request()->only(
            'progagree_attachment',
            'progmechanic_attachment'
        ));

        
        request()->merge(['enrollmentID' => $id, 'status' => 0, 'dateenrolled' => UserSession::formatDate($now, 'm/d/Y h:i A')]);
        
        $mailReceivers = FormApproverService::getApproverEmail('enrollmentID', $id, 'formsalesprogramenrollment', 'Enrollment0Program');

        // mail notification
        MailServices::sendNotify($mailReceivers, UserSession::getSessionID(), 'SALES ENROLLMENT PROGRAM');
        MailServices::formNotify($mailReceivers, UserSession::getSessionID(), 'sales enrollment program', $id, 'enrollmentprogram');        
        return request()->all();
        

    }
    

    // update override
    public function updateEnrollment(){
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::parse(request('dateenrolled'))->format('Y-m-d H:i:s');
        $carbon_format = Carbon::parse(request('dateenrolled'))->format('Y');
        // return $carbon_format;
        
        $id = request('enrollmentID');
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        request()->merge([
            'dateenrolled' => $now,
        ]);
        

        $attachment = UserSession::IMG_Attachment('enrollmentprogram/'.$carbon_format.'/'.$id, request('attachmentOldPath'));
        
        if(request()->has('attachtype')){
            foreach (request('attachtype') as $key => $value) {
                request()->merge([
                    $value => $attachment[$key]
                ]);
            }
        }

        // update
        DB::table('formsalesprogramenrollment')
            ->where('enrollmentID', request('enrollmentID'))
            ->where('status', 0)
            ->update(request()->except([
                'enrollmentID', 'remarks', 
                'approvedby', 'empID_', 
                'status', 'recstat',
                'attachment', 'attachtype',
                'approveddate',
                'approveddatetime',
                'attachmentOldPath',
            ]));

        // request()->except(['reciever_emails', 'attachment', 'attachtype'])
        request()->merge([
            'dateenrolled' => UserSession::formatDate($now, 'm/d/Y h:i A')
        ]);
        return request()->except(['attachtype']);
    }
    // delete override
    public function deleteEnrollment($enrollmentID  = null){
        
        $affected = DB::table('formsalesprogramenrollment')
        ->where(['enrollmentID'=>$enrollmentID, 'status'=>0])
        ->update(['recstat'=>404]);
        if($affected) {
            $mailReceivers = FormApproverService::getApproverEmail('enrollmentID', $enrollmentID, 'formsalesprogramenrollment', 'Enrollment0Program');
            MailServices::send_email_Notify($mailReceivers, UserSession::getSessionID(), 'SALES PROGRAM ENROLLMENT FORM', 'Deleted his/her');
        }
        
    }
    // FOR APPPROVERS =====================================================================================
    // GET  APPROVERS LIST NAME AND EMAIL
    public function getEnrollmentProgApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformapprover eform right join employee emp on eform.empID_ = emp.empID where eform.Leave0Form = 1');
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Override0Form = 1 and eform.empID_ = :empiD', 
        // [UserSession::getSessionID()]);
        $data = FormApproverService::getFormApproverByUser('Enrollment0Program');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalEnrollmentProgApprover(){

        $data = DB::select('select eleave.*,
                            DATE_FORMAT(eleave.dateenrolled, "%m/%d/%Y %h:%i %p") as dateenrolled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eleave.approvedby
                            ) as approvedby,
                            branch.branchname, pos.posname
                            from formsalesprogramenrollment eleave left join eformapproverbyemp eform
                                on eleave.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eleave.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID
                            and eform.Enrollment0Program = 1
                            and eleave.recstat = 0
                            ORDER BY eleave.enrollmentID desc
                            limit 2000',
                            [UserSession::getSessionID()]);
        
        return $data;
    }    

    // approved / reject
    public function actionFormEnrollment(){
        $sqlQuery = '';
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();
        $user = UserSession::getSessionID();

        // get list of approvers email
        $mailReceivers = FormApproverService::getApproverEmail('enrollmentID', request('enrollmentID'), 'formsalesprogramenrollment', 'Enrollment0Program');
        
        
       
        DB::table('formsalesprogramenrollment')
        ->where('enrollmentID', request('enrollmentID'))
        ->update([
            'status' => request('status'), 'approvedby'=> $user,
            'approveddate'=> date("Y-m-d"), 
            'approveddatetime'=> date("Y-m-d H:i:s"),
            'remarks' => request('remarks')
            ]);
        
        // // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            // approve
            MailServices::send_email_Notify($mailReceivers, request('empID_'), 'SALES PROGRAM ENROLLMENT FORM', ' <br><br>Approved his/her ');
            // MailServices::form_post_Notify($mailReceivers, request('empID_'), 'override form request', request('workID'), 'workreq', 'confirmed his/her submitted');
        }
        if(request('status') == 2){
            // reject
            MailServices::send_email_Notify($mailReceivers, request('empID_'), 'SALES PROGRAM ENROLLMENT FORM', ' <br><br>Rejected his/her ');
            // MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'rejected', request('leaveID'), 'leave');
        }
        else{
            // cancel
            MailServices::send_email_Notify($mailReceivers, request('empID_'), 'SALES PROGRAM ENROLLMENT FORM', ' <br><br>Cancelled his/her ');
            // MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'undertime request', 'cancelled', request('undertimeID'), 'undertime');
        }

        
        return request()->all();
    }

}
