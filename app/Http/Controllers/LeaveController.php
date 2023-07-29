<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use App\Services\FormApproverService;
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

            
        $mailReceivers = FormApproverService::getApproverEmail('leaveID', $data, 'formleave', 'Leave0Form');

        // mail notification
        MailServices::sendNotify($mailReceivers, request('empID_'), 'LEAVE REQUEST');
        MailServices::formNotify($mailReceivers, request('empID_'), 'leave request', $data, 'leave');

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
        ->update(['recstat'=>404]);
        // ->delete();

        $mailReceivers = FormApproverService::getApproverEmail('leaveID', $leaveID, 'formleave', 'Leave0Form');
        
        if($affected) {
            MailServices::send_email_Notify($mailReceivers, request('empID_'), 'LEAVE REQUEST', 'Deleted his/her');
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
        form.approvedby = emp.empID where form.recstat = 0 and form.empID_ = :empid', [UserSession::getSessionID()]);
        return $data;
    }

    // get only approve leaves per user
    public function getMyApproveLeave(){
        $data = DB::select('Select name, start, start as end, details, "red" as color from ph_holidays where status = 1
        UNION
        Select (
        CASE
            WHEN form.leavetype = 1 THEN "Sick Leave"
            WHEN form.leavetype = 2 THEN "Birthday Leave"
            WHEN form.leavetype = 3 THEN "Leave w/o Pay"
            WHEN form.leavetype = 4 THEN "Bereavement Leave"
            WHEN form.leavetype = 5 THEN "Vacation Leave"
            WHEN form.leavetype = 6 THEN "Descritionary Leave"
            WHEN form.leavetype = 7 THEN "Solo Parent Leave"
            WHEN form.leavetype = 8 THEN "Paternity Leave"
            ELSE "Others"
        END
        ) as name, form.datestart as start, form.dateend as end, form.reason as details, "orange" as color
        from formleave form
        WHERE
        form.datestart between :start and :end
        and form.empID_ = :empID
        and (form.recstat = 0 and form.status = 1)
        ',[
            'start'  => request('start'),
            'end'    => request('end'),
            'empID'  => UserSession::getSessionID(),
        ]);
        return $data;
    }



    // FOR APPROVERS ====================================================================================================================================
    // GET LEAVE FORM EMPLOYEE APPROVERS
    public function getLeaveApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformapprover eform right join employee emp on eform.empID_ = emp.empID where eform.Leave0Form = 1');
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Leave0Form = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        $data = FormApproverService::getFormApproverByUser('Leave0Form');
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
                            and eleave.recstat = 0',
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
        //                     and eleave.recstat = 0',
        //                     [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormLeave(){
        // return request()->all();
        $totalDaysFiled = request('totaldays');
        $sqlLeaveUpdate = '';
        switch (request('leavetype')):
            // SL
            case 1:
                if(request('status') == 1){

                    $sqlLeaveUpdate = 'update employee set SL = SL - '.$totalDaysFiled.' where empID = :empID';
                }
                else if(request('status') == 0){
                    if(strtolower(request('old_status')) == 'approved'){
                        $sqlLeaveUpdate = 'update employee set SL = SL + '.$totalDaysFiled.' where empID = :empID';
                    }
                }
                break;
            // BL
            case 2:

                if(request('status') == 1){
                    $sqlLeaveUpdate = 'update employee set BL = BL - '.$totalDaysFiled.' where empID = :empID';
                }
                else if(request('status') == 0){
                    if(strtolower(request('old_status')) == 'approved'){
                        $sqlLeaveUpdate = 'update employee set BL = BL + '.$totalDaysFiled.' where empID = :empID';
                    }
                }
                break;
            //  VL
            case 5:
                if(request('status') == 1){
                    $sqlLeaveUpdate = 'update employee set VL = VL - '.$totalDaysFiled.' where empID = :empID';
                }
                else if(request('status') == 0){
                    if(strtolower(request('old_status')) == 'approved'){
                        $sqlLeaveUpdate = 'update employee set VL = VL + '.$totalDaysFiled.' where empID = :empID';
                    }
                }
                break;
            //  DL
            case 6:
                if(request('status') == 1){
                    $sqlLeaveUpdate = 'update employee set DL = DL - '.$totalDaysFiled.' where empID = :empID';
                }
                else if(request('status') == 0){
                    if(strtolower(request('old_status')) == 'approved'){
                        $sqlLeaveUpdate = 'update employee set DL = DL + '.$totalDaysFiled.' where empID = :empID';
                    }
                }
                break;
        endswitch;
        

        
        // return $totalDaysFiled;

        DB::table('formleave')
        ->where('leaveID', request('leaveID'))
        ->update([
                'status' => request('status'), 'approvedby'=> request('approvedby'),
                'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        // update employee leave credits
        if($sqlLeaveUpdate != '')
        DB::select($sqlLeaveUpdate, [request('empID_')]);

        // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'LEAVE REQUEST', 'APPROVED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'approved', request('leaveID'), 'leave');
        }
        elseif(request('status') == 2){
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'LEAVE REQUEST', 'REJECTED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'rejected', request('leaveID'), 'leave');
        }
        else{
            MailServices::sendNotifyReviewed(request('email'), request('approvedby'), 'LEAVE REQUEST', 'CANCELLED');
            MailServices::formNotifyReviewed(request('email'), request('approvedby'), 'leave request', 'cancelled', request('leaveID'), 'leave');
        }
        return request()->all();
    }

}
