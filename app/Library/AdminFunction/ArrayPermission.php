<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QuynhTM
 */
namespace App\Library\AdminFunction;

class ArrayPermission{
    public static $arrPermit = array(
        'root' => array('name_permit'=>'Quản trị site','group_permit'=>'Quản trị site'),//admin site
        'is_boss' => array('name_permit'=>'Boss','group_permit'=>'Boss'),//tech dùng quyen cao nhat

        'user_view' => array('name_permit'=>'Xem danh sách user Admin','group_permit'=>'Tài khoản Admin'),
        'user_create' => array('name_permit'=>'Tạo user Admin','group_permit'=>'Tài khoản Admin'),
        'user_edit' => array('name_permit'=>'Sửa user Admin','group_permit'=>'Tài khoản Admin'),
        'user_change_pass' => array('name_permit'=>'Thay đổi user Admin','group_permit'=>'Tài khoản Admin'),
        'user_remove' => array('name_permit'=>'Xóa user Admin','group_permit'=>'Tài khoản Admin'),

        'group_user_view' => array('name_permit'=>'Xem nhóm quyền','group_permit'=>'Nhóm quyền'),
        'group_user_create' => array('name_permit'=>'Tạo nhóm quyền','group_permit'=>'Nhóm quyền'),
        'group_user_edit' => array('name_permit'=>'Sửa nhóm quyền','group_permit'=>'Nhóm quyền'),

        'permission_full' => array('name_permit'=>'Full tạo quyền','group_permit'=>'Tạo quyền'),
        'permission_create' => array('name_permit'=>'Tạo tạo quyền','group_permit'=>'Tạo quyền'),
        'permission_edit' => array('name_permit'=>'Sửa tạo quyền','group_permit'=>'Tạo quyền'),

        'province_full' => array('name_permit'=>'Full tỉnh thành','group_permit'=>'Quyền tỉnh thành'),
        'province_view' => array('name_permit'=>'Xem tỉnh thành','group_permit'=>'Quyền tỉnh thành'),
        'province_delete' => array('name_permit'=>'Xóa tỉnh thành','group_permit'=>'Quyền tỉnh thành'),
        'province_create' => array('name_permit'=>'Tạo tỉnh thành','group_permit'=>'Quyền tỉnh thành'),
        'province_edit' => array('name_permit'=>'Sửa tỉnh thành','group_permit'=>'Quyền tỉnh thành'),

        'user_customer_full' => array('name_permit'=>'Full khách hàng','group_permit'=>'Quyền khách hàng'),
        'user_customer_view' => array('name_permit'=>'Xem khách hàng','group_permit'=>'Quyền khách hàng'),
        'user_customer_delete' => array('name_permit'=>'Xóa khách hàng','group_permit'=>'Quyền khách hàng'),
        'user_customer_create' => array('name_permit'=>'Tạo khách hàng','group_permit'=>'Quyền khách hàng'),
        'user_customer_edit' => array('name_permit'=>'Sửa khách hàng','group_permit'=>'Quyền khách hàng'),

        'adminCronjob_full' => array('name_permit'=>'Full cronjob','group_permit'=>'Quyền cronjob'),
        'adminCronjob_view' => array('name_permit'=>'Xem cronjob','group_permit'=>'Quyền cronjob'),
        'adminCronjob_delete' => array('name_permit'=>'Xóa cronjob','group_permit'=>'Quyền cronjob'),
        'adminCronjob_create' => array('name_permit'=>'Tạo cronjob','group_permit'=>'Quyền cronjob'),
        'adminCronjob_edit' => array('name_permit'=>'Sửa cronjob','group_permit'=>'Quyền cronjob'),

        'menu_full' => array('name_permit'=>'Full menu','group_permit'=>'Quyền menu'),
        'menu_view' => array('name_permit'=>'Xem menu','group_permit'=>'Quyền menu'),
        'menu_delete' => array('name_permit'=>'Xóa menu','group_permit'=>'Quyền menu'),
        'menu_create' => array('name_permit'=>'Tạo menu','group_permit'=>'Quyền menu'),
        'menu_edit' => array('name_permit'=>'Sửa menu','group_permit'=>'Quyền menu'),



        'role_full' => array('name_permit'=>'Full Role','group_permit'=>'Quyền Role'),
        'role_view' => array('name_permit'=>'Xem Role','group_permit'=>'Quyền Role'),
        'role_delete' => array('name_permit'=>'Xóa Role','group_permit'=>'Quyền Role'),
        'role_create' => array('name_permit'=>'Tạo Role','group_permit'=>'Quyền Role'),
        'role_edit' => array('name_permit'=>'Sửa Role','group_permit'=>'Quyền Role'),

        'role_permission_view' => array('name_permit'=>'Full','group_permit'=>'Phân quyền role'),
        'role_permission_create' => array('name_permit'=>'Tạo','group_permit'=>'Phân quyền role'),
        'role_permission_edit' => array('name_permit'=>'Sửa','group_permit'=>'Phân quyền role'),

        'hr_document_full' => array('name_permit'=>'Full văn bản','group_permit'=>'Quyền văn bản'),
        'hr_document_view' => array('name_permit'=>'Xem văn bản','group_permit'=>'Quyền văn bản'),
        'hr_document_delete' => array('name_permit'=>'Xóa văn bản','group_permit'=>'Quyền văn bản'),
        'hr_document_create' => array('name_permit'=>'Tạo văn bản','group_permit'=>'Quyền văn bản'),
        'hr_document_edit' => array('name_permit'=>'Sửa văn bản','group_permit'=>'Quyền văn bản'),

        'personBonusFull' => array('name_permit'=>'Full','group_permit'=>'Quyền Thông tin khen thưởng, danh hiệu, kỷ luật'),
        'personBonusView' => array('name_permit'=>'Xem','group_permit'=>'Quyền Thông tin khen thưởng, danh hiệu, kỷ luật'),
        'personBonusDelete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Thông tin khen thưởng, danh hiệu, kỷ luật'),
        'personBonusCreate' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Thông tin khen thưởng, danh hiệu, kỷ luật'),

