<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpCardController extends Controller
{
    public function data($id){
        $data = DB::table('emp_cards')
                    ->where('userid', $id)
                    ->get();
        
        return $data;
    }

    public function add(Request $request){
        $check = DB::table('emp_cards')
                    ->where('userid', $request->userid)
                    ->where('cardtype', $request->cardtype);
        
        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {

            DB::table('emp_cards')->insert([
                'userid' => $request->userid,
                'cardtype' => $request->cardtype,
                'cardid' => $request->cardid,
                'expiredate' => $request->expire,
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
        $contract = DB::table('emp_cards')->find($id);
        return $contract;
    }

    public function update(Request $request){
        DB::table('emp_cards')
            ->where('id', $request->id)
            ->update([
                'cardtype' => $request->cardtype,
                'cardid' => $request->cardid,
                'expiredate' => $request->expire,
                'remarks' => $request->remarks
            ]);
    }

    public function delete($id){
        DB::delete('delete from emp_cards where id = ?', [$id]);
    }

}
