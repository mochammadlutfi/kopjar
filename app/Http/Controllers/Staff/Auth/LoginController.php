<?php

namespace App\Http\Controllers\Staff\Auth;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Inertia\Inertia;
use Route;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admin users for the application and
    | redirecting them to your admin dashboard.
    |
    */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Login the admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        if(is_numeric($request->get('username'))){
            $login_type = 'anggota_id';
        }else{
            $login_type = 'username';
        }
        $request->merge([
            $login_type => $request->input('username')
        ]);

        $rules = [
            $login_type => 'required|string',
            'password' => 'required|string'
        ];

        $pesan = [
            $login_type.'.required' => 'Username / ID Anggota Wajib Diisi!',
            'password.required' => 'Password Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            // return 
            return back()->withErrors($validator->errors());
        }else{

            if(auth()->guard('admin')->attempt($request->only($login_type,'password')))
            {
                return redirect()->route('dashboard');
            }else{
                $gagal['password'] = array('Password salah!');

                return back()->withErrors($gagal);
            }
        }
    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login');
    }


    /**
     * Redirect back after a failed login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed(){
        return redirect()
            ->back()
            ->withInput()
            ->with('error','Login failed, please try again!');
    }

}

