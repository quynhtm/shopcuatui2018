<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 09/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Shop\Department;
use App\Http\Models\Admin\User;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;

class DepartmentController extends BaseAdminController{
    private $error = array();
    private $arrStatus = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý kiểu chuyên mục';
    }
    public function _getDataDefault(){
        $this->arrStatus = array(
            STATUS_DEFAULT => viewLanguage('status_choose', $this->languageSite),
            STATUS_SHOW => viewLanguage('status_show', $this->languageSite),
            STATUS_HIDE => viewLanguage('status_hidden', $this->languageSite));

        //Out put permiss
        $this->viewPermission = [
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_DEPARTMENT_FULL),
            'permission_view' => $this->checkPermiss(PERMISS_DEPARTMENT_VIEW),
            'permission_create' => $this->checkPermiss(PERMISS_DEPARTMENT_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_DEPARTMENT_DELETE),
        ];
    }
    public function view(){
        if (!$this->checkMultiPermiss([PERMISS_DEPARTMENT_FULL, PERMISS_DEPARTMENT_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $pageNo = (int)Request::get('page_no', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['member_id'] = app(User::class)->getMemberIdUser();
        //$search['field_get'] = 'department_id,department_name,department_order,department_status,created_at,updated_at';
        $data = app(Department::class)->searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        $search['order_id'] = addslashes(Request::get('order_id',''));
        $search['order_product_name'] = addslashes(Request::get('order_product_name',''));
        $search['order_customer_name'] = addslashes(Request::get('order_customer_name',''));
        $search['order_customer_phone'] = addslashes(Request::get('order_customer_phone',''));
        $search['order_customer_email'] = addslashes(Request::get('order_customer_email',''));
        $search['time_start_time'] = addslashes(Request::get('time_start_time',''));
        $search['time_end_time'] = addslashes(Request::get('time_end_time',''));
        $search['order_status'] = (int)Request::get('order_status', -1);
        $search['order_user_shop_id'] = (int)Request::get('order_user_shop_id',-1);

        $data = Order::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';


        $this->_getDataDefault();
        $this->_outDataView($data);

        $optionStatusSearch = getOption($this->arrStatus, $search['department_status']);

        return view('shop.ShopDepartment.view', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'optionStatusSearch' => $optionStatusSearch,
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function postItem($id){
        if(!$this->checkMultiPermiss([PERMISS_DEPARTMENT_FULL, PERMISS_DEPARTMENT_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        if($this->_validData($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($data['department_name'] != ''){
                $data['department_alias'] = safe_title($data['department_name']);
            }
            if($id > 0) {
                app(Department::class)->updateItem($id, $data);
            }else{
                app(Department::class)->createItem($data);
            }
        }
        $_data['url'] = URL::route('shop.department');
        return Response::json($_data);
    }
    public function deleteItem(){
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_DEPARTMENT_FULL, PERMISS_DEPARTMENT_DELETE])) {
            return Response::json($data['msg'] = 'Bạn không có quyền thao tác.');
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Department::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    public function ajaxLoadForm(){
        if (!$this->checkMultiPermiss([PERMISS_DEPARTMENT_FULL, PERMISS_DEPARTMENT_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $id = $_POST['id'];
        $item = app(Department::class)->getItemById($id);
        $member_id = app(User::class)->getMemberIdUser();
        $data = ($item && isset($item->member_id) && $item->member_id != $member_id ) ? [] : $item;
        $this->_getDataDefault();

        $optionStatus = getOption($this->arrStatus, isset($data['department_status']) ? $data['department_status'] : STATUS_SHOW);

        return view('shop.ShopDepartment.component.ajax_load_item',
            array_merge([
                'data' => $data,
                'optionStatus' => $optionStatus,
            ], $this->viewPermission));
    }
    public function _outDataView($data){
        $optionStatus = getOption($this->arrStatus, isset($data['define_status']) ? $data['define_status'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'pageAdminTitle' => CGlobal::$pageAdminTitle,
            'arrStatus' => $this->arrStatus,
        ];
    }
    private function _validData($data = array()){
        if(!empty($data)) {
            if (isset($data['department_name']) && trim($data['department_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}