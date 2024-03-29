<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use DB;

class SupplementaryController extends Controller
{
     // ADD
     public function addSupplementary(){

        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID() ]);
        $response = request()->except(['ísDisable', 'supdate']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);


        $data = DB::table('formsupplementary')->insertGetId(request()->except(['isDisable', 'supID', 'entries', 'supdate', 'timein', 'timeout', 'timein2', 'timeout2','reason' , 'remarks', 'approvedby', 'reciever_emails']));
        // GET LAST INDEX
        // $data = DB::select('select supID from formsupplementary order by supID desc limit 1');

            // FROM SUP DETAILS DATA
            $suprecord = request()->only(['entries']);
            $supdetailsparams = [];
            foreach($suprecord as $keys=>$val)
            {
               foreach($val as $key=>$val2)
               {
                    $val2['timein'] = date("H:i", strtotime($val2['timein']));
                    $val2['timeout'] = date("H:i", strtotime($val2['timeout']));

                    $val2['timein2'] = date("H:i", strtotime($val2['timein2']));
                    $val2['timeout2'] = date("H:i", strtotime($val2['timeout2']));

                    $supdetailsparams[] = array_merge($val2, ['supID_'=>$data]);
               }
            }
            // 12 to 24hr echo date("H:i", strtotime("1:30 PM"));
            // 24 to 12 echo date("g:i a", strtotime("13:30"));


            DB::table('formsupdetails')->insert($supdetailsparams);
            // request()->merge(['status' => 0, 'supID' => $data[0]->supID]);
            $response = array_merge($response, ['status' => 0, 'supID' => $data]);

        // MAIL NOTIFICATION
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'ATTENDANCE SUPPLEMENTARY REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'attendance supplementary request', $data, 'supplementary');
        // return request()->except(['isDisable', 'supdate']);
        return $response;
    }

    // UPDATE
    public function updateSupplementary(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
        ]);

        // update
        DB::table('formsupplementary')
            ->where('supID', request('supID'))
            ->update(request()->except(['isDisable', 'supID', 'entries', 'supdate', 'timein', 'timeout', 'timein2', 'timeout2','reason' , 'remarks', 'approvedby', 'reciever_emails', 'empID_', 'status']));
        // FROM SUP DETAILS DATA
        $suprecord = request()->only(['entries']);
        $supdetailsparams = [];
        foreach($suprecord as $keys=>$val)
        {
           foreach($val as $key=>$val2)
           {
                $val2['timein'] = date("H:i", strtotime($val2['timein']));
                $val2['timeout'] = date("H:i", strtotime($val2['timeout']));

                $val2['timein2'] = date("H:i", strtotime($val2['timein2']));
                $val2['timeout2'] = date("H:i", strtotime($val2['timeout2']));

                $supdetailsparams[] = array_merge($val2, ['supID_' => request('supID')]);
           }
        }

        // 12 to 24hr echo date("H:i", strtotime("1:30 PM"));
        // 24 to 12 echo date("g:i a", strtotime("13:30"));
        //DELETE AND THEN ADD
        DB::table('formsupdetails')->where('supID_', '=', request('supID'))->delete();
        DB::table('formsupdetails')->insert($supdetailsparams);

        return $response;
    }

    // DELETE
    public function deleteSupplementary($supID  = null){
        DB::table('formsupplementary')->where('supID', '=', $supID)
        ->update(['recstat'=>1]);
        // ->delete();
    }

    // GET
    public function getSupplementaryByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formsupplementary form left join employee emp on
        form.approvedby = emp.empID where form.recstat != 1 and form.empID_ = :empid', [UserSession::getSessionID()]);
        // check if data has vale
        if(count($data) > 0)
        {

           foreach($data as $keys => $value)
           {
                $supID = $data[$keys]->supID;
                // never add supdetailsID to avoid error in update
                $supdetails = DB::select('select supID_, supdate, timein, timeout, timein2, timeout2, reason from formsupdetails where supID_ = :supid', [$supID]);
                $data[$keys]->entries = $supdetails;
           }

        }

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET LEAVE FORM EMPLOYEE APPROVERS
    public function getSupplementaryApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Supplementary = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Supplementary = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalSupplementaryRequest(){
        // $data = DB::select('select * from formsupplementary where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [UserSession::getSessionID(), UserSession::getSessionID() ]);

        // $data = DB::select('select esupp.* from formsupplementary esupp left join eformapproverbyemp eform on esupp.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);
        $data = DB::select('select esup.*,
                            DATE_FORMAT(esup.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formsupplementary esup left join eformapproverbyemp eform
                                on esup.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = esup.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID and esup.recstat != 1', [UserSession::getSessionID()]);

        // check if data has vale
        if(count($data) > 0)
        {

           foreach($data as $keys => $value)
           {
                $supID = $data[$keys]->supID;
                // never add supdetailsID to avoid error in update
                $supdetails = DB::select('select supID_, supdate, timein, timeout, timein2, timeout2, reason from formsupdetails where supID_ = :supid', [$supID]);
                $data[$keys]->entries = $supdetails;
           }

        }

        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormSupplementary(){
        DB::table('formsupplementary')
        ->where('supID', request('supID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

         // MAIL NOTIFICATION
         if(request('status') == 1)
         {
             MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'ATTENDANCE SUPPLEMENTARY REQUEST', 'APPROVED');
             MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'attendance supplementary request', 'approved', request('supID'), 'supplementary');
         }
         elseif(request('status') == 2){
             MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'ATTENDANCE SUPPLEMENTARY REQUEST', 'REJECTED');
             MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'attendance supplementary request', 'rejected', request('supID'), 'supplementary');
         }
         else{
             MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'ATTENDANCE SUPPLEMENTARY REQUEST', 'CANCELLED');
             MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'attendance supplementary request', 'cancelled', request('supID'), 'supplementary');
         }
        return request()->all();
    }
}
