<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use DB;

class IncidentReportController extends Controller
{
    // ADD
    public function addIncidentReport(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID() ]);
        $data = DB::table('formincidentreport')->insertGetId(request()->except(['isDisable', 'incidentID', 'remarks', 'approvedby', 'reciever_emails']));
            // $data = DB::select('select incidentID from formincidentreport order by incidentID desc limit 1');
            // GET LAST INDEX
            request()->merge(['status' => 0, 'incidentID' => $data]);

        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'INCIDENT REPORT REQUEST');
        return request()->except(['isDisable']);
    }

    // UPDATE
    public function updateIncidentReport(){
        // SESSION ID
        DB::table('formincidentreport')
            ->where('incidentID', request('incidentID'))
            ->update(request()->except(['isDisable', 'incidentID', 'remarks', 'approvedby', 'datefiled', 'reciever_emails']));
        request()->merge(['empID_' => UserSession::getSessionID() , 'status' => 0]);
        return request()->except(['isDisable']);
    }

    // DELETE
    public function deleteIncidentReport($incidentID  = null){
        DB::table('formincidentreport')->where('incidentID', '=', $incidentID)
        ->update(['recstat' => 1]);
        // ->delete();
    }

    // GET
    public function getIncidentReportByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formincidentreport form left join employee emp on
        form.approvedby = emp.empID where form.recstat !=1 and form.empID_ = :empid', [UserSession::getSessionID() ]);

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET IncidentReport FORM EMPLOYEE APPROVERS
    public function getIncidentReportApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Incident0Report = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Incident0Report = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalIncidentReportRequest(){
        // $data = DB::select('select * from formincidentreport where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [UserSession::getSessionID(), UserSession::getSessionID()]);
        // $data = DB::select('select eIReport.* from formincidentreport eIReport left join eformapproverbyemp eform on eIReport.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);

        $data = DB::select('select eIReport.*,
                            DATE_FORMAT(eIReport.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formincidentreport eIReport left join eformapproverbyemp eform
                                on eIReport.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eIReport.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID and eIReport.recstat != 1', [UserSession::getSessionID()]);

        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormIncidentReport(){
        DB::table('formincidentreport')
        ->where('incidentID', request('incidentID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

         // MAIL NOTIFICATION
         if(request('status') == 1)
         {
             MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'INCIDENT REPORT REQUEST', 'APPROVED');
             MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'approved', request('incidentID'), 'leave');
         }
         elseif(request('status') == 2){
             MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'INCIDENT REPORT REQUEST', 'REJECTED');
             MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'rejected', request('incidentID'), 'leave');
         }
         else{
             MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'INCIDENT REPORT REQUEST', 'CANCELLED');
             MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'cancelled', request('incidentID'), 'leave');
         }
        return request()->all();
    }

}
