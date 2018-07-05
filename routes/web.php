<?php
Auth::routes();

const Admin = "Admin";
const HResources = "Hr";
const Cronjob = "Cronjob";
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

require __DIR__.'/site.php';

//Quan tri CMS cho admin
Route::get('/login', array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@getLogin'));
Route::post('/login',  array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@postLogin'));

Route::group(array('prefix' => 'manager', 'before' => ''), function(){
	require __DIR__.'/admin.php';
});

//Router HR
Route::group(array('prefix' => 'manager', 'before' => ''), function(){
	require __DIR__.'/hr.php';
});

//Router Api
Route::group(array('prefix' => 'api', 'before' => ''), function () {
    require __DIR__.'/api.php';
});

//Router Api
Route::group(array('prefix' => 'cronjob', 'before' => ''), function () {
    require __DIR__.'/cronjob.php';
});

//Router Ajax
Route::group(array('prefix' => 'ajax', 'before' => ''), function () {
    Route::post('upload', array('as' => 'ajax.upload','uses' => 'AjaxUploadController@upload'));
});

Route::get('sentmail/mail',array('as' => 'admin.mail','uses' => 'MailSendController@sentEmail'));