<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class SettingsController extends Controller
{
    use SendsPasswordResetEmails;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function  getLoadManagementTableData(Request $request){
        $data = array();

        $columns = array(
            0 => 'id',
            1 =>'profilename',
            2 =>'socactivate',
            3 => 'socdeactivate',
            4 => 'loadlimit',
            5 => 'active'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = DB::table('loadmanagementprofiles')->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $tableData = DB::table('loadmanagementprofiles')->offset($start)
                ->limit($limit)
                ->orderBy($order,'desc')
                ->get();
        }
        else{
            $search = $request->input('search.value');
            $tableData = DB::table('loadmanagementprofiles')
                ->where('profilename', 'LIKE', "%{$search}%")
                ->orWhere('socactivate', 'LIKE', "%{$search}%")
                ->orWhere('socdeactivate', 'LIKE', "%{$search}%")
                ->orWhere('loadlimit', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)->get();
            $totalFiltered = $tableData->count();
        }
        if(!empty($tableData)){
            foreach ($tableData as $tableRow){
                $rowItems['id'] = $tableRow->id;
                $rowItems['profilename'] = $tableRow->profilename;
                $rowItems['socactivate'] = $tableRow->socactivate;
                $rowItems['socdeactivate'] = $tableRow->socdeactivate;
                $rowItems['loadlimit'] = $tableRow->loadlimit;
                $rowItems['active'] = $tableRow->active;
                $data[] = $rowItems;
            }
        }
        $jsonData = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "aaData" => $data
        );
        echo json_encode($jsonData);
    }

    public function saveLoadManagementTableData(Request $request){
        $id = $request->input('id');
        $profilename = $request->input('profile');
        $soc_activate = $request->input('socactivate');
        $soc_deactivate = $request->input('socdeactivate');
        $load_limit = $request->input('loadlimit');
        $active = $request->input('active');

        if($id == null){
            $affected = DB::table('loadmanagementprofiles')
                ->insert([
                    'profilename' => $profilename,
                    'socactivate' => intval($soc_activate),
                    'socdeactivate' => intval($soc_deactivate),
                    'loadlimit' => intval($load_limit),
                    'active' => intval($active)
                ]);
        }
        else{
            $affected = DB::table('loadmanagementprofiles')
                ->where('id', '=', $id)
                ->update([
                    'socactivate' => intval($soc_activate),
                    'socdeactivate' => intval($soc_deactivate),
                    'loadlimit' => intval($load_limit),
                    'active' => intval($active)
                ]);
        }


        if($affected){
            return response()->json(true, 200);
        }
        else{
            return response()->json(true, 304);
        }

    }

    public function changeProfileActive(Request $request){
        $id = $request->input('id');
        $active = $request->input('active');
        $affected = DB::table('loadmanagementprofiles')
            ->where('id', '=', $id)
            ->update([
                'active' => intval($active)
            ]);
        if($affected){
            return response()->json(['active' => $active], 200);
        }
        else{
            return response()->json(true, 400);
        }
    }

    public function getMeterListTableData(Request $request){
        $data = array();

        $columns = array(
            0 => 'id',
            1 =>'meternumber',
            2 =>'customername',
            3=> 'customertype',
            4=> 'customernumber'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = DB::table('meters')->where('isdeleted', '=', 0)->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $tableData = DB::table('meters')
                ->join('customertype', 'meters.customertype_id', '=', 'customertype.id')
                ->select('meters.id', 'meters.meternumber', 'meters.customername', 'customertype.customertype', 'meters.customernum')
                ->where('isdeleted', '=', 0)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else{
            $search = $request->input('search.value');
            $tableData = DB::table('meters')
                ->join('customertype', 'meters.customertype_id', '=', 'customertype.id')
                ->select('meters.id', 'meters.meternumber', 'meters.customername', 'customertype.customertype', 'meters.customernum')
                ->where('isdeleted', '=', 0)
                ->orWhere('meters.meternumber', 'LIKE', "%{$search}%")
                ->orWhere('meters.customername', 'LIKE', "%{$search}%")
                ->orWhere('meters.customernum', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)->get();
            $totalFiltered = $tableData->count();
        }
        if(!empty($tableData)){
            foreach ($tableData as $tableRow){
                $rowItems['id'] = $tableRow->id;
                $rowItems['meternumber'] = $tableRow->meternumber;
                $rowItems['customername'] = $tableRow->customername;
                $rowItems['customertype'] = $tableRow->customertype;
                $rowItems['customernumber'] = $tableRow->customernum;
                $data[] = $rowItems;
            }
        }
        $jsonData = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "aaData" => $data
        );
        echo json_encode($jsonData);
    }

    public function saveMeterListTableData(Request $request){
        $id = $request->input('id');
        $meternumber = $request->input('meternumber');
        $customername = $request->input('customername');
        $customertype = $request->input('customertype');
        $customernumber = $request->input('customernumber');

        if($id != 0){
            try{
                $affected = DB::table('meters')
                    ->where('id', '=', intval($id))
                    ->update([
                        'meternumber' => $meternumber,
                        'customername' => $customername,
                        'customertype_id' => intval($customertype),
                        'customernum' => $customernumber
                    ]);
            }
            catch (\Exception $err){
                echo $err->getMessage();
            }

        }
        else if($id == 0){
            $affected = DB::table('meters')
                ->insert([
                    'meternumber' => $meternumber,
                    'customername' => $customername,
                    'customertype_id' => intval($customertype),
                    'customernum' => $customernumber,
                    'isdeleted' => 0
                ]);
        }
        if($affected){
            return response()->json(true, 200);
        }
        else{
            return response()->json(true, 304);
        }
    }

    public function deleteMeterListTableData(Request $request){
        $id = $request->input('id');
        $affected = DB::table('meters')->where('id', '=', $id)->update([
            'isdeleted' => 1
        ]);
        if($affected){
            return response()->json(true, 200);
        }
        else{
            return response()->json(true, 304);
        }
    }

    public function getUserListTableData(Request $request){
        $data = array();

        $columns = array(
            0 => 'id',
            1 =>'name',
            2 =>'email',
            3=> 'description'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = DB::table('users')->where('isdeleted', '=', 0)->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $tableData = DB::table('users')
                ->join('usertype', 'users.usertype_id', '=', 'usertype.id')
                ->select('users.id', 'users.name', 'users.email', 'usertype.description')
                ->where('isdeleted', '=', 0)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else{
            $search = $request->input('search.value');
            $tableData = DB::table('users')
                ->join('usertype', 'users.usertype_id', '=', 'usertype.id')
                ->select('users.id', 'users.name', 'users.email', 'usertype.description')
                ->where('isdeleted', '=', 0)
                ->orWhere('users.name', 'LIKE', "%{$search}%")
                ->orWhere('users.email', 'LIKE', "%{$search}%")
                ->orWhere('usertype.description', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)->get();
            $totalFiltered = $tableData->count();
        }
        if(!empty($tableData)){
            foreach ($tableData as $tableRow){
                $rowItems['id'] = $tableRow->id;
                $rowItems['name'] = $tableRow->name;
                $rowItems['email'] = $tableRow->email;
                $rowItems['description'] = $tableRow->description;
                $data[] = $rowItems;
            }
        }
        $jsonData = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "aaData" => $data
        );
        echo json_encode($jsonData);
    }

    public function saveUserListTableData(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $description = $request->input('description');

        if($id != 0){
            try{
                $affected = DB::table('users')
                    ->where('id', '=', intval($id))
                    ->update([
                        'name' => $name,
                        'email' => $email,
                        'usertype_id' => intval($description)
                    ]);
            }
            catch (\Exception $err){
                echo $err->getMessage();
            }

        }
        else if($id == 0){
            try{
                $affected = DB::table('users')
                    ->insert([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make('standard random password'),
                        'usertype_id' => intval($description),
                        'isdeleted' => 0
                    ]);
            }
            catch (\Exception $err){

            }
        }

        if($affected){
            $this->sendResetLinkEmail($request);
            return response()->json(true, 200);
        }
        else{
            return response()->json(true, 304);
        }
    }

    public function deleteUserListTableData(Request $request){
        $id = $request->input('id');
        $affected = DB::table('users')->where('id', '=', $id)->update([
            'isdeleted' => 1
        ]);
        if($affected){
            return response()->json(true, 200);
        }
        else{
            return response()->json(true, 304);
        }
    }

    public function resetUserPassword(Request $request){
        try{
            $this->sendResetLinkEmail($request);
            return response()->json(true, 200);
        }
        catch (\Exception $error){
            return response()->json(true, 304);
        }

    }

    public function updateGeneralInfo(Request $request){
        $projectname = $request->input('projectname');
        $gps_lat = $request->input('gps_latitude');
        $gps_lng = $request->input('gps_longitude');
        $serial = $request->input('weather_predict_serial');
        $affected = DB::table('generalinfo')->where('id', '=', 1)->update([
            'projectname' => $projectname,
            'gps_lat' => $gps_lat,
            'gps_long' => $gps_lng,
            'weatherstationserno' => $serial
        ]);
        if($affected){
            return response()->json(true, 200);
        }
        else{
            return response()->json(true, 304);
        }
    }
}
