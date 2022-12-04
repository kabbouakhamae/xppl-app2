<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpTransController extends Controller
{
    public function out(Request $request){

        $dept = $request->dept;
        $date = $request->date;

        $out = DB::select('exec uspEmpTranOutbound ?, ?', [$dept, $date]);
        return $out;
    }


    public function in(Request $request){
        $dept = $request->dept;
        $date = $request->date;

        $in = DB::select('exec uspEmpTranInbound ?, ?', [$dept, $date]);
        return $in;
    }

    public function pickup(Request $request){
        DB::table('emp_details')
            ->where('id', $request->id)
            ->update([
                'pickup' => $request->pickup
            ]);
    }

    public function dropoff(Request $request){
        DB::table('emp_details')
            ->where('id', $request->id)
            ->update([
                'dropoff' => $request->dropoff
            ]);
    }

}
