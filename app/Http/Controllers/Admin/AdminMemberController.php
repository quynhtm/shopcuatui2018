<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\MemberSite;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;


class AdminMemberController extends BaseAdminController
{
    private $permission_view = 'member_view';
    private $permission_full = 'member_full';
    private $permission_delete = 'member_delete';
    private $permission_create = 'member_create';
    private $permission_edit = 'member_edit';

    private $arrStatus = array();
    private $error = array();
    private $viewPermission = array();
    private $arrDefinedType = array();
    private $object_member;

    public function __construct()
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý Member';
        $this->object_member = new MemberSite();
    }

    public function getDataDefault()
    {
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden', $this->languageSite)
        );
        $this->arrDefinedType = CGlobal::$arrTypeMember;
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_boss' => $this->is_boss ? 1 : 0,
            'is_root' => $this->is_root ? 1 : 0,
            'permission_edit' => in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create' => in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_remove' => in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full' => in_array($this->permission_full, $this->permission) ? 1 : 0,
        ];
    }

    public function view()
    {
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        $pageNo = (int)Request::get('page_no', 1);
        $limit = CGlobal::number_show_15;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['member_name'] = addslashes(Request::get('member_name_s'));
        $search['member_type'] = (int)Request::get('member_type',Define::STATUS_HIDE);
        $search['field_get'] = '';

        $dataView = $this->object_member->searchByCondition($search, $limit, $offset, $total);
        unset($search['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$search) : '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($search['member_status']) ? $search['member_status'] : CGlobal::status_show);
        $optionDefinedType = FunctionLib::getOption($this->arrDefinedType, isset($search['member_type']) ? $search['member_type'] : CGlobal::hr_tu_nhan);

        $this->viewPermission = $this->getPermissionPage();

        return view('admin.AdminMember.view', array_merge([
            'data' => $dataView,
            'search' => $search,
            'total' => $total,
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'optionStatus' => $optionStatus,
            'arrStatus' => $this->arrStatus,
            'optionDefinedType' => $optionDefinedType,
            'arrDefinedType' => $this->arrDefinedType,
        ], $this->viewPermission));
    }

    public function postItem($ids)
    {
        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $arrSucces = ['isOk' => 0];
        $id_hiden = (int)Request::get('id', 0);
        $data = $_POST;
        unset($data['id']);
         if ($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                $data['member_update_time'] = time();
                $data['member_update_user_id'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;
                $data['member_update_user_name'] = isset($this->user['user_name']) ? $this->user['user_name'] : 0;
                $this->object_member->updateItem($id, $data);
            } else {
                $data['member_creater_time'] = time();
                $data['member_creater_user_id'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;
                $data['member_creater_user_name'] = isset($this->user['user_name']) ? $this->user['user_name'] : 0;
                $this->object_member->createItem($data);
            }
            $arrSucces['isOk'] = 1;
            $arrSucces['url'] = URL::route('admin.memberView');
            return $arrSucces;
        }
        return $arrSucces;
    }

    public function deleteItem()
    {
        $data = array('isIntOk' => 0);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_delete, $this->permission)) {
            return Response::json($data);
        }
        $id = isset($_GET['id']) ? FunctionLib::outputId($_GET['id']) : 0;
        if ($id > 0) {
            $dataUpdate['member_status'] = Define::STATUS_BLOCK;
            $this->object_member->updateItem($id, $dataUpdate);
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    public function ajaxLoadForm()
    {
        $ids = $_POST['id'];
        $id = FunctionLib::outputId($ids);
        $data = [];
        if ($id > 0) {
            $data = MemberSite::find($id);
        }
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['member_status']) ? $data['member_status'] : CGlobal::status_show);
        $optionDefinedType = FunctionLib::getOption($this->arrDefinedType, isset($data['member_type']) ? $data['member_type'] : CGlobal::hr_tu_nhan);

        return view('admin.AdminMember.ajaxLoadForm',
            array_merge([
                'data' => $data,
                'optionStatus' => $optionStatus,
                'optionDefinedType' => $optionDefinedType,
            ], $this->viewPermission));
    }

    private function valid($data = array())
    {
        if (!empty($data)) {
            if (isset($data['define_name']) && trim($data['define_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
