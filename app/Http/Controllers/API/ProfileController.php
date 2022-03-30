<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PendingReporter;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function uploadAvatar(Request $request){
        if($request->hasFile('file')){
            try{
                $file = $request->file('file');
                $originalname = $file->getClientOriginalName();
                $filename = Str::uuid().'_'.$originalname;
                $file->move(public_path('assets\uploads\avatars'), $filename);
                $avatar_url = url('assets/uploads/avatars/'.$filename);

                DB::table('users')->where('id', Auth::id())->update(['avatar' => $avatar_url]);
                return response()->json([
                    'status' => true,
                    'message' => 'Avatar Upload Complete',
                ], 200);
            }
            catch (\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()//'Avatar Upload Failed'
                ], 200);
            }

        }
    }

    public function completeProfile(Request $request){
        $reporter_request = $request->input('reporter_request');
        $update_data = $request->all();
        unset($update_data["reporter_request"]);
        if ($update_data["email"] === null || $update_data["email"] === Auth::user()->email) {unset($update_data["email"]);}


        if($reporter_request){
            try{
                /*
                $pendingReporter = new PendingReporter();
                $pendingReporter->user_id = $user->id;
                $pendingReporter->save();
                */

                DB::table('verified_reporter')
                    ->updateOrInsert(
                        ['user_id' => Auth::id()],
                        ['status' => 0]
                    );
                // $user->save();
                $update_data['isReporter'] = $reporter_request;
                DB::table('users')->where('id', Auth::id())->update($update_data);
                
            }
            catch (\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'Reporter Request Failed'
                ], 200);
            }
        }
        else{
            try{
                // $user->save();
                DB::table('users')->where('id', Auth::id())->update($update_data);
            }
            catch (\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'Profile Complete Failed'
                ], 200);
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Profile Complete'
        ], 200);
    }

    public function getProfile(Request $request){
        $user = Auth::user();

        try{
            $profile = DB::table('users')
                ->select('name', 'firstname', 'lastname', 'avatar', 'bio', 'community_id', 'isActive')
                ->where('id', $user->id)
                ->first();

            $community = DB::table('communities')
                ->where('id', $profile->community_id)
                ->first();

            $blocked = DB::table('blocked_profiles')
                ->where('visitor_id', $user->id)
                ->where('user_id', $user->id)
                ->first();

            $following = DB::table('profile_followings')
                ->where('follower_id', $user->id)
                ->where('user_id', $user->id)
                ->first();

            $feeds = DB::table('posts')
                ->where('user_id', $user->id)
                ->get()->count();
            $profile->community = $community;
            $profile->totalPost = $feeds;
            $profile->isBlocked = $blocked != null && $blocked->isBlocked == 1;
            $profile->isFollowing = $following != null && $following->isFollowing == 1;

            return response()->json([
                'status' => true,
                'profile' => $profile,
                'user' => DB::table('users')->where('id', $user->id)->first(),
                'viewer' => $user
            ], 200);
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'profile' => $e->getMessage()
            ], 200);
        }
        return response()->json([
            'status' => false,
            'profile' => null
        ], 200);
    }

    public function getProfileFeeds(Request $request){
        $user = Auth::user();
        $pageNum = $request->input('page');
        $pageCount = $request->input('pageCount');

        if($pageCount == 0) {
            $feeds = DB::table('posts')
            ->where('user_id', $user->id);
        }
        else {
            $feeds = DB::table('posts')
            ->where('user_id', $user->id)
            ->paginate($pageCount, '*', 'page', $pageNum);
        }

        $feeds_array = array();
        /// Adding Post Media
        foreach ($feeds as $feed) {
            $attaches = DB::table('post_attaches')
                ->where('post_id', $feed->id)
                ->get();
            $publisher = DB::table('users')
                ->select('id', 'name', 'firstname', 'lastname', 'avatar', 'bio')
                ->where('id', $feed->user_id)
                ->first();
            $tags = DB::table('post_tags')
                ->select('tag')
                ->where('post_id', $feed->id)
                ->get();
            $feed->media = $attaches;
            $feed->publisher = $publisher;
            $feed->tags = $tags;
            array_push($feeds_array, $feed);
        }
        return response()->json([
            'status' => true,
            'feeds' => $feeds_array,
            'user' => $user
        ], 200);
    }

    public function followManage(Request $request){
        $visitor = Auth::user();
        $user_id = $request->input('user_id');

        try{
            $exist = DB::table('profile_followings')
                    ->where('follower_id', $visitor->id)
                    ->where('user_id', $user_id)
                    ->get()->count() > 0;
            if($exist){
                $following = DB::table('profile_followings')
                    ->where('follower_id', $visitor->id)
                    ->where('user_id', $user_id)
                    ->first();
                DB::table('profile_followings')
                    ->where('follower_id', $visitor->id)
                    ->where('user_id', $user_id)
                    ->update([
                        'isFollowing' => ($following->isFollowing + 1) % 2
                    ]);
                return response()->json([
                    'status' => true,
                    'isFollowing' => ($following->isFollowing)% 2 == 0
                ], 200);
            }
            else{
                DB::table('profile_followings')
                    ->insert([
                        'follower_id' => $visitor->id,
                        'user_id' => $user_id,
                        'isFollowing' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                return response()->json([
                    'status' => true,
                    'isFollowing' => true
                ], 200);
            }
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function blockManage(Request $request){
        $user = Auth::user();
        $visitor_id = $request->input('user_id');

        try{
            $exist = DB::table('blocked_profiles')
                    ->where('visitor_id', $visitor_id)
                    ->where('user_id', $user->id)
                    ->get()->count() > 0;
            if($exist){
                $blocked = DB::table('blocked_profiles')
                    ->where('visitor_id', $visitor_id)
                    ->where('user_id', $user->id)
                    ->first();
                DB::table('blocked_profiles')
                    ->where('visitor_id', $visitor_id)
                    ->where('user_id', $user->id)
                    ->update([
                        'isBlocked' => ($blocked->isBlocked + 1) % 2
                    ]);
                return response()->json([
                    'status' => true,
                    'isBlocked' => ($blocked->isBlocked)% 2 == 0
                ], 200);
            }
            else{
                DB::table('profile_followings')
                    ->insert([
                        'visitor_id' => $visitor_id,
                        'user_id' => $user->id,
                        'isFollowing' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                return response()->json([
                    'status' => true,
                    'isBlocked' => true
                ], 200);
            }
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function fetchFollowing(Request $request){
        $user = Auth::user();
        $pageNum = $request->input('page');
        $pageCount = $request->input('pageCount');
        $searchKey = $request->input('searchKey');

        if(strlen($searchKey) > 0){
            $followings = DB::table('profile_followings')
                ->join('users', 'profile_followings.follower_id','=', 'users.id')
                ->select('users.id', 'users.name', 'users.avatar')
                ->where('profile_followings.user_id', $user->id)
                ->where('users.name', 'LIKE', '%'.$searchKey.'%')
                ->where('profile_followings.isFollowing', 1)
                ->forPage($pageNum, $pageCount)->get();
        }
        else{

            $followings = DB::table('profile_followings')
                ->join('users', 'profile_followings.follower_id','=', 'users.id')
                ->select('users.id', 'users.name', 'users.avatar')
                ->where('profile_followings.user_id', $user->id)
                ->where('profile_followings.isFollowing', 1)
                ->forPage($pageNum, $pageCount)->get();
        }

        return response()->json([
            'status' => true,
            'followings' => $followings,
        ], 200);
    }

    public function fetchFollower(Request $request){
        $user = Auth::user();
        $pageNum = $request->input('page');
        $pageCount = $request->input('pageCount');
        $searchKey = $request->input('searchKey');
        if(strlen($searchKey) > 0){
            $followers = DB::table('profile_followings')
                ->join('users', 'profile_followings.user_id','=', 'users.id')
                ->select('users.id', 'users.name', 'users.avatar')
                ->where('profile_followings.follower_id', $user->id)
                ->where('users.name', 'LIKE', '%'.$searchKey.'%')
                ->where('profile_followings.isFollowing', 1)
                ->forPage($pageNum, $pageCount)->get();
        }
        else{
            $followers = DB::table('profile_followings')
                ->join('users', 'profile_followings.user_id','=', 'users.id')
                ->select('users.id', 'users.name', 'users.avatar')
                ->where('profile_followings.follower_id', $user->id)
                ->where('profile_followings.isFollowing', 1)
                ->forPage($pageNum, $pageCount)->get();
        }
        return response()->json([
            'status' => true,
            'followers' => $followers,
        ], 200);
    }

    public function fetchBlockedUsers(Request $request){
        $user = Auth::user();
        $pageNum = $request->input('page');
        $pageCount = $request->input('pageCount');

        $blocked_users = DB::table('blocked_profiles')
            ->join('users', 'blocked_profiles.visitor_id','=', 'users.id')
            ->select('users.id', 'users.name', 'users.avatar')
            ->where('blocked_profiles.user_id', $user->id)
            ->where('blocked_profiles.isBlocked', 1)
            ->forPage($pageNum, $pageCount)->get();
        return response()->json([
            'status' => true,
            'blocked_users' => $blocked_users,
        ], 200);
    }

    public function changePassword(Request $request){
        // $user = Auth::user();
        $userId = $request->userId;
        $user = User::find($userId);
        $email = $user->email;
        $password = $request->input('old_password');
        $new_password = $request->input('new_password');

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        try{
            if($user->password == null){
                return response()->json([
                    'status' => true,
                    'changed' => false,
                    'message' => 'You Signed In with Social'
                ], 200);
            }
            else if(Auth::guard('web')->attempt($credentials)){
                $user->password = Hash::make($new_password);
                $user->save();
                return response()->json([
                    'status' => true,
                    'changed' => true,
                ], 200);
            }
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'changed' => false,
                'message' => 'Password Change Failed'
                //'message' => $e->getMessage()
            ], 200);
        }
    }
}