        'personCurriculumVitaeFull' => array('name_permit'=>'Full','group_permit'=>'Quyền Thông tin lý lịch 2C'),
        'personCurriculumVitaeView' => array('name_permit'=>'Xem','group_permit'=>'Quyền Thông tin lý lịch 2C'),
        'personCurriculumVitaeDelete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Thông tin lý lịch 2C'),
        'personCurriculumVitaeCreate' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Thông tin lý lịch 2C'),

        'department_config_full' => array('name_permit'=>'Full','group_permit'=>'Quyền Quản lý cấu hình đơn vị'),
        'department_config_view' => array('name_permit'=>'Xem','group_permit'=>'Quyền Quản lý cấu hình đơn vị'),
        'department_config_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Quản lý cấu hình đơn vị'),
        'department_config_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Quản lý cấu hình đơn vị'),

        'device_full' => array('name_permit'=>'Full','group_permit'=>'Quyền Quản lý tài sản'),
        'device_view' => array('name_permit'=>'Xem','group_permit'=>'Quyền Quản lý tài sản'),
        'device_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Quản lý tài sản'),
        'device_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Quản lý tài sản'),
        'device_export' => array('name_permit'=>'Export','group_permit'=>'Quyền Quản lý tài sản'),

        'hrDefined_full' => array('name_permit'=>'Full','group_permit'=>'Quyền Quản lý định nghĩa chung'),
        'hrDefined_view' => array('name_permit'=>'Xem','group_permit'=>'Quyền Quản lý định nghĩa chung'),
        'hrDefined_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Quản lý định nghĩa chung'),
        'hrDefined_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Quản lý định nghĩa chung'),

        'department_full' => array('name_permit'=>'Full','group_permit'=>'Quyền Quản lý đơn vị - phòng ban'),
        'department_view' => array('name_permit'=>'Xem','group_permit'=>'Quyền Quản lý đơn vị - phòng ban'),
        'department_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Quản lý đơn vị - phòng ban'),
        'department_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Quản lý đơn vị - phòng ban'),

        'hr_mail_full' => array('name_permit'=>'Full','group_permit'=>'Quyền thư gửi'),
        'hr_mail_view' => array('name_permit'=>'Xem','group_permit'=>'Quyền thư gửi'),
        'hr_mail_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền thư gửi'),
        'hr_mail_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền thư gửi'),

        'wagestepconfig_full' => array('name_permit'=>'Full','group_permit'=>'Quyền thang bảng lương'),
        'wagestepconfig_view' => array('name_permit'=>'Xem','group_permit'=>'Quyền thang bảng lương'),
        'wagestepconfig_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền thang bảng lương'),
        'wagestepconfig_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền thang bảng lương'),

        'personContractsFull' => array('name_permit'=>'Full','group_permit'=>'Quyền Họp đồng lao động'),
        'personContractsView' => array('name_permit'=>'Xem','group_permit'=>'Quyền Họp đồng lao động'),
        'personContractsDelete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Họp đồng lao động'),
        'personContractsCreate' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Họp đồng lao động'),

        'personCreaterUser_full' => array('name_permit'=>'Full','group_permit'=>'Quyền tạo NS login'),
        'personCreaterUser_view' => array('name_permit'=>'Xem','group_permit'=>'Quyền tạo NS login'),
        'personCreaterUser_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền tạo NS login'),
        'personCreaterUser_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền tạo NS login'),

        'jobAssignmentFull' => array('name_permit'=>'Full','group_permit'=>'Quyền Bổ nhiệm/Bổ nhiệm lại chức vụ'),
        'jobAssignmentView' => array('name_permit'=>'Xem','group_permit'=>'Quyền Bổ nhiệm/Bổ nhiệm lại chức vụ'),
        'jobAssignmentDelete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Bổ nhiệm/Bổ nhiệm lại chức vụ'),
        'jobAssignmentCreate' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Bổ nhiệm/Bổ nhiệm lại chức vụ'),

        'passport_full' => array('name_permit'=>'Full','group_permit'=>'Quyền Thông tin hộ chiếu - mã số thuế'),
        'passport_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Thông tin hộ chiếu - mã số thuế'),
        'passport_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Thông tin hộ chiếu - mã số thuế'),

        'person_full' => array('name_permit'=>'Full','group_permit'=>'Quyền Quản lý nhân sự'),
        'person_view' => array('name_permit'=>'Xem','group_permit'=>'Quyền Quản lý nhân sự'),
        'person_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Quản lý nhân sự'),
        'person_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Quản lý nhân sự'),
        'person_creater_user' => array('name_permit'=>'Tạo user login','group_permit'=>'Quyền Quản lý nhân sự'),

        'quitJob_full' => array('name_permit'=>'Full','group_permit'=>'Quyền Thiết lập buộc thôi việc nhân sự'),
        'quitJob_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Thiết lập buộc thôi việc nhân sự'),
        'quitJob_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Thiết lập buộc thôi việc nhân sự'),

        'viewTienLuongCongChuc' => array('name_permit'=>'Full','group_permit'=>'Quyền Báo cáo danh sách và tiền lương công chức'),
        'exportTienLuongCongChuc' => array('name_permit'=>'Export','group_permit'=>'Quyền Báo cáo danh sách và tiền lương công chức'),

        'personViewTienLuongCongChuc' => array('name_permit'=>'Full','group_permit'=>'NS xem tiền lương công chức'),
        'personExportTienLuongCongChuc' => array('name_permit'=>'Export','group_permit'=>'NS xem tiền lương công chức'),

        'retirement_full' => array('name_permit'=>'Full','group_permit'=>'Quyền Thiết lập ngày nghỉ hưu'),
        'retirement_view' => array('name_permit'=>'Xem','group_permit'=>'Quyền Thiết lập ngày nghỉ hưu'),
        'retirement_delete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Thiết lập ngày nghỉ hưu'),
        'retirement_create' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Thiết lập ngày nghỉ hưu'),

        'salaryAllowanceFull' => array('name_permit'=>'Full','group_permit'=>'Quyền Lương, phụ cấp'),
        'salaryAllowanceView' => array('name_permit'=>'Xem','group_permit'=>'Quyền Lương, phụ cấp'),
        'salaryAllowanceDelete' => array('name_permit'=>'Xóa','group_permit'=>'Quyền Lương, phụ cấp'),
        'salaryAllowanceCreate' => array('name_permit'=>'Tạo','group_permit'=>'Quyền Lương, phụ cấp'),


        /**
         * private $salaryAllowanceFull = 'salaryAllowanceFull';
        private $salaryAllowanceView = 'salaryAllowanceView';
        private $salaryAllowanceDelete = 'salaryAllowanceDelete';
        private $salaryAllowanceCreate = 'salaryAllowanceCreate';
         */
    );

}