<?php

/*thông tin danh mục tin tức */
Route::get('categoryNews/view',array('as' => 'admin.categoryNews','uses' => News.'\CategoryNewsController@view'));
Route::get('categoryNews/edit/{id?}', array('as' => 'admin.categoryNewsEdit','uses' => News.'\CategoryNewsController@getItem'));
Route::post('categoryNews/edit/{id?}', array('as' => 'admin.categoryNewsEdit','uses' => News.'\CategoryNewsController@postItem'));
Route::post('categoryNews/deleteCategoryNews', array('as' => 'admin.deletecategoryNews','uses' => News.'\CategoryNewsController@deleteCategoryNews'));//ajax

/*thông tin tin tức */
Route::get('news/view',array('as' => 'admin.newsView','uses' => News.'\NewsController@view'));
Route::get('news/edit/{id?}', array('as' => 'admin.newsEdit','uses' => News.'\NewsController@getItem'));
Route::post('news/edit/{id?}', array('as' => 'admin.newsEdit','uses' => News.'\NewsController@postItem'));
Route::post('news/deleteNews', array('as' => 'admin.deleteNews','uses' => News.'\NewsController@deleteNews'));//ajax

/// /*thông tin sản phẩm*/
Route::get('news/viewProduct',array('as' => 'admin.newsViewProduct','uses' => News.'\NewsController@viewProduct'));
Route::get('news/editProduct/{id?}', array('as' => 'admin.newsEditProduct','uses' => News.'\NewsController@getItemProduct'));
Route::post('news/editProduct/{id?}', array('as' => 'admin.newsEditProduct','uses' => News.'\NewsController@postItemProduct'));


