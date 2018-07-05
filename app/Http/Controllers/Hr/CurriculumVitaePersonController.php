<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\CurriculumVitae;
use App\Http\Models\Hr\Relationship;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class CurriculumVitaePersonController extends BaseAdminController
{
    //lý lịch 2C
    private $personCurriculumVitaeView = 'personCurriculumVitaeView';
    private $personCurriculumVitaeFull = 'personCurriculumVitaeFull';
    private $personCurriculumVitaeDelete = 'personCurriculumVitaeDelete';
    private $personCurriculumVitaeCreate = 'personCurriculumVitaeCreate';

    private $arrStatus = array(1 => 'hiển thị', 2 => 'Ẩn');
    private $viewPermission = array();//check quyen

    public function __construct()
    {
        parent::__construct();

    }

    public function getDataDefault()
    {
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden', $this->languageSite));
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_root' => $this->is_root ? 1 : 0,
            //contracts
            'personCurriculumVitaeFull' => in_array($this->personCurriculumVitaeFull, $this->permission) ? 1 : 0,
            'personCurriculumVitaeView' => in_array($this->personCurriculumVitaeView, $this->permission) ? 1 : 0,
            'personCurriculumVitaeCreate' => in_array($this->personCurriculumVitaeCreate, $this->permission) ? 1 : 0,
            'personCurriculumVitaeDelete' => in_array($this->personCurriculumVitaeDelete, $this->permission) ? 1 : 0,
        ];
    }


    /************************************************************************************************************************************
     * Thông tin lý lịch 2C
     ************************************************************************************************************************************/
    public function viewCurriculumVitae($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Lý lịch 2C';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeView, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //Quá trình đào tạo
        $quatrinhdaotao = CurriculumVitae::getCurriculumVitaeByType($person_id,Define::CURRICULUMVITAE_DAO_TAO);

        //van bang chung chi khac
        $vanbangchungchikhac = CurriculumVitae::getCurriculumVitaeByType($person_id,Define::CURRICULUMVITAE_CHUNG_CHI_KHAC);

        //Quá trình công tác
        $quatrinhcongtac = CurriculumVitae::getCurriculumVitaeByType($person_id,Define::CURRICULUMVITAE_CONG_TAC);

        //Hoạt động đảng
        $hoatdongdang = CurriculumVitae::getCurriculumVitaeByType($person_id,Define::CURRICULUMVITAE_HOAT_DONG_DANG);

        //quan he gia dinh
        $quanhegiadinh = Relationship::getRelationshipByPersonId($person_id);
        $arrQuanHeGiaDinh = HrDefine::getArrayByType(Define::quan_he_gia_dinh);

        //common
        $arrVanBangChungChi = HrDefine::getArrayByType(Define::van_bang_chung_chi);
        $arrHinhThucHoc = HrDefine::getArrayByType(Define::hinh_thuc_hoc);
        $arrChuyenNghanhDaoTao = HrDefine::getArrayByType(Define::chuyen_nghanh_dao_tao);
        $arrChucVuDang = HrDefine::getArrayByType(Define::chuc_vu_doan_dang);

        //Quan hệ nước ngoài, đặc điểm bản thân
        $dbType = Define::CURRICULUMVITAE_QUANHE_DACDIEM_BANTHAN;
        $dataQuanHeDacDiemBanThan = CurriculumVitae::checkCurriculumVitaeByType($person_id, $dbType);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.CurriculumVitaePerson.CurriculumVitaeView', array_merge([
            'person_id' => $person_id,
            'infoPerson' => $infoPerson,
            'arrVanBangChungChi' => $arrVanBangChungChi,
            'arrHinhThucHoc' => $arrHinhThucHoc,
            'arrChuyenNghanhDaoTao' => $arrChuyenNghanhDaoTao,
            'arrChucVuDang' => $arrChucVuDang,

            'quatrinhdaotao' => $quatrinhdaotao,
            'vanbangchungchikhac' => $vanbangchungchikhac,
            'quatrinhcongtac' => $quatrinhcongtac,
            'hoatdongdang' => $hoatdongdang,

            'quanhegiadinh' => $quanhegiadinh,
            'arrQuanHeGiaDinh' => $arrQuanHeGiaDinh,
            'dataQuanHeDacDiemBanThan' => $dataQuanHeDacDiemBanThan,
        ], $this->viewPermission));
    }

    /************************************************************************************************************************************
     * Quá trình công tác,học tập
     ************************************************************************************************************************************/
    public function editStudy()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $curriculumId = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');

        $person_id = FunctionLib::outputId($personId);
        $curriculum_id = FunctionLib::outputId($curriculumId);
        //thông tin chung
        $curriculum = CurriculumVitae::find($curriculum_id);

        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        $template = '';
        if ($typeAction == Define::CURRICULUMVITAE_DAO_TAO) {
            $template = 'daotaodaihanPopupAdd';
        } elseif ($typeAction == Define::CURRICULUMVITAE_CHUNG_CHI_KHAC) {
            $template = 'daotaokhacPopupAdd';
        }elseif ($typeAction == Define::CURRICULUMVITAE_CONG_TAC) {
            $template = 'quatrinhcongtacPopupAdd';
        } else {
            $template = 'hoatdongdangPopupAdd';
        }

        $arrMonth = FunctionLib::getListMonth();
        $arrYears = FunctionLib::getListYears();

        $optionMonthIn = FunctionLib::getOption($arrMonth, isset($curriculum['curriculum_month_in']) ? $curriculum['curriculum_month_in'] : 1);
        $optionMonthOut = FunctionLib::getOption($arrMonth, isset($curriculum['curriculum_month_out']) ? $curriculum['curriculum_month_out'] : 1);

        $optionYearsIn = FunctionLib::getOption($arrYears, isset($curriculum['curriculum_year_in']) ? $curriculum['curriculum_year_in'] : (int)date('Y', time()));
        $optionYearsOut = FunctionLib::getOption($arrYears, isset($curriculum['curriculum_year_out']) ? $curriculum['curriculum_year_out'] : (int)date('Y', time()));

        //van bang chung chỉ
        $arrVanBangChungChi = HrDefine::getArrayByType(Define::van_bang_chung_chi);
        $optionVanBangChungChi = FunctionLib::getOption($arrVanBangChungChi, isset($curriculum['curriculum_certificate_id']) ? $curriculum['curriculum_certificate_id'] : '');

        //Hình thức học
        $arrHinhThucHoc = HrDefine::getArrayByType(Define::hinh_thuc_hoc);
        $optionHinhThucHoc = FunctionLib::getOption($arrHinhThucHoc, isset($curriculum['curriculum_formalities_id']) ? $curriculum['curriculum_formalities_id'] : '');

        //Chuyen nghanh dao tao
        $arrChuyenNghanhDaoTao = HrDefine::getArrayByType(Define::chuyen_nghanh_dao_tao);
        $optionChuyenNghanhDaoTao = FunctionLib::getOption($arrChuyenNghanhDaoTao, isset($curriculum['curriculum_training_id']) ? $curriculum['curriculum_training_id'] : '');

        //Chức vụ đoàn thể
        $arrChucVuDang = HrDefine::getArrayByType(Define::chuc_vu_doan_dang);
        $optionChucVuDang = FunctionLib::getOption($arrChucVuDang, isset($curriculum['curriculum_chucvu_id']) ? $curriculum['curriculum_chucvu_id'] : '');

        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.CurriculumVitaePerson.' . $template, [
            'curriculum' => $curriculum,
            'infoPerson' => $infoPerson,
            'optionHinhThucHoc' => $optionHinhThucHoc,
            'optionVanBangChungChi' => $optionVanBangChungChi,
            'optionChuyenNghanhDaoTao' => $optionChuyenNghanhDaoTao,
            'optionChucVuDang' => $optionChucVuDang,

            'optionYearsIn' => $optionYearsIn,
            'optionYearsOut' => $optionYearsOut,
            'optionMonthIn' => $optionMonthIn,
            'optionMonthOut' => $optionMonthOut,
            'person_id' => $person_id,
            'curriculum_id' => $curriculum_id,
            'typeAction' => $typeAction,
        ], $this->viewPermission)->render();
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }
    public function postStudy()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = (int)Request::get('curriculum_person_id', '');
        $curriculum_id = (int)Request::get('curriculum_id', '');
        //FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if ((isset($data['curriculum_address_train']) && $data['curriculum_address_train'] == '') || (isset($data['curriculum_name']) && $data['curriculum_name'] == '')) {
            $arrData = ['intReturn' => 0, 'msg' => 'Dữ liệu nhập không đủ'];
        } else {
            if ($person_id > 0) {
                if ($curriculum_id > 0) {
                    CurriculumVitae::updateItem($curriculum_id, $data);
                } else {
                    CurriculumVitae::createItem($data);
                }
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];

                //thông tin view list\
                $dataList = array();
                if ($data['curriculum_type'] == Define::CURRICULUMVITAE_DAO_TAO) {
                    $dataList = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_DAO_TAO);
                    $template = 'daotaodaihanList';
                } elseif ($data['curriculum_type'] == Define::CURRICULUMVITAE_CHUNG_CHI_KHAC) {
                    $dataList = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_CHUNG_CHI_KHAC);
                    $template = 'daotaokhacList';
                } elseif ($data['curriculum_type'] == Define::CURRICULUMVITAE_CONG_TAC) {
                    $dataList = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_CONG_TAC);
                    $template = 'quatrinhcongtacList';
                } else {
                    $dataList = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_HOAT_DONG_DANG);
                    $template = 'hoatdongdangList';
                }

                //common
                $arrVanBangChungChi = HrDefine::getArrayByType(Define::van_bang_chung_chi);
                $arrHinhThucHoc = HrDefine::getArrayByType(Define::hinh_thuc_hoc);
                $arrChuyenNghanhDaoTao = HrDefine::getArrayByType(Define::chuyen_nghanh_dao_tao);
                $arrChucVuDang = HrDefine::getArrayByType(Define::chuc_vu_doan_dang);

                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();
                $html = view('hr.CurriculumVitaePerson.' . $template, array_merge([
                    'person_id' => $person_id,
                    'dataList' => $dataList,
                    'arrVanBangChungChi' => $arrVanBangChungChi,
                    'arrHinhThucHoc' => $arrHinhThucHoc,
                    'arrChuyenNghanhDaoTao' => $arrChuyenNghanhDaoTao,
                    'arrChucVuDang' => $arrChucVuDang,
                ], $this->viewPermission))->render();
                $arrData['html'] = $html;
            } else {
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật' . $person_id];
            }
        }
        return response()->json($arrData);
    }
    public function deleteStudy()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $curriculumId = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');
        $person_id = FunctionLib::outputId($personId);
        $curriculum_id = FunctionLib::outputId($curriculumId);
        if ($curriculum_id > 0 && CurriculumVitae::deleteItem($curriculum_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
            //thông tin view list\
            //thông tin view list\
            $dataList = array();
            if ($typeAction == Define::CURRICULUMVITAE_DAO_TAO) {
                $dataList = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_DAO_TAO);
                $template = 'daotaodaihanList';
            } elseif ($typeAction == Define::CURRICULUMVITAE_CHUNG_CHI_KHAC) {
                $dataList = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_CHUNG_CHI_KHAC);
                $template = 'daotaokhacList';
            }elseif ($typeAction == Define::CURRICULUMVITAE_CONG_TAC) {
                $dataList = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_CONG_TAC);
                $template = 'quatrinhcongtacList';
            } else {
                $dataList = CurriculumVitae::getCurriculumVitaeByType($person_id, Define::CURRICULUMVITAE_HOAT_DONG_DANG);
                $template = 'hoatdongdangList';
            }

            //common
            $arrVanBangChungChi = HrDefine::getArrayByType(Define::van_bang_chung_chi);
            $arrHinhThucHoc = HrDefine::getArrayByType(Define::hinh_thuc_hoc);
            $arrChuyenNghanhDaoTao = HrDefine::getArrayByType(Define::chuyen_nghanh_dao_tao);
            $arrChucVuDang = HrDefine::getArrayByType(Define::chuc_vu_doan_dang);

            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();
            $html = view('hr.CurriculumVitaePerson.' . $template, array_merge([
                'person_id' => $person_id,
                'dataList' => $dataList,
                'arrVanBangChungChi' => $arrVanBangChungChi,
                'arrHinhThucHoc' => $arrHinhThucHoc,
                'arrChuyenNghanhDaoTao' => $arrChuyenNghanhDaoTao,
                'arrChucVuDang' => $arrChucVuDang,
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }

    /************************************************************************************************************************************
     * Quan hệ gia đình
     ************************************************************************************************************************************/
    public function editFamily()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $relationshipId = Request::get('str_object_id', '');

        $person_id = FunctionLib::outputId($personId);
        $relationship_id = FunctionLib::outputId($relationshipId);

        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin chung
        $data = Relationship::find($relationship_id);
        //FunctionLib::debug($contracts);
        $template = 'quanhegiadinhPopupAdd';
        $arrQuanHeGiaDinh = HrDefine::getArrayByType(Define::quan_he_gia_dinh);

        $arrYears = FunctionLib::getListYears();
        $optionYears = FunctionLib::getOption($arrYears, isset($data['relationship_year_birth']) ? $data['relationship_year_birth'] : (int)date('Y', time()));
        $optionType = FunctionLib::getOption($arrQuanHeGiaDinh, isset($data['relationship_define_id']) ? $data['relationship_define_id'] : '');

        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.CurriculumVitaePerson.' . $template, [
            'data' => $data,
            'infoPerson' => $infoPerson,
            'optionType' => $optionType,
            'optionYears' => $optionYears,
            'person_id' => $person_id,
            'relationship_id' => $relationship_id,
        ], $this->viewPermission)->render();
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }
    public function postFamily()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = Request::get('person_id', '');
        $relationship_id = Request::get('relationship_id', '');
        //FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if ($data['relationship_human_name'] == '') {
            $arrData = ['intReturn' => 0, 'msg' => 'Dữ liệu nhập không đủ'];
        } else {
            if ($person_id > 0) {
                $dataBonus = array(
                    'relationship_describe' => $data['relationship_describe'],
                    'relationship_year_birth' => $data['relationship_year_birth'],
                    'relationship_define_id' => $data['relationship_define_id'],
                    'relationship_human_name' => $data['relationship_human_name'],
                    'relationship_person_id' => $person_id,
                );
                if ($relationship_id > 0) {
                    Relationship::updateItem($relationship_id, $dataBonus);
                } else {
                    Relationship::createItem($dataBonus);
                }
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];

                $template = 'quanhegiadinhList';
                $quanhegiadinh = Relationship::getRelationshipByPersonId($person_id);
                $arrQuanHeGiaDinh = HrDefine::getArrayByType(Define::quan_he_gia_dinh);

                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();
                $html = view('hr.CurriculumVitaePerson.' . $template, array_merge([
                    'person_id' => $person_id,
                    'quanhegiadinh' => $quanhegiadinh,
                    'arrQuanHeGiaDinh' => $arrQuanHeGiaDinh,
                ], $this->viewPermission))->render();
                $arrData['html'] = $html;
            } else {
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật' . $person_id];
            }
        }
        return response()->json($arrData);
    }
    public function deleteFamily()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $relationshipId = Request::get('str_object_id', '');

        $person_id = FunctionLib::outputId($personId);
        $relationship_id = FunctionLib::outputId($relationshipId);
        if ($relationship_id > 0 && Relationship::deleteItem($relationship_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];

            $template = 'quanhegiadinhList';
            $quanhegiadinh = Relationship::getRelationshipByPersonId($person_id);
            $arrQuanHeGiaDinh = HrDefine::getArrayByType(Define::quan_he_gia_dinh);

            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();
            $html = view('hr.CurriculumVitaePerson.' . $template, array_merge([
                'person_id' => $person_id,
                'quanhegiadinh' => $quanhegiadinh,
                'arrQuanHeGiaDinh' => $arrQuanHeGiaDinh,
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }
    public function changeValueViewCurriculumVitae(){
        $curriculum_person_id = Request::get('uid', '');
        $nameField = Request::get('nameField', '');
        $dataField = Request::get('dataField', '');
        $type = Request::get('type', 0);
        $dbType = Define::CURRICULUMVITAE_QUANHE_DACDIEM_BANTHAN;
        if($curriculum_person_id != ''){
            $curriculum_person_id = FunctionLib::outputId($curriculum_person_id);
            if($curriculum_person_id > 0){
                //curriculum_desc_history1
                if($type == 1 && $nameField == 'curriculum_desc_history1'){
                    $checkDb = CurriculumVitae::checkCurriculumVitaeByType($curriculum_person_id, $dbType);
                    if(sizeof($checkDb) == 0){
                        $data['curriculum_desc_history1'] = $dataField;
                        $data['curriculum_person_id'] = $curriculum_person_id;
                        $data['curriculum_type'] = $dbType;
                        CurriculumVitae::createItem($data);
                    }else{
                        $data['curriculum_desc_history1'] = $dataField;
                        CurriculumVitae::updateItem($checkDb->curriculum_id, $data);
                    }
                }elseif($type == 2 && $nameField == 'curriculum_desc_history2'){
                    $checkDb = CurriculumVitae::checkCurriculumVitaeByType($curriculum_person_id, $dbType);
                    if(sizeof($checkDb) == 0){
                        $data['curriculum_desc_history2'] = $dataField;
                        $data['curriculum_person_id'] = $curriculum_person_id;
                        $data['curriculum_type'] = $dbType;
                        CurriculumVitae::createItem($data);
                    }else{
                        $data['curriculum_desc_history2'] = $dataField;
                        CurriculumVitae::updateItem($checkDb->curriculum_id, $data);
                    }
                }elseif($type == 3 && $nameField == 'curriculum_foreign_relations1'){
                    $checkDb = CurriculumVitae::checkCurriculumVitaeByType($curriculum_person_id, $dbType);
                    if(sizeof($checkDb) == 0){
                        $data['curriculum_foreign_relations1'] = $dataField;
                        $data['curriculum_person_id'] = $curriculum_person_id;
                        $data['curriculum_type'] = $dbType;
                        CurriculumVitae::createItem($data);
                    }else{
                        $data['curriculum_foreign_relations1'] = $dataField;
                        CurriculumVitae::updateItem($checkDb->curriculum_id, $data);
                    }
                }elseif($type == 4 && $nameField == 'curriculum_foreign_relations2'){
                    $checkDb = CurriculumVitae::checkCurriculumVitaeByType($curriculum_person_id, $dbType);
                    if(sizeof($checkDb) == 0){
                        $data['curriculum_foreign_relations2'] = $dataField;
                        $data['curriculum_person_id'] = $curriculum_person_id;
                        $data['curriculum_type'] = $dbType;
                        CurriculumVitae::createItem($data);
                    }else{
                        $data['curriculum_foreign_relations2'] = $dataField;
                        CurriculumVitae::updateItem($checkDb->curriculum_id, $data);
                    }
                }else{
                    echo 'error';die;
                }
            }
        }
        echo 'ok';die;
    }
}
