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

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('user_management');
    }

    public function getUserListTableData(Request $request){
        $data = array();

        $columns = array(
            0 => 'id',
            1 => 'firstname',
            2 => 'lastname',
            3 => 'name',
            4 => 'email',
            5 => 'isActive'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = DB::table('users')
            ->where('isAdmin', 0)
            ->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $tableData = DB::table('users')
                ->select(
                    'id', 'firstname', 'lastname', 'name', 'email', 'isActive'
                )
                ->where('isAdmin', 0)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else{
            $search = $request->input('search.value');
            $tableData = DB::table('users')
                ->select(
                    'id', 'firstname', 'lastname', 'name', 'email', 'isActive', 'isAdmin'
                )
                ->where('isAdmin', 0)
                ->where(function ($query) use ($search){
                    $query->where('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('users.email', 'LIKE', "%{$search}%");
                })

                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)->get();
            $totalFiltered = $tableData->count();
        }
        if(!empty($tableData)){
            foreach ($tableData as $tableRow){
                $rowItems['id'] = $tableRow->id;
                $rowItems['firstname'] = $tableRow->firstname;
                $rowItems['lastname'] = $tableRow->lastname;
                $rowItems['name'] = $tableRow->name;
                $rowItems['email'] = $tableRow->email;
                $rowItems['isActive'] = $tableRow->isActive;
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

    public function getProfileDetail($id){
        $user = DB::table('users')
            ->select('id', 'firstname', 'lastname', 'avatar', 'name', 'email', 'isReporter', 'isActive', 'created_at')
            ->where('id', $id)
            ->first();
        return view('user_detail')
            ->with([
                'user' => $user
            ]);
    }

    public function activeManage(Request $request){
        $user_id = Auth::id();
        $isActive = DB::table('users')
            ->select('isActive')
            ->where('id', $user_id)
            ->first();
        DB::table('users')
            ->where('id', $user_id)
            ->update([
                'isActive' => ($isActive->isActive + 1) % 2
            ]);
        return redirect()->back();
    }
}
