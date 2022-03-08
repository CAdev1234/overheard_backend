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
	    	$user->updated_at = gmdate("Y-m-d H:i:s");
			$user->email_verified_at = gmdate("Y-m-d H:i:s");
			$user->save();
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
	    if ($user->email_verified_at != null) {
	        return response()->json([
	        	"status"=> true,
	        	"message" => "Email already verified."
	        ], 200);
	    }

		$user->sendAuthVerificationEmail($request->email, $user->id);

	    return response()->json([
	    	"status" => true,
	    	"message" => "Email verification link sent on your email id"
	    ]);
	}
}
