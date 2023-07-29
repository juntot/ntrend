<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use App\Services\MailServices;
use Carbon\Carbon;
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
            'ann.tagwith_dept',
            'ann.tagwith_comp'
        )
        ->whereRaw('(match(ann.taggednames) against (? IN BOOLEAN MODE)
                    or match(ann.taggedepts) against (? IN BOOLEAN MODE)
                    or match(ann.taggedcomps) against (? IN BOOLEAN MODE))',
            [$active_user[0]->empID, 'dept'.$active_user[0]->deptID_, 'comp'.$active_user[0]->compID_])
        ->where('ann.status', 0)
        ->orderBy('ann.dateposted', 'desc')
        ->simplePaginate(7);

        
        foreach ($data as $key => $value) {
            $comments = DB::select('select comm.*, CONCAT(emp.fname," ",emp.lname) as fullname, emp.avatar from comments comm 
                                    inner join employee emp 
                                        on comm.commentBy_ = emp.empID
                                    where comm.postID_ = :postID and comm.status = 0 order by comm.comment_ID', 
                                 [ 'postID'=> $value->postID]);
            $value->comments = $comments;
            $data[$key] = $value;
        }
        return $data;
        // return $data;

    }

    // ADD
    public function addAnnouncement(){
        date_default_timezone_set("Asia/Hong_Kong");
        $carbon_format = Carbon::parse(request('dateposted'))->format('Y-m-d');

        $file_path = '/'.$carbon_format.'/'.request('postedby_');
        $path = UserSession::postAttachment($file_path);

        request()->merge(['attachment' => $path]);
        request()->merge(['taggednames' => str_replace(',', ' @ ', request('taggednames'))]);
        request()->merge(['taggedepts' => str_replace(',', ' @ ', request('taggedepts'))]);
        request()->merge(['taggedcomps' => str_replace(',', ' @ ', request('taggedcomps'))]);

        // dd(request()->all(), request('taggedcomps'));

        $lastID = DB::table('announcement')->insertGetId(request()->except(['file']));
        // $lastID = DB::select('select postID from announcement where postedby_ = :empID order by 1 desc limit 1', [UserSession::getSessionID()]);
        request()->merge(['postID' => $lastID, 'comments'=>[]]);
        // date('Y-m-d H:i:s', time());


        // NOTIFICATIONS
        MailServices::postNotify(request('taggednames'), request('taggedepts'), request('taggedcomps'), request('message'));
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



    // DEL LOGICAL
    public function delAnnouncementLogical($postID = null){

        DB::table('announcement')
            ->where('postID', $postID)
            ->update(['status' => 1]);

    }


    // POST METHOD ANNOUNCEMENT
    // search employee
    public function searchEmp(){
        $data = DB::select('select empID,  CONCAT(fname," ",lname) as fullname from employee where (fname like :search OR lname like :search2) 
        and empID not IN( :empID, "00001" )
        AND status = 1
        order by fname limit 30',
                [request('keyword').'%', request('keyword').'%', UserSession::getSessionID()]);
        return $data;
    }

    // search department
    public function searchDept(){
        $active_user = UserSession::getEmpKey();
        $data = DB::select('select deptID,  deptname from department where deptname like :search and deptID <> :deptID order by deptname limit 30',
                [request('keyword').'%', $active_user[0]->deptID_]);
        return $data;
    }

    // search company
    public function searchComp(){
        $active_user = UserSession::getEmpKey();
        $data = DB::select('select compID,  compname from companytbl where compname like :search order by compname limit 30',
                [request('keyword').'%']);
        return $data;
    }
}

