<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;
use session;

class FormController extends Controller
{
    //
    public function getForm()
    {
        $data = DB::select('select * from eforms where status = 1');
        return $data;
    }

    public function getFormByDept()
    {
        $data = DB::select('select dept.deptname from department dept inner join employee emp
                on dept.deptID = emp.deptID_
                where emp.empID = :session', // ['00001']);
                [ UserSession::getSessionID() ]);
        if(count($data)>0)
        {

            $deptname = str_replace(' ', '0', (str_replace('-' ,'_' , (str_replace('&','8', $data[0]->deptname)) )) );
            $data = DB::select('select formID, navname, formtitle, '.$deptname.' from eforms where '.$deptname.' = 1', [$deptname, $deptname]);
            return $data;

        }
    }

    public function updateFormByDept($navname = null, $formID = null, $deptname = null, $status = null){
        DB::table('eforms')
            ->where('formID', $formID)
            ->update([$deptname => $status]);
        $deptval = str_replace('0', ' ', $deptname);
        $deptval = str_replace('_', '-', $deptval);
        $deptval = str_replace('8', '&', $deptval);
        $navform = str_replace(' ','0', $navname);

        // update eformusers
        DB::select('update eformuser set '.$navform.' = '.$status.' where empID_ IN (
            select empID from employee where deptID_ = (select deptID from department where deptname = "'.$deptval.'" limit 1)
        )');
    }



    // FORMS NEW TABLE ========================================================================

    // END==========================

    // GET FORM AUTH USER
    public function getUserForm()
    {

        $data = DB::select('select * from eformuser where empID_ = :empid',  //['00001']);
        [ UserSession::getSessionID() ]);
        $forms = [];
        if(count($data)>0):
            foreach ($data[0] as $key=>$value) {
                if($value != 0 && $key != 'empID_'):
                    $navname = str_replace('0', ' ', (str_replace('_' ,'-' , (str_replace('8','&', $key)) )) );
                    $forms[] = ['navname' => $navname];
                endif;
            }
            return $forms;
        endif;
        return $forms;
    }
    // array of allowed forms for user to be used in settings controller
    public function getUserFormNavNames()
    {

        $data = DB::select('select * from eformuser where empID_ = :empid',  //['00001']);
        [ UserSession::getSessionID() ]);
        $forms = [];
        if(count($data)>0):
            foreach ($data[0] as $key=>$value) {
                if($value != 0 && $key != 'empID_'):
                    $navname = str_replace('0', ' ', (str_replace('_' ,'-' , (str_replace('8','&', $key)) )) );
                    $forms[] =  $navname;
                endif;
            }
            return $forms;
        endif;
        return $forms;
    }

