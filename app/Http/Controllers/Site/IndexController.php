<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 08/2016
* @Version	 : 1.0
*/

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseSiteController;

class IndexController extends BaseSiteController{
	
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$this->header();
		$this->footer();
		$this->master();
        return view('site_itomedic.home',[
                'user'=>array()]
        );
	}
    public function actionRouter($catname, $catid){
        
    }
}
