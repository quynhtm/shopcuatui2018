<?php
/*
* @Created by: DIGO - VAYMUON
* @Author    : nguyenduypt86@gmail.com/duynx@peacesoft.net
* @Date      : 09/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Member;
use App\Library\AdminFunction\CGlobal;
use \Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;

class AdminMemberController extends BaseAdminController{
    private $error = array();
    private $arrStatus = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý thành viên';
    }
    public function _getDataDefault(){
        $this->arrStatus = array(
            STATUS_HIDE => viewLanguage('status_choose', $this->languageSite),
            STATUS_SHOW => viewLanguage('status_show', $this->languageSite)
        );
        //Out put permiss
        $this->viewPermission = [
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_MEMBER_FULL),
        ];
    }
    public function view(){
        if (!$this->checkMultiPermiss([PERMISS_MEMBER_FULL, PERMISS_MEMBER_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $this->_getDataDefault();
        $pageNo = (int)Request::get('page_no', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['member_name'] = addslashes(Request::get('member_name', ''));
        $search['define_code'] = addslashes(Request::get('define_code', ''));
        $search['member_status'] = addslashes(Request::get('member_status', STATUS_DEFAULT));
        //$search['field_get'] = 'id,define_code,member_name,define_note,define_type,member_status';
        $data = app(Member::class)->searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        $this->_outDataView($data);
        $optionSearch = getOption($this->arrStatus, $search['member_status']);
        return view('admin.AdminMember.view', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'optionSearch' => $optionSearch,
        ], $this->viewPermission, $this->viewOptionData));
    }
    public function postItem($id){
        if(!$this->checkMultiPermiss([PERMISS_MEMBER_FULL, PERMISS_MEMBER_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        if($this->_validData($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {
                $data['member_update_user_id'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;
                $data['member_update_user_name'] = isset($this->user['user_name']) ? $this->user['user_name'] : '';
                app(Member::class)->updateItem($id, $data);
            }else{
                $data['member_creater_user_id'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;
                $data['member_creater_user_name'] = isset($this->user['user_name']) ? $this->user['user_name'] : '';
                app(Member::class)->createItem($data);
            }
        }
        $_data['url'] = URL::route('admin.viewMember');
        return Response::json($_data);
    }
    public function deleteItem(){
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_MEMBER_FULL, PERMISS_MEMBER_DELETE])) {
            return Response::json($data['msg'] = 'Bạn không có quyền thao tác.');
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Member::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    public function ajaxLoadForm(){
        if (!$this->checkMultiPermiss([PERMISS_MEMBER_FULL, PERMISS_BANNER_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }

        $id = $_POST['id'];
        $data = (($id > 0)) ? app(Member::class)->getItemById($id) : [];

        $this->_getDataDefault();
        $optionStatus = getOption($this->arrStatus, isset($data['member_status']) ? $data['member_status'] : STATUS_SHOW);

        return view('admin.AdminMember.component.ajax_load_item',
            array_merge([
                'data' => $data,
                'optionStatus' => $optionStatus,
            ], $this->viewPermission));
    }
    public function _outDataView($data){
        $optionStatus = getOption($this->arrStatus, isset($data['member_status']) ? $data['member_status'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'pageAdminTitle' => CGlobal::$pageAdminTitle,
            'arrStatus' => $this->arrStatus,
        ];
    }
    private function _validData($data = array()){
        if(!empty($data)) {
            if (isset($data['member_name']) && trim($data['member_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
