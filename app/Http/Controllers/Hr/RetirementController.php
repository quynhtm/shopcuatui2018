<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Retirement;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\HrDefine;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class RetirementController extends BaseAdminController
{
    private $permission_view = 'retirement_view';
    private $permission_full = 'retirement_full';
    private $permission_delete = 'retirement_delete';
    private $permission_create = 'retirement_create';
    private $permission_edit = 'retirement_edit';
    private $arrStatus = array();
    private $error = array();
    private $arrMenuParent = array();
    private $viewPermission = array();//check quyen

    public function __construct()
    {
        parent::__construct();
        $this->arrMenuParent = array();

    }

    public function getDataDefault()
    {
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden', $this->languageSite));
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_root' => $this->is_root ? 1 : 0,
            'permission_edit' => in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create' => in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_delete' => in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full' => in_array($this->permission_full, $this->permission) ? 1 : 0,
        ];
    }

    public function getItem($person_ids){

        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        CGlobal::$pageAdminTitle = 'Thiết lập ngày nghỉ hưu';
        $person_id = FunctionLib::outputId($person_ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($person_id > 0) {
            $data = Retirement::getRetirementByPersonId($person_id);
        }

        $retirement_id = 0;
        if(sizeof($data) > 0){
            $retirement_id = $data->retirement_id;
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);
        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Retirement.add', array_merge([
            'data' => $data,
            'infoPerson' => $infoPerson,
            'person_id' => $person_id,
            'retirement_id' => $retirement_id,
        ], $this->viewPermission));
    }
    public function postItem($person_ids)
    {
        CGlobal::$pageAdminTitle = 'Thiết lập ngày nghỉ hưu';
        $person_id = FunctionLib::outputId($person_ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = $_POST;

        if ($this->valid($data) && empty($this->error)) {
            $passport = Retirement::getRetirementByPersonId($person_id);
            $id = ($passport && isset($passport->retirement_id)) ? $passport->retirement_id : 0;
            $data['retirement_date_creater'] = (isset($data['retirement_date_creater']) && $data['retirement_date_creater'] != '')? strtotime($data['retirement_date_creater']): '';
            $data['retirement_date_notification'] = (isset($data['retirement_date_notification']) && $data['retirement_date_notification'] != '')? strtotime($data['retirement_date_notification']): '';
            $data['retirement_date'] = (isset($data['retirement_date']) && $data['retirement_date'] != '')? strtotime($data['retirement_date']): '';
            if ($id > 0) {
                //cap nhat
                if (Retirement::updateItem($id, $data)) {
                    return Redirect::route('hr.personnelView');
                }
            } else {
                //them moi
                //FunctionLib::debug($data);
                $data['retirement_person_id'] = $person_id;
                if (Retirement::createItem($data)) {
                    return Redirect::route('hr.personnelView');
                }
            }
        }

        $retirement_id = 0;
        if($person_id > 0){
            $dataRetirement = Retirement::getRetirementByPersonId($person_id);
            if(sizeof($dataRetirement) > 0){
                $retirement_id = $dataRetirement->retirement_id;
            }
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);
        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Retirement.add', array_merge([
            'data' => $data,
            'person_id' => $person_id,
            'infoPerson' => $infoPerson,
            'retirement_id' => $retirement_id,
            'error' => $this->error,
        ], $this->viewPermission));
    }

    public function getItemTime($person_ids)
    {
        CGlobal::$pageAdminTitle = 'Kéo dài thời gian nghỉ hưu';
        $person_id = FunctionLib::outputId($person_ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($person_id > 0) {
            $data = Retirement::getRetirementByPersonId($person_id);
        }

        $retirement_id = 0;
        if(sizeof($data) > 0){
            $retirement_id = $data->retirement_id;
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //chức vụ đảm nhiêm
        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $optionChucVu = FunctionLib::getOption($arrChucVu, isset($data['retirement_position_define_id']) ? $data['retirement_position_define_id'] : '');

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Retirement.addTime', array_merge([
            'data' => $data,
            'infoPerson' => $infoPerson,
            'optionChucVu' => $optionChucVu,
            'person_id' => $person_id,
            'retirement_id' => $retirement_id,
        ], $this->viewPermission));
    }
    public function postItemTime($person_ids)
    {
        CGlobal::$pageAdminTitle = 'Kéo dài thời gian nghỉ hưu';
        $person_id = FunctionLib::outputId($person_ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = $_POST;

        if ($this->valid($data) && empty($this->error)) {
            $passport = Retirement::getRetirementByPersonId($person_id);
            $id = ($passport && isset($passport->retirement_id)) ? $passport->retirement_id : 0;
            $data['retirement_date'] = (isset($data['retirement_date']) && $data['retirement_date'] != '')? strtotime($data['retirement_date']): '';
            if ($id > 0) {
                //cap nhat
                if (Retirement::updateItem($id, $data)) {
                    return Redirect::route('hr.personnelView');
                }
            } else {
                //them moi
                //FunctionLib::debug($data);
                $data['retirement_person_id'] = $person_id;
                if (Retirement::createItem($data)) {
                    return Redirect::route('hr.personnelView');
                }
            }
        }

        $retirement_id = 0;
        if($person_id > 0){
            $dataRetirement = Retirement::getRetirementByPersonId($person_id);
            if(sizeof($dataRetirement) > 0){
                $retirement_id = $dataRetirement->retirement_id;
            }
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);
        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();

        //chức vụ đảm nhiêm
        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $optionChucVu = FunctionLib::getOption($arrChucVu, isset($data['retirement_position_define_id']) ? $data['retirement_position_define_id'] : '');

        return view('hr.Retirement.addTime', array_merge([
            'data' => $data,
            'person_id' => $person_id,
            'infoPerson' => $infoPerson,
            'optionChucVu' => $optionChucVu,
            'error' => $this->error,
            'retirement_id' => $retirement_id,
        ], $this->viewPermission));
    }

    private function valid($data = array())
    {
        if (!empty($data)) {
            if (isset($data['banner_name']) && trim($data['banner_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
