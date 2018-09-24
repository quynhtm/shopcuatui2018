<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 09/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Category;
use App\Http\Models\Admin\User;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\FunctionLib;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class CategoryController extends BaseAdminController{
    private $error = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    private $category_type = CATEGORY_PRODUCT;
    private $arrCategoryParent = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý danh mục';
    }
    public function _getDataDefault(){
        //Out put permiss
        $this->viewPermission = [
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_CATEGORY_FULL),
            'permission_view' => $this->checkPermiss(PERMISS_CATEGORY_VIEW),
            'permission_create' => $this->checkPermiss(PERMISS_CATEGORY_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_CATEGORY_DELETE),
        ];
    }
    public function view(){
        if (!$this->checkMultiPermiss([PERMISS_CATEGORY_FULL, PERMISS_CATEGORY_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $pageNo = (int)Request::get('page_no', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['category_name'] = addslashes(Request::get('category_name', ''));
        $search['category_status'] = (int)Request::get('category_status',-1);
        $search['category_depart_id'] = (int)Request::get('category_depart_id',-1);
        $search['category_type'] = (int)Request::get('category_type',-1);
        $search['category_menu_right'] = (int)Request::get('category_menu_right',-1);
        $search['member_id'] = app(User::class)->getMemberIdUser();
        
        $search['field_get'] = '';
        $dataSearch = app(Category::class)->searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        if(!empty($dataSearch)){
            $data =  app(Category::class)->getTreeCategory($data);
            $data = !empty($data) ? $data : $dataSearch;
        }
        $paging = '';

        $this->_getDataDefault();
        $this->_outDataView($data);

        return view('shop.ShopCategory.view', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function getItem($id){
        if (!$this->checkMultiPermiss([PERMISS_CATEGORY_FULL, PERMISS_CATEGORY_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $member_id = app(User::class)->getMemberIdUser();
        $exist = app(Category::class)->getItemByMemberId($member_id);

        $id = (isset($exist) && $exist) ? $exist->category_id : 0;
        $data = (($id > 0)) ? app(Category::class)->getItemById($id) : [];

        $this->_getDataDefault();
        $this->_outDataView($data);

        return view('shop.ShopCategory.add', array_merge([
            'data' => $data,
            'id' => $id,
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function postItem($id){
        if(!$this->checkMultiPermiss([PERMISS_DEPARTMENT_FULL, PERMISS_DEPARTMENT_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        if($this->_validData($data) && empty($this->error)) {
            $id = $id_hiden;

            $data['infor_sale_uid'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;

            if($id > 0) {
                app(Infosale::class)->updateItem($id, $data);
            }else{
                app(Infosale::class)->createItem($data);
            }
            return Redirect::route('shop.infosale');
        }
        return view('shop.ShopInfosale.add', array_merge([
            'data' => $data,
            'id' => $id,
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function deleteItem(){
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_CATEGORY_FULL, PERMISS_CATEGORY_DELETE])) {
            return Response::json($data['msg'] = 'Bạn không có quyền thao tác.');
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Category::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    public function _outDataView($data){
        $this->category_type = (int)Request::get('category_type', 0);
        $this->arrCategoryParent = app(Category::class)->getAllParentCateWithType($this->category_type);

        return $this->viewOptionData = [
            'pageAdminTitle' => CGlobal::$pageAdminTitle,
            'arrCategoryParent' => $this->arrCategoryParent,
        ];
    }
    private function _validData($data = array()){
        if(!empty($data)) {
            if (isset($data['category_name']) && trim($data['category_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}