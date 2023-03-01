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
                $request->type,
                $request->comm
            ];
            DB::insert("exec uspOvertimeAdd ?, ?, ?, ?, ?, ?, ?", $param);
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

    public function EditOT(Request $request){
        $editOT = DB::table('emp_overtime')
                    ->where('userid', $request->userid)
                    ->where('ot_date', $request->date)
                    ->first();
        return $editOT;
    }       

    public function AddOTRange(Request $request){
        foreach($request->list as $date){
            DB::table('emp_overtime')
                ->insert([
                    'userid' => $request->userid,
                    'ot_date' => $date,
                    'time_start' => $request->start,
                    'time_end' => $request->end,
                    'hrs' => $request->hrs,
                    'payment' => 'N',
                    'ot_type' => $request->type,
                    'scan_note' => 'Waiting scan',
                    'remarks' => $request->comm
                ]);
        }; 
    }

    public function UpdOTRange(Request $request){
        foreach($request->list as $date){
            DB::table('emp_overtime')
                ->where('userid', $request->userid)
                ->where('ot_date', $date)
                ->update([
                    'time_start' => $request->start,
                    'time_end' => $request->end,
                    'hrs' => $request->hrs,
                    'ot_type' => $request->type,
                    'remarks' => $request->comm
                ]);
        };   
    }

    public function DelOTRange(Request $request){
        foreach($request->list as $date){
            DB::table('emp_overtime')
                ->where('userid', $request->userid)
                ->where('ot_date', $date)
                ->delete();
        };
    }

    public function OTDetail(Request $request){
        $otdetail = DB::table('emp_overtime')
                        ->where('userid', $request->userid)
                        ->whereBetween('ot_date', [$request->datefr, $request->dateto])
                        ->get();
        return $otdetail;
    }

    public function RunOTScan(){
        DB::select('exec uspOvertimeUpdate');
    }




}


