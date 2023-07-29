<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
use DB;

class MeetingMinutesController extends Controller
{
    // search employee 
    public function searchMeetingEmp(){
        $data = DB::select('select empID, email,  CONCAT(fname,", ",lname) as fullname 
        from employee where (fname like :search OR lname like :search2) 
        AND empID not IN("00001" )
        AND status = 1
        order by fname limit 30',
                [request('keyword').'%', request('keyword').'%']);
        // UserSession::getSessionID()
        return $data;
    }

    // ADD
   public function addMeetingMinutes(){
    
        // $user = UserSession::getEmpKey()[0]->fullname;
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        $id = Carbon::now()->valueOf();
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'meetingID' => $id,
        ]);
        $response = request()->except(['ísDisable']);

        $data = DB::table('formmeetingminutes')->insert(request()->except(['isDisable', 'reciever_emails', 'fullname']));
            // $data = DB::select('select ccID from formcallingcard order by ccID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'ccID' => $data[0]->ccID]);
            // $response = array_merge($response, ['status' => 0]);

        // message
        $message= 'has created a minutes of meeting and added you as one of the attendees in the ';

        // mail notification
        MailServices::send_email_Notify(request('reciever_emails'), UserSession::getSessionID(), 'MINUTES OF MEETING', $message, 'Meeting Minutes Form');
        
        MailServices::form_post_Notify(request('reciever_emails'), request('empID_'), 'minutes of meeting', $id, 'minutes meeting');
        // form_post_Notify($email, $from, $formType = null, $formID = null, $type = '', $message = '')
        return $response;
    }

    // Acknowledge
    public function acknowledgeMeetingMinutes(){
        $response = request()->except(['isDisable']);
        
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
        ]);

        DB::table('formmeetingminutes')
            ->where('meetingID', request('meetingID'))
            ->update(request()->except(['isDisable', 'meetingID', 'reciever_emails', 'empID_', 'fullname']));
        // request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);

        $message= 'updated the minutes of meeting form and notify you as one of the attendees';
        // MailServices::send_email_Notify(request('reciever_emails'), UserSession::getSessionID(), '', $message, 'Meeting Minutes Form');
        
        return $response;
        
    }

    // UPDATE
    public function updateMeetingMinutes(){
        // return request()->all();        
        $response = request()->except(['isDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
        ]);

        DB::table('formmeetingminutes')
            ->where('meetingID', request('meetingID'))
            ->update(request()->except(['isDisable', 'meetingID', 'reciever_emails', 'empID_', 'fullname']));

        $message= 'updated the minutes of meeting form and notify you as one of the attendees';
        // MailServices::send_email_Notify(request('reciever_emails'), UserSession::getSessionID(), '', $message, 'Meeting Minutes Form');
        return $response;
    }

    // DELETE
    public function delMeetingMinutes($ccID  = null){
        $data = DB::table('formmeetingminutes')->where('meetingID', '=', $ccID)
        ->update(['recstat' => 404]);
        // ->delete();
        return $data;
    }
    // GET
    public function getMeetingMinutesByEmployee(){
        // $data = DB::select('select form.*, CONCAT(emp.fname," ", emp.lname) as approvedby from formoffset form left join employee emp on
        // form.approvedby = emp.empID where form.empID_ = :empid', [UserSession::getSessionID()]);
        // SESSION ID
        
        $data = DB::select("select cc.*,
        CONCAT(emp.fname,' ',emp.lname) as fullname
        from formmeetingminutes cc
        INNER JOIN employee emp
        on cc.empID_ = emp.empID
        where cc.recstat = 0 and
        (cc.empID_ = :empID or cc.attendeelistId  like :attendee)", [UserSession::getSessionID(), '%'.UserSession::getSessionID().'%']);

        return $data;

    }

    // FOR APPROVERS ====================================================================================================================================
    // GET LEAVE FORM EMPLOYEE APPROVERS
    public function getCallingCardApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Calling0Card0Request = 1');
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Calling0Card0Request = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);

        $data = FormApproverService::getFormApproverByUser('Calling0Card0Request');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalCallingCardRequest(){
        // $data = DB::select('select emp.mobile, cc.* from formcallingcard cc inner join employee emp on cc.empID_ =emp.empID
        // where (cc.status = 0 and cc.empID_ != :empID) OR cc.approvedby = :empid',
        // [UserSession::getSessionID(), UserSession::getSessionID()]);
        // $data = DB::select('select eccard.* from formcallingcard eccard left join eformapproverbyemp eform on eccard.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);

        $data = DB::select('select eccard.*,
                            DATE_FORMAT(eccard.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            emp.mobile, emp.lname, emp.fname, emp.email,
                            branch.branchname, pos.posname
                            from formcallingcard eccard left join eformapproverbyemp eform
                                on eccard.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eccard.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID and eccard.recstat = 0', [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormCallingCard(){
        DB::table('formcallingcard')
        ->where('ccID', request('ccID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'CALLING CARD REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'calling card request', 'approved', request('leaveID'), 'callcard');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'CALLING CARD REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'calling card request', 'rejected', request('leaveID'), 'callcard');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'CALLING CARD REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'calling card request', 'cancelled', request('leaveID'), 'callcard');
        }
        return request()->all();
    }

}

