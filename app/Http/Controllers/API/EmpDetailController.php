<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpDetailController extends Controller
{
    public function data($id){
        $data = DB::select('select * from emp_details where userid = ?', [$id]);
        return $data;
    }

    public function add(Request $request){
        $check = DB::table('emp_details')
                    ->where('userid', $request->userid)
                    ->where('startdate', $request->startdate);
        
        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {

            DB::table('emp_details')->insert([
                'userid' => $request->userid,
                'startdate' => $request->startdate,
                'enddate' => $request->enddate,
                'position' => $request->position,
                'status' => $request->status,
                'site' => $request->site,
                'department' => $request->dept,
                'section' => $request->section,
                'crew' => $request->crew,
                'empid' => $request->empid,
                'scanid' => $request->scanid,
                'foodid' => $request->foodid,
                'roster' => $request->roster,
                'scantimes' => $request->scantimes,
                'working_hrs' => $request->hours,
                'levels' => $request->levels,
                'contract' => $request->contract,
                'remarks' => $request->remarks
            ]);

            $success = true;
            $message = "Insert completed!";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];
        return response()->json($response);
    }

    public function edit($id){
        $detail = DB::table('emp_details')->find($id);
        return $detail;
    }

    public function update(Request $request){
        DB::table('emp_details')
            ->where('id', $request->id)
            ->update([
                'startdate' => $request->startdate,
                'enddate' => $request->enddate,
                'position' => $request->position,
                'status' => $request->status,
                'site' => $request->site,
                'department' => $request->dept,
                'section' => $request->section,
                'crew' => $request->crew,
                'empid' => $request->empid,
                'scanid' => $request->scanid,
                'foodid' => $request->foodid,
                'roster' => $request->roster,
                'scantimes' => $request->scantimes,
                'working_hrs' => $request->hours,
                'levels' => $request->levels,
                'contract' => $request->contract,
                'remarks' => $request->remarks
            ]);
        
    }

    public function delete($id){
        DB::delete('delete from emp_details where id = ?', [$id]);
    }
}
