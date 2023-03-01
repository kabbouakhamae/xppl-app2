<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;    // ໂຕກວດຊອບ ການເຂົ້າລະບົບ
use Illuminate\Support\Facades\Hash;    // ໂຕຊ່ວຍ ເຂົ້າລະຫັດ ລະຫັດຜ່ານ
use App\Models\User;                    // import Model User ເຂົ້າມາໃຊ້
use Session;                            // import Session ເພື່ອທຳການ ອອກຈາກລະບົບ

class UserController extends Controller
{
    //
    public function signUp(Request $request){
        try{
            $userCheck1 = User::where('userid', $request->userid);
            $userCheck2 = User::where('username', $request->username);

            if($userCheck1->count()){  /// ກວດຊອບອີເມວໃນຖານຂໍ້ມູນ
                
                $success = false;
                $message = "This employee already sign up!";
            
            } elseif ($userCheck2->count()) {
                
                $success = false;
                $message = "This username is not available!";
    
            } else {  // ບໍ່ມີອີເມວ
                $user = new User();
                $user->userid = $request->userid;
                $user->username = $request->username;
                $user->password = Hash::make($request->password);
                $user->save();

                $success = true;
                $message = "Sign up completed!";
            }
        } catch (\Illnminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }


    public function signIn(Request $request){
        $check_name_pass_login = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if(Auth::attempt($check_name_pass_login)){
                $success = true;
                $message = "Sign in completed!";
        } else {
                $success = false;
                $message = "Incorrect name or password!";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];
        return response()->json($response);
    }

    public function signOut(){
        Session::flush();  // ທຳລາຍ Session ເພື່ອອອກຈາກລະບົບ
        $success = true;
        $message = "Sign out completed!";

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function profile(){
        $id = auth()->user()->userid;
        $profile = DB::table('emp_names as a')
                        ->join('users as b', 'a.id', 'b.userid')
                        ->join('emp_details as c', 'a.id', 'c.userid')
                        ->join(DB::raw('(select max(id) as mxid, userid from emp_details group by userid) as d'),
                            function($query){
                                $query->on('c.id', 'd.mxid');
                            })
                        ->where('a.id', $id)
                        ->select('a.gender', 'a.name', 'a.surname', 'a.photo', 'c.department', 'b.username')
                        ->first();
        return $profile;
    }

    public function permiss(){
        $userid = auth()->user()->userid;
        $permiss = DB::table('permission')->where('userid', $userid)->first();
        
        return $permiss;
    }
    
    // public function username(Request $request){
    //     $name = $request->search.'%';
    //     $userList = DB::table('emp_names as a')
    //                     ->join('permission as b', 'a.id', 'b.userid')
    //                     ->where('a.name', 'like', $name)
    //                     ->select('a.id', 'a.name', 'a.surname')
    //                     ->orderBy('a.name')
    //                     ->get();

    //     return $userList;
    // }

    public function username(){
        $userList = DB::table('emp_names as a')
                        ->join('permission as b', 'a.id', 'b.userid')
                        ->select(DB::raw("a.id as value, a.name +' '+ a.surname as label"))
                        ->orderBy('a.name')
                        ->get();

        return $userList;
    }
    

    public function permissEdit(Request $request){
        $permissData = DB::table('permission')->where('userid', $request->id)->first();

        return $permissData;
    }

    public function permissUpdate(Request $request){   
        DB::table('permission')
            ->where('id', $request->id)
            ->update([
                'permiss' => $request->permiss,
                'safety' => $request->safety,
                'lk_add' => $request->lkAdd,
                'lk_edit' => $request->lkEdit,
                'lk_del' => $request->lkDel,
                'emp_all' => $request->empAll,
                'emp_add' => $request->empAdd,
                'emp_edit' => $request->empEdit,
                'emp_del' => $request->empDel,
                'detail_del' => $request->detailDel,
                'cont_del' => $request->contDel,
                'bank_del' => $request->bankDel,
                'card_del' => $request->cardDel,
                'al_del' => $request->alDel,
                'file_del' => $request->fileDel,
                'fuel' => $request->fuel,
                'fuel_all' => $request->fuelAll,
                'ros_all' => $request->rosAll,
                'ros_edit' => $request->rosEdit
            ]);
    }

    public function Language(Request $request){
        $userid = auth()->user()->userid;
        DB::table('permission')
            ->where('userid', $userid)
            ->update([
                'lang' => $request->lang
            ]);
    }

}
