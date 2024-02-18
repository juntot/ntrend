<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
use DB;

class SalaryDiscrepancyController extends Controller
{
   // ADD
   public function addSalaryDiscrepancy(){
    // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        $response = request()->except(['ísDisable']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'discrepancydate' => UserSession::formatDate(request()->discrepancydate)
        ]);



        $data = DB::table('formsalarydiscrepancy')->insertGetId(request()->except(['isDisable', 'saldiscID', 'remarks', 'approvedby', 'reciever_emails']));
            // $data = DB::select('select saldiscID from formsalarydiscrepancy order by saldiscID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'saldiscID' => $data[0]->saldiscID]);
            $response = array_merge($response, ['status' => 0, 'saldiscID' => $data]);

        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'SALARY DISCREPANCY REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'salary discrepancy request', $data, 'saldisc');
        return $response;
    }
    // UPDATE
    public function updateSalaryDiscrepancy(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'discrepancydate' => UserSession::formatDate(request()->discrepancydate)
        ]);

        // update
        DB::table('formsalarydiscrepancy')
            ->where('saldiscID', request('saldiscID'))
            ->update(request()->except(['isDisable', 'saldiscID', 'remarks', 'approvedby', 'reciever_emails','empID_', 'status']));

        return $response;
    }

    // DELETE
    public function deleteSalaryDiscrepancy($saldiscID  = null){
        DB::table('formsalarydiscrepancy')->where('saldiscID', '=', $saldiscID)
        ->update(['recstat' => 404]);
        // ->delete();
    }

    // GET
    public function getSalaryDiscrepancyByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        DATE_FORMAT(form.discrepancydate, "%m/%d/%Y") as discrepancydate,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formsalarydiscrepancy form left join employee emp on
        form.approvedby = emp.empID where form.recstat = 0 and form.empID_ = :empid', [UserSession::getSessionID()]);

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET SalaryDiscrepancy FORM EMPLOYEE APPROVERS
    public function getSalaryDiscrepancyApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Salary0Discrepancy = 1');
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp
        // on eform.approverID_ = emp.empID where eform.Salary0Discrepancy = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);

        $data = FormApproverService::getFormApproverByUser('Salary0Discrepancy');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalSalaryDiscrepancyRequest(){
        // $data = DB::select('select * from formsalarydiscrepancy where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [UserSession::getSessionID(), UserSession::getSessionID()]);
        // $data = DB::select('select esaldisc.* from formsalarydiscrepancy esaldisc left join eformapproverbyemp eform on esaldisc.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);
        $data = DB::select('select esaldisc.*,
                            DATE_FORMAT(esaldisc.datefiled, "%m/%d/%Y") as datefiled,
                            DATE_FORMAT(esaldisc.discrepancydate, "%m/%d/%Y") as discrepancydate,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formsalarydiscrepancy esaldisc left join eformapproverbyemp eform
                                on esaldisc.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = esaldisc.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID and esaldisc.recstat = 0', [UserSession::getSessionID()]);

        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionformSalaryDiscrepancy(){
        DB::table('formsalarydiscrepancy')
        ->where('saldiscID', request('saldiscID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'SALARY DISCREPANCY REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'salary discrepancy request', 'approved', request('saldiscID'), 'saldisc');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'SALARY DISCREPANCY REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'salary discrepancy request', 'rejected', request('saldiscID'), 'saldisc');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'SALARY DISCREPANCY REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'salary discrepancy request', 'cancelled', request('saldiscID'), 'saldisc');
        }
        return request()->all();
    }

}
