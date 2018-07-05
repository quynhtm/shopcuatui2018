<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Districts;
use App\Http\Models\Admin\Province;
use App\Http\Models\Admin\Wards;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Admin\Role;
use App\Http\Models\Hr\PersonExtend;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class PersonExtendController extends BaseAdminController
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
    private $arrTrueFasle = array();
    private $viewPermission = array();//check quyen
    private $viewOptionData = array();

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

        $this->arrTrueFasle = array(
            CGlobal::status_hide => 'Không',
            CGlobal::status_show => 'Có');
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_root' => $this->is_root ? 1 : 0,
            'permission_edit' => in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create' => in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_delete' => in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full' => in_array($this->permission_full, $this->permission) ? 1 : 0,
            'person_creater_user' => in_array($this->person_creater_user, $this->permission) ? 1 : 0,
        ];
    }

    public function getItem($personId)
    {
        CGlobal::$pageAdminTitle = 'Bổ xung thêm thông tin nhân sự';
        $person_id = FunctionLib::outputId($personId);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($person_id > 0) {
            $data = PersonExtend::getPersonExtendByPersonId($person_id);
        }

        $this->getDataDefault();
        $this->viewOptionData($data);
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonExtend.add', array_merge([
            'data' => $data,
            'id' => $person_id,
            'infoPerson' => $infoPerson,
        ], $this->viewOptionData, $this->viewPermission));
    }

    public function postItem($personId)
    {
        CGlobal::$pageAdminTitle = 'Bổ xung thêm thông tin nhân sự';
        $person_id = FunctionLib::outputId($personId);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        if ($person_id <= 0) {
            return Redirect::route('hr.personnelView');
        }
        $id_hiden = Request::get('id_hiden', '');
        $data = $_POST;

        $data['person_extend_ngaytuyendung'] = (isset($data['person_extend_ngaytuyendung']) && $data['person_extend_ngaytuyendung'] != '') ? strtotime($data['person_extend_ngaytuyendung']) : 0;
        $data['person_extend_ngaylamviec'] = (isset($data['person_extend_ngaylamviec']) && $data['person_extend_ngaylamviec'] != '') ? strtotime($data['person_extend_ngaylamviec']) : 0;
        $data['person_extend_ngaythamgia_cachmang'] = (isset($data['person_extend_ngaythamgia_cachmang']) && $data['person_extend_ngaythamgia_cachmang'] != '') ? strtotime($data['person_extend_ngaythamgia_cachmang']) : 0;
        $data['person_extend_ngayvaodang'] = (isset($data['person_extend_ngayvaodang']) && $data['person_extend_ngayvaodang'] != '') ? strtotime($data['person_extend_ngayvaodang']) : 0;
        $data['person_extend_ngayvaodang_chinhthuc'] = (isset($data['person_extend_ngayvaodang_chinhthuc']) && $data['person_extend_ngayvaodang_chinhthuc'] != '') ? strtotime($data['person_extend_ngayvaodang_chinhthuc']) : 0;
        $data['person_extend_ngaythamgia_tochuc'] = (isset($data['person_extend_ngaythamgia_tochuc']) && $data['person_extend_ngaythamgia_tochuc'] != '') ? strtotime($data['person_extend_ngaythamgia_tochuc']) : 0;
        $data['person_extend_ngaynhapngu'] = (isset($data['person_extend_ngaynhapngu']) && $data['person_extend_ngaynhapngu'] != '') ? strtotime($data['person_extend_ngaynhapngu']) : 0;
        $data['person_extend_ngayxuatngu'] = (isset($data['person_extend_ngayxuatngu']) && $data['person_extend_ngayxuatngu'] != '') ? strtotime($data['person_extend_ngayxuatngu']) : 0;
        $data['person_extend_person_id'] = $person_id;

        if ($this->valid($data) && empty($this->error)) {
            $person_id = ($person_id == 0) ? FunctionLib::outputId($id_hiden) : $person_id;
            $person_extend_id = 0;
            if ($person_id > 0) {
                $dataPersonExtend = PersonExtend::getPersonExtendByPersonId($person_id);
                $person_extend_id = (isset($dataPersonExtend->person_extend_id) && $dataPersonExtend->person_extend_id > 0) ? $dataPersonExtend->person_extend_id : $person_extend_id;
            }
            if ($person_extend_id > 0) {
                //cap nhat
                if (PersonExtend::updateItem($person_extend_id, $data)) {
                    return Redirect::route('hr.personnelView');
                }
            } else {
                //them moi
                if (PersonExtend::createItem($data)) {
                    return Redirect::route('hr.personnelView');
                }
            }
        }

        $this->getDataDefault();
        $this->viewOptionData($data);
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonExtend.add', array_merge([
            'data' => $data,
            'id' => $person_id,
            'infoPerson' => $infoPerson,
            'error' => $this->error,
        ], $this->viewOptionData, $this->viewPermission));
    }

    public function viewOptionData($data)
    {
        $arrYears = FunctionLib::getListYears();
        $optionYears_namdat_qlnn = FunctionLib::getOption($arrYears, isset($data['person_extend_namdat_qlnn']) ? $data['person_extend_namdat_qlnn'] : 0);
        $optionYears_namdat_tinhoc = FunctionLib::getOption($arrYears, isset($data['person_extend_namdat_tinhoc']) ? $data['person_extend_namdat_tinhoc'] : 0);
        $optionYears_namdat_hoc_ham = FunctionLib::getOption($arrYears, isset($data['person_extend_namdat_hoc_ham']) ? $data['person_extend_namdat_hoc_ham'] : 0);
        $optionYears_namdat_hoc_vi = FunctionLib::getOption($arrYears, isset($data['person_extend_namdat_hoc_vi']) ? $data['person_extend_namdat_hoc_vi'] : 0);
        $optionYears_namdat_lyluan_chinhtri = FunctionLib::getOption($arrYears, isset($data['person_extend_namdat_lyluan_chinhtri']) ? $data['person_extend_namdat_lyluan_chinhtri'] : 0);

        $depart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($depart, isset($data['person_depart_id']) ? $data['person_depart_id'] : 0);

        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $arrChucVu = !empty($arrChucVu) ? (Define::$arrCheckDefault + $arrChucVu) : Define::$arrCheckDefault;
        $optionChucVu = FunctionLib::getOption($arrChucVu, isset($data['person_extend_chucvu_hiennay']) ? $data['person_extend_chucvu_hiennay'] : 0);

        $arrChucDanhKHCN = HrDefine::getArrayByType(Define::chuc_danh_khoa_hoc_cong_nghe);
        $arrChucDanhKHCN = !empty($arrChucDanhKHCN) ? (Define::$arrCheckDefault + $arrChucDanhKHCN) : Define::$arrCheckDefault;
        $optionChucDanhKHCN= FunctionLib::getOption($arrChucDanhKHCN, isset($data['person_extend_chucdanh_khcn']) ? $data['person_extend_chucdanh_khcn'] : 0);

        $arrCapUy = HrDefine::getArrayByType(Define::cap_uy);
        $arrCapUy = !empty($arrCapUy) ? (Define::$arrCheckDefault + $arrCapUy) : Define::$arrCheckDefault;
        $optionCapUy_hiennay = FunctionLib::getOption($arrCapUy, isset($data['person_extend_capuy_hiennay']) ? $data['person_extend_capuy_hiennay'] : 0);
        $optionCapUy_kiemnhiem = FunctionLib::getOption($arrCapUy, isset($data['person_extend_capuy_kiemnhiem']) ? $data['person_extend_capuy_kiemnhiem'] : 0);

        $arrThanhphangiadinh = HrDefine::getArrayByType(Define::thanh_phan_gia_dinh);
        $arrThanhphangiadinh = !empty($arrThanhphangiadinh) ? (Define::$arrCheckDefault + $arrThanhphangiadinh) : Define::$arrCheckDefault;
        $optionThanhphan_giadinh = FunctionLib::getOption($arrThanhphangiadinh, isset($data['person_extend_thanhphan_giadinh']) ? $data['person_extend_thanhphan_giadinh'] : 0);

        $arrCongtac_danglam = HrDefine::getArrayByType(Define::cong_tac_chinh);
        $arrCongtac_danglam = !empty($arrCongtac_danglam) ? (Define::$arrCheckDefault + $arrCongtac_danglam) : Define::$arrCheckDefault;
        $optionCongtac_danglam = FunctionLib::getOption($arrCongtac_danglam, isset($data['person_extend_congtac_danglam']) ? $data['person_extend_congtac_danglam'] : 0);
        $optionCongviec_launhat = FunctionLib::getOption($arrCongtac_danglam, isset($data['person_extend_congviec_launhat']) ? $data['person_extend_congviec_launhat'] : 0);

        $arrQL_nha_nuoc = HrDefine::getArrayByType(Define::trinh_do_ql_nhanuoc);
        $arrQL_nha_nuoc = !empty($arrQL_nha_nuoc) ? (Define::$arrCheckDefault + $arrQL_nha_nuoc) : Define::$arrCheckDefault;
        $optionQL_nha_nuoc = FunctionLib::getOption($arrQL_nha_nuoc, isset($data['person_extend_trinhdo_quanly_nhanuoc']) ? $data['person_extend_trinhdo_quanly_nhanuoc'] : 0);

        $arrQuanham = HrDefine::getArrayByType(Define::quan_ham);
        $arrQuanham = !empty($arrQuanham) ? (Define::$arrCheckDefault + $arrQuanham) : Define::$arrCheckDefault;
        $optionQuanham = FunctionLib::getOption($arrQuanham, isset($data['person_extend_chucvu_quanngu']) ? $data['person_extend_chucvu_quanngu'] : 0);

        $arrHangthuongbinh = HrDefine::getArrayByType(Define::hang_thuong_binh);
        $arrHangthuongbinh = !empty($arrHangthuongbinh) ? (Define::$arrCheckDefault + $arrHangthuongbinh) : Define::$arrCheckDefault;
        $optionThuongbinh = FunctionLib::getOption($arrHangthuongbinh, isset($data['person_extend_thuongbinh']) ? $data['person_extend_thuongbinh'] : 0);

        $arrHocvan = HrDefine::getArrayByType(Define::trinh_do_hoc_van);
        $arrHocvan = !empty($arrHocvan) ? (Define::$arrCheckDefault + $arrHocvan) : Define::$arrCheckDefault;
        $optionHocvan = FunctionLib::getOption($arrHocvan, isset($data['person_extend_trinhdo_hocvan']) ? $data['person_extend_trinhdo_hocvan'] : 0);

        $arrTinhoc = HrDefine::getArrayByType(Define::trinh_do_tin_hoc);
        $arrTinhoc = !empty($arrTinhoc) ? (Define::$arrCheckDefault + $arrTinhoc) : Define::$arrCheckDefault;
        $optionTinhoc = FunctionLib::getOption($arrTinhoc, isset($data['person_extend_trinhdo_tinhoc']) ? $data['person_extend_trinhdo_tinhoc'] : 0);

        $arrHocHam = HrDefine::getArrayByType(Define::hoc_ham);
        $arrHocHam = !empty($arrHocHam) ? (Define::$arrCheckDefault + $arrHocHam) : Define::$arrCheckDefault;
        $optionHocHam = FunctionLib::getOption($arrHocHam, isset($data['person_extend_hoc_ham']) ? $data['person_extend_hoc_ham'] : 0);

        $arrHocvi = HrDefine::getArrayByType(Define::hoc_vi);
        $arrHocvi = !empty($arrHocvi) ? (Define::$arrCheckDefault + $arrHocvi) : Define::$arrCheckDefault;
        $optionHocvi = FunctionLib::getOption($arrHocvi, isset($data['person_extend_hoc_vi']) ? $data['person_extend_hoc_vi'] : 0);

        $arrLyluan_chinhtri = HrDefine::getArrayByType(Define::ly_luan_chinh_tri);
        $arrLyluan_chinhtri = !empty($arrLyluan_chinhtri) ? (Define::$arrCheckDefault + $arrLyluan_chinhtri) : Define::$arrCheckDefault;
        $optionLyluan_chinhtri = FunctionLib::getOption($arrLyluan_chinhtri, isset($data['person_extend_lyluan_chinhtri']) ? $data['person_extend_lyluan_chinhtri'] : 0);

        $arrNgoaiNgu = HrDefine::getArrayByType(Define::ngoai_ngu);
        $arrNgoaiNgu = !empty($arrNgoaiNgu) ? (Define::$arrCheckDefault + $arrNgoaiNgu) : Define::$arrCheckDefault;
        $optionNgoaiNgu_1 = FunctionLib::getOption($arrNgoaiNgu, isset($data['person_extend_language_1']) ? $data['person_extend_language_1'] : 0);
        $optionNgoaiNgu_2 = FunctionLib::getOption($arrNgoaiNgu, isset($data['person_extend_language_2']) ? $data['person_extend_language_2'] : 0);
        $optionNgoaiNgu_3 = FunctionLib::getOption($arrNgoaiNgu, isset($data['person_extend_language_3']) ? $data['person_extend_language_3'] : 0);
        $optionNgoaiNgu_4 = FunctionLib::getOption($arrNgoaiNgu, isset($data['person_extend_language_4']) ? $data['person_extend_language_4'] : 0);

        $arrTrinhdoNgoaiNgu = HrDefine::getArrayByType(Define::trinh_do_ngoai_ngu);
        $arrTrinhdoNgoaiNgu = !empty($arrTrinhdoNgoaiNgu) ? (Define::$arrCheckDefault + $arrTrinhdoNgoaiNgu) : Define::$arrCheckDefault;
        $optionTrinhdoNgoaiNgu_1 = FunctionLib::getOption($arrTrinhdoNgoaiNgu, isset($data['person_extend_trinhdo_1']) ? $data['person_extend_trinhdo_1'] : 0);
        $optionTrinhdoNgoaiNgu_2 = FunctionLib::getOption($arrTrinhdoNgoaiNgu, isset($data['person_extend_trinhdo_2']) ? $data['person_extend_trinhdo_2'] : 0);
        $optionTrinhdoNgoaiNgu_3 = FunctionLib::getOption($arrTrinhdoNgoaiNgu, isset($data['person_extend_trinhdo_3']) ? $data['person_extend_trinhdo_3'] : 0);
        $optionTrinhdoNgoaiNgu_4 = FunctionLib::getOption($arrTrinhdoNgoaiNgu, isset($data['person_extend_trinhdo_4']) ? $data['person_extend_trinhdo_4'] : 0);

        $optionDangVien = FunctionLib::getOption($this->arrTrueFasle, isset($data['person_extend_is_dangvien']) ? $data['person_extend_is_dangvien'] : 0);

        return $this->viewOptionData = [
            'optionYears_namdat_qlnn' => $optionYears_namdat_qlnn,
            'optionYears_namdat_tinhoc' => $optionYears_namdat_tinhoc,
            'optionYears_namdat_hoc_ham' => $optionYears_namdat_hoc_ham,
            'optionYears_namdat_hoc_vi' => $optionYears_namdat_hoc_vi,
            'optionYears_namdat_lyluan_chinhtri' => $optionYears_namdat_lyluan_chinhtri,
            'optionDepart' => $optionDepart,
            'optionChucVu' => $optionChucVu,
            'optionChucDanhKHCN' => $optionChucDanhKHCN,
            'optionCapUy_hiennay' => $optionCapUy_hiennay,
            'optionCapUy_kiemnhiem' => $optionCapUy_kiemnhiem,
            'optionThanhphan_giadinh' => $optionThanhphan_giadinh,
            'optionCongtac_danglam' => $optionCongtac_danglam,
            'optionCongviec_launhat' => $optionCongviec_launhat,
            'optionQL_nha_nuoc' => $optionQL_nha_nuoc,
            'optionQuanham' => $optionQuanham,
            'optionHocvan' => $optionHocvan,
            'optionTinhoc' => $optionTinhoc,
            'optionHocvi' => $optionHocvi,
            'optionHocHam' => $optionHocHam,
            'optionLyluan_chinhtri' => $optionLyluan_chinhtri,
            'optionNgoaiNgu_1' => $optionNgoaiNgu_1,
            'optionNgoaiNgu_2' => $optionNgoaiNgu_2,
            'optionNgoaiNgu_3' => $optionNgoaiNgu_3,
            'optionNgoaiNgu_4' => $optionNgoaiNgu_4,
            'optionTrinhdoNgoaiNgu_1' => $optionTrinhdoNgoaiNgu_1,
            'optionTrinhdoNgoaiNgu_2' => $optionTrinhdoNgoaiNgu_2,
            'optionTrinhdoNgoaiNgu_3' => $optionTrinhdoNgoaiNgu_3,
            'optionTrinhdoNgoaiNgu_4' => $optionTrinhdoNgoaiNgu_4,
            'optionDangVien' => $optionDangVien,
            'optionThuongbinh' => $optionThuongbinh,
        ];
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

}
