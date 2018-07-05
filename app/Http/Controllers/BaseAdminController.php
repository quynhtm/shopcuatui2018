<?php
/*
* @Created by: HaiAnhEm
* @Author    : HaiAnhEm
* @Date      : 01/2018
* @Version   : 1.0
*/

namespace App\Http\Controllers;

use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use App\Http\Models\Admin\User;
use App\Http\Models\Admin\MenuSystem;
use Illuminate\Support\Facades\Session;
use View;
use App\Library\AdminFunction\FunctionLib;

class BaseAdminController extends Controller
{
    protected $permission = array();
    protected $user = array();
    protected $menuSystem = array();
    protected $user_group_menu = array();
    protected $is_root = false;
    protected $is_boss = false;
    protected $user_id = 0;
    protected $user_project = 0;
    protected $user_object_id = 0;
    protected $user_depart_id = 0;
    protected $user_name = '';
    protected $role_type = Define::ROLE_TYPE_CUSTOMER;
    protected $languageSite = Define::VIETNAM_LANGUAGE;

    public function __construct(){

        $this->middleware(function ($request, $next) {
            if (!User::isLogin()) {
                Redirect::route('admin.login')->send();
            }
            $this->user = User::user_login();
            if (!empty($this->user)) {
                if (sizeof($this->user['user_permission']) > 0) {
                    $this->permission = $this->user['user_permission'];
                }
                if (trim($this->user['user_group_menu']) != '') {
                    $this->user_group_menu = explode(',', $this->user['user_group_menu']);
                }
                if (isset($this->user['role_type']) && trim($this->user['role_type'])) {
                    $this->role_type = $this->user['role_type'];
                }
                if (isset($this->user['user_depart_id']) && trim($this->user['user_depart_id'])) {
                    $this->user_depart_id = $this->user['user_depart_id'];
                }
                if (isset($this->user['user_id']) && trim($this->user['user_id'])) {
                    $this->user_id = $this->user['user_id'];
                    $this->user_name = $this->user['user_name'];
                    $this->user_object_id = $this->user['user_object_id'];
                    $this->user_project = $this->user['user_project'];
                }
            }
            if (in_array('is_boss', $this->permission) || $this->user['user_view'] == CGlobal::status_hide) {
                $this->is_boss = true;
            }
            if (in_array('root', $this->permission)) {
                $this->is_root = true;
            }
            $this->is_root = ($this->is_boss) ? true : $this->is_root;

            $arrMenu = array();
            if ($this->is_boss) {
                $this->menuSystem = $this->getMenuSystem();
            } else {
                $arrMenu = $this->getMenuSystem();
            }
            if (!empty($arrMenu)) {
                foreach ($arrMenu as $menu_id => $menu) {
                    if ($menu['show_menu'] == CGlobal::status_show) {
                        if (!empty($menu['sub'])) {
                            $checkMenu = false;
                            foreach ($menu['sub'] as $ks => $sub) {
                                if (!empty($this->user_group_menu) && in_array($sub['menu_id'], $this->user_group_menu)) {
                                    $checkMenu = true;
                                }
                            }
                            if ($checkMenu) {
                                $this->menuSystem[$menu_id] = $menu;
                            }
                        }
                    }
                }
            }

            $error = isset($_GET['error']) ? $_GET['error'] : 0;
            $msg = array();
            if ($error == Define::ERROR_PERMISSION) {
                $msg[] = 'Bạn không có quyền truy cập';
                View::share('error', $msg);
            }

            //Get lang
            if (isset($_GET['lang']) && (int)$_GET['lang'] > 0) {
                $get_lang = $_GET['lang'];
                $lang = (isset(Define::$arrLanguage[$get_lang])) ? $get_lang : $this->languageSite;
                $request->session()->put('languageSite', $lang, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
            $this->languageSite = (Session::has('languageSite')) ? Session::get('languageSite') : $this->languageSite;

            View::share('languageSite', $this->languageSite);
            View::share('menu', $this->menuSystem);
            View::share('aryPermissionMenu', $this->user_group_menu);
            View::share('is_root', $this->is_root);
            View::share('is_boss', $this->is_boss);
            View::share('role_type', $this->role_type);
            View::share('user_id', $this->user_id);
            View::share('user_depart_id', $this->user_depart_id);
            View::share('user_object_id', $this->user_object_id);
            View::share('user_project', $this->user_project);
            View::share('user_name', $this->user_name);
            View::share('user', $this->user);

            //Count mail notify
            $newMailInbox = $this->countMailNotify();
            View::share('newMailInbox', $newMailInbox);

            return $next($request);
        });
    }

    public function getMenuSystem(){
        $menuTree = MenuSystem::buildMenuAdmin();
        return $menuTree;
    }

    public function getControllerAction(){
        return $routerName = Route::currentRouteName();
    }
    public function countMailNotify(){
        $count = 0;
        if(Cache::has(Define::CACHE_HR_MAIL_COUNT_NEW_INBOX . $this->user['user_id'])){
            $count = (Define::CACHE_ON) ? Cache::get(Define::CACHE_HR_MAIL_COUNT_NEW_INBOX . $this->user['user_id']) : 0;
        }
        return $count;
    }
}