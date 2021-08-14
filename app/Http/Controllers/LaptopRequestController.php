<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;

class LaptopRequestController extends Controller
{
     // ADD
     public function addLaptopRequest(){
        // Session ID
        request()->merge(['empID_' => UserSession::getSessionID()]);
        $data = DB::table('formlaptoprequest')->insertGetId(request()->except(['isDisable', 'laptopID', 'remarks', 'approvedby']));
            // $data = DB::select('select laptopID from formlaptoprequest order by laptopID desc limit 1');
            // GET LAST INDEX
            request()->merge(['status' => 0, 'laptopID' => $data]);
            return request()->except(['isDisable']);
    }
    // UPDATE
    public function updateLaptopRequest(){
        // SESSION ID
        DB::table('formlaptoprequest')
            ->where('laptopID', request('laptopID'))
            ->update(request()->except(['isDisable', 'laptopID', 'remarks', 'approvedby']));
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        return request()->except(['isDisable']);
    }

    // DELETE
    public function deleteLaptopRequest($laptopID  = null){
        DB::table('formlaptoprequest')->where('laptopID', '=', $laptopID)
        ->update(['recstat' => 1]);
        // ->delete();
    }

    // GET
    public function getLaptopRequestByEmployee(){
        // SESSION ID
        $data = DB::select('select form.*, CONCAT(emp.fname," ", emp.lname) as hrapprover from formlaptoprequest form left join employee emp on
        form.hrapprover = emp.empID where form.empID_ = :empid', [UserSession::getSessionID()]);

        return $data;
    }

    // FOR APPROVERS ====================================================================================================================================
    // GET LaptopRequest FORM EMPLOYEE APPROVERS
    public function getLaptopRequestApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.Laptop0Form = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.Laptop0Form = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalLaptopRequest(){
        // $data = DB::select('select * from formlaptoprequest where status = 0 OR hrapprover != :empid', [ UserSession::getSessionID() ]);
        $data = DB::select('select elaptop.* from formlaptoprequest elaptop left join eformapproverbyemp eform on elaptop.empID_ = eform.empID_ where eform.approverID_ = :approverID', [UserSession::getSessionID()]);
        return $data;
    }

    // IE APPROVE / REJECTED
    public function actionFormLaptopRequest(){
        DB::table('formlaptoprequest')
        ->where('laptopID', request('laptopID'))
        ->update([
            'status' => request('status'), 'hrapprover'=> request('hrapprover'),
            'approveddate'=> date("Y-m-d"), 'remarks' => request('remarks')
            ]);

        return request()->all();
    }

}

