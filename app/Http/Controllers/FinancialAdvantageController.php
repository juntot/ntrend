<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use DB;

class FinancialAdvantageController extends Controller
{
    // ADD
    public function addFinancialAdvantage(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        $response = request()->except(['ísDisable']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);

        $data = DB::table('formfinancialadvantage')->insertGetId(request()->except(['isDisable', 'faID', 'remarks', 'approvedby', 'reciever_emails']));
            // $data = DB::select('select faID from formfinancialadvantage order by faID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'faID' => $data[0]->faID]);
            $response = array_merge($response, ['status' => 0, 'faID' => $data]);

        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'FINANCIAL ADVANTAGE REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'financial advantage request', $data, 'fadvantage');
        return $response;
    }
    // UPDATE
    public function updateFinancialAdvantage(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);

        DB::table('formfinancialadvantage')
            ->where('faID', request('faID'))
            ->update(request()->except(['isDisable', 'faID', 'remarks', 'approvedby', 'empID_', 'status', 'reciever_emails']));
        return $response;
    }

    // DELETE
    public function deleteFinancialAdvantage($faID  = null){
        DB::table('formfinancialadvantage')->where('faID', '=', $faID)
        ->update(['recstat' => 1]);
        // ->delete();
    }

    // GET
    public function getFinancialAdvantageByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formfinancialadvantage form left join employee emp on
        form.approvedby = emp.empID where form.recstat != 1 and form.empID_ = :empid', [UserSession::getSessionID()]);
        return $data;
    }

   // FOR APPROVERS ====================================================================================================================================
    // GET LEAVE FORM EMPLOYEE APPROVERS
    public function getFinancialAdvantageApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Financial0Advance = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Financial0Advance = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalFinancialAdvantageRequest(){
        // $data = DB::select('select * from formfinancialadvantage where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [UserSession::getSessionID(), UserSession::getSessionID()]);
        // $data = DB::select('select efadvantage.* from formfinancialadvantage efadvantage left join eformapproverbyemp eform on efadvantage.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);
        $data = DB::select('select efadvantage.*,
                            DATE_FORMAT(efadvantage.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname , dept.deptname
                            from formfinancialadvantage efadvantage left join eformapproverbyemp eform
                                on efadvantage.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = efadvantage.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            where efadvantage.recstat != 1 and
                            eform.approverID_ = :approverID', [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormFinancialAdvantage(){
        DB::table('formfinancialadvantage')
        ->where('faID', request('faID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'FINANCIAL ADVANTAGE REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'financial advantage request', 'approved', request('faID'), 'fadvantage');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'FINANCIAL ADVANTAGE REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'financial advantage request', 'rejected', request('faID'), 'fadvantage');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'FINANCIAL ADVANTAGE REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'financial advantage request', 'cancelled', request('faID'), 'fadvantage');
        }
        return request()->all();
    }

}