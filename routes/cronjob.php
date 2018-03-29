<?php

//use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('sendSuccess',array('as' => 'api.sendSuccess','uses' => 'Api\ApiSendSuccessController@index'));


Route::match(['GET','POST'],'cronjob/view', array('as' => 'cr.CronjobView','uses' => 'Cronjob\CronjobUserController@view'));
Route::get('cronjob/edit/{id?}',array('as' => 'cr.CronjobEdit','uses' => 'Cronjob\CronjobUserController@getItem'));
Route::post('cronjob/edit/{id?}', array('as' => 'cr.CronjobEdit','uses' => 'Cronjob\CronjobUserController@postItem'));
Route::get('cronjob/deleteCronjob', array('as' => 'cr.deleteCronjob','uses' => 'Cronjob\CronjobUserController@deleteCronjob'));

