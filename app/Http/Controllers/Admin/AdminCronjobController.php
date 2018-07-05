<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Cronjob;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class AdminCronjobController extends BaseAdminController{

	private $permission_view = 'adminCronjob_view';
	private $permission_full = 'adminCronjob_full';
	private $permission_delete = 'adminCronjob_delete';
	private $permission_create = 'adminCronjob_create';
	private $permission_edit = 'adminCronjob_edit';

	private $arrStatus = array();
	private $error = array();
	private $viewPermission = array();

	public function __construct(){
		parent::__construct();
		CGlobal::$pageAdminTitle = 'Quản lý cronjob';
	}
	public function getDataDefault(){
		$this->arrStatus = array(
			CGlobal::status_block => 'Không hoạt động',
			CGlobal::status_show => 'Hoạt động',
		);
	}
    public function getPermissionPage(){
        return $this->viewPermission = [
            'is_root'=> $this->is_root ? 1:0,
            'permission_edit'=>in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create'=>in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_remove'=>in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full'=>in_array($this->permission_full, $this->permission) ? 1 : 0,
        ];
    }
	public function view(){
		$pageNo = (int) Request::get('page_no',1);
		$limit = CGlobal::number_limit_show;
		$total = 0;
		$offset = ($pageNo - 1) * $limit;

		$dataSearch['cronjob_name'] = addslashes(Request::get('cronjob_name',''));
		$dataSearch['cronjob_status'] = addslashes(Request::get('cronjob_status', -1));
		$dataSearch['field_get'] = '';

		$data = Cronjob::searchByCondition($dataSearch, $limit, $offset,$total);
		$paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

		$this->getDataDefault();
		$optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['cronjob_status']);

        $this->viewPermission = $this->getPermissionPage();

		return view('admin.AdminCronjobs.view',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
        ],$this->viewPermission));


	}
	public function getItem($ids){
		$id = FunctionLib::outputId($ids);
		$data = array();
		if($id > 0) {
			$data = Cronjob::getItemById($id);
		}
		$this->getDataDefault();
		$optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['cronjob_status'])? $data['cronjob_status']: CGlobal::status_show);
        $this->viewPermission = $this->getPermissionPage();

		return view('admin.AdminCronjobs.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'optionStatus'=>$optionStatus,
        ],$this->viewPermission));
	}
	public function postItem($ids){
		$id = FunctionLib::outputId($ids);
		$id_hiden = (int)Request::get('id_hiden', 0);
		$data = $_POST;

		if(isset($data['cronjob_type'])) {
			$data['cronjob_type'] = (int)$data['cronjob_type'];
		}
		if(isset($data['cronjob_number_plan'])) {
			$data['cronjob_number_plan'] = (int)$data['cronjob_number_plan'];
		}
		if(isset($data['cronjob_number_running'])) {
			$data['cronjob_number_running'] = (int)$data['cronjob_number_running'];
		}
		if(isset($data['cronjob_date_run'])) {
			$data['cronjob_date_run'] = FunctionLib::convertDate($data['cronjob_date_run']);
		}
		if(isset($data['cronjob_status'])) {
			$data['cronjob_status'] = (int)$data['cronjob_status'];
		}

		if($this->valid($data) && empty($this->error)) {
			$id = ($id == 0) ? $id_hiden : $id;
			if($id > 0) {
				if(Cronjob::updateItem($id, $data)) {
					return Redirect::route('admin.CronjobView');
				}
			}else{
				if(Cronjob::createItem($data)) {
					return Redirect::route('admin.CronjobView');
				}
			}
		}

		$this->getDataDefault();
		$optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['cronjob_status'])? $data['cronjob_status']: CGlobal::status_show);

        $this->viewPermission = $this->getPermissionPage();

		return view('admin.AdminCronjobs.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
        ],$this->viewPermission));

	}
	public function deleteCronjob(){
		$data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
		$id = isset($_GET['id']) ? FunctionLib::outputId($_GET['id']) : 0;
		if ($id > 0 && Cronjob::deleteItem($id)) {
			$data['isIntOk'] = 1;
		}
		return Response::json($data);
	}
	private function valid($data=array()) {
		if(!empty($data)) {
			if(isset($data['cronjob_router']) && trim($data['cronjob_router']) == '') {
				$this->error[] = 'Router cronjob không được rỗng';
			}
		}
		return true;
	}
}
