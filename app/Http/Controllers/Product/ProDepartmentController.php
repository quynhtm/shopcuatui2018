<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/

namespace App\Http\Controllers\Product;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Product\DepartmentProduct;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use View;

class ProDepartmentController extends BaseAdminController{

    private $permission_view = 'departProductView';
    private $permission_full = 'departProductFull';
    private $permission_delete = 'departProductDelete';
    private $permission_create = 'departProductCreate';
    private $permission_edit = 'departProductEdit';

    private $error = array();
    private $viewPermission = array();
    private $arrStatus = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý Depart sản phẩm';
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
            'permission_delete'=>in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full'=>in_array($this->permission_full, $this->permission) ? 1 : 0,
        ];
    }
    public function view() {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $page_no = (int) Request::get('page_no',1);
        $search['department_name'] = addslashes(Request::get('department_name_s',''));
        $limit = 0;
        $total = 0;
        $offset = ($page_no - 1) * $limit;
        $data = DepartmentProduct::searchByCondition($search, $limit, $offset, $total);
        $paging = '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($search['role_status']) ? $search['role_status'] : CGlobal::status_show);

        $this->viewPermission = $this->getPermissionPage();
        return view('product.Department.view',array_merge([
            'data'=>$data,
            'search'=>$search,
            'size'=>$total,
            'start'=>($page_no - 1) * $limit,
            'paging'=>$paging,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
        ],$this->viewPermission));
    }
    public function addItem($ids){
        $id = FunctionLib::outputId($ids);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $arrSucces=['isOk'=>0];
        $id_hiden = (int)Request::get('id', 0);
        $data = $_POST;
        unset($data['id']);
        $data['department_order'] = (int)($data['department_order']);
        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden: $id;
            if($id > 0) {
                DepartmentProduct::updateItem($id, $data);
            }else{
                DepartmentProduct::createItem($data);
            }
            $arrSucces['isOk'] = 1;
            $arrSucces['url'] = URL::route('admin.proDepartView');
            return $arrSucces;
        }
        return $arrSucces;
    }
    public function deleteItem(){
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Redirect::route('admin.proDepartView');
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if($id > 0) {
            DepartmentProduct::deleteItem($id);
        }
        return Redirect::route('admin.proDepartView');
    }
    public function ajaxLoadFormDepart(){
        $ids = $_POST['id'];
        $id = FunctionLib::outputId($ids);
        $data = [];
        $data['role_id'] = 0;
        if($id > 0){
            $data = DepartmentProduct::find($id);
        }
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['role_status'])? $data['role_status'] : CGlobal::status_show);

        return view('product.Department.ajaxLoadForm',
            array_merge([
                'data'=>$data,
                'optionStatus'=>$optionStatus,
            ],$this->viewPermission));
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['department_name']) && trim($data['department_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}