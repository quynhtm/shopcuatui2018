<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Passport;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class PassportController extends BaseAdminController
{
    private $permission_view = 'passport_view';
    private $permission_full = 'passport_full';
    private $permission_delete = 'passport_delete';
    private $permission_create = 'passport_create';
    private $permission_edit = 'passport_edit';
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

    public function getItem($person_ids)
    {
        CGlobal::$pageAdminTitle = 'Thông tin hộ chiếu - mã số thuế';
        $person_id = FunctionLib::outputId($person_ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($person_id > 0) {
            $data = Passport::getPassportByPersonId($person_id);
        }

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Passport.add', array_merge([
            'data' => $data,
            'person_id' => $person_id,
        ], $this->viewPermission));
    }

    public function postItem($person_ids)
    {
        CGlobal::$pageAdminTitle = 'Thông tin hộ chiếu - mã số thuế';
        $person_id = FunctionLib::outputId($person_ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = $_POST;

        if ($this->valid($data) && empty($this->error)) {
            $data['passport_common_date_range'] = ($data['passport_common_date_range'] != '')? strtotime($data['passport_common_date_range']): '';
            $data['passport_common_date_expiration'] = ($data['passport_common_date_expiration'] != '')? strtotime($data['passport_common_date_expiration']): '';
            $data['passport_equitment_date_range'] = ($data['passport_equitment_date_range'] != '')? strtotime($data['passport_equitment_date_range']): '';
            $data['passport_equitment_date_expiration'] = ($data['passport_equitment_date_expiration'] != '')? strtotime($data['passport_equitment_date_expiration']): '';

            $passport = Passport::getPassportByPersonId($person_id);
            $id = ($passport && isset($passport->passport_id)) ? $passport->passport_id : 0;
            if ($id > 0) {
                //cap nhat
                if (Passport::updateItem($id, $data)) {
                    return Redirect::route('hr.personnelView');
                }
            } else {
                //them moi
                //FunctionLib::debug($data);
                $data['passport_person_id'] = $person_id;
                if (Passport::createItem($data)) {
                    return Redirect::route('hr.personnelView');
                }
            }
        }

        $this->getDataDefault();

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Passport.add', array_merge([
            'data' => $data,
            'person_id' => $person_id,
            'error' => $this->error,
        ], $this->viewPermission));
    }

    public function deleteMenu()
    {
        $data = array('isIntOk' => 0);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_delete, $this->permission)) {
            return Response::json($data);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && Passport::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
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
