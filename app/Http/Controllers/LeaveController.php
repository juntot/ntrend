<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use DB;

class LeaveController extends Controller
{

    // ADD
    public function addLeave(){

        // Session ID
        // request()->merge(['empID_' => '00001']);
        request()->merge(['empID_' => UserSession::getSessionID() ]);
        $response = request()->except(['ísDisable']);

        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'datestart' => UserSession::formatDate(request()->datestart),
            'dateend'   => UserSession::formatDate(request()->dateend)
        ]);


        $data = DB::table('formleave')->insertGetId(request()->except(['isDisable', 'leaveID', 'remarks', 'approvedby', 'reciever_emails']));
            // $data = DB::select('select leaveID from formleave order by leaveID desc limit 1');
            // GET LAST INDEX
            // request()->merge(['status' => 0, 'leaveID' => $data[0]->leaveID]);
            $response = array_merge($response, ['status' => 0, 'leaveID' => $data]);



        // mail notification
        MailServices::sendNotify(request('reciever_emails'), request('empID_'), 'LEAVE REQUEST');
        MailServices::formNotify(request('reciever_emails'), request('empID_'), 'leave request', $data, 'leave');
        return $response;
    }

    // UPDATE
    public function updateLeave(){
        // SESSION ID
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        $response = request()->except(['ísDisable']);
        request()->merge([
            'datefiled' => UserSession::formatDate(request()->datefiled),
            'datestart' => UserSession::formatDate(request()->datestart),
            'dateend'   => UserSession::formatDate(request()->dateend),
        ]);

        // update
        DB::table('formleave')
            ->where('leaveID', request('leaveID'))
            ->update(request()->except(['isDisable', 'leaveID', 'remarks', 'approvedby', 'reciever_emails', 'empID_', 'status']));
        return $response;
    }

    // DELETE
    public function deleteLeave($leaveID  = null){
        request()->merge(['empID_' => UserSession::getSessionID() ]);
        
        $affected = DB::table('formleave')
        ->where(['leaveID'=>$leaveID, 'status'=>0])
        ->update(['recstat'=>1]);
        // ->delete();
        if($affected) {
            MailServices::send_email_Notify(request('reciever_emails'), request('empID_'), 'LEAVE REQUEST', 'Deleted his/her');
        }
        
    }

    // GET
    public function getLeaveByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y") as datefiled,
        DATE_FORMAT(form.datestart, "%m/%d/%Y") as datestart,
        DATE_FORMAT(form.dateend, "%m/%d/%Y") as dateend,
        CONCAT(emp.fname," ", emp.lname) as approvedby from formleave form left join employee emp on
        form.approvedby = emp.empID where form.recstat != 1 and form.empID_ = :empid', [UserSession::getSessionID()]);

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET LEAVE FORM EMPLOYEE APPROVERS
    public function getLeaveApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformapprover eform right join employee emp on eform.empID_ = emp.empID where eform.Leave0Form = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Leave0Form = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalLeaveRequest(){
        // $data = DB::select('select * from formleave where (status = 0 and empID_ != :empID) OR approvedby = :empid', //['000001']);
        $data = DB::select('select eleave.*,
                            DATE_FORMAT(eleave.datefiled, "%m/%d/%Y") as datefiled,
                            DATE_FORMAT(eleave.datestart, "%m/%d/%Y") as datestart,
                            DATE_FORMAT(eleave.dateend, "%m/%d/%Y") as dateend,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            branch.branchname, pos.posname
                            from formleave eleave left join eformapproverbyemp eform
                                on eleave.empID_ = eform.empID_
                            right join employee emp
                                on emp.empID = eleave.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where eform.approverID_ = :approverID
                            and eform.Leave0Form = 1
                            and eleave.recstat != 1',
                            [UserSession::getSessionID()]);
        // $data = DB::select('select eleave.*,
        //                     DATE_FORMAT(eleave.datefiled, "%m/%d/%Y") as datefiled,
        //                     DATE_FORMAT(eleave.datestart, "%m/%d/%Y") as datestart,
        //                     DATE_FORMAT(eleave.dateend, "%m/%d/%Y") as dateend,
        //                     CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
        //                     branch.branchname, pos.posname
        //                     from formleave eleave left join eformapproverbyemp eform
        //                         on eleave.empID_ = eform.empID_
        //                     right join employee emp
        //                         on emp.empID = eleave.empID_
        //                     inner join positiontbl pos
        //                         on pos.posID = emp.posID_
        //                     inner join branchtbl branch
        //                         on branch.branchID = emp.branchID_
        //                     where eform.approverID_ = :approverID
        //                     and eleave.recstat != 1',
        //                     [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormLeave(){

        $sqlLeaveUpdate = '';
        switch (request('leavetype')):
            // SL
            case 1:
                if(request('status') == 1){
                    $sqlLeaveUpdate = 'update employee set SL = SL - 1 where empID = :empID';
                }
                else if(request('status') == 0){
                    if(strtolower(request('old_status')) == 'approved'){
                        $sqlLeaveUpdate = 'update employee set SL = SL + 1 where empID = :empID';
                    }
                }
                break;
            // BL
            case 2:

                if(request('status') == 1){
                    $sqlLeaveUpdate = 'update employee set BL = BL - 1 where empID = :empID';
                }
                else if(request('status') == 0){
                    if(strtolower(request('old_status')) == 'approved'){
                        $sqlLeaveUpdate = 'update employee set BL = BL + 1 where empID = :empID';
                    }
                }
                break;
            //  VL
            case 5:
                if(request('status') == 1){
                    $sqlLeaveUpdate = 'update employee set VL = VL - 1 where empID = :empID';
                }
                else if(request('status') == 0){
                    if(strtolower(request('old_status')) == 'approved'){
                        $sqlLeaveUpdate = 'update employee set VL = VL + 1 where empID = :empID';
                    }
                }
                break;
            //  DL
            case 6:
                if(request('status') == 1){
                    $sqlLeaveUpdate = 'update employee set DL = DL - 1 where empID = :empID';
                }
                else if(request('status') == 0){
                    if(strtolower(request('old_status')) == 'approved'){
                        $sqlLeaveUpdate = 'update employee set DL = DL + 1 where empID = :empID';
                    }
                }
                break;
        endswitch;
        if($sqlLeaveUpdate != '')
        DB::select($sqlLeaveUpdate, [request('empID_')]);


        DB::table('formleave')
        ->where('leaveID', request('leaveID'))
        ->update([
                'status' => request('status'), 'approvedby'=> request('approvedby'),
                'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            // MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'LEAVE REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'approved', request('leaveID'), 'leave');
        }
        elseif(request('status') == 2){
            // MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'LEAVE REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'rejected', request('leaveID'), 'leave');
        }
        else{
            // MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'LEAVE REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'cancelled', request('leaveID'), 'leave');
        }
        return request()->all();
    }

}
