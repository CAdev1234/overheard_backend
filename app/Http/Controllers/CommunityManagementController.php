<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('community_management');
    }

    public function getCommunityListData(Request $request){
        $data = array();

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'lat',
            3 => 'lng',
            4 => 'participants',
            5 => 'radius',
            6 => 'ads_price'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = DB::table('communities')
            ->where('isApproved', 1)
            ->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $tableData = DB::table('communities')
                ->where('isApproved', 1)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else{
            $search = $request->input('search.value');
            $tableData = DB::table('communities')
                ->where('isApproved', 1)
                ->where(function ($query) use ($search){
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('lat', 'LIKE', "%{$search}%")
                        ->orWhere('lng', 'LIKE', "%{$search}%")
                        ->orWhere('participants', 'LIKE', "%{$search}%")
                        ->orWhere('radius', 'LIKE', "%{$search}%")
                        ->orWhere('ads_price', 'LIKE', "%{$search}%");
                })

                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)->get();
            $totalFiltered = $tableData->count();
        }
        if(!empty($tableData)){
            foreach ($tableData as $tableRow){
                $rowItems['id'] = $tableRow->id;
                $rowItems['name'] = $tableRow->name;
                $rowItems['lat'] = $tableRow->lat;
                $rowItems['lng'] = $tableRow->lng;
                $rowItems['participants'] = $tableRow->participants;
                $rowItems['radius'] = $tableRow->radius;
                $rowItems['ads_price'] = $tableRow->ads_price;
                $rowItems['created_at'] = $tableRow->created_at;
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

    public function getSubmittedCommunityListData(Request $request){
        $data = array();

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'lat',
            3 => 'lng',
            4 => 'participants',
            5 => 'radius',
            6 => 'ads_price'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = DB::table('communities')
            ->where('isApproved', 0)
            ->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $tableData = DB::table('communities')
                ->where('isApproved', 0)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else{
            $search = $request->input('search.value');
            $tableData = DB::table('communities')
                ->where('isApproved', 0)
                ->where(function ($query) use ($search){
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('lat', 'LIKE', "%{$search}%")
                        ->orWhere('lng', 'LIKE', "%{$search}%")
                        ->orWhere('participants', 'LIKE', "%{$search}%")
                        ->orWhere('radius', 'LIKE', "%{$search}%")
                        ->orWhere('ads_price', 'LIKE', "%{$search}%");
                })

                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)->get();
            $totalFiltered = $tableData->count();
        }
        if(!empty($tableData)){
            foreach ($tableData as $tableRow){
                $rowItems['id'] = $tableRow->id;
                $rowItems['name'] = $tableRow->name;
                $rowItems['lat'] = $tableRow->lat;
                $rowItems['lng'] = $tableRow->lng;
                $rowItems['participants'] = $tableRow->participants;
                $rowItems['radius'] = $tableRow->radius;
                $rowItems['ads_price'] = $tableRow->ads_price;
                $rowItems['created_at'] = $tableRow->created_at;
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

    public function approveCommunity(Request $request){
        $community_id = $request->input('id');
        DB::table('communities')
            ->where('id', $community_id)
            ->update([
                'isApproved' => 1
            ]);
        return response()->json([
            'status' => true
        ], 200);
    }

    public function declineCommunity(Request $request){
        $community_id = $request->input('id');
        DB::table('communities')
            ->where('id', $community_id)
            ->update([
                'isApproved' => 2
            ]);
        return response()->json([
            'status' => true
        ], 200);
    }

    public function communityDetail($id){
        $community = DB::table('communities')
            ->where('id', $id)
            ->first();
        return view('community_detail')
            ->with([
                'community' => $community
            ]);
    }

    public function getCommunityPostListData(Request $request){
        $community_id = $request->input('community_id');
        $data = array();

        $columns = array(
            0 => 'id'
        );

        $users = DB::table('users')
            ->where('community_id', $community_id)
            ->get();

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = 0;
        $totalFiltered = 0;
        $tableData = new \Illuminate\Support\Collection();
        foreach ($users as $user){
            $user_id = $user->id;
            $_totalData = DB::table('posts')
                ->where('user_id', $user_id)
                ->count();
            $totalData += $_totalData;
            $_totalFiltered = $_totalData;

            if(empty($request->input('search.value')))
            {
                $_tableData = DB::table('posts')
                    ->select(
                        'posts.title', 'posts.content', 'posts.upvotes', 'posts.downvotes', 'posts.seen_count',
                        'posts.comments_count', 'posts.post_datetime', 'posts.id as post_id'
                    )
                    ->where('posts.user_id', '=', $user_id)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                foreach ($_tableData as $tableItem){
                    $attaches = DB::table('post_attaches')->where('post_id', $tableItem->post_id)->get();
                    $tableItem->user = $user;
                    $tableItem->attaches = $attaches;
                }
            }
            else{
                $search = $request->input('search.value');
                $_tableData = DB::table('posts')
                    ->select(
                        'posts.title', 'posts.content', 'posts.upvotes', 'posts.downvotes', 'posts.seen_count',
                        'posts.comments_count', 'posts.post_datetime', 'posts.id as post_id'
                    )
                    ->where('posts.user_id', '=', $user_id)
                    ->where('content', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                foreach ($_tableData as $tableItem){
                    $attaches = DB::table('post_attaches')->where('post_id', $tableItem->post_id)->get();
                    $tableItem->user = $user;
                    $tableItem->attaches = $attaches;
                }

                $_totalFiltered = $_tableData->count();
            }
            $totalFiltered += $_totalFiltered;
            $tableData = $tableData->merge($_tableData);
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

    public function getCommunityUserListData(Request $request){
        $community_id = $request->input('community_id');
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
            ->where('community_id', $community_id)
            ->count();
        $totalFiltered = $totalData;

        if(empty($request->input('search.value')))
        {
            $tableData = DB::table('users')
                ->select(
                    'id', 'firstname', 'lastname', 'name', 'email', 'isActive'
                )
                ->where('isAdmin', 0)
                ->where('community_id', $community_id)
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
                ->where('community_id', $community_id)
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

    public function communityEdit($id){
        $community = DB::table('communities')
            ->where('id', $id)
            ->first();
        return view('community_edit')
            ->with([
                'community' => $community
            ]);
    }

    public function updateCommunitySetting(Request $request){
        $community_id = $request->input('id');
        $community_name = $request->input('community_name');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = $request->input('radius');
        $ads_price = $request->input('ads_price');

        DB::table('communities')
            ->where('id', $community_id)
            ->update([
                'name' => $community_name,
                'lat' => $lat,
                'lng' => $lng,
                'radius' => $radius,
                'ads_price' => $ads_price
            ]);
    }

    public function createCommunitySetting(Request $request){
        $community_name = $request->input('community_name');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = $request->input('radius');
        $ads_price = $request->input('ads_price');
        try{
            DB::table('communities')
                ->insert([
                    'name' => $community_name,
                    'lat' => $lat,
                    'lng' => $lng,
                    'radius' => $radius,
                    'participants' => 0,
                    'ads_price' => $ads_price,
                    'created_at' => gmdate('Y-m-d')
                ]);
            return response()->json([
                'status' => true
            ], 200);
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function communityCreate(Request $request){
        return view('community_create');
    }
}
