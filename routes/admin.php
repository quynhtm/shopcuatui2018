<?php

Route::get('logout', array('as' => 'admin.logout','uses' => Admin.'\AdminLoginController@logout'));
Route::get('dashboard', array('as' => 'admin.dashboard','uses' => Admin.'\AdminDashBoardController@dashboard'));
Route::get('dashboard/infoEdit', array('as' => 'admin.infoEdit','uses' => Admin.'\AdminSystemSettingController@getInfoEdit'));
Route::post('dashboard/infoEdit/{id?}', array('as' => 'admin.infoEdit','uses' => Admin.'\AdminSystemSettingController@postInfoEdit'));

/*thông tin tài khoản*/
Route::match(['GET','POST'],'user/view', array('as' => 'admin.user_view','uses' => Admin.'\AdminUserController@view'));
Route::get('user/edit/{id}',array('as' => 'admin.user_edit','uses' => Admin.'\AdminUserController@editInfo'));
Route::post('user/edit/{id}',array('as' => 'admin.user_edit','uses' => Admin.'\AdminUserController@edit'));
Route::get('user/change/{id}',array('as' => 'admin.user_change','uses' => Admin.'\AdminUserController@changePassInfo'));
Route::post('user/change/{id}',array('as' => 'admin.user_change','uses' => Admin.'\AdminUserController@changePass'));
Route::post('user/remove/{id}',array('as' => 'admin.user_remove','uses' => Admin.'\AdminUserController@remove'));
Route::get('user/getInfoSettingUser', array('as' => 'admin.getInfoSettingUser','uses' => Admin.'\AdminUserController@getInfoSettingUser'));//ajax
Route::post('user/submitInfoSettingUser', array('as' => 'admin.submitInfoSettingUser','uses' => Admin.'\AdminUserController@submitInfoSettingUser'));//ajax

/*thông tin quyền*/
Route::match(['GET','POST'],'permission/view',array('as' => 'admin.permission_view','uses' => Admin.'\AdminPermissionController@view'));
Route::get('permission/addPermit',array('as' => 'admin.addPermit','uses' => Admin.'\AdminPermissionController@addPermit'));
Route::get('permission/create',array('as' => 'admin.permission_create','uses' => Admin.'\AdminPermissionController@createInfo'));
Route::post('permission/create',array('as' => 'admin.permission_create','uses' => Admin.'\AdminPermissionController@create'));
Route::get('permission/edit/{id}',array('as' => 'admin.permission_edit','uses' => Admin.'\AdminPermissionController@editInfo'))->where('id', '[0-9]+');
Route::post('permission/edit/{id}',array('as' => 'admin.permission_edit','uses' => Admin.'\AdminPermissionController@edit'))->where('id', '[0-9]+');
Route::post('permission/deletePermission', array('as' => 'admin.deletePermission','uses' => Admin.'\AdminPermissionController@deletePermission'));//ajax


/*thông tin nhóm quyền*/
Route::match(['GET','POST'],'groupUser/view',array('as' => 'admin.groupUser_view','uses' => Admin.'\AdminGroupUserController@view'));
Route::get('groupUser/create',array('as' => 'admin.groupUser_create','uses' => Admin.'\AdminGroupUserController@createInfo'));
Route::post('groupUser/create',array('as' => 'admin.groupUser_create','uses' => Admin.'\AdminGroupUserController@create'));
Route::get('groupUser/edit/{id?}',array('as' => 'admin.groupUser_edit','uses' => Admin.'\AdminGroupUserController@editInfo'))->where('id', '[0-9]+');
Route::post('groupUser/edit/{id?}',array('as' => 'admin.groupUser_edit','uses' => Admin.'\AdminGroupUserController@edit'))->where('id', '[0-9]+');
Route::post('groupUser/remove/{id}',array('as' => 'admin.groupUser_remove','uses' => Admin.'\AdminGroupUserController@remove'));
/*thông tin quyền theo role */
Route::get('groupUser/viewRole',array('as' => 'admin.viewRole','uses' => Admin.'\AdminGroupUserController@viewRole'));
Route::get('groupUser/editRole/{id?}', array('as' => 'admin.editRole','uses' => Admin.'\AdminGroupUserController@getRole'));
Route::post('groupUser/editRole/{id?}', array('as' => 'admin.editRole','uses' => Admin.'\AdminGroupUserController@postRole'));

/*thông tin role */
Route::get('role/view',array('as' => 'admin.roleView','uses' => Admin.'\AdminRoleController@view'));
Route::post('role/addRole/{id?}',array('as' => 'admin.addRole','uses' => Admin.'\AdminRoleController@addRole'));
Route::get('role/deleteRole',array('as' => 'admin.deleteRole','uses' => Admin.'\AdminRoleController@deleteRole'));
Route::post('role/ajaxLoadForm',array('as' => 'admin.loadForm','uses' => Admin.'\AdminRoleController@ajaxLoadForm'));

/*thông tin menu */
Route::get('menu/view',array('as' => 'admin.menuView','uses' => Admin.'\AdminManageMenuController@view'));
Route::get('menu/edit/{id?}', array('as' => 'admin.menuEdit','uses' => Admin.'\AdminManageMenuController@getItem'));
Route::post('menu/edit/{id?}', array('as' => 'admin.menuEdit','uses' => Admin.'\AdminManageMenuController@postItem'));
Route::post('menu/deleteMenu', array('as' => 'admin.deleteMenu','uses' => Admin.'\AdminManageMenuController@deleteMenu'));//ajax

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

