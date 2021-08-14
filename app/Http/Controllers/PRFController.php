<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use DB;

class PRFController extends Controller
{

    // ADD
    public function addPRF(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        request()->merge(['empID_' => UserSession::getSessionID() ]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);


        $data = DB::table('formPRF')->insertGetId(request()->except(['isDisable', 'prfID', 'entries', 'itemdesc', 'uom', 'qty', 'allocatedbudget', 'reason', 'remarks', 'accountableto', 'approvedby','reciever_emails']));
        // GET LAST INDEX
        // $data = DB::select('select prfID from formPRF order by prfID desc limit 1');

            // FROM SUP DETAILS DATA
            $prfrecord = request()->only(['entries']);
            $prfdetailsparams = [];
            foreach($prfrecord as $keys=>$val)
            {
               foreach($val as $key=>$val2)
               {
                    $prfdetailsparams[] = array_merge($val2, ['prfID_'=>$data]);
               }
            }
            // 12 to 24hr echo date("H:i", strtotime("1:30 PM"));
            // 24 to 12 echo date("g:i a", strtotime("13:30"));

            DB::table('formPRFdetails')->insert($prfdetailsparams);
            // request()->merge(['status' => 0, 'prfID' => $data[0]->prfID]);
            // return request()->except(['isDisable']);
            $response = array_merge($response, ['status' => 0, 'prfID' => $data]);

        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'PURCHASE REQUISITION');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'purchase request', $data, 'prf');
        return $response;
    }

    // UPDATE
    public function updatePRF(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $request = request()->except(['isDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);


        DB::table('formPRF')
            ->where('prfID', request('prfID'))
            ->update(request()->except(['isDisable', 'prfID', 'entries', 'itemdesc', 'uom', 'qty',
                'allocatedbudget', 'reason', 'remarks', 'accountableto', 'approvedby',
                'empID_', 'status'
                ,'reciever_emails']));
        // FROM SUP DETAILS DATA
        $prfrecord = request()->only(['entries']);
        $prfdetailsparams = [];
        foreach($prfrecord as $keys=>$val)
        {
           foreach($val as $key=>$val2)
           {
                $prfdetailsparams[] = array_merge($val2, ['prfID_' => request('prfID')]);
           }
        }

        //DELETE AND THEN ADD
        DB::table('formPRFdetails')->where('prfID_', '=', request('prfID'))->delete();
        DB::table('formPRFdetails')->insert($prfdetailsparams);
        // request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        // return request()->except(['isDisable']);
        return $request;
    }

    // DELETE
    public function deletePRF($prfID  = null){
        DB::table('formPRF')->where('prfID', '=', $prfID)
        ->update(['recstat' => 1]);
        // ->delete();
    }

    // GET
    public function getPRFByEmployee(){
        // SESSION ID
        // $data = DB::select('select * from formPRF where empID_ = :empid', [UserSession::getSessionID()]);
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formPRF form left join employee emp on
        form.approvedby = emp.empID where
        form.recstat != 1  and form.empID_ = :empid', [UserSession::getSessionID()]);

        // check if data has vale
        if(count($data) > 0)
        {

           foreach($data as $keys => $value)
           {
                $prfID = $data[$keys]->prfID;
                // never add supdetailsID to avoid error in update
                $supdetails = DB::select('select prfID_, itemdesc, uom, qty, allocatedbudget, reason, accountableto from formPRFdetails where prfID_ = :prfID', [$prfID]);
                $data[$keys]->entries = $supdetails;
           }

        }

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET PRF FORM EMPLOYEE APPROVERS
    public function getPRFApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.PRF = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.PRF = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalPRFRequest(){
        // $data = DB::select('select * from formPRF where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [UserSession::getSessionID(), UserSession::getSessionID()]);
        // $data = DB::select('select eprf.* from formPRF eprf left join eformapproverbyemp eform on eprf.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);
        $data = DB::select('select eprf.*,
                            DATE_FORMAT(eprf.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname, dept.deptname
                            from formPRF eprf left join eformapproverbyemp eform
                                on eprf.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eprf.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            where eform.approverID_ = :approverID and eprf.recstat != 1', [UserSession::getSessionID()]);

         // check if data has vale
         if(count($data) > 0)
         {

            foreach($data as $keys => $value)
            {
                 $prfID = $data[$keys]->prfID;
                 // never add supdetailsID to avoid error in update
                 $supdetails = DB::select('select prfID_, itemdesc, uom, qty, allocatedbudget, reason, accountableto from formPRFdetails where prfID_ = :prfID', [$prfID]);
                 $data[$keys]->entries = $supdetails;
            }

         }
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormPRF(){
        DB::table('formPRF')
        ->where('prfID', request('prfID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'PURCHASE REQUISITION', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'purchase requisition request', 'approved', request('prfID'), 'prf');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'PURCHASE REQUISITION', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'purchase requisition request', 'rejected', request('prfID'), 'prf');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'PURCHASE REQUISITION', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'purchase requisition request', 'cancelled', request('prfID'), 'prf');
        }
        return request()->all();
    }
}
