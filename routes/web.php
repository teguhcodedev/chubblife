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

Route::get('/','AuthManageController@viewLogin')->name('login');

Route::get('/login', 'AuthManageController@viewLogin')->middleware("throttle:10,2")->name('login');
Route::post('/verify_login', 'AuthManageController@verifyLogin');
Route::post('/first_account', 'UserManageController@firstAccount');

Route::group(['middleware' => ['auth', 'checkRole:admin,spv,manager,tmo,QA,customer,TMO,asisten manager']], function(){
	Route::get('/logout', 'AuthManageController@logoutProcess');
	Route::get('/chubblife-dashboard', 'ChubblifeDashboard@viewDashboard');

	// ------------------------- Setting Menu -------------------------
	// > Akun
	Route::get('/setting-menu', 'ChubblifeDashboard@settingMenu');

		// ------------------------- Kelola Akun -------------------------
	// > Akun
	Route::get('/account', 'Chubblife\UserManageController@viewAccount');
	Route::get('/account/new', 'Chubblife\UserManageController@viewNewAccount');
	Route::post('/account/create', 'Chubblife\UserManageController@createAccount');
	Route::get('/account/edit/{id}', 'Chubblife\UserManageController@editAccount');

	Route::get('/chubblife/edit/{id}', 'Chubblife\UserManageController@editAccount');
	Route::post('/account/update/{id}', 'Chubblife\UserManageController@updateAccount');
	
	Route::get('/account/delete/{id}', 'Chubblife\UserManageController@deleteAccount');
	Route::get('/account/filter/{id}', 'Chubblife\UserManageController@filterTable');

	Route::get('/account/getspv', 'Chubblife\UserManageController@getspv')->name('getspv');
    Route::get('/account/getmanager', 'Chubblife\UserManageController@getmanager')->name('getmanager');
	Route::get('chubblife/edit/getspv', 'Chubblife\UserManageController@getspv')->name('/chubblife/edit/getspv');
    Route::get('/chubblife/getmanager', 'Chubblife\UserManageController@getmanager')->name('getmanager');

	// ------------------------- Fitur Cari -------------------------
	Route::get('/search/{word}', 'SearchManageController@searchPage');
	// ------------------------- Profil -------------------------
	Route::get('/profile', 'ProfileManageController@viewProfile');
	Route::post('/profile/update/data', 'ProfileManageController@changeData');
	Route::post('/profile/update/password', 'ProfileManageController@changePassword');
	Route::post('/profile/update/picture', 'ProfileManageController@changePicture');


});

// Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');