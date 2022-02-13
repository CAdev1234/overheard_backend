<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization, x-ijt');

Route::get('test',function(){
    return response()->json('api is working', 200);
});

Route::group([ 'prefix' => 'auth'], function (){
    Route::group(['middleware' => ['guest:api', 'cors']], function () {
        Route::post('signin', 'API\AuthController@signin');
        Route::post('signup', 'API\AuthController@signup');
        Route::post('firebaseSignIn', 'API\AuthController@firebaseSignIn');
        Route::post('firebaseSignUp', 'API\AuthController@firebaseSignUp');
        Route::post('verify', 'API\AuthController@verify');
        Route::post('restore_password', 'API\AuthController@restoreUserPassword');
        Route::post('reset_password', 'API\AuthController@resetUserPassword');
    });
});

Route::get('email/verify/{id}', 'API\VerificationController@verify');
Route::get('email/resend', 'API\VerificationController@resend');

Route::group(['middleware' => ['auth:api', 'cors']], function() {

    // Auth Related Path
    Route::get('logout', 'API\AuthController@logout');
    Route::post('access-token', 'API\AuthController@getUser');

    // Profile Related Path
    Route::post('complete_profile', 'API\ProfileController@completeProfile');
    Route::post('avatar_upload', 'API\ProfileController@uploadAvatar');
    Route::post('get_profile', 'API\ProfileController@getProfile');
    Route::post('get_profile_feeds', 'API\ProfileController@getProfileFeeds');
    Route::post('follow_manage', 'API\ProfileController@followManage');
    Route::post('block_manage', 'API\ProfileController@blockManage');
    Route::post('fetch_followers', 'API\ProfileController@fetchFollower');
    Route::post('fetch_followings', 'API\ProfileController@fetchFollowing');
    Route::post('fetch_blocked_users', 'API\ProfileController@fetchBlockedUsers');
    Route::post('change_password', 'API\ProfileController@changePassword');

    // Community Related Path
    Route::post('get_communities', 'API\CommunityController@getCommunities');
    Route::post('confirm_community', 'API\CommunityController@confirmCommunity');
    Route::post('submit_community', 'API\CommunityController@submitCommunity');

    // Feed Related Path
    Route::post('get_feeds', 'API\FeedController@getFeeds');
    Route::post('get_location', 'API\FeedController@getLocation');
    Route::post('post_feed_content', 'API\FeedController@postFeedContent');
    Route::post('post_feed_media', 'API\FeedController@postFeedMedia');
    Route::post('get_feed', 'API\FeedController@getFeed');
    Route::post('report_feed', 'API\FeedController@reportFeed');
    Route::post('comment_feed', 'API\FeedController@commentFeed');
    Route::post('comment_vote', 'API\FeedController@commentVote');
    Route::post('feed_vote', 'API\FeedController@feedVote');
    Route::post('feed_edit', 'API\FeedController@feedEdit');
    Route::post('edit_feed_content', 'API\FeedController@editFeedContent');
    Route::post('delete_feed', 'API\FeedController@deleteFeed');

    Route::post('test_array', 'API\FeedController@testArray');
});