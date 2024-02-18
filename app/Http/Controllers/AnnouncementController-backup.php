<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use DB;



class AnnouncementController extends Controller
{
    // GET
    public function getAnnouncement(){

       /*  $active_user = UserSession::getEmpKey();
        $data = DB::table('announcement as ann')
        ->join('employee as emp', 'ann.postedby_', '=', 'emp.empID')
        ->select(
            DB::raw('CONCAT(emp.fname," ",emp.lname) as fullname'),
            'ann.*',
            'emp.avatar'
        )
        ->whereRaw('match(ann.taggednames) against (? IN BOOLEAN MODE) ', [$active_user[0]->empID])
        ->orWhereRaw('match(ann.taggedepts) against (? IN BOOLEAN MODE) ', ['dept'.$active_user[0]->deptID_])
        ->where('ann.status', 0)
        ->orderBy('ann.dateposted', 'desc')
        ->simplePaginate(7); */

        $active_user = UserSession::getEmpKey();
        $data = DB::table('announcement as ann')
        ->join('employee as emp', 'ann.postedby_', '=', 'emp.empID')
        ->select(
            DB::raw('CONCAT(emp.fname," ",emp.lname) as fullname'),
            'ann.*',
            'emp.avatar',
            'ann.tagwith_emp',
            'ann.tagwith_dept'
        )
        ->whereRaw('(match(ann.taggednames) against (? IN BOOLEAN MODE)
                    or match(ann.taggedepts) against (? IN BOOLEAN MODE))',
            [$active_user[0]->empID, 'dept'.$active_user[0]->deptID_])
        ->where('ann.status', 0)
        ->orderBy('ann.dateposted', 'desc')
        ->simplePaginate(7);
        return $data;
    }

    // ADD
    public function addAnnouncement(){
        date_default_timezone_set("Asia/Hong_Kong");
        $path = UserSession::postAttachment();

        request()->merge(['attachment' => $path]);
        request()->merge(['taggednames' => str_replace(',', ' @ ', request('taggednames'))]);
        request()->merge(['taggedepts' => str_replace(',', ' @ ', request('taggedepts'))]);
        DB::table('announcement')->insert(request()->except(['file']));
        $lastID = DB::select('select postID from announcement where postedby_ = :empID order by 1 desc limit 1', [UserSession::getSessionID()]);
        request()->merge($lastID);
        // date('Y-m-d H:i:s', time());


        // NOTIFICATIONS
        MailServices::postNotify(request('taggednames'), request('taggedepts'), request('message'));
        return request()->except(['file']);
    }

    // UPDATE
    public function updateAnnouncement(){
         // update
        DB::table('announcement')
            ->where('postID', request('postID'))
            ->update(request()->only('message', 'dateposted'));
        return request()->all();
    }

    // DEL
    public function delAnnouncement($postID = null){
        DB::table('announcement')->where('postID', $postID)->delete();

    }

    // DEL LOGICAL
    public function delAnnouncementLogical($postID = null){

        DB::table('announcement')
            ->where('postID', $postID)
            ->update(['status' => 1]);

    }


    // search employee
    public function searchEmp($searchkey = ''){
        $data = DB::select('select empID,  CONCAT(fname," ",lname) as fullname from employee where (fname like :search OR lname like :search2) and empID <> :empID order by fname limit 10',
                [$searchkey.'%', $searchkey.'%', UserSession::getSessionID()]);
        return $data;
    }

    // search department
    public function searchDept($searchkey = ''){
        $active_user = UserSession::getEmpKey();
        $data = DB::select('select deptID,  deptname from department where deptname like :search and deptID <> :deptID order by deptname limit 10',
                [$searchkey.'%', $active_user[0]->deptID_]);
        return $data;
    }

    // search company
    public function searchComp($searchkey = ''){
        $active_user = UserSession::getEmpKey();
        $data = DB::select('select compID,  compname from companytbl where compname like :search and compID <> :compID order by compname limit 10',
                [$searchkey.'%', $active_user[0]->compID_]);
        return $data;
    }
}

