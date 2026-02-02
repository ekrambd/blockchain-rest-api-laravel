<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [IndexController::class, 'indexPage'])->name('index.page');

Route::post('admin-login', [AccessController::class, 'adminLogin'])->name('admin.login');

Route::get('/admin-logout', [AccessController::class, 'adminLogout'])->name('admin.logout');

Route::group(['middleware' => 'prevent-back-history'],function(){
   //dashboard
	Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

// Route::get('/', function(){
//    $data = array();
//    $data['name'] = 'Admin';
//    $data['role_id'] = 1;
//    $data['email'] = 'admin@gmail.com';
//    $data['password'] = bcrypt('123456');
//    DB::table('users')->insert($data);
//    return back();
// });