    // GET USERS FORMS
    public function getFormUsers()
    {
        $data = DB::select('select form.empID_, emp.fname, emp.lname, form.* from eformuser 
            form inner join employee emp on form.empID_ = emp.empID');
        return $data;
    }

    // UPDATE USERS FORMS
    public function updateFormByUsers($empid = null ,$formname = null ,$status = null){
        DB::table('eformuser')
            ->where('empID_', $empid)
            ->update([$formname => $status]);
    }


    // APPROVERS GENERAL
    public function getFormApprovers()
    {
        $data = DB::select('select form.empID_, emp.fname, emp.lname, form.* 
        from eformapprover form 
        inner join employee emp 
            on form.empID_ = emp.empID 
        and emp.status = 1');
        return $data;
    }

    // APPROVERS BY EMPLOYEE
    public function getFormApproversByEmployee($empID = '')
    {
        
        if($empID){
            $data = DB::select('select form.*, form.approverID_ AS empID_, emp.fname, emp.lname 
            from eformapproverbyemp form 
            inner join employee emp 
                on form.approverID_ = emp.empID 
            where form.empID_ = :empID 
            and emp.status = 1', [$empID]);
            
        }else{
            // $appvByEmployee = DB::select('select form.empID_, emp.fname, emp.lname, form.* from efromapproverbyemp form inner join employee emp on form.empID_ = emp.empID where emp.empID != :empID', [$empID]);
            // DB::select('select form.empID_, emp.fname, emp.lname, form.* from eformapprover form inner join employee emp on form.empID_ = emp.empID');
            $data = $this->getFormApprovers();
            foreach($data as $key => $value){
                $data[$key]->Leave0Form = 0;
                $data[$key]->Undertime0Request = 0;
                $data[$key]->Salary0Discrepancy = 0;
                $data[$key]->Supplementary = 0;
                $data[$key]->Offset = 0;
                $data[$key]->Incident0Report = 0;
                $data[$key]->Company0Loan = 0;
                $data[$key]->Clearance0Form = 0;
                $data[$key]->Calling0Card0Request = 0;
                $data[$key]->Laptop0Form = 0;
                $data[$key]->Work0Request = 0;
                $data[$key]->Financial0Advance = 0;
                $data[$key]->Canvas = 0;
                $data[$key]->MIIS = 0;
                $data[$key]->PRF = 0;
                $data[$key]->Travel0Form = 0;
                $data[$key]->Urgent0Check = 0;
                $data[$key]->Supplier0Accreditation = 0;
                $data[$key]->PRS = 0;
                $data[$key]->Overtime0Request = 0;

                // remove transmittal in datatable 
                unset($data[$key]->Transmittal);
            }
            
        }


        // $data = DB::select('select form.empID_, emp.fname, emp.lname, form.* from efromapproverbyemp form inner join employee emp on form.empID_ = emp.empID where emp.empID != :empID', [$empID]);
        // if(count($data) < 1){
        //     $data = DB::select('select form.empID_, emp.fname, emp.lname, form.* from eformapprover form inner join employee emp on form.empID_ = emp.empID');
        // }
        return $data;

    }

    // USER APPROVERS NAV
    public function getFormNavApproval($empid = null ,$formname = null ,$status = null){

        $data = DB::select('select * from eformapprover where empID_ = :empid',  //['00001']);
        [ UserSession::getSessionID() ]);
        return $data;
        $formnav = [];
        if(count($data)>0):
            foreach ($data[0] as $key=>$value) {
                if($value != 0 && $key != 'empID_'):
                    $navname = str_replace('0', ' ', (str_replace('_' ,'-' , (str_replace('8','&', $key)) )) );
                    $formnav[] = ['navname' => $navname];
                endif;
            }
            return $formnav;
        endif;
    }
    
    // form approval nav names for settings controller
    public function getFormNavApprovalNavNames($empid = null ,$formname = null ,$status = null){

        $data = DB::select('select * from eformapprover where empID_ = :empid',  //['00001']);
        [ UserSession::getSessionID() ]);
        $formnav = [];
        if(count($data)>0):
            foreach ($data[0] as $key=>$value) {
                if($value != 0 && $key != 'empID_'):
                    $navname = str_replace('0', ' ', (str_replace('_' ,'-' , (str_replace('8','&', $key)) )) );
                    $formnav[] =$navname;
                endif;
            }
            return $formnav;
        endif;
    }


// UPDATE FORM APPROVERS
    public function updateFormApprover($empid = null ,$formname = null ,$status = null){
        // return ()
        DB::table('eformapprover')
            ->where('empID_', $empid)
            ->update([$formname => $status]);
    }

    // UPDATE FORM APPROVERS BY EMPLOYEE
    public function updateFormApproverByEmployee($empid = null, $approverid = null ,$formname = null ,$status = null){
        // return ()
       $isExist = DB::select("select empID_, approverID_ from eformapproverbyemp where empID_ = '$empid' and approverID_ = '$approverid'");
        
       if(count($isExist)>0)
       {
            DB::table('eformapproverbyemp')
                ->where('empID_', $empid)
                ->where('approverID_', $approverid)
                ->update([$formname => $status]);
       }else{
            DB::select("insert into eformapproverbyemp(empID_, approverID_, $formname) values('$empid', '$approverid', $status)");
       }
        // from eformapprover WHERE NOT EXISTS
        // (select empID_, approverID_ from eformapproverbyemp where empID_ = '$empid' and approverID_ = '$approverid')"); //, [$empid, $approverid]);



    }




}
