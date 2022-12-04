<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmpRosterController extends Controller
{
    public function rosCode(){
        $rosCode = DB::select('select * from lookup_roster where used = 1 order by ref_code, rcode');
        return $rosCode;
    }

    public function rosCodeAdd(Request $request){
        $check = DB::table('lookup_roster')
                    ->where('rcode', $request->rcode);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            DB::table('lookup_roster')
                ->insert([
                    'rcode' => $request->rcode,
                    'ref_code' => $request->ref_code,
                    'descr_eng' => $request->descr_eng,
                    'descr_lao' => $request->descr_lao,
                    'working_time' => $request->working_time,
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

    public function rosCodeEdit($id){
        $result = DB::table('lookup_roster')->find($id);
        return $result;
    }

    public function rosCodeUpd(Request $request){
        DB::table('lookup_roster')
            ->where('id', $request->id)
            ->update([
                'rcode' => $request->rcode,
                'ref_code' => $request->ref_code,
                'descr_eng' => $request->descr_eng,
                'descr_lao' => $request->descr_lao,
                'working_time' => $request->working_time,
                'used' => $request->used
            ]);
    }

    public function rosCodeDel($id){
        DB::delete('delete from lookup_roster where id = ?', [$id]);
    }

    public function dateCol(Request $request){
        $param = [
            $request->datefr, 
            $request->dateto
        ];

        $col = DB::select("select 'c'+ convert(varchar, calendar, 112) as 'colid', left(convert(varchar, calendar, 3),2) as colname, format(calendar, 'MMMM-yyyy') as mont from calendar where calendar between ? and ? order by calendar", $param);
        return $col;
    }

    public function roster(Request $request){
        $param = [
            $request->dept,
            $request->datefr, 
            $request->dateto,
            $request->search.'%'
        ];

        $ros = DB::select("exec uspEmpRoster ?, ?, ?, ?", $param);
        return $ros;
    }

    public function rcode(){
        $rcode = DB::select('select rcode as value, rcode as label from lookup_roster where used = 1 order by rcode');
        return $rcode;
    }

    public function updRosDate(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);

        if ($request->rtype == 'A'){
            DB::table('emp_rosters')
                ->where('userid', $request->userid)
                ->where('rdate', $request->rdatefr)
                ->update([
                    'acode' => $request->rcode,
                    'anote' => $request->comment,
                    'updated_at' => $datetime,
                    'updated_by' => $username
                ]);
        } else {
            DB::table('emp_rosters')
                ->where('userid', $request->userid)
                ->where('rdate', $request->rdatefr)
                ->update([
                    'pcode' => $request->rcode,
                    'pnote' => $request->comment
                ]);
        }
    }

    public function updRosRange(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);

        if ($request->rtype == 'A'){
            DB::table('emp_rosters')
                ->where('userid', $request->userid)
                ->whereBetween('rdate', [$request->rdatefr, $request->rdateto])
                ->update([
                    'acode' => $request->rcode,
                    'anote' => $request->comment,
                    'updated_at' => $datetime,
                    'updated_by' => $username
                ]);
        } else {
            DB::table('emp_rosters')
                ->where('userid', $request->userid)
                ->whereBetween('rdate', [$request->rdatefr, $request->rdateto])
                ->update([
                    'pcode' => $request->rcode,
                    'pnote' => $request->comment
                ]);
        }   
    }

    public function rosDetail(Request $request){
        $param = [
            $request->dept,
            $request->datefr,
            $request->dateto,
            $request->userid
        ];

        $rosDet = DB::select("select b.* from dbo.utfEmpDetails() a, emp_rosters b where a.userid = b.userid and a.department = ? and b.rdate between ? and ? and b.userid = ? order by b.rdate", $param);
        return $rosDet;
    }

    public function empList(Request $request){
        $emplist = DB::select("select a.id as value, case when a.surname is null then name else name +' '+ a.surname end as label from emp_names a, 
                               dbo.utfEmpDetails() b where a.id = b.userid and b.status = 'current' and b.department = ? order by a.name", [$request->dept]);
        return $emplist;
    }

    public function addRos(Request $request){
        foreach($request->list as $userid) {
            $rosdata = DB::table('emp_details as a')
                        ->select('a.roster')
                        ->join(DB::raw('(select max(id) as mxid, userid from emp_details group by userid) as b'),
                            function($join){
                                    $join->on('a.id', 'b.mxid');
                                })
                        ->where('a.userid', $userid)
                        ->first();
            
            $roster = $rosdata->roster;
            $param = [
                $request->date,
                $userid,
                $roster
            ];
            
            DB::select("exec uspEmpRosterAdd ?, ?, ?", $param);
        }  
    }

    public function delRos(Request $request){
        foreach($request->list as $userid) {
            
            DB::table('emp_rosters')
                ->where('userid', $userid)
                ->where('rdate', '>=', $request->date)
                ->delete();
            
        }  
    }

    public function leaveInfo(Request $request){
        $param = [
            $request->dept,
            $request->year,
            $request->search.'%'
        ];

        $leaveInfo = DB::select("exec uspEmpDayLeaveInfo ?, ?, ?", $param);
        return $leaveInfo;
    }

    public function leaveHeader(){
        $header = DB::select("select a.rcode, a.descr_eng from lookup_roster a,	(select distinct case when ref_code like '%/%' then left(ref_code,1) when ref_code ='WN' then 'W' else ref_code end as ref from lookup_roster) as b where a.rcode = b.ref and a.used = 1");
        return $header;
    }

    public function leaveDetail(Request $request){
        $param = [
            $request->userid,
            $request->year,
            $request->code,
            $request->code.'/2',
        ];

        $detail = DB::select("select rdate, pcode, acode, ref_code, anote from emp_rosters where userid = ? and year(rdate) = ? and (ref_code = ? or ref_code = ?) order by rdate", $param);
        return $detail;
    }

    public function addCrew(Request $request){
        DB::table('emp_details')
            ->where('id', $request->id)
            ->update([
                'crew' => $request->crew
            ]);
    }

    public function addSection(Request $request){
        DB::table('emp_details')
            ->where('id', $request->id)
            ->update([
                'section' => $request->section
            ]);
    }

    public function updShiftWork(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);

        foreach($request->list as $userid) {

            DB::table('emp_rosters')
                ->where('userid', $userid)
                ->whereBetween('rdate', [$request->datefr, $request->dateto])
                ->update([
                    'acode' => $request->code,
                    'anote' => $request->comment,
                    'updated_at' => $datetime,
                    'updated_by' => $username
                ]);
        }
    }

    public function roster2(Request $request){
        $param = [
            $request->datefr,
            $request->dateto,
            $request->userid,
            $request->dept
        ];

        $ros2 = DB::select('exec uspEmpRoster2 ?, ?, ?, ?', $param);
        return $ros2;
    }

    public function breakInfo(Request $request){
        $break = DB::select('exec uspEmpComingBreak ?', [$request->dept]);
        return $break;
    }

}
