<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Http\Controllers\FormController;
use DB;
use Carbon\Carbon;

class SettingsController extends Controller
{
    //GRAPH
    public function graph(){

        $post = DB::select('select count(*) as counter, DATE_FORMAT(dateposted, "%m") as months from announcement where Year(dateposted) = Year(now()) group by months');
        $employees  = DB::Select('select count(*) as counter, DATE_FORMAT(dhired, "%m") as months from employee where Year(dhired) = Year(now()) group by months');
        $dept  = DB::Select('select count(*) as counter, DATE_FORMAT(datecreated, "%m") as months from department where Year(datecreated) = Year(now()) group by months');
        $branch  = DB::Select('select count(*) as counter, DATE_FORMAT(datecreated, "%m") as months from branchtbl where Year(datecreated) = Year(now()) group by months');

        // COUNT
        $postCount = DB::Select('select count(postID) as count from announcement');
        $empCount = DB::Select('select count(empID) as count from employee');
        $deptCount = DB::Select('select count(deptID) as count from department');
        $branchCount = DB::Select('select count(branchID) as count from branchtbl');

        return [
                'post' => $post, 'emp' => $employees, 'dept' => $dept, 'branch' => $branch,
                'postCount' => $this->arrLen($postCount), 'empCount' => $this->arrLen($empCount), 'deptCount' => $this->arrLen($deptCount), 'branchCount' => $this->arrLen($branchCount),
                ];
    }

    public function arrLen($len = null)
    {
        return $len > 0 ? $len[0] : 0;
    }


    // change Logo
    public function changeLogo(){
        $path = $this->newAvatar();
        request()->merge(['avatar' => $path]);
        // $data = DB::table('employee')->insert(request()->except(['isDisable', 'image']));
        return  request()->all();
    }
    // store image to local disk
    public function newAvatar(){
        $attachment = null;
        $ext = request()->file('image')->guessExtension() == 'jpeg'? 'jpg': request()->file('image')->guessExtension();
    	if(request('image') != 'null' ){
	        $attachment = request()->file('image')->storeAs(
                'public/images', 'comp_logo.png'
            );

	     }
       return $attachment;
    }

