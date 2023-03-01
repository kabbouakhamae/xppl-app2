<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LookupController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\EmpDetailController;
use App\Http\Controllers\API\EmpContController;
use App\Http\Controllers\API\EmpBankController;
use App\Http\Controllers\API\EmpCardController;
use App\Http\Controllers\API\EmpAnnualController;
use App\Http\Controllers\API\EmpFileController;
use App\Http\Controllers\API\EmpTransController;
use App\Http\Controllers\API\FuelConsController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\LookupSettController;
use App\Http\Controllers\API\EmpRosterController;
use App\Http\Controllers\API\FingerscanController;
use App\Http\Controllers\API\SafetyController;
use App\Http\Controllers\API\OvertimeController;
use App\Http\Controllers\API\ProductionController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Controllers\API\ClinicController;
use App\Http\Controllers\API\TestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('signUp', [UserController::class, 'signUp']);
Route::post('signIn', [UserController::class, 'signIn']);
Route::post('signOut', [UserController::class, 'signOut']);
Route::get('profile', [UserController::class, 'profile']);
Route::get('permiss', [UserController::class, 'permiss']);
Route::get('username', [UserController::class, 'username']);
Route::post('permissUpdate', [UserController::class, 'permissUpdate']);
Route::post('permissEdit', [UserController::class, 'permissEdit']);
Route::get('language', [UserController::class, 'Language']);

Route::group(['prefix' => 'permission', 'middlewar' => 'auth:santum'], function(){
    Route::get('/home', [PermissionController::class, 'home']);
    Route::get('/admin', [PermissionController::class, 'admin']);

    Route::post('/addFile', [PermissionController::class, 'addFile']);
    Route::get('/files', [PermissionController::class, 'files']);
    Route::get('/download/{file}', [PermissionController::class, 'download']);
});


Route::group(['prefix' => 'employee', 'middlewar' => 'auth:santum'], function(){
    Route::get('/', [EmployeeController::class, 'index']);
    Route::post('/add', [EmployeeController::class, 'add']);
    Route::post('/update', [EmployeeController::class, 'update']);
    Route::post('/edit/{id}', [EmployeeController::class, 'edit']);
    Route::post('/delete/{id}', [EmployeeController::class, 'delete']);
    Route::post('/preview/{id}', [EmployeeController::class, 'preview']);
    Route::get('/currentemp', [EmployeeController::class, 'currentEmp']);
    Route::get('/exemp', [EmployeeController::class, 'exEmp']);
});

Route::group(['prefix' => 'detail', 'middlewar' => 'auth:santum'], function(){
    Route::post('/data/{id}', [EmpDetailController::class, 'data']);
    Route::post('/add', [EmpDetailController::class, 'add']);
    Route::post('/edit/{id}', [EmpDetailController::class, 'edit']);
    Route::post('/update', [EmpDetailController::class, 'update']);
    Route::post('/delete/{id}', [EmpDetailController::class, 'delete']);
});

Route::group(['prefix' => 'contact', 'middlewar' => 'auth:santum'], function(){
    Route::post('/data/{id}', [EmpContController::class, 'data']);
    Route::post('/add', [EmpContController::class, 'add']);
    Route::post('/edit/{id}', [EmpContController::class, 'edit']);
    Route::post('/update', [EmpContController::class, 'update']);
    Route::post('/delete/{id}', [EmpContController::class, 'delete']);
});

Route::group(['prefix' => 'bank', 'middlewar' => 'auth:santum'], function(){
    Route::post('/data/{id}', [EmpBankController::class, 'data']);
    Route::post('/add', [EmpBankController::class, 'add']);
    Route::post('/edit/{id}', [EmpBankController::class, 'edit']);
    Route::post('/update', [EmpBankController::class, 'update']);
    Route::post('/delete/{id}', [EmpBankController::class, 'delete']);
});

