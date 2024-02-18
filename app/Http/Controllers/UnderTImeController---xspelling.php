<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;

class UnderTimeController extends Controller
{
    // ADD
    public function addUndertime(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        DB::table('formundertime')->insert(request()->except(['isDisable', 'undertimeID', 'remarks', 'approvedby']));
            $data = DB::select('select undertimeID from formundertime order by undertimeID desc limit 1');
            // GET LAST INDEX
            request()->merge(['status' => 0, 'undertimeID' => $data[0]->undertimeID]);
            return request()->except(['isDisable']);
    }
    // UPDATE
    public function updateUndertime(){
        // SESSION ID
        DB::table('formundertime')
            ->where('undertimeID', request('undertimeID'))
            ->update(request()->except(['isDisable', 'undertimeID', 'remarks', 'approvedby']));
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        return request()->except(['isDisable']);
    }
    
    // DELETE
    public function deleteUndertime($undertimeID  = null){
        DB::table('formundertime')->where('undertimeID', '=', $undertimeID)->delete();
    }

    // GET
    public function getUndertimeByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*, CONCAT(emp.fname," ", emp.lname) as approvedby from formundertime form left join employee emp on  
        form.approvedby = emp.empID where form.empID_ = :empid', [UserSession::getSessionID()]);
        
        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET UNDERTIME FORM EMPLOYEE APPROVERS
    public function getUndertimeApprover(){
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Undertime0Request = 1');
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalUndertimeRequest(){
        $data = DB::select('select * from formundertime where (status = 0 and empID_ != :empID) OR approvedby = :empid', 
        [ UserSession::getSessionID(), UserSession::getSessionID() ]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormUndertime(){
        DB::table('formundertime')
        ->where('undertimeID', request('undertimeID'))
        ->update([
            'status' => request('status'), 'approvedby'=> request('approvedby'), 
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);
        
        return request()->all();
    }

}
