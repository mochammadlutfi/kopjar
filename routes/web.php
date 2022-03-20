<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::any('{all}', function () {
//     return view('layouts.app');
// })
// ->where(['all' => '.*']);

Route::get('/', function () {
    return Inertia::render('Auth/Login');
})->middleware('guest');

Route::namespace('Staff')->group(function(){
    Route::namespace('Auth')->group(function(){
        //Login Routes
        Route::get('/','LoginController@showLoginForm');
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::post('/login','LoginController@login')->name('loginPost');
        Route::post('/logout','LoginController@logout')->name('logout');

        //Forgot Password Routes
        Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');

        //Reset Password Routes
        Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');

        // // Email Verification Route(s)
        Route::get('email/verify','VerificationController@show')->name('verification.notice');
        Route::get('email/verify/{id}','VerificationController@verify')->name('verification.verify');
        Route::get('email/resend','VerificationController@resend')->name('verification.resend');
    });

    
    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/dashboard','HomeController@index')->name('dashboard');

        Route::group(['prefix' => 'settings', 'as'=>'settings.'], function () {

            Route::group(['prefix' => 'staff', 'as'=>'staff.'], function () {
                Route::get('/', 'StaffController@index')->name('index');
                Route::get('/create', 'StaffController@create')->name('create');
                Route::post('/store','StaffController@store')->name('store');
                Route::get('/edit/{id}', 'StaffController@edit')->name('edit');
                Route::post('/update','StaffController@update')->name('update');
                Route::get('/detail/{id}', 'StaffController@show')->name('show');
            });

            Route::group(['prefix' => 'roles'], function () {
                Route::get('/', 'RolesController@index')->name('roles.index');
                Route::get('/create', 'RolesController@create')->name('roles.create');
                Route::post('/store','RolesController@store')->name('roles.store');
                Route::get('/edit/{id}', 'RolesController@edit')->name('roles.edit');
                Route::post('/update','RolesController@update')->name('roles.update');
                Route::delete('/delete/{id}','RolesController@destroy')->name('roles.delete');
            });

        });

        Route::group(['prefix' => 'anggota', 'as'=>'anggota.'], function () {
            Route::get('/', 'AnggotaController@index');
            Route::get('/list', 'AnggotaController@index')->name('index');
            Route::get('/create', 'AnggotaController@create')->name('create');
            Route::post('/store','AnggotaController@store')->name('store');
            Route::get('/edit/{id}', 'AnggotaController@edit')->name('edit');
            Route::post('/update','AnggotaController@update')->name('update');
            Route::get('/data', 'AnggotaController@data')->name('data');
            Route::get('/import', 'AnggotaController@import')->name('import');

            Route::group(['prefix' => 'auth'], function () {
                Route::get('/', 'AnggotaAuthController@index')->name('auth.index');
                Route::get('/create', 'AnggotaAuthController@create')->name('auth.create');
                Route::post('/store','AnggotaAuthController@store')->name('auth.store');
                Route::get('/edit/{id}', 'AnggotaAuthController@edit')->name('auth.edit');
                Route::post('/update','AnggotaAuthController@update')->name('auth.update');
                Route::delete('/delete/{id}','AnggotaAuthController@destroy')->name('auth.delete');
            });
            
            Route::get('/{id}/{state?}', 'AnggotaController@show')->name('show');
        });

        
        Route::group(['prefix' => 'simpanan','namespace' => 'Simpanan', 'as' => 'simpanan.'], function () {
            // Route::get('/setoran-wajib', 'SetoranController@wajib')->name('setoran_wajib');
            // Route::get('/setoran-sukarela', 'SetoranController@sukarela')->name('setoran_sukarela');
            // Route::post('/setoran-wajib-store','SetoranController@wajib_store')->name('wajib.store');
            // Route::get('/wajib-paid/{id}', 'SetoranController@wajib_paid')->name('wajib_paid');

            
            Route::group(['prefix' => 'wajib', 'as' => 'wajib.'], function () {
                Route::get('/', 'WajibController@index')->name('index');
                Route::get('/create', 'WajibController@create')->name('create');
                Route::post('/store','WajibController@store')->name('store');
                Route::get('/tunggakan', 'WajibController@tunggakan')->name('tunggakan');
                Route::get('/edit/{id}', 'WajibController@edit')->name('edit');
                Route::post('/update','WajibController@update')->name('update');
                Route::get('/detail/{id}', 'WajibController@show')->name('show');
                Route::get('/paid/{id}', 'WajibController@paid')->name('paid');
            });


            Route::group(['prefix' => 'sukarela/deposit', 'as' => 'sukarela.'], function () {
                Route::get('/', 'SukarelaController@index')->name('index');
                Route::get('/create', 'SukarelaController@create')->name('create');
                Route::post('/store','SukarelaController@store')->name('store');
                Route::get('/edit/{id}', 'SukarelaController@edit')->name('edit');
                Route::post('/update','SukarelaController@update')->name('update');
                Route::get('/detail/{id}', 'SukarelaController@show')->name('show');
            });

            Route::group(['prefix' => 'sukarela/withdraw', 'as' => 'sukarela.withdraw.'], function () {
                Route::get('/', 'SukarelaWithdrawController@index')->name('index');
                Route::get('/create', 'SukarelaWithdrawController@create')->name('create');
                Route::post('/store','SukarelaWithdrawController@store')->name('store');
                Route::get('/edit/{id}', 'SukarelaWithdrawController@edit')->name('edit');
                Route::post('/update','SukarelaWithdrawController@update')->name('update');
                Route::get('/detail/{id}', 'SukarelaWithdrawController@show')->name('show');
            });
        });

        Route::group(['prefix' => 'pembiayaan','namespace' => 'Pembiayaan', 'as' => 'pembiayaan.'], function () {
            Route::group(['prefix' => 'tunai', 'as' => 'tunai.'], function () {
                Route::get('/', 'PmbTunaiController@index')->name('index');
                Route::get('/create', 'PmbTunaiController@create')->name('create');
                Route::post('/store','PmbTunaiController@store')->name('store');
                Route::get('/edit/{id}', 'PmbTunaiController@edit')->name('edit');
                Route::get('/detail/{id}', 'PmbTunaiController@show')->name('show');
                Route::post('/updateState','PmbTunaiController@updateState')->name('updateState');

                Route::group(['prefix' => 'transaksi', 'as' => 'transaksi.'], function () {
                    Route::get('/', 'PmbTunaiTransaksiController@index')->name('index');
                    Route::get('/create', 'PmbTunaiTransaksiController@create')->name('create');
                    Route::post('/store','PmbTunaiTransaksiController@store')->name('store');
                    Route::get('/edit/{id}', 'PmbTunaiTransaksiController@edit')->name('edit');
                    Route::get('/detail/{id}', 'PmbTunaiTransaksiController@show')->name('show');
                });
            });
        });


        Route::group(['prefix' => 'accounting','namespace' => 'Accounting', 'as' => 'accounting.'], function () {


            Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {
                Route::get('/', 'PaymentController@index')->name('index');
                Route::get('/create', 'PaymentController@create')->name('create');
                Route::post('/store','PaymentController@store')->name('store');
                Route::get('/edit/{id}', 'PaymentController@edit')->name('edit');
                Route::get('/detail/{id}', 'PaymentController@show')->name('show');
                Route::post('/updateState','PaymentController@updateState')->name('updateState');
            });


            Route::group(['prefix' => 'config', 'as' => 'config.'], function () {

                Route::group(['prefix' => 'payment-method', 'as' => 'payment_method.'], function () {
                    Route::get('/', 'PaymentMethodController@index')->name('index');
                    Route::get('/create', 'PaymentMethodController@create')->name('create');
                    Route::post('/store','PaymentMethodController@store')->name('store');
                    Route::get('/edit/{id}', 'PaymentMethodController@edit')->name('edit');
                    Route::get('/detail/{id}', 'PaymentMethodController@show')->name('show');
                    Route::get('/delete/{id}', 'PaymentMethodController@delete')->name('delete');
                    Route::get('/data', 'PaymentMethodController@data')->name('data');
                });


                Route::group(['prefix' => 'bank', 'as' => 'bank.'], function () {
                    Route::get('/', 'BankController@index')->name('index');
                    Route::get('/create', 'BankController@create')->name('create');
                    Route::post('/store','BankController@store')->name('store');
                    Route::post('/update','BankController@update')->name('update');
                    Route::get('/edit/{id}', 'BankController@edit')->name('edit');
                    Route::get('/detail/{id}', 'BankController@show')->name('show');
                    Route::delete('/delete/{id}', 'BankController@destroy')->name('delete');
                });

                
                Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
                    Route::get('/', 'AccountController@index')->name('index');
                    Route::get('/create', 'AccountController@create')->name('create');
                    Route::post('/store','AccountController@store')->name('store');
                    Route::get('/edit/{id}', 'AccountController@edit')->name('edit');
                    Route::post('/update','AccountController@update')->name('update');
                    Route::get('/detail/{id}', 'AccountController@show')->name('show');
                    Route::post('/updateState','AccountController@updateState')->name('updateState');
                });
            });

            Route::group(['prefix' => 'reports', 'as' => 'report.'], function () {
                Route::get('/simpanan', 'ReportController@simpanan')->name('simpanan');
                Route::get('/neraca-saldo', 'ReportController@balance_sheet')->name('balance_sheet');
            });
            
            Route::group(['prefix' => 'potong-gaji', 'as' => 'potong_gaji.'], function () {
                Route::get('/', 'PotongGajiController@index')->name('index');
                Route::get('/{anggota_id}', 'PotongGajiController@show')->name('show');
            });

            
            Route::group(['prefix' => 'cash', 'as' => 'cash.'], function () {
                Route::get('/', 'CashController@index')->name('index');
                Route::get('/create', 'CashController@create')->name('create');
                Route::post('/store','CashController@store')->name('store');
                Route::get('/edit/{id}', 'CashController@edit')->name('edit');
                Route::post('/update','CashController@update')->name('update');
                Route::get('/detail/{id}', 'CashController@show')->name('show');
            });


        });
    });
});


Route::get('/get-wilayah', 'GeneralController@getWilayah')->name('wilayah');
// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware('auth')->name('dashboard');

// Route::get('/pages/1', function () {
//     return Inertia::render('Dashboard');
// })->middleware('auth')->name('pages.1.1');

// Route::get('/pages/2', function () {
//     return Inertia::render('Dashboard');
// })->middleware('auth')->name('pages.2.1');
// Route::get('/profile', 'UserController@profile')->middleware('auth')->name('user.profile');

// Route::post('/updateProfile', 'UserController@updateProfile')->middleware('auth')->name('user.profile.update');


// Route::group(['prefix' => 'product', 'middleware' => 'auth','as'=>'product.', 'namespace' => 'Product'], function () {

//     Route::group(['prefix' => 'category'], function () {
//         Route::get('/', 'ProductCategoryController@index')->name('category.index');
//     });
// });