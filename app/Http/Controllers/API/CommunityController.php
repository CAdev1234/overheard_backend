<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Drivers\Driver;
use Stevebauman\Location\Drivers\IpInfo;
use Stevebauman\Location\Location;
use Illuminate\Support\Facades\Http;

class CommunityController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getCommunities(Request $request){
        // $user = Auth::user();
        $pageNum = $request->input('page');
        $pageCount = $request->input('pageCount');
        $searchKey = $request->input('searchKey');
        $lat = $request->lat;
        $lng = $request->lng;

        if(strlen($searchKey) > 0){
            $communities = Community::where('name', 'LIKE', '%'.$searchKey.'%')
                ->where('isApproved', 1)->get()->forPage($pageNum, $pageCount);
        }
        else{
            $communities = Community::where('isApproved', 1)->get()->forPage($pageNum, $pageCount);
        }

        $community_array = array();
        foreach ($communities as $community) {
            try{

                $community_lat = $community->lat;
                $community_lng = $community->lng;
                $community_radius = $community->radius;
                $distance = $this->distance($community_lat, $community_lng, $lat, $lng);

                if($distance <= $community_radius){
                    array_push($community_array, $community);
                }

            }
            catch(\Exception $e){
                //echo $e->getMessage();
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to get communities'
                ], 200);
            }

        }

        return response()->json([
            'status' => true,
            'communities' => $community_array,
            // 'user' => $user
        ], 200);
    }

    function distance($lat1, $lon1, $lat2, $lon2) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return ($miles * 1.609344);
    }

    public function confirmCommunity(Request $request){
        try{
            $user = User::find($request->userId);
            $community_id = $request->input('community_id');
            if($user->community_id == null){
                DB::table('communities')
                    ->where('id', $community_id)
                    ->update(['participants' => DB::raw('participants + 1')]);
            }
            else{
                DB::table('communities')
                    ->where('id', $user->community_id)
                    ->update(['participants' => DB::raw('participants - 1')]);

                DB::table('communities')
                    ->where('id', $community_id)
                    ->update(['participants' => DB::raw('participants + 1')]);
            }
            $user->community_id = $community_id;
            $user->save();
            return response()->json([
                'status' => true
            ], 200);
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false
            ], 200);
        }
    }

    public function submitCommunity(Request $request){

        $community_name = $request->input('community_name');
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        try{
            $user = Auth::user();
            $communities = DB::table('communities')->get();
            foreach ($communities as $community) {
                $distance = $this->distance($community->lat, $community->lng, $lat, $lng);
                //echo $distance.',';

                if($distance <= $community->radius && $community_name == $community->name){
                    return response()->json([
                        'status' => false,
                        'message' => 'Community already exists'
                    ], 200);
                }
            }
            DB::table('communities')->insert([
                'name' => $community_name,
                'lat' => doubleval($lat),
                'lng' => doubleval($lng),
                'participants' => 0,
                'radius' => 1000,
                'ads_price' => 1,
                'isApproved' => 0,
                'created_at' => date('Y-m-d')
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Community submitted'
            ], 200);
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()//'Community Submit Failed'
            ], 200);
        }
    }
}
