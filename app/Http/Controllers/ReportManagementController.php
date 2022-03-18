<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('report_management');
    }

    public function getReportListData(Request $request){
        $data = array();

        $columns = array(
            0 => 'id',
            1 => 'reporter',
            2 => 'reported',
            3 => 'created_at',
            4 => 'isSeen'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = DB::table('reports')->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $tableData = DB::table('reports')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            foreach ($tableData as $tableItem){
                $reporter = DB::table('users')->where('id', $tableItem->reporter_id)->first();
                $reported = DB::table('users')->where('id', $tableItem->reported_id)->first();
                $tableItem->reporter = $reporter;
                $tableItem->reported = $reported;
            }
        }
        else{
            $search = $request->input('search.value');
            $tableData = DB::table('reports')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)->get();
            $totalFiltered = $tableData->count();

            foreach ($tableData as $tableItem){
                $reporter = DB::table('users')->where('id', $tableItem->reporter_id)->first();
                $reported = DB::table('users')->where('id', $tableItem->reported_id)->first();
                $tableItem->reporter = $reporter;
                $tableItem->reported = $reported;
            }
        }
        if(!empty($tableData)){
            foreach ($tableData as $tableRow){
                $rowItems['id'] = $tableRow->id;
                $rowItems['reporter'] = $tableRow->reporter;
                $rowItems['reported'] = $tableRow->reported;
                $rowItems['created_at'] = $tableRow->created_at;
                $rowItems['isSeen'] = $tableRow->isSeen;
                $rowItems['post-id'] = $tableRow->reported_post_id;
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

    public function getReportDetail(Request $request){
        $report_id = $request->input('report');
        $post_id = $request->input('post');
        $report = DB::table('reports')
            ->where('id', $report_id)
            ->first();
        $reporter = DB::table('users')->where('id', $report->reporter_id)->first();
        $reported = DB::table('users')->where('id', $report->reported_id)->first();
        $post = DB::table('posts')
            ->where('id', $post_id)->first();
        $post_attaches = DB::table('post_attaches')->where('post_id', $post_id)->get();
        $post->attaches = $post_attaches;

        return view('report_detail')
            ->with([
                'reporter' => $reporter,
                'reported' => $reported,
                'report' => $report,
                'post' => $post
            ]);
    }
}
