<?php

//vi du
Route::get('product', array('as' => 'shop.index','uses' => Shop.'\LoanController@index'));