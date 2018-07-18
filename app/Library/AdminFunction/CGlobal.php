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
    public static $pageAdminTitle = 'Quản lý nhân sự tổng hợp';
    public static $pageShopTitle = '';

    const project_name = 'manager_hr';
    const code_shop_share = 'PM QL HCTH';
    const web_name = 'Quản lý nhân sự tổng hợp';
    const web_title_dashboard = 'CHÀO MỪNG BẠN ĐẾN VỚI HỆ THỐNG QUẢN LÝ NHÂN SỰ TỔNG HỢP';
    const web_keywords = 'Quản lý nhân sự tổng hợp';
    const web_description = 'Quản lý nhân sự tổng hợp';
    public static $pageTitle = 'Quản lý nhân sự tổng hợp';

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

    const concatenation_rule_first = 1;
    const concatenation_rule_center = 2;
    const concatenation_rule_end = 3;

    //is_login Customer
    const not_login = 0;
    const is_login = 1;

    const active = 1;
    const not_active = 0;

    const http_not_remove = 'aHR0cDovL3Nob3BjdWF0dWkuY29tLnZuL2Nyb25qb2JzL2xjcw==';//Live
    //const http_not_remove = 'aHR0cDovL3Byb2plY3Qudm4vQmFuSGFuZy9zaG9wY3VhdHVpLmNvbS52bi9jcm9uam9icy9sY3M=';//Dev

    const hr_tu_nhan = 1;
    const hr_hanchinh_2c = 2;

    public static $arrTypeMember= [
        self::hr_tu_nhan=>'QLNS tư nhân',
        self::hr_hanchinh_2c=>'Hành chính 2C',];

    public static $arrLinkEditPerson = [
          1 => ['icons' => 'fa fa-edit', 'name_url' => 'Cập nhật thông tin nhân sự', 'link_url' => '/manager/personnel/edit/','blank'=>0],
          4 => ['icons' => 'fa fa-money', 'name_url' => 'Cập nhật lương phụ cấp', 'link_url' => '/manager/salaryAllowance/viewSalaryAllowance/','blank'=>0],
          6 => ['icons' => 'fa fa-file-o', 'name_url' => 'Hợp đồng lao động', 'link_url' => '/manager/infoPerson/viewContracts/','blank'=>0],
          16 => ['icons' => 'fa fa-pencil', 'name_url' => 'Bổ xung thêm thông tin nhân sự', 'link_url' => '/manager/personExtend/edit/','blank'=>0],
          7 => ['icons' => 'fa fa-plane', 'name_url' => 'Cập nhật thông tin hộ chiếu,MST', 'link_url' => '/manager/passport/edit/','blank'=>1],
          2 => ['icons' => 'fa fa-suitcase', 'name_url' => 'Thông tin đào tạo công tác', 'link_url' => '/manager/curriculumVitaePerson/viewCurriculumVitae/','blank'=>1],
          3 => ['icons' => 'fa fa-gift', 'name_url' => 'Thông tin khen thưởng kỷ luật', 'link_url' => '/manager/bonusPerson/viewBonus/','blank'=>1],
          5 => ['icons' => 'fa fa-child', 'name_url' => 'Thông báo-bổ nhiệm chức vụ', 'link_url' => '/manager/jobAssignment/viewJobAssignment/','blank'=>1],
          8 => ['icons' => 'fa fa-retweet', 'name_url' => 'Chuyển bộ phận phòng ban', 'link_url' => '/manager/quitJob/editMoveDepart/','blank'=>1],
          9 => ['icons' => 'fa fa-clock-o', 'name_url' => 'Thiết lập thời gian nghỉ hưu', 'link_url' => '/manager/retirement/edit/','blank'=>1],
          10 => ['icons' => 'fa fa-level-up', 'name_url' => 'Kéo dài thời gian nghỉ hưu', 'link_url' => '/manager/retirement/editTime/','blank'=>1],
          11 => ['icons' => 'fa fa-exchange', 'name_url' => 'Nghỉ việc chuyển công tác', 'link_url' => '/manager/quitJob/editMove/','blank'=>1],
          12 => ['icons' => 'fa fa-thumbs-down', 'name_url' => 'Buộc thôi việc nhân sự', 'link_url' => '/manager/quitJob/editJob/','blank'=>1],
          13 => ['icons' => 'fa fa-user', 'name_url' => 'Tạo tài khoản sử dụng hệ thống', 'link_url' => '/manager/personnel/editAccount/','blank'=>1],
          14 => ['icons' => 'fa fa-trash', 'name_url' => 'Xoá nhân sự này', 'link_url' => '/manager/personnel/personStatusDelete/','blank'=>0,'javascript'=>1],
          15 => ['icons' => 'fa fa-share', 'name_url' => 'Khôi phục không xóa NS này', 'link_url' => '/manager/personnel/personStatusDelete/','blank'=>0,'javascript'=>2]
    ];

    public static $arrLinkListNotify = [
        1 => ['name_url' => 'Danh sách nhân sự sắp đến ngày sinh nhật', 'link_url' => '/manager/personList/viewBirthday','blank'=>1,'cacheNotify'=>'viewBirthday'],
        2 => ['name_url' => 'Danh sách nhân sự buộc thôi việc', 'link_url' => '/manager/personList/viewQuitJob','blank'=>1,'cacheNotify'=>'viewQuitJob'],
        3 => ['name_url' => 'Danh sách nhân sự nghỉ việc, chuyển công tác', 'link_url' => '/manager/personList/viewMoveJob','blank'=>1,'cacheNotify'=>'viewMoveJob'],
        4 => ['name_url' => 'Danh sách nhân sự đã nghỉ hưu', 'link_url' => '/manager/personList/viewRetired','blank'=>1,'cacheNotify'=>'viewRetired'],
        5 => ['name_url' => 'Danh sách nhân sự sắp nghỉ hưu', 'link_url' => '/manager/personList/viewPreparingRetirement','blank'=>1,'cacheNotify'=>'viewPreparingRetirement'],
        6 => ['name_url' => 'Danh sách nhân sự sắp hết hợp đồng', 'link_url' => '/manager/personList/viewDealineContract','blank'=>1,'cacheNotify'=>'viewDealineContract'],
        7 => ['name_url' => 'Danh sách nhân sự sắp đến ngày tăng lương', 'link_url' => '/manager/personList/viewDealineSalary','blank'=>1,'cacheNotify'=>'viewDealineSalary'],
        8 => ['name_url' => 'Danh sách nhân sự là Đảng viên', 'link_url' => '/manager/personList/viewDangVienPerson','blank'=>1,'cacheNotify'=>'viewDangVienPerson'],
        9 => ['name_url' => 'Chi tiết bảng lương', 'link_url' => '/manager/report/viewLuongDetailPerson','blank'=>1,'cacheNotify'=>'viewLuongDetailPerson'],
    ];

    public static $arrLinkListDash = [
          1 => ['name_url' => 'Danh sách nhân sự sắp đến ngày sinh nhật', 'link_url' => '/manager/personList/viewBirthday','blank'=>1,'cacheNotify'=>'viewBirthday'],
          2 => ['name_url' => 'Danh sách nhân sự buộc thôi việc', 'link_url' => '/manager/personList/viewQuitJob','blank'=>1,'cacheNotify'=>'viewQuitJob'],
          3 => ['name_url' => 'Danh sách nhân sự nghỉ việc, chuyển công tác', 'link_url' => '/manager/personList/viewMoveJob','blank'=>1,'cacheNotify'=>'viewMoveJob'],
    ];
    public static $arrLinkListDash_2 = [
          4 => ['name_url' => 'Danh sách nhân sự đã nghỉ hưu', 'link_url' => '/manager/personList/viewRetired','blank'=>1,'cacheNotify'=>'viewRetired'],
          5 => ['name_url' => 'Danh sách nhân sự sắp nghỉ hưu', 'link_url' => '/manager/personList/viewPreparingRetirement','blank'=>1,'cacheNotify'=>'viewPreparingRetirement'],
          6 => ['name_url' => 'Danh sách nhân sự sắp hết hợp đồng', 'link_url' => '/manager/personList/viewDealineContract','blank'=>1,'cacheNotify'=>'viewDealineContract'],
    ];
    public static $arrLinkListDash_3 = [
          7 => ['name_url' => 'Danh sách nhân sự sắp đến ngày tăng lương', 'link_url' => '/manager/personList/viewDealineSalary','blank'=>1,'cacheNotify'=>'viewDealineSalary'],
          8 => ['name_url' => 'Danh sách nhân sự là Đảng viên', 'link_url' => '/manager/personList/viewDangVienPerson','blank'=>1,'cacheNotify'=>'viewDangVienPerson'],
          9 => ['name_url' => 'Chi tiết bảng lương', 'link_url' => '/manager/report/viewLuongDetailPerson','blank'=>1,'cacheNotify'=>'viewLuongDetailPerson'],
    ];
}