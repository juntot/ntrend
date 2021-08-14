<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;

class PRSController extends Controller
{
    // UPDATE
    public function updatePRF(){
        // SESSION ID
        DB::table('formPRF')
            ->where('prfID', request('prfID'))
            ->update(request()->except(['isDisable', 'prfID', 'entries', 'itemdesc', 'uom', 'qty', 'allocatedbudget', 'reason', 'remarks']));
        // FROM SUP DETAILS DATA
        $prfrecord = request()->only(['entries']);
        $prfdetailsparams = [];
        foreach($prfrecord as $keys=>$val)
        {
           foreach($val as $key=>$val2)
           {    
                $prfdetailsparams[] = array_merge($val2, ['prfID_' => request('prfID')]);
           }
        }
        
        //DELETE AND THEN ADD
        DB::table('formPRFdetails')->where('prfID_', '=', request('prfID'))->delete();
        DB::table('formPRFdetails')->insert($prfdetailsparams);
        request()->merge(['empID_' => UserSession::getSessionID(), 'status' => 0]);
        return request()->except(['isDisable']);
    }
    
   

    // GET
    public function getPRSByEmployee(){
        // SESSION ID
        // $data = DB::select('select * from formPRF where empID_ = :empid', [UserSession::getSessionID()]);
        $data = DB::select('select form.*, CONCAT(emp.fname," ", emp.lname) as approvedby from formPRF form left join employee emp on  
        form.approvedby = emp.empID where form.empID_ = :empid and form.status = 1', [UserSession::getSessionID()]);
        
        // check if data has vale
        if(count($data) > 0)
        {
           
           foreach($data as $keys => $value)
           {
                $prfID = $data[$keys]->prfID;
                // never add supdetailsID to avoid error in update
                $supdetails = DB::select('select prfdetailsID, prfID_, itemdesc, uom, qty, allocatedbudget, 
                                          reason, accountableto, receivedby, receiveddate from formPRFdetails where prfID_ = :prfID', [$prfID]);
                
                $data[$keys]->entries = $supdetails;
           }
            
        }
        
        return $data;
    }


    // FOR APPROVERS ====================================================================================================================================
    // GET PRF FORM EMPLOYEE APPROVERS
    public function getPRFApprover(){
        // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformuser eform right join employee emp on eform.empID_ = emp.empID where eform.PRF = 1');
        $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers from eformapproverbyemp eform right join employee emp on eform.approverID_ = emp.empID where eform.PRF = 1 and eform.empID_ = :empiD', [UserSession::getSessionID()]);
        return $data;
    }

    // GET FORMS FOR APPROVAL
    public function approvalPRSRequest(){
        // $data = DB::select('select * from formPRF where status = 0 OR approvedby != :empid', ['000001']);
        //  // check if data has vale
        //  if(count($data) > 0)
        //  {
            
        //     foreach($data as $keys => $value)
        //     {
        //          $prfID = $data[$keys]->prfID;
        //          // never add supdetailsID to avoid error in update
        //          $supdetails = DB::select('select prfID_, itemdesc, uom, qty, allocatedbudget, reason, accountableto from formPRFdetails where prfID_ = :prfID', [$prfID]);
        //          $data[$keys]->entries = $supdetails;
        //     }
             
        //  }
        // return $data;
        // OLD-----
        // $data = DB::select('select form.*, CONCAT(emp.fname," ", emp.lname) as approvedby from formPRF form left join employee emp on  
        // form.approvedby = emp.empID where form.empID_ != :empid and form.status = 1', 

        // $data = DB::select('select form.*, CONCAT(emp.fname," ", emp.lname) as approvedby from formPRF form left join employee emp on  
        // form.approvedby = emp.empID where (form.status = 1 and form.empID_ != :empID) OR form.approvedby = :empid',
        // [ UserSession::getSessionID(), UserSession::getSessionID() ]);

        $data = DB::select('select ePRF.* from formPRF ePRF left join eformapproverbyemp eform on ePRF.empID_ = eform.empID_ where eform.approverID_ = :approverID 
        and ePRF.status = 1', [UserSession::getSessionID()]);
        
        // check if data has vale
        if(count($data) > 0)
        {
           
           foreach($data as $keys => $value)
           {
                $prfID = $data[$keys]->prfID;
                // never add supdetailsID to avoid error in update
                $supdetails = DB::select('select prfdetailsID, prfID_, itemdesc, uom, qty, allocatedbudget, 
                                          reason, accountableto, receivedby, receiveddate from formPRFdetails where prfID_ = :prfID', [$prfID]);
                
                $data[$keys]->entries = $supdetails;
           }
            
        }
        
        return $data;
    }


    // IE RECIEVED / CANCEL
    public function actionReceivedPRS(){
        DB::table('formprfdetails')
        ->where('prfdetailsID', request('prfdetailsID'))
        ->update([
            'receivedby'=> request('receivedby'), 
            'receiveddate'=> date("Y-m-d")
            ]);
        
        return request()->all();
    }
}
