<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QuynhTM
 */

namespace App\Library\AdminFunction;

use App\library\AdminFunction\Define;

class CGlobal
{
    const IS_DEV = 0;//0: trên server 1: local

    static $css_ver = 1;
    static $js_ver = 1;
    public static $POS_HEAD = 1;
    public static $POS_END = 2;
    public static $extraHeaderCSS = '';
    public static $extraHeaderJS = '';
    public static $extraFooterCSS = '';
    public static $extraFooterJS = '';
    public static $extraMeta = '';
    public static $pageAdminTitle = 'Quản trị vaymuon.vn';
    public static $pageShopTitle = '';

    const project_name = 'vaymuon.vn';
    const code_shop_share = '';
    const web_name = 'Quản trị vaymuon.vn';
    const web_title_dashboard = 'CHÀO MỪNG BẠN ĐẾN VỚI HỆ THỐNG QUẢN TRỊ VAYMUON.VN';
    const web_keywords = 'Quản trị vaymuon.vn';
    const web_description = 'Quản trị vaymuon.vn';
    public static $pageTitle = 'Quản trị vaymuon.vn';

    const phoneSupport = '';

    const num_scroll_page = 2;
    const number_limit_show = 30;
    const number_show_30 = 30;
    const number_show_40 = 40;
    const number_show_20 = 20;
    const number_show_15 = 15;
    const number_show_10 = 10;
    const number_show_5 = 5;
    const number_show_8 = 8;
    const number_show_1000 = 1000;

    const status_show = 1;
    const status_hide = 0;
    const status_block = -2;

    static $arrLanguage = array(VIETNAM_LANGUAGE => 'vi', ENGLISH_LANGUAGE => 'en');

    public static $arrMenuTabTop = [
            5 => 'Người vay',
            2 => 'Nhà đầu tư',
            3 => 'Đối tác',
            4 => 'Báo cáo',
            1 => 'Setting',
        ];

    const member_type_vip = 1;
    const member_type_A = 2;
    const member_type_B = 3;
    const member_type_C = 4;
    public static $arrTypeMember= [
        self::member_type_vip=>'Member VIP',
        self::member_type_A=>'Member A',
        self::member_type_B=>'Member B',
        self::member_type_C=>'Member C',];
}