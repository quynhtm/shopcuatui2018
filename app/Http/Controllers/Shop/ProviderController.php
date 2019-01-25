<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 09/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Shop\Provider;
use App\Http\Models\Admin\User;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;

class ProviderController extends BaseAdminController{
    private $error = array();
    private $arrStatus = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý nhà cung cấp';
    }
    public function _getDataDefault(){
        $this->arrStatus = array(
            STATUS_DEFAULT => viewLanguage('status_choose', $this->languageSite),
            STATUS_SHOW => viewLanguage('status_show', $this->languageSite),
            STATUS_HIDE => viewLanguage('status_hidden', $this->languageSite));

        //Out put permiss
        $this->viewPermission = [
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_PROVIDER_FULL),
            'permission_view' => $this->checkPermiss(PERMISS_PROVIDER_VIEW),
            'permission_create' => $this->checkPermiss(PERMISS_PROVIDER_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_PROVIDER_DELETE),
        ];
    }
    public function view(){
        if (!$this->checkMultiPermiss([PERMISS_PROVIDER_FULL, PERMISS_PROVIDER_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $pageNo = (int)Request::get('page_no', 1);
        $limit = LIMIT_RECORD_20;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['provider_name'] = addslashes(Request::get('provider_name', ''));
        $search['provider_status'] = addslashes(Request::get('provider_status', STATUS_DEFAULT));
        $search['member_id'] = app(User::class)->getMemberIdUser();
        //$search['field_get'] = 'department_id,provider_name,department_order,provider_status,created_at,updated_at';

        $data = app(Provider::class)->searchByCondition($search, $limit, $offset);
        $paging = $data['total'] > 0 ? Pagging::getNewPager(3, $pageNo, $data['total'], $limit, $search) : '';

        $this->_getDataDefault();
        $this->_outDataView($data);

        $optionStatusSearch = getOption($this->arrStatus, $search['provider_status']);
        return view('shop.ShopProvider.view', array_merge([
            'data' => $data['data'],
            'search' => $search,
            'total' => $data['total'],
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'optionStatusSearch' => $optionStatusSearch,
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function postItem($id){
        if(!$this->checkMultiPermiss([PERMISS_PROVIDER_FULL, PERMISS_PROVIDER_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        if($this->_validData($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {
                app(Provider::class)->updateItem($id, $data);
            }else{
                app(Provider::class)->createItem($data);
            }
        }
        $_data['url'] = URL::route('shop.provider');
        return Response::json($_data);
    }
    public function deleteItem(){
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_PROVIDER_FULL, PERMISS_PROVIDER_DELETE])) {
            return Response::json($data['msg'] = 'Bạn không có quyền thao tác.');
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Provider::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    public function ajaxLoadForm(){
        if (!$this->checkMultiPermiss([PERMISS_PROVIDER_FULL, PERMISS_PROVIDER_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $id = $_POST['id'];
        $item = app(Provider::class)->getItemById($id);
        $member_id = app(User::class)->getMemberIdUser();
        $data = ($item && isset($item->member_id) && $item->member_id != $member_id ) ? [] : $item;

        $this->_getDataDefault();
        $optionStatus = getOption($this->arrStatus, isset($data['provider_status']) ? $data['provider_status'] : STATUS_SHOW);
        return view('shop.ShopProvider.component.ajax_load_item',
            array_merge([
                'data' => $data,
                'optionStatus' => $optionStatus,
            ], $this->viewPermission));
    }
    public function _outDataView($data){
        $optionStatus = getOption($this->arrStatus, isset($data['provider_status']) ? $data['provider_status'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'pageAdminTitle' => CGlobal::$pageAdminTitle,
            'arrStatus' => $this->arrStatus,
        ];
    }
    private function _validData($data = array()){
        if(!empty($data)) {
            if (isset($data['provider_name']) && trim($data['provider_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}