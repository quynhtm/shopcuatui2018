<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Banners;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Upload;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class AdminBannersController extends BaseAdminController
{
    private $error = array();           //lỗi
    private $arrStatus = array();       //trạng thái
    private $arrMenuParent = array();   //
    private $viewOptionData = array();  //dữ liệu tùy chọn
    private $viewPermission = array();  //check quyen



    public function __construct()   //hàm tạo
    {
        parent::__construct();  //gọi đến hàm __construct mà hàm này kế thừa và hàm __construct này ở trong baseAdminController - hàm parent ngoài gọi để kế thừa ,mở rộng hàm cha còn có thể dùng để ghi đè lên hàm cha
        CGlobal::$pageAdminTitle = 'Quản lý Banner quảng cáo mới';  // ghi đè lên biến $pageAdminTitle ở model CGlobal
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
            'permission_full' => $this->checkPermiss(PERMISS_BANNER_FULL),
            'permission_create' => $this->checkPermiss(PERMISS_BANNER_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_BANNER_DELETE),
        ];
    }

    public function view()     //hiển thị
    {
        //Check phan quyen.
        if (!$this->checkMultiPermiss([PERMISS_BANNER_FULL, PERMISS_BANNER_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $this->_getDataDefault(); //lấy dữ liệu mặc định
        $pageNo = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['banner_name'] = addslashes(Request::get('banner_name', ''));
        $search['banner_status'] = (int)Request::get('banner_status', -1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = app(Banners::class)->searchByCondition($search, $limit, $offset, $total);
        // dd($data); kiểm tra lấy dữ liệu

        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
        $optionStatus = getOption($this->arrStatus, $search['banner_status']);
        //vmDebug($data);
        return view('admin.AdminBanners.view', array_merge([
            'data' => $data['data'],
// sai ở đoạn đường dẫn lúc đầu chỉ lấy ở 'data' => $data tức là đang foreach cả cục
//  "data" => Collection {#409 ▶}    dùng lệnh dd($data) để hiển thị
//  "total" => 0
// sửa như sau 'data' => $data['data'] : tức là vào hẳn đường dẫn data rồi foreach để hiển thị dữ liệu trong data

            'search' => $search,
            'total' => $total,
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'optionStatus' => $optionStatus,
        ], $this->viewPermission));
    }

    public function getItem($id)
    {
        if (!$this->checkMultiPermiss([PERMISS_BANNER_FULL, PERMISS_BANNER_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $data = (($id > 0)) ? app(Banners::class)->getItemById($id) : [];
        $this->_getDataDefault();
        $this->_outDataView($data);
        return view('admin.AdminBanners.add', array_merge([
            'data' => $data,
            'id' => $id,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function postItem($id)
    {
        if (!$this->checkMultiPermiss([PERMISS_BANNER_FULL, PERMISS_BANNER_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        if(isset($_FILES['banner_image']) && count($_FILES['banner_image'])>0 && $_FILES['banner_image']['name'] != '') {

            $folder = 'banner';
            $_max_file_size = 10* 1024* 1024;
            $_file_ext = 'jpg,jpeg,png,gif';
            $pathFileUpload = app(Upload::class)->uploadFile('banner_image', $_file_ext, $_max_file_size, $folder);
            $data['banner_image'] = trim($pathFileUpload) != ''? $pathFileUpload: '';
        }

        if ($this->_validData($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                //cap nhat
                if (app(Banners::class)->updateItem($id, $data)) {
                    return Redirect::route('admin.bannerView');
                }
            } else {
                //them moi
                if (app(Banners::class)->createItem($data)) {
                    return Redirect::route('admin.bannerView');
                }
            }
        }
        $this->_getDataDefault();
        $this->_outDataView($data);
        return view('admin.AdminBanners.add', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function _outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus, isset($data['status']) ? $data['status'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
        ];
    }

    public function deleteBanner()
    {
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_BANNER_FULL, PERMISS_BANNER_DELETE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Banners::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    private function _validData($data = array())
    {
        if (!empty($data)) {
            if (isset($data['banner_name']) && trim($data['banner_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
