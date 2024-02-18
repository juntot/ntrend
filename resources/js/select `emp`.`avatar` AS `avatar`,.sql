select `emp`.`avatar` AS `avatar`,
concat(`emp`.`fname`,' ',`emp`.`lname`) AS `fullName`,
`emp`.`birthdate` AS `birthdate`,
`bran`.`branchname` AS `branchname`,
`pos`.`posname` AS `posname`,
`dept`.`deptname` AS `deptname` 
from `employee` `emp` 
  left join `trends`.`branchtbl` `bran` 
    on `emp`.`branchID_` = `bran`.`branchID` 
  left join `trends`.`positiontbl` `pos` 
    on `emp`.`posID_` = `pos`.`posID`
  left join `trends`.`department` `dept` 
    on `emp`.`deptID_` = `dept`.`deptID` 
  inner join users user
    on emp.empID = user.empID
  where date_format(`emp`.`birthdate`,'%m-%d') = 
    date_format(addtime(current_timestamp(),'09:04:00'),'%m-%d') 
    
    or if(dayofweek(addtime(current_timestamp(),'09:04:00')) = 6 
    -- CONCAT(CAST(`date_from` AS CHAR),' - ',CAST(`date_to` AS CHAR)
    and dayofweek(CONCAT(
      date_format(addtime(current_timestamp(),'09:04:00'),'%Y-'),
      date_format(`emp`.`birthdate`,'%m-%d')
    )) in (1, 7)
    -- and dayofweek(`emp`.`birthdate`) in (1,7) 
    -- and week(`emp`.`birthdate`,3) = week(addtime(current_timestamp(),'09:04:00'),3),1,0) = 1 
    and `emp`.`status` = 1
    and user.isAdmin = 0


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
    
    