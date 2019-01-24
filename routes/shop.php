<?php
//provider
Route::get('provider', array('as' => 'shop.provider','uses' => Shop.'\ProviderController@view'));
Route::post('provider/post/{id?}', array('as' => 'shop.providerGet','uses' => Shop.'\ProviderController@postItem'))->where('id', '[0-9]+');
Route::get('provider/delete',array('as' => 'shop.providerDelete','uses' => Shop.'\ProviderController@deleteItem'));
Route::post('provider/ajaxLoad', array('as' => 'shop.providerAjax','uses' => Shop.'\ProviderController@ajaxLoadForm'));

//department
Route::get('department', array('as' => 'shop.department','uses' => Shop.'\DepartmentController@view'));
Route::post('department/post/{id?}', array('as' => 'shop.departmentGet','uses' => Shop.'\DepartmentController@postItem'))->where('id', '[0-9]+');
Route::get('department/delete',array('as' => 'shop.departmentDelete','uses' => Shop.'\DepartmentController@deleteItem'));
Route::post('department/ajaxLoad', array('as' => 'shop.departmentAjax','uses' => Shop.'\DepartmentController@ajaxLoadForm'));

//infosale
Route::get('infosale', array('as' => 'shop.infosale','uses' => Shop.'\InfosaleController@view'));
Route::get('infosale/get/{id?}', array('as' => 'shop.infosaleGet','uses' => Shop.'\InfosaleController@getItem'))->where('id', '[0-9]+');
Route::post('infosale/get/{id?}', array('as' => 'shop.infosalePost','uses' => Shop.'\InfosaleController@postItem'))->where('id', '[0-9]+');
Route::get('infosale/delete',array('as' => 'shop.infosaleDelete','uses' => Shop.'\InfosaleController@deleteItem'));

//order
Route::get('order', array('as' => 'shop.order','uses' => Shop.'\OrderController@view'));
Route::get('order/get/{id?}', array('as' => 'shop.orderGet','uses' => Shop.'\OrderController@getItem'))->where('id', '[0-9]+');
Route::post('order/get/{id?}', array('as' => 'shop.orderPost','uses' => Shop.'\OrderController@postItem'))->where('id', '[0-9]+');
Route::get('order/delete',array('as' => 'shop.orderDelete','uses' => Shop.'\OrderController@deleteItem'));

//*thông tin Product */
Route::match(['GET','POST'],'product',array('as' => 'shop.productView','uses' => Shop.'\ProductController@view'));
Route::get('product/edit/{id?}', array('as' => 'shop.productEdit','uses' => Shop.'\ProductController@getItem'));
Route::post('product/edit/{id?}', array('as' => 'shop.productEdit','uses' => Shop.'\ProductController@postItem'));
Route::post('product/deleteProduct', array('as' => 'shop.deleteProduct','uses' => Shop.'\ProductController@deleteProduct'));//ajax

//danh mục category
Route::get('category', array('as' => 'shop.category','uses' =>  Shop.'\CategoryController@view'));
Route::get('category/get/{id?}', array('as' => 'shop.categoryGet','uses' => Shop.'\CategoryController@getItem'))->where('id', '[0-9]+');
Route::post('category/get/{id?}', array('as' => 'shop.categoryPost','uses' => Shop.'\CategoryController@postItem'))->where('id', '[0-9]+');
Route::get('category/delete', array('as' => 'shop.categoryDelete','uses' => Shop.'\CategoryController@deleteItem'));


Route::post('category/updateStatusCategory', array('as' => 'admin.status_category_post','uses' => Shop.'\CategoryController@updateStatusCategory'));
Route::post('category/updatePositionStatusCategory', array('as' => 'admin.status_category_position','uses' => Shop.'\CategoryController@updatePositionStatusCategory'));
