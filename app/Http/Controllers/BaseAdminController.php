<?php
/*
* @Created by: HaiAnhEm
* @Author    : HaiAnhEm
* @Date      : 01/2018
* @Version   : 1.0
*/

namespace App\Http\Controllers;

use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use App\Http\Models\Admin\MenuSystem;
use Illuminate\Support\Facades\Route;
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
    protected $is_tech = false;
    protected $user_id = 0;
    protected $user_project = 0;
    protected $user_object_id = 0;
    protected $user_depart_id = 0;
    protected $user_name = '';
    protected $tab_top = '';
    protected $role_type = 0;
    protected $languageSite = VIETNAM_LANGUAGE;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!app('App\Http\Models\Admin\User')->isLogin()) {
                Redirect::route('admin.login')->send();
            }
            $this->user = app('App\Http\Models\Admin\User')->user_login();
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
            if (in_array('is_boss', $this->permission) || $this->user['is_boss'] == CGlobal::status_hide) {
                $this->is_boss = true;
            }
            if (in_array('root', $this->permission)) {
                $this->is_root = true;
            }
            if (in_array('is_tech', $this->permission)) {
                $this->is_tech = true;
            }
            $this->is_root = ($this->is_boss) ? true : $this->is_root;
            $this->is_tech = ($this->is_boss) ? true : $this->is_tech;

            //menu tab top
            $tab_top = isset($_GET['tab_top']) ? $_GET['tab_top'] : 0;
            if ($tab_top != 0) {
                $request->session()->put('menu_tab_top', $tab_top, 60 * 24);
                $this->tab_top = $tab_top;
            } else {
                $this->tab_top = Session::get('menu_tab_top');
            }

            $arrMenu = $this->getMenuSystem();
            if (!empty($arrMenu)) {
                foreach ($arrMenu as $menu_id => $menu) {
                    if ($menu['show_menu'] == CGlobal::status_show && ($menu['menu_tab_top_id'] == $this->tab_top || $menu['menu_tab_top_id'] == STATUS_HIDE)) {
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
            //vmDebug($this->menuSystem);
            $error = isset($_GET['error']) ? $_GET['error'] : 0;
            $msg = array();
            if ($error == ERROR_PERMISSION) {
                $msg[] = 'Bạn không có quyền truy cập';
                View::share('error', $msg);
            }

            //Get lang
            if (isset($_GET['lang']) && (int)$_GET['lang'] > 0) {
                $get_lang = $_GET['lang'];
                $lang = (isset(CGlobal::$arrLanguage[$get_lang])) ? $get_lang : $this->languageSite;
                $request->session()->put('languageSite', $lang, CACHE_ONE_MONTH);
            }
            $this->languageSite = (Session::has('languageSite')) ? Session::get('languageSite') : $this->languageSite;

            View::share('languageSite', $this->languageSite);
            View::share('menu', $this->menuSystem);
            View::share('aryPermissionMenu', $this->user_group_menu);
            View::share('is_root', $this->is_root);
            View::share('is_boss', $this->is_boss);
            View::share('is_tech', $this->is_tech);
            View::share('role_type', $this->role_type);
            View::share('user_id', $this->user_id);
            View::share('user_depart_id', $this->user_depart_id);
            View::share('user_object_id', $this->user_object_id);
            View::share('user_project', $this->user_project);
            View::share('user_name', $this->user_name);
            View::share('user', $this->user);
            View::share('arrMenuTabTop', CGlobal::$arrMenuTabTop);
            View::share('tab_top', $this->tab_top);

            View::share('newMailInbox', 0);

            return $next($request);
        });
    }

    public function getMenuSystem()
    {
        $menuTree = MenuSystem::buildMenuAdmin();
        return $menuTree;
    }

    public function getRouterNameSite(){
        $route_name = [];
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            if (array_key_exists('as', $action)) {
                $route_name[] = $action['as'];
            }
        }
        return $route_name;
    }

    public function getControllerAction()
    {
        return $routerName = Route::currentRouteName();
    }

    /**
     * @param string $permiss
     * @return bool
     */
    public function checkPermiss($permiss = '')
    {
        return ($permiss != '') ? in_array($permiss, $this->permission) : false;
    }

/**
 * @param array $arrPermiss
 * @return bool
 */
public function checkMultiPermiss($arrPermiss = []){
    if($this->is_root) return true;
    if(empty($arrPermiss)) return false;
    foreach ($arrPermiss as $permiss){
        return $this->checkPermiss($permiss);
    }
    return false;
}
}