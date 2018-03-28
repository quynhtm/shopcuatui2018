<?php
Auth::routes();

const Admin = "Admin";
const Site = "Site";
const News = "News";

// Used for dev by Quynh
$isDev = Request::get('is_debug','');
if($isDev == 'tech_code'){
    Session::put('is_debug_of_tech', '13031984');
    Config::set('compile.debug',true);
}
if(Session::has('is_debug_of_tech')){
    Config::set('compile.debug',true);
}

//Quan tri CMS cho admin
Route::get('/quantri.html', array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@getLogin'));
Route::match(['GET','POST'], '/quantri.html', array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@postLogin'));

Route::group(array('prefix' => 'manager', 'before' => ''), function(){
	require __DIR__.'/admin.php';
});

//News
Route::group(array('prefix' => 'manager', 'before' => ''), function(){
	require __DIR__.'/news.php';
});

//Router Api
Route::group(array('prefix' => '/', 'before' => ''), function () {
    require __DIR__.'/site.php';
});

//Router Api
Route::group(array('prefix' => 'api', 'before' => ''), function () {
    require __DIR__.'/api.php';
});

//Router Ajax
Route::group(array('prefix' => 'ajax', 'before' => ''), function () {
    Route::post('upload', array('as' => 'ajax.upload','uses' => 'AjaxUploadController@upload'));
});
