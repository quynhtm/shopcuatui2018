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
use App\Http\Models\Product\Product;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use View;

class AdminProductController extends BaseAdminController{

    private $permission_view = 'productView';
    private $permission_full = 'productFull';
    private $permission_delete = 'productDelete';
    private $permission_create = 'productCreate';
    private $permission_edit = 'productEdit';

    private $arrStatus = array();
    private $error = array();
    private $viewPermission = array();//check quyen

    public $obj_product;

    public function __construct(Product $obj_product){
        $this->obj_product = $obj_product;

        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý sản phẩm';
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
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['product_name'] = addslashes(Request::get('product_name',''));
        $search['product_id'] = (int)Request::get('product_id',0);
        $search['product_status'] = (int)Request::get('product_status',-1);
        $search['product_is_hot'] = (int)Request::get('product_is_hot',-1);
        $search['category_id'] = (int)Request::get('category_id',-1);
        $search['depart_id'] = (int)Request::get('depart_id',-1);
        $search['user_shop_id'] = (int)Request::get('user_shop_id',0);
        $search['user_id_creater'] = (int)Request::get('user_id_creater',0);
        //$search['field_get'] = 'order_id,order_product_name,order_status';//cac truong can lay

        $data = $this->obj_product->searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        //$optionStatus = FunctionLib::getOption($this->arrStatus, $search['product_status']);
        //FunctionLib::debug($data);
        $this->getDataDefault();

        $this->viewPermission = $this->getPermissionPage();
        return view('product.Product.view',array_merge([
            'data'=>$data,
            'search'=>$search,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,

            'optionStatus'=>[],
            'optionType'=>[],
            'optionDepart'=>[],
            'optionCategory'=>[],
            'arrShop'=>[],
            'arrUser'=>[],
        ],$this->viewPermission));
    }

    public function getItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = MenuSystem::find($id);
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active'])? $data['active']: CGlobal::status_show);
        $optionShowContent = FunctionLib::getOption($this->arrStatus, isset($data['showcontent'])? $data['showcontent']: CGlobal::status_show);
        $optionShowPermission = FunctionLib::getOption($this->arrStatus, isset($data['show_permission'])? $data['show_permission']: CGlobal::status_hide);
        $optionShowMenu = FunctionLib::getOption($this->arrStatus, isset($data['show_menu'])? $data['show_menu']: CGlobal::status_show);
        $optionMenuParent = FunctionLib::getOption($this->arrMenuParent, isset($data['parent_id'])? $data['parent_id'] : 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('admin.AdminMenuSystem.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
            'optionShowContent'=>$optionShowContent,
            'optionShowPermission'=>$optionShowPermission,
            'optionShowMenu'=>$optionShowMenu,
            'optionMenuParent'=>$optionMenuParent,
        ],$this->viewPermission));
    }

    public function postItem($ids) {
        $id = FunctionLib::outputId($ids);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        if(!$this->is_boss){
            unset($data['menu_url']);
        }
        $data['ordering'] = (int)($data['ordering']);
        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0)?$id_hiden: $id;
            if($id > 0) {
                //cap nhat
                if(MenuSystem::updateItem($id, $data)) {
                    return Redirect::route('admin.menuView');
                }
            }else{
                //them moi
                if(MenuSystem::createItem($data)) {
                    return Redirect::route('admin.menuView');
                }
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active'])? $data['active']: CGlobal::status_hide);
        $optionShowContent = FunctionLib::getOption($this->arrStatus, isset($data['showcontent'])? $data['showcontent']: CGlobal::status_show);
        $optionShowMenu = FunctionLib::getOption($this->arrStatus, isset($data['show_menu'])? $data['show_menu']: CGlobal::status_show);
        $optionShowPermission = FunctionLib::getOption($this->arrStatus, isset($data['show_permission'])? $data['show_permission']: CGlobal::status_hide);
        $optionMenuParent = FunctionLib::getOption($this->arrMenuParent, isset($data['parent_id'])? $data['parent_id'] : 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('admin.AdminMenuSystem.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
            'optionShowContent'=>$optionShowContent,
            'optionShowPermission'=>$optionShowPermission,
            'optionShowMenu'=>$optionShowMenu,
            'optionMenuParent'=>$optionMenuParent,
        ],$this->viewPermission));
    }

    public function deleteMenu()
    {
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && MenuSystem::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['banner_name']) && trim($data['banner_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
