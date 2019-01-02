<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Department;
use Illuminate\Support\Facades\Redirect;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Pagging;
use Illuminate\Http\Request;

class AdminDepartmentController extends BaseAdminController
{
    private $error = array();
    private $arrStatus = array();
    private $arrMenuParent = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct()
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý Department mới';
    }

    public function __getDataDefaut()
    {
        $this->arrStatus = array(
            STATUS_BLOCK => viewLanguage('status_choose',$this->languageSite),
            STATUS_SHOW => viewLanguage('status_show',$this->languageSite),
            STATUS_HIDE => viewLanguage('status_hidden',$this->languageSite));

        $this->viewPermission = [
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_DEPARTMENT_FULL),
            'permission_create' => $this->checkPermiss(PERMISS_DEPARTMENT_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_DEPARTMENT_DELETE)
        ];
    }

    public function __outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus,isset($data['banner_status']) ? $data['banner_status'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'pageTitle' => CGlobal::$pageAdminTitle,
        ];
    }

    public function view()
    {
        if (!$this->checkPermiss([PERMISS_DEPARTMENT_FULL,PERMISS_DEPARTMENT_VIEW]))
        {
            return Redirect::route('admin.dashboard',array('error'=>ERROR_PERMISSION));
        }
        $this->_getDataDefault();
        $pageNo = (int)Request::get('page_no',1);
        $sbmValue = Request::get('submit',1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['banner_name'] = addslashes(Request::get('banner_name',''));
        $search['banner_status'] = (int)Request::get('banner_status',-1);

        $data = app(Department::class)->searchByCondition($search,$limit,$offset);

        $paging = $data['total'] > 0 ? Pagging::getNewPager(3,$pageNo,$data['total'],$limit,$search) : '';

        $this->_outDataView($search);
        return view('admin.AdminDepartment.view',array_merge([
            'data' => $data['data'],
            'search' => $search,
            'total' => $data['total'],
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging
        ],$this->viewPermission , $this->viewOptionData));
    }

    public function getItem($id= 0)
    {
        if(!$this->checkMultiPermiss([PERMISS_DEPARTMENT_FULL,PERMISS_DEPARTMENT_CREATE]))
        {
            return Redirect::route('admin.dashboard',array('error' => ERROR_PERMISSION));
        }
        $data = (($id > 0)) ? app(Department::class)->getItemById($id) : [];
        $this->__getDataDefaut();
        $this->__outDataView($data);
        return $this->view('admin.AdminDepartment.add',array_merge([
            'data' => $data,
            'id' => $id,
        ],$this->viewPermission , $this->viewoptionData));
    }

    public function postItem($id = 0)
    {
        if (!$this->checkMultiPermiss([PERMISS_DEPARTMENT_FULL,PERMISS_DEPARTMENT_CREATE]))
        {
            return Redirect::route('admin.dashboard',array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden',0);
        $data = $_POST;

        if ($id > 0) {
            if (app(Department::class)->updateItem($id,$data)){
                return Redirect::route('admin.departmentView');
            }else{
                if (app(Department::class)->createItem($data)){
                    return Redirect::route('admin.departmentView');
                }
            }
        }
        $this->__getDataDefaut();
        $this->__outDataView($data);
        return $this->view('admin.AdminDepartment.add',array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error
        ],$this->viewPermission , $this->viewOptionData));
    }

    public function deleteDepartment()
    {
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_DEPARTMENT_FULL,PERMISS_DEPARTMENT_DELETE])){
            return Redirect::route('admin.dashboard',array('error'=>ERROR_PERMISSION));
        }
    }

}
