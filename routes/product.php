<?php
/*thông tin product */
Route::get('product',array('as' => 'admin.productView','uses' => Product.'\ProductController@view'));

/*thông tin Depart product */
Route::get('proDepart/view',array('as' => 'admin.proDepartView','uses' => Product.'\ProDepartmentController@view'));
Route::post('proDepart/addProDepart/{id?}',array('as' => 'admin.addProDepart','uses' => Product.'\ProDepartmentController@addItem'));
Route::get('proDepart/deleteProDepart',array('as' => 'admin.deleteItem','uses' => Product.'\ProDepartmentController@deleteItem'));
Route::post('proDepart/ajaxLoadFormDepart',array('as' => 'admin.loadFormDepart','uses' => Product.'\ProDepartmentController@ajaxLoadFormDepart'));


/*Quan Ly hệ thống đơn hàng*/
Route::get('managerOrder',array('as' => 'admin.managerOrderView','uses' => Product.'ManagerOrderController@view'));
Route::get('managerOrder/detailOrder/{order_id}', array('as' => 'admin.detailOrder','uses' => Product.'ManagerOrderController@detailOrder'))->where('order_id', '[0-9]+');
Route::post('managerOrder/deleteOrder', array('as' => 'admin.deleteOrder','uses' => Product.'ManagerOrderController@deleteOrder'));
Route::get('managerOrder/getInforProduct', array('as' => 'admin.getInforProduct','uses' => Product.'ManagerOrderController@getInforProduct'));
Route::get('managerOrder/addOrder/{order_id?}', array('as' => 'admin.addOrder','uses' => Product.'ManagerOrderController@getOrder'))->where('order_id', '[0-9]+');
Route::post('managerOrder/addOrder/{order_id?}', array('as' => 'admin.addOrder','uses' => Product.'ManagerOrderController@postOrder'))->where('order_id', '[0-9]+');

