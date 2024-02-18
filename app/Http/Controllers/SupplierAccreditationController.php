<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\FormApproverService;
use DB;

class SupplierAccreditationController extends Controller
{
    // ADD
    public function addSupplierAccreditation(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID() ]);
        $response = request()->except(['ísDisable']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);

        $data = DB::table('formaccreditation')->insertGetId(request()->except(['isDisable', 'accredID', 'remarks', 'approvedby','reciever_emails']));
            // $data = DB::select('select accredID from formaccreditation order by accredID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'accredID' => $data[0]->accredID]);
            $response = array_merge($response, ['status' => 0, 'accredID' => $data]);

        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'SUPPLIER ACCREDITATION REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'supplier accreditation request', $data, 'supaccred');
        return $response;
    }
    // UPDATE
    public function updateSupplierAccreditation(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);

        DB::table('formaccreditation')
            ->where('accredID', request('accredID'))
            ->update(request()->except([
                'isDisable', 'accredID', 'remarks', 'approvedby',
                'empID_', 'status', 'reciever_emails']));
        // request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        return $response;
    }

    // DELETE
    public function deleteSupplierAccreditation($accredID  = null){
        DB::table('formaccreditation')->where('accredID', '=', $accredID)
        ->update(['recstat' => 404]);
        // ->delete();
    }

    // GET
    public function getSupplierAccreditationByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formaccreditation form left join employee emp on
        form.approvedby = emp.empID where form.recstat = 0 and form.empID_ = :empid', [UserSession::getSessionID()]);

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET SupplierAccreditation FORM EMPLOYEE APPROVERS
    public function getSupplierAccreditationApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Supplier0Accreditation = 1');
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Supplier0Accreditation = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);

        $data = FormApproverService::getFormApproverByUser('Supplier0Accreditation');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalSupplierAccreditationRequest(){
        // $data = DB::select('select * from formaccreditation where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [ UserSession::getSessionID(), UserSession::getSessionID() ]);
        $data = DB::select('select eaccredit.*,
        DATE_FORMAT(eaccredit.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ",emp.lname) as fullname , emp.email
        from formaccreditation eaccredit left join eformapproverbyemp eform on eaccredit.empID_ = eform.empID_
            right join employee emp
            on emp.empID = eaccredit.empID_
        where eform.approverID_ = :approverID and
        eaccredit.recstat = 0', [UserSession::getSessionID()]);

        // $data = DB::select('select eaccredit.*, CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname
        //                     from formaccreditation eaccredit left join eformapproverbyemp eform
        //                         on eaccredit.empID_ = eform.empID_
        //                     right join employee emp
        //                         on emp.empID = eaccredit.empID_
        //                     inner join positiontbl pos
        //                         on pos.posID = emp.posID_
        //                     inner join branchtbl branch
        //                         on branch.branchID = emp.branchID_
        //                     where eform.approverID_ = :approverID and eaccredit.status != 3', [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormSupplierAccreditation(){
        DB::table('formaccreditation')
        ->where('accredID', request('accredID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'SUPPLIER ACCREDITATION REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'supplier accreditation request', 'approved', request('accredID'), 'supaccred');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'SUPPLIER ACCREDITATION REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'supplier accreditation request', 'rejected', request('accredID'), 'supaccred');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'SUPPLIER ACCREDITATION REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'supplier accreditation request', 'cancelled', request('accredID'), 'supaccred');
        }
        return request()->all();
    }

}
