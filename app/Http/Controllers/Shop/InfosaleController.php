<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 09/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Shop\Infosale;
use App\Http\Models\Admin\User;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class InfosaleController extends BaseAdminController{
    private $error = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý thông tin người bán';
    }
    public function _getDataDefault(){
        //Out put permiss
        $this->viewPermission = [
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_INFOSALE_FULL),
            'permission_view' => $this->checkPermiss(PERMISS_INFOSALE_VIEW),
            'permission_create' => $this->checkPermiss(PERMISS_INFOSALE_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_INFOSALE_DELETE),
        ];
    }
    public function view(){
        if (!$this->checkMultiPermiss([PERMISS_INFOSALE_FULL, PERMISS_INFOSALE_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $pageNo = (int)Request::get('page_no', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['infor_sale_name'] = addslashes(Request::get('infor_sale_name', ''));
        $search['infor_sale_phone'] = addslashes(Request::get('infor_sale_phone', ''));
        $search['member_id'] = app(User::class)->getMemberIdUser();
        
        $search['field_get'] = 'infor_sale_id,infor_sale_uid,infor_sale_name,infor_sale_phone,infor_sale_mail,infor_sale_skype,infor_sale_address,infor_sale_sotaikhoan,infor_sale_vanchuyen,created_at,updated_at';
        $data = app(Infosale::class)->searchByCondition($search, $limit, $offset);
        $paging = $data['total'] > 0 ? Pagging::getNewPager(3, $pageNo, $data['total'] , $limit, $search) : '';

        $this->_getDataDefault();
        $this->_outDataView($data);

        return view('shop.ShopInfosale.view', array_merge([
            'data' => $data['data'],
            'search' => $search,
            'total' => $data['total'],
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function getItem($id){
        if (!$this->checkMultiPermiss([PERMISS_INFOSALE_FULL, PERMISS_INFOSALE_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        //$member_id = app(User::class)->getMemberIdUser();
        //$exist = app(Infosale::class)->getItemByMemberId($id);

        //$id = (isset($exist) && $exist) ? $exist->infor_sale_id : 0;
        $data = (($id > 0)) ? app(Infosale::class)->getItemById($id) : [];

        $this->_getDataDefault();
        $this->_outDataView($data);

        return view('shop.ShopInfosale.add', array_merge([
            'data' => $data,
            'id' => $id,
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function postItem($id){
        if(!$this->checkMultiPermiss([PERMISS_DEPARTMENT_FULL, PERMISS_DEPARTMENT_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        if($this->_validData($data) && empty($this->error)) {
            $id = $id_hiden;

            $data['infor_sale_uid'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;

            if($id > 0) {
                app(Infosale::class)->updateItem($id, $data);
            }else{
                app(Infosale::class)->createItem($data);
            }

            return Redirect::route('shop.infosale');
        }
        return view('shop.ShopInfosale.add', array_merge([
            'data' => $data,
            'id' => $id,
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function deleteItem(){
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_INFOSALE_FULL, PERMISS_INFOSALE_DELETE])) {
            return Response::json($data['msg'] = 'Bạn không có quyền thao tác.');
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Infosale::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    public function _outDataView($data){
        return $this->viewOptionData = [
            'pageAdminTitle' => CGlobal::$pageAdminTitle,
        ];
    }
    private function _validData($data = array()){
        if(!empty($data)) {
            if (isset($data['infor_sale_name']) && trim($data['infor_sale_name']) == '') {
                $this->error[] = 'Null';
            }
            if (isset($data['infor_sale_phone']) && trim($data['infor_sale_phone']) == '') {
                $this->error[] = 'Null';
            }
            if (isset($data['infor_sale_mail']) && trim($data['infor_sale_mail']) == '') {
                $this->error[] = 'Null';
            }
            if (isset($data['infor_sale_address']) && trim($data['infor_sale_address']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}