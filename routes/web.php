<?php
Auth::routes();

const Admin = "Admin";
const Shop = "Shop";

// Used for dev by Quynh
$isDev = Request::get('is_debug','');
if($isDev == 'tech_code'){
    Session::put('is_debug_of_tech', '13031984');
    Config::set('compile.debug',true);
}
if(Session::has('is_debug_of_tech')){
    Config::set('compile.debug',true);
}

require __DIR__.'/site.php';

//Quan tri CMS cho admin
Route::get('/quan-tri.html', array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@getLogin'));
Route::post('/quan-tri.html',  array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@postLogin'));

Route::group(array('prefix' => 'manager', 'before' => ''), function(){
	require __DIR__.'/admin.php';
});

Route::group(array('prefix' => 'manager', 'before' => ''), function(){
	require __DIR__.'/shop.php';
});

//Router Api
Route::group(array('prefix' => 'api', 'before' => ''), function () {
    require __DIR__.'/api.php';
});

//Router Ajax
Route::group(array('prefix' => 'ajax', 'before' => ''), function () {
    Route::post('upload', array('as' => 'ajax.upload','uses' => 'AjaxUploadController@upload'));
});
