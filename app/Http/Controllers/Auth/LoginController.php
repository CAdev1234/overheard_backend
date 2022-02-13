<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        // $this->middleware('guest')->except('logout');
    }

    // protected function attemptLogin(Request $request)
    // {
    //     if( $this->guard()->attempt(
    //         $this->credentials($request), $request->filled('remember')
    //     ) ) { // Credential auth was successful
    //         // Get user model
    //         $user = Auth::user();
    //         if($user->isAdmin == 1){
    //             return true;
    //         }
    //         $this->guard()->logout($user);
    //         return redirect()->route('login');
    //     }

    //     return redirect()->route('login');
    // }

    protected function authenticated(Request $request, $user)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        if (AppUser::where('email', '=', $request->email)->first() !== null) {
            return redirect()->intended('usermanagement');
        }else {
            return;
        }
        // if (Auth::attempt($credentials)) {
        //     // Authentication passed...
        //     return redirect()->intended('usermanagement');
        // }
        // return redirect()->route('usermanagement');
    }
}
