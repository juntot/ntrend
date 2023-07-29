<?php

namespace App\Http\Controllers;
use App\Services\UserSession;
use Illuminate\Http\Request;
use DB;

class CommentController extends Controller
{
    //

    function addComment(){
        
        request()->merge(['commentBy_' => UserSession::getSessionID() ]);
        $id = DB::table('comments')->insertGetId(request()->except(['avatar']));
        return $id;
    }

    function editComment(){
        // DB::table('comments')
        //     ->where('leaveID', request('leaveID'))
        //     ->update(request()->except(['isDisable', 'leaveID', 'remarks', 'approvedby', 'reciever_emails', 'empID_', 'status']));
    }

    function deleteComment($id = ''){
        $data = DB::table('comments')->where('comment_ID', '=', $id)
        ->update(['status'=>1]);
        return $data;
    }
}
