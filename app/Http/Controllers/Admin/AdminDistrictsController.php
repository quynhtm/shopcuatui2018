<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Districts;
use App\Http\Models\Admin\Province;
use Illuminate\Support\Facades\Request;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class AdminDistrictsController extends BaseAdminController
{
    private $error = array();
    private $arrStatus = array();
    private $arrMenuParent = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct()   //hàm tạo
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản Lý Quận Huyện';
    }

    public function _getDataDefault() // khai báo dữ liệu mặc định
    {
        $this->arrStatus = array(
            STATUS_BLOCK => viewLanguage('status_choose', $this->languageSite),
            STATUS_SHOW =>  viewLanguage('status_show', $this->languageSite),
            STATUS_HIDE =>  viewLanguage('status_hidden', $this->languageSite));

        //out put permiss
        $this->viewPermission = [
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_DISTRICTS_FULL),
            'permission_create' => $this->checkPermiss(PERMISS_DISTRICTS_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_DISTRICTS_DELETE),
        ];
    }
    public function _outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus, isset($data['district_status']) ? $data['district_status'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'pageTitle' => CGlobal::$pageAdminTitle,//thêm biến
        ];
    }

    public function view()     //hiển thị
    {
        //Check phan quyen.
        if (!$this->checkMultiPermiss([PERMISS_DISTRICTS_FULL, PERMISS_DISTRICTS_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $this->_getDataDefault(); //lấy dữ liệu mặc định
        $pageNo = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();

        $search['district_name'] = addslashes(Request::get('district_name', ''));
        $search['district_status'] = (int)Request::get('district_status', -1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

//hiện thị thông tin tỉnh
        $result = app(Districts::class)->searchByCondition($search, $limit, $offset);  //đổi $data thành $$result

        $data = isset($result['data']) ? $result['data'] : array();
        $arrProviceId = array();
        $arrInforProvice = array();
        if(sizeof($data) > 0){
            foreach($data as $item){
                $arrProviceId[$item['district_province_id']] = $item['district_province_id'];
            }
        }

        //lấy thông tin tỉnh thành cha
        if(!empty($arrProviceId)){
            $arrInforProvice = app(Province::class)->getListProviceNameById($arrProviceId);
        }
//

        $paging = $result['total'] > 0 ? Pagging::getNewPager(3, $pageNo, $result['total'], $limit, $search) : '';
        $this->_outDataView($search);
        return view('admin.AdminDistricts.view', array_merge([
            'data' => $result['data'],
            'search' => $search,
            'total' => $result['total'],
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'arrInforProvice' => $arrInforProvice,   //
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function getItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_DISTRICTS_FULL, PERMISS_DISTRICTS_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $data = (($id > 0)) ? app(Districts::class)->getItemById($id) : [];
        $this->_getDataDefault();
        $this->_outDataView($data);
        /*add*/ return view('admin.AdminDistricts.add', array_merge([
        'data' => $data,
        'id' => $id,
    ], $this->viewPermission, $this->viewOptionData));
    }

    public function postItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_DISTRICTS_FULL, PERMISS_DISTRICTS_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;


        if ($this->_validData($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                //cap nhat
                if (app(Districts::class)->updateItem($id, $data)) {
                    return Redirect::route('admin.districtsView');
                }
            } else {
                //them moi
                if (app(Districts::class)->createItem($data)) {
                    return Redirect::route('admin.districtsView');
                }
            }
        }
        $this->_getDataDefault();
        $this->_outDataView($data);
        /*add*/ return view('admin.AdminDistricts.add', array_merge([
        'data' => $data,
        'id' => $id,
        'error' => $this->error,
    ], $this->viewPermission, $this->viewOptionData));
    }

    public function deleteDistricts()  // lưu ý hàm
    {
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_DISTRICTS_FULL, PERMISS_DISTRICTS_DELETE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Districts::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }


    private function _validData($data = array())
    {
        if (!empty($data)) {
            if (isset($data['district_name']) && trim($data['district_name']) == '') {
                $this->error[] = 'Tên không được bỏ trống';
            }
        }
        return true;
    }
}
