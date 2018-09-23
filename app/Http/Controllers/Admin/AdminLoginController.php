<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/

namespace App\Http\Controllers\Admin;

use App\Http\Models\Admin\MemberSite;
use App\Library\AdminFunction\Curl;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Http\Models\Admin\User;
use App\Http\Models\Admin\GroupUserPermission;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\URL;

class AdminLoginController extends Controller
{

    public $_user;

    public function __construct(User $user)
    {
        $this->_user = $user;
    }

    public function getLogin($url = '')
    {
        if (Session::has('user')) {
            if ($url === '' || $url === 'user') {
                return Redirect::route('admin.dashboard');
            } else {
                return Redirect::to(self::buildUrlDecode($url));
            }
        } else {
            return view('admin.AdminUser.login');
        }
    }

    public function postLogin(Request $request, $url = '')
    {
        if (Session::has('user')) {
            if ($url === '' || $url === 'user') {
                return Redirect::route('admin.dashboard');
            } else {
                return Redirect::to(self::buildUrlDecode($url));
            }
        }

        $token = $request->input('_token', '');
        $username = $request->input('user_name', '');
        $password = $request->input('user_password', '');
        $error = '';
        if (Session::token() === $token) {
            if ($username != '' && $password != '') {
                if (strlen($username) < 3 || strlen($username) > 50 || preg_match('/[^A-Za-z0-9_\.@]/', $username) || strlen($password) < 5) {
                    $error = 'Không tồn tại tên đăng nhập!';
                } else {
                    $user = $this->_user->getUserByName($username);
                    if ($user !== NULL) {
                        if ($user->user_status == CGlobal::status_hide || $user->user_status == CGlobal::status_block) {
                            $error = 'Tài khoản bị khóa!';
                        } elseif ($user->user_status == CGlobal::status_show || $user->user_view == CGlobal::status_hide) {
                            if ($this->_user->password_verify($password, $user->user_password)) {
                                $permission_code = array();
                                $group = explode(',', $user->user_group);
                                if ($group) {
                                    $permission = GroupUserPermission::getListPermissionByGroupId($group);
                                    if ($permission) {
                                        foreach ($permission as $v) {
                                            $permission_code[] = $v->permission_code;
                                        }
                                    }
                                }
                                $data = array(
                                    'user_id' => $user->user_id,
                                    'user_project' => $user->user_project,//ko dung
                                    'user_object_id' => $user->user_object_id,
                                    'user_parent' => $user->user_parent,
                                    'member_id' => $user->user_parent,//member
                                    'user_name' => $user->user_name,
                                    'user_full_name' => $user->user_full_name,
                                    'user_email' => $user->user_email,
                                    'user_depart_id' => $user->user_depart_id,
                                    'user_is_admin' => $user->user_is_admin,
                                    'user_group_menu' => $user->user_group_menu,
                                    'is_boss' => $user->user_view,
                                    'role_type' => $user->role_type,
                                    'user_permission' => $permission_code
                                );
                                $request->session()->put('user', $data, 60 * 24);
                                $this->_user->updateLogin($user);
                                if ($url === '' || $url === 'login') {
                                    return Redirect::route('admin.dashboard');
                                } else {
                                    return Redirect::to(self::buildUrlDecode($url));
                                }
                            } else {
                                $error = 'Mật khẩu không đúng!';
                            }
                        }
                    } else {
                        $error = 'Không tồn tại tên đăng nhập!';
                    }
                }
            } else {
                $error = 'Chưa nhập thông tin đăng nhập!';
            }
        }
        return view('admin.AdminUser.login', ['error' => $error, 'username' => $username]);
    }

    public function logout(Request $request)
    {
        if ($request->session()->has('user')) {
            $request->session()->forget('user');
        }
        return Redirect::route('admin.login');
    }
}