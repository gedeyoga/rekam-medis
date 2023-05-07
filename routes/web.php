<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MedicalRecordController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\PublicController;
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

Route::get('/', function() {
    return redirect()->route('login');
})->name('root');

Route::group(['prefix' => 'admin' , 'middleware' => 'auth'] , function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //users
    Route::resource('users', UserController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('medicalrecords', MedicalRecordController::class);
    Route::get('/users/{user}/profile' , [UserController::class, 'profile'])->name('users.profile');


    Route::resource('roles', RoleController::class);

    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
});

Route::group(['prefix' => 'auth'] , function() {
    Auth::routes();
    // Route::get('/login' , [AuthController::class , 'login'])->name('auth.login');
});
