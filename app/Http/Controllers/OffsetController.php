<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use DB;

class OffsetController extends Controller
{
     // ADD
     public function addOffset(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        $response = request()->except(['ísDisable', 'offsetdate']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);

        $data = DB::table('formoffset')->insertGetId(request()->except(['isDisable', 'offsetID', 'entries', 'offsetdate', 'timein', 'timeout','reason' , 'remakrs', 'approvedby', 'reciever_emails']));
        // GET LAST INDEX
        // $data = DB::select('select offsetID from formoffset order by offsetID desc limit 1');

            // FROM SUP DETAILS DATA
            $suprecord = request()->only(['entries']);
            $supdetailsparams = [];
            foreach($suprecord as $keys=>$val)
            {
               foreach($val as $key=>$val2)
               {
                    $val2['timein'] = date("H:i", strtotime($val2['timein']));
                    $val2['timeout'] = date("H:i", strtotime($val2['timeout']));

                    $supdetailsparams[] = array_merge($val2, ['offsetID_'=>$data]);
               }
            }
            // 12 to 24hr echo date("H:i", strtotime("1:30 PM"));
            // 24 to 12 echo date("g:i a", strtotime("13:30"));

            DB::table('formoffsetdetails')->insert($supdetailsparams);
            // request()->merge(['status' => 0, 'offsetID' => $data]);
            $response = array_merge($response, ['status' => 0, 'offsetID' => $data]);
            // return request()->except(['isDisable', 'offsetdate']);

        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'OFFSET FORM REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'offset form request', $data, 'offset');
        return $response;
    }

    // UPDATE
    public function updateOffset(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);


        DB::table('formoffset')
            ->where('offsetID', request('offsetID'))
            ->update(request()->except(['isDisable', 'offsetID', 'entries', 'offsetdate', 'timein', 'timeout','reason' , 'remakrs', 'approvedby', 'empID_', 'status', 'reciever_emails']));
        // FROM SUP DETAILS DATA
        $suprecord = request()->only(['entries']);
        $supdetailsparams = [];
        foreach($suprecord as $keys=>$val)
        {
           foreach($val as $key=>$val2)
           {
                $val2['timein'] = date("H:i", strtotime($val2['timein']));
                $val2['timeout'] = date("H:i", strtotime($val2['timeout']));

                $supdetailsparams[] = array_merge($val2, ['offsetID_' => request('offsetID')]);
           }
        }

        // 12 to 24hr echo date("H:i", strtotime("1:30 PM"));
        // 24 to 12 echo date("g:i a", strtotime("13:30"));
        //DELETE AND THEN ADD
        DB::table('formoffsetdetails')->where('offsetID_', '=', request('offsetID'))->delete();
        DB::table('formoffsetdetails')->insert($supdetailsparams);

        // return request()->except(['isDisable']);
        return $response;
    }

    // DELETE
    public function deleteOffset($offsetID  = null){
        DB::table('formoffset')->where('offsetID', '=', $offsetID)
        ->update(['recstat' => 1]);
        // ->delete();
    }

    // GET
    public function getOffsetByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formoffset form left join employee emp on
        form.approvedby = emp.empID where form.recstat !=1  and form.empID_ = :empid', [UserSession::getSessionID()]);
        // check if data has vale
        if(count($data) > 0)
        {

           foreach($data as $keys => $value)
           {
                $offsetID = $data[$keys]->offsetID;
                // never add supdetailsID to avoid error in update
                $supdetails = DB::select('select offsetID_, offsetdate, timein, timeout, reason from formoffsetdetails where offsetID_ = :offsetID', [$offsetID]);
                $data[$keys]->entries = $supdetails;
           }

        }

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET Offset FORM EMPLOYEE APPROVERS
    public function getOffsetApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Offset = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Offset = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalOffsetRequest(){
        // $data = DB::select('select * from formoffset where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [ UserSession::getSessionID(), UserSession::getSessionID() ]);
        // $data = DB::select('select eoffset.* from formoffset eoffset left join eformapproverbyemp eform on eoffset.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);

        $data = DB::select('select eoffset.*,
                            DATE_FORMAT(eoffset.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formoffset eoffset left join eformapproverbyemp eform
                                on eoffset.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eoffset.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID 
                            and eform.Offset = 1
                            and eoffset.recstat != 1', [UserSession::getSessionID()]);

        // check if data has vale
        if(count($data) > 0)
        {

           foreach($data as $keys => $value)
           {
                $offsetID = $data[$keys]->offsetID;
                // never add supdetailsID to avoid error in update
                $supdetails = DB::select('select offsetID_, offsetdate, timein, timeout, reason from formoffsetdetails where offsetID_ = :offsetID', [$offsetID]);
                $data[$keys]->entries = $supdetails;
           }

        }

        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionformOffset(){
        DB::table('formoffset')
        ->where('offsetID', request('offsetID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'OFFSET FORM REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'offset form request', 'approved', request('offsetID'), 'offset');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'OFFSET FORM REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'offset form request', 'rejected', request('offsetID'), 'offset');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'OFFSET FORM REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'offset form request', 'cancelled', request('offsetID'), 'offset');
        }
        return request()->all();
    }
}

