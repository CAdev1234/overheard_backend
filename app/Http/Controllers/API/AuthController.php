<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use PHPUnit\Exception;
use Twilio\Rest\Client;
// use Validator;
use Illuminate\Support\Facades\Password;
use Firebase\Auth\Token\Exception\InvalidToken;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    //create user
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credential'
            ], 200);
        }

        try {
            if (User::where('email', '=', $request->email)->first() === null) {
                $user = new User([
                    'email' => $request->email,
                    'name' => $request->name,
                    'password' => bcrypt($request->password),
                    'created_at' => gmdate("Y-m-d H:i:s"),
                    'updated_at' => gmdate("Y-m-d H:i:s")
                ]);
                $user->save();
                // $user->sendEmailVerificationNotification();
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Email Verification has been sent',
                        'user' => $user
                    ],
                    200
                );
            } else {
                $user = User::where('email', '=', $request->email)->first();
                $user->name = $request->name;
                $user->password = bcrypt($request->password);
                $user->updated_at = gmdate("Y-m-d H:i:s");
                $user->save();
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Account is updated',
                        'user' => $user
                    ],
                    200
                );
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Sign up Failed'
        ], 200);
    }

    public function firebaseSignUp(Request $request)
    {
        try {
            if (User::where('email', '=', $request->email)->first() === null) {
                $user = new User([
                    'email' => $request->email,
                    'name' => $request->name,
                    'firebaseUID' => $request->firebaseUID,
                    'avatar' => $request->avatar
                ]);
                $user->save();
                return response()->json(['result' => true], 200);
            } else {
                $user = User::where('email', '=', $request->email)->first();
                $user->name = $request->name;
                $user->firebaseUID = $request->firebaseUID;
                $user->avatar = $request->avatar;
                $user->save();
                return response()->json(['result' => true], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => $e->getMessage()
            ], 200);
        }
        return response()->json(['result' => false], 200);
    }

    public function firebaseSignIn(Request $request)
    {
        // Launch Firebase Auth
        $auth = app('firebase.auth');

        $idTokenString = $request->input('Firebasetoken');

        try { // Try to verify the Firebase credential token with Google
            //$config = $container->get(Configuration::class);
            //$token = $config->parser()->parse($idTokenString);
            $verifiedIdToken = $auth->verifyIdToken($idTokenString);
        } catch (\InvalidArgumentException $e) { // If the token has the wrong format

            return response()->json([
                'message' => 'Unauthorized - Can\'t parse the token: ' . $e->getMessage()
            ], 401);
        } catch (InvalidToken $e) { // If the token is invalid (expired ...)

            return response()->json([
                'message' => 'Unauthorized - Token is invalide: ' . $e->getMessage()
            ], 401);
        }

        // Retrieve the UID (User ID) from the verified Firebase credential's token
        $uid = $verifiedIdToken->getClaim('sub');

        // Retrieve the user model linked with the Firebase UID
        $user = User::where('firebaseUID', $uid)->where('isActive', 1)->first();

        // Here you could check if the user model exist and if not create it
        // For simplicity we will ignore this step

        // Once we got a valid user model
        // Create a Personnal Access Token
        $tokenResult = $user->createToken('token');

        // Store the created token
        $token = $tokenResult->token;

        // Save the token to the user
        $token->save();

        // Return a JSON object containing the token datas
        // You may format this object to suit your needs
        return response()->json([
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
        ], 200);
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credential'
            ], 200);
        }

        $credentials = request(['email', 'password']);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credential'
            ], 200);
        }
        // try {
        //     if (!Auth::attempt($credentials)) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'Invalid Credential'
        //         ], 200);
        //     }
        // } catch (Exception $e) {

        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Invalid Credential'
        //     ], 200);
        // }

        // $user = $request->user();

        // if(!$user->hasVerifiedEmail()){
        //     return response()->json([
        //             'status' => false,
        //             'message' => "You didn't verify email"
        //         ], 200);
        // }
        // if($user->isActive == 0){
        //     return response()->json([
        //             'status' => false,
        //             'message' => "You had been blocked"
        //         ], 200);
        // }
        try {
            $tokenResult = $user->createToken('access_token');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
        $token = $tokenResult->accessToken;
        $success['status'] = true;
        $success['user'] = $user;
        $success['access_token'] =  $token;
        $sender = new ResponseController;
        return $sender->sendResponse($success);
    }

    //logout
    public function logout(Request $request)
    {

        $isUser = $request->user()->token()->revoke();
        $sender = new ResponseController;
        if ($isUser) {
            $success['message'] = "Successfully logged out.";
            return $sender->sendResponse($success);
        } else {
            $error = "Something went wrong.";
            return $sender->sendResponse($error);
        }
    }

    //getuser
    public function getUser(Request $request)
    {
        $auth = Auth::user();
        $user = User::find($auth->email);
        $sender = new ResponseController;

        if ($user) {
            if($user->isActive == 0){
                return response()->json([
                        'status' => false,
                        'message' => "You has been blocked"
                    ], 200);
            }
            $tokenResult = $user->createToken('token');
            $token = $tokenResult->accessToken;
            $success['status'] = true;
            $success['user'] = $user;
            $success['access_token'] = $token;
            return $sender->sendResponse($success);
        } else {
            $success['status'] = false;
            $error = "user not found";
            return $sender->sendResponse($error);
        }
    }

    public function restoreUserPassword(Request $request)
    {
        // try {
        //     //$this->sendResetLinkEmail($request);
        //     $credentials = ['email' => $request->input('email')];


        //     $response = Password::sendResetLink($credentials, function (Message $message) {
        //         $message->subject('Password Reset');
        //     });

        //     switch ($response) {
        //         case Password::RESET_LINK_SENT:
        //             return response()->json(['status' => true], 200);
        //         case Password::INVALID_USER:
        //             return response()->json(['status' => false], 200);
        //     }

        // } catch (\Exception $error) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => $error->getMessage()
        //     ], 200);
        // }

    }

    public function resetUserPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|between:5,255|confirmed',
            'token' => 'required'
        ]);
        //check if payload is valid before moving on
        if ($validator->fails()) {
            $error = $validator->errors();
            print_r($error);
            return response()->json([
                'status' => false,
                'message' => 'Required Fields Validation Failed'
            ], 200);
        }

        $password = $request->input('password');
        $reset = DB::table('password_resets')->where('email', $request->input('email'))->first();
        if (!Hash::check($request->input('token'), $reset->token))
            return response()->json([
                'status' => false,
                'message' => 'Invalid Token'
            ], 200);;

        $user = User::where('email', $request->input('email'))->first();

        try {
            if (!$user)
                return response()->json([
                    'status' => false,
                    'message' => 'User not Found',
                ], 200);

            $user->password = Hash::make($password);
            $user->update();

            //Delete the token
            DB::table('password_resets')->where('email', $user->email)
                ->delete();

            return response()->json([
                'status' => true,
                'message' => 'Password Changed Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Network Error'
            ], 200);
        }
    }
}
