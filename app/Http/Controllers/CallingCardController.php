<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
use DB;

class CallingCardController extends Controller
{
    // ADD
   public function addCallingCard(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
        ]);

        $data = DB::table('formcallingcard')->insertGetId(request()->except(['isDisable', 'ccID','mobile', 'approvedby', 'reciever_emails']));
            // $data = DB::select('select ccID from formcallingcard order by ccID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'ccID' => $data[0]->ccID]);
            $response = array_merge($response, ['status' => 0, 'ccID' => $data]);

        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'CALLING CARD REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'calling card request', $data, 'callcard');
        return $response;
    }

    // UPDATE
    public function updateCallingCard(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['isDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
        ]);

        DB::table('formcallingcard')
            ->where('ccID', request('ccID'))
            ->update(request()->except(['isDisable', 'ccID', 'mobile', 'approvedby', 'empID_', 'status', 'reciever_emails']));
        // request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        return $response;
    }

    // DELETE
    public function deleteCallingCard($ccID  = null){
        DB::table('formcallingcard')->where('ccID', '=', $ccID)
        ->update(['recstat' => 1]);
        // ->delete();
    }
    // GET
    public function getCallingCardByEmployee(){
        // $data = DB::select('select form.*, CONCAT(emp.fname," ", emp.lname) as approvedby from formoffset form left join employee emp on
        // form.approvedby = emp.empID where form.empID_ = :empid', [UserSession::getSessionID()]);
        // SESSION ID
        $data = DB::select('select emp.mobile,
            DATE_FORMAT(cc.datefiled, "%m/%d/%Y") as datefiled,
            (select temp.lname from employee temp where temp.empID = cc.approvedby) as approvedby,
            cc.ccID, cc.empID_,
            cc.mobile2, cc.product, cc.ntmc, cc.apbw, cc.philcrest, cc.solid,
            cc.tyreplus, cc.approveddate, cc.remarks, cc.status from formcallingcard cc
            INNER JOIN employee emp
            on cc.empID_ = emp.empID
            where cc.recstat != 1 and
            cc.empID_ = :empid', [UserSession::getSessionID()]);

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
                            where eform.approverID_ = :approverID and eccard.recstat != 1', [UserSession::getSessionID()]);
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

