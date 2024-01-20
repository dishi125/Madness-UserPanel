<?php

use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/',[\App\Http\Controllers\AuthController::class,'index'])->name('login');
Route::post('postlogin', [\App\Http\Controllers\AuthController::class, 'postLogin'])->name('postlogin');
Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('register',[\App\Http\Controllers\AuthController::class,'register'])->name('register');
Route::post('register/payment',[\App\Http\Controllers\AuthController::class,'pay'])->name('register.payment');
Route::post('register/payment/status', [\App\Http\Controllers\AuthController::class,'paymentCallback'])->name('register.status');

Route::get('forget_password', [\App\Http\Controllers\ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget_password', [\App\Http\Controllers\ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset_password/{token}', [\App\Http\Controllers\ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset_password', [\App\Http\Controllers\ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::group(['middleware'=>'auth'],function (){
    Route::get('dashboard',[\App\Http\Controllers\DashboardController::class,'index'])->name('dashboard');

    Route::get('monthly_commissions',[\App\Http\Controllers\CommissionController::class,'index'])->name('monthly_commissions.list');
    Route::post('allMonthlyCommissionlist',[\App\Http\Controllers\CommissionController::class,'allMonthlyCommissionlist'])->name('allMonthlyCommissionlist');
    Route::get('viewMonthlyCommission/{id}',[\App\Http\Controllers\CommissionController::class,'viewMonthlyCommission'])->name('monthly_commissions.view');
    Route::post('allCommissionlist',[\App\Http\Controllers\CommissionController::class,'allCommissionlist'])->name('allCommissionlist');

    Route::get('profile',[\App\Http\Controllers\UserController::class,'index'])->name('profile.view');
    Route::get('user/{id}/edit',[\App\Http\Controllers\UserController::class,'edituser'])->name('profile.edit');
    Route::post('updateUser',[\App\Http\Controllers\UserController::class,'updateUser'])->name('profile.save');
    Route::get('user_password/{id}/edit',[\App\Http\Controllers\UserController::class,'editpassword'])->name('password.edit');
    Route::post('updatePassword',[\App\Http\Controllers\UserController::class,'updatePassword'])->name('password.save');

    Route::get('commission_report',[\App\Http\Controllers\CommissionReportController::class,'index'])->name('commission_report.list');
    Route::post('allCommissionReportlist',[\App\Http\Controllers\CommissionReportController::class,'allCommissionReportlist'])->name('allCommissionReportlist');
});

