<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
use DB;

class UrgentCheckController extends Controller
{
     // ADD
     public function addUrgentCheck(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID() ]);
        $response = request()->except(['ísDisable']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);


        $data = DB::table('formurgentcheck')->insertGetId(request()->except(['isDisable', 'urgentID', 'approvedby', 'reciever_emails']));
            // $data = DB::select('select urgentID from formurgentcheck order by urgentID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'urgentID' => $data[0]->urgentID]);
            $response = array_merge($response, ['status' => 0, 'urgentID' => $data]);

        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'URGENT CHECK REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'urgent check request', $data, 'urgentcheck');
        return $response;
    }
    // UPDATE
    public function updateUrgentCheck(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);

        DB::table('formurgentcheck')
            ->where('urgentID', request('urgentID'))
            ->update(request()->except(['isDisable', 'urgentID', 'approvedby', 'empID_', 'status', 'reciever_emails']));

        return $response;
    }

    // DELETE
    public function deleteUrgentCheck($urgentID  = null){
        DB::table('formurgentcheck')->where('urgentID', '=', $urgentID)
        ->update(['recstat' => 1]);
        // ->delete();
    }

    // GET
    public function getUrgentCheckByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formurgentcheck form left join employee emp on
        form.approvedby = emp.empID where form.recstat != 1  and form.empID_ = :empid', [UserSession::getSessionID()]);
        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET LEAVE FORM EMPLOYEE APPROVERS
    public function getUrgentCheckApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Urgent0Check = 1');

        $data = FormApproverService::getFormApproverByUser('Urgent0Check');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalUrgentCheckRequest(){
        // $data = DB::select('select * from formurgentcheck where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [ UserSession::getSessionID(), UserSession::getSessionID() ]);
        // $data = DB::select('select eurgent.* from formurgentcheck eurgent left join eformapproverbyemp eform on eurgent.empID_ = eform.empID_ where eform.approverID_ = :approverID and status != 3', [UserSession::getSessionID()]);

        $data = DB::select('select eurgent.*,
                            DATE_FORMAT(eurgent.datefiled, "%m/%d/%Y") as datefiled,
                            DATE_FORMAT(eurgent.datereleased, "%m/%d/%Y") as datereleased,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formurgentcheck eurgent left join eformapproverbyemp eform
                                on eurgent.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eurgent.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID and eurgent.recstat != 1', [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormUrgentCheck(){
        DB::table('formurgentcheck')
        ->where('urgentID', request('urgentID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'URGENT CHECK REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'urgent check request', 'approved', request('urgentID'), 'urgentcheck');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'URGENT CHECK REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'urgent check request', 'rejected', request('urgentID'), 'urgentcheck');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'URGENT CHECK REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'urgent check request', 'cancelled', request('urgentID'), 'urgentcheck');
        }
        return request()->all();
    }

}