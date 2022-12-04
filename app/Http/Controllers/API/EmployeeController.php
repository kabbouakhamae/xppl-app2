<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeController extends Controller{

    public function paginateArray($data, $perPage = 10){
        $page = Paginator::resolveCurrentPage();
        $total = count($data);
        $results = array_slice($data, ($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
    }

    public function index(Request $request){
        $name = $request->search.'%';
        $namelao = $request->search.'%';
        $dept = $request->search.'%';

        $userid = auth()->user()->userid;
        $data = DB::table('permission')->where('userid', $userid)->first(); // Employee view all or view by dept

        if ($request->permiss == 1){
            $empList = DB::table('emp_names as a')
                            ->join('emp_details as b', 'a.id', 'b.userid')
                            ->join(DB::raw('(select max(id) as mxid, userid from emp_details group by userid) as c'),
                                function($join){
                                    $join->on('b.id', 'c.mxid');
                                })
                            ->orWhere('a.name', 'like', $name)
                            ->orWhere('a.namelao', 'like', $namelao)
                            ->orWhere('b.department', 'like', $dept)
                            ->select('a.id', 'a.gender', 'a.name', 'a.surname', 'a.namelao', 'a.surnamelao', 'a.country', 'a.province', 'a.phone', 'b.position', 'b.department', 'b.section', 'b.startdate', 'b.status', 'b.empid', 'b.scanid', 'b.foodid', 'b.roster', 'b.scantimes', 'b.levels', 'a.photo')
                            ->orderBy('a.name')
                            ->paginate(10);

        } else {
            // Find by current dept
            $deptData = DB::table('emp_details as a')
                            ->select('a.department')
                            ->join(DB::raw('(select max(id) as mxid, userid from emp_details group by userid) as b'),
                                function($join){
                                    $join->on('a.id', 'b.mxid');
                                })
                            ->where('a.userid', $userid)
                            ->first();
            $dept = $deptData->department;

            $empList = DB::table('emp_names as a')
                            ->join('emp_details as b', 'a.id', 'b.userid')
                            ->join(DB::raw("(select max(id) as mxid, userid, department from emp_details where department ='{$request->dept}' group by userid, department) as c"),
                                function($join){
                                    $join->on('b.id', 'c.mxid');
                                })
                            ->orWhere('a.name', 'like', $name)
                            ->orWhere('a.namelao', 'like', $namelao)
                            ->select('a.id', 'a.gender', 'a.name', 'a.surname', 'a.namelao', 'a.surnamelao', 'a.country', 'a.province', 'a.phone', 'b.position', 'b.department', 'b.section', 'b.startdate', 'b.status', 'b.empid', 'b.scanid', 'b.foodid', 'b.roster', 'b.scantimes', 'b.levels', 'a.photo')
                            ->orderBy('a.name')
                            ->paginate(10);
        }

        return $empList;
    }

    // public function index(Request $request){
    
    //     if ($request->permiss == 1){
    //         $param = [
    //             $request->search.'%',
    //             $request->search.'%',
    //             $request->search.'%'
    //         ];
    //         $empList = $this->paginateArray(
    //             DB::select("select * from emp_names as a, dbo.utfEmpDetails() as b where a.id = b.userid and (a.name like ? or a.namelao like ? or b.department like ?) order by a.name ", $param)
    //         );
    //         return $empList;
    //     }

    // }


    public function add(Request $request){
        try{
            $checkName = DB::table('emp_names')
                        ->where('name', $request->name)
                        ->Where('surname', $request->surname); 
            if ($checkName->count()){
                $success = false;
                $message = "This name already in database!";
            } else {

                $maxUserid = DB::table('emp_names')->max('id');
                $newUserid = $maxUserid + 1;

                if($request->hasFile('photo')){
                    $filename = $newUserid.'-'.time().'.'.$request->photo->getClientOriginalExtension();
                    $request->photo->move('assets/img/profile/', $filename);
                } else {
                    $filename = null;
                }

                // Insert to employee names
                DB::table('emp_names')->insert([
                    'id' => $newUserid,
                    'gender' => $request->gender,
                    'name' => $request->name,
                    'surname' => $request->surname,
                    'namelao' => $request->namelao,
                    'surnamelao' => $request->surnamelao,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'birthday' => $request->birthday,
                    'country' => $request->country,
                    'province' => $request->province,
                    'district' => $request->district,
                    'village' => $request->village,
                    'photo' => $filename
                ]);
                
                // Insert to employee details
                DB::table('emp_details')->insert([
                    'userid' => $newUserid,
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
                    'contract' => $request->contract
                ]);

                $success = true;
                $message = "Insert completed!";
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

    public function update(Request $request){
        if($request->hasFile('photo')){
            $data = DB::table('emp_names')->find($request->id);

            if($data->photo != '' && $data->photo != null){
                if(file_exists('assets/img/profile/'.$data->photo)){
                    unlink('assets/img/profile/'.$data->photo);
                }
            }

            $filename = $request->id.'-'.time().'.'.$request->photo->getClientOriginalExtension();
            $request->photo->move('assets/img/profile/', $filename);
        } else {
            $filename = 'no pic';
        }
            $param = [
                $request->id,
                $request->gender,
                $request->name,
                $request->surname,
                $request->phone,
                $request->namelao,
                $request->surnamelao,
                $request->birthday,
                $request->email,
                $request->country,
                $request->province,
                $request->district,
                $request->village,
                $filename
            ];
            DB::update("exec uspEmpUpdate ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?", $param);
    }

    public function edit($id){
        $edit = DB::table('emp_names')->find($id);
        return $edit;            
    }

    public function preview($id){
        $empPrev = DB::select('exec uspEmpPreview ?', [$id]);
        return $empPrev;
    }

    public function delete($id){
        $data = DB::table('emp_names')->find($id);

        if($data->photo != '' && $data->photo != null){
            if(file_exists('assets/img/profile/'.$data->photo)){
                unlink('assets/img/profile/'.$data->photo);
            }
        }
        DB::delete('delete from emp_names where id = ?', [$id]);
    }

    public function currentEmp(Request $request){
        $curEmp = DB::select("SELECT a.Gender, a.Name, a.Surname, a.NameLao, a.SurnameLao, a.Phone, b.Position, a.Birthday, a.Country, a.Province, a.District, a.Village, b.StartDate, b.Status, b.Site, b.Department, b.Section, b.EmpID, b.Scanid, b.Foodid, b.Roster, b.Scantimes, b.Levels, b.Contract, c.ContactPerson, d.PersonalCards, b.Remarks
                              FROM dbo.emp_names a 
                                    LEFT OUTER JOIN dbo.utfEmpDetails() b ON b.userid = a.id
                                    LEFT OUTER JOIN dbo.utf_EmpContactPerson() c ON c.UserID = a.id
                                    LEFT OUTER JOIN dbo.utf_EmpPersonalCard() d ON d.UserID = a.id
                              WHERE 
                                    b.status = 'current'
                                    AND b.department = ? 
                              ORDER BY a.name", [$request->dept]);

        return $curEmp;
    }

    public function exEmp(Request $request){
        $exEmp = DB::select("SELECT a.Gender, a.Name, a.Surname, a.NameLao, a.SurnameLao, a.Phone, b.Position, a.Birthday, a.Country, a.Province, a.District, a.Village, b.StartDate, b.Status, b.Site, b.Department, b.Section, b.EmpID, b.Scanid, b.Foodid, b.Roster, b.Scantimes, b.Levels, b.Contract, c.ContactPerson, d.PersonalCards, b.Remarks
                              FROM dbo.emp_names a 
                                    LEFT OUTER JOIN dbo.utfEmpDetails() b ON b.userid = a.id
                                    LEFT OUTER JOIN dbo.utf_EmpContactPerson() c ON c.UserID = a.id
                                    LEFT OUTER JOIN dbo.utf_EmpPersonalCard() d ON d.UserID = a.id
                              WHERE 
                                    b.status not in('current')
                                    AND b.department = ? 
                              ORDER BY a.name", [$request->dept]);

        return $exEmp;
    }


}
