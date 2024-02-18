<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
use DB;

class CanvasController extends Controller
{
    // ADD
    public function addCanvas(){
        // Session ID

        request()->merge(['empID_' => UserSession::getSessionID()]);
        $response = request()->except(['isDisable', 'supdate']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);


        $data = DB::table('formcanvas')->insertGetId(
            request()->except([
                'isDisable', 'canvasID', 'itembrand', 'itemdesc', 'itemcost',
                'qty', 'total', 'itemremarks', 'remarks', 'entries', 'approvedby',
                'reciever_emails'
            ])
        );
        // GET LAST INDEX
        // $data = DB::select('select canvasID from formcanvas order by canvasID desc limit 1');

            // FROM SUP DETAILS DATA
            $canvasrecord = request()->only(['entries']);
            $canvasdetailsparams = [];
            foreach($canvasrecord as $keys=>$val)
            {
               foreach($val as $key=>$val2)
               {
                    $canvasdetailsparams[] = array_merge($val2, ['canvasID_'=>$data]);
               }
            }
            // 12 to 24hr echo date("H:i", strtotime("1:30 PM"));
            // 24 to 12 echo date("g:i a", strtotime("13:30"));

            DB::table('formcanvasdetails')->insert($canvasdetailsparams);
            // request()->merge(['status' => 0, 'canvasID' => $data[0]->canvasID]);
            // return request()->except(['isDisable', 'supdate']);
            $response = array_merge($response, ['status' => 0, 'canvasID' => $data]);

        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'CANVAS REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'canvas request', $data, 'canvas');
        return $response;
    }

    // UPDATE
    public function updateCanvas(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled)
        ]);


        DB::table('formcanvas')
            ->where('', request('canvasID'))
            ->update(
                request()->except([
                    'isDisable', 'canvasID', 'itembrand', 'itemdesc', 'itemcost',
                    'qty', 'total', 'itemremarks', 'remarks', 'entries', 'approvedby',
                    'empID_', 'status', 'reciever_emails'
                ])
            );
        // FROM SUP DETAILS DATA
        $canvasrecord = request()->only(['entries']);
        $canvasdetailsparams = [];
        foreach($canvasrecord as $keys=>$val)
        {
           foreach($val as $key=>$val2)
           {
                $canvasdetailsparams[] = array_merge($val2, ['canvasID_' => request('canvasID')]);
           }
        }

        // 12 to 24hr echo date("H:i", strtotime("1:30 PM"));
        // 24 to 12 echo date("g:i a", strtotime("13:30"));
        //DELETE AND THEN ADD
        DB::table('formcanvasdetails')->where('canvasID_', '=', request('canvasID'))->delete();
        DB::table('formcanvasdetails')->insert($canvasdetailsparams);
        // request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        return $response;
    }

    // DELETE
    public function deleteCanvas($canvasID  = null){
        DB::table('formcanvas')->where('canvasID', '=', $canvasID)
        ->update(['recstat' => 404]);
        // ->delete();
    }

    // GET
    public function getCanvasByEmployee(){
        // SESSION ID
        // $data = DB::select('select * from formcanvas where empID_ = :empid', [UserSession::getSessionID()]);
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formcanvas form left join employee emp on
        form.approvedby = emp.empID where
        form.recstat = 0 and
        form.empID_ = :empid', [UserSession::getSessionID()]);

        // check if data has vale
        if(count($data) > 0)
        {

           foreach($data as $keys => $value)
           {
                $canvasID = $data[$keys]->canvasID;
                // never add supdetailsID to avoid error in update
                $canvasdetails = DB::select('select canvasID_, itembrand, itemdesc, itemcost, qty, total, itemremarks from formcanvasdetails where canvasID_ = :canvasID', [$canvasID]);
                $data[$keys]->entries = $canvasdetails;
           }

        }

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET LEAVE FORM EMPLOYEE APPROVERS
    public function getCanvasApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Canvas = 1');
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Leave0Form = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        
        $data = FormApproverService::getFormApproverByUser('canvas');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalCanvasRequest(){
        // $data = DB::select('select * from formcanvas where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [UserSession::getSessionID(), UserSession::getSessionID()]);
        $data = DB::select('select ecanvas.*,
        DATE_FORMAT(ecanvas.datefiled, "%m/%d/%Y") as datefiled,
        CONCAT(emp.fname," ",emp.lname) as fullname, emp.email
        from formcanvas ecanvas left join eformapproverbyemp eform on
            ecanvas.empID_ = eform.empID_
        right join employee emp
            on emp.empID = ecanvas.empID_
        where ecanvas.recstat = 0 and eform.approverID_ = :approverID', [UserSession::getSessionID()]);


        // check if data has vale
        if(count($data) > 0)
        {

           foreach($data as $keys => $value)
           {
                $canvasID = $data[$keys]->canvasID;
                // never add supdetailsID to avoid error in update
                $canvasdetails = DB::select('select canvasID_, itembrand, itemdesc, itemcost, qty, total, itemremarks from formcanvasdetails where canvasID_ = :canvasID', [$canvasID]);
                $data[$keys]->entries = $canvasdetails;
           }

        }
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormCanvas(){
        DB::table('formcanvas')
        ->where('canvasID', request('canvasID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'CANVAS REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'canvas request', 'approved', request('canvasID'), 'canvas');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'CANVAS REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'canvas request', 'rejected', request('canvasID'), 'canvas');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'CANVAS REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'canvas request', 'cancelled', request('canvasID'), 'canvas');
        }
        return request()->all();
    }
}
