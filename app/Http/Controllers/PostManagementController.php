<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PostManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getPostList($user_id){
        return view('post_management')
            ->with([
                'user_id' => $user_id
            ]);
    }

    public function getPostListData(Request $request){
        $user = Auth::user();
        $data = array();


        $columns = array(
            0 => 'id',
            1 => 'content',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = DB::table('posts')
            ->where('user_id', $user->id)
            ->count();
        $totalFiltered = $totalData;
        $user = DB::table('users')->where('id', $user->id)->first();
        if(empty($request->input('search.value')))
        {
            $tableData = DB::table('posts')
                ->select(
                    'posts.title', 'posts.content', 'posts.upvotes', 'posts.downvotes', 'posts.seen_count',
                    'posts.comments_count', 'posts.post_datetime', 'posts.id as post_id'
                )
                ->where('posts.user_id', '=', $user->id)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            foreach ($tableData as $tableItem){
                $attaches = DB::table('post_attaches')->where('post_id', $tableItem->post_id)->get();
                $tableItem->user = $user;
                $tableItem->attaches = $attaches;
            }
        }
        else{
            $search = $request->input('search.value');
            $tableData = DB::table('posts')
                ->select(
                    'posts.title', 'posts.content', 'posts.upvotes', 'posts.downvotes', 'posts.seen_count',
                    'posts.comments_count', 'posts.post_datetime', 'posts.id as post_id'
                )
                ->where('posts.user_id', '=', $user->id)
                ->where('content', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            foreach ($tableData as $tableItem){
                $attaches = DB::table('post_attaches')->where('post_id', $tableItem->post_id)->get();
                $tableItem->user = $user;
                $tableItem->attaches = $attaches;
            }

            $totalFiltered = $tableData->count();
        }
        if(!empty($tableData)){
            foreach ($tableData as $tableRow){
                $rowItems['id'] = $tableRow->post_id;
                $rowItems['user'] = $tableRow->user;
                $rowItems['title'] = $tableRow->title;
                $rowItems['content'] = $tableRow->content;
                $rowItems['upvotes'] = $tableRow->upvotes;
                $rowItems['downvotes'] = $tableRow->downvotes;
                $rowItems['seen_count'] = $tableRow->seen_count;
                $rowItems['comments_count'] = $tableRow->comments_count;
                $rowItems['post_datetime'] = $tableRow->post_datetime;
                $rowItems['attaches'] = $tableRow->attaches;
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

    public function deletePost(Request $request){
        $feed_id = $request->input('post_id');
        try{

            $files = DB::table('post_attaches')
                ->select('filename')
                ->where('post_id', $feed_id)
                ->get();

            foreach ($files as $file){
                File::delete(public_path('/uploads/post_media/'.$feed_id.'/'.$file->filename));
            }

            DB::table('posts')
                ->where('id', $feed_id)
                ->delete();

            DB::table('post_attaches')
                ->where('post_id', $feed_id)
                ->delete();

            DB::table('post_comments')
                ->where('post_id', $feed_id)
                ->delete();

            DB::table('post_tags')
                ->where('post_id', $feed_id)
                ->delete();

            DB::table('reports')
                ->where('reported_post_id', $feed_id)
                ->delete();

            return response()->json([
                'status' => true,
                'feed_id' => $feed_id
            ], 200);
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }

    }
}
