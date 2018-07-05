<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;

use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\HrContracts;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\HrDefine;

use App\Http\Models\Hr\PersonTime;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Border;

class PersonListController extends BaseAdminController
{
    private $permission_view = 'person_view';
    private $permission_full = 'person_full';
    private $permission_delete = 'person_delete';
    private $permission_create = 'person_create';
    private $permission_edit = 'person_edit';
    private $person_creater_user = 'person_creater_user';
    private $arrStatus = array();
    private $arrMenuParent = array();
    private $arrSex = array();
    private $depart = array();
    private $arrChucVu = array();
    private $arrChucDanhNgheNghiep = array();
    private $viewPermission = array();//check quyen
    private $action_person = 1;

    const val10 = 5;
    const val18 = 18;
    const val35 = 35;
    const val45 = 60;
    const val25 = 25;
    const val55 = 55;

    public function __construct()
    {
        parent::__construct();
        $this->arrMenuParent = array();
    }

    public function getDataDefault()
    {
        $this->arrStatus = array(
            CGlobal::status_hide => FunctionLib::controLanguage('status_all', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_block => FunctionLib::controLanguage('status_block', $this->languageSite));

        $this->arrSex = array(
            CGlobal::status_hide => FunctionLib::controLanguage('sex_girl', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('sex_boy', $this->languageSite));

        $this->depart = Department::getDepartmentAll();
        $this->arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $this->arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_root' => $this->is_root ? 1 : 0,
            'is_boss' => $this->is_boss ? 1 : 0,
            'permission_edit' => in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create' => in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_delete' => in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full' => in_array($this->permission_full, $this->permission) ? 1 : 0,
            'person_creater_user' => in_array($this->person_creater_user, $this->permission) ? 1 : 0,
        ];
    }

    /******************************************************************************************************************
     * NS sắp sinh nhật:
     ******************************************************************************************************************/
    public function viewBirthday()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự sắp sinh nhật';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $page_no = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;
        //tính toán lấy user_id
        $arrPersonId = PersonTime::getListPersonIdByTypeTime(Define::PERSONNEL_TIME_TYPE_BIRTH,Define::config_date_check_notify_7);

        //sau
        if(sizeof($arrPersonId) > 0){
            $search['person_name'] = addslashes(Request::get('person_name', ''));
            $search['person_mail'] = addslashes(Request::get('person_mail', ''));
            $search['person_code'] = addslashes(Request::get('person_code', ''));
            $search['list_person_id'] = $arrPersonId;
            $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
            $search['person_status'] = Define::$arrStatusPersonAction;
            $search['orderBy'] = 'person_birth';
            $search['sortOrder'] = 'asc';
            $data = Person::searchByCondition($search, $limit, $offset, $total);
            //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay
        }
        //FunctionLib::bug($search);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';
        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'action_person' => $this->action_person,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /******************************************************************************************************************
     * NS nghỉ việc
     * job chạy quét bảng quit_job để cập nhật lại bảng personal trạng thái nghỉ việc
     ******************************************************************************************************************/
    public function viewQuitJob()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự buộc thôi việc';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $page_no = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['person_name'] = addslashes(Request::get('person_name', ''));
        $search['person_mail'] = addslashes(Request::get('person_mail', ''));
        $search['person_code'] = addslashes(Request::get('person_code', ''));
        $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
        $search['person_status'] = Define::PERSON_STATUS_NGHIVIEC;
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = Person::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }
        //FunctionLib::debug($data);

        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'action_person' =>0,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /******************************************************************************************************************
     * NS chuyển công tác
     * job chạy quét bảng quit_job để cập nhật lại bảng personal trạng thái nghỉ việc
     ******************************************************************************************************************/
    public function viewMoveJob()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự chuyển công tác';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $page_no = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['person_name'] = addslashes(Request::get('person_name', ''));
        $search['person_mail'] = addslashes(Request::get('person_mail', ''));
        $search['person_code'] = addslashes(Request::get('person_code', ''));
        $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
        $search['person_status'] = Define::PERSON_STATUS_CHUYENCONGTAC;
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = Person::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }
        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'action_person' => 0,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /******************************************************************************************************************
     * NS Đã nghỉ hưu
     * job chạy quét bảng retirement để cập nhật lại bảng personal trạng thái nghi huu
     ******************************************************************************************************************/
    public function viewRetired()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự đã nghỉ hưu';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $page_no = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['person_name'] = addslashes(Request::get('person_name', ''));
        $search['person_mail'] = addslashes(Request::get('person_mail', ''));
        $search['person_code'] = addslashes(Request::get('person_code', ''));
        $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
        $search['person_status'] = Define::PERSON_STATUS_NGHIHUU;
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = Person::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }

        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'action_person' => 0,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /******************************************************************************************************************
     * NS sắp nghỉ hưu
     * job chạy quét bảng retirement để cập nhật lại bảng personal trạng thái sắp nghi huu
     ******************************************************************************************************************/
    public function viewPreparingRetirement()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự sắp nghỉ hưu';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $page_no = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['person_name'] = addslashes(Request::get('person_name', ''));
        $search['person_mail'] = addslashes(Request::get('person_mail', ''));
        $search['person_code'] = addslashes(Request::get('person_code', ''));
        $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
        $search['person_status'] = Define::PERSON_STATUS_SAPNGHIHUU;
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = Person::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle, 5);
        }

        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'action_person' => $this->action_person,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /******************************************************************************************************************
     * NS sắp hết hạn hợp đồng:
     ******************************************************************************************************************/
    public function viewDealineContract(){
        CGlobal::$pageAdminTitle = 'Nhân sự sắp hết Hợp đồng';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        $page_no = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;

        //tính toán lấy user_id
        $arrPersonId = PersonTime::getListPersonIdByTypeTime(Define::PERSONNEL_TIME_TYPE_CONTRACTS_DEALINE_DATE,Define::config_date_check_notify_7);
        //sau
        if(sizeof($arrPersonId) > 0){
            $search['person_name'] = addslashes(Request::get('person_name', ''));
            $search['person_mail'] = addslashes(Request::get('person_mail', ''));
            $search['person_code'] = addslashes(Request::get('person_code', ''));
            $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
            $search['person_status'] = Define::$arrStatusPersonAction;
            $search['list_person_id'] = $arrPersonId;
            $data = (count($arrPersonId) > 0)? Person::searchByCondition($search, $limit, $offset, $total): array();
        }
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';
        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle, 3);
        }
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'action_person' => $this->action_person,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /******************************************************************************************************************
     * NS sắp đến hạn tăng lương;
     ******************************************************************************************************************/
    public function viewDealineSalary()
    {
        CGlobal::$pageAdminTitle = 'NS sắp tăng lương';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $page_no = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;
        //tính toán lấy user_id
        $arrPersonId = PersonTime::getListPersonIdByTypeTime(Define::PERSONNEL_TIME_TYPE_DATE_SALARY_INCREASE,Define::config_date_check_notify_7);
        if(sizeof($arrPersonId) > 0) {
            $search['person_name'] = addslashes(Request::get('person_name', ''));
            $search['person_mail'] = addslashes(Request::get('person_mail', ''));
            $search['person_code'] = addslashes(Request::get('person_code', ''));
            $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
            $search['person_status'] = Define::$arrStatusPersonAction;
            $search['list_person_id'] = $arrPersonId;
            $search['orderBy'] = 'person_date_salary_increase';
            $search['sortOrder'] = 'asc';
            //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay
            $data = Person::searchByCondition($search, $limit, $offset, $total);
        }
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle, 2);
        }
        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'action_person' => $this->action_person,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /******************************************************************************************************************
     * NS Đã bị xóa tạm thời
     ******************************************************************************************************************/
    public function viewDeletePerson()
    {
        CGlobal::$pageAdminTitle = 'NS đã bị xóa';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $page_no = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['person_name'] = addslashes(Request::get('person_name', ''));
        $search['person_mail'] = addslashes(Request::get('person_mail', ''));
        $search['person_code'] = addslashes(Request::get('person_code', ''));
        $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
        $search['person_status'] = Define::PERSON_STATUS_DAXOA;
        $search['sortOrder'] = 'asc';
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = Person::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }
        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'is_delete' => 1,
            'total' => $total,
            'action_person' => $this->action_person,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /******************************************************************************************************************
     * NS la Dang vien
     ******************************************************************************************************************/
    public function viewDangVienPerson()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự là Đảng viên';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $page_no = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['person_name'] = addslashes(Request::get('person_name', ''));
        $search['person_mail'] = addslashes(Request::get('person_mail', ''));
        $search['person_code'] = addslashes(Request::get('person_code', ''));
        $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
        $search['person_is_dangvien'] = Define::DANG_VIEN;
        //$search['field_get'] = '';//cac truong can lay

        $data = Person::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle, 4);
        }

        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'action_person' => $this->action_person,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /*
    public function exportData($data, $title ='') {
        if(empty($data)){
            return;
        }

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        // Set Orientation, size and scaling
        $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // Set font
        $sheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('000000');
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue("A1", $title );
        $sheet->getRowDimension("1")->setRowHeight(32);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // setting header
        $position_hearder = 3;
        $sheet->getRowDimension($position_hearder)->setRowHeight(30);
        $val10 = 5; $val18 = 18; $val35 = 35;$val45 = 60; $val25 = 25;$val55 = 55;
        $ary_cell = array(
            'A'=>array('w'=>$val10,'val'=>'STT','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'B'=>array('w'=>$val35,'val'=>'Họ tên','align'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
            'C'=>array('w'=>$val10,'val'=>'Giới tính','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'D'=>array('w'=>$val18,'val'=>'Ngày sinh','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'E'=>array('w'=>$val18,'val'=>'Ngày làm việc','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'F'=>array('w'=>$val35,'val'=>'Đơn vị bộ phận','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'G'=>array('w'=>$val35,'val'=>'Chức danh nghề nghiệp','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'H'=>array('w'=>$val35,'val'=>'Chức vụ','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        );

        //build header title
        foreach($ary_cell as $col => $attr){
            $sheet->getColumnDimension($col)->setWidth($attr['w']);
            $sheet->setCellValue("$col{$position_hearder}",$attr['val']);
            $sheet->getStyle($col)->getAlignment()->setWrapText(true);
            $sheet->getStyle($col . $position_hearder)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '05729C'),
                        'style' => array('font-weight' => 'bold')
                    ),
                    'font'  => array(
                        'bold'  => true,
                        'color' => array('rgb' => 'FFFFFF'),
                        'size'  => 10,
                        'name'  => 'Verdana'
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '333333')
                        )
                    ),
                    'alignment' => array(
                        'horizontal' => $attr['align'],
                    )
                )
            );
        }
        //hien thị dũ liệu
        $rowCount = $position_hearder+1; // hang bat dau xuat du lieu
        $i = 1;
        $break="\r";
        foreach ($data as $k => $v) {
            $sheet->getRowDimension($rowCount)->setRowHeight(30);//chiều cao của row

            $sheet->getStyle('A' . $rowCount)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('A' . $rowCount, $i);

            $sheet->getStyle('B' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
            $sheet->SetCellValue('B' . $rowCount, $v['person_name']);

            $sheet->getStyle('C' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('C' . $rowCount, isset($this->arrSex[$v['person_sex']])? $this->arrSex[$v['person_sex']]: '' );

            $sheet->getStyle('D' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('D' . $rowCount, date('d-m-Y',$v['person_birth']));

            $sheet->getStyle('E' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('E' . $rowCount, date('d-m-Y',$v['person_date_start_work']));

            $sheet->getStyle('F' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('F' . $rowCount, isset($this->depart[$v['person_depart_id']])? $this->depart[$v['person_depart_id']]: '');

            $sheet->getStyle('G' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('G' . $rowCount, isset($this->arrChucDanhNgheNghiep[$v['person_career_define_id']])? $this->arrChucDanhNgheNghiep[$v['person_career_define_id']]: '');

            $sheet->getStyle('H' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('H' . $rowCount, isset($this->arrChucVu[$v['person_position_define_id']])? $this->arrChucVu[$v['person_position_define_id']]: '');

            $rowCount++;
            $i++;
        }

        // output file
        ob_clean();
        $filename = "Danh sách nhân sự" . "_" . date("_d/m_") . '.xls';
        @header("Cache-Control: ");
        @header("Pragma: ");
        @header("Content-type: application/octet-stream");
        @header("Content-Disposition: attachment; filename=\"{$filename}\"");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save("php://output");
        exit();
    }
    */
    public function exportData($data, $title = '', $type=1){
        if (empty($data)) {
            return;
        }
        //FunctionLib::debug($data);
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        // Set Orientation, size and scaling
        $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // Set font
        //8db4e2
        $sheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $sheet->getStyle('A1')->getFont()->setSize(15)->setBold(true)->getColor()->setRGB('000000');
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue("A1", $title);
        $sheet->getRowDimension("1")->setRowHeight(32);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '8db4e2'),
                    'style' => array('font-weight' => 'bold')
                ),
            )
        );
        $str = '';
        if($type == 2){
            $str = ' SẮP TĂNG LƯƠNG';
        }elseif($type == 3){
            $str = ' SẮP HẾT HẠN HỢP ĐỒNG';
        }elseif($type == 4){
            $str = ' ĐẢNG VIÊN';
        }elseif($type == 5){
            $str = ' SẮP NGHỈ HƯU';
        }

        $sheet->SetCellValue('A1', 'BÁO CÁO DANH SÁCH NHÂN SỰ'.$str);

        // setting header
        $position_hearder = 2;
        $sheet->getRowDimension($position_hearder)->setRowHeight(30);

        //$type:
        // 1: Thong ke bao cao nhan su,---
        // 2:Nhan su sap tang luong,---
        // 3: Nhan su sap het han hop dong,---
        // 4: Nhan su la dang vien,
        // 5: Nhan su sap nghi huu--
        $ary_cell = $this->buildHeadExcel($type);

        //build header title
        foreach ($ary_cell as $col => $attr) {
            $sheet->getColumnDimension($col)->setWidth($attr['w']);
            $sheet->setCellValue("$col{$position_hearder}", $attr['val']);
            $sheet->getStyle($col)->getAlignment()->setWrapText(true);
            $sheet->getStyle($col . $position_hearder)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FFFFFF'),
                        'style' => array('font-weight' => 'bold')
                    ),
                    'font' => array(
                        'bold' => true,
                        'color' => array('rgb' => '000000'),
                        'size' => 10,
                        'name' => 'Verdana'
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '222222')
                        )
                    ),
                    'alignment' => array(
                        'horizontal' => $attr['align'],
                    )
                )
            );
            $sheet->getStyle($col . $position_hearder)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        }
        //hien thị dũ liệu
        $rowCount = $position_hearder + 1; // hang bat dau xuat du lieu
        $i = 1;
        $break = "\r";

        $arrDanToc = HrDefine::getArrayByType(Define::dan_toc);
        $depart = Department::getDepartmentAll();
        $arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
        $arrLoaihopdong = HrDefine::getArrayByType(Define::loai_hop_dong);
        $this->getDataDefault();

        foreach ($data as $k => $v) {
            $sheet->getRowDimension($rowCount)->setRowHeight(24);

            $sheet->getStyle('A' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('A' . $rowCount, $i);

            $sheet->getStyle('B' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
            $sheet->SetCellValue('B' . $rowCount, $v['person_name']);

            $sheet->getStyle('C' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('C' . $rowCount, date('d-m-Y', $v['person_birth']));

            $sheet->getStyle('D' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('D' . $rowCount, isset($this->arrSex[$v['person_sex']]) ? $this->arrSex[$v['person_sex']] : '');

            $sheet->getStyle('E' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('E' . $rowCount, isset($arrDanToc[$v['person_nation_define_id']]) ? $arrDanToc[$v['person_nation_define_id']] : '');

            $sheet->getStyle('F' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
            $sheet->SetCellValue('F' . $rowCount, $v['person_address_home_town'] ? $v['person_address_home_town'] : '');

            $sheet->getStyle('G' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
            $sheet->SetCellValue('G' . $rowCount, $v['person_address_current'] ? $v['person_address_current'] : '');

            $sheet->getStyle('H' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('H' . $rowCount, $v['person_phone'] ? $v['person_phone'] : '');

            $sheet->getStyle('I' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
            $sheet->SetCellValue('I' . $rowCount, isset($depart[$v['person_depart_id']]) ? $depart[$v['person_depart_id']] : '');

            $sheet->getStyle('J' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
            $sheet->SetCellValue('J' . $rowCount, isset($arrChucDanhNgheNghiep[$v['person_career_define_id']]) ? $arrChucDanhNgheNghiep[$v['person_career_define_id']] : '');

            $sheet->getStyle('K' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('K' . $rowCount, '');

            if($type == 1) {
                $sheet->getStyle('L' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('L' . $rowCount, ($v['person_ngayvao_dang'] != 0)  ? date('d/m/Y', $v['person_ngayvao_dang']) : '');

                $sheet->getStyle('M' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('M' . $rowCount, ($v['person_date_start_work'] != 0)  ? date('d/m/Y', $v['person_date_start_work']) : '');

                $sheet->getStyle('N' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('N' . $rowCount, isset($arrLoaihopdong[$v['person_type_contracts']]) ? $arrLoaihopdong[$v['person_type_contracts']] : '');
            }elseif($type == 2){

                $sheet->getStyle('L' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('L' . $rowCount, ($v['person_date_start_work'] != 0)  ? date('d/m/Y', $v['person_date_start_work']) : '');

                $sheet->getStyle('M' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('M' . $rowCount, isset($arrLoaihopdong[$v['person_type_contracts']]) ? $arrLoaihopdong[$v['person_type_contracts']] : '');

                $sheet->getStyle('N' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('N' . $rowCount, ($v['person_date_salary_increase'] != 0) ? date('d/m/Y', $v['person_date_salary_increase']) : '');
            }elseif($type == 3){

                $sheet->getStyle('L' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('L' . $rowCount, ($v['person_date_start_work'] != 0)  ? date('d/m/Y', $v['person_date_start_work']) : '');

                $sheet->getStyle('M' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('M' . $rowCount, isset($arrLoaihopdong[$v['person_type_contracts']]) ? $arrLoaihopdong[$v['person_type_contracts']] : '');

                $sheet->getStyle('N' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('N' . $rowCount, ($v['contracts_dealine_date'] != 0)  ? date('d/m/Y', $v['contracts_dealine_date']) : '');
            }elseif($type == 4){
                $sheet->getStyle('L' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('L' . $rowCount, ($v['person_ngayvao_dang'] != 0)  ? date('d/m/Y', $v['person_ngayvao_dang']) : '');
            }elseif($type == 5){
                $sheet->getStyle('L' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('L' . $rowCount, ($v['person_date_start_work'] != 0)  ? date('d/m/Y', $v['person_date_start_work']) : '');

                $sheet->getStyle('M' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('M' . $rowCount, ($v['retirement_date'] != 0)  ? date('d/m/Y', $v['retirement_date']) : '');
            }
            $rowCount++;
            $i++;
        }

        ob_clean();

        $filename = "Danh sach nhan su" . "_" . date("_d/m_");
        @header("Cache-Control: ");
        @header("Pragma: ");
        @header("Content-type: application/octet-stream");
        @header('Content-Disposition: attachment; filename="'.$filename.'.xls"');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save("php://output");
        exit();
    }
    public function buildHeadExcel($type = 1){
        $ary_cell = array(
            'A' => array('w' => self::val10, 'val' => 'STT', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'B' => array('w' => self::val25, 'val' => 'Họ tên', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
            'C' => array('w' => self::val18, 'val' => 'Ngày sinh', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'D' => array('w' => self::val18, 'val' => 'Giới tính', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'E' => array('w' => self::val18, 'val' => 'Dân tộc', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'F' => array('w' => self::val25, 'val' => 'Quê quán', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'G' => array('w' => self::val25, 'val' => 'Địa chỉ', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'H' => array('w' => self::val18, 'val' => 'Số điện thoại', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'I' => array('w' => self::val18, 'val' => 'Phòng ban đơn vị', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'J' => array('w' => self::val18, 'val' => 'Chức danh nghề nghiệp', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'K' => array('w' => self::val18, 'val' => 'Trình độ học vấn', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        );

        if($type == 1){
            $ary_cell +=  array(
                'L' => array('w' => self::val18, 'val' => 'Ngày vào Đảng', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'M' => array('w' => self::val18, 'val' => 'Ngày làm việc', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'N' => array('w' => self::val18, 'val' => 'Loại hợp đồng', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
        }elseif($type == 2){
            $ary_cell +=  array(
                'L' => array('w' => self::val18, 'val' => 'Ngày làm việc', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'M' => array('w' => self::val18, 'val' => 'Loại hợp đồng', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'N' => array('w' => self::val18, 'val' => 'Ngày tăng lương', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
        }elseif($type == 3){
            $ary_cell +=  array(
                'L' => array('w' => self::val18, 'val' => 'Ngày làm việc', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'M' => array('w' => self::val18, 'val' => 'Loại hợp đồng', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'N' => array('w' => self::val18, 'val' => 'Ngày hết hạn HĐ', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
        }elseif($type == 4){
            $ary_cell +=  array(
                'L' => array('w' => self::val18, 'val' => 'Ngày vào Đảng', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
        }elseif($type == 5){
            $ary_cell +=  array(
                'L' => array('w' => self::val18, 'val' => 'Ngày làm việc', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'M' => array('w' => self::val18, 'val' => 'Ngày nghỉ hưu', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
        }
        return $ary_cell;
    }
}
