<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function  getFeeds(Request $request){
        $user = Auth::user();
        $community_id = $user->community_id;
        $pageNum = $request->input('page');
        $pageCount = $request->input('pageCount');
        $searchKey = $request->input('searchKey');
        $filterOption = $request->input('filterOption');


        $users = DB::table('users')
            ->where('community_id', $community_id)
            ->get();


        if($user->community_id == null){

        }

        $user_ids = array();
        foreach ($users as $_user){
            array_push($user_ids, $_user->id);
        }

        $_feed_ids = DB::table('post_tags')
            ->select('post_id')
            ->where('tag', $searchKey)
            ->get();

        $feed_ids = array();
        foreach ($_feed_ids as $_feed_id){
            array_push($feed_ids, $_feed_id->post_id);
        }

        if(strlen($searchKey) > 0){
            switch ($filterOption){
                case 1:
                    $feeds = DB::table('posts')
                        ->where('posts.content', 'LIKE', '%'.$searchKey.'%')
                        ->whereIn('id', $feed_ids)
                        ->whereIn('user_id', $user_ids)
                        ->orderBy('id', 'desc')
                        ->paginate($pageCount, '*', 'page', $pageNum);
                    break;
                case 2:
                    $feeds = DB::table('posts')
                        ->where('posts.content', 'LIKE', '%'.$searchKey.'%')
                        ->whereIn('id', $feed_ids)
                        ->whereIn('user_id', $user_ids)
                        ->orderBy('upvotes', 'desc')
                        ->paginate($pageCount, '*', 'page', $pageNum);
                    break;
                case 3:
                    $feeds = DB::table('posts')
                        ->where('posts.content', 'LIKE', '%'.$searchKey.'%')
                        ->whereIn('id', $feed_ids)
                        ->whereIn('user_id', $user_ids)
                        ->orderBy('seen_count', 'desc')
                        ->paginate($pageCount, '*', 'page', $pageNum);
                    break;
                default:
                    $feeds = DB::table('posts')
                        ->where('posts.content', 'LIKE', '%'.$searchKey.'%')
                        ->whereIn('id', $feed_ids)
                        ->whereIn('user_id', $user_ids)
                        ->orderBy('id', 'desc')
                        ->paginate($pageCount, '*', 'page', $pageNum);
                    break;
            }

        }
        else{
            switch ($filterOption){
                case 1:
                    $feeds = DB::table('posts')
                        ->whereIn('user_id', $user_ids)
                        ->orderBy('id', 'desc')
                        ->paginate($pageCount, '*', 'page', $pageNum);
                    break;
                case 2:
                    $feeds = DB::table('posts')
                        ->whereIn('user_id', $user_ids)
                        ->orderBy('upvotes', 'desc')
                        ->paginate($pageCount, '*', 'page', $pageNum);
                    break;
                case 3:
                    $feeds = DB::table('posts')
                        ->whereIn('user_id', $user_ids)
                        ->orderBy('seen_count', 'desc')
                        ->paginate($pageCount, '*', 'page', $pageNum);
                    break;
                default:
                    $feeds = DB::table('posts')
                        ->whereIn('user_id', $user_ids)
                        ->orderBy('seen_count', 'desc')
                        ->paginate($pageCount, '*', 'page', $pageNum);
                    break;
            }
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
            'user' => $user,
            'page' => $pageNum + 1
        ], 200);
    }

    public function  getFeed(Request $request){
        $feed_id = $request->input('feed_id');
        $filterOption = $request->input('filterOption');
        $user = Auth::user();

        $feed = DB::table('posts')
            ->where('id', $feed_id)
            ->first();

        DB::table('posts')
            ->where('id', $feed_id)
            ->update([
                'seen_count' => DB::raw('seen_count + 1')
            ]);

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
        if($filterOption == 1){
            $comments = DB::table('post_comments')
                ->where('post_id', $feed_id)
                ->orderBy('id', 'asc')
                ->get();
        }
        else{
            $comments = DB::table('post_comments')
                ->where('post_id', $feed_id)
                ->orderBy('id', 'desc')
                ->get();
        }

        $comments_array = array();
        foreach ($comments as $comment){
            $comment_user = DB::table('users')
                ->select('id', 'name', 'firstname', 'lastname', 'avatar', 'bio')
                ->where('id', $comment->commenter_id)
                ->get();
            array_push($comments_array, [
                'comment' => $comment,
                'comment_user' => $comment_user
            ]);
        }

        $feed->media = $attaches;
        $feed->publisher = $publisher;
        $feed->tags = $tags;
        $feed->comments = $comments_array;

        return response()->json([
            'status' => true,
            'feed' => $feed,
            'user' => $user,
        ], 200);
    }

    public function reportFeed(Request $request){
        $user_id = Auth::user()->id;
        $reported_feed_id = $request->input('feed_id');
        $reported_author_id = $request->input('reported_author_id');
        $report_reason = $request->input('reason');
        $report_content = $request->input('content');
        $reporter = User::find($user_id);
        try{
            $check_double = DB::table('reports')
                ->where('reporter_id', $reporter->id)
                ->where('reported_id', $reported_author_id)
                ->where('reported_post_id', $reported_feed_id)
                ->get()->count();
            if($check_double == 0){
                DB::table('reports')->insert([
                    'reporter_id' => $reporter->id,
                    'reported_id' => $reported_author_id,
                    'reported_post_id' => $reported_feed_id,
                    'reason' => $report_reason,
                    'content' => $report_content,
                    'isSeen' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Report is under review'
                ], 200);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'You already reported this post'
                ], 200);
            }

        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Reporting Failed'
            ], 200);
        }
    }

    public function commentFeed(Request $request){
        // $userId = $request->input('userId');
        $userId = Auth::user()->id;
        $feed_id = $request->input('feed_id');
        $comment = $request->input('comment');
        $commenter = User::find($userId);
        try{
            DB::table('post_comments')->insert([
                'post_id' => $feed_id,
                'commenter_id' => $commenter->id,
                'comment_content' => $comment,
                'comment_datetime' => gmdate("Y-m-d H:i:s"),
                'created_at' => gmdate("Y-m-d H:i:s"),
                'updated_at' => gmdate("Y-m-d H:i:s")
            ]);
            // Increase comment count
            DB::table('posts')
                        ->where('id', $feed_id)
                        ->update([
                            'comments_count' => DB::raw('comments_count + 1')
                        ]);
            return response()->json([
                'status' => true,
                'message' => 'Comment success'
            ], 200);

        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Comment Failed'
            ], 200);
        }
    }

    public function getLocation(Request $request){
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $user = Auth::user();
        return response()->json([
            'status' => true,
            'user' => $user
        ], 200);
    }

    public function postFeedContent(Request $request) {
        $title = $request->input('title');
        $content = $request->input('content');
        $location = $request->input('location');
        $tags = $request->input('tags');
        $feed_id = 0;
        try{
            $feed_id = DB::table('posts')->insertGetId([
                'user_id' => Auth::id(),
                'content' => $content,
                'title' => $title,
                'location' => $location,
                'post_datetime' => gmdate("Y-m-d H:i:s")
            ]);

            if(count($tags) > 0){
                for ($i = 0; $i < count($tags); $i++) {
                    $post_tags[] = [
                        'post_id' => $feed_id,
                        'tag' => $tags[$i]
                    ];
                }
                DB::table('post_tags')->insert($post_tags);
            }

            return response()->json([
                'status' => true,
                'feed_id' => $feed_id
            ], 200);
        }
        catch (\Exception $e){
            DB::table('posts')->where('id', $feed_id)->delete();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function editFeedContent(Request $request){
        $feed_id = $request->input('feed_id');
        $title = $request->input('title');
        $content = $request->input('content');
        $location = $request->input('location');
        $urls = $request->input('urls');
        $tags = $request->input('tags');
        try{
            $affected = DB::table('posts')
                ->where('id', $feed_id)
                ->update(
                    [
                        'title' => $title,
                        'content' => $content,
                        'location' => $location
                    ]);

            $files = DB::table('post_attaches')
                ->select('filename')
                ->where('post_id', $feed_id)
                ->whereNotIn('url', $urls)
                ->get();

            foreach ($files as $file){
                File::delete(public_path('uploads/post_media/'.$feed_id.'/'.$file->filename));
            }

            $old_tags = DB::table('post_tags')
                ->where('post_id', $feed_id)
                ->get();
            DB::table('post_tags')
                ->where('post_id', $feed_id)
                ->delete();

            for ($i = 0; $i < count($tags); $i++) {
                $post_tags[] = [
                    'post_id' => $feed_id,
                    'tag' => $tags[$i]
                ];
            }
            DB::table('post_tags')->insert($post_tags);


            DB::table('post_attaches')
                ->where('post_id', $feed_id)
                ->whereNotIn('url', $urls)
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

    public function deleteFeed(Request $request){
        $feed_id = $request->input('feed_id');
        try{
            DB::table('posts')
                ->where('id', $feed_id)
                ->delete();

            $files = DB::table('post_attaches')
                ->select('filename')
                ->where('post_id', $feed_id)
                ->get();

            foreach ($files as $file){
                File::delete(public_path('/uploads/post_media/'.$feed_id.'/'.$file->filename));
            }

            DB::table('post_attaches')
                ->where('post_id', $feed_id)
                ->delete();

            DB::table('post_comments')
                ->where('post_id', $feed_id)
                ->delete();

            DB::table('post_tags')
                ->where('post_id', $feed_id)
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

    public function postFeedMedia(Request $request){
        if($request->hasFile('file')){
            try{
                $file = $request->file('file');
                $feed_id = $request->input('id');
                $originalname = $file->getClientOriginalName();
                $filename = $originalname;
                // $file->storeAs('public/post_media/'.$feed_id.'/', $filename);
                $file->move(public_path('/uploads/post_media/'.$feed_id.'/'), $filename);
                $media_url = url('assets/uploads/post_media/'.$feed_id.'/'.$filename);
                $ext = pathinfo($originalname, PATHINFO_EXTENSION);

                // $double_check = DB::table('post_attaches')
                //     ->where('post_id', $feed_id)
                //     ->where('filename', $filename)
                //     ->get()->count();
                
                if(mb_strtolower($ext) == 'mp4' || mb_strtolower($ext) == 'avi'){
                    DB::table('post_attaches')->insert([
                        'post_id' => $feed_id,
                        'filename' => $filename,
                        'url' => $media_url,
                        'thumbnail' => $media_url
                    ]);
                }
                else{
                    DB::table('post_attaches')->insert([
                        'post_id' => $feed_id,
                        'filename' => $filename,
                        'url' => $media_url,
                        'thumbnail' => $media_url
                    ]);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Media Upload Complete',
                    'path' => $media_url
                ], 200);

                // return response()->json([
                //     'status' => true,
                //     'message' => 'You already Uploaded This file - '.$filename,
                //     'path' => $media_url
                // ], 200);
            }
            catch (\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ], 200);
            }

        }
    }

    public function commentVote(Request $request){
        $comment_id = $request->input('comment_id');
        $isUp = $request->input('isUp');
        $user = Auth::user();
        try{
            $check_double = DB::table('comment_votes')
                ->where('voter_id', $user->id)
                ->where('comment_id', $comment_id)
                ->get()->count();
            if($check_double > 0){
                return response()->json([
                    'status' => true,
                    'message' => "You can't vote again"
                ], 200);
            }
            else{
                if($isUp){
                    /// Increasing Upvote count
                    DB::table('post_comments')
                        ->where('id', $comment_id)
                        ->update([
                            'upvotes' => DB::raw('upvotes + 1')
                        ]);

                    /// Get Comment
                    $comment = DB::table('post_comments')
                        ->where('id', $comment_id)
                        ->first();
                    $comment_user = DB::table('users')
                        ->select('id', 'name', 'firstname', 'lastname', 'avatar', 'bio')
                        ->where('id', $comment->commenter_id)
                        ->get();

                    /// Record vote history
                    DB::table('comment_votes')
                        ->insert([
                            'comment_id' => $comment_id,
                            'voter_id' => $user->id,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    return response()->json([
                        'status' => true,
                        'comment' => [
                            'comment' => $comment,
                            'comment_user' => $comment_user
                        ],
                        'message' => 'Upvote Done'
                    ], 200);
                }
                else{
                    /// Increasing Downvote count
                    DB::table('post_comments')
                        ->where('id', $comment_id)
                        ->update([
                            'downvotes' => DB::raw('downvotes + 1')
                        ]);

                    /// Get Comment
                    $comment = DB::table('post_comments')
                        ->where('id', $comment_id)
                        ->first();
                    $comment_user = DB::table('users')
                        ->select('id', 'name', 'firstname', 'lastname', 'avatar', 'bio')
                        ->where('id', $comment->commenter_id)
                        ->get();

                    /// Record vote history
                    DB::table('comment_votes')
                        ->insert([
                            'comment_id' => $comment_id,
                            'voter_id' => $user->id,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    return response()->json([
                        'status' => true,
                        'comment' => [
                            'comment' => $comment,
                            'comment_user' => $comment_user
                        ],
                        'message' => 'Downvote Done'
                    ], 200);
                }
            }
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function feedVote(Request $request){
        $feed_id = $request->input('feed_id');
        $isUp = $request->input('isUp');
        $user = Auth::user();
        try{
            $check_double = DB::table('feed_votes')
                ->where('voter_id', $user->id)
                ->where('feed_id', $feed_id)
                ->get()->count();

            if($check_double > 0){
                $feed = DB::table('posts')
                    ->where('id', $feed_id)
                    ->first();

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
                $comments = DB::table('post_comments')
                    ->where('post_id', $feed_id)
                    ->get();
                $comments_array = array();
                foreach ($comments as $comment){
                    $comment_user = DB::table('users')
                        ->select('id', 'name', 'firstname', 'lastname', 'avatar', 'bio')
                        ->where('id', $comment->commenter_id)
                        ->get();
                    array_push($comments_array, [
                        'comment' => $comment,
                        'comment_user' => $comment_user
                    ]);
                }

                $feed->media = $attaches;
                $feed->publisher = $publisher;
                $feed->tags = $tags;
                $feed->comments = $comments_array;

                return response()->json([
                    'status' => true,
                    'feed' => $feed,
                    'message' => "You can't vote again"
                ], 200);
            }
            else{
                if($isUp){
                    /// Increasing Upvote count
                    DB::table('posts')
                        ->where('id', $feed_id)
                        ->update([
                            'upvotes' => DB::raw('upvotes + 1')
                        ]);
                    $feed = DB::table('posts')
                        ->where('id', $feed_id)
                        ->first();


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
                    $comments = DB::table('post_comments')
                        ->where('post_id', $feed_id)
                        ->get();
                    $comments_array = array();
                    foreach ($comments as $comment){
                        $comment_user = DB::table('users')
                            ->select('id', 'name', 'firstname', 'lastname', 'avatar', 'bio')
                            ->where('id', $comment->commenter_id)
                            ->get();
                        array_push($comments_array, [
                            'comment' => $comment,
                            'comment_user' => $comment_user
                        ]);
                    }

                    $feed->media = $attaches;
                    $feed->publisher = $publisher;
                    $feed->tags = $tags;
                    $feed->comments = $comments_array;
                    /// Record vote history
                    DB::table('feed_votes')
                        ->insert([
                            'feed_id' => $feed_id,
                            'voter_id' => $user->id,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    return response()->json([
                        'status' => true,
                        'feed' => $feed,
                        'message' => 'Upvote Done'
                    ], 200);
                }
                else{
                    /// Increasing Downvote count
                    DB::table('posts')
                        ->where('id', $feed_id)
                        ->update([
                            'downvotes' => DB::raw('downvotes + 1')
                        ]);
                    $feed = DB::table('posts')
                        ->where('id', $feed_id)
                        ->first();

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
                    $comments = DB::table('post_comments')
                        ->where('post_id', $feed_id)
                        ->get();
                    $comments_array = array();
                    foreach ($comments as $comment){
                        $comment_user = DB::table('users')
                            ->select('id', 'name', 'firstname', 'lastname', 'avatar', 'bio')
                            ->where('id', $comment->commenter_id)
                            ->get();
                        array_push($comments_array, [
                            'comment' => $comment,
                            'comment_user' => $comment_user
                        ]);
                    }

                    $feed->media = $attaches;
                    $feed->publisher = $publisher;
                    $feed->tags = $tags;
                    $feed->comments = $comments_array;
                    /// Record vote history
                    DB::table('feed_votes')
                        ->insert([
                            'feed_id' => $feed_id,
                            'voter_id' => $user->id,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    return response()->json([
                        'status' => true,
                        'feed' => $feed,
                        'message' => 'Downvote Done'
                    ], 200);
                }
            }
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function feedEdit(Request $request){

    }
    public function testArray(Request $request){
        $get_value = $request->input('test');
        print_r(json_encode($get_value));
    }
}
