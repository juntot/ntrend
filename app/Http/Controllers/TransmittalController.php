<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Services\UserSession;
use App\Services\MailServices;
class TransmittalController extends Controller
{

    
    // search employee
     public function searchEmp(){
        $data = DB::select('select emp.empID,  CONCAT(emp.fname,", ",emp.lname) as fullname,
        pos.posname as receiver_pos, dept.deptname as receiver_dept, 
        branch.branchname as receiver_branch
        from employee emp 
            inner join positiontbl pos on emp.posID_ = pos.posID
            inner join department dept on emp.deptID_ = dept.deptID
            inner join branchtbl branch on emp.branchID_ = branch.branchID

        where (emp.fname like :search OR emp.lname like :search2) 
        AND emp.empID not IN( :empID, "00001" )
        AND emp.status = 1
        order by emp.fname limit 30',
        [request('keyword').'%', request('keyword').'%', UserSession::getSessionID()]);
        return $data;
    }

    // get transmittal
    function getTransmittal() {
        
        $data = DB::select('select form.*,
        DATE_FORMAT(form.datefiled, "%m/%d/%Y %h:%i %p") as datefiled,
        CONCAT(emp.fname," ", emp.lname) as search_employee,
        pos.posname as receiver_pos, dept.deptname as receiver_dept,
        branch.branchname as receiver_branch
        from formtransmittal form 
            left join employee emp on form.approvedby = emp.empID 
            inner join positiontbl pos on emp.posID_ = pos.posID
            inner join department dept on emp.deptID_ = dept.deptID
            inner join branchtbl branch on emp.branchID_ = branch.branchID
        where form.recstat != 1 and form.empID_ = :empid', 
        [UserSession::getSessionID()]);
        
        return $data;
    }

    // Add transmittal
    function addTransmittal(){
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();
        
        request()->merge([
            'datefiled' => $now,
            'empID_' => UserSession::getSessionID(),
            'status' => 0,
            'recstat' => 0,
        ]);
        
        
        $id = DB::table('formtransmittal')->insertGetId(request()->all());
        request()->merge(['transID' => $id, 'status' => 0, 'datefiled' => UserSession::formatDate($now, 'm/d/Y h:i A')]);
        
        // message
        $message= 'has created a transmittal to you. 
        <br>please expect the following item(s) to be delivered:<br><br>';
        // entries 
        $entries = json_decode(request('entries'));
        $message .='<table style="width:100%; border:1px" width="100%" border="1">
        <tr>
            <th colspan="2" style="font-weight: bold;">POUCH #: '.$id.'</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">QTY/COPIES</th>
            <th style="font-weight: bold;">DESCRIPTION</th>
        </tr>';
        foreach ($entries as $key => $value) {
            $message .= '<tr>
                <td>'.$value->doctype.'</td>
                <td>'.$value->refnum.'</td>
            </tr>';
        }
        $message .= '</table>';

        
        // mail notification
        $approverEmail = MailServices::getEmailsByEmpId(request('approvedby'));
        if($approverEmail)
        MailServices::send_email_Notify($approverEmail, UserSession::getSessionID(), '', $message, 'Transmittal Form');
        return request()->all();
        MailServices::formNotify($approverEmail, UserSession::getSessionID(), 'transmittal form', $id, 'transmittalform');        
        return request()->all();
        

    }

    // update transmittal
    public function updateTransmittal(){
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();
        // SESSION ID
        request()->merge([
            'empID_' => UserSession::getSessionID(), 
            'datefiled' => $now,
            'status' => 0
        ]);
        // $response = request()->except(['ísDisable']);
        
        
        // update
        DB::table('formtransmittal')
            ->where('transID', request('transID'))
            ->where('status', 0)
            ->update(request()->except([
                'transID', 'remarks',
                'status', 'recstat'
            ]));

        request()->merge([
            'datefiled' => UserSession::formatDate($now, 'm/d/Y h:i A')
        ]);

        return request()->all();
    }
    // delete transmittal
    public function deleteTransmittal($leaveID  = null){
        
        $affected = DB::table('formtransmittal')
        ->where(['transID'=>$leaveID, 'status'=>0])
        ->update(['recstat'=>1]);
        
        // mail notification
        $approverEmail = MailServices::getEmailsByEmpId(request('approvedby'));
        if($affected) {
            MailServices::send_email_Notify($approverEmail, UserSession::getSessionID(), 'TRANSMITTAL FORM with a pouch# '.$leaveID, 'Deleted his/her', 'Transmittal Form');
        }
        
    }
    // FOR APPPROVERS =====================================================================================
    // GET  APPROVERS LIST NAME AND EMAIL
    public function getOverrideApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformapprover eform right join employee emp on eform.empID_ = emp.empID where eform.Leave0Form = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Override0Form = 1 and eform.empID_ = :empiD', 
        [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalTransmittal(){

        // $data = DB::select('select eleave.*,
        //                     DATE_FORMAT(eleave.datefiled, "%m/%d/%Y %h:%i %p") as datefiled,
        //                     CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
        //                     branch.branchname, pos.posname
        //                     from formtransmittal eleave 
        //                     left join eformapproverbyemp eform
        //                         on eleave.empID_ = eform.empID_
        //                     right join employee emp
        //                         on emp.empID = eleave.empID_
        //                     inner join positiontbl pos
        //                         on pos.posID = emp.posID_
        //                     inner join branchtbl branch
        //                         on branch.branchID = emp.branchID_
        //                     where eform.approverID_ = :approverID
        //                     and eform.Override0Form = 1
        //                     and eleave.recstat != 1',
        //                     [UserSession::getSessionID()]);
        $data = DB::select('select eleave.*,
                            DATE_FORMAT(eleave.datefiled, "%m/%d/%Y %h:%i %p") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.email,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eleave.approvedby
                            ) as search_employee,
                            branch.branchname,
                            pos.posname,
                            (
                                select sub.posname from positiontbl sub 
                                inner join employee subemp
                                    on sub.posID = subemp.posID_
                                where subemp.empID = eleave.approvedby
                            ) as receiver_pos,
                            (
                                select sub.deptname from department sub 
                                inner join employee subemp
                                    on sub.deptID = subemp.deptID_
                                where subemp.empID = eleave.approvedby
                            ) as receiver_dept,
                            (
                                select sub.branchname from branchtbl sub 
                                inner join employee subemp
                                    on sub.branchID = subemp.branchID_
                                where subemp.empID = eleave.approvedby
                            ) as receiver_branch

                            from formtransmittal eleave 
                            right join employee emp
                                on emp.empID = eleave.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            where approvedby = :approverID
                            and eleave.recstat != 1',
                            [UserSession::getSessionID()]);
        return $data;
    }    

