<?php

namespace App\Http\Controllers\API;

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
				"message" => $e->getMessage()
			], 200);
		}
	    
	}

	public function resend() {
	    if (auth()->user()->hasVerifiedEmail()) {
	        return response()->json([
	        	"status"=> false,
	        	"message" => "Email already verified."
	        ], 400);
	    }

	    auth()->user()->sendEmailVerificationNotification();

	    return response()->json([
	    	"status" => true,
	    	"message" => "Email verification link sent on your email id"
	    ]);
	}
}
