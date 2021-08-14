<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Services\UserSession;
use App\Services\MailServices;
use Carbon\Carbon;

class OverrideController extends Controller
{
    // get override
    function getOverride() {
        $data = DB::select('select form.*,
        DATE_FORMAT(form.dateoverride, "%m/%d/%Y") as dateoverride,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formoverride form 
        left join employee emp on
        form.approvedby = emp.empID where form.recstat != 1 and form.empID_ = :empid', [UserSession::getSessionID()]);

        return $data;
    }

    // Add override
    function addOverride(){
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();
        request()->merge([
            'dateoverride' => $now,
            'empID_' => UserSession::getSessionID(),
            'commited_date' => UserSession::formatDate(request()->commited_date)
        ]);
        
        $id = DB::table('formoverride')->insertGetId(request()->except(['reciever_emails']));
        request()->merge(['overrideID' => $id]);
        // mail notification
        // MailServices::sendNotify(request('reciever_emails'), UserSession::getSessionID(), 'OVERRIDE REQUEST');
        // MailServices::formNotify(request('reciever_emails'), UserSession::getSessionID(), 'override request', $id, 'leave');        
        return request()->all();
        

    }
    

    // update override

    // delete override

    // FOR APPPROVERS =====================================================================================
    // GET  APPROVERS LIST NAME AND EMAIL
    public function getOverrideApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformapprover eform right join employee emp on eform.empID_ = emp.empID where eform.Leave0Form = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Override0Form = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalOverrideForm(){
        // $data = DB::select('select * from formleave where (status = 0 and empID_ != :empID) OR approvedby = :empid', //['000001']);
        $data = DB::select('select eleave.*,
                            DATE_FORMAT(eleave.datefiled, "%m/%d/%Y") as datefiled,
                            DATE_FORMAT(eleave.datestart, "%m/%d/%Y") as datestart,
                            DATE_FORMAT(eleave.dateend, "%m/%d/%Y") as dateend,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formleave eleave left join eformapproverbyemp eform
                                on eleave.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eleave.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID
                            and eform.Leave0Form = 1
                            and eleave.recstat != 1',
                            [UserSession::getSessionID()]);
        return $data;
    }    

    // approved / reject
    public function actionOverrideForm(){

    }

}
