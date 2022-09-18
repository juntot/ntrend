<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
use DB;

class TravelController extends Controller
{
    // ADD
    public function addTravel(){

        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        $response = request()->except(['ísDisable']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'departuredate' => UserSession::formatDate(request()->departuredate)
        ]);


        $data = DB::table('formtravel')->insertGetId(request()->except(['isDisable', 'travelID', 'remarks', 'approvedby', 'reciever_emails', 'specify_kgs']));
            // $data = DB::select('select travelID from formtravel order by travelID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'travelID' => $data[0]->travelID]);
            $response = array_merge($response, ['status' => 0, 'travelID' => $data]);

        // mail notification
        if(count(request('reciever_emails'))){
            MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'TRAVEL REQUEST');
            MailServices::formNotify(request('reciever_emails'), request('empID_'), 'travel request', $data, 'travel');
        }

        return $response;
    }
    // UPDATE
    public function updateTravel(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'departuredate' => UserSession::formatDate(request()->departuredate)
        ]);



        DB::table('formtravel')
            ->where('travelID', request('travelID'))
            ->update(request()->except([
                'isDisable', 'travelID', 'remarks', 'approvedby',
                'empID_', 'status', 'reciever_emails', 'specify_kgs']));
        // request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        // return request()->except(['isDisable']);
        return $response;
    }

    // DELETE
    public function deleteTravel($travelID  = null){
        DB::table('formtravel')->where('travelID', '=', $travelID)
        ->update(['recstat' => 1]);
        // ->delete();
    }

    // GET
    public function getTravelByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,

        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        DATE_FORMAT(form.departuredate, "%m/%d/%Y") as departuredate,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formtravel form left join employee emp on
        form.approvedby = emp.empID where form.empID_ = :empid', [UserSession::getSessionID()]);

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET TRAVEL FORM EMPLOYEE APPROVERS
    public function getTravelApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Travel0Form = 1');
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform
        //             right join employee emp on eform.approverID_ = emp.empID where eform.Travel0Form = 1 and eform.empID_ = :empiD',
        //         [UserSession::getSessionID()]);

        $data = FormApproverService::getFormApproverByUser('Travel0Form');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalTravelRequest(){
        // $data = DB::select('select * from formtravel where (status = 0 and empID_ != :empID) OR approvedby = :empid',
        // [ UserSession::getSessionID(), UserSession::getSessionID() ]);
        // $data = DB::select('select etravel.* from formtravel etravel left join eformapproverbyemp eform on etravel.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);
        $data = DB::select('select etravel.*,
                            DATE_FORMAT(etravel.datefiled, "%m/%d/%Y") as datefiled,
                            DATE_FORMAT(etravel.departuredate, "%m/%d/%Y") as departuredate,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            emp.birthdate, emp.mobile,
                            branch.branchname, pos.posname
                            from formtravel etravel left join eformapproverbyemp eform
                                on etravel.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = etravel.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID and etravel.recstat != 1', [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormTravel(){
        DB::table('formtravel')
        ->where('travelID', request('travelID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'TRAVEL REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'travel request', 'approved', request('travelID'), 'travel');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'TRAVEL REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'travel request', 'rejected', request('travelID'), 'travel');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'TRAVEL REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'travel request', 'cancelled', request('travelID'), 'travel');
        }
        return request()->all();
    }

}
