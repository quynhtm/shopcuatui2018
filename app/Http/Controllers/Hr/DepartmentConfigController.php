<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\DepartmentConfig;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;

class DepartmentConfigController extends BaseAdminController{
    private $permission_view = 'department_config_view';
    private $permission_full = 'department_config_full';
    private $permission_delete = 'department_config_delete';
    private $permission_create = 'department_config_create';
    private $permission_edit = 'department_config_edit';
    private $arrStatus = array();
    private $error = array();
    private $arrDepartment = array();
    private $viewPermission = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý cấu hình đơn vị';
    }
    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite)
        );
    }
    public function getPermissionPage(){
        return $this->viewPermission = [
            'is_root'=> $this->is_root ? 1:0,
            'permission_edit'=>in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create'=>in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_remove'=>in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full'=>in_array($this->permission_full, $this->permission) ? 1 : 0,
        ];
    }
    public function view(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        //$dataSearch['department_id'] = addslashes(Request::get('department_id',''));
        $dataSearch['department_id'] = ($this->is_root) ? (int)Request::get('department_id', -1) : $this->user_depart_id;
        $dataSearch['department_config_status'] = (int)Request::get('department_config_status', -1);
        $dataSearch['field_get'] = '';
        $data = DepartmentConfig::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';
        //Get data cate department
        $this->arrDepartment =  Department::getDepartmentAll();

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['department_config_status']);
        $optionDepartment = FunctionLib::getOption($this->arrDepartment, $dataSearch['department_id']);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.DepartmentConfig.view',array_merge([
            'data'=>$data,
            'search'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'optionDepartment'=>$optionDepartment,
            'arrDepartment'=>$this->arrDepartment,
        ],$this->viewPermission));
    }
    public function getItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = DepartmentConfig::getItemById($id);
        }

        $this->getDataDefault();
        //Get data cate department
        $this->arrDepartment =  Department::getDepartmentAll();

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['department_status'])? $data['department_status']: CGlobal::status_show);
        $optionDepartment = FunctionLib::getOption($this->arrDepartment, isset($data['department_id']) ? $data['department_id'] : -1);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.DepartmentConfig.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
            'optionDepartment'=>$optionDepartment,
            'arrDepartment'=>$this->arrDepartment,
        ],$this->viewPermission));
    }
    public function postItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        $data['department_id'] = (int)($data['department_id']);
        $data['department_retired_age_min_girl'] = (int)($data['department_retired_age_min_girl']);
        $data['department_retired_age_max_girl'] = (int)($data['department_retired_age_max_girl']);
        $data['department_retired_age_min_boy'] = (int)($data['department_retired_age_min_boy']);
        $data['department_retired_age_max_boy'] = (int)($data['department_retired_age_max_boy']);
        $data['month_regular_wage_increases'] = (int)($data['month_regular_wage_increases']);
        $data['month_raise_the_salary_ahead_of_time'] = (int)($data['month_raise_the_salary_ahead_of_time']);
        $data['department_config_status'] = (int)($data['department_config_status']);

        if($data['month_regular_wage_increases'] == 0){
            $data['month_regular_wage_increases'] = 36;
        }
        if($data['month_raise_the_salary_ahead_of_time'] == 0){
            $data['month_raise_the_salary_ahead_of_time'] = 24;
        }

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            $checkDepart = DepartmentConfig::getItemByDepartmentId($data['department_id']);
            if(sizeof($checkDepart) > 0){
                $id = $checkDepart->department_config_id;
            }
            if($id > 0) {
                if(DepartmentConfig::updateItem($id, $data)) {
                    return Redirect::route('hr.departmentConfigView');
                    //return Redirect::route('hr.departmentView');
                }
            }else{
                if(DepartmentConfig::createItem($data)) {
                    return Redirect::route('hr.departmentConfigView');
                    //return Redirect::route('hr.departmentView');
                }
            }
        }
        $this->arrDepartment =  Department::getDepartmentAll();
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['department_status'])? $data['department_status']: CGlobal::status_show);
        $optionDepartment = FunctionLib::getOption($this->arrDepartment, isset($data['department_id']) ? $data['department_id'] : -1);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.DepartmentConfig.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
            'optionDepartment'=>$optionDepartment,

        ],$this->viewPermission));
    }
    public function deleteDepartmentConfig(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0 && DepartmentConfig::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['department_id']) && trim($data['department_id']) == '') {
                $this->error[] = 'Loại đơn vị/ phòng ban không được rỗng';
            }
            if(isset($data['department_retired_age_min_girl']) && trim($data['department_retired_age_min_girl']) == 0) {
                $this->error[] = 'Tuổi tối thiểu về hưu với nữ';
            }
            if(isset($data['department_retired_age_max_girl']) && trim($data['department_retired_age_max_girl']) == 0) {
                $this->error[] = 'Tuổi tối đa về hưu với nữ';
            }

            if(isset($data['department_retired_age_min_boy']) && trim($data['department_retired_age_min_boy']) == 0) {
                $this->error[] = 'Tuổi tối thiểu về hưu với nam';
            }
            if(isset($data['department_retired_age_max_boy']) && trim($data['department_retired_age_max_boy']) == 0) {
                $this->error[] = 'Tuổi tối đa về hưu với nam';
            }

            if(isset($data['month_regular_wage_increases']) && trim($data['month_regular_wage_increases']) == 0) {
                $this->error[] = 'Số tháng cần sét tăng lương thường xuyên';
            }
            if(isset($data['month_raise_the_salary_ahead_of_time']) && trim($data['month_raise_the_salary_ahead_of_time']) == 0) {
                $this->error[] = 'Số tháng tối thiểu để sét tăng lương trước thời hạn';
            }
        }
        return true;
    }
    public function getArrayDepartment($data=array()){
        $result = array();
        if(sizeof($data) > 0){
            foreach($data as $item){
                $result[$item->department_id] = $item->department_name;
            }
        }
        return $result;
    }
}