// CARD
Route::group(['prefix' => 'card', 'middlewar' => 'auth:santum'], function(){
    Route::post('/data/{id}', [EmpCardController::class, 'data']);
    Route::post('/add', [EmpCardController::class, 'add']);
    Route::post('/edit/{id}', [EmpCardController::class, 'edit']);
    Route::post('/update', [EmpCardController::class, 'update']);
    Route::post('/delete/{id}', [EmpCardController::class, 'delete']);
});

// ANNUAL LEAVE
Route::group(['prefix' => 'annual', 'middlewar' => 'auth:santum'], function(){
    Route::post('/data/{id}', [EmpAnnualController::class, 'data']);
    Route::post('/add', [EmpAnnualController::class, 'add']);
    Route::post('/edit/{id}', [EmpAnnualController::class, 'edit']);
    Route::post('/update', [EmpAnnualController::class, 'update']);
    Route::post('/delete/{id}', [EmpAnnualController::class, 'delete']);
    Route::get('/info', [EmpAnnualController::class, 'info']);
});

// FILE
Route::group(['prefix' => 'file', 'middlewar' => 'auth:santum'], function(){
    Route::post('/data/{id}', [EmpFileController::class, 'data']);
    Route::post('/add', [EmpFileController::class, 'add']);
    Route::get('/download/{file}', [EmpFileController::class, 'download']);
    Route::post('/delete/{file}', [EmpFileController::class, 'delete']);
});

// TRANSPORT
Route::group(['prefix' => 'transport', 'middlewar' => 'auth:santum'], function(){
    Route::post('/out', [EmpTransController::class, 'out']);
    Route::post('/in', [EmpTransController::class, 'in']);
    Route::post('/pickup', [EmpTransController::class, 'pickup']);
    Route::post('/dropoff', [EmpTransController::class, 'dropoff']);
});

// FUEL
Route::group(['prefix' => 'fuel', 'middlewar' => 'auth:santum'], function(){
    Route::post('/date', [FuelConsController::class, 'date']);
    Route::post('/dateadd', [FuelConsController::class, 'dateAdd']);
    Route::post('/head', [FuelConsController::class, 'head']);
    Route::post('/detail', [FuelConsController::class, 'detail']);
    Route::post('/headadd', [FuelConsController::class, 'headAdd']);
    Route::post('/headedit/{id}', [FuelConsController::class, 'headEdit']);
    Route::post('/headupd', [FuelConsController::class, 'headUpd']);
    Route::post('/headdel/{id}', [FuelConsController::class, 'headDel']);
    Route::post('/detailadd', [FuelConsController::class, 'detailAdd']);
    Route::post('/detaildel/{id}', [FuelConsController::class, 'detailDel']);
    Route::post('/detailedit/{id}', [FuelConsController::class, 'detailEdit']);
    Route::post('/detailupd', [FuelConsController::class, 'detailUpd']);
    Route::post('/reserveadd', [FuelConsController::class, 'reserveAdd']);
    Route::get('/category', [FuelConsController::class, 'category']);
    Route::post('/code', [FuelConsController::class, 'code']);
    Route::get('/getmin', [FuelConsController::class, 'getMin']);
});

// HOME
Route::group(['prefix' => 'home', 'middlewar' => 'auth:santum'], function(){
    Route::get('/countcountry', [HomeController::class, 'countcountry']);
    Route::get('/countdept', [HomeController::class, 'countdept']);
    Route::get('/allemp', [HomeController::class, 'allemp']);
    Route::get('/onsite', [HomeController::class, 'onsite']);
    Route::get('/offsite', [HomeController::class, 'offsite']);
    Route::get('/users', [HomeController::class, 'users']);
    Route::post('/manpower', [HomeController::class, 'manpower']);
});

