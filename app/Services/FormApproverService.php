<?php

namespace App\Services;

use App\Services\UserSession;
use DB;


class FormApproverService{

  /*
  Get Approver Depending on a form type
  */
    public static function getFormApproverByUser($formType){
      $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers, emp.email 
                          from eformapproverbyemp eform 
                          right join employee emp 
                            on eform.approverID_ = emp.empID 
                          where eform.'.$formType.' = 1 
                          and eform.empID_ = :empiD
                          and emp.status = 1', [UserSession::getSessionID()]);
        return $data;
    }



    public static function getApproverEmail($id, $idVal, $form, $colName) { 
      $result = DB::select('select emp.email from employee emp
      right join eformapproverbyemp eform
      on eform.approverID_ = emp.empID  
      join '.$form.' form
      on form.empID_ = eform.empID_  
      where form.'.$id.' = :id
      and eform.'.$colName.' = 1
      and emp.status = 1', 
      [$idVal]);
      
      $emails = [];
      foreach ($result as $value) {
          if($value->email)
          $emails[] = $value->email;
      }
      return $emails;
  }

}


