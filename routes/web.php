<?php
Auth::routes();

const Admin = "Admin";
const HResources = "Hr";
const News = "News";
const Product = "Product";

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
Route::get('/quan-tri.html', array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@getLogin'));
Route::match(['GET','POST'], 'quan-tri.html', array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@postLogin'));

//Router Admin
Route::group(array('prefix' => 'manager', 'before' => ''), function(){
	require __DIR__.'/admin.php';
});

//Router News
Route::group(array('prefix' => 'manager', 'before' => ''), function(){
    require __DIR__.'/news.php';
});

//Router Product
Route::group(array('prefix' => 'manager', 'before' => ''), function(){
    require __DIR__.'/product.php';
});

//Router Api
Route::group(array('prefix' => 'api', 'before' => ''), function () {
    require __DIR__.'/api.php';
});

//Router Cronjob
Route::group(array('prefix' => 'cronjob', 'before' => ''), function () {
    require __DIR__.'/cronjob.php';
});

//Router Ajax
Route::group(array('prefix' => 'ajax', 'before' => ''), function () {
    Route::post('upload', array('as' => 'ajax.upload','uses' => 'AjaxUploadController@upload'));
});

Route::get('sentmail/mail',array('as' => 'admin.mail','uses' => 'MailSendController@sentEmail'));