// ROTER
Route::group(['prefix' => 'roster', 'middlewar' => 'auth:santum'], function(){
    Route::get('/rosCode', [EmpRosterController::class, 'rosCode']);
    Route::post('/rosCodeAdd', [EmpRosterController::class, 'rosCodeAdd']);
    Route::post('/rosCodeEdit/{id}', [EmpRosterController::class, 'rosCodeEdit']);
    Route::post('/rosCodeUpd', [EmpRosterController::class, 'rosCodeUpd']);
    Route::post('/rosCodeDel/{id}', [EmpRosterController::class, 'rosCodeDel']);
    Route::get('/datecol', [EmpRosterController::class, 'dateCol']);
    Route::get('/roster', [EmpRosterController::class, 'roster']);
    Route::get('/rcode', [EmpRosterController::class, 'rcode']);
    Route::post('/rosdetail', [EmpRosterController::class, 'rosDetail']);
    Route::post('/emplist', [EmpRosterController::class, 'empList']);
    Route::post('/addros', [EmpRosterController::class, 'addRos']);
    Route::post('/delros', [EmpRosterController::class, 'delRos']);
    Route::get('/leaveheader', [EmpRosterController::class, 'leaveHeader']);
    Route::get('/leaveinfo', [EmpRosterController::class, 'leaveInfo']);
    Route::post('/leavedetail', [EmpRosterController::class, 'leaveDetail']);
    Route::post('/addcrew', [EmpRosterController::class, 'addCrew']);
    Route::post('/addsection', [EmpRosterController::class, 'addSection']);
    Route::post('/updshiftwork', [EmpRosterController::class, 'updShiftWork']);
    Route::post('/rostermultiname', [EmpRosterController::class, 'rosterMultiname']);
    Route::get('/breakinfo', [EmpRosterController::class, 'breakInfo']);
    Route::get('/grouplist', [EmpRosterController::class, 'groupList']);
    Route::post('/rosgroupfilter', [EmpRosterController::class, 'rosGroupFilter']);
    Route::get('/positionlist', [EmpRosterController::class, 'PositionList']);
    Route::post('/positionfilter', [EmpRosterController::class, 'PositionFilter']);
    Route::get('/filterlist', [EmpRosterController::class, 'FilterList']);
    Route::post('/filterresult', [EmpRosterController::class, 'FilterResult']);
    Route::post('/addrosterrange', [EmpRosterController::class, 'AddRosterRange']);
    Route::post('/updrosterrange', [EmpRosterController::class, 'UpdRosterRange']);
    Route::post('/delrosterrange', [EmpRosterController::class, 'DelRosterRange']);
});

// FINGER SCAN
Route::group(['prefix' => 'fingerscan', 'middlewar' => 'auth:santum'], function(){
    Route::get('/datecol', [FingerscanController::class, 'dateCol']);
    Route::get('/summary', [FingerscanController::class, 'summary']);
    Route::post('/detail', [FingerscanController::class, 'detail']);
    Route::post('/aclog', [FingerscanController::class, 'AcLog']);
    Route::get('/summary2', [FingerscanController::class, 'summary2']);
    Route::get('/header', [FingerscanController::class, 'header']);


    Route::get('/testprod', [FingerscanController::class, 'testProd']);

});

