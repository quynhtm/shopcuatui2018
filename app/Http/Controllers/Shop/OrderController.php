<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Shop\Order;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;

class OrderController extends BaseAdminController
{
    private $error = array();
    private $arrStatus = array();
    private $arrMenuParent = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct()
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản Lý Đơn Hàng';
    }

    public function _getDataDefault()
    {
        $this->arrStatus = array(
            STATUS_BLOCK => viewLanguage('status_choose', $this->languageSite),
            STATUS_SHOW =>  viewLanguage('status_show', $this->languageSite),
            STATUS_HIDE =>  viewLanguage('status_hidden', $this->languageSite));

        //out put permiss
        $this->viewPermission = [
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_ORDER_FULL),
            'permission_create' => $this->checkPermiss(PERMISS_ORDER_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_ORDER_DELETE),
        ];
    }
    public function _outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus, isset($data['order_status']) ? $data['order_status'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'pageTitle' => CGlobal::$pageAdminTitle,
        ];
    }

    public function view()
    {
        //Check phan quyen.
        if (!$this->checkMultiPermiss([PERMISS_ORDER_FULL, PERMISS_ORDER_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $this->_getDataDefault();
        $pageNo = (int)Request::get('page_no', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['order_id'] = addslashes(Request::get('order_id', ''));
        $search['order_product_id'] = addslashes(Request::get('order_product_id', ''));
        $search['order_total_buy'] = addslashes(Request::get('order_total_buy', ''));
        $search['order_note'] = addslashes(Request::get('order_note', ''));
        $search['order_money_ship'] = addslashes(Request::get('order_money_ship', ''));
        $search['order_total_money'] = addslashes(Request::get('order_total_money', ''));
        $search['order_product_name'] = addslashes(Request::get('order_product_name', ''));
        $search['order_customer_name'] = addslashes(Request::get('order_customer_name', ''));
        $search['order_customer_phone'] = addslashes(Request::get('order_customer_phone', ''));
        $search['order_customer_email'] = addslashes(Request::get('order_customer_email', ''));
        $search['order_customer_address'] = addslashes(Request::get('order_customer_address', ''));
        $search['order_customer_note'] = addslashes(Request::get('order_customer_note', ''));
        $search['order_type'] = addslashes(Request::get('order_type', ''));
        $search['time_start_time'] = addslashes(Request::get('time_start_time', ''));
        $search['time_end_time'] = addslashes(Request::get('time_end_time', ''));
        $search['order_status'] = (int)Request::get('order_status', -1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = app(Order::class)->searchByCondition($search, $limit, $offset ,$total );
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
        $this->_outDataView($search);
        return view('Shop.ShopOrder.view', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function getItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_ORDER_FULL, PERMISS_ORDER_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $data = (($id > 0)) ? app(Order::class)->getItemById($id) : [];
        $this->_getDataDefault();
        $this->_outDataView($data);
        return view('admin.ShopOrder.addOrder', array_merge([
            'data' => $data,
            'id' => $id,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function postItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_ORDER_FULL, PERMISS_ORDER_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        if ($this->_validData($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                //cap nhat
                if (app(Order::class)->updateItem($id, $data)) {
                    return Redirect::route('shop.order');
                }
            } else {
                //them moi
                if (app(Order::class)->createItem($data)) {
                    return Redirect::route('shop.order');
                }
            }
        }
        $this->_getDataDefault();
        $this->_outDataView($data);
        return view('admin.ShopOrder.addOrder', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function deleteOrder()
    {
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_ORDER_FULL, PERMISS_ORDER_DELETE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Order::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    private function _validData($data = array())
    {
        if (!empty($data)) {
            if (isset($data['order_customer_name']) && trim($data['order_customer_name']) == '') {
                $this->error[] = 'Tên không được bỏ trống';
            }
        }
        return true;
    }
}
