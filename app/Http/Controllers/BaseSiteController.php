<?php

namespace App\Http\Controllers;

class BaseSiteController extends Controller{

	public function __construct(){}
    public function head(){
        return view('site_itomedic.common.head');
    }
	public function header($catid=0){
        return view('site_itomedic.common.header');
	}
    public function slider(){
        return view('site_itomedic.common.slide');
    }
    public function footer(){
        return view('site_itomedic.common.footer');
	}
    public function master(){
        return view('site_itomedic.common.master');
    }
	public function page403(){
		echo '403';
	}
	public function page404(){
		echo '404';
	}
}  