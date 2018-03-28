<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\News\CategoryNew;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class CategoryNewsController extends BaseAdminController
{
    private $permission_view = 'categoryNewView';
    private $permission_full = 'categoryNewFull';
    private $permission_delete = 'categoryNewDelete';
    private $permission_create = 'categoryNewCreate';
    private $permission_edit = 'categoryNewEdit';
    private $arrStatus = array();
    private $error = array();
    private $arrayCategorySearch = [];
    private $arrMenuParent = array();
    private $viewPermission = array();//check quyen

    public function __construct()
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Danh mục tin tức';
    }

    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite));
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
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;
        $search['category_name'] = Request::get('category_name','');
        $search['category_type'] = Request::get('category_type',0);
        $search['category_status'] = Request::get('category_status',-2);
        $search['category_order'] = Request::get('category_order','');
        $search['active'] = (int)Request::get('active',-1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $dataSearch = CategoryNew::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$search) : '';
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['category_status']);
        $optionCategoryType = FunctionLib::getOption(array(0=>'--Chọn loại danh mục--')+Define::$arrCategoryType, $search['category_type']);

        $this->viewPermission = $this->getPermissionPage();
        return view('news.CategoryNew.view',array_merge([
            'data'=>$dataSearch,
            'search'=>$search,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'arrayStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
            'optionCategoryType'=>$optionCategoryType,
            'arrCategoryType'=>Define::$arrCategoryType,
        ],$this->viewPermission));
    }

    public function getItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = CategoryNew::find($id);
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active'])? $data['active']: CGlobal::status_show);
        $optionCategoryType = FunctionLib::getOption(Define::$arrCategoryType, isset($data['category_type'])? $data['category_type'] : Define::Category_News_Menu);
        $this->viewPermission = $this->getPermissionPage();
        return view('news.CategoryNew.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
            'optionCategoryType'=>$optionCategoryType,
            'arrCategoryType'=>Define::$arrCategoryType,

        ],$this->viewPermission));
    }

    public function postItem($ids) {
        $id = FunctionLib::outputId($ids);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
//        FunctionLib::debug($data);
        $data['category_order'] = (int)($data['category_order']);

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0)?$id_hiden: $id;
            if($id > 0) {
                //cap nhat
                if(CategoryNew::updateItem($id, $data)) {
                    return Redirect::route('admin.categoryNews');
                }
            }else{
                //them moi
                if(CategoryNew::createItem($data)) {
                    return Redirect::route('admin.categoryNews');
                }
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active'])? $data['active']: CGlobal::status_hide);
        $optionCategoryType = FunctionLib::getOption(Define::$arrCategoryType, isset($data['category_type'])? $data['category_type'] : Define::Category_News_Menu);
        $this->viewPermission = $this->getPermissionPage();
        return view('news.CategoryNew.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
            'arrCategoryType'=>Define::$arrCategoryType,
            'optionCategoryType'=>$optionCategoryType
        ],$this->viewPermission));
    }

    public function deleteCategoryNews()
    {
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && CategoryNew::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['category_name']) && trim($data['category_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
