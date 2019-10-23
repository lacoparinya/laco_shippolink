<?php

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

Route::get('/', 'PoDatasController@index');

Auth::routes();


Route::resource('ship-datas', 'ShipDatasController');
Route::resource('sap-data-cfs', 'SapDataCfsController');
Route::resource('po-datas', 'PoDatasController');
Route::resource('file-uploads', 'FileUploadsController');

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/imports/shipdata', 'ImportsController@shipdata');
Route::post('/imports/processAction', 'ImportsController@processAction');
Route::get('/imports/sapcfsdata', 'ImportsController@sapcfsdata');
Route::get('/imports/podata', 'ImportsController@podata');
Route::get('/po-datas/manualProcess/{id}', 'PoDatasController@manualProcess');
Route::get('/imports/AllProcess', 'PoDatasController@AllProcess');
Route::get('/imports/AllProcessCf', 'PoDatasController@AllProcessCf');
Route::get('/po-datas/changestatus/{id}', 'PoDatasController@changestatus');
