<?php 

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function(){


//     Route::namespace('Auth')->group(function(){

//         //Login Routes
//         Route::get('/','LoginController@showLoginForm');
//         Route::get('/login','LoginController@showLoginForm')->name('login');
//         Route::post('/login','LoginController@login')->name('loginPost');
//         Route::post('/logout','LoginController@logout')->name('logout');

//         //Forgot Password Routes
//         Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
//         Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');

//         //Reset Password Routes
//         Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
//         Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');

//         // // Email Verification Route(s)
//         Route::get('email/verify','VerificationController@show')->name('verification.notice');
//         Route::get('email/verify/{id}','VerificationController@verify')->name('verification.verify');
//         Route::get('email/resend','VerificationController@resend')->name('verification.resend');
//     });

    
//     Route::group(['middleware' => 'auth:admin'], function () {
//         Route::get('/dashboard','HomeController@index')->name('dashboard');
//     // Route::get('/dashboard', function () {
//     //     return Inertia::render('Dashboard');
//     // })->name('dashboard');

//     });
    
// });