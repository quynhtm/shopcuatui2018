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
define('PREFIX', 'shop_');
define('TABLE_USER_ADMIN', PREFIX.'user_admin');
define('TABLE_GROUP_USER', PREFIX.'group_user');
define('TABLE_PERMISSION', PREFIX.'permission');
define('TABLE_MENU_SYSTEM', PREFIX.'menu_system');
define('TABLE_ROLE_MENU', PREFIX.'role_menu');
define('TABLE_ROLE', PREFIX.'role');
define('TABLE_GROUP_USER_PERMISSION', PREFIX.'group_user_permission');
define('TABLE_MEMBER', PREFIX.'member');


/**************************************************************************************************************
 * định nghĩa quyền
 **************************************************************************************************************/
//permiss banner
define('PERMISS_BANNER_FULL', 'bannerFull');
define('PERMISS_BANNER_VIEW', 'bannerView');
define('PERMISS_BANNER_CREATE', 'bannerCreate');
define('PERMISS_BANNER_DELETE', 'bannerDelete');