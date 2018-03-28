<?php

//Index
Route::any('/', array('as' => 'site_itomedic.home','uses' => Site.'\IndexController@index'));
Route::any('/gioi-thieu.html', array('as' => 'site_itomedic.gioi_thieu','uses' => Site.'\IntroduceController@index'));
Route::any('/san-pham.html', array('as' => 'site_itomedic.san_pham','uses' => Site.'\ProductController@index'));
Route::any('/showroom.html', array('as' => 'site_itomedic.showroom','uses' => Site.'\ShowroomController@index'));
Route::any('/dich-vu-kham-chua-benh.html', array('as' => 'site_itomedic.dich_vu_kham_chua_benh','uses' => Site.'\ServiceController@index'));
Route::any('/dao-tao-y-te.html', array('as' => 'site_itomedic.dao_tao_y_te','uses' => Site.'\TrainingController@index'));
Route::any('/nhat-ban-ngay-nay.html', array('as' => 'site_itomedic.nhat_ban_ngay_nay','uses' => Site.'\JapanCurrentController@index'));
Route::any('/lien-he.html', array('as' => 'site_itomedic.lien_he','uses' => Site.'\ContactController@index'));

