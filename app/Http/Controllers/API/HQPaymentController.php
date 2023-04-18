<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HQPaymentController extends Controller
{   
    public function PRPermission(){
        $userid = auth()->user()->userid;
        $permiss = DB::table('pr_permission')->where('userid', $userid)->first();
        return $permiss;
    }

    public function Lookup(){
        $lookup = DB::table("pr_lookup_code")->where('active','1')->get();
        return $lookup;
    }

    // public function CheckID(Request $request){
    //     $check = DB::table('pr_payment_requisitions')->where('id', $request->id);

    //     if ($check->count()){
    //         $success = false;
    //         $message = 'Duplicate ID please try again!';
    //     } else {
    //         $success = true;
    //         $message = "ID is available";
    //     }
    //     $response = [
    //         'success' => $success,
    //         'message' => $message
    //     ];
    //     return response()->json($response);
    // }

    public function AddPayment(Request $request){
        $check = DB::table('pr_payment_requisitions')
                    ->where('company', $request->company)
                    ->where('doc_dept', $request->doc_dept)
                    ->where('doc_no', $request->doc_no);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            $datetime = now("Asia/Bangkok")->toDateTimeString();
            $username = Str::lower(auth()->user()->username);

            $max = DB::table('pr_payment_requisitions')->max('pr_id');
            $nextid = $max + 1;
            $newid = substr(str_repeat(0, 8).$nextid, - 8);

            DB::table('pr_payment_requisitions')
                ->insert([
                    'pr_id' => $newid,
                    'company' => $request->company,
                    'doc_dept' => $request->doc_dept,
                    'doc_no' => $request->doc_no,
                    'date' => $request->date,
                    'doc_type' => $request->doc_type,
                    'descr' => $request->descr,
                    'amount' => $request->amount,
                    'currency' => $request->currency,
                    'submit_to' => $request->submit_to,
                    'requested_by' => $request->requested_by,
                    'created_at' => $datetime,
                    'created_by' => $username,
                    'approved_seq' => 1,
                    'status' => 'Pending'
                ]);

            $success = true;
            $message = "Insert completed!";
            $id = $newid;
        }
        $response = [
            'success' => $success,
            'message' => $message,
            'id' => $id
        ];
        return response()->json($response);
    }  

    public function AddFiles(Request $request){
        if ($request->hasFile('files')){
            foreach ($request->file('files') as $file){
                
                $filename = $request->id.'-'.$file->getClientOriginalName();
                $file->move(public_path('assets/HQPayment/'), $filename);
                DB::table('pr_payment_files')
                    ->insert([
                        'pr_id' => $request->id,
                        'file_name' => $filename
                    ]);                
            }  
        }
    }

    public function AddApproveName(Request $request){
        $n = 1;
        foreach($request->list as $lst){
            DB::table('pr_release_strategy')
            ->insert([
                'pr_id' => $request->id,
                'to_do' => $lst['to_do'],
                'approved_by' => $lst['approved_by'],
                'approved_status' => 'Pending',
                'approved_seq' => $n
            ]);  
            $n = $n + 1;           
        } 
    }

    public function UpdPayment(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);
        $d = $request->data;
        DB::table('pr_payment_requisitions')
            ->where('pr_id', $d['id'])
            ->update([
                'company' => $d['company'],
                'doc_dept' => $d['doc_dept'],
                'doc_no' => $d['doc_no'],
                'date' => $d['date'],
                'doc_type' => $d['doc_type'],
                'descr' => $d['descr'],
                'amount' => $d['amount'],
                'currency' => $d['currency'],
                'submit_to' => $d['submit_to'],
                'requested_by' => $d['requested_by'],
                'status' => $d['status'],
                'approved_seq' => $d['approved_seq'],
                'updated_at' => $datetime,
                'updated_by' => $username
        ]);

        foreach($request->appr as $lst){
            $check = DB::table('pr_release_strategy')
                        ->where('pr_id', $d['id'])
                        ->where('approved_by', $lst['approved_by']);

            if (!$check->count()){
                DB::table('pr_release_strategy')
                    ->insert([
                        'pr_id' => $d['id'],
                        'to_do' => $lst['to_do'],
                        'approved_by' => $lst['approved_by'],
                        'approved_status' => 'Pending',
                ]); 
            }       
        }; 

        $n = 1;
        foreach($request->appr as $lst){
            DB::table('pr_release_strategy')
                ->where('pr_id', $d['id'])
                ->where('to_do', $lst['to_do'])
                ->where('approved_by', $lst['approved_by'])
                ->update([
                    'approved_seq' => $n
            ]); 
            $n = $n + 1;
        }; 
    }

    public function GetAllData(Request $request){
        $param = [
            $request->comp,
            $request->dept,
            $request->year,
            $request->search.'%'
        ];
        $allData = DB::select('exec usp_pr_alldata ?, ?, ?, ?', $param);
        return $allData;
    }

    public function GetPending(Request $request){
        $param = [
            $request->appr,
            $request->fullname,
            $request->comp,
            $request->dept,
            $request->year,
            $request->search.'%'
        ];
        $allPend = DB::select('exec usp_pr_allpending ?, ?, ?, ?, ?, ?', $param);
        return $allPend;
        
    }

    public function GetApproved(Request $request){
        $param = [
            $request->comp,
            $request->dept,
            $request->year,
            $request->search.'%'
        ];
        $apprData = DB::select('exec usp_pr_allapproved ?, ?, ?, ?', $param);
        return $apprData;
    }

    public function GetRejected(Request $request){
        $param = [
            $request->comp,
            $request->dept,
            $request->year,
            $request->search.'%'
        ];
        $rejectData = DB::select('exec usp_pr_allrejected ?, ?, ?, ?', $param);
        return $rejectData;
    }

    public function GetAllFile(Request $request) {
        $param = [
            $request->comp,
            $request->dept,
            $request->year
        ];
        $allFile = DB::select('exec usp_pr_allfiles ?, ?, ?', $param);
        return $allFile;
    }

    public function GetApproveList(Request $request) {
        $param = [
            $request->comp,
            $request->dept,
            $request->year
        ];
        $allAppr = DB::select('exec usp_pr_approve_list ?, ?, ?', $param);
        return $allAppr;
    }

    public function DelFile($filename){
        DB::table('pr_payment_files')->where('file_name', $filename)->delete();
        if(file_exists(public_path('assets/HQPayment/'.$filename))){
            unlink(public_path('assets/HQPayment/'.$filename));
        }
    }

    public function Rejected(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        DB::table('pr_release_strategy')
            ->where('id', $request->appr_id)
            ->update([
                'approved_status' => 'Rejected',
                'approved_at' => $datetime,
                'approved_note' => $request->note
            ]);

        DB::table('pr_release_strategy')
            ->where('pr_id', $request->pr_id)
            ->where('approved_seq', '>', $request->appr_seq)
            ->update([
                'approved_status' => null,
            ]);

        DB::table('pr_payment_requisitions')
            ->where('pr_id', $request->pr_id)
            ->update([
                'status' => 'Rejected',
                'approved_seq' => 0
            ]);
    }

    public function Approved(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();

        if ($request->to_do == "Approval"){
            $status = "Approved";
        } else {
            $status = $request->to_do;
        }

        DB::table('pr_release_strategy')
            ->where('id', $request->appr_id)
            ->update([
                'approved_status' => $status,
                'approved_at' => $datetime,
                'approved_note' => $request->note
            ]);
            
        $mx = DB::table('pr_release_strategy')->where('pr_id', $request->pr_id)->max('approved_seq');
        if ($mx != $request->appr_seq){    
            DB::table('pr_payment_requisitions')
                ->where('pr_id', $request->pr_id)
                ->update([
                    'approved_seq' => $request->appr_seq + 1
                ]);
        } else {
            DB::table('pr_payment_requisitions')
                ->where('pr_id', $request->pr_id)
                ->update([
                    'status' => $status,
                    'approved_seq' => 0
                ]);
        }
    }

    public function DelPayment($id){
        $check = DB::table('pr_payment_files')->where('pr_id', $id);
        if ($check->count()){
            $files = DB::table('pr_payment_files')->where('pr_id', $id)->get();
            foreach ($files as $file){
                if(file_exists(public_path('assets/HQPayment/'.$file->file_name))){
                    unlink(public_path('assets/HQPayment/'.$file->file_name));
                }
            }  
        }
        DB::table('pr_payment_requisitions')->where('pr_id', $id)->delete();
    }

    public function GetPendingNotic(){
        $pendData = DB::table('pr_payment_requisitions')->where('status', 'Pending')->orderBy('date')->get();
        return $pendData;
    }   

    public function GetPendingNoticFile(){
        $pendFile = DB::select("select b.* from pr_payment_requisitions a, pr_payment_files b where a.id = b.pr_id and a.status = 'Pending'");
        return $pendFile;
    }
    
    public function NameList(){
        $name = DB::select("select fullname as value, fullname as label from dbo.utfEmpDetails() where status in('current', 'temporary') order by fullname");
        return $name;
    }

    public function DelApproveName(Request $request){
        DB::table('pr_release_strategy')->where('id', $request->id)->delete();

        $n = 1;
        foreach($request->appr as $lst){
            DB::table('pr_release_strategy')
                ->where('pr_id', $lst['pr_id'])
                ->where('to_do', $lst['to_do'])
                ->where('approved_by', $lst['approved_by'])
                ->update([
                    'approved_seq' => $n
            ]); 
            $n = $n + 1;
        }; 
    }

    public function UpdApproveName(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);

        DB::table('pr_release_strategy')
        ->where('id', $request->id)
        ->update([
            'to_do' => $request->to_do,
            'approved_by' => $request->approved_by,
            'approved_at' => $request->approved_at,
            'approved_note' => $request->approved_note,
            'approved_status' => $request->approved_status,
            'updated_at' => $datetime,
            'updated_by' => $username
        ]); 
    }


    



}
