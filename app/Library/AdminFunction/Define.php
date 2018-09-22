<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QuynhTM
 */

namespace App\Library\AdminFunction;
class Define
{
    /***************************************************************************************************************
     * //Database
     ***************************************************************************************************************/
    const DB_CONNECTION_MYSQL = 'mysql';
    const DB_CONNECTION_SQLSRV = 'sqlsrv';
    const DB_CONNECTION_PGSQL = 'pgsql';
    const DB_SOCKET = '';
    //local
    const DB_HOST = 'localhost';
    const DB_PORT = '3306';
    const DB_DATABASE = 'vaymuon_ver4';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = '';
    //server

    /***************************************************************************************************************
     * //Memcache
     ***************************************************************************************************************/
    const CACHE_ON = 1;// 0: khong dung qua cache, 1: dung qua cache
    const CACHE_TIME_TO_LIVE_5 = 300; //Time cache 5 phut
    const CACHE_TIME_TO_LIVE_15 = 900; //Time cache 15 phut
    const CACHE_TIME_TO_LIVE_30 = 1800; //Time cache 30 phut
    const CACHE_TIME_TO_LIVE_60 = 3600; //Time cache 60 phut
    const CACHE_TIME_TO_LIVE_HALF_DAY_DAY = 43200; //Time cache nửa ngay
    const CACHE_TIME_TO_LIVE_ONE_DAY = 86400; //Time cache 1 ngay
    const CACHE_TIME_TO_LIVE_ONE_WEEK = 604800; //Time cache 1 tuan
    const CACHE_TIME_TO_LIVE_ONE_MONTH = 2419200; //Time cache 1 thang
    const CACHE_TIME_TO_LIVE_ONE_YEAR = 29030400; //Time cache 1 nam

    //user customer
    const CACHE_DEBUG = 'cache_debug';
    const CACHE_CUSTOMER_ID = 'cache_customer_id_';
    const CACHE_ALL_PARENT_MENU = 'cache_all_parent_menu_';
    const CACHE_TREE_MENU = 'cache_tree_menu_';
    const CACHE_LIST_MENU_PERMISSION = 'cache_list_menu_permission';
    const CACHE_ALL_PARENT_CATEGORY = 'cache_all_parent_category_';
    const CACHE_USER_NAME = 'haianhem';
    const CACHE_USER_KEY = 'admin!@133';
    const CACHE_EMAIL_NAME = 'manager@gmail.com';

    const CACHE_INFO_USER = 'cache_info_user';
    const CACHE_OPTION_USER = 'cache_option_user';
    const CACHE_OPTION_CARRIER = 'cache_option_carrier';
    const CACHE_OPTION_ROLE = 'cache_option_role';

    const CACHE_LIST_BANNER = 'cache_list_banner';

    /***************************************************************************************************************
     * //Define
     ***************************************************************************************************************/
    const ERROR_PERMISSION = 1;
    static $arrLanguage = array(VIETNAM_LANGUAGE => 'vi', ENGLISH_LANGUAGE => 'en');

    const STATUS_SHOW = 1;
    const STATUS_HIDE = 0;
    const STATUS_BLOCK = -2;
    const STATUS_SEARCH_ALL = -1;

}