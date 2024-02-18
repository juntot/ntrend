<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
use DB;

class UnderTimeController extends Controller
{
    // ADD
    public function addUndertime(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        $response = request()->except(['ísDisable']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'date_undertime' => UserSession::formatDate(request()->date_undertime),
        ]);

        $data = DB::table('formundertime')->insertGetId(request()->except(['isDisable', 'undertimeID', 'remarks', 'approvedby', 'reciever_emails']));
            // $data = DB::select('select undertimeID from formundertime order by undertimeID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'undertimeID' => $data[0]->undertimeID]);
            $response = array_merge($response, ['status' => 0, 'undertimeID' => $data]);

        // MAIL NOTIFICATION
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'UNDERTIME REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'undertime request', $data, 'undertime');
        return $response;
    }
    // UPDATE
    public function updateUndertime(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'date_undertime' => UserSession::formatDate(request()->date_undertime),
        ]);

        // update
        DB::table('formundertime')
            ->where('undertimeID', request('undertimeID'))
            ->update(request()->except(['isDisable', 'undertimeID', 'remarks', 'approvedby', 'reciever_emails', 'empID_', 'status']));

        return $response;
    }

    // DELETE
    public function deleteUndertime($undertimeID  = null){
        DB::table('formundertime')->where('undertimeID', '=', $undertimeID)
        ->update(['recstat'=>404]);
        // ->delete();
    }

    // GET
    public function getUndertimeByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        DATE_FORMAT(form.date_undertime, "%m/%d/%Y") as date_undertime,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formundertime form left join employee emp on
        form.approvedby = emp.empID where form.recstat = 0 and form.empID_ = :empid', [UserSession::getSessionID()]);

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET UNDERTIME FORM EMPLOYEE APPROVERS
    public function getUndertimeApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Undertime0Request = 1');
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Undertime0Request = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        $data = FormApproverService::getFormApproverByUser('Undertime0Request');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalUndertimeRequest(){
        // $data = DB::select('select eundertime.* from formundertime eundertime left join eformapproverbyemp eform on eundertime.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);
        $data = DB::select('select eundertime.*,
                            DATE_FORMAT(eundertime.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formundertime eundertime left join eformapproverbyemp eform
                                on eundertime.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eundertime.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID 
                            and eform.Undertime0Request = 1
                            and eundertime.recstat = 0', [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormUndertime(){
        DB::table('formundertime')
        ->where('undertimeID', request('undertimeID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);


        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'UNDERTIME REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'undertime request', 'approved', request('undertimeID'), 'undertime');
        }elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'UNDERTIME REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'undertime request', 'rejected', request('undertimeID'), 'undertime');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'UNDERTIME REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'undertime request', 'cancelled', request('undertimeID'), 'undertime');
        }
        return request()->all();
    }

}
