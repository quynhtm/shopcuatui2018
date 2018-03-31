<?php

/*thông tin Depart product */
Route::get('proDepart/view',array('as' => 'admin.proDepartView','uses' => Product.'\ProDepartmentController@view'));
Route::post('proDepart/addProDepart/{id?}',array('as' => 'admin.addProDepart','uses' => Product.'\ProDepartmentController@addItem'));
Route::get('proDepart/deleteProDepart',array('as' => 'admin.deleteItem','uses' => Product.'\ProDepartmentController@deleteItem'));
Route::post('proDepart/ajaxLoadFormDepart',array('as' => 'admin.loadFormDepart','uses' => Product.'\ProDepartmentController@ajaxLoadFormDepart'));




