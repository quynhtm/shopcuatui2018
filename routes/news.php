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

/*thông tin video */
Route::get('video/view',array('as' => 'admin.videoView','uses' => Admin.'\AdminVideoController@view'));
Route::get('video/edit/{id?}', array('as' => 'admin.videoEdit','uses' => Admin.'\AdminVideoController@getItem'));
Route::post('video/edit/{id?}', array('as' => 'admin.videoEdit','uses' => Admin.'\AdminVideoController@postItem'));
Route::post('video/deleteVideo', array('as' => 'admin.deleteVideo','uses' => Admin.'\AdminVideoController@deleteVideo'));//ajax

/*thông tin banner */
Route::get('baner/view',array('as' => 'admin.bannerView','uses' => Admin.'\AdminBannerController@view'));
Route::get('baner/edit/{id?}', array('as' => 'admin.bannerEdit','uses' => Admin.'\AdminBannerController@getItem'));
Route::post('baner/edit/{id?}', array('as' => 'admin.bannerEdit','uses' => Admin.'\AdminBannerController@postItem'));
Route::post('baner/deleteBanner', array('as' => 'admin.deleteBanner','uses' => Admin.'\AdminBannerController@deleteBanner'));//ajax

/*thong tin contact*/
Route::get('contact/view',array('as' => 'admin.contactView','uses' => Admin.'\AdminContactController@view'));
Route::get('contact/edit/{id?}', array('as' => 'admin.contactEdit','uses' => Admin.'\AdminContactController@getItem'));
Route::post('contact/edit/{id?}', array('as' => 'admin.contactEdit','uses' => Admin.'\AdminContactController@postItem'));
Route::post('contact/deleteContact', array('as' => 'admin.deleteContact','uses' => Admin.'\AdminContactController@deleteContact'));//ajax

/*thong tin chung*/

Route::get('info/view',array('as' => 'admin.infoView','uses' => Admin.'\AdminInfoController@view'));
Route::get('info/edit/{id?}', array('as' => 'admin.infoEdit','uses' => Admin.'\AdminInfoController@getItem'));
Route::post('info/edit/{id?}', array('as' => 'admin.infoEdit','uses' => Admin.'\AdminInfoController@postItem'));
Route::post('info/deleteInfo', array('as' => 'admin.deleteInfo','uses' => Admin.'\AdminInfoController@deleteInfo'));//ajax