// SAFETY
Route::group(['prefix' => 'safety', 'middlewar' => 'auth:santum'], function(){
    Route::get('/increcord', [SafetyController::class, 'incRecord']);
    Route::get('/incgroup', [SafetyController::class, 'incGroup']);
    Route::get('/inctype', [SafetyController::class, 'incType']);
    Route::get('/injtype', [SafetyController::class, 'injuryType']);
    Route::get('/injgroup', [SafetyController::class, 'injuryGroup']);
    Route::get('/actseq', [SafetyController::class, 'actualSeq']);
    Route::get('/potseq', [SafetyController::class, 'potentialSeq']);
    Route::get('/invest', [SafetyController::class, 'investStatus']);
    Route::get('/action', [SafetyController::class, 'actionStatus']);
    Route::get('/yesno', [SafetyController::class, 'yesNo']);
    Route::get('/company', [SafetyController::class, 'company']);
    Route::get('/newincid', [SafetyController::class, 'newIncidentId']);
    Route::post('/addincident', [SafetyController::class, 'addIncident']);
    Route::post('/addfiles', [SafetyController::class, 'addFiles']);
    Route::post('/editincident/{id}', [SafetyController::class, 'editIncident']);
    Route::post('/editfiles/{id}', [SafetyController::class, 'editFiles']);
    Route::post('/delincident', [SafetyController::class, 'delIncident']);
    Route::post('/updincident', [SafetyController::class, 'updIncident']);
    Route::get('/downloadfile/{file}', [SafetyController::class, 'downloadFile']);
    Route::post('/delfile/{file}', [SafetyController::class, 'delFile']);
    Route::get('/preview', [SafetyController::class, 'preview']);
    Route::get('/category', [SafetyController::class, 'category']);
    Route::post('/code', [SafetyController::class, 'code']);
    Route::post('/editcode/{id}', [SafetyController::class, 'editCode']);
    Route::post('/addcode', [SafetyController::class, 'addCode']);
    Route::post('/updcode', [SafetyController::class, 'updCode']);
    Route::post('/delcode/{id}', [SafetyController::class, 'delCode']);
    Route::get('/exincident', [SafetyController::class, 'exIncident']);
    Route::get('/safcategory', [SafetyController::class, 'safCategory']);
    Route::post('/cateadd', [SafetyController::class, 'addCate']);
    Route::post('/addfile', [SafetyController::class, 'addFile']);
    Route::post('/getfile', [SafetyController::class, 'getFile']);
    Route::post('/deldoc/{filename}', [SafetyController::class, 'delDoc']);
    Route::get('/downloaddoc/{filename}', [SafetyController::class, 'downloadDoc']);
    Route::get('/filterlist', [SafetyController::class, 'FilterList']);
    Route::post('/filterresult', [SafetyController::class, 'FilterResult']);

    Route::get('/getmin', [SafetyController::class, 'getMin']);
});

// OVERTIME
Route::group(['prefix' => 'overtime', 'middlewar' => 'auth:santum'], function(){
    Route::post('/addovertime', [OvertimeController::class, 'addOvertime']);
    Route::post('/delovertime', [OvertimeController::class, 'delOvertime']);
    Route::get('/otsearch', [OvertimeController::class, 'otSearch']);
    Route::get('/editot', [OvertimeController::class, 'EditOT']);
    Route::post('/addotrange', [OvertimeController::class, 'AddOTRange']);
    Route::post('/updotrange', [OvertimeController::class, 'UpdOTRange']);
    Route::post('/delotrange', [OvertimeController::class, 'DelOTRange']);
    Route::post('/otdetail', [OvertimeController::class, 'OTDetail']);
    Route::post('/runscan', [OvertimeController::class, 'RunOTScan']);




});

// CLINIC
Route::group(['prefix' => 'clinic', 'middlewar' => 'auth:santum'], function(){
    Route::post('/registername', [ClinicController::class, 'RegisterName']);
    Route::get('/newid', [ClinicController::class, 'NewID']);


});

// PRODUCTION
Route::group(['prefix' => 'production', 'middlewar' => 'auth:santum'], function(){
    Route::get('/proddate', [ProductionController::class, 'prodDate']);
    Route::get('/proddata', [ProductionController::class, 'prodData']);


});

// INVENTORY
Route::group(['prefix' => 'inventory', 'middlewar' => 'auth:santum'], function(){
    Route::get('/prrecord', [InventoryController::class, 'prRecord']);
    // Route::get('/proddata', [InventoryController::class, 'prodData']);


});

