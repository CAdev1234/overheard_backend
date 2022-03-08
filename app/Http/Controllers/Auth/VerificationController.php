<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\User;
use Illuminate\Support\Facades\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify($user_id, Request $request) {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
        }
        $user = User::findOrFail($user_id);

        try{
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }   
            return response()->json([
                "status"=> true,
                "message" => "Email Verified"
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 200);
        }
        
    }

    // public function resend() {
    //     if (auth()->user()->hasVerifiedEmail()) {
    //         return response()->json([
    //             "status"=> false,
    //             "message" => "Email already verified."
    //         ], 400);
    //     }

    //     auth()->user()->sendEmailVerificationNotification();

    //     return response()->json([
    //         "status" => true,
    //         "message" => "Email verification link sent on your email id"
    //     ]);
    // }
}