    // get default leaves by position
    public function getDefaultLeaveByPos(){
        $data = DB::select('SELECT * FROM leave_pos lp
                    right join positiontbl pos
                        on lp.posID_ = pos.posID
                    where pos.status = 1');
        return $data;
    }

    // set default leaves by position
    public function defaultLeaveByPos(){
        $data = DB::table('leave_pos')->updateOrInsert(['posID_'=>request('posID_')], request()->all());


        // $data = DB::table('leave_pos_emp')->updateOrInsert(
        //     ['posID_'=>request('posID_')
        // ], request()->all());
        DB::select('INSERT INTO leave_pos_emp(empID_, VL, SL, BL, DL)
        SELECT emp.empID, lp.VL, lp.SL, lp.BL,lp.DL FROM leave_pos lp
        right join positiontbl pos
            on lp.posID_ = pos.posID
		inner join employee emp
            on emp.posID_ = pos.posID
        where pos.status = 1
        ON DUPLICATE KEY UPDATE VL=lp.VL, SL=lp.SL, BL = lp.BL, DL = lp.DL');
        return request()->all();
    }

    // get users by posID
    public function getUsersByPos($posID){
        $data = DB::select('select leavpos.*, emp.fname, emp.lname, emp.empID from leave_pos_emp leavpos
                inner join employee emp
                    on leavpos.empID_ = emp.empID
                where posID_ = :posID', [$posID]);
        return $data;
    }


    // DEFAULT LEAVE BY EMP ======================================================
    public function defaultLeaveByEmp(){
        $data = DB::table('leave_pos_emp')->updateOrInsert(['empID_'=>request('empID_')], request()->all());
        return request()->all();
    }



    // reset now for maintenance ===============================================
    public function resetNow(){
        $data = DB::select("UPDATE employee a
                    INNER JOIN leave_pos_emp b ON a.empID = b.empID_
                    SET a.VL = b.VL,
                    a.SL = b.SL,
                    a.BL = b.BL,
                    a.DL = b.DL");
        return $data;

    }


    // FORM SETTINGS =============================================================
    public function getForm_Settings()
    {
        $data = DB::select('select formID, formtitle, td from eforms where status = 1');
        return $data;
    }

    public function getFormRecords(){

        // $data = DB::table(request(''))->select('name', 'email as user_email')->get();
        $sql = "select form.status, form.datefiled, CONCAT(emp.fname, emp.lname) as fullname,
                dept.deptname, comp.compname, pos.posname, brch.branchname
                    from ".request('td')." form
                    inner join employee emp
                        on form.empID_ = emp.empID
                    inner join department dept
                        on emp.deptID_ = dept.deptID
                    inner join companytbl comp
                        on emp.compID_ = comp.compID
                    inner join positiontbl pos
                        on emp.posID_ = pos.posID
                    inner join branchtbl brch
                        on emp.branchID_ = brch.branchID
                where form.status = ".request('status')."
                and form.datefiled between :datefrom and :dateto
                and form.recstat = 0";

        if(request()->has('recstat')){
           $sql = "select form.status, form.datefiled, CONCAT(emp.fname, emp.lname) as fullname,
                dept.deptname, comp.compname, pos.posname, brch.branchname
                    from ".request('td')." form
                    inner join employee emp
                        on form.empID_ = emp.empID
                    inner join department dept
                        on emp.deptID_ = dept.deptID
                    inner join companytbl comp
                        on emp.compID_ = comp.compID
                    inner join positiontbl pos
                        on emp.posID_ = pos.posID
                    inner join branchtbl brch
                        on emp.branchID_ = brch.branchID
                where form.recstat = 1
                and form.datefiled between :datefrom and :dateto";
        }
        $data = DB::select($sql, [
            request('datefrom'),
            request('dateto')
        ]);

        return $data;
    }

    public function enableFormRecords(){
        $sql = "update ".request('td')." set recstat = 0
                where datefiled between :datefrom and :dateto";
        DB::statement($sql,  [
            request('datefrom'),
            request('dateto')
        ]);

        return http_response_code(201);
    }

    public function disableFormRecords(){

        $sql = "update ".request('td')." set recstat = 1
                    where status = ".request('status')."
                and datefiled between :datefrom and :dateto";
        DB::statement($sql,  [
            request('datefrom'),
            request('dateto')
        ]);

        return http_response_code(201);
    }



    // POST SETTINGS ==================================================================================
    public function getPostRecords(){
        // return request()->all();
        // $sql = '';
        // // inactive

        // // actives
        $sql = 'select ann.*, CONCAT(emp.fname, emp.lname) AS fullname
                from announcement ann
                    inner join employee emp
                        on ann.postedby_ = emp.empID
                where
                    emp.deptID_ = :deptID
                    and ann.status = :status
                    and ann.dateposted between :datefrom and :dateto
            ';
        $data = DB::select($sql, [
            'deptID'   => request('deptID'),
            'status'   => request('status'),
            'datefrom' => request('datefrom'),
            'dateto'   => request('dateto')
        ]);
        return $data;
    }

    public function disablePostRecords(){
    //    return request()->all();
        $sql = 'update announcement ann
                inner join employee emp
                on ann.postedby_ = emp.empID
                   set ann.status = :status
                   where
                       emp.deptID_ = :deptID AND
                    ann.dateposted between :datefrom and :dateto
            ';
        $data = DB::statement($sql, [
            'status'   => request('status'),
            'deptID'   => request('deptID'),
            'datefrom' => request('datefrom'),
            'dateto'   => request('dateto')
        ]);
        return http_response_code(200);
    }

    public function enablePostRecords(){
        $sql = 'update announcement ann
                inner join employee emp
                on ann.postedby_ = emp.empID
                    set ann.status = :status
                    where
                        emp.deptID_ = :deptID AND
                    ann.dateposted between :datefrom and :dateto
            ';
        $data = DB::statement($sql, [
            'status'   => request('status'),
            'deptID'   => request('deptID'),
            'datefrom' => request('datefrom'),
            'dateto'   => request('dateto')
        ]);
        return http_response_code(200);
    }

    // DEL
    public function delAnnouncement($postID = null){
        if($postID){
            DB::table('announcement')->where('postID', $postID)->delete();
        }
        else{
            
            // return request()->all();
            // get files to be deleted permanently
            $records = DB::select("select ann.attachment, emp.empID
                            from announcement ann
                            inner join employee emp
                                on ann.postedby_ = emp.empID
                            WHERE
                                emp.deptID_ = ".request('deptID')."
                            AND
                                ann.dateposted BETWEEN '".request('datefrom')."' AND '".request('dateto')."'");


            if(count($records) > 0){
                foreach ($records as $key => $value) {
                    UserSession::delAttachment($value->attachment);
                }
            }

            // delete announcement from db
            DB::statement("
                delete from announcement where postedby_
                IN (
                    select empID
                    from employee
                    where deptID_ = ".request('deptID')."
                )
                AND
                    dateposted BETWEEN '".request('datefrom')."' AND '".request('dateto')."'
            ");
        }


    }


    // FORM GROUP =======================================================================================
    public function getFormGroup(){
        $data = DB::select('select * from form_group');
        return $data;
    }

    // add form group
    public function addFormGroup(){
        $data = DB::table('form_group')->insertGetId(request()->all());
        return $data;
    }

    // delete from group
    public function deleteFormGroup(){
        $data = DB::table('form_group')->where('id', request('id'))->delete();
        // $data = DB::table('form_group_detail')->where('id', request('id'))->delete();
        return http_response_code(200);
    }

    // update from group
    public function updateFormGroup(){
        $data = DB::table('form_group')->where('id', request('id'))->update(request()->all());
        return http_response_code(200);
    }

    // manage form group details ================
    public function getFormGroupDetails(){

        $data = DB::select('select e.formID, e.formtitle, g.groupname, g.id as group_id
                    from eforms e
                    left join form_group_detail d
                        on e.formID = d.formID_
                    left join form_group g
                        on g.id = d.groupID_
                    where e.status = 1
        ');
        return $data;
    }

    public function setFormGroupDetails(){
        $data = DB::table('form_group_detail')
        ->updateOrInsert(
            ['formID_' => request('formID_')], //condition
            request()->all() //inserted
        );
        return http_response_code(200);
    }


    public function getFormNav(){
        $par = new FormController;
        $nav_user = array_values($par->getUserFormNavNames());
        $data = DB::select('select e.formID, e.formtitle, e.navname,  g.groupname, g.id as group_id
                    from eforms e
                    left join form_group_detail d
                        on e.formID = d.formID_
                    left join form_group g
                        on g.id = d.groupID_
        ');
        $nav_list= [];
        foreach ($data as $key => $value) {
            if(in_array($value->navname, $nav_user) && $value->groupname != ''){
                // if(array_key_exists($value->groupname, $nav_list)){
                //     $nav_list[$value->groupname][] = ['navname'=>$value->navname];
                // }else{
                    $nav_list[$value->groupname][] = ['navname'=>$value->navname];
                // }

            }
        }
        return $nav_list;
        // return $nav_user;
    }


    public function getFormNavApprover(){
        $par = new FormController;
        $nav_user = array_values($par->getFormNavApprovalNavNames());
        $data = DB::select('select e.formID, e.formtitle, e.navname,  e.td, g.groupname, g.id as group_id
                    from eforms e
                    left join form_group_detail d
                        on e.formID = d.formID_
                    left join form_group g
                        on g.id = d.groupID_
        ');
        $nav_list= [];
        foreach ($data as $key => $value) {
            if(in_array($value->navname, $nav_user) && $value->groupname != ''){
                // if(array_key_exists($value->groupname, $nav_list)){
                //     $nav_list[$value->groupname][] = ['navname'=>$value->navname];
                // }else{
                    $formcol = str_replace(' ','0', $value->navname);
                    $formcol = str_replace('-','_', $formcol);
                    $formcol = str_replace('&','8', $formcol);
                    $nav_list[$value->groupname][] = ['navname'=>$value->navname, 'formcode'=> $value->td, 'col' => $formcol];
                // }

            }
        }
        return $nav_list;
        // return $nav_user;
    }


    // OVERRIDE SETTINGS
    function getOverrideSetting() {
        $data = DB::select('select type, json from override_setting');
        return $data;
    }

    function addOverrideSetting() {
        date_default_timezone_set("Asia/Hong_Kong");
        $dt = Carbon::parse(Carbon::now());
        $dateID = $dt->valueOf();
        request()->merge(['id' => $dateID ]);
        // return request()->all();
        $getData = DB::table('override_setting')
            ->select('json')
            ->where('type', request('type'))
            ->get();

        if($getData) {
            // return $getData[0]->json;
            $arr = json_decode($getData[0]->json, true);

            // foreach($arr as $item) { //foreach element in $arr
            //     return $item['name']; //etc
            // }
        
            $arr[] = request()->except('type');
            $arr = json_encode($arr);
            DB::table('override_setting')->updateOrInsert(
                ['type' => request('type')], // checking
                ['json' => $arr] // value to update
            );

            return request()->all();
        }
        
    }

    function updateOverrideSetting() {
        $getData = DB::table('override_setting')
            ->select('json')
            ->where('type', request('type'))
            ->get();
        if($getData) {
            // return $getData[0]->json;
            $arr = json_decode($getData[0]->json, true);
            $temp = [];
            foreach($arr as $item) { //foreach element in $arr
                if($item['id'] == request('id')) {
                    $item['name'] = request('name');
                    $temp[] = $item;
                }else{
                    $temp[] = $item;
                }
            }
            $arr = json_encode($temp);
             DB::table('override_setting')->updateOrInsert(
                ['type' => request('type')], // checking
                ['json' => $arr] // value to update
            );
            return request()->all();
            
        }
    }

    function delOverrideSetting() {
        $getData = DB::table('override_setting')
            ->select('json')
            ->where('type', request('type'))
            ->get();
        if($getData) {
            // return $getData[0]->json;
            $arr = json_decode($getData[0]->json, true);
            $temp = [];
            foreach($arr as $item) { //foreach element in $arr
                if($item['id'] != request('id')) {
                    $temp[] = $item;
                } 
            }
            $arr = json_encode($temp);
             DB::table('override_setting')->updateOrInsert(
                ['type' => request('type')], // checking
                ['json' => $arr] // value to update
            );
            return request()->all();
            
        }
    }
}
