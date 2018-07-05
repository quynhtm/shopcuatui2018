<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Districts;
use App\Http\Models\Admin\Province;
use App\Http\Models\Admin\Wards;
use App\Http\Models\Hr\Allowance;
use App\Http\Models\Hr\CurriculumVitae;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\HrContracts;
use App\Http\Models\Hr\HrWageStepConfig;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\Bonus;
use App\Http\Models\Hr\HrDefine;

use App\Http\Models\Admin\User;
use App\Http\Models\Admin\Role;
use App\Http\Models\Admin\RoleMenu;

use App\Http\Models\Hr\PersonExtend;
use App\Http\Models\Hr\Relationship;
use App\Http\Models\Hr\Salary;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

use App\Library\AdminFunction\Loader;
use App\Library\AdminFunction\Upload;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Border;

class PersonController extends BaseAdminController
{
    private $permission_view = 'person_view';
    private $permission_full = 'person_full';
    private $permission_delete = 'person_delete';
    private $permission_create = 'person_create';
    private $permission_edit = 'person_edit';
    private $person_creater_user = 'person_creater_user';
    private $arrStatus = array();
    private $error = array();
    private $arrMenuParent = array();
    private $arrRoleType = array();
    private $arrSex = array();
    private $arrTonGiao = array();
    private $viewPermission = array();//check quyen
    private $viewOptionData = array();

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
        $this->arrRoleType = Role::getOptionRole();
        $this->arrStatus = array(
            CGlobal::status_hide => FunctionLib::controLanguage('status_all', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_block => FunctionLib::controLanguage('status_block', $this->languageSite));
        $this->arrSex = array(
            CGlobal::status_hide => FunctionLib::controLanguage('sex_girl', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('sex_boy', $this->languageSite));

        $this->arrTonGiao = array(
            CGlobal::status_hide => 'Không',
            CGlobal::status_show => 'Có');
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_boss' => $this->is_boss ? 1 : 0,
            'is_root' => $this->is_root ? 1 : 0,
            'permission_edit' => in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create' => in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_delete' => in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full' => in_array($this->permission_full, $this->permission) ? 1 : 0,
            'person_creater_user' => in_array($this->person_creater_user, $this->permission) ? 1 : 0,
        ];
    }

