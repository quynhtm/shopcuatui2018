<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\QuitJob;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\Department;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class QuitJobController extends BaseAdminController
{
    private $permission_view = 'quitJob_view';
    private $permission_full = 'quitJob_full';
    private $permission_delete = 'quitJob_delete';
    private $permission_create = 'quitJob_create';
    private $permission_edit = 'quitJob_edit';
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

    public function getQuitJob($person_ids)
    {

        CGlobal::$pageAdminTitle = 'Thiết lập buộc thôi việc nhân sự';
        $person_id = FunctionLib::outputId($person_ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($person_id > 0) {
            $data = QuitJob::getQuitJobByPersonId($person_id,Define::QUITJOB_THOI_VIEC);
        }

        $quit_job_id = 0;
        if(sizeof($data) > 0){
            $quit_job_id = $data->quit_job_id;
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);
        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.QuitJob.addJob', array_merge([
            'data' => $data,
            'infoPerson' => $infoPerson,
            'person_id' => $person_id,
            'quit_job_id' => $quit_job_id,
        ], $this->viewPermission));
    }
    public function postQuitJob($person_ids)
    {
        CGlobal::$pageAdminTitle = 'Thiết lập buộc thôi việc nhân sự';
        $person_id = FunctionLib::outputId($person_ids);
        $id_hiden = Request::get('id_hiden', '');

        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = $_POST;

        if ($this->valid($data) && empty($this->error)) {
            $passport = QuitJob::getQuitJobByPersonId($person_id,Define::QUITJOB_THOI_VIEC);
            $id = ($passport && isset($passport->quit_job_id)) ? $passport->quit_job_id : FunctionLib::outputId($id_hiden);
            $data['quit_job_date_creater'] = ($data['quit_job_date_creater'] != '')? strtotime($data['quit_job_date_creater']): '';
            if ($id > 0) {
                //cap nhat
                $data['quit_job_type'] = Define::QUITJOB_THOI_VIEC;
                if (QuitJob::updateItem($id, $data)) {
                    return Redirect::route('hr.personnelView');
                }
            } else {
                //them moi
                $data['quit_job_person_id'] = $person_id;
                $data['quit_job_type'] = Define::QUITJOB_THOI_VIEC;
                if (QuitJob::createItem($data)) {
                    return Redirect::route('hr.personnelView');
                }
            }
        }

        $quit_job_id = 0;
        if($person_id > 0){
            $dataQuitJob = Retirement::getRetirementByPersonId($person_id);
            if(sizeof($dataQuitJob) > 0){
                $quit_job_id = $dataQuitJob->quit_job_id;
            }
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);
        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.QuitJob.addJob', array_merge([
            'data' => $data,
            'person_id' => $person_id,
            'infoPerson' => $infoPerson,
            'error' => $this->error,
            'quit_job_id' => $quit_job_id,
        ], $this->viewPermission));
    }

    public function getJobMove($person_ids)
    {
        CGlobal::$pageAdminTitle = 'Thiết lập Nghỉ việc / Chuyển công tác nhân sự';
        $person_id = FunctionLib::outputId($person_ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($person_id > 0) {
            $data = QuitJob::getQuitJobByPersonId($person_id,Define::QUITJOB_CHUYEN_CONGTAC);
        }

        $quit_job_id = 0;
        if(sizeof($data) > 0){
            $quit_job_id = $data->quit_job_id;
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);
        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.QuitJob.addMove', array_merge([
            'data' => $data,
            'infoPerson' => $infoPerson,
            'person_id' => $person_id,
            'quit_job_id' => $quit_job_id,
        ], $this->viewPermission));
    }
    public function postJobMove($person_ids)
    {
        CGlobal::$pageAdminTitle = 'Thiết lập Nghỉ việc / Chuyển công tác nhân sự';
        $person_id = FunctionLib::outputId($person_ids);
        $id_hiden = Request::get('id_hiden', '');

        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = $_POST;

        if ($this->valid($data) && empty($this->error)) {
            $passport = QuitJob::getQuitJobByPersonId($person_id,Define::QUITJOB_CHUYEN_CONGTAC);
            $id = ($passport && isset($passport->quit_job_id)) ? $passport->quit_job_id : FunctionLib::outputId($id_hiden);;
            $data['quit_job_date_creater'] = ($data['quit_job_date_creater'] != '')? strtotime($data['quit_job_date_creater']): '';
            if ($id > 0) {
                //cap nhat
                $data['quit_job_type'] = Define::QUITJOB_CHUYEN_CONGTAC;
                if (QuitJob::updateItem($id, $data)) {
                    return Redirect::route('hr.personnelView');
                }
            } else {
                //them moi
                //FunctionLib::debug($data);
                $data['quit_job_person_id'] = $person_id;
                $data['quit_job_type'] = Define::QUITJOB_CHUYEN_CONGTAC;
                if (QuitJob::createItem($data)) {
                    return Redirect::route('hr.personnelView');
                }
            }
        }

        $quit_job_id = 0;
        if($person_id > 0){
            $dataQuitJob = Retirement::getRetirementByPersonId($person_id);
            if(sizeof($dataQuitJob) > 0){
                $quit_job_id = $dataQuitJob->quit_job_id;
            }
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);
        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.QuitJob.addMove', array_merge([
            'data' => $data,
            'person_id' => $person_id,
            'infoPerson' => $infoPerson,
            'quit_job_id' => $quit_job_id,
            'error' => $this->error,
        ], $this->viewPermission));
    }

    public function getJobMoveDepart($person_ids)
    {
        CGlobal::$pageAdminTitle = 'Chuyển phòng ban/ bộ phận';
        $person_id = FunctionLib::outputId($person_ids);

        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($person_id > 0) {
            $data = QuitJob::getQuitJobByPersonId($person_id, Define::QUITJOB_CHUYEN_PHONG_BAN);
        }

        $arrDepart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($arrDepart, isset($data['quit_job_depart_id']) ? $data['quit_job_depart_id'] : '');

        $quit_job_id = 0;
        if(sizeof($data) > 0){
            $quit_job_id = $data->quit_job_id;
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);
        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.QuitJob.addMoveDepart', array_merge([
            'data' => $data,
            'infoPerson' => $infoPerson,
            'optionDepart' => $optionDepart,
            'person_id' => $person_id,
            'quit_job_id' => $quit_job_id,
        ], $this->viewPermission));
    }
    public function postJobMoveDepart($person_ids)
    {
        CGlobal::$pageAdminTitle = 'Chuyển phòng ban/ bộ phận';
        $person_id = FunctionLib::outputId($person_ids);
        $id_hiden = Request::get('id_hiden', '');

        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = $_POST;

        if ($this->valid($data) && empty($this->error)) {
            $passport = QuitJob::getQuitJobByPersonId($person_id, Define::QUITJOB_CHUYEN_PHONG_BAN);
            $id = ($passport && isset($passport->quit_job_id)) ? $passport->quit_job_id : FunctionLib::outputId($id_hiden);

            if ($id > 0) {
                //cap nhat
                $data['quit_job_type'] = Define::QUITJOB_CHUYEN_PHONG_BAN;
                if (QuitJob::updateItem($id, $data)) {
                    return Redirect::route('hr.personnelView');
                }
            } else {
                //them moi
                $data['quit_job_person_id'] = $person_id;
                $data['quit_job_type'] = Define::QUITJOB_CHUYEN_PHONG_BAN;
                FunctionLib::bug($data);
                if (QuitJob::createItem($data)) {
                    return Redirect::route('hr.personnelView');
                }
            }
        }

        $quit_job_id = 0;
        if($person_id > 0){
            $dataRetirement = QuitJob::getQuitJobByPersonId($person_id, Define::QUITJOB_CHUYEN_PHONG_BAN);
            if(sizeof($dataRetirement) > 0){
                $quit_job_id = $dataRetirement->quit_job_id;
            }
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);
        $arrDepart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($arrDepart, isset($data['quit_job_depart_id']) ? $data['quit_job_depart_id'] : '');

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.QuitJob.MoveDepart', array_merge([
            'data' => $data,
            'person_id' => $person_id,
            'infoPerson' => $infoPerson,
            'optionDepart' => $optionDepart,
            'quit_job_id' => $quit_job_id,
            'error' => $this->error,
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
