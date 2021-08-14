<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TermsConditionController extends Controller
{
    //

    public function showTerms(){
        $markup = DB::table('terms_conditions')->get();
        return view('terms_and_conditions', compact(['markup']));
    }


    public function updateTerms(){
        DB::table('terms_conditions')
            ->update(request()->all());
    }

    public function getTermsCode(){
        $data = DB::table('terms_conditions')->get();
        return $data;
    }
}
