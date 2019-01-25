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


    public function __construct()
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản Lý Quận Huyện';
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
            'permission_full' => $this->checkPermiss(PERMISS_DISTRICTS_FULL),
            'permission_create' => $this->checkPermiss(PERMISS_DISTRICTS_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_DISTRICTS_DELETE),
        ];
    }
    public function _outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus, isset($data['district_status']) ? $data['district_status'] : STATUS_SHOW);
        $optionProvince = getOption(app(Province::class)->getAllProvince(),isset($data['district_province_id']) ? $data['district_province_id'] : PROVINCE_SHOW );

        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'optionProvince' => $optionProvince,
            'pageTitle' => CGlobal::$pageAdminTitle,
        ];
    }

    public function view()
    {
        //Check phan quyen.
        if (!$this->checkMultiPermiss([PERMISS_DISTRICTS_FULL, PERMISS_DISTRICTS_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $this->_getDataDefault();
        $pageNo = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $arrProvinceId = array();// chua nhung id cua tinh thanh ma search theo name

//tìm kiếm theo tỉnh thành và add tỉnh thành theo option
        $search['district_name'] = addslashes(Request::get('district_name', ''));
        $search['province_name'] = addslashes(Request::get('province_name', ''));
        $search['district_status'] = (int)Request::get('district_status', -1);

        if($search['province_name'] !== ''){
            $province_by_name = app(Province::class)->searchByCondition($search,70,0,false); // nếu total là false thì n sẽ k đếm dữ liệu trong model nữa
            if(count($province_by_name['data']) > 0){
                foreach ($province_by_name['data'] as $key => $value){
                    $arrProvinceId[$value->province_id] = $value->province_id;  // cho nó bằng chính nó để tránh bị trùng . để luôn luôn chỉ có 1 id duy nhất k xảy ra hiện tượng id trùng nhau
                }
            }
        }
        if(count($arrProvinceId) > 0){
            $search['district_province_id'] = $arrProvinceId;
        }
        $arrInforProvice = app(Province::class)->getAllProvince();
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $result = app(Districts::class)->searchByCondition($search, $limit, $offset);
//        $data = isset($result['data']) ? $result['data'] : array();

        $paging = $result['total'] > 0 ? Pagging::getNewPager(3, $pageNo, $result['total'], $limit, $search) : '';
        $this->_outDataView($search);

        return view('admin.AdminDistricts.view', array_merge([
            'data' => $result['data'],
            'search' => $search,
            'total' => $result['total'],
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'arrInforProvice' => $arrInforProvice,
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
        return view('admin.AdminDistricts.add', array_merge([
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
        return view('admin.AdminDistricts.add', array_merge([
        'data' => $data,
        'id' => $id,
        'error' => $this->error,
    ], $this->viewPermission, $this->viewOptionData));
    }

    public function deleteDistricts()
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
            if (isset($data['district_status']) && trim($data['district_status']) == '') {
                $this->error[] = 'Trạng thái không được bỏ trống';
            }
        }
        return true;
    }
}