// LOOKUP
Route::group(['prefix' => 'lookup', 'middlewar' => 'auth:santum'], function(){
    Route::get('/country', [LookupController::class, 'country']);
    Route::get('/province', [LookupController::class, 'province']);
    Route::post('/district', [LookupController::class, 'district']);
    Route::get('/position', [LookupController::class, 'position']);
    Route::get('/status', [LookupController::class, 'status']);
    Route::get('/contract', [LookupController::class, 'contract']);
    Route::get('/levels', [LookupController::class, 'levels']);
    Route::get('/roster', [LookupController::class, 'roster']);
    Route::get('/scantimes', [LookupController::class, 'scantimes']);
    Route::get('/site', [LookupController::class, 'site']);
    Route::get('/dept', [LookupController::class, 'dept']);
    Route::get('/depts', [LookupController::class, 'depts']);
    Route::get('/section', [LookupController::class, 'section']);
    Route::get('/crew', [LookupController::class, 'crew']);
    Route::get('/name', [LookupController::class, 'name']);
    Route::get('/relate', [LookupController::class, 'relate']);
    Route::get('/bank', [LookupController::class, 'bank']);
    Route::get('/card', [LookupController::class, 'card']);
    Route::get('/year', [LookupController::class, 'year']);
    Route::get('/month', [LookupController::class, 'month']);
    Route::get('/fueldept', [LookupController::class, 'fueldept']);
    Route::get('/costtype', [LookupController::class, 'costtype']);
    Route::post('/costno', [LookupController::class, 'costno']);
    Route::post('/wbs', [LookupController::class, 'wbs']);
    Route::post('/approve', [LookupController::class, 'approve']);
    Route::get('/equipno', [LookupController::class, 'equipno']);
    Route::get('/material', [LookupController::class, 'material']);
    Route::post('/equipdescr', [LookupController::class, 'equipdescr']);
    Route::post('/workorder', [LookupController::class, 'workorder']);
    Route::post('/driver', [LookupController::class, 'driver']);
    Route::post('/driveradd', [LookupController::class, 'driverAdd']);
    Route::get('/emplist', [LookupController::class, 'empList']);
});

// LOOKUP SETTING
Route::group(['prefix' => 'lookupSett', 'middlewar' => 'auth:santum'], function(){
    Route::get('/genCategory', [LookupSettController::class, 'genCategory']);
    Route::post('/genLookup', [LookupSettController::class, 'genLookup']);
    Route::post('/genCodeAdd', [LookupSettController::class, 'genCodeAdd']);
    Route::post('/genCodeEdit/{id}', [LookupSettController::class, 'genCodeEdit']);
    Route::post('/genCodeUpd', [LookupSettController::class, 'genCodeUpd']);
    Route::post('/genCodeDel/{id}', [LookupSettController::class, 'genCodeDel']);
    Route::get('/fuelCategory', [LookupSettController::class, 'fuelCategory']);
    Route::post('/fuelLookup', [LookupSettController::class, 'fuelLookup']);
    Route::post('/fuelCodeAdd', [LookupSettController::class, 'fuelCodeAdd']);
    Route::post('/fuelCodeEdit/{id}', [LookupSettController::class, 'fuelCodeEdit']);
    Route::post('/fuelCodeUpd', [LookupSettController::class, 'fuelCodeUpd']);
    Route::post('/fuelCodeDel/{id}', [LookupSettController::class, 'fuelCodeDel']);
    Route::get('/equipment', [LookupSettController::class, 'equipment']);
    Route::post('/workOrder', [LookupSettController::class, 'workOrder']);
    Route::post('/equipAdd', [LookupSettController::class, 'equipAdd']);
    Route::post('/equipEdit/{id}', [LookupSettController::class, 'equipEdit']);
    Route::post('/equipUpd', [LookupSettController::class, 'equipUpd']);
    Route::post('/equipDel/{id}', [LookupSettController::class, 'equipDel']);
    Route::post('/workOrderAdd', [LookupSettController::class, 'workOrderAdd']);
    Route::post('/workOrderEdit/{id}', [LookupSettController::class, 'workOrderEdit']);
    Route::post('/workOrderUpd', [LookupSettController::class, 'workOrderUpd']);
    Route::post('/workOrderDel/{id}', [LookupSettController::class, 'workOrderDel']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
