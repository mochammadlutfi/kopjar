<?php

namespace App\Http\Controllers\Staff\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:mitra');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify','resend');
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $request->user('mitra')->hasVerifiedEmail()
            ? redirect()->route('mitra.beranda')
            : view('auth.verify',[
                'resendRoute' => 'mitra.verification.resend',
            ]);
    }

    /**
     * Verfy the user email.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        if ($request->route('id') != $request->user('mitra')->getKey()) {
            //id value doesn't match.
            return redirect()
                ->route('mitra.verification.notice')
                ->with('error','Invalid user!');
        }

        if ($request->user('mitra')->hasVerifiedEmail()) {
            return redirect()
                ->route('mitra.beranda');
        }

        $request->user('mitra')->markEmailAsVerified();

        return redirect()
            ->route('mitra.beranda')
            ->with('status','Thank you for verifying your email!');
    }

    /**
     * Resend the verification email.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        if ($request->user('mitra')->hasVerifiedEmail()) {
            return redirect()->route('mitra.beranda');
        }

        $request->user('mitra')->sendEmailVerificationNotification();

        return redirect()
            ->back()
            ->with('status','We have sent you a verification email!');
    }

}