    // approved / reject
    public function actionTransmittal(){
        $sqlQuery = '';
        date_default_timezone_set("Asia/Hong_Kong");
        $now = Carbon::now();
        $user = UserSession::getSessionID();

        request()->merge([
            'confirmdate' => $now,
        ]);
        // get list of approvers email
        // $mailReceivers = MailServices::getApproverEmail('overrideID', request('overrideID'), 'formoverride', 'Override0Form');
        
        // update
        DB::table('formtransmittal')
            ->where('transID', request('transID'))
            ->update(request()->except([
                'transID',
                'recstat',
                'empID_'
            ]));
        
        // // MAIL NOTIFICATION
        if(request('status') == 1)
        {
            // mail notification
            $approverEmail = MailServices::getEmailsByEmpId(request('empID_'));
            if($approverEmail)
            MailServices::send_email_Notify($approverEmail, UserSession::getSessionID(), 'TRANSMITTAL FORM <br> Pouch# '.request('transID'), ' <br><br>has partially received your<br><br>', 'Transmittal Form');
        }
        if(request('status') == 2){
            // mail notification
            $approverEmail = MailServices::getEmailsByEmpId(request('empID_'));
            if($approverEmail)
            MailServices::send_email_Notify($approverEmail, UserSession::getSessionID(), 'TRANSMITTAL FORM <br> Pouch# '.request('transID'), ' <br><br>has completely received your<br><br>', 'Transmittal Form');
        }
        if(request('status') == 3){
            // mail notification
            $approverEmail = MailServices::getEmailsByEmpId(request('empID_'));
            if($approverEmail)
            MailServices::send_email_Notify($approverEmail, UserSession::getSessionID(), 'TRANSMITTAL FORM <br> Pouch# '.request('transID'), ' <br><br>Rejected your<br><br>', 'Transmittal Form');
        }
        if(request('status') == 4){
            // mail notification
            $approverEmail = MailServices::getEmailsByEmpId(request('empID_'));
            if($approverEmail)
            MailServices::send_email_Notify($approverEmail, UserSession::getSessionID(), 'TRANSMITTAL FORM <br> Pouch# '.request('transID'), ' <br><br>Rejected your<br><br>', 'Transmittal Form');
        }
        // if(request('status') == 0){
        //     $message = '';
        //     if(request()->has('stat')){
        //         $message = ' <br><br>Move Back to Endorsed his/her ';
        //     } 
        //     else {
        //         $message = ' <br><br>Move Back to Pended his/her ';
        //     }
        //     MailServices::send_email_Notify($mailReceivers, request('empID_'), 'OVERRIDE FORM REQUEST', $message);
        // }
        
        return request()->all();
    }



    // TRANSMITTAL ADDRESS GROUPING
    // FORM GROUP =======================================================================================
    public function getAddress(){
        $data = DB::select('select * from transmittal_addr order by addressName');
        return $data;
    }
    public function addAddress(){
        $data = DB::table('transmittal_addr')->insertGetId(request()->all());
        return $data;
    }
    public function updateAddress(){
        $data = DB::table('transmittal_addr')->where('id', request('id'))->update(request()->all());
        return http_response_code(200);
    }
    public function deleteAddress(){
        $data = DB::table('transmittal_addr')->where('id', request('id'))->delete();
        return http_response_code(200);
    }

    
    // groupings
    public function getFormGroup(){
        $data = DB::select('select * from transmittal_addr_group');
        return $data;
    }

    // add form group
    public function addFormGroup(){
        $data = DB::table('transmittal_addr_group')->insertGetId(request()->all());
        return $data;
    }

    // delete from group
    public function deleteFormGroup(){
        $data = DB::table('transmittal_addr_group')->where('id', request('id'))->delete();
        // $data = DB::table('transmittal_addr_group_detail')->where('id', request('id'))->delete();
        return http_response_code(200);
    }

    // update from group
    public function updateFormGroup(){
        $data = DB::table('transmittal_addr_group')->where('id', request('id'))->update(request()->all());
        return http_response_code(200);
    }

    // manage form group details ================
    public function getFormGroupDetails(){

        $data = DB::select('select e.id, e.addressName, branch.branchname, branch.branchID as group_id
                    from transmittal_addr e
                    left join transmittal_addr_group_detail d
                        on e.id = d.addrID_
                    left join branchtbl branch
                        on branch.branchID = d.groupID_
                    where 1
        ');
        return $data;
    }

    public function setFormGroupDetails(){
        $data = DB::table('transmittal_addr_group_detail')
        ->updateOrInsert(
            ['addrID_' => request('addrID_')], //condition
            request()->all() //inserted
        );
        return http_response_code(200);
    }    

}
