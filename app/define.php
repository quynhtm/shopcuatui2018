<?php
/**
 * Created by PhpStorm.
 * User: QuynhTM
 * Date: 10/17/2016
 * Time: 2:06 PM
 */

define('LIMIT_RECORD_10', 10);
define('LIMIT_RECORD_15', 15);
define('LIMIT_RECORD_20', 20);
define('LIMIT_RECORD_30', 30);

define('VIETNAM_LANGUAGE', 1);
define('ENGLISH_LANGUAGE', 2);

define('PROVINCE_SHOW' , 1);
define('DISTRICTS_SHOW' , 2);

define('STATUS_HIDE', 0);
define('STATUS_SHOW', 1);
define('STATUS_DEFAULT', -1);
define('STATUS_BLOCK', -2);
define('ERROR_PERMISSION', 1);

define('CACHE_FIVE_MINUTE', 300);
define('CACHE_TEN_MINUTE', 600);
define('CACHE_THIRTY_MINUTE', 1800);
define('CACHE_ONE_HOUR', 3600);
define('CACHE_SIX_HOUR', 21600);
define('CACHE_ONE_DAY', 86400);
define('CACHE_TWO_DAY', 172800);
define('CACHE_THREE_DAY', 259200);
define('CACHE_SIX_DAY', 518400);
define('CACHE_ONE_WEEK', 604800);
define('CACHE_ONE_MONTH', 2592000);
define('CACHE_THREE_MONTH', 7776000);
define('CACHE_ONE_YEAR', 31104000);
define('CACHE_FIVE_YEAR', 155520000);

/**************************************************************************************************************
 * định nghĩa Table vaymuon
 **************************************************************************************************************/
define('PREFIX_SHOP', 'shop_');
define('PREFIX_WEB', 'web_');
define('TABLE_USER_ADMIN', PREFIX_SHOP.'user_admin');
define('TABLE_GROUP_USER', PREFIX_SHOP.'group_user');
define('TABLE_PERMISSION', PREFIX_SHOP.'permission');
define('TABLE_MENU_SYSTEM', PREFIX_SHOP.'menu_system');
define('TABLE_ROLE_MENU', PREFIX_SHOP.'role_menu');
define('TABLE_ROLE', PREFIX_SHOP.'role');
define('TABLE_GROUP_USER_PERMISSION', PREFIX_SHOP.'group_user_permission');
define('TABLE_MEMBER', PREFIX_SHOP.'member');
define('TABLE_PROVIDER', PREFIX_SHOP.'provider');
define('TABLE_BANNER', PREFIX_SHOP.'banner');
define('TABLE_ORDER', PREFIX_SHOP.'order');
define('TABLE_ORDER_ITEM', PREFIX_SHOP.'order_item');
define('TABLE_PROVINCE', PREFIX_SHOP.'province');
define('TABLE_DISTRICTS', PREFIX_SHOP.'districts');
define('TABLE_WARDS', PREFIX_SHOP.'wards');
define('TABLE_CONTACT', PREFIX_SHOP.'contact');
define('TABLE_DEPARTMENT', PREFIX_SHOP.'department');
define('TABLE_INFOR_SALE', PREFIX_SHOP.'infor_sale');
define('TABLE_CATEGORY', PREFIX_SHOP.'category');
define('TABLE_PRODUCT', PREFIX_SHOP.'product');
define('TABLE_PRODUCT_STORAGE', PREFIX_SHOP.'product_storage');

//Fix Id
define('CATEGORY_PRODUCT', 5);


/**************************************************************************************************************
 * định nghĩa quyền
 **************************************************************************************************************/
//member
define('PERMISS_MEMBER_FULL', 'memberFull');
define('PERMISS_MEMBER_VIEW', 'memberView');
define('PERMISS_MEMBER_CREATE', 'memberCreate');
define('PERMISS_MEMBER_DELETE', 'memberDelete');

//Type NCC
define('PERMISS_PROVIDER_FULL', 'providerFull');
define('PERMISS_PROVIDER_VIEW', 'providerView');
define('PERMISS_PROVIDER_CREATE', 'providerCreate');
define('PERMISS_PROVIDER_DELETE', 'providerDelete');

//Permiss banner
define('PERMISS_BANNER_FULL', 'bannerFull');
define('PERMISS_BANNER_VIEW', 'bannerView');
define('PERMISS_BANNER_CREATE', 'bannerCreate');
define('PERMISS_BANNER_DELETE', 'bannerDelete');

//Permiss contact
define('PERMISS_CONTACT_FULL', 'contactFull');
define('PERMISS_CONTACT_VIEW', 'contactView');
define('PERMISS_CONTACT_CREATE', 'contactCreate');
define('PERMISS_CONTACT_DELETE', 'contactDelete');

//Permiss product
define('PERMISS_PRODUCT_FULL', 'productFull');
define('PERMISS_PRODUCT_VIEW', 'productView');
define('PERMISS_PRODUCT_CREATE', 'productCreate');
define('PERMISS_PRODUCT_DELETE', 'productDelete');

//Type dategory
define('PERMISS_DEPARTMENT_FULL', 'departmentFull');
define('PERMISS_DEPARTMENT_VIEW', 'departmentView');
define('PERMISS_DEPARTMENT_CREATE', 'departmentCreate');
define('PERMISS_DEPARTMENT_DELETE', 'departmentDelete');

//Info sale
define('PERMISS_INFOSALE_FULL', 'infosaleFull');
define('PERMISS_INFOSALE_VIEW', 'infosaleView');
define('PERMISS_INFOSALE_CREATE', 'infosaleCreate');
define('PERMISS_INFOSALE_DELETE', 'infosaleDelete');

//order
define('PERMISS_ORDER_FULL', 'orderFull');
define('PERMISS_ORDER_VIEW', 'orderView');
define('PERMISS_ORDER_CREATE', 'orderCreate');
define('PERMISS_ORDER_DELETE', 'orderDelete');

//Category
define('PERMISS_CATEGORY_FULL', 'categoryFull');
define('PERMISS_CATEGORY_VIEW', 'categoryView');
define('PERMISS_CATEGORY_CREATE', 'categoryCreate');
define('PERMISS_CATEGORY_DELETE', 'categoryDelete');

//province
define('PERMISS_PROVINCE_FULL', 'provinceFull');
define('PERMISS_PROVINCE_VIEW', 'provinceView');
define('PERMISS_PROVINCE_CREATE', 'provinceCreate');
define('PERMISS_PROVINCE_DELETE', 'provinceDelete');

//districts
define('PERMISS_DISTRICTS_FULL', 'districtsFull');
define('PERMISS_DISTRICTS_VIEW', 'districtsView');
define('PERMISS_DISTRICTS_CREATE', 'districtsCreate');
define('PERMISS_DISTRICTS_DELETE', 'districtsDelete');

//wards
define('PERMISS_WARDS_FULL', 'wardsFull');
define('PERMISS_WARDS_VIEW', 'wardsView');
define('PERMISS_WARDS_CREATE', 'wardsCreate');
define('PERMISS_WARDS_DELETE', 'wardsDelete');