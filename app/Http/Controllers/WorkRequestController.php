<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use Carbon\Carbon;
use DB;

class WorkRequestController extends Controller
{
     // ADD
     public function addWorkRequest(){
        date_default_timezone_set("Asia/Hong_Kong");
        $carbon_format = Carbon::parse(request('dateposted'))->format('Y');

        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);

        //merge datefile formatted date

        request()->merge([
            'datefiled' => date("Y-m-d"),
            'datefiled_datetime' => date("Y-m-d H:i:s"),
            'dateneed' => UserSession::formatDate(request()->dateneed),
            'date_from' => UserSession::formatDate(request()->date_from),
            'date_to' => UserSession::formatDate(request()->date_to),
        ]);


        $data = DB::table('formworkrequest')->insertGetId(request()->except(['isDisable', 'workID', 'approvedby', 'status','reciever_emails', 'attachment']));
            
           /*
            // $data = DB::select('select workID from formworkrequest order by workID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'workID' => $data[0]->workID]);


            // save attchment
            //folder by ID preparation for multiple files in single workID folder
            */

            $attachment = UserSession::IMG_Attachment('workrequest/'.$carbon_format.'/'.$data);
            if(count($attachment)){
                $attachment = $attachment[0];
                request()->merge([
                    'work_attachment' => $attachment
                ]);
            }
            else{
                $attachment = '';
            }
            /*
            // $attachment = $attachment[0][0];
            */
            
            DB::table('formworkrequest')
            ->where('workID', $data)
            ->update(['work_attachment' => $attachment]);

