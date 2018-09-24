<?php
namespace App\Library\AdminFunction;

class Memcache{
    const CACHE_ON = 1 ;// 0: khong dung qua cache, 1: dung qua cache

    const CACHE_BANNER_ID = 'cache_banner_id_';

    const CACHE_PROVIDER_ID = 'cache_provider_id_';
    const CACHE_ALL_PROVIDER = 'cache_all_provider';
    const CACHE_LIST_PROVIDER_BY_MEMBER_ID = 'cache_list_provider_by_member_id_';

    const CACHE_INFO_MEMBER_ID = 'cache_info_member_id_';
    const CACHE_ALL_MEMBER = 'cache_all_member';

    const CACHE_DEPARTMENT_ID = 'cache_department_id_';

    const CACHE_INFOR_SALE_ID = 'cache_infor_sale_id_';
    const CACHE_INFOR_SALE_MEMBER_ID = 'cache_infor_sale_member_id_';

    const CACHE_ALL_CATEGORY = 'cache_all_category';
    const CACHE_CATEGORY_ID = 'cache_category_id_';
    const CACHE_CATEGORY_MEMBER_ID = 'cache_category_member_id_';
    const CACHE_ALL_PARENT_CATEGORY = 'cache_all_parent_category';
    const CACHE_ALL_CHILD_CATEGORY_BY_PARENT_ID = 'cache_all_child_category_by_parent_id_';
    const CACHE_ALL_SHOW_CATEGORY_FRONT = 'cache_all_show_category_front';
    const CACHE_ALL_CATEGORY_BY_TYPE = 'cache_all_category_by_type_';
    const CACHE_ALL_CATEGORY_RIGHT = 'cache_all_category_right';
}
