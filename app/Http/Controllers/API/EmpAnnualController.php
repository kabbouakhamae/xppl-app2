<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class EmpAnnualController extends Controller
{
    public function data($id){
        $data = DB::table('emp_annual')
                    ->where('userid', $id)
                    ->get();
        
        return $data;
    }

    public function add(Request $request){
        $check = DB::table('emp_annual')
                    ->where('userid', $request->userid)
                    ->where('years', $request->years);
        
        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {

            DB::table('emp_annual')->insert([
                'userid' => $request->userid,
                'years' => $request->years,
                'remain' => $request->remain,
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
        $contract = DB::table('emp_annual')->find($id);
        return $contract;
    }

    public function update(Request $request){
        DB::table('emp_annual')
            ->where('id', $request->id)
            ->update([
                'years' => $request->years,
                'remain' => $request->remain,
                'remarks' => $request->remarks
            ]);
    }

    public function delete($id){
        DB::delete('delete from emp_annual where id = ?', [$id]);
    }

    public function paginateArray($data, $perPage = 24){
        $page = Paginator::resolveCurrentPage();
        $total = count($data);
        $results = array_slice($data, ($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
    }

    // public function info(Request $request){
    //     $userid = auth()->user()->userid;
    //     $year = $request->year;
    //     $name = $request->search.'%';

    //     $info = $this->paginateArray(
    //         DB::select('exec uspEmpAnnualLeave ?, ?, ?', [$userid, $year, $name])
    //     );

    //     return $info;
    // }

    public function info(Request $request){

        $param = [
            $request->dept,
            $request->year,
            $request->search.'%'
        ];

        $info = DB::select('exec uspEmpAnnualLeave ?, ?, ?', $param);
        return $info;
    }

    // public function info(Request $request){
    //     $year = $request->year;
    //     $name = $request->search.'%';

    //     $info = DB::table('emp_annual as a')
    //                 ->join('emp_names as b', 'a.userid', 'b.id')
    //                 ->where('a.years', $year)
    //                 ->where('b.name', 'like', $name)
    //                 ->orderBy('b.name')
    //                 ->select('*')
    //                 ->paginate(20);

    //     return $info;
    // }

}
