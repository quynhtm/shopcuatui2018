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
    const DB_DATABASE = '';
    const DB_USERNAME = '';
    const DB_PASSWORD = '';
    //server

    const TABLE_USER = 'shop_user';
    const TABLE_MEMBER = 'shop_member';
    const TABLE_GROUP_USER = 'shop_group_user';
    const TABLE_PERMISSION = 'shop_permission';
    const TABLE_MENU_SYSTEM = 'shop_menu_system';
    const TABLE_ROLE_MENU = 'shop_role_menu';
    const TABLE_ROLE = 'shop_role';
    const TABLE_VIDEO = 'shop_video';
    const TABLE_INFO = 'shop_info';
    const TABLE_BANNER = 'shop_banner';
    const TABLE_CRONJOB = 'shop_cronjob';
    const TABLE_CONTACT = 'shop_contact';
    const TABLE_PROVINCE = 'shop_province';
    const TABLE_WARDS = 'shop_wards';
    const TABLE_DISTRICTS = 'shop_districts';
    const TABLE_GROUP_USER_PERMISSION = 'shop_group_user_permission';

    const TABLE_NEW_CATEGORY = 'new_category';
    const TABLE_NEWS= 'new_news';

    const TABLE_PRODUCT = 'pro_product';
    const TABLE_PRO_CATEGORY = 'pro_category';
    const TABLE_PRO_DEPARTMENT = 'pro_department';
    const TABLE_ORDER = 'pro_order';
    const TABLE_ORDER_ITEM = 'pro_order_item';


    /***************************************************************************************************************
     * //Memcache
     ***************************************************************************************************************/
    const CACHE_ON = 1;// 0: khong dung qua cache, 1: dung qua cache
    const CACHE_TIME_TO_LIVE_5 = 300; //Time cache 5 phut
    const CACHE_TIME_TO_LIVE_15 = 900; //Time cache 15 phut
    const CACHE_TIME_TO_LIVE_30 = 1800; //Time cache 30 phut
    const CACHE_TIME_TO_LIVE_60 = 3600; //Time cache 60 phut
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

    const CACHE_USER_NAME = 'haianhem';
    const CACHE_USER_KEY = 'admin!@133';
    const CACHE_EMAIL_NAME = 'manager@gmail.com';

    //category new
    const CACHE_ALL_PARENT_CATEGORY = 'cache_all_parent_category_';
    const CACHE_ALL_CHILD_CATEGORY_BY_PARENT_ID = 'cache_all_child_category_by_parent_id_';
    const CACHE_CATEGORY_NEWS = 'cache_category_news';
    const CACHE_CATEGORY_ID = 'cache_category_id_';

    const CACHE_NEWS_ID = 'cache_news_id_';

    const CACHE_INFO_USER = 'cache_info_user';
    const CACHE_OPTION_USER = 'cache_option_user';
    const CACHE_OPTION_CARRIER = 'cache_option_carrier';

    const CACHE_OPTION_ROLE = 'cache_option_role';

    const CACHE_MEMBER_LIST = 'cache_member_list';
    const CACHE_OPTION_MEMBER = 'cache_member_member';

    //danh mục Product
    const CACHE_CATEGORY_PRODUCT = 'cache_category_product';
    const CACHE_PRO_CATEGORY_ID = 'cache_pro_category_id_';
    const CACHE_ALL_PARENT_CATEGORY_PRO = 'cache_all_parent_category_pro_';
    const CACHE_ALL_CHILD_CATEGORY_PRO_BY_PARENT_ID = 'cache_all_child_category_pro_by_parent_id_';

    //Hr nhân sự
    const CACHE_PERSON = 'cache_info_person_id_';

    //Hr key cache
    const CACHE_ROLE_ID = 'cache_admin_role_id_';
    const CACHE_HR_DEFINED_ID = 'cache_hr_defined_id_';
    const CACHE_DEPARTMENT_ID = 'cache_department_id_';
    const CACHE_ALL_DEPARTMENT = 'cache_all_department';
    const CACHE_DEFINED_TYPE = 'cache_defined_type_';
    const CACHE_DEFINED_ALL = 'cache_defined_all';
    const CACHE_DEVICE_ID = 'cache_device_id_';
    const CACHE_HR_DOCUMENT_ID = 'cache_hr_document_id_';
    const CACHE_HR_MAIL_ID = 'cache_hr_mail_id_';

    /***************************************************************************************************************
     * //Define
     ***************************************************************************************************************/
    const ERROR_PERMISSION = 1;

    const VIETNAM_LANGUAGE = 1;
    const ENGLISH_LANGUAGE = 2;
    static $arrLanguage = array(Define::VIETNAM_LANGUAGE => 'vi', Define::ENGLISH_LANGUAGE => 'en');

    const STATUS_SHOW = 1;
    const STATUS_HIDE = 0;
    const STATUS_BLOCK = -2;

    //SuperAdmin, Admin, Customer
    const ROLE_TYPE_SUPER_ADMIN = 6;
    const ROLE_TYPE_ADMIN = 7;
    const ROLE_TYPE_CUSTOMER = 10;
    static $arrUserRole = array(
        Define::ROLE_TYPE_SUPER_ADMIN => 'SuperAdmin',
        Define::ROLE_TYPE_ADMIN => 'Admin',
        Define::ROLE_TYPE_CUSTOMER => 'Customer');

    //type upload
    const TYPE_UPLOAD_NEWS = 1;
    //Folder
    const IMAGE_ERROR = -1000;
    const FOLDER_DEVICE = 'device';
    const FOLDER_NEWS = 'img_news';
    const FOLDER_DOCUMENT = 'document';
    const FOLDER_MAIL = 'mail';

    const sizeImage_80 = 80;
    const sizeImage_100 = 100;
    const sizeImage_150 = 150;
    const sizeImage_200 = 200;
    const sizeImage_300 = 300;
    const sizeImage_400 = 400;
    const sizeImage_450 = 450;

    public static $arrSizeImage = array(
        self::sizeImage_100 => array('w' => self::sizeImage_100, 'h' => self::sizeImage_100),
        self::sizeImage_200 => array('w' => self::sizeImage_200, 'h' => self::sizeImage_200),
        self::sizeImage_300 => array('w' => self::sizeImage_300, 'h' => self::sizeImage_300),
        self::sizeImage_450 => array('w' => self::sizeImage_450, 'h' => self::sizeImage_450),
    );

    const Category_News_Menu = 1;
    const Category_News_News = 2;
    const Category_News_Product = 3;
    const Category_News_Note = 4;
    public static $arrCategoryType = [
        self::Category_News_Menu => 'Danh Mục Menu',
        self::Category_News_News => 'Danh Mục Tin Tức',
        self::Category_News_Product => 'Danh Mục Sản Phẩm',
        self::Category_News_Note => 'Danh Mục Ghi Chú',
    ];

    //loại tin tức, hay sản phẩm của bài viết tin tức
    const news_type_new = 1;
    const news_type_product = 2;
    public static $arrTypeNews = [
        self::news_type_new => 'Loại tin tức',
        self::news_type_product => 'Loại sản phẩm'
    ];
    const Banner_Page_Home = 1;
    const Banner_Page_List = 2;
    const Banner_Page_Detail = 3;
    public static $arrBannerPage = [
        self::Banner_Page_Home => 'Trang chủ',
        self::Banner_Page_List => 'Danh sách',
        self::Banner_Page_Detail => 'Chi tiết',
    ];
    const Banner_Type_Home = 1;
    const Banner_Type_List = 2;
    public static $arrBannerType = [
        self::Banner_Type_Home => 'Trang chủ',
        self::Banner_Type_List => 'Danh sách',
    ];
    const Banner_is_Target = 1;
    const Banner_no_Target = 0;
    public static $arrBannerTarget = [
        self::Banner_is_Target => 'Mở tab mới',
        self::Banner_no_Target => 'Không mở tab mới',
    ];
    const Banner_is_Run_Time = 1;
    const Banner_no_Run_Time = 0;
    public static $arrBannerRunTime = [
        self::Banner_is_Run_Time => 'Chọn thời gian chạy',
        self::Banner_no_Run_Time => 'Vĩnh viễn',
    ];

    //InfoSite
    const INFO_FOOTER = 1;
    const INFO_CONTACT = 2;
}