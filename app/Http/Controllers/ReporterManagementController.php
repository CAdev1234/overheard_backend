<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporterManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('reporter_management');
    }

    public function getReporterListData(Request $request){
        $data = array();

        $columns = array(
            0 => 'id',
            1 => 'firstname',
            2 => 'lastname',
            3 => 'name',
            4 => 'email'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = DB::table('verified_reporter')
            ->get()
            ->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $tableData = DB::table('verified_reporter')
                ->join('users', 'verified_reporter.user_id', '=', 'users.id')
                ->select('verified_reporter.id', 'users.firstname', 'users.lastname', 'users.name', 'users.email', 'verified_reporter.status')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else{
            $search = $request->input('search.value');
            $tableData = DB::table('verified_reporter')
                ->join('users', 'verified_reporter.user_id', '=', 'users.id')
                ->select('verified_reporter.id', 'users.firstname', 'users.lastname', 'users.name', 'users.email', 'verified_reporter.status')
                ->where(function ($query) use ($search){
                    $query->where('users.firstname', 'LIKE', "%{$search}%")
                        ->orWhere('users.lastname', 'LIKE', "%{$search}%")
                        ->orWhere('users.name', 'LIKE', "%{$search}%")
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
                $rowItems['status'] = $tableRow->status;
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

    public function approveUser(Request $request){
        $id = $request->input('id');
        DB::table('verified_reporter')
            ->where('id', $id)
            ->update([
                'status' => 1
            ]);
        $user = DB::table('verified_reporter')
            ->where('id', $id)
            ->first();

        DB::table('users')
            ->where('id', $user->user_id)
            ->update([
                'isVerified' => 1
            ]);
            
        return response()->json([
            'status' => true
        ], 200);
    }

    public function declineUser(Request $request){
        $id = $request->input('id');
        DB::table('verified_reporter')
            ->where('id', $id)
            ->update([
                'status' => 2
            ]);
        return response()->json([
            'status' => true
        ], 200);
    }
}
