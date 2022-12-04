<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function countcountry()
    {
        $countCountry = DB::select("select isnull(a.country, 'Undefine') as country, count(distinct a.id) as cnt from emp_names as a, emp_details as b where a.id = b.userid and b.status = 'current' group by a.country");
        return $countCountry;
    }

    public function allemp()
    {
        $allemp = DB::select("select count(distinct a.id) as cnt from emp_names as a, emp_details as b where a.id = b.userid and b.status = 'current'");
        return $allemp;
    }

    public function countdept()
    {
        $countdept = DB::select("select department, count(distinct userid) as cnt from emp_details where status = 'current' group by department   ");
        return $countdept;
    }

    public function onsite()
    {
        $date =  date('Y-m-d');
        $onsite = DB::select("select count(b.userid) as onsite from dbo.utfEmpDetails() a, emp_rosters b where a.userid = b.userid and a.status = 'current' and b.ref_code in('W','WN','A/2','W/2','S/2') and rdate = ?", [$date]);

        return $onsite;
    }

    public function offsite()
    {
        $date =  date('Y-m-d');
        $onsite = DB::select("select count(b.userid) as offsite from dbo.utfEmpDetails() a, emp_rosters b where a.userid = b.userid and a.status = 'current' and b.ref_code not in('W','WN','A/2','W/2','S/2') and rdate = ?", [$date]);

        return $onsite;
    }

    public function users()
    {
        $users = DB::select("select count(userid) as users from users");
        return $users;
    }

    public function manpower(Request $request)
    {   
        $param = [
            $request->dept,
            $request->date
        ];

        $manpower = DB::select('exec usp_ExEmpManpower ?, ?', $param);
        return $manpower;
    }
}
