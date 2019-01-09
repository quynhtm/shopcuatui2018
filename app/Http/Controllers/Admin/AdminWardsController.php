<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Districts;
use App\Http\Models\Admin\Province;
use App\Http\Models\Admin\Wards;
use Illuminate\Support\Facades\Request;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class AdminWardsController extends BaseAdminController
{
    private $error = array();
    private $arrStatus = array();
    private $arrMenuParent = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct()   //hàm tạo
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản Lý Xã';
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
            'permission_full' => $this->checkPermiss(PERMISS_WARDS_FULL),
            'permission_create' => $this->checkPermiss(PERMISS_WARDS_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_WARDS_DELETE),
        ];
    }
    public function _outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus, isset($data['wards_status']) ? $data['wards_status'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'pageTitle' => CGlobal::$pageAdminTitle,//thêm biến
        ];
    }

    public function view()     //hiển thị
    {
        //Check phan quyen.
        if (!$this->checkMultiPermiss([PERMISS_WARDS_FULL, PERMISS_WARDS_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $this->_getDataDefault(); //lấy dữ liệu mặc định
        $pageNo = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();

        $search['wards_name'] = addslashes(Request::get('wards_name', ''));
        $search['wards_status'] = (int)Request::get('wards_status', -1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay


// hiển thị thông tin quận

        $result = app(Wards::class)->searchByCondition($search, $limit, $offset);  //đổi $data thành $$result
        $data = isset($result['data']) ? $result['data'] : array();

        $arrDistrictsId = array();
        $arrInforDistricts = array();
        $arrProviceId =array();
        $arrInforProvince = array();

        if(sizeof($data) > 0){
            foreach($data as $item){
                $arrDistrictsId[$item['district_id']] = $item['district_id'];
            }
        }

        //lấy dữ thông tin quận
        if(!empty($arrDistrictsId)) {
            $dataDistricts = app(Districts::class)->getListDistrictsNameById($arrDistrictsId);

            if($dataDistricts){
                $searchProvice = array();
                foreach ($dataDistricts as $dis ){
                    $arrInforDistricts[$dis->district_id] = $dis->district_name;
                    $searchProvice[$dis->district_id] = $dis->district_province_id;
                }

                //lấy thông tin của tỉnh thành theo mảng id quận huyện
                if(!empty($searchProvice)) //empty( kiểm tra rỗng) còn insset là biến này có tồn tại
                {
                    $dataProvince = app(Province::class)->getListProviceNameById($searchProvice);
                    //$dataProvince là 1 mảng dữ liệu
                    if($dataProvince){
                        foreach ($searchProvice as $key_district_id=>$province_id){
                            foreach ($dataProvince as $k_id=>$name_pro){
                                if($province_id == $k_id){
                                    $arrInforProvince[$key_district_id] = $name_pro;
                                }
                            }
                        }
                    }

                }
            }
        }
        $paging = $result['total'] > 0 ? Pagging::getNewPager(3, $pageNo, $result['total'], $limit, $search) : '';

        $this->_outDataView($search);
        return view('admin.AdminWards.view', array_merge([
           'data' => $result['data'],
           'search' => $search,
           'total' => $result['total'],
           'stt' => ($pageNo - 1) * $limit,
           'paging' =>$paging,
           'arrInforDistricts' => $arrInforDistricts,
           'arrInforProvince' => $arrInforProvince,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function getItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_WARDS_FULL, PERMISS_WARDS_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $data = (($id > 0)) ? app(Wards::class)->getItemById($id) : [];
        $this->_getDataDefault();
        $this->_outDataView($data);
        /*add*/ return view('admin.AdminWards.add', array_merge([
        'data' => $data,
        'id' => $id,
    ], $this->viewPermission, $this->viewOptionData));
    }

    public function postItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_WARDS_FULL, PERMISS_WARDS_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;


        if ($this->_validData($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                //cap nhat
                if (app(Wards::class)->updateItem($id, $data)) {
                    return Redirect::route('admin.wardsView');
                }
            } else {
                //them moi
                if (app(Wards::class)->createItem($data)) {
                    return Redirect::route('admin.wardsView');
                }
            }
        }
        $this->_getDataDefault();
        $this->_outDataView($data);
        /*add*/ return view('admin.AdminWards.add', array_merge([
        'data' => $data,
        'id' => $id,
        'error' => $this->error,
    ], $this->viewPermission, $this->viewOptionData));
    }

    public function deleteWards()
    {
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_WARDS_FULL, PERMISS_WARDS_DELETE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Wards::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    private function _validData($data = array())
    {
        if (!empty($data)) {
            if (isset($data['wards_name']) && trim($data['wards_name']) == '') {
                $this->error[] = 'Tên không được bỏ trống';
            }
        }
        return true;
    }
}
