<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use PHPUnit\Exception;

class VerificationController extends Controller
{
    public function verify($user_id, Request $request) {
    	
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
				"message" => 'Email Verification Failed'
			], 200);
		}
	    
	}

	public function resend(Request $request) {
		$user = User::where('email', '=', $request->email)->first();
	    if ($user->hasVerifiedEmail()) {
	        return response()->json([
	        	"status"=> true,
	        	"message" => "Email already verified."
	        ], 200);
	    }

	    $user->sendEmailVerificationNotification();

	    return response()->json([
	    	"status" => true,
	    	"message" => "Email verification link sent on your email id"
	    ]);
	}
}
