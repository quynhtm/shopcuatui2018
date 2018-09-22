<?php

//vi du
Route::get('product', array('as' => 'shop.index','uses' => Shop.'\LoanController@index'));

Route::get('department', array('as' => 'shop.department','uses' => Shop.'\DepartmentController@view'));
Route::post('department/post/{id?}', array('as' => 'shop.departmentGet','uses' => Shop.'\DepartmentController@postItem'))->where('id', '[0-9]+');
Route::get('department/delete',array('as' => 'shop.departmentDelete','uses' => Shop.'\DepartmentController@deleteItem'));
Route::post('department/ajaxLoad', array('as' => 'shop.departmentAjax','uses' => Shop.'\DepartmentController@ajaxLoadForm'));