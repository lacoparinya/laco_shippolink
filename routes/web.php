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
Route::get('/po-datas/changemainstatus/{id}/{status}', 'PoDatasController@changemainstatus');

Route::get('/uploadtrans/create', 'UploadTransController@create');
Route::post('/uploadtrans/createAction', 'UploadTransController@createAction');
Route::get('/uploadtrans/index', 'UploadTransController@index');
Route::get('/uploadtrans/view/{id}', 'UploadTransController@view');
Route::get('/uploadtrans/edit/{id}', 'UploadTransController@edit');
Route::post('/uploadtrans/editAction/{id}', 'UploadTransController@editAction');
Route::get('/uploadtrans/addnewinv/{bank_trans_m_id}', 'UploadTransController@addnewinv');
Route::post('/uploadtrans/addnewinvAction/{bank_trans_m_id}', 'UploadTransController@addnewinvAction');
Route::get('/uploadtrans/removeinv/{bank_trans_d_id}', 'UploadTransController@removeinv');
Route::delete('/uploadtrans/delete/{id}', 'UploadTransController@destroy');

Route::get('/uploadtrans/testpdf', 'UploadTransController@testpdf');
Route::get('/uploadtrans/genpdf/{id}', 'UploadTransController@processPdf');
