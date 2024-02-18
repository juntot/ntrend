<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class GreetingController extends Controller
{
    //Birthday greetings
    function birthdayGreeting(){
       $result = DB::select('select * from birthdategreetings where status = 1'); 
       return $result;
    }
    
    /*
CREATE VIEW birthdategreetings AS
select emp.avatar AS avatar,
  concat(emp.fname,' ',emp.lname) AS fullName,
  emp.birthdate AS birthdate,
  bran.branchname AS branchname,
  pos.posname AS posname,
  dept.deptname AS deptname 
from employee emp left join branchtbl bran 
  on emp.branchID_ = bran.branchID
left join positiontbl pos 
  on emp.posID_ = pos.posID
left join department dept 
  on emp.deptID_ = dept.deptID
inner join users user
  on emp.empID = user.empID
where 
user.isAdmin = 0
and emp.status = 1
and date_format(emp.birthdate,'%m-%d') = date_format(addtime(current_timestamp(),'09:04:00'),'%m-%d') 
or if(
  dayofweek(addtime(current_timestamp(),'09:04:00')) = 6 
  and dayofweek(CONCAT(
    date_format(addtime(current_timestamp(),'09:04:00'),'%Y-'),
    date_format(emp.birthdate,'%m-%d')
  )) in (1, 7)
  
  
  and week(CONCAT(
    date_format(addtime(current_timestamp(),'09:04:00'),'%Y-'),
    date_format(emp.birthdate,'%m-%d')
  ),3) 
  = week(addtime(current_timestamp(),'09:04:00'),3)
  ,
  1,
  0)  
= 1 

*/


// select `emp`.`status` AS `status`,`emp`.`avatar` AS `avatar`,concat(`emp`.`fname`,' ',`emp`.`lname`) AS `fullName`,`emp`.`birthdate` AS `birthdate`,`bran`.`branchname` AS `branchname`,`pos`.`posname` AS `posname`,`dept`.`deptname` AS `deptname` from ((((`trends`.`employee` `emp` left join `trends`.`branchtbl` `bran` on(`emp`.`branchID_` = `bran`.`branchID`)) left join `trends`.`positiontbl` `pos` on(`emp`.`posID_` = `pos`.`posID`)) left join `trends`.`department` `dept` on(`emp`.`deptID_` = `dept`.`deptID`)) join `trends`.`users` `user` on(`emp`.`empID` = `user`.`empID`)) where `user`.`isAdmin` = 0 and date_format(`emp`.`birthdate`,'%m-%d') = date_format(addtime(current_timestamp(),'09:04:00'),'%m-%d') or if(dayofweek(addtime(current_timestamp(),'09:04:00')) = 6 and dayofweek(concat(date_format(addtime(current_timestamp(),'09:04:00'),'%Y-'),date_format(`emp`.`birthdate`,'%m-%d'))) in (1,7) and week(concat(date_format(addtime(current_timestamp(),'09:04:00'),'%Y-'),date_format(`emp`.`birthdate`,'%m-%d')),3) = week(addtime(current_timestamp(),'09:04:00'),3),1,0) = 1 and `emp`.`status` = 1
}
