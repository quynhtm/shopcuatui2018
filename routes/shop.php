<?php

//vi du
Route::get('product', array('as' => 'shop.index','uses' => Shop.'\LoanController@index'));

Route::get('department', array('as' => 'shop.department','uses' => Shop.'\DepartmentController@view'));
Route::post('department/post/{id?}', array('as' => 'shop.departmentGet','uses' => Shop.'\DepartmentController@postItem'))->where('id', '[0-9]+');
Route::get('department/delete',array('as' => 'shop.departmentDelete','uses' => Shop.'\DepartmentController@deleteItem'));
Route::post('department/ajaxLoad', array('as' => 'shop.departmentAjax','uses' => Shop.'\DepartmentController@ajaxLoadForm'));

Route::get('infosale', array('as' => 'shop.infosale','uses' => Shop.'\InfosaleController@view'));
Route::get('infosale/get/{id?}', array('as' => 'shop.infosaleGet','uses' => Shop.'\InfosaleController@getItem'))->where('id', '[0-9]+');
Route::post('infosale/post/{id?}', array('as' => 'shop.infosalePost','uses' => Shop.'\InfosaleController@postItem'))->where('id', '[0-9]+');
Route::get('infosale/delete',array('as' => 'shop.infosaleDelete','uses' => Shop.'\InfosaleController@deleteItem'));