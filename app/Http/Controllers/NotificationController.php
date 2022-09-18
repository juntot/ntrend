<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;


class NotificationController extends Controller
{
    // leave credits
    public function getLeaveCredits(){

        // UserSession::getSessionID()
        $data = DB::select('select SL, VL, BL, DL from employee where empID = :empid', [UserSession::getSessionID()]);
        return $data;
    }

    // get form notification
    public function getFormNotifs(){

        date_default_timezone_set("Asia/Hong_Kong");
        // AND dateadded between '.date('Y-m-d', strtotime('-1 week')).' and '.date('Y-m-d').' order by status', [$email]);
        $active_user = UserSession::getEmpKey();
        // dd('"'.$active_user[0]->email.'"');
        $prev_date = date('Y-m-d', strtotime('-2 week'));
        $cur_date = date('Y-m-d');
        $data = DB::select('select notif_msg, dateadded, status from notif_form where match(notify_mail) against (:mail IN BOOLEAN MODE)
                            AND validate_date between :prevdate and :cur_date order by formNotifID desc limit 50', ['"'.$active_user[0]->email.'"', $prev_date, $cur_date]);

        // OLD
        // $data = DB::select('select notif_msg, dateadded, status from notif_form where match(notify_mail) against (:mail IN NATURAL LANGUAGE MODE)
        //                     AND validate_date between (CURDATE() - INTERVAL 2 WEEK) and NOW() order by formNotifID desc limit 50', [$active_user[0]->email]);
        // END
        $count = 0;
        foreach($data as $val)
        {
            if($val->status == 0)
            {
                $count++;
            }
        }
        $data['count_pending'] = $count;
        return $data;
    }

    // change status once viewed
    public function updateFormNotif(){
        $data = DB::select('update notif_form set status = 1 where match(notify_mail) against (:mail IN NATURAL LANGUAGE MODE) and status = 0', [request('email')]);
    }

    // get post notification
    public function getPostNotifs(){
        date_default_timezone_set("Asia/Hong_Kong");

        $active_user = UserSession::getEmpKey();
        $prev_date = date('Y-m-d', strtotime('-2 week'));
        $cur_date = date('Y-m-d');
        $data = DB::select('select postNotifID, dateadded, notifby_empID, notifBy, notif_msg, notify_dept, notify_users, viewedby
                    from notif_post where
                    (
                        match(notify_users) against (? IN NATURAL LANGUAGE MODE)
                        or match(notify_dept) against (? IN NATURAL LANGUAGE MODE)
                        or match(notify_comp) against (? IN NATURAL LANGUAGE MODE)
                    )
                    AND validate_date between ? and ?
                    AND notifby_empID <> ?
                    order by postNotifID desc',
                [$active_user[0]->empID, 'dept'.$active_user[0]->deptID_, 'comp'.$active_user[0]->compID_, $prev_date, $cur_date, $active_user[0]->empID]);

        // OLD
        // $data = DB::select('select postNotifID, dateadded, notifby_empID, notifBy, notif_msg, notify_dept, notify_users, viewedby
        //             from notif_post where
        //             (
        //                 match(notify_users) against (? IN NATURAL LANGUAGE MODE)
        //                 or match(notify_dept) against (? IN NATURAL LANGUAGE MODE)
        //             )
        //             AND validate_date between (CURDATE() - INTERVAL 2 WEEK) and NOW()
        //             AND notifby_empID <> ?
        //             order by postNotifID desc',
        //         [$active_user[0]->empID, 'dept'.$active_user[0]->deptID_, $active_user[0]->empID]);
        // END

        $count = 0;
        foreach($data as $val)
        {
            if (strpos($val->viewedby, $active_user[0]->empID) === false) {
                $count++;
            }
        }
        $data['count_pending'] = $count;
        return $data;
    }

     // add ID to viewedby once viewed
    public function updatePostNotif(){

        $appendEmp = request('empID');
        $data = DB::select('update notif_post set viewedby = CONCAT(viewedby, ?, " user") where NOT match(viewedby) against (? IN NATURAL LANGUAGE MODE)', [$appendEmp, 'user'.$appendEmp]);
    }



    public function notifCenter(){
        date_default_timezone_set("Asia/Hong_Kong");


        // leave cred ================================================================================
        $leaveCred = DB::select('select SL, VL, BL, DL from employee where empID = :empid', [UserSession::getSessionID()]);


        // post notif =================================================================================
        $active_user = UserSession::getEmpKey();
        $prev_date = date('Y-m-d', strtotime('-2 week'));
        $cur_date = date('Y-m-d');
        $postNotif = DB::select('select postNotifID, dateadded, notifby_empID, notifBy, notif_msg, notify_dept, notify_users, viewedby
                    from notif_post where
                    (
                        match(notify_users) against (? IN NATURAL LANGUAGE MODE)
                        or match(notify_dept) against (? IN NATURAL LANGUAGE MODE)
                        or match(notify_comp) against (? IN NATURAL LANGUAGE MODE)
                    )
                    AND validate_date between ? and ?
                    AND notifby_empID <> ?
                    order by postNotifID desc',
                [$active_user[0]->empID, 'dept'.$active_user[0]->deptID_, 'comp'.$active_user[0]->compID_, $prev_date, $cur_date, $active_user[0]->empID]);

        $count = 0;
        foreach($postNotif as $val)
        {
            if (strpos($val->viewedby, $active_user[0]->empID) === false) {
                $count++;
            }
        }
        $postNotif['count_pending'] = $count;



        // form notif ======================================================================================
        $prev_date = date('Y-m-d', strtotime('-2 week'));
        $cur_date = date('Y-m-d');
        $formNotif = DB::select('select notif_msg, dateadded, status from notif_form where match(notify_mail) against (:mail IN BOOLEAN MODE)
                            AND validate_date between :prevdate and :cur_date order by formNotifID desc limit 50', ['"'.$active_user[0]->email.'"', $prev_date, $cur_date]);


        $count = 0;
        foreach($formNotif as $val)
        {
            if($val->status == 0)
            {
                $count++;
            }
        }
        $formNotif['count_pending'] = $count;



        // from nav approval pending counter ================================================================
        $req = request('fromnavapproval');

        $approval_notif = [];
        foreach ($req as $key => $value) {
            $pending_count = [];

            foreach ($value as $k => $v) {

                $formcode = $v['formcode'];
                $formcol = $v['col'];
                $exist = DB::select("SHOW TABLES LIKE '$formcode' ");

                // formsupplementary
               if(count($exist) > 0){
                    // $db_approval = DB::select("select count(leaveID) from '.$formcode.' where empID_ = :empID and ", [UserSession::getSessionID()]);
                    $data = '';
                    if($formcode == 'formsupplementary'){
                        // 0 pending, 1 verified by witness, 2 appprover, 3 rejected
                        $data = DB::select('select count(eleave.empID_) as count
                                from '.$formcode.' eleave left join eformapproverbyemp eform
                                    on eleave.empID_ = eform.empID_
                                inner join employee emp
                                    on emp.empID = eleave.empID_
                                where eform.approverID_ = :approverID and
                                eleave.recstat != 1 
                                and eform.'.$formcol.' = 1
                                and eleave.status = 1', [UserSession::getSessionID()]);
                    }else if($formcode == 'formtransmittal'){
                        $data = DB::select('select count(eleave.empID_) as count
                                from '.$formcode.' eleave 
                                where eleave.approvedby = :approverID and
                                eleave.recstat != 1 
                                and eleave.status = 0', [UserSession::getSessionID()]);
                    }
                    
                    else{
                        $data = DB::select('select count(eleave.empID_) as count
                                from '.$formcode.' eleave left join eformapproverbyemp eform
                                    on eleave.empID_ = eform.empID_
                                inner join employee emp
                                    on emp.empID = eleave.empID_
                                where eform.approverID_ = :approverID 
                                and eform.'.$formcol.' = 1
                                and eleave.recstat != 1 
                                and eleave.status = 0', [UserSession::getSessionID()]);
                    }

                    if(count($data) > 0)
                    $v['count'] = $data[0]->count;
               }
                $pending_count[] = $v;
            }

            $approval_notif[$key]= $pending_count;
        }


        // witness notif =====================================================================================

        $witness = [];
        if(request('witness')){
            $witness = DB::select('select count(form.supID) as count
                from formsupplementary form
                where
                    form.empID_
                IN(
                    select empID_ from supplementary_witness
                    where
                        approverID_ = "'.UserSession::getSessionID().'"
                )
                AND
                    (form.witnesses not like ? or form.witnesses is null)
                AND
                    form.recstat != 1
                AND
                    form.status <= 1'
            ,[
                "%".UserSession::getEmpKey()[0]->fullname."%"
            ]);
        }

        // return [$approval_notif, $req] ;
        // consolidated

        return [
            'approval_notif' => $approval_notif,
            'leave_cred' => $leaveCred,
            'post' => $postNotif,
            'form' => $formNotif,
            'witness' => $witness

        ];

    }


}
