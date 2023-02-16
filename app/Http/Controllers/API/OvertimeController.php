<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OvertimeController extends Controller
{

    public function addOvertime(Request $request){
        foreach($request->list as $userid) {

            $param = [
                $userid,
                $request->datefr,
                $request->dateto,
                $request->start,
                $request->end,
                $request->type
            ];
            DB::insert("exec uspOvertimeAdd ?, ?, ?, ?, ?, ?", $param);
        }
    }

    public function delOvertime(Request $request){
        foreach($request->list as $userid) {
            
            DB::table('emp_overtime')
                ->where('userid', $userid)
                ->whereBetween('ot_date', [$request->datefr, $request->dateto])
                ->delete();
        }  
    }

    public function otSearch(Request $request){
        $param = [
            $request->dept,
            $request->datefr, 
            $request->dateto,
            $request->search.'%'
        ];

        $otsearch = DB::select("exec uspEmpOvertime_Search ?, ?, ?, ?", $param);
        return $otsearch;
    }







    public function test(){
        $test = DB::table('XPPL_PRODUCTION.dbo.tbl_MiningPit')
                    ->get();

        return $test;
    }


}


