<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
use DB;

class LoanController extends Controller
{
   // ADD
   public function addLoan(){
    // Session ID
    request()->merge(['empID_' => UserSession::getSessionID() ]);
    $response = request()->except(['ísDisable']);
    request()->merge([
        'datefiled' => UserSession::formatDate(request()->datefiled)
    ]);

    $data = DB::table('formloan')->insertGetId(request()->except(['isDisable', 'loanID', 'remarks', 'eula', 'approvedby', 'reciever_emails']));
        // $data = DB::select('select loanID from formloan order by loanID desc limit 1');
        // GET LAST INDEX
        // request()->merge(['status' => 0, 'loanID' => $data[0]->loanID]);
        $response = array_merge($response, ['status' => 0, 'loanID' => $data]);

    // mail notification
    MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'COMPANY LOAN REQUEST');
    MailServices::formNotify(request('reciever_emails'), request('empID_'), 'company loan request', $data, 'comploan');
    return $response;
}
// UPDATE
public function updateLoan(){
    // SESSION ID
    DB::table('formloan')
        ->where('loanID', request('loanID'))
        ->update(request()->except(['isDisable', 'loanID', 'remarks', 'eula', 'approvedby' , 'datefiled', 'reciever_emails']));
    request()->merge(['empID_' => UserSession::getSessionID() , 'status' => 0]);
    return request()->except(['isDisable']);
}

// DELETE
public function deleteLoan($loanID  = null){
    DB::table('formloan')->where('loanID', '=', $loanID)
    ->update(['recstat' => 1]);
    // ->delete();
}

// GET
public function getLoanByEmployee(){
    // SESSION ID
    $data = DB::select('select form.*,
    DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
    CONCAT(emp.fname," ", emp.lname) as approvedby from formloan form left join employee emp on
    form.approvedby = emp.empID where form.recstat !=1 and form.empID_ = :empid', [UserSession::getSessionID() ]);

    return $data;
}

// FOR APPROVERS ====================================================================================================================================
// GET Loan FORM EMPLOYEE APPROVERS
public function getLoanApprover(){
    // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Company0Loan = 1');
    // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Company0Loan = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);

    $data = FormApproverService::getFormApproverByUser('Company0Loan');
    return $data;
}

// GET FORMS FOR APPROVAL
public function approvalLoanRequest(){
    // $data = DB::select('select * from formloan where (status = 0 and empID_ != :empID) OR approvedby = :empid',
    // [ UserSession::getSessionID(), UserSession::getSessionID() ]);
    // $data = DB::select('select eformloan.* from formloan eformloan left join eformapproverbyemp eform on eformloan.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);

    $data = DB::select('select eformloan.*,
                            DATE_FORMAT(eformloan.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formloan eformloan left join eformapproverbyemp eform
                                on eformloan.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eformloan.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID and eformloan.recstat != 1', [UserSession::getSessionID()]);
    return $data;
}

// IE APPROVE / REJECTED
public function actionFormLoan(){
    DB::table('formloan')
    ->where('loanID', request('loanID'))
    ->update([
        'status' => request('status'), 'approvedby'=> request('approvedby'),
        'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
        ]);

    // MAIL NOTIFICATION
    if(request('status') == 1)
    {
        MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'COMPANY LOAN REQUEST', 'APPROVED');
        MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'company loan request', 'approved', request('loanID'), 'comploan');
    }
    elseif(request('status') == 2){
        MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'COMPANY LOAN REQUEST', 'REJECTED');
        MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'company loan request', 'rejected', request('loanID'), 'comploan');
    }
    else{
        MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'COMPANY LOAN REQUEST', 'CANCELLED');
        MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'company loan request', 'cancelled', request('loanID'), 'comploan');
    }
    return request()->all();
}

}
