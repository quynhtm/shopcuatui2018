<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\MemberSite;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\HrWageStepConfig;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\Bonus;
use App\Http\Models\Hr\Allowance;
use App\Http\Models\Hr\Salary;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use App\Library\AdminFunction\Loader;

class SalaryAllowanceController extends BaseAdminController
{
    //contracts
    private $salaryAllowanceFull = 'salaryAllowanceFull';
    private $salaryAllowanceView = 'salaryAllowanceView';
    private $salaryAllowanceDelete = 'salaryAllowanceDelete';
    private $salaryAllowanceCreate = 'salaryAllowanceCreate';

    private $arrStatus = array(1 => 'hiển thị', 2 => 'Ẩn');
    private $viewPermission = array();//check quyen

    private $arrThangbangluong = array();
    private $arrNghachcongchuc = array();
    private $arrMaNgach = array();
    private $arrBacluong = array();

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

        $this->arrThangbangluong = HrWageStepConfig::getArrayByType(Define::type_thang_bang_luong);
        $this->arrNghachcongchuc = HrWageStepConfig::getArrayByType(Define::type_ngach_cong_chuc);
        $this->arrMaNgach = HrWageStepConfig::getArrayByType(Define::type_ma_ngach);
        $this->arrBacluong = HrWageStepConfig::getArrayByType(Define::type_bac_luong);
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_root' => $this->is_root ? 1 : 0,
            'salaryAllowanceFull' => in_array($this->salaryAllowanceFull, $this->permission) ? 1 : 0,
            'salaryAllowanceView' => in_array($this->salaryAllowanceView, $this->permission) ? 1 : 0,
            'salaryAllowanceCreate' => in_array($this->salaryAllowanceCreate, $this->permission) ? 1 : 0,
            'salaryAllowanceDelete' => in_array($this->salaryAllowanceDelete, $this->permission) ? 1 : 0,
        ];
    }

    public function viewSalaryAllowance($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Lương, phụ cấp';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceView, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);
        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin lương
        $lương = Salary::getSalaryByPersonId($person_id);

        //thông tin phu cap
        $phucap = Allowance::getAllowanceByPersonId($person_id);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();

        $theme = ($this->user_project == CGlobal::hr_hanchinh_2c) ? 'hr.SalaryAllowance.View' : 'hr.SalaryAllowance.View_TuNhan';

        return view($theme, array_merge([
            'person_id' => $person_id,
            'lương' => $lương,
            'arrNgachBac' => $this->arrNghachcongchuc,

            'phucap' => $phucap,
            'arrOptionPhuCap' => Define::$arrOptionPhuCap,
            'arrMethodPhuCap' => Define::$arrMethodPhuCap,
            'infoPerson' => $infoPerson,
        ], $this->viewPermission));
    }

    /************************************************************************************************************************************
     * Thông tin lương
     ************************************************************************************************************************************/
    public function editSalary()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $html = '';
        if($this->user_project == CGlobal::hr_tu_nhan){
            $html = $this->editSalaryTuNhan();

        }else{
            $html = $this->editSalary2c();
        }
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }
    public function editSalary2c(){
        $personId = Request::get('str_person_id', '');
        $str_object_id = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');

        $person_id = FunctionLib::outputId($personId);
        $salary_id = FunctionLib::outputId($str_object_id);

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin chung
        $data = Salary::find($salary_id);

        $arrYears = FunctionLib::getListYears();
        $optionYears = FunctionLib::getOption($arrYears, isset($data['salary_year']) ? $data['salary_year'] : (int)date('Y', time()));

        $arrMonth = FunctionLib::getListMonth();
        $optionMonth = FunctionLib::getOption($arrMonth, isset($data['salary_month']) ? $data['salary_month'] : (int)date('m', time()));

        $this->getDataDefault();

        //thang bang luong
        $salary_wage_table = isset($data['salary_wage_table']) ? $data['salary_wage_table'] : 0;
        $arrThangbangluong = !empty($this->arrThangbangluong) ? array(0 => 'Chọn thang bảng lương') + $this->arrThangbangluong : array(0 => 'Chọn thang bảng lương');
        $optionThangbangluong = FunctionLib::getOption($arrThangbangluong,$salary_wage_table );

        //Nghạch công chức
        $salary_civil_servants = isset($data['salary_civil_servants']) ? $data['salary_civil_servants'] : 0;
        $arrNghachcongchuc = HrWageStepConfig::getDataOption($salary_wage_table,Define::type_ngach_cong_chuc);
        $arrNghachcongchuc = !empty($arrNghachcongchuc) ? (Define::$arrCheckDefault + $arrNghachcongchuc) : Define::$arrCheckDefault;
        $optionNghachcongchuc = FunctionLib::getOption($arrNghachcongchuc,$salary_civil_servants );

        //mã Nghạch
        $salary_tariffs = isset($data['salary_tariffs']) ? $data['salary_tariffs'] : 0;
        $arrMaNgach = HrWageStepConfig::getDataOption($salary_civil_servants,Define::type_ma_ngach);
        $arrMaNgach = !empty($arrMaNgach) ? (Define::$arrCheckDefault + $arrMaNgach) : Define::$arrCheckDefault;
        $optionMaNgach = FunctionLib::getOption($arrMaNgach, $salary_tariffs);

        //bac luong
        $salary_wage = isset($data['salary_wage']) ? $data['salary_wage'] : 0;
        $arrBacluong = HrWageStepConfig::getDataOption($salary_tariffs,Define::type_bac_luong);
        $arrBacluong = !empty($arrBacluong) ? (Define::$arrCheckDefault + $arrBacluong) : Define::$arrCheckDefault;
        $optionBacluong = FunctionLib::getOption($arrBacluong, $salary_wage);

        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.SalaryAllowance.SalaryPopupAdd', [
            'data' => $data,
            'infoPerson' => $infoPerson,
            'optionMonth' => $optionMonth,
            'optionYears' => $optionYears,
            'optionThangbangluong' => $optionThangbangluong,
            'optionNghachcongchuc' => $optionNghachcongchuc,
            'optionMaNgach' => $optionMaNgach,
            'optionBacluong' => $optionBacluong,
            'person_id' => $person_id,
            'salary_id' => $salary_id,
            'typeAction' => $typeAction,
        ], $this->viewPermission)->render();

        return $html;
    }
    public function editSalaryTuNhan(){
        $personId = Request::get('str_person_id', '');
        $str_object_id = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');

        $person_id = FunctionLib::outputId($personId);
        $salary_id = FunctionLib::outputId($str_object_id);

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin chung
        $data = Salary::find($salary_id);

        $arrYears = FunctionLib::getListYears();
        $optionYears = FunctionLib::getOption($arrYears, isset($data['salary_year']) ? $data['salary_year'] : (int)date('Y', time()));

        $arrMonth = FunctionLib::getListMonth();
        $optionMonth = FunctionLib::getOption($arrMonth, isset($data['salary_month']) ? $data['salary_month'] : (int)date('m', time()));

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.SalaryAllowance.SalaryPopupAdd_TuNhan', [
            'data' => $data,
            'infoPerson' => $infoPerson,
            'optionMonth' => $optionMonth,
            'optionYears' => $optionYears,
            'person_id' => $person_id,
            'salary_id' => $salary_id,
            'typeAction' => $typeAction,
        ], $this->viewPermission)->render();
        return $html;
    }

    public function postSalary()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = (int)Request::get('person_id', '');
        $salary_id = (int)Request::get('salary_id', '');
        $id_hiden = Request::get('id_hiden', '');
        ///FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        $msg = $this->validateForm($this->user_project,$data);
        if (trim($msg) != '') {
            $arrData = ['intReturn' => 0, 'msg' => $msg];
        } else {
            if ($person_id > 0) {
                $data['salary_person_id'] = $person_id;
                $salary_id = ($salary_id > 0) ? $salary_id : FunctionLib::outputId($id_hiden);
                if ($salary_id > 0) {
                    Salary::updateItem($salary_id, $data);
                } else {
                    $salary_id = Salary::createItem($data);
                }

                //cap nhat dong bo thong tin nguoi dung
                $request = array('person_id'=>$person_id, 'salary_id'=>$salary_id, 'person_date_salary_increase'=>Define::STATUS_SHOW);
                if($this->user_project == CGlobal::hr_tu_nhan){
                    app(Person::class)->putDataSalaryPersonTuNhan($request);
                }else{
                    app(Person::class)->putDataSalaryPerson($request);
                }

                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];

                //thông tin lương
                $lương = Salary::getSalaryByPersonId($person_id);

                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();
                $theme = ($this->user_project == CGlobal::hr_tu_nhan)?'hr.SalaryAllowance.SalaryList_TuNhan':'hr.SalaryAllowance.SalaryList';
                $html = view($theme, array_merge([
                    'person_id' => $person_id,
                    'lương' => $lương,
                    'arrNgachBac' => $this->arrNghachcongchuc,
                ], $this->viewPermission))->render();

                $arrData['html'] = $html;
            } else {
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật' . $person_id];
            }
        }
        return response()->json($arrData);
    }

    public function validateForm($member_type,$data){
        $msg = '';
        if($member_type == CGlobal::hr_hanchinh_2c){
            if($data['salary_salaries'] == '' || $data['salary_coefficients'] == '' || $data['salary_wage_table'] == 0 || $data['salary_civil_servants'] == 0 || $data['salary_tariffs'] == 0 || $data['salary_wage'] == 0){
                return 'Chưa nhập đầy đủ dữ liêu';
            }
        }else{
            if($data['salary_salaries'] == '' || $data['salary_percent'] == '' ){
                return 'Chưa nhập đầy đủ dữ liêu';
            }
        }
        return $msg;
    }
    public function deleteSalary()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $str_object_id = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');
        $person_id = FunctionLib::outputId($personId);
        $salary_id = FunctionLib::outputId($str_object_id);
        if ($salary_id > 0 && Salary::deleteItem($salary_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
            //thông tin view list\
            //thông tin lương
            $lương = Salary::getSalaryByPersonId($person_id);
            $arrNgachBac = HrDefine::getArrayByType(Define::nghach_bac);

            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();
            $html = view('hr.SalaryAllowance.SalaryList', array_merge([
                'person_id' => $person_id,
                'lương' => $lương,
                'arrNgachBac' => $arrNgachBac,
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }

    //ajax get thong tin cơ bản của nhân sự
    public function getInfoSalary()
    {
        //Check phan quyen.
        $salaryId = Request::get('str_salary_id', '');
        $salary_id = FunctionLib::outputId($salaryId);
        $arrData = ['intReturn' => 0, 'msg' => ''];

        $this->getDataDefault();

        //thong tin lương
        $salary = Salary::find($salary_id);

        //thong tin nhân sự
        $person_id = isset($salary->salary_person_id)? $salary->salary_person_id: 0;
        $infoPerson = Person::getInfoPerson($person_id);

        $this->viewPermission = $this->getPermissionPage();

        $theme = ($this->user_project == CGlobal::hr_hanchinh_2c) ? 'hr.SalaryAllowance.SalaryInfoPopup' : 'hr.SalaryAllowance.SalaryInfoPopup_TuNhan';

        $html = view($theme, [
            'salary' => $salary,
            'infoPerson' => $infoPerson,
            'arrThangbangluong' => $this->arrThangbangluong,
            'arrNghachcongchuc' => $this->arrNghachcongchuc,
            'arrMaNgach' => $this->arrMaNgach,
            'arrBacluong' => $this->arrBacluong,
        ], $this->viewPermission)->render();

        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }

    /************************************************************************************************************************************
     * Thông tin phụ cấp
     ************************************************************************************************************************************/
    public function editAllowance()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $str_object_id = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');

        $person_id = FunctionLib::outputId($personId);
        $allowance_id = FunctionLib::outputId($str_object_id);

        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin chung
        $data = Allowance::find($allowance_id);

        $arrMonth = FunctionLib::getListMonth();
        $arrYears = FunctionLib::getListYears();
        $optionMonth2 = FunctionLib::getOption($arrMonth, isset($data['allowance_month_start']) ? $data['allowance_month_start'] : (int)date('m', time()));
        $optionYears2 = FunctionLib::getOption($arrYears, isset($data['allowance_year_start']) ? $data['allowance_year_start'] : (int)date('Y', time()));
        $optionMonth3 = FunctionLib::getOption($arrMonth, isset($data['allowance_month_end']) ? $data['allowance_month_end'] : (int)date('m', time()));
        $optionYears3 = FunctionLib::getOption($arrYears, isset($data['allowance_year_end']) ? $data['allowance_year_end'] : (int)date('Y', time()));
        $optionAllowanceType = FunctionLib::getOption(Define::$arrOptionPhuCap, isset($data['allowance_type']) ? $data['allowance_type'] : 0);

        $this->viewPermission = $this->getPermissionPage();

        $theme = ($this->user_project == CGlobal::hr_hanchinh_2c) ? 'hr.SalaryAllowance.AllowancePopupAdd' : 'hr.SalaryAllowance.AllowancePopupAdd_TuNhan';

        $html = view($theme, [
            'data' => $data,
            'infoPerson' => $infoPerson,
            'optionMonth2' => $optionMonth2,
            'optionYears2' => $optionYears2,
            'optionMonth3' => $optionMonth3,
            'optionYears3' => $optionYears3,
            'optionAllowanceType' => $optionAllowanceType,
            'person_id' => $person_id,
            'allowance_id' => $allowance_id,
            'typeAction' => $typeAction,
        ], $this->viewPermission)->render();
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }

    public function postAllowance()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = Request::get('person_id', '');
        $allowance_id = Request::get('allowance_id', '');
        $id_hiden = Request::get('id_hiden', '');
        //FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        $allowance_method_type = $data['allowance_method_type'];
        $data['allowance_method_value'] = $data['allowance_method_value_' . $allowance_method_type];
        if ($data['allowance_method_value'] == '') {
            $arrData = ['intReturn' => 0, 'msg' => 'Dữ liệu nhập không đủ'];
        } else {
            if ($person_id > 0) {
                $dataAllowance = ['allowance_person_id' => $person_id,
                    'allowance_type' => $data['allowance_type'],
                    'allowance_method_type' => $data['allowance_method_type'],
                    'allowance_method_value' => $data['allowance_method_value'],
                    'allowance_month_start' => $data['allowance_month_start'],
                    'allowance_year_start' => $data['allowance_year_start'],
                    'allowance_note' => $data['allowance_note'],
                    ///'allowance_month_end' => $data['allowance_month_end'],
                    //'allowance_year_end' => $data['allowance_year_end']
                ];
                $allowance_id = ($allowance_id > 0) ? $allowance_id : FunctionLib::outputId($id_hiden);
                if ($allowance_id > 0) {
                    Allowance::updateItem($allowance_id, $dataAllowance);
                } else {
                    $allowance_id = Allowance::createItem($dataAllowance);
                }
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];

                //cap nhat dong bo phụ cấp NS
                $request = array(
                    'person_id'=>$person_id,
                    'allowance_id'=>$allowance_id,
                    'allowance_type'=>$data['allowance_type'],
                    'allowance_method_value'=>$data['allowance_method_value'],
                    'allowance_month_start'=>$data['allowance_month_start'],
                    'allowance_year_start'=>$data['allowance_year_start']
                );
                Person::putDataAllowancePerson($request);

                //thông tin phu cap
                $phucap = Allowance::getAllowanceByPersonId($person_id);

                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();
                $html = view('hr.SalaryAllowance.AllowanceList', array_merge([
                    'person_id' => $person_id,
                    'phucap' => $phucap,
                    'arrOptionPhuCap' => Define::$arrOptionPhuCap,
                    'arrMethodPhuCap' => Define::$arrMethodPhuCap,
                ], $this->viewPermission))->render();
                $arrData['html'] = $html;
            } else {
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật' . $person_id];
            }
        }
        return response()->json($arrData);
    }

    public function deleteAllowance()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $str_object_id = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');
        $person_id = FunctionLib::outputId($personId);
        $salary_id = FunctionLib::outputId($str_object_id);
        if ($salary_id > 0 && Allowance::deleteItem($salary_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
            //thông tin view list\
            //thông tin lương
            $phucap = Allowance::getAllowanceByPersonId($person_id);
            $arrNgachBac = HrDefine::getArrayByType(Define::nghach_bac);

            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();
            $html = view('hr.SalaryAllowance.AllowanceList', array_merge([
                'person_id' => $person_id,
                'phucap' => $phucap,
                'arrOptionPhuCap' => Define::$arrOptionPhuCap,
                'arrMethodPhuCap' => Define::$arrMethodPhuCap,
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }
}
