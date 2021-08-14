<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use DB;

class OvertimeController extends Controller
{
    // ADD
    public function addOvertime(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        $response = request()->except(['ísDisable']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'date_overtime' => UserSession::formatDate(request()->date_overtime)
        ]);

        $data = DB::table('formovertime')->insertGetId(request()->except(['isDisable', 'overtimeID', 'remarks', 'approvedby', 'reciever_emails']));
            // $data = DB::select('select overtimeID from formovertime order by overtimeID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'overtimeID' => $data[0]->overtimeID]);
            $response = array_merge($response, ['status' => 0, 'overtimeID' => $data]);

        // MAIL NOTIFICATION
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'UNDERTIME REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'undertime request', $data, 'undertime');
        return $response;
    }
    // UPDATE
    public function updateOvertime(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'date_overtime' => UserSession::formatDate(request()->date_overtime)
        ]);

        // update
        DB::table('formovertime')
            ->where('overtimeID', request('overtimeID'))
            ->update(request()->except(['isDisable', 'overtimeID', 'remarks', 'approvedby', 'reciever_emails', 'empID_', 'status']));

        return $response;
    }

    // DELETE
    public function deleteOvertime($overtimeID  = null){
        DB::table('formovertime')->where('overtimeID', '=', $overtimeID)
        ->update(['recstat'=>1]);
        // ->delete();
    }

    // GET
    public function getOvertimeByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        DATE_FORMAT(form.date_overtime, "%m/%d/%Y") as date_overtime,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formovertime form left join employee emp on
        form.approvedby = emp.empID where form.recstat != 1 and form.empID_ = :empid', [UserSession::getSessionID()]);

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET UNDERTIME FORM EMPLOYEE APPROVERS
    public function getOvertimeApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Undertime0Request = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Overtime0Request = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalOvertimeRequest(){
        // $data = DB::select('select eovertime.* from formovertime eovertime left join eformapproverbyemp eform on eovertime.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);
        $data = DB::select('select eovertime.*,
                            DATE_FORMAT(eovertime.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formovertime eovertime left join eformapproverbyemp eform
                                on eovertime.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eovertime.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID 
                            and
                                eform.Overtime0Request = 1
                            and 
                                eovertime.recstat != 1', [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormOvertime(){
        DB::table('formovertime')
        ->where('overtimeID', request('overtimeID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);


        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'OVERTIME REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'overtime request', 'approved', request('overtimeID'), 'overtime');
        }elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'OVERTIME REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'overtime request', 'rejected', request('overtimeID'), 'overtime');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'OVERTIME REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'overtime request', 'cancelled', request('overtimeID'), 'overtime');
        }
        return request()->all();
    }
}
