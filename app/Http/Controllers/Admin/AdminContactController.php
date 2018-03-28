<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Contact;
use App\Http\Models\Admin\Video;
use App\Http\Models\News\CategoryNew;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class AdminContactController extends BaseAdminController
{
    private $permission_view = 'adminContactView';
    private $permission_full = 'adminContactFull';
    private $permission_delete = 'adminContactDelete';
    private $permission_create = 'adminContactCreate';
    private $permission_edit = 'adminContactEdit';
    private $arrStatus = array();
    private $error = array();
    private $arrayCategorySearch = [];
    private $arrMenuParent = array();
    private $viewPermission = array();//check quyen

    public function __construct()
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Danh mục Contact';
    }

    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite));
    }

    public function getPermissionPage(){
        return $this->viewPermission = [
            'is_root'=> $this->is_root ? 1:0,
            'permission_edit'=>in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create'=>in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_delete'=>in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full'=>in_array($this->permission_full, $this->permission) ? 1 : 0,
        ];
    }

    public function view() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;
        $search['contact_title'] = Request::get('contact_title','');
        $search['contact_content'] = Request::get('contact_content','');
        $search['contact_user_name_send'] = Request::get('contact_user_name_send','');

        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay
        $dataSearch = Contact::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$search) : '';
        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('admin.AdminContact.view',array_merge([
            'data'=>$dataSearch,
            'search'=>$search,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'arrayStatus'=>$this->arrStatus,
        ],$this->viewPermission));
    }

    public function getItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = Contact::find($id);
        }
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active'])? $data['active']: CGlobal::status_show);
        $this->viewPermission = $this->getPermissionPage();
        return view('admin.AdminContact.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
        ],$this->viewPermission));
    }

    public function postItem($ids) {
        $id = FunctionLib::outputId($ids);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
//        FunctionLib::debug($data);

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0)?$id_hiden: $id;
            if($id > 0) {
                //cap nhat
                if(Video::updateItem($id, $data)) {
                    return Redirect::route('admin.contactView');
                }
            }else{
                //them moi
                if(Video::createItem($data)) {
                    return Redirect::route('admin.contactView');
                }
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active'])? $data['active']: CGlobal::status_hide);
        $this->viewPermission = $this->getPermissionPage();
        return view('admin.AdminContact.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
        ],$this->viewPermission));
    }

    public function deleteContact()
    {
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && Contact::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['video_name']) && trim($data['video_name']) == '') {
                $this->error[] = 'Null';
            }
            if(isset($data['video_link']) && trim($data['video_link']) == '') {
                $this->error[] = 'Null';
            }
            if(isset($data['video_sort_desc']) && trim($data['video_sort_desc']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
