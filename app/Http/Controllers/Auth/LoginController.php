<?php

namespace App\Http\Controllers\Auth;

use Redirect;
use App\Http\Controllers\Controller;
use Auth as mAuth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\Auth;

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
    protected $redirectTo = '/layout';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    

    
    public function login(Request $request){
       // return array('Loging' => $request->input());
       
       // Guardamos en un arreglo los datos del usuario.
       $userdata = array(
           'email' => $request->email,
           'password'=> $request->password
       );
       
       if(mAuth::attempt($userdata))
       {
            return mAuth::user();
           //return Redirect::to('/users');
       }
       // En caso de que la autenticaci贸n haya fallado manda un mensaje al formulario de login y tambi茅n regresamos los valores enviados con withInput().
       return array('data' => "no login");
                   
        
    }
}
