<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
use DB;

class MIISController extends Controller
{
    // ADD
    public function addMIIS(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        $response = request()->except(['ísDisable']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);

        $data = DB::table('formMIIS')->insertGetId(request()->except(['isDisable', 'miisID', 'remarks', 'approvedby', 'reciever_emails']));
            // $data = DB::select('select miisID from formMIIS order by miisID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'miisID' => $data[0]->miisID]);
            $response = array_merge($response, ['status' => 0, 'miisID' => $data]);

        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'MARKETING ITEM INFORMATION SHEET REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'marketing item information sheet request', $data, 'miis');
        return $response;
    }
    // UPDATE
    public function updateMIIS(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);

        DB::table('formMIIS')
            ->where('miisID', request('miisID'))
            ->update(request()->except(['isDisable', 'miisID', 'remarks', 'approvedby', 'empID_', 'status', 'reciever_emails']));

        return $response;
    }

    // DELETE
    public function deleteMIIS($miisID  = null){
        DB::table('formMIIS')->where('miisID', '=', $miisID)
        ->update(['recstat' => 404]);
        // ->delete();
    }

    // GET
    public function getMIISByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*, CONCAT(emp.fname," ", emp.lname) as approvedby from formMIIS form left join employee emp on
        form.approvedby = emp.empID where form.recstat = 0 and form.empID_ = :empid', [UserSession::getSessionID()]);

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET LEAVE FORM EMPLOYEE APPROVERS
    public function getMIISApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.MIIS = 1');
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.MIIS = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);

        $data = FormApproverService::getFormApproverByUser('MIIS');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalMIISRequest(){
        // $data = DB::select('select * from formMIIS where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [ UserSession::getSessionID(), UserSession::getSessionID() ]);
        // $data = DB::select('select emiis.* from formMIIS emiis left join eformapproverbyemp eform on emiis.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);

        $data = DB::select('select emiis.*,
                            DATE_FORMAT(emiis.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, dept.deptname
                            from formMIIS emiis left join eformapproverbyemp eform
                                on emiis.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = emiis.empID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID and emiis.recstat = 0', [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormMIIS(){
        DB::table('formMIIS')
        ->where('miisID', request('miisID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'MARKETING ITEM INFORMATION SHEET REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), '(miis) request', 'approved', request('miiseID'), 'miis');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'MARKETING ITEM INFORMATION SHEET REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), '(miis) request', 'rejected', request('miiseID'), 'miis');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'MARKETING ITEM INFORMATION SHEET REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'marketing item information sheet request', 'cancelled', request('miiseID'), 'miis');
        }
        return request()->all();
    }

}
