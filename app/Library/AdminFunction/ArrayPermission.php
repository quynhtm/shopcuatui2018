<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QuynhTM
 */

namespace App\Library\AdminFunction;

class ArrayPermission
{
    public static $arrNewPermiss = array(
        1 => array(
            'group_permiss' => 'Quản trị site',
            'infor' => array(
                1 => array('code' => 'root', 'name' => 'Quản trị site '),
            ),
        ),
        2 => array(
            'group_permiss' => 'Tech code',
            'infor' => array(
                1 => array('code' => 'is_tech', 'name' => 'Tech code'),
            ),
        ),
        3 => array(
            'group_permiss' => 'Tài khoản Admin',
            'infor' => array(
                1 => array('code' => 'user_view', 'name' => 'Xem danh sách user'),
                2 => array('code' => 'user_create', 'name' => 'Tạo user'),
                3 => array('code' => 'user_edit', 'name' => 'Sửa user'),
                4 => array('code' => 'user_change_pass', 'name' => 'Đổi pass user'),
                5 => array('code' => 'user_remove', 'name' => 'Xóa user'),
            ),
        ),
        4 => array(
            'group_permiss' => 'Nhóm quyền',
            'infor' => array(
                1 => array('code' => 'group_user_view', 'name' => 'Xem nhóm quyền'),
                2 => array('code' => 'group_user_create', 'name' => 'Tạo nhóm quyền'),
                3 => array('code' => 'group_user_edit', 'name' => 'Sửa nhóm quyền'),
            ),
        ),
        5 => array(
            'group_permiss' => 'Tạo quyền',
            'infor' => array(
                1 => array('code' => 'permission_full', 'name' => 'Full tạo quyền'),
                2 => array('code' => 'permission_create', 'name' => 'Tạo quyền'),
                3 => array('code' => 'permission_edit', 'name' => 'Sửa tạo quyền'),
            ),
        ),
        6 => array(
            'group_permiss' => 'Menu admin',
            'infor' => array(
                1 => array('code' => 'menu_full', 'name' => 'Full menu'),
                2 => array('code' => 'menu_view', 'name' => 'View menu'),
                3 => array('code' => 'menu_create', 'name' => 'Tạo menu'),
                4 => array('code' => 'menu_delete', 'name' => 'Xóa menu'),
            ),
        ),
        7 => array(
            'group_permiss' => 'Quyền Role',
            'infor' => array(
                1 => array('code' => 'role_full', 'name' => 'Full role'),
                2 => array('code' => 'role_view', 'name' => 'View role'),
                3 => array('code' => 'role_create', 'name' => 'Tạo role'),
                4 => array('code' => 'role_delete', 'name' => 'Xóa role'),
            ),
        ),
        8 => array(
            'group_permiss' => 'Phân quyền role',
            'infor' => array(
                2 => array('code' => 'role_permission_view', 'name' => 'View quyền role'),
                3 => array('code' => 'role_permission_create', 'name' => 'Tạo quyền role'),
                4 => array('code' => 'role_permission_edit', 'name' => 'Sửa quyền role'),
            ),
        ),
        9 => array(
            'group_permiss' => 'Phân quyền role',
            'infor' => array(
                1 => array('code' => PERMISS_BANNER_FULL, 'name' => 'Full banner'),
                2 => array('code' => PERMISS_BANNER_VIEW, 'name' => 'View banner'),
                3 => array('code' => PERMISS_BANNER_CREATE, 'name' => 'Tạo banner'),
                4 => array('code' => PERMISS_BANNER_DELETE, 'name' => 'Sửa banner'),
            ),
        ),
    );

}