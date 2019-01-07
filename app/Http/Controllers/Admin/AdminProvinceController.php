<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Province;
use Illuminate\Support\Facades\Request;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;



class AdminProvinceController extends BaseAdminController
{
    private $error = array();
    private $arrStatus = array();
    private $arrMenuParent = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct()   //hàm tạo
    {
        parent::__construct();  //gọi đến hàm __construct mà hàm này kế thừa và hàm __construct này ở trong baseAdminController - hàm parent ngoài gọi để kế thừa ,mở rộng hàm cha còn có thể dùng để ghi đè lên hàm cha
        CGlobal::$pageAdminTitle = 'Quản Lý Tỉnh thành';  // ghi đè lên biến $pageAdminTitle ở model CGlobal
    }

    public function _getDataDefault() // khai báo dữ liệu mặc định
    {
        $this->arrStatus = array(   // khai báo mảng arrStatus
            STATUS_BLOCK => viewLanguage('status_choose', $this->languageSite),  //nếu nhấn vào nút(this) trạng thái thì chọn
            STATUS_SHOW =>  viewLanguage('status_show', $this->languageSite),    // trạng thái hiển thị
            STATUS_HIDE =>  viewLanguage('status_hidden', $this->languageSite)); // trạng thái ẩn

        //out put permiss -- đặt ra cho phép
        $this->viewPermission = [  // cấp quyền cho người dùng
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_PROVINCE_FULL),
            'permission_create' => $this->checkPermiss(PERMISS_PROVINCE_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_PROVINCE_DELETE),
        ];
    }
    public function _outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus, isset($data['province_status']) ? $data['province_status'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'pageTitle' => CGlobal::$pageAdminTitle,//thêm biến
        ];
    }

    public function view()     //hiển thị
    {
        //Check phan quyen.
        if (!$this->checkMultiPermiss([PERMISS_PROVINCE_FULL, PERMISS_PROVINCE_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $this->_getDataDefault(); //lấy dữ liệu mặc định
        $pageNo = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();

        $search['province_name'] = addslashes(Request::get('province_name', ''));
        $search['province_status'] = (int)Request::get('province_status', -1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = app(Province::class)->searchByCondition($search, $limit, $offset);
        $paging = $data['total'] > 0 ? Pagging::getNewPager(3, $pageNo, $data['total'], $limit, $search) : '';
        $this->_outDataView($search);
        return view('admin.AdminProvince.view', array_merge([
            'data' => $data['data'],
            'search' => $search,
            'total' => $data['total'],
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function getItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_PROVINCE_FULL, PERMISS_PROVINCE_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $data = (($id > 0)) ? app(Province::class)->getItemById($id) : [];
        $this->_getDataDefault();
        $this->_outDataView($data);
/*add*/ return view('admin.AdminProvince.add', array_merge([
            'data' => $data,
            'id' => $id,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function postItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_PROVINCE_FULL, PERMISS_PROVINCE_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;


        if ($this->_validData($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                //cap nhat
                if (app(Province::class)->updateItem($id, $data)) {
                    return Redirect::route('admin.provinceView');
                }
            } else {
                //them moi
                if (app(Province::class)->createItem($data)) {
                    return Redirect::route('admin.provinceView');
                }
            }
        }
        $this->_getDataDefault();
        $this->_outDataView($data);
/*add*/ return view('admin.AdminProvince.add', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function deleteProvince()
    {
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_PROVINCE_FULL, PERMISS_PROVINCE_DELETE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Province::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    private function _validData($data = array())
    {
        if (!empty($data)) {
            if (isset($data['province_name']) && trim($data['province_name']) == '') {
                $this->error[] = 'Tên không được bỏ trống';
            }
        }
        return true;
    }
}
