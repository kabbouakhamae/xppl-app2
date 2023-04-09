<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HQPaymentController extends Controller
{
    public function Lookup(){
        $lookup = DB::table("HQ_lookup_code")->where('active','1')->get();
        return $lookup;
    }

    public function CheckID(Request $request){
        $check = DB::table('hq_payment_requisitions')
                    ->where('id', $request->id);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate ID please try again!';
        } else {
            $success = true;
            $message = "ID is available";
        }
        $response = [
            'success' => $success,
            'message' => $message
        ];
        return response()->json($response);
    }

    public function AddPayment(Request $request){
        $check = DB::table('hq_payment_requisitions')
                    ->where('company', $request->company)
                    ->where('dept', $request->dept)
                    ->where('doc_no', $request->doc_no);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            $datetime = now("Asia/Bangkok")->toDateTimeString();
            $username = Str::lower(auth()->user()->username);

            DB::table('hq_payment_requisitions')
                ->insert([
                    'id' => $request->id,
                    'company' => $request->company,
                    'dept' => $request->dept,
                    'doc_no' => $request->doc_no,
                    'pr_date' => $request->date,
                    'docs_type' => $request->docs_type,
                    'descr' => $request->descr,
                    'amount' => $request->amount,
                    'currency' => $request->currency,
                    'submit_to' => $request->submit_to,
                    'status' => 'Pending',
                    'created_at' => $datetime,
                    'created_by' => $username

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

    public function UpdPayment(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);

        DB::table('hq_payment_requisitions')
            ->where('id', $request->id)
            ->update([
                'company' => $request->company,
                'doc_no' => $request->doc_no,
                'pr_date' => $request->date,
                'docs_type' => $request->docs_type,
                'descr' => $request->descr,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'submit_to' => $request->submit_to,
                'updated_at' => $datetime,
                'updated_by' => $username
            ]);

    }

    public function AddFiles(Request $request){
        if ($request->hasFile('files')){
            foreach ($request->file('files') as $file){
                
                $filename = $request->id.'-'.$file->getClientOriginalName();
                $file->move(public_path('assets/HQPayment/'), $filename);
                DB::table('hq_payment_files')->insert([
                    'pr_id' => $request->id,
                    'file_name' => $filename
                ]);                
            }  
        }
    }

    public function GetAllData(Request $request){
        if ($request->view == 1) {
            $param = [
                $request->year,
                $request->search.'%', //company
                '%'.$request->search.'%', //doc no
                '%'.$request->search.'%', //doc type
                '%'.$request->search.'%', //descr
                $request->search.'%', //submit to
                $request->search.'%' //status
            ];
            $all = DB::select("select * from hq_payment_requisitions where year(pr_date) = ? and (company like ? or doc_no like ? or docs_type like ? or descr like ? or submit_to like ? or status like ?) order by pr_date", $param);
        } else {
            $param = [
                $request->year,
                $request->dept,
                $request->search.'%', //company
                '%'.$request->search.'%', //doc no
                '%'.$request->search.'%', //doc type
                '%'.$request->search.'%', //descr
                $request->search.'%', //submit to
                $request->search.'%' //status
            ];
            $all = DB::select("select * from hq_payment_requisitions where year(pr_date) = ? and dept = ? and (company like ? or doc_no like ? or docs_type like ? or descr like ? or submit_to like ? or status like ?) order by pr_date", $param);
        }
        return $all;
    }

    public function GetPending(Request $request){
        if ($request->view == 1) {
            $param = [
                $request->year,
                $request->search.'%', //company
                '%'.$request->search.'%', //doc no
                '%'.$request->search.'%', //doc type
                '%'.$request->search.'%', //descr
                $request->search.'%', //submit to
            ];
            $all = DB::select("select * from hq_payment_requisitions where status = 'pending' and year(pr_date) = ? and (company like ? or doc_no like ? or docs_type like ? or descr like ? or submit_to like ?) order by pr_date", $param);
        } else {
            $param = [
                $request->year,
                $request->dept,
                $request->search.'%', //company
                '%'.$request->search.'%', //doc no
                '%'.$request->search.'%', //doc type
                '%'.$request->search.'%', //descr
                $request->search.'%', //submit to
            ];
            $all = DB::select("select * from hq_payment_requisitions where status = 'pending' and year(pr_date) = ? and dept = ? and (company like ? or doc_no like ? or docs_type like ? or descr like ? or submit_to like ?) order by pr_date", $param);
        }
        return $all;
    }

    public function GetApprove(Request $request){
        if ($request->view == 1) {
            $param = [
                $request->year,
                $request->search.'%', //company
                '%'.$request->search.'%', //doc no
                '%'.$request->search.'%', //doc type
                '%'.$request->search.'%', //descr
                $request->search.'%', //submit to
            ];
            $all = DB::select("select * from hq_payment_requisitions where status in('approved','verified') and year(pr_date) = ? and (company like ? or doc_no like ? or docs_type like ? or descr like ? or submit_to like ?) order by pr_date", $param);
        } else {
            $param = [
                $request->year,
                $request->dept,
                $request->search.'%', //company
                '%'.$request->search.'%', //doc no
                '%'.$request->search.'%', //doc type
                '%'.$request->search.'%', //descr
                $request->search.'%', //submit to
            ];
            $all = DB::select("select * from hq_payment_requisitions where status in('approved','verified') and year(pr_date) = ? and dept = ? and (company like ? or doc_no like ? or docs_type like ? or descr like ? or submit_to like ?) order by pr_date", $param);
        }
        return $all;
    }

    public function GetReject(Request $request){
        if ($request->view == 1) {
            $param = [
                $request->year,
                $request->search.'%', //company
                '%'.$request->search.'%', //doc no
                '%'.$request->search.'%', //doc type
                '%'.$request->search.'%', //descr
                $request->search.'%', //submit to
            ];
            $all = DB::select("select * from hq_payment_requisitions where status = 'rejected' and year(pr_date) = ? and (company like ? or doc_no like ? or docs_type like ? or descr like ? or submit_to like ?) order by pr_date", $param);
        } else {
            $param = [
                $request->year,
                $request->dept,
                $request->search.'%', //company
                '%'.$request->search.'%', //doc no
                '%'.$request->search.'%', //doc type
                '%'.$request->search.'%', //descr
                $request->search.'%', //submit to
            ];
            $all = DB::select("select * from hq_payment_requisitions where status = 'rejected' and year(pr_date) = ? and dept = ? and (company like ? or doc_no like ? or docs_type like ? or descr like ? or submit_to like ?) order by pr_date", $param);
        }
        return $all;
    }

    public function GetAllFile(Request $request) {
        if ($request->view == 1) {
            $file = DB::select("select b.* from hq_payment_requisitions a, hq_payment_files b where a.id = b.pr_id and year(pr_date) = ? ", [$request->year]);
        } else {
            $file = DB::select("select b.* from hq_payment_requisitions a, hq_payment_files b where a.id = b.pr_id and year(pr_date) = ? and a.dept = ? ", [$request->year, $request->dept]);
        }
        return $file;
    }

    public function DelFile($filename){
        DB::table('hq_payment_files')
            ->where('file_name', $filename)
            ->delete();

        if(file_exists(public_path('assets/HQPayment/'.$filename))){
            unlink(public_path('assets/HQPayment/'.$filename));
        }
    }

    public function Rejected(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);

        DB::table('hq_payment_requisitions')
            ->where('id', $request->id)
            ->update([
                'status' => 'Rejected',
                'approved_at' => $datetime,
                'approved_by' => $username,
                'approved_note' => $request->note
            ]);

    }

    public function Approved(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);

        if ($request->status == "Approval") {
            $status = "Approved";
          } else {
            $status = $request->status;
          }
        DB::table('hq_payment_requisitions')
            ->where('id', $request->id)
            ->update([
                'status' => $status,
                'approved_at' => $datetime,
                'approved_by' => $username,
                'approved_note' => $request->note
            ]);

    }

    public function DelPayment($id){
        $check = DB::table('hq_payment_files')->where('pr_id', $id);
        if ($check->count()){
            $files = DB::table('hq_payment_files')->where('pr_id', $id)->get();
            
            foreach ($files as $file){
                if(file_exists(public_path('assets/HQPayment/'.$file->file_name))){
                    unlink(public_path('assets/HQPayment/'.$file->file_name));
                }
            }  
        }
        DB::table('hq_payment_requisitions')->where('id', $id)->delete();
    }

    public function GetPendingNotic(){
        $pendData = DB::table('hq_payment_requisitions')->where('status', 'Pending')->orderBy('pr_date')->get();
        return $pendData;
    }   

    public function GetPendingNoticFile(){
        $pendFile = DB::select("select b.* from hq_payment_requisitions a, hq_payment_files b where a.id = b.pr_id and a.status = 'Pending'");
        return $pendFile;
    }   


}
