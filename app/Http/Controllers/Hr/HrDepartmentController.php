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
use App\Http\Models\Hr\HrDefine;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;

class HrDepartmentController extends BaseAdminController
{
    private $permission_view = 'department_view';
    private $permission_full = 'department_full';
    private $permission_delete = 'department_delete';
    private $permission_create = 'department_create';
    private $permission_edit = 'department_edit';
    private $arrStatus = array();
    private $error = array();
    private $arrDepartment = array();
    private $viewPermission = array();

    private $arrDepartmentType = array();
    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý đơn vị - phòng ban';
    }
    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite)
        );
        $this->arrDepartmentType = HrDefine::getArrayByType(Define::loai_donvi_phongban);
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

        $dataSearch['department_id'] = ($this->is_root) ? (int)Request::get('department_id', -1) : $this->user_depart_id;
        $dataSearch['department_name'] = addslashes(Request::get('department_name',''));
        $dataSearch['department_status'] = (int)Request::get('department_status', -1);
        $dataSearch['field_get'] = 'department_id,department_type,department_name,department_phone,department_fax,department_parent_id,department_creater_time,department_update_time';

        $data = Department::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        //Get data cate left
        $this->arrDepartment =  Department::getDepartmentAll();

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['department_status']);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Department.view',array_merge([
            'data'=>$data,
            'search'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrDepartmentType'=>$this->arrDepartmentType,
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
            $data = Department::getItemById($id);
        }

        $this->getDataDefault();

        $this->arrDepartment =  Department::getDepartmentAll();
        if(!empty($this->arrDepartment) && in_array($id, array_keys($this->arrDepartment))){
            unset($this->arrDepartment[$id]);
        }
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['department_status'])? $data['department_status']: CGlobal::status_show);
        $optionDepartmentType = FunctionLib::getOption($this->arrDepartmentType, isset($data['department_type'])? $data['department_type']: 0);
        $optionDepartmentParent = FunctionLib::getOption($this->arrDepartment, isset($data['department_parent_id'])? $data['department_parent_id']: 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Department.add',array_merge([
            'data'=>$data,
            'optionDepartmentParent'=>$optionDepartmentParent,
            'id'=>$id,
            'optionStatus'=>$optionStatus,
            'optionDepartmentType'=>$optionDepartmentType,
            'arrDepartmentType'=>$this->arrDepartmentType,
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
        if(isset($data['department_parent_id'])) {
            $data['department_parent_id'] = (int)$data['department_parent_id'];
        }
        if(isset($data['department_type'])) {
            $data['department_type'] = (int)$data['department_type'];
        }
        if($data['department_type'] == 43){
            $data['department_parent_id'] = -1;
        }
        $data['department_order'] = (int)($data['department_order']);
        $data['department_status'] = (int)($data['department_status']);

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            $category_level = Department::getLevelParentId($data['department_parent_id']);
            $dataSave['department_level'] = ($category_level <=5) ? $category_level : 5;
            if($id > 0) {
                $data['department_update_time'] = time();
                $data['department_user_id_update'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;
                $data['department_user_name_update'] = isset($this->user['user_name']) ? $this->user['user_name'] : 0;
                if(Department::updateItem($id, $data)) {
                    if(isset($data['clickPostPageNext'])){
                        //return Redirect::route('hr.departmentEdit', array('id'=>FunctionLib::inputId(0)));
                        $checkDepart = DepartmentConfig::getItemByDepartmentId($id);
                        $departConfigId = isset($checkDepart->department_config_id) ? $checkDepart->department_config_id : 0;
                        if($departConfigId == 0){
                            $this->createItemDepart($id);
                            $checkDepart = DepartmentConfig::getItemByDepartmentId($id);
                            $departConfigId = isset($checkDepart->department_config_id) ? $checkDepart->department_config_id : 0;
                        }
                        return Redirect::route('hr.departmentConfigEdit', array('id'=>FunctionLib::inputId($departConfigId)));
                    }else{
                        return Redirect::route('hr.departmentView');
                    }
                }
            }else{
                $data['department_creater_time'] = time();
                $data['department_user_id_creater'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;
                $data['department_user_name_creater'] = isset($this->user['user_name']) ? $this->user['user_name'] : 0;
                $id = Department::createItem($data);
                if($id) {
                    if(isset($data['clickPostPageNext'])){
                        //return Redirect::route('hr.departmentEdit', array('id'=>FunctionLib::inputId(0)));
                        $this->createItemDepart($id);
                        $checkDepart = DepartmentConfig::getItemByDepartmentId($id);
                        $departConfigId = isset($checkDepart->department_config_id) ? $checkDepart->department_config_id : 0;
                        return Redirect::route('hr.departmentConfigEdit', array('id'=>FunctionLib::inputId($departConfigId)));
                    }else{
                        return Redirect::route('hr.departmentView');
                    }
                }
            }
        }
        $this->getDataDefault();

        $this->arrDepartment =  Department::getDepartmentAll();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['department_status'])? $data['department_status']: CGlobal::status_show);
        $optionDepartmentType = FunctionLib::getOption($this->arrDepartmentType, isset($data['department_type'])? $data['department_type']: 0);
        $optionDepartmentParent = FunctionLib::getOption($this->arrDepartment, isset($data['department_parent_id'])? $data['department_parent_id']: 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Department.add',array_merge([
            'data'=>$data,
            'optionDepartmentParent'=>$optionDepartmentParent,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
            'optionDepartmentType'=>$optionDepartmentType,
            'arrDepartmentType'=>$this->arrDepartmentType,
            'arrDepartment'=>$this->arrDepartment,

        ],$this->viewPermission));
    }
    public function deleteDepartment(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0 && Department::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['department_type']) && trim($data['department_type']) == '') {
                $this->error[] = 'Loại đơn vị/ phòng ban không được rỗng';
            }
            if(isset($data['department_name']) && trim($data['department_name']) == '') {
                $this->error[] = 'Tên đơn vị/ Phòng ban không được rỗng';
            }
        }
        return true;
    }
    public static function showCategories($categories, $parent_id = 0, $char='-', &$str){
        foreach($categories as $key => $item){
            if($item['department_parent_id'] == $parent_id) {
                if($parent_id == 0){
                    $bold='txt-bold';
                }else{
                    $bold = '';
                }
                $str .= '<li class="list-group-item node-treeview '.$bold.'">' . $char . '<span class="icon glyphicon glyphicon-minus"></span> <a href="' . URL::route('hr.departmentEdit', array('id' => FunctionLib::inputId($item['department_id']))) . '" title="' . $item->department_name . '">' . $item['department_name'] . '</a></li>';
                unset($categories[$key]);
                self::showCategories($categories, $item['department_id'], $char.'<span class="indent"></span>', $str);
            }
        }
    }
    public static function showCategoriesView($categories, $parent_id = 0, $char='-', &$str){
        foreach($categories as $key => $item){
            if($item['department_parent_id'] == $parent_id){
                if($parent_id == 0){
                    $bold='txt-bold';
                }else{
                    $bold = '';
                }
                $str .= '<li class="list-group-item node-treeview '.$bold.'" title="'.$item['department_name'].'" rel="'.$item['department_id'].'" psrel="'.$item['department_parent_id'].'" data="'.FunctionLib::inputId($item['department_id']).'">'.$char. '<span class="icon glyphicon glyphicon-minus"></span> '.$item['department_name'].'</li>';
                unset($categories[$key]);

                self::showCategoriesView($categories, $item['department_id'], $char.'<span class="indent"></span>', $str);
            }
        }
    }
    public function createItemDepart($id){
        $dataDepart = array(
            'department_id'=>$id,
            'department_retired_age_min_girl'=>55,
            'department_retired_age_max_girl'=>60,
            'department_retired_age_min_boy'=>55,
            'department_retired_age_max_boy'=>65,
            'month_regular_wage_increases'=>36,
            'month_raise_the_salary_ahead_of_time'=>24,
        );
        DepartmentConfig::createItem($dataDepart);
    }
}