    public function view()
    {
        CGlobal::$pageAdminTitle = 'Quản lý nhân sự';
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
        $search['person_status'] = Define::PERSON_STATUS_DANGLAMVIEC;
        $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
        $search['person_is_dangvien'] = (int)Request::get('person_is_dangvien', -1);
        $search['person_type_contracts'] = (int)Request::get('person_type_contracts', 0);

        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = Person::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        if ($sbmValue == 2) {
            $this->exportData($data, 'Danh sách nhân sự đang làm việc', 1);
        }

        $this->getDataDefault();
        $depart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $optionDangVien = FunctionLib::getOption(array(-1 => '--Tìm kiếm theo Đảng viên --') + $this->arrTonGiao, isset($search['person_is_dangvien']) ? $search['person_is_dangvien'] : -1);

        $arrLoaihopdong = HrDefine::getArrayByType(Define::loai_hop_dong);
        $optionLoaihopdong = FunctionLib::getOption(array(-1 => '--Tìm kiếm theo loại HĐ--') + $arrLoaihopdong, isset($search['person_type_contracts']) ? $search['person_type_contracts'] : -1);

        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.view', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'arrSex' => $this->arrSex,
            'arrDepart' => $depart,
            'arrChucVu' => $arrChucVu,
            'arrChucDanhNgheNghiep' => $arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'optionDangVien' => $optionDangVien,
            'optionLoaihopdong' => $optionLoaihopdong,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    public function getItem($ids)
    {
        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        CGlobal::$pageAdminTitle = 'Thông tin nhân sự';
        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($id > 0) {
            $data = Person::find($id);
        }

        $this->getDataDefault();
        $this->viewOptionData($data);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.add', array_merge([
            'data' => $data,
            'id' => $id,
        ], $this->viewOptionData, $this->viewPermission));
    }

    public function postItem($ids)
    {
        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        CGlobal::$pageAdminTitle = 'Thông tin nhân sự';
        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $id_hiden = Request::get('id_hiden', '');
        $data = $_POST;
        $data['ordering'] = (isset($data['person_birth']) && $data['person_birth'] != '') ? strtotime($data['person_birth']) : 0;
        $data['person_date_trial_work'] = (isset($data['person_date_trial_work']) && $data['person_date_trial_work'] != '') ? strtotime($data['person_date_trial_work']) : 0;
        $data['person_date_start_work'] = (isset($data['person_date_start_work']) && $data['person_date_start_work'] != '') ? strtotime($data['person_date_start_work']) : 0;
        $data['person_date_range_cmt'] = (isset($data['person_date_range_cmt']) && $data['person_date_range_cmt'] != '') ? strtotime($data['person_date_range_cmt']) : 0;

        $data['person_birth'] = (isset($data['person_birth']) && $data['person_birth'] != '') ? strtotime($data['person_birth']) : 0;
        $data['person_avatar'] = (isset($data['img']) && $data['img'] != '') ? trim($data['img']) : '';
        $data['person_status'] = Define::STATUS_SHOW;

        if ($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? FunctionLib::outputId($id_hiden) : $id;
            $submit = $_POST['save_form'];
            if ($id > 0) {
                //cap nhat
                if (Person::updateItem($id, $data)) {
                    return ($submit == Define::SUBMIT_BACK_LIST) ? Redirect::route('hr.personnelView') : Redirect::route('hr.viewSalaryAllowance',array('person_id'=>FunctionLib::inputId($id)));
                }
            } else {
                //them moi
                $person_id = Person::createItem($data);
                return ($submit == Define::SUBMIT_BACK_LIST) ? Redirect::route('hr.personnelView') : Redirect::route('hr.viewSalaryAllowance',array('person_id'=>FunctionLib::inputId($person_id)));
            }
        }

        $this->getDataDefault();
        $this->viewOptionData($data);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.add', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
        ], $this->viewOptionData, $this->viewPermission));
    }

    public function viewOptionData($data)
    {
        //thông tin của nhân sự
        $optionSex = FunctionLib::getOption($this->arrSex, isset($data['person_sex']) ? $data['person_sex'] : 0);
        $optionTonGiao = FunctionLib::getOption($this->arrTonGiao, isset($data['person_respect']) ? $data['person_respect'] : 0);
        $depart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($depart, isset($data['person_depart_id']) ? $data['person_depart_id'] : 0);

        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $optionChucVu = FunctionLib::getOption($arrChucVu, isset($data['person_position_define_id']) ? $data['person_position_define_id'] : 0);

        $arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
        $optionChucDanhNgheNghiep = FunctionLib::getOption($arrChucDanhNgheNghiep, isset($data['person_career_define_id']) ? $data['person_career_define_id'] : 0);

        $arrNhomMau = HrDefine::getArrayByType(Define::nhom_mau);
        $optionNhomMau = FunctionLib::getOption($arrNhomMau, isset($data['person_blood_group_define_id']) ? $data['person_blood_group_define_id'] : 0);

        $arrDanToc = HrDefine::getArrayByType(Define::dan_toc);
        $optionDanToc = FunctionLib::getOption($arrDanToc, isset($data['person_nation_define_id']) ? $data['person_nation_define_id'] : 0);

        $arrProvince = Province::getAllProvince();
        $optionProvincePlaceBirth = FunctionLib::getOption($arrProvince, isset($data['person_province_place_of_birth']) ? $data['person_province_place_of_birth'] : Define::PROVINCE_HANOI);
        $optionProvinceHomeTown = FunctionLib::getOption($arrProvince, isset($data['person_province_home_town']) ? $data['person_province_home_town'] : Define::PROVINCE_HANOI);

        $person_province_current = isset($data['person_province_current']) ? $data['person_province_current'] : Define::PROVINCE_HANOI;
        $optionProvinceCurrent = FunctionLib::getOption($arrProvince, $person_province_current);
        $arrDistricts = Districts::getDistrictByProvinceId($person_province_current);
        $optionDistrictsCurrent = FunctionLib::getOption($arrDistricts, isset($data['person_districts_current']) ? $data['person_districts_current'] : 0);
        $person_districts_current = isset($data['person_districts_current']) ? $data['person_districts_current'] : 0;
        $arrWards = Wards::getWardsByDistrictId($person_districts_current);
        $optionWardsCurrent = FunctionLib::getOption($arrWards, isset($data['person_wards_current']) ? $data['person_wards_current'] : 0);

        return $this->viewOptionData = [
            'optionSex' => $optionSex,
            'optionDepart' => $optionDepart,
            'optionChucVu' => $optionChucVu,
            'optionChucDanhNgheNghiep' => $optionChucDanhNgheNghiep,
            'optionNhomMau' => $optionNhomMau,
            'optionDanToc' => $optionDanToc,
            'optionTonGiao' => $optionTonGiao,
            'optionProvincePlaceBirth' => $optionProvincePlaceBirth,
            'optionProvinceHomeTown' => $optionProvinceHomeTown,
            'optionProvinceCurrent' => $optionProvinceCurrent,
            'optionDistrictsCurrent' => $optionDistrictsCurrent,
            'optionWardsCurrent' => $optionWardsCurrent,
        ];
    }

    public function getDetail($personId)
    {

        $person_id = FunctionLib::outputId($personId);

        CGlobal::$pageAdminTitle = 'Thông tin chi tiết nhân sự';
        //Check phan quyen.
        if (!$this->is_root && ($this->user_id != $person_id)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //Thông tin khen thưởng
        $khenthuong = Bonus::getBonusByType($person_id, Define::BONUS_KHEN_THUONG);
        $arrTypeKhenthuong = HrDefine::getArrayByType(Define::khen_thuong);
        //Thông tin danh hieu
        $danhhieu = Bonus::getBonusByType($person_id, Define::BONUS_DANH_HIEU);
        $arrTypeDanhhieu = HrDefine::getArrayByType(Define::danh_hieu);

        //Thông tin kỷ luật
        $kyluat = Bonus::getBonusByType($person_id, Define::BONUS_KY_LUAT);
        $arrTypeKyluat = HrDefine::getArrayByType(Define::ky_luat);
        $this->getDataDefault();

        $arrDepart = Department::getDepartmentAll();
        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
        $arrDanToc = HrDefine::getArrayByType(Define::dan_toc);
        $arrTonGiao = $this->arrTonGiao;
        $arrNhomMau = HrDefine::getArrayByType(Define::nhom_mau);

        $arrCurriculumVitaeMain = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_DAO_TAO);
        $arrVanBangChungChi = HrDefine::getArrayByType(Define::van_bang_chung_chi);
        $arrHinhThucHoc = HrDefine::getArrayByType(Define::hinh_thuc_hoc);
        $arrChuyenNghanhDaoTao = HrDefine::getArrayByType(Define::chuyen_nghanh_dao_tao);

        $arrCurriculumVitaeOther = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_CHUNG_CHI_KHAC);
        $arrQuaTrinhCongTac = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_CONG_TAC);

        $arrHoatDongDang = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_HOAT_DONG_DANG);
        $arrChucVuDang = HrDefine::getArrayByType(Define::chuc_vu_doan_dang);

        $quanHeGiaDinh = Relationship::getRelationshipByPersonId($person_id);
        $arrQuanHeGiaDinh = HrDefine::getArrayByType(Define::quan_he_gia_dinh);

        $contractsPerson = HrContracts::getListContractsByPersonId($person_id);
        $arrLoaihopdong = HrDefine::getArrayByType(Define::loai_hop_dong);
        $arrChedothanhtoan = HrDefine::getArrayByType(Define::che_do_thanh_toan);

        $dbType = Define::CURRICULUMVITAE_QUANHE_DACDIEM_BANTHAN;
        $dataQuanHeDacDiemBanThan = CurriculumVitae::checkCurriculumVitaeByType($person_id, $dbType);

        //thông tin lương
        $luong = Salary::getSalaryByPersonId($person_id);
        $arrNgachBac = HrWageStepConfig::getArrayByType(Define::type_ngach_cong_chuc);
        //thông tin phu cap
        $phucap = Allowance::getAllowanceByPersonId($person_id);

        $infoPassPort = Person::getInfoPersonPassport($person_id);

        //Extent person
        $dataExtend = PersonExtend::getPersonExtendByPersonId($person_id);
        $arrChucDanhKHCN = HrDefine::getArrayByType(Define::chuc_danh_khoa_hoc_cong_nghe);
        $arrThanhphangiadinh = HrDefine::getArrayByType(Define::thanh_phan_gia_dinh);
        $arrQuanham = HrDefine::getArrayByType(Define::quan_ham);
        $arrHocvan = HrDefine::getArrayByType(Define::trinh_do_hoc_van);
        $arrHocHam = HrDefine::getArrayByType(Define::hoc_ham);
        $arrHocvi = HrDefine::getArrayByType(Define::hoc_vi);
        $arrLyluan_chinhtri = HrDefine::getArrayByType(Define::ly_luan_chinh_tri);
        $arrCongtac_danglam = HrDefine::getArrayByType(Define::cong_tac_chinh);
        $arrHangthuongbinh = HrDefine::getArrayByType(Define::hang_thuong_binh);
        $arrCapUy = HrDefine::getArrayByType(Define::cap_uy);
        $arrNgoaiNgu = HrDefine::getArrayByType(Define::ngoai_ngu);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.detail', array_merge([
            'person_id' => $person_id,
            'khenthuong' => $khenthuong,
            'danhhieu' => $danhhieu,
            'kyluat' => $kyluat,
            'arrTypeKhenthuong' => $arrTypeKhenthuong,
            'arrTypeDanhhieu' => $arrTypeDanhhieu,
            'arrTypeKyluat' => $arrTypeKyluat,
            'infoPerson' => $infoPerson,
            'arrDepart' => $arrDepart,
            'arrChucVu' => $arrChucVu,
            'arrChucDanhNgheNghiep' => $arrChucDanhNgheNghiep,
            'arrDanToc' => $arrDanToc,
            'arrTonGiao' => $arrTonGiao,
            'arrNhomMau' => $arrNhomMau,
            'arrVanBangChungChi' => $arrVanBangChungChi,
            'arrCurriculumVitaeMain' => $arrCurriculumVitaeMain,
            'arrHinhThucHoc' => $arrHinhThucHoc,
            'arrChuyenNghanhDaoTao' => $arrChuyenNghanhDaoTao,
            'arrCurriculumVitaeOther' => $arrCurriculumVitaeOther,
            'arrQuaTrinhCongTac' => $arrQuaTrinhCongTac,
            'arrHoatDongDang' => $arrHoatDongDang,
            'arrChucVuDang' => $arrChucVuDang,
            'quanHeGiaDinh' => $quanHeGiaDinh,
            'arrQuanHeGiaDinh' => $arrQuanHeGiaDinh,
            'contractsPerson' => $contractsPerson,
            'arrLoaihopdong' => $arrLoaihopdong,
            'arrChedothanhtoan' => $arrChedothanhtoan,
            'dataQuanHeDacDiemBanThan' => $dataQuanHeDacDiemBanThan,
            'luong' => $luong,
            'phucap' => $phucap,
            'arrNgachBac' => $arrNgachBac,
            'arrOptionPhuCap' => Define::$arrOptionPhuCap,
            'arrMethodPhuCap' => Define::$arrMethodPhuCap,
            'infoPassPort' => $infoPassPort,
            'dataExtend' => $dataExtend,
            'arrChucDanhKHCN' => $arrChucDanhKHCN,
            'arrThanhphangiadinh' => $arrThanhphangiadinh,
            'arrQuanham' => $arrQuanham,
            'arrHocvan' => $arrHocvan,
            'arrHocHam' => $arrHocHam,
            'arrHocvi' => $arrHocvi,
            'arrLyluan_chinhtri' => $arrLyluan_chinhtri,
            'arrCongtac_danglam' => $arrCongtac_danglam,
            'arrHangthuongbinh' => $arrHangthuongbinh,
            'arrCapUy' => $arrCapUy,
            'arrNgoaiNgu' => $arrNgoaiNgu,
        ], $this->viewPermission));
    }

    //get Person with account
    public function getPersonWithAccount($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = "Sửa Account nhân sự| " . CGlobal::web_name;
//        //check permission
        if (!$this->is_root && !in_array($this->person_creater_user, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($person_id > 0) {
            $data = User::getUserByPersonId($person_id);
            if (!$data && empty($data)) {
                $personInfo = Person::find($person_id);
                if ($personInfo) {
                    $data['user_name'] = strtolower(FunctionLib::safe_title($personInfo['person_name'], '_'));
                    $data['user_full_name'] = $personInfo['person_name'];
                    $data['user_email'] = $personInfo['person_mail'];
                    $data['user_sex'] = $personInfo['person_sex'];
                    $data['user_phone'] = $personInfo['person_phone'];
                    $data['telephone'] = $personInfo['person_telephone'];
                    $data['number_code'] = $personInfo['person_code'];
                    $data['address_register'] = $personInfo['person_address_current'];
                    $data['user_status'] = CGlobal::status_show;
                }
            } else {
                $this->error[] = 'Tài khoản này đã được tạo.';
            }
        }
        $this->getDataDefault();

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['user_status']) ? $data['user_status'] : CGlobal::status_show);
        $optionSex = FunctionLib::getOption($this->arrSex, isset($data['user_sex']) ? $data['user_sex'] : CGlobal::status_show);
        $optionRoleType = FunctionLib::getOption($this->arrRoleType, isset($data['role_type']) ? $data['role_type'] : Define::ROLE_TYPE_CUSTOMER);
        $arrDepart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($arrDepart, isset($data['user_depart_id']) ? $data['user_depart_id'] : 0);
        return view('hr.Person.addAccount', [
            'data' => $data,
            'person_id' => $person_id,
            'user_id' => isset($data['user_id']) ? $data['user_id'] : 0,
            'arrStatus' => $this->arrStatus,
            'optionStatus' => $optionStatus,
            'optionSex' => $optionSex,
            'optionRoleType' => $optionRoleType,
            'optionDepart' => $optionDepart,
            'error' => $this->error,
        ]);
    }

    //post Person with account
    public function postPersonWithAccount($personId)
    {
        //check permission
        if (!$this->is_root && !in_array($this->person_creater_user, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $person_id = FunctionLib::outputId($personId);
        $data['user_status'] = (int)Request::get('user_status', -1);
        $data['user_sex'] = (int)Request::get('user_sex', CGlobal::status_show);
        $data['user_full_name'] = htmlspecialchars(trim(Request::get('user_full_name', '')));
        $data['user_email'] = htmlspecialchars(trim(Request::get('user_email', '')));
        $data['user_phone'] = htmlspecialchars(trim(Request::get('user_phone', '')));
        $data['user_name'] = Request::get('user_name', '');
        $data['user_password'] = Request::get('user_password', '');
        $data['telephone'] = Request::get('telephone', '');
        $data['address_register'] = Request::get('address_register', '');
        $data['number_code'] = Request::get('number_code', '');
        $data['role_type'] = Request::get('role_type', 0);
        $data['user_depart_id'] = Request::get('user_depart_id', 0);
        $id = Request::get('user_id', 0);

        $this->validUser($id, $data);
        //FunctionLib::debug($this->error);

        //lấy phân quyền và menu view theo role
        if ($data['role_type'] > 0) {
            $infoPermiRole = RoleMenu::getInfoByRoleId((int)$data['role_type']);
            if ($infoPermiRole) {
                $dataInsert['user_group'] = (isset($infoPermiRole->role_group_permission) && trim($infoPermiRole->role_group_permission) != '') ? $infoPermiRole->role_group_permission : '';
                $dataInsert['user_group_menu'] = (isset($infoPermiRole->role_group_menu_id) && trim($infoPermiRole->role_group_menu_id) != '') ? $infoPermiRole->role_group_menu_id : '';
            }
        }
        if (empty($this->error)) {
            $groupRole = Role::getOptionRole();
            //Insert dữ liệu
            $dataInsert['user_depart_id'] = $data['user_depart_id'];
            $dataInsert['user_name'] = $data['user_name'];
            $dataInsert['user_email'] = $data['user_email'];
            $dataInsert['user_phone'] = $data['user_phone'];
            $dataInsert['telephone'] = $data['telephone'];
            $dataInsert['address_register'] = $data['address_register'];
            $dataInsert['number_code'] = $data['number_code'];
            $dataInsert['role_type'] = $data['role_type'];
            $dataInsert['role_name'] = isset($groupRole[$data['role_type']]) ? $groupRole[$data['role_type']] : '';
            $dataInsert['user_full_name'] = $data['user_full_name'];
            $dataInsert['user_status'] = (int)$data['user_status'];
            $dataInsert['user_edit_id'] = User::user_id();
            $dataInsert['user_edit_name'] = User::user_name();
            $dataInsert['user_parent'] = app(User::class)->get_user_project();
            $dataInsert['user_updated'] = time();

            if ($id > 0) {
                if (User::updateUser($id, $dataInsert)) {
                    return Redirect::route('hr.personnelView');
                } else {
                    $this->error[] = 'Lỗi truy xuất dữ liệu';;
                }
            } else {
                $dataInsert['user_create_id'] = User::user_id();
                $dataInsert['user_create_name'] = User::user_name();
                $dataInsert['user_created'] = time();
                $dataInsert['user_password'] = $data['user_password'];
                $dataInsert['user_object_id'] = $person_id;
                if (User::createNew($dataInsert)) {
                    return Redirect::route('hr.personnelView');
                } else {
                    $this->error[] = 'Lỗi truy xuất dữ liệu';;
                }
            }

        }
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['user_status']) ? $data['user_status'] : CGlobal::status_show);
        $optionSex = FunctionLib::getOption($this->arrSex, isset($data['user_sex']) ? $data['user_sex'] : CGlobal::status_show);
        $optionRoleType = FunctionLib::getOption($this->arrRoleType, isset($data['role_type']) ? $data['role_type'] : Define::ROLE_TYPE_CUSTOMER);
        $arrDepart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($arrDepart, isset($data['user_depart_id']) ? $data['user_depart_id'] : 0);
        return view('hr.Person.addAccount', [
            'data' => $data,
            'person_id' => $person_id,
            'user_id' => isset($data['user_id']) ? $data['user_id'] : 0,
            'arrStatus' => $this->arrStatus,
            'arrUserGroupMenu' => array(),
            'optionStatus' => $optionStatus,
            'optionSex' => $optionSex,
            'optionRoleType' => $optionRoleType,
            'optionDepart' => $optionDepart,
            'error' => $this->error,
        ]);
    }

    private function validUser($user_id = 0, $data = array())
    {
        if (!empty($data)) {
            if (isset($data['user_name']) && trim($data['user_name']) == '') {
                $this->error[] = 'Tài khoản đăng nhập không được bỏ trống';
            } elseif (isset($data['user_name']) && trim($data['user_name']) != '') {
                $checkIssetUser = User::getUserByName($data['user_name']);
                if ($checkIssetUser && $checkIssetUser->user_id != $user_id) {
                    $this->error[] = 'Tài khoản này đã tồn tại, hãy tạo lại';
                }
            }

            if (isset($data['user_full_name']) && trim($data['user_full_name']) == '') {
                $this->error[] = 'Tên nhân viên không được bỏ trống';
            }

            if (isset($data['user_email']) && trim($data['user_email']) == '') {
                $this->error[] = 'Mail không được bỏ trống';
            }
        }
        return true;
    }

    //ajax get thong tin cơ bản của nhân sự
    public function getInfoPerson()
    {
        //Check phan quyen.
        $personId = Request::get('str_person_id', '');
        $person_id = FunctionLib::outputId($personId);
        $data = array();
        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getInfoPerson($person_id);

        $infoPerson->ngach_bac = '';
        $arrNgachBac = HrWageStepConfig::getArrayByType(Define::type_ngach_cong_chuc);
        if (isset($infoPerson->salary) && count($infoPerson->salary) > 0) {
            $infoPerson->ngach_bac = isset($arrNgachBac[$infoPerson->salary[count($infoPerson->salary) - 1]->salary_civil_servants]) ? $arrNgachBac[$infoPerson->salary[count($infoPerson->salary) - 1]->salary_civil_servants] : '';
        }

        $infoPerson->phu_cap = '';
        $phucap = Allowance::getAllowanceByPersonId($person_id);
        if (sizeof($phucap) > 0) {
            $arrPhuCap = array();
            $arrOptionPhuCap = Define::$arrOptionPhuCap;
            foreach ($phucap as $item2) {
                if (isset($arrOptionPhuCap[$item2['allowance_type']])) {
                    $arrPhuCap[] = $arrOptionPhuCap[$item2['allowance_type']];
                }
            }
            $infoPerson->phu_cap = implode(', ', $arrPhuCap);
        }

        $arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
        $infoPerson->chuc_danh = '';
        if (isset($arrChucDanhNgheNghiep[$infoPerson->person_career_define_id])) {
            $infoPerson->chuc_danh = $arrChucDanhNgheNghiep[$infoPerson->person_career_define_id];
        }

        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.Person.infoPersonPopup', [
            'infoPerson' => $infoPerson,
            'person_id' => $person_id,
        ], $this->viewPermission)->render();

        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }

    //cap nhat trạng thái xóa của user
    public function statusDeletePerson($personId)
    {
        //check permission
        if (!$this->is_root && !in_array($this->permission_delete, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        $person_id = FunctionLib::outputId($personId);
        $infoPerson = Person::getPersonById($person_id);
        $status = ($infoPerson->person_status == Define::PERSON_STATUS_DAXOA) ? Define::PERSON_STATUS_DANGLAMVIEC : Define::PERSON_STATUS_DAXOA;
        $dataUpdate['person_status'] = $status;
        if (Person::updateItem($person_id, $dataUpdate)) {
            $account = User::getUserByObjectId($person_id);
            if (isset($account->user_id)) {
                $dataUpd['user_status'] = ($status == Define::PERSON_STATUS_DAXOA) ? Define::STATUS_BLOCK : Define::STATUS_SHOW;
                User::updateUser($account->user_id, $dataUpd);
            }
            return Redirect::route('hr.personnelView');
        } else {
            return Redirect::route('hr.personnelView');
        }
    }

    //xóa hẳn các thông tin liên quan đến user
    public function deletePerson($personId){
        $person_id = FunctionLib::outputId($personId);
        $data['success'] = 0;
        if(!$this->is_root && !in_array($this->permission_delete, $this->permission)&& !in_array($this->permission_full, $this->permission)){
            return Response::json($data);
        }
        if($person_id >0){
            if(Person::deleteItem($person_id)){
                $data['success'] = 1;
            }
        }
        return Response::json($data);
    }

    private function valid($data = array())
    {
        if (!empty($data)) {
            if (isset($data['person_name']) && trim($data['person_name']) == '') {
                $this->error[] = 'Họ và tên khai sinh KHÔNG được bỏ trống';
            }
            if (isset($data['person_chung_minh_thu']) && trim($data['person_chung_minh_thu']) == '') {
                $this->error[] = 'Số CMT KHÔNG được bỏ trống';
            }
            if (isset($data['person_depart_id']) && trim($data['person_depart_id']) <= 0) {
                $this->error[] = 'Chưa chọn Phòng ban đơn vị';
            }
            if (isset($data['person_date_range_cmt']) && trim($data['person_date_range_cmt']) <= 0) {
                $this->error[] = 'Chưa chọn ngày cấp CMT';
            }
            if (isset($data['person_date_start_work']) && trim($data['person_date_start_work']) <= 0) {
                $this->error[] = 'Chưa chọn ngày làm việc chính thức';
            }
            if (isset($data['person_wards_current']) && trim($data['person_wards_current']) <= 0) {
                $this->error[] = 'Chưa chọn phường xã hiện tại';
            }
            if (isset($data['person_address_place_of_birth']) && trim($data['person_address_place_of_birth']) == '') {
                $this->error[] = 'Địa chỉ nơi sinh KHÔNG được bỏ trống';
            }
            if (isset($data['person_address_home_town']) && trim($data['person_address_home_town']) == '') {
                $this->error[] = 'Địa chỉ quê quán KHÔNG được bỏ trống';
            }
            if (isset($data['person_address_current']) && trim($data['person_address_current']) == '') {
                $this->error[] = 'Địa chỉ hiện tại KHÔNG được bỏ trống';
            }
        }
        return true;
    }

    public function viewInfoPersonal()
    {

        $person_id = $this->user_object_id;

        CGlobal::$pageAdminTitle = 'Thông tin chi tiết cá nhân';
        //Check phan quyen.
        if ($person_id <= 0) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //Thông tin khen thưởng
        $khenthuong = Bonus::getBonusByType($person_id, Define::BONUS_KHEN_THUONG);
        $arrTypeKhenthuong = HrDefine::getArrayByType(Define::khen_thuong);
        //Thông tin danh hieu
        $danhhieu = Bonus::getBonusByType($person_id, Define::BONUS_DANH_HIEU);
        $arrTypeDanhhieu = HrDefine::getArrayByType(Define::danh_hieu);

        //Thông tin kỷ luật
        $kyluat = Bonus::getBonusByType($person_id, Define::BONUS_KY_LUAT);
        $arrTypeKyluat = HrDefine::getArrayByType(Define::ky_luat);
        $this->getDataDefault();

        $arrDepart = Department::getDepartmentAll();
        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
        $arrDanToc = HrDefine::getArrayByType(Define::dan_toc);
        $arrTonGiao = $this->arrTonGiao;
        $arrNhomMau = HrDefine::getArrayByType(Define::nhom_mau);

        $arrCurriculumVitaeMain = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_DAO_TAO);
        $arrVanBangChungChi = HrDefine::getArrayByType(Define::van_bang_chung_chi);
        $arrHinhThucHoc = HrDefine::getArrayByType(Define::hinh_thuc_hoc);
        $arrChuyenNghanhDaoTao = HrDefine::getArrayByType(Define::chuyen_nghanh_dao_tao);

        $arrCurriculumVitaeOther = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_CHUNG_CHI_KHAC);
        $arrQuaTrinhCongTac = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_CONG_TAC);

        $arrHoatDongDang = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_HOAT_DONG_DANG);
        $arrChucVuDang = HrDefine::getArrayByType(Define::chuc_vu_doan_dang);

        $quanHeGiaDinh = Relationship::getRelationshipByPersonId($person_id);
        $arrQuanHeGiaDinh = HrDefine::getArrayByType(Define::quan_he_gia_dinh);

        $contractsPerson = HrContracts::getListContractsByPersonId($person_id);
        $arrLoaihopdong = HrDefine::getArrayByType(Define::loai_hop_dong);
        $arrChedothanhtoan = HrDefine::getArrayByType(Define::che_do_thanh_toan);

        $dbType = Define::CURRICULUMVITAE_QUANHE_DACDIEM_BANTHAN;
        $dataQuanHeDacDiemBanThan = CurriculumVitae::checkCurriculumVitaeByType($person_id, $dbType);

        //thông tin lương
        $luong = Salary::getSalaryByPersonId($person_id);
        $arrNgachBac = HrWageStepConfig::getArrayByType(Define::type_ngach_cong_chuc);
        //thông tin phu cap
        $phucap = Allowance::getAllowanceByPersonId($person_id);

        $infoPassPort = Person::getInfoPersonPassport($person_id);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.detail', array_merge([
            'person_id' => $person_id,
            'khenthuong' => $khenthuong,
            'danhhieu' => $danhhieu,
            'kyluat' => $kyluat,
            'arrTypeKhenthuong' => $arrTypeKhenthuong,
            'arrTypeDanhhieu' => $arrTypeDanhhieu,
            'arrTypeKyluat' => $arrTypeKyluat,
            'infoPerson' => $infoPerson,
            'arrDepart' => $arrDepart,
            'arrChucVu' => $arrChucVu,
            'arrChucDanhNgheNghiep' => $arrChucDanhNgheNghiep,
            'arrDanToc' => $arrDanToc,
            'arrTonGiao' => $arrTonGiao,
            'arrNhomMau' => $arrNhomMau,
            'arrVanBangChungChi' => $arrVanBangChungChi,
            'arrCurriculumVitaeMain' => $arrCurriculumVitaeMain,
            'arrHinhThucHoc' => $arrHinhThucHoc,
            'arrChuyenNghanhDaoTao' => $arrChuyenNghanhDaoTao,
            'arrCurriculumVitaeOther' => $arrCurriculumVitaeOther,
            'arrQuaTrinhCongTac' => $arrQuaTrinhCongTac,
            'arrHoatDongDang' => $arrHoatDongDang,
            'arrChucVuDang' => $arrChucVuDang,
            'quanHeGiaDinh' => $quanHeGiaDinh,
            'arrQuanHeGiaDinh' => $arrQuanHeGiaDinh,
            'contractsPerson' => $contractsPerson,
            'arrLoaihopdong' => $arrLoaihopdong,
            'arrChedothanhtoan' => $arrChedothanhtoan,
            'dataQuanHeDacDiemBanThan' => $dataQuanHeDacDiemBanThan,
            'luong' => $luong,
            'phucap' => $phucap,
            'arrNgachBac' => $arrNgachBac,
            'arrOptionPhuCap' => Define::$arrOptionPhuCap,
            'arrMethodPhuCap' => Define::$arrMethodPhuCap,
            'infoPassPort' => $infoPassPort,
        ], $this->viewPermission));
    }

    public function exportData($data, $title = '', $type = 1)
    {
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
        if ($type == 2) {
            $str = ' SẮP TĂNG LƯƠNG';
        } elseif ($type == 3) {
            $str = ' SẮP HẾT HẠN HỢP ĐỒNG';
        } elseif ($type == 4) {
            $str = ' ĐẢNG VIÊN';
        } elseif ($type == 5) {
            $str = ' SẮP NGHỈ HƯU';
        }

        $sheet->SetCellValue('A1', 'BÁO CÁO DANH SÁCH NHÂN SỰ' . $str);

        // setting header
        $position_hearder = 2;
        $sheet->getRowDimension($position_hearder)->setRowHeight(30);

        //$type:
        // 1: Thong ke bao cao nhan su---
        // 2:Nhan su sap tang luong---
        // 3: Nhan su sap het han hop dong---
        // 4: Nhan su la dang vien--
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

            if ($type == 1) {
                $sheet->getStyle('L' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('L' . $rowCount, ($v['person_ngayvao_dang'] != 0) ? date('d/m/Y', $v['person_ngayvao_dang']) : '');

                $sheet->getStyle('M' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('M' . $rowCount, ($v['person_date_start_work'] != 0) ? date('d/m/Y', $v['person_date_start_work']) : '');

                $sheet->getStyle('N' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('N' . $rowCount, isset($arrLoaihopdong[$v['person_type_contracts']]) ? $arrLoaihopdong[$v['person_type_contracts']] : '');
            } elseif ($type == 2) {

                $sheet->getStyle('L' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('L' . $rowCount, ($v['person_date_start_work'] != 0) ? date('d/m/Y', $v['person_date_start_work']) : '');

                $sheet->getStyle('M' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('M' . $rowCount, isset($arrLoaihopdong[$v['person_type_contracts']]) ? $arrLoaihopdong[$v['person_type_contracts']] : '');

                $sheet->getStyle('N' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('N' . $rowCount, ($v['person_date_salary_increase'] != 0) ? date('d/m/Y', $v['person_date_salary_increase']) : '');
            } elseif ($type == 3) {

                $sheet->getStyle('L' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('L' . $rowCount, ($v['person_date_start_work'] != 0) ? date('d/m/Y', $v['person_date_start_work']) : '');

                $sheet->getStyle('M' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('M' . $rowCount, isset($arrLoaihopdong[$v['person_type_contracts']]) ? $arrLoaihopdong[$v['person_type_contracts']] : '');

                $sheet->getStyle('N' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('N' . $rowCount, ($v['contracts_dealine_date'] != 0) ? date('d/m/Y', $v['contracts_dealine_date']) : '');
            } elseif ($type == 4) {
                $sheet->getStyle('L' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('L' . $rowCount, ($v['person_ngayvao_dang'] != 0) ? date('d/m/Y', $v['person_ngayvao_dang']) : '');
            } elseif ($type == 5) {
                $sheet->getStyle('L' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('L' . $rowCount, ($v['person_date_start_work'] != 0) ? date('d/m/Y', $v['person_date_start_work']) : '');

                $sheet->getStyle('M' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $sheet->SetCellValue('M' . $rowCount, ($v['retirement_date'] != 0) ? date('d/m/Y', $v['retirement_date']) : '');
            }
            $rowCount++;
            $i++;
        }

        ob_clean();

        $filename = "Danh sach nhan su" . "_" . date("_d/m_");
        @header("Cache-Control: ");
        @header("Pragma: ");
        @header("Content-type: application/octet-stream");
        @header('Content-Disposition: attachment; filename="' . $filename . '.xls"');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save("php://output");
        exit();
    }

    public function buildHeadExcel($type = 1)
    {
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

        if ($type == 1) {
            $ary_cell += array(
                'L' => array('w' => self::val18, 'val' => 'Ngày vào Đảng', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'M' => array('w' => self::val18, 'val' => 'Ngày làm việc', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'N' => array('w' => self::val18, 'val' => 'Loại hợp đồng', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
        } elseif ($type == 2) {
            $ary_cell += array(
                'L' => array('w' => self::val18, 'val' => 'Ngày làm việc', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'M' => array('w' => self::val18, 'val' => 'Loại hợp đồng', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'N' => array('w' => self::val18, 'val' => 'Ngày tăng lương', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
        } elseif ($type == 3) {
            $ary_cell += array(
                'L' => array('w' => self::val18, 'val' => 'Ngày làm việc', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'M' => array('w' => self::val18, 'val' => 'Loại hợp đồng', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'N' => array('w' => self::val18, 'val' => 'Ngày hết hạn HĐ', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
        } elseif ($type == 4) {
            $ary_cell += array(
                'L' => array('w' => self::val10, 'val' => 'Ngày vào Đảng', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
        } elseif ($type == 5) {
            $ary_cell += array(
                'L' => array('w' => self::val10, 'val' => 'Ngày làm việc', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'M' => array('w' => self::val10, 'val' => 'Ngày nghỉ hưu', 'align' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
        }
        return $ary_cell;
    }
}
