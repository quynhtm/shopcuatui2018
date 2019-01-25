<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Banners;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Upload;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class AdminBannersController extends BaseAdminController
{
    private $error = array();
    private $arrStatus = array();
    private $arrMenuParent = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct()
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý Banner quảng cáo mới';
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
            'permission_full' => $this->checkPermiss(PERMISS_BANNER_FULL),
            'permission_create' => $this->checkPermiss(PERMISS_BANNER_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_BANNER_DELETE),
        ];
    }
    public function _outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus, isset($data['banner_status']) ? $data['banner_status'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'pageTitle' => CGlobal::$pageAdminTitle,
        ];
    }

    public function view()     //hiển thị
    {
        //Check phan quyen.
        if (!$this->checkMultiPermiss([PERMISS_BANNER_FULL, PERMISS_BANNER_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $this->_getDataDefault();
        $pageNo = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();

        $search['banner_name'] = addslashes(Request::get('banner_name', ''));
        $search['banner_status'] = (int)Request::get('banner_status', -1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = app(Banners::class)->searchByCondition($search, $limit, $offset);
        $paging = $data['total'] > 0 ? Pagging::getNewPager(3, $pageNo, $data['total'], $limit, $search) : '';
        $this->_outDataView($search);
        return view('admin.AdminBanners.view', array_merge([
            'data' => $data['data'],
            'search' => $search,
            'total' => $data['total'],
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function getItem($id=0)
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

    public function postItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_BANNER_FULL, PERMISS_BANNER_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        if(isset($_FILES['banner_image']) && count($_FILES['banner_image'])>0 && $_FILES['banner_image']['name'] != '') {
            $folder = 'banner';
            $_max_file_size = 10 * 1024 * 1024;
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
                $this->error[] = 'Tên banner không được bỏ trống';
            }

            if (isset($data['banner_link']) && trim($data['banner_link']) == ''){
                $this->error[] = 'Tên URL không được bỏ trống';
            }
        }
        return true;
    }
}
