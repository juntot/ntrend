<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
use Carbon\Carbon;
use DB;

class IncidentReportController extends Controller
{
    // ADD
    public function addIncidentReport(){
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();
        // Session ID
        request()->merge([
            'empID_' => UserSession::getSessionID(),
            'datefiled' => $now
        ]);

        
        $data = DB::table('formincidentreport')->insertGetId(request()->except(['isDisable', 'incidentID', 'remarks', 'approvedby', 'reciever_emails', 'actionTaken', 'explanation']));
            // GET LAST INDEX
            request()->merge(['status' => 0, 'incidentID' => $data]);

        // persons involved approvers 
        $personinvolveApprovers = $this->getIncidentReportApprover(request('personsinvolve'));
        $tagbyName = UserSession::getEmpKey();
        
        $tagEmpApprovers = [request('personsinvolve_email')];
        if(count($personinvolveApprovers)){
            foreach ($personinvolveApprovers as $value) {
                $tagEmpApprovers[] = $value->email;
            }
            
        }

        $reciever_emails = $this->getIncidentReportApprover(UserSession::getSessionID());

        foreach ($reciever_emails as $value) {
            # code...
            $tagEmpApprovers[] = $value->email;
        }
        $tagEmpApprovers[] = request('personsinvolve_email');

        
        // form notification for requestor approvers
        MailServices::formNotify($tagEmpApprovers, request('empID_'), 'incident report request', $data, 'incident report');

        // notificaiton for tagged persons approvers
        MailServices::send_email_Notify($tagEmpApprovers, request('personsinvolve'), 'INCIDENT REPORT', 'was tagged by '.$tagbyName[0]->fullname.' from his/her ', 'Incident Report Request');
        
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
        $affected = DB::table('formincidentreport')
        ->where('incidentID', '=', $incidentID)
        ->update(['recstat' => 404]);
        // ->delete();
        
        $record = DB::table('formincidentreport')
                ->select('personsinvolve','personsinvolve_email')
                ->where('incidentID', $incidentID)
                ->first();

        if($affected) {
            $personinvolveApprovers = $this->getIncidentReportApprover($record->personsinvolve);
            $tagbyName = UserSession::getEmpKey();
            
            $tagEmpApprovers = [$record->personsinvolve_email];
            if(count($personinvolveApprovers)){
                foreach ($personinvolveApprovers as $value) {
                    $tagEmpApprovers[] = $value->email;
                }
                
            }

            // get approvers email direct
            $reciever_emails = $this->getIncidentReportApprover(UserSession::getSessionID());

            foreach ($reciever_emails as $value) {
                # code...
                $tagEmpApprovers[] = $value->email;
            }

            $tagEmpApprovers[] = $record->personsinvolve_email;

            MailServices::send_email_Notify($tagEmpApprovers, request('empID_'), 'LEAVE REQUEST', 'Deleted his/her');
        }

    }

    // GET
    public function getIncidentReportByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ", emp.lname) as approvedby,
        (
            select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
            where subemp.empID = form.personsinvolve
        ) as search_employee,
        (
            select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
            where subemp.empID = form.empID_
        ) as reportedby,
        (
            select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
            where subemp.empID = form.endorse1
        ) as search_endorse_employee,
        (
            select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
            where subemp.empID = form.endorse2
        ) as search_endorse_employee2

        from formincidentreport form left join employee emp on
        form.approvedby = emp.empID 
        where form.empID_ = :empid 
            OR form.personsinvolve = :personinvolve
            and form.recstat = 0', 
        ['empid'=> UserSession::getSessionID(), 'personinvolve'=> UserSession::getSessionID()]);

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET IncidentReport FORM EMPLOYEE APPROVERS
    public function getIncidentReportApprover($userID = ''){
        if(!$userID)
        $userID = UserSession::getSessionID();
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Incident0Report = 1');
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Incident0Report = 1 and eform.empID_ = :empiD', [$userID]);

        $data = FormApproverService::getFormApproverByUser('Incident0Report');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalIncidentReportRequest(){
        // $data = DB::select('select * from formincidentreport where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [UserSession::getSessionID(), UserSession::getSessionID()]);
        // $data = DB::select('select eIReport.* from formincidentreport eIReport left join eformapproverbyemp eform on eIReport.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);

        $data = DB::select('select distinct(eIReport.incidentID), eIReport.*,
                            DATE_FORMAT(eIReport.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eIReport.approvedby
                            ) as approvedby,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eIReport.personsinvolve
                            ) as search_employee,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eIReport.empID_
                            ) as reportedby,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eIReport.endorse1
                            ) as search_endorse_employee,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eIReport.endorse2
                            ) as search_endorse_employee2

                            from formincidentreport eIReport left join eformapproverbyemp eform
                                on eIReport.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eIReport.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID
                            OR eIReport.endorse1 = :endorse1ID 
                            OR eIReport.endorse2 = :endorse2ID 

                            and eIReport.recstat = 0', [
                                UserSession::getSessionID(),
                                UserSession::getSessionID(),
                                UserSession::getSessionID()
                            ]);

        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormIncidentReport(){
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();

        // endorsed 1 
        if(request('status') == 1 )
        request()->merge([
            'approveddate' => $now, 
            'approvedby' => UserSession::getSessionID()
        ]);

        // 
        if(request('status') == 2)
        request()->merge([
            'approveddate2' => $now, 
        ]);

        // close or rejected
        if(request('status') == 3 || request('status') == 4)
        request()->merge([
            'closeDate' => $now, 
        ]);

        // return request()->all();

        DB::table('formincidentreport')
        ->where('incidentID', request('incidentID'))
        ->update(request()->all());

         // MAIL NOTIFICATION
         if(request('status') == 1)
         {
            //  approve but first endorsement
            //  MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'INCIDENT REPORT REQUEST', 'APPROVED');
            MailServices::send_email_Notify(request('endorse1_email'), request('approvedby'), '', 'Endorse to you an Incident Report Request for Further Investigation ', 'Incident Report Request');
         }
         elseif(request('status') == 2){
            //  2nd endorsement
            //  MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'INCIDENT REPORT REQUEST', 'REJECTED');
            MailServices::send_email_Notify(request('endorse2_email'), request('endorse1'), '', 'Endorse to you an Incident Report Request for Further Investigation ', 'Incident Report Request');
         }
         elseif(request('status') == 3){
            //  close
            //  endorse
            //  MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'INCIDENT REPORT REQUEST', 'REJECTED');
            $requestorEmail = [ MailServices::getEmailsByEmpId(request('empID_')), request('personsinvolve_email')];
            MailServices::send_email_Notify($requestorEmail, request('approvedby'), '', 'Mark Close an Incident Report Request ', 'Incident Report Request');
         }
         else{
            //  rejected 4 status
            //  MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'INCIDENT REPORT REQUEST', 'CANCELLED');
            $requestorEmail = [ MailServices::getEmailsByEmpId(request('empID_')), request('personsinvolve_email')];
            MailServices::send_email_Notify($requestorEmail, request('approvedby'), '', 'Rejected an Incident Report Request ', 'Incident Report Request');
         }
        return request()->all();
    }

}
