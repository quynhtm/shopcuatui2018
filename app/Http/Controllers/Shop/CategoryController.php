<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 09/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Shop\Category;
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
    private $arrStatus = array();
    private $arrMenu = array();
    private $arrMenuRight = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản Lý Danh Mục';
    }
    public function _getDataDefault(){
        $this->arrStatus = $this->arrMenu = $this->arrMenuRight = array(
            STATUS_DEFAULT => viewLanguage('status_choose', $this->languageSite),
            STATUS_SHOW => viewLanguage('status_show', $this->languageSite),
            STATUS_HIDE => viewLanguage('status_hidden', $this->languageSite));

        //Out put permiss
        $this->viewPermission = [
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_CATEGORY_FULL),
            'permission_view' => $this->checkPermiss(PERMISS_CATEGORY_VIEW),
            'permission_create' => $this->checkPermiss(PERMISS_CATEGORY_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_CATEGORY_DELETE),
        ];
    }
    public function _outDataView($data){
        $optionStatus = getOption($this->arrStatus, isset($data['category_status']) ? $data['category_status'] : STATUS_HIDE);
        $optionMenu = getOption($this->arrMenu, isset($data['category_menu_status']) ? $data['category_menu_status'] : STATUS_HIDE);
        $optionMenuRight = getOption($this->arrMenuRight, isset($data['category_menu_right']) ? $data['category_menu_right'] : STATUS_HIDE);

        return $this->viewOptionData = [
            'pageAdminTitle' => CGlobal::$pageAdminTitle,
            'arrCategoryParent' => $this->arrCategoryParent,
            'optionStatus' => $optionStatus,
            'optionMenu' => $optionMenu,
            'optionMenuRight' => $optionMenuRight,
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

        $search['category_name'] = addslashes(Request::get('category_name', ''));
        $search['category_status'] = (int)Request::get('category_status',-1);
        $search['category_depart_id'] = (int)Request::get('category_depart_id',-1);
        $search['category_type'] = (int)Request::get('category_type',-1);
        $search['category_menu_right'] = (int)Request::get('category_menu_right',-1);
        $search['member_id'] = app(User::class)->getMemberIdUser();
        $search['field_get'] = '';
        $data  = app(Category::class)->searchByCondition($search, $limit, $offset);
//lấy dữ liệu để hiện cha.
        $arrCategoryId = array();
        $arrInforCategory= array();
        $result = isset($data['data']) ? $data['data'] : array();

        if(sizeof($result) > 0){
            foreach($result as $item){
                $arrCategoryId[$item['category_parent_id']] = $item['category_parent_id'];
            }
        }
//lấy thông tin tỉnh thành cha
        if(!empty($arrCategoryId)){
            $arrInforCategory = app(Category::class)->getListCategoryNameById($arrCategoryId);
            }

        if(!empty($dataSearch)){
            $data =  app(Category::class)->getTreeCategory($data);
            $data = !empty($data) ? $data : $dataSearch;
        }

        $paging = $data['total'] > 0 ? Pagging::getNewPager(3, $pageNo, $data['total'], $limit, $search) : '';
        $this->_getDataDefault();
        $this->_outDataView($data);

        return view('shop.ShopCategory.view', array_merge([
            'data' => $data['data'],
            'search' => $search,
            'total' => $data['total'],
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'arrInforCategory' => $arrInforCategory
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function getItem($id){
        if (!$this->checkMultiPermiss([PERMISS_CATEGORY_FULL, PERMISS_CATEGORY_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $data=[];
        if($id > 0){
            $data = (($id > 0)) ? app(Category::class)->getItemById($id) : [];
        }

        $type = $this->category_type;
        if($id > 0) {
            $type = isset($data['category_type']) ? $data['category_type'] : $type;
        }
        $category_type = (int)Request::get('category_type', $type);
        $this->arrCategoryParent = app(Category::class)->getAllParentCateWithType($category_type);

        $this->_getDataDefault();
        $this->_outDataView($data);

        $optionCategoryParent = FunctionLib::getOption($this->arrCategoryParent, isset($data['category_parent_id'])? $data['category_parent_id'] : 0);

        return view('shop.ShopCategory.add', array_merge([
            'data' => $data,
            'id' => $id,
            'optionCategoryParent' => $optionCategoryParent,
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function postItem($id){
        if(!$this->checkMultiPermiss([PERMISS_CATEGORY_FULL, PERMISS_CATEGORY_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        if($this->_validData($data) && empty($this->error)) {
            $id = $id_hiden;

            $data['member_id'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;

            if($id > 0) {
                app(Category::class)->updateItem($id, $data);
            }else{
                app(Category::class)->createItem($data);
            }
            return Redirect::route('shop.category');
        }
        return view('shop.ShopCategory.add', array_merge([
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

    private function _validData($data = array()){
        if(!empty($data)) {
            if (isset($data['category_name']) && trim($data['category_name']) == '') {
                $this->error[] = 'Không được bỏ trống name';
            }

            if (isset($data['category_status']) && trim($data['category_status']) == '') {
                $this->error[] = 'Không được bỏ trống status';
            }
        }
        return true;
    }
}