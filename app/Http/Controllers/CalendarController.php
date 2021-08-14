<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;

class CalendarController extends Controller
{
    //
    public function getEvents(){
        $data = DB::select('select *, "red" as color from ph_holidays where status = 1');
        return $data;
    }

    public function addEvent(){
        DB::table('ph_holidays')->insert(request()->except(['color']));
        return request()->all();
    }

    public function updateEvent(){
        DB::table('ph_holidays')->where('id', request('id'))->update(request()->only(['details', 'name', 'start']));
        return request()->all();
    }

    public function delEvent(){
        DB::table('ph_holidays')->where('id', request('id'))->update(['status' => 0]);
        return request()->all();
    }


    // plot leaves and holidays
    public function plotCalendar(){

        // 'Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave',
        // 'Descritionary Leave', 'Solo Parent Leave', 'Paternity Leave', 'Others'
        $data = [];

        $emp = DB::select('select deptID_, branchID_ from employee where empID = :empID', ['empID' => request('empID')]);
        if($emp){
            $data = DB::select('
            Select name, start, start as end, details, "red" as color from ph_holidays where status = 1
            UNION
            Select concat(emp.fname," ",emp.lname, " - ",
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
            ) as name, form.datestart as start, form.dateend as end, form.reason as details, "primary" as color
            from formleave form
                inner join
            employee emp
                on emp.empID = form.empID_
            WHERE
            form.datestart between :start and :end
            and emp.deptID_ = :deptID
            and emp.branchID_ = :branchID
            and (form.recstat != 1 and form.status = 1)
            ',[
                'start'  => request('start'),
                'end'    => request('end'),
                'deptID' => $emp[0]->deptID_,
                'branchID' => $emp[0]->branchID_,
            ]);
        }

        return $data;
    }




}
