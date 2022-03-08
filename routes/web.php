<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\TestAmazonSes;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();


Route::get('privacy_policy', function(){
    return view('privacy_policy');
})->name('privacypolicy');

Route::get('terms', function(){
    return view('terms');
})->name('terms');

Route::get('about_us', function(){
    return view('about_us')->with([
        'testData' => "https://overheard.net"
    ]);
})->name('aboutus');

Route::get('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () {
        return redirect('/usermanagement');
    });

    /***
     * User Management Page Request URLs
     */
    Route::get('usermanagement', 'UserManagementController@index')->name('usermanagement');

    Route::post('getUserListData', 'UserManagementController@getUserListTableData')->name('getuserlistdata');

    Route::get('profile_detail/{id}','UserManagementController@getProfileDetail')->name('getprofiledetail');

    Route::post('active_manage_user', 'UserManagementController@activeManage')->name('user_active_manage');

    /***
     * Post Management Page Request URLs
     */

    Route::get('posts/{id}', 'PostManagementController@getPostList')->name('getpostlist');

    Route::post('getPostListData', 'PostManagementController@getPostListData')->name('getpostlistdata');

    Route::post('delete_post','PostManagementController@deletePost')->name('delete_post');

    /***
     * Report Management Page Request URLs
     */
    Route::get('reportmanagement', 'ReportManagementController@index')->name('reportmanagement');

    Route::post('getReportListData', 'ReportManagementController@getReportListData')->name('reportlistdata');

    Route::get('report_detail', 'ReportManagementController@getReportDetail')->name('reportdetail');

    /***
     * Reporter Management Page Request URLs
     */

    Route::get('reportermanagement', 'ReporterManagementController@index')->name('reportermanagement');

    Route::post('getReporterListData', 'ReporterManagementController@getReporterListData')->name('getreporterlistdata');

    Route::post('approveUser', 'ReporterManagementController@approveUser')->name('approveUser');

    Route::post('declineUser', 'ReporterManagementController@declineUser')->name('declineUser');

    /***
     * Community Management Page Request URLs
     */

    Route::get('communitymanagement', 'CommunityManagementController@index')->name('communitymanagement');

    Route::post('getCommunityListData', 'CommunityManagementController@getCommunityListData')->name('getCommunityListData');

    Route::post('getSubmittedCommunityListData', 'CommunityManagementController@getSubmittedCommunityListData')->name('getSubmittedCommunityListData');

    Route::post('approveCommunity', 'CommunityManagementController@approveCommunity')->name('approveCommunity');

    Route::post('declineCommunity', 'CommunityManagementController@declineCommunity')->name('declineCommunity');

    Route::get('community_detail/{id}', 'CommunityManagementController@communityDetail')->name('communitydetail');

    Route::post('getCommunityPostListData', 'CommunityManagementController@getCommunityPostListData')->name('getCommunityPostListData');

    Route::post('getCommunityUserListData', 'CommunityManagementController@getCommunityUserListData')->name('getCommunityUserListData');

    Route::get('community_edit/{id}', 'CommunityManagementController@communityEdit')->name('communityedit');

    Route::post('updateCommunitySetting', 'CommunityManagementController@updateCommunitySetting')->name('updateCommunitySetting');

    Route::post('createCommunitySetting', 'CommunityManagementController@createCommunitySetting')->name('createCommunitySetting');

    Route::get('community_create', 'CommunityManagementController@communityCreate')->name('communitycreate');

    /***
     * Withdrawals Management Page Request URLs
     */

    Route::get('withdrawalmanagement', function (){
        return 'Withdrawal management';
    })->name('withdrawalmanagement');

    /***
     * Advertisement Management Page Request URLs
     */

    Route::get('advertisementmanagement', function (){
        return 'Advertisement management';
    })->name('advertisementmanagement');
});

Route::get('/test_mail', function () {
      //Mail::to('steadydevelop@outlook.com')->send(new TestAmazonSes('It works!'));
    Mail::send('emails.tpl', [], function($message){
        $message->to('fantasticdev2@gmail.com', 'aaa')->subject('Test');
    });
});