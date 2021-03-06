<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('stokbarang','StokBarangController');

Route::post('/barangmasuk','StokBarangController@barangMasuk');
Route::post('/barangkeluar','StokBarangController@barangKeluar');

//
//\Illuminate\Support\Facades\Route::resource('barangmasuk','BarangMasukController');
//\Illuminate\Support\Facades\Route::resource('barangkeluar','BarangKeluarController');
