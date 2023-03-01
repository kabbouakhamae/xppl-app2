<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FingerscanController extends Controller
{   
    // OLD FUNCTION
    // public function dateCol(Request $request){
    //     $m = explode('-',$request->date)[1];
    //     $y = explode('-',$request->date)[0];

    //     $col = DB::select("select 'c'+ convert(varchar, calendar, 112) as 'colid', left(convert(varchar, calendar, 3),2) as colname, format(calendar, 'MMMM-yyyy') as mont from calendar where year(calendar) = ? and month(calendar) = ? order by calendar", [$y, $m]);
    //     return $col;
    // }

    // public function summary(Request $request){
    //     $param = [
    //         $request->dept,
    //         $request->date,
    //         $request->search.'%'
    //     ];
        
    //     $summary = DB::select('exec uspEmpScanSummary ?, ?, ?', $param);
    //     return $summary;
    // }

    public function dateCol(Request $request){
        $param = [
            $request->datefr, 
            $request->dateto
        ];

        $col = DB::select("select 'c'+ convert(varchar, calendar, 112) as 'colid', left(convert(varchar, calendar, 3),2) as colname, format(calendar, 'MMMM-yyyy') as mont from calendar where calendar between ? and ? order by calendar", $param);
        return $col;
    }

    public function summary(Request $request){
        $param = [
            $request->dept,
            $request->datefr, 
            $request->dateto,
            $request->search.'%'
        ];

        $ros = DB::select("exec uspEmpScanSummary ?, ?, ?, ?", $param);
        return $ros;
    }



    public function detail(Request $request){
        $param = [
            $request->dept,
            $request->datefr,
            $request->dateto,
            $request->userid
        ];

        $detail = DB::select('exec uspEmpScanDetail ?, ?, ?, ?', $param);
        return $detail;
    }

    public function AcLog(Request $request){
        // $param = [
        //     $request->date,
        //     $request->userid
        // ];
        // $aclog = DB::select('exec uspEmpScanAcLog ?, ?', $param);

        $aclog = DB::table('emp_fingerprints as a')
                    ->join('emp_details as b', 'a.userid', 'b.userid')
                    ->join(DB::raw('(select max(id) as mxid, userid from emp_details group by userid) as c'),
                        function($qry){
                            $qry->on('b.id', 'c.mxid');
                        })
                    ->where('b.department', $request->dept)
                    ->whereBetween('a.scan_date', [$request->datefr, $request->dateto])
                    ->get('a.*');

        return $aclog;
    }

    public function header(Request $request){
        $param = [
            $request->datefr,
            $request->dateto
        ];
        
        $dead = DB::select('exec uspEmpScanSummaryHead ?, ?', $param);
        return $dead;
    }

    public function testProd()
    {
        $test = DB::select("select * from XPPL_PRODUCTION.dbo.tbl_MinedProduction WHERE YEAR(MinedDate) = '2022'");
        return $test;
    }
}
