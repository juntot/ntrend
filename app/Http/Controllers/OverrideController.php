<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Services\UserSession;
use App\Services\MailServices;
use Carbon\Carbon;

class OverrideController extends Controller
{

    // get employee lsiting
    public function searchEmp(){
        $data = DB::select('select empID,  CONCAT(fname,", ",lname) as fullname 
        from employee where (fname like :search OR lname like :search2) 
        AND empID not IN( :empID, "00001" )
        AND status = 1
        order by fname limit 30',
                [request('keyword').'%', request('keyword').'%', UserSession::getSessionID()]);
        return $data;
    }
    // get override
    function getOverride() {
        
        $data = DB::select('select form.*,
        DATE_FORMAT(form.dateoverride, "%m/%d/%Y %h:%i %p") as dateoverride,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formoverride form 
        left join employee emp on
        form.approvedby = emp.empID where form.recstat != 1 and form.empID_ = :empid', [UserSession::getSessionID()]);
        // return $data;
        $response = [];
        foreach ($data as $value) {
            // return $value->endorsedby_;
            $endorsedId = explode(",", ($value->endorsedby_ ? $value->endorsedby_ : 'Null'));
            
            if($endorsedId){
                // $response[] = $empname;
                $temp = [];
                foreach ($endorsedId as $id) {
                    $sql = 'select CONCAT(fname," ",lname) as endorser 
                                from employee
                            where empID = "'.$id.'"';
                    
                    $empname = DB::select($sql);
                    if($empname) {
                        $temp[] = $empname[0]->endorser;
                    }
                }
                $value->endorsedby_  = implode(", ",$temp);

            }

            // get approver names
            $sql = 'select CONCAT(fname," ",lname) as approvedby
            from employee
                where empID = :approver';
            $approver = DB::select($sql, [$value->approvedby]);
            
            if($approver) {
                $value->approvedby = $approver[0]->approvedby;
            }
            $response[] = $value;
            
        }
        return $response;
    }

    // Add override
    function addOverride(){
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();
        request()->merge([
            'dateoverride' => $now,
            'empID_' => UserSession::getSessionID(),
            'commited_date' => UserSession::formatDate(request()->commited_date),
            'status' => 0,
            'recstat' => 0,
        ]);
        
        $id = DB::table('formoverride')->insertGetId(request()->except(['reciever_emails']));
        
        request()->merge(['overrideID' => $id, 'status' => 0, 'dateoverride' => UserSession::formatDate($now, 'm/d/Y h:m A')]);
        
        // mail notification
        MailServices::sendNotify(request('reciever_emails'), UserSession::getSessionID(), 'OVERRIDE REQUEST');
        MailServices::formNotify(request('reciever_emails'), UserSession::getSessionID(), 'override request', $id, 'overrideform');        
        return request()->all();
        

    }
    

    // update override
    public function updateOverride(){
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        // $response = request()->except(['ísDisable']);
        request()->merge([
            'dateoverride' => $now,
            'commited_date' => UserSession::formatDate(request()->commited_date),
        ]);
        
        // update
        DB::table('formoverride')
            ->where('overrideID', request('overrideID'))
            ->where('status', 0)
            ->update(request()->except([
                'overrideID', 'remarks', 
                'approvedby', 'empID_', 
                'status', 'recstat'
            ]));
        request()->merge([
            'dateoverride' => UserSession::formatDate($now, 'm/d/Y h:m A')
        ]);
        return request()->all();
    }
    // delete override
    public function deleteOverride($leaveID  = null){
        
        $affected = DB::table('formoverride')
        ->where(['overrideID'=>$leaveID, 'status'=>0])
        ->update(['recstat'=>1]);
        // ->delete();
        if($affected) {
            MailServices::send_email_Notify(request('reciever_emails'), UserSession::getSessionID(), 'OVERRIDE REQUEST FORM', 'Deleted his/her');
        }
        
    }
    // FOR APPPROVERS =====================================================================================
    // GET  APPROVERS LIST NAME AND EMAIL
    public function getOverrideApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformapprover eform right join employee emp on eform.empID_ = emp.empID where eform.Leave0Form = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Override0Form = 1 and eform.empID_ = :empiD', 
        [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalOverrideForm(){

        $data = DB::select('select eleave.*,
                            DATE_FORMAT(eleave.dateoverride, "%m/%d/%Y %h:%i %p") as dateoverride,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formoverride eleave left join eformapproverbyemp eform
                                on eleave.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eleave.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID
                            and eform.Override0Form = 1
                            and eleave.recstat != 1',
                            [UserSession::getSessionID()]);
        $response = [];
        foreach ($data as $value) {
            $endorsedId = explode(",", ($value->endorsedby_ ? $value->endorsedby_ : 'Null'));
            
            if($endorsedId){
                // $response[] = $empname;
                $temp = [];
                foreach ($endorsedId as $id) {
                    $sql = 'select CONCAT(fname," ",lname) as endorser 
                                from employee
                            where empID = "'.$id.'"';
                    
                    $empname = DB::select($sql);
                    if($empname) {
                        $temp[] = $empname[0]->endorser;
                    }
                }
                $value->endorsedby_  = implode(", ",$temp);

            }

            // get approver names
            $sql = 'select CONCAT(fname," ",lname) as approvedby
            from employee
                where empID = :approver';
            $approver = DB::select($sql, [$value->approvedby]);
            
            if($approver) {
                $value->approvedby = $approver[0]->approvedby;
            }
            $response[] = $value;
            
        }

        
        return $response;
    }    

    // approved / reject
    public function actionFormOverride(){
        $sqlQuery = '';
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();
        $user = UserSession::getSessionID();

        // get list of approvers email
        $mailReceivers = MailServices::getApproverEmail('overrideID', request('overrideID'), 'formoverride', 'Override0Form');
        
        // check for affected rows
        $rows = DB::select('select count(overrideID) as count from formoverride 
                    where (endorsedby_ NOT LIKE "%'.$user.'%" or endorsedby_ IS NULL)
                    and overrideID = :ID
                ', 
                [request('overrideID')]);
        

        switch (request('status')):
            // endore
            case 1:
                
                // find number of endorsers limit to 2
                $sqlQuery = 'select overrideID from formoverride 
                    where endorsedby_ LIKE "%,%"
                    and overrideID = :ID';
                     
                $checkendorser = DB::select($sqlQuery, [request('overrideID')]);
                if($checkendorser) {
                    return request()->all();
                }

                $sqlQuery = '
                update formoverride set status = :status,
                    remarks = "'.request('remarks').'",
                    endorsedby_ = 
                    IF(endorsedby_ IS NULL , "'.$user.'", 
                        CONCAT(
                            endorsedby_, ",", "'.$user.'"
                        )
                    ),
                    endorseddate = "'.$now.'"
                    
                where 
                    (endorsedby_ NOT LIKE "%'.$user.'%" or endorsedby_ IS NULL)
                and overrideID = :ID
                ';
                break;
            // approve
            case 2:
                $sqlQuery = 'update formoverride set status = :status,
                    approvedby = "'.$user.'", approveddate = "'.$now.'",
                    remarks = "'.request('remarks').'"
                    WHERE overrideID = :ID
                ';
                
                break;
            //  rejected
            case 3:
                $sqlQuery = 'update formoverride set status = :status,
                    approvedby = "'.$user.'", approveddate = "'.$now.'",
                    remarks = "'.request('remarks').'"
                    WHERE overrideID = :ID
                ';
                
                break;

            //  back to pending / cancelled
            case 0:
                // approved / rejected status but being cancelled
                if(request()->has('stat')){
                    $sqlQuery = 'update formoverride set status = :status,
                        approvedby = NULL, approveddate = NULL
                        WHERE overrideID = :ID
                    ';
                    request()->merge(['status' => request('stat')]);
                } 
                // endorsed status and cancelled
                else {
                    $sqlQuery = 'update formoverride set status = :status,
                        endorsedby_ = NULL, endorseddate = NULL
                        WHERE overrideID = :ID
                    ';
                }
                break;
        endswitch;
        // return $sqlQuery;
       
        DB::select($sqlQuery, [request('status'), request('overrideID')]);
        
        // // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            if($rows[0]->count == 0) {
                return request()->all();
            }

            MailServices::send_email_Notify($mailReceivers, request('empID_'), 'OVERRIDE FORM REQUEST', ' <br><br>Endorse his/her ');
            // MailServices::form_post_Notify($mailReceivers, request('empID_'), 'override form request', request('workID'), 'workreq', 'confirmed his/her submitted');
        }
        if(request('status') == 2){
            MailServices::send_email_Notify($mailReceivers, request('empID_'), 'OVERRIDE FORM REQUEST', ' <br><br>Approved his/her ');
        //     MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'rejected', request('leaveID'), 'leave');
        }
        if(request('status') == 3){
            MailServices::send_email_Notify($mailReceivers, request('empID_'), 'OVERRIDE FORM REQUEST', ' <br><br>Rejected his/her ');
        //     MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'rejected', request('leaveID'), 'leave');
        }
        if(request('status') == 0){
            $message = '';
            if(request()->has('stat')){
                $message = ' <br><br>Move Back to Endorsed his/her ';
            } 
            // endorsed status and cancelled
            else {
                $message = ' <br><br>Move Back to Pended his/her ';
            }
            MailServices::send_email_Notify($mailReceivers, request('empID_'), 'OVERRIDE FORM REQUEST', $message);
        //     MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'rejected', request('leaveID'), 'leave');
        }
        // else{
        //     // MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'LEAVE REQUEST', 'CANCELLED');
        //     MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'cancelled', request('leaveID'), 'leave');
        // }

        
        return request()->all();
    }

}