            $response = request()->except(['ísDisable']);
            $response = array_merge($response, ['status' => 0, 'workID' => $data]);


        
        // mail notification
        // return MailServices::getApproverEmail('workID', 360, 'formworkrequest', 'Work0Request');
        
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'WORK REQUEST', 'Work Request Form');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'work request request', $data, 'workreq');
        return $response;


    }

    // UPDATE
    public function updateWorkRequest(){
        date_default_timezone_set("Asia/Hong_Kong");
        $carbon_format = Carbon::parse(request('dateposted'))->format('Y');

        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);



        // save file
        $attachment = UserSession::IMG_Attachment('workrequest/'.$carbon_format.'/'.request('workID'));

        if(count($attachment)){
            $attachment = $attachment[0];
            request()->merge([
                'work_attachment' => $attachment
            ]);
        }
        else{
            $attachment = '';
        }

        request()->merge([
            // 'datefiled' => UserSession::formatDate(request()->datefiled),
            'datefiled' => date("Y-m-d"),
            'datefiled_datetime' => date("Y-m-d H:i:s"),
            'dateneed' => UserSession::formatDate(request()->dateneed),
            'date_from' => UserSession::formatDate(request()->date_from),
            'date_to' => UserSession::formatDate(request()->date_to),
        ]);

        $response = request()->except(['isDisable']);


        DB::table('formworkrequest')
            ->where('workID', request('workID'))
            ->update(request()->except(['isDisable', 'workID', 'approvedby', 'empID_', 'status', 'reciever_emails', 'attachment']));

        return $response;
    }

    // DELETE
    public function deleteWorkRequest($workID  = null){
        DB::table('formworkrequest')->where('workID', '=', $workID)
        ->update(['recstat' => 1]);
        // ->delete();
    }

    // GET
    public function getWorkRequestByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
            DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
            DATE_FORMAT(form.dateneed, "%m/%d/%Y") as dateneed,
            CONCAT(emp.fname," ", emp.lname) as approvedby_,
            (
                select CONCAT(emp.fname," ",emp.lname) from employee emp
                where form.exec_by = emp.empID
            ) as executedby_
            from formworkrequest form
            left join employee emp on
                form.approvedby = emp.empID
            where form.recstat != 1 and form.empID_ = :empid', [UserSession::getSessionID()]);
        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET LEAVE FORM EMPLOYEE APPROVERS
    public function getWorkRequestApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Work0Request = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Work0Request = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalWorkRequest(){
        // $data = DB::select('select * from formworkrequest where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [ UserSession::getSessionID(), UserSession::getSessionID() ]);
        // $data = DB::select('select ework.* from formworkrequest ework left join eformapproverbyemp eform on ework.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);

        $data = DB::select('select ework.*,
                            DATE_FORMAT(ework.datefiled, "%m/%d/%Y") as datefiled,
                            DATE_FORMAT(ework.dateneed, "%m/%d/%Y") as dateneed,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            pos.posname, dept.deptname, emp.mobile,
                            branch.branchname,
                            (
                                select CONCAT(emp.fname," ",emp.lname) from employee emp
                                where ework.approvedby = emp.empID
                            ) as approvedby_,
                            (
                                select CONCAT(emp.fname," ",emp.lname) from employee emp
                                where ework.exec_by = emp.empID
                            ) as executedby_

                            from formworkrequest ework
                            left join eformapproverbyemp eform
                                on ework.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = ework.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on emp.branchID_ = branch.branchID
                            where eform.approverID_ = :approverID 
                            and eform.Work0Request = 1
                            and ework.recstat != 1', [UserSession::getSessionID()]);

        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormWorkRequest(){
        date_default_timezone_set("Asia/Hong_Kong");

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            // DB::table('formworkrequest')
            // ->where('workID', request('workID'))
            // ->update([
            //     'status' => request('status'), 'approvedby'=> request('approvedby'),
            //     'approveddate'=> date("Y-m-d H:i:s"), 'remarks' => request('remarks')
            //     ]);
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'WORK REQUEST', 'APPROVED', 'Work Request Form');
            // MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'work request request', 'approved', request('workID'), 'workreq');
        }
        elseif(request('status') == 2){
            DB::table('formworkrequest')
            ->where('workID', request('workID'))
            ->update([
                'status' => request('status'), 'approvedby'=> request('approvedby'),
                'approveddate'=> date("Y-m-d H:i:s"), 'remarks' => request('remarks')
                ]);

            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'WORK REQUEST', 'REJECTED', 'Work Request Form');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'work request request', 'rejected', request('workID'), 'workreq');
        }
        elseif(request('status') == 0){
            return request()->all();
            DB::table('formworkrequest')
            ->where('workID', request('workID'))
            ->update([
                'status' => request('status'),
                'approvedby' => '', 'exec_by' => '',
                'approveddate'=> date("Y-m-d H:i:s"), 'remarks' => request('remarks')
            ]);
            
            
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'WORK REQUEST', 'CANCELLED', 'Work Request Form');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'work request request', 'cancelled', request('workID'), 'workreq');
        }
        elseif(request('status') == 3){
            DB::table('formworkrequest')
            ->where('workID', request('workID'))
            ->update([
                'status' => request('status'), 'exec_by'=> request('approvedby'),
                'exec_datetime'=> date("Y-m-d H:i:s"), 'remarks' => request('remarks'),

            ]);

            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'WORK REQUEST', 'EXECUTED', 'Work Request Form');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'work request request', 'executed', request('workID'), 'workreq');
        }
        elseif(request('status') == 5){
            // return request()->all();
            DB::table('formworkrequest')
            ->where('workID', request('workID'))
            ->update([
                'status' => 0,
                'approvedby' => '', 'exec_by' => '',
                'approveddate'=> date("Y-m-d H:i:s"), 'remarks' => request('remarks')
            ]);
            
            
            MailServices::send_email_Notify(request('reciever_emails'), request('approvedby'), 'WORK REQUEST', ' <br><br>move back to pending his/her ', 'Work Request Form');
            MailServices::form_post_Notify(request('reciever_emails'), request('approvedby'), 'work request request', request('workID'), 'workreq', 'move back to pending his/her submitted');

            request()->merge(['status' => 0]);
        }
        else{
            DB::table('formworkrequest')
            ->where('workID', request('workID'))
            ->update([
                'status' => request('status'),
                'confirm_datetime'=> date("Y-m-d H:i:s"), 'remarks' => request('remarks'),
            ]);

            MailServices::send_email_Notify(request('reciever_emails'), request('empID_'), 'WORK REQUEST', ' <br><br>confirmed his/her ', 'Work Request Form');
            MailServices::form_post_Notify(request('reciever_emails'), request('empID_'), 'work request request', request('workID'), 'workreq', 'confirmed his/her submitted');
            
            
            // MailServices::sendNotifyReviewed(request('approvedby'), request('email'), 'WORK REQUEST', 'CONFIRMED');
            // MailServices::formNotifyReviewed(request('approvedby'), request('email'), 'work request request', 'confirmed', request('workID'), 'workreq');
        }

        return request()->all();
    }

}
