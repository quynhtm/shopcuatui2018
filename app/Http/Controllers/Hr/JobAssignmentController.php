<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\Bonus;
use App\Http\Models\Hr\JobAssignment;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class JobAssignmentController extends BaseAdminController
{
    //contracts
    private $jobAssignmentView = 'jobAssignmentView';
    private $jobAssignmentFull = 'jobAssignmentFull';
    private $jobAssignmentDelete = 'jobAssignmentDelete';
    private $jobAssignmentCreate = 'jobAssignmentCreate';

    private $arrStatus = array(1 => 'hiển thị', 2 => 'Ẩn');
    private $viewPermission = array();//check quyen

    public function __construct()
    {
        parent::__construct();

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
            //contracts
            'jobAssignmentFull' => in_array($this->jobAssignmentFull, $this->permission) ? 1 : 0,
            'jobAssignmentView' => in_array($this->jobAssignmentView, $this->permission) ? 1 : 0,
            'jobAssignmentCreate' => in_array($this->jobAssignmentCreate, $this->permission) ? 1 : 0,
            'jobAssignmentDelete' => in_array($this->jobAssignmentDelete, $this->permission) ? 1 : 0,
        ];
    }


    /************************************************************************************************************************************
     * Thông tin khen thưởng, danh hiệu, kỷ luật
     ************************************************************************************************************************************/
    public function viewJobAssignment($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Bổ nhiệm/Bổ nhiệm lại chức vụ';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->jobAssignmentFull, $this->permission) && !in_array($this->jobAssignmentView, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin list
        $jobAssignment = JobAssignment::getJobAssignmentByPersonId($person_id);

        //chức vụ đảm nhiêm
        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.JobAssignment.JobAssignmentView', array_merge([
            'person_id' => $person_id,
            'jobAssignment' => $jobAssignment,
            'arrChucVu' => $arrChucVu,
            'infoPerson' => $infoPerson,
        ], $this->viewPermission));
    }

    public function editJobAssignment()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->jobAssignmentFull, $this->permission) && !in_array($this->jobAssignmentCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $str_object_id = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');

        $person_id = FunctionLib::outputId($personId);
        $job_assignment_id = FunctionLib::outputId($str_object_id);

        $arrData = ['intReturn' => 0, 'msg' => ''];
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin chung
        $job_assignment = JobAssignment::find($job_assignment_id);

        //chức vụ đảm nhiêm
        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $optionChucVuNew = FunctionLib::getOption($arrChucVu, isset($job_assignment['job_assignment_define_id_new']) ? $job_assignment['job_assignment_define_id_new'] : '');
        $optionChucVuOld = FunctionLib::getOption($arrChucVu, isset($job_assignment['job_assignment_define_id_old']) ? $job_assignment['job_assignment_define_id_old'] : '');

        $template = ($typeAction == Define::JOBASSIGNMENT_THONG_BAO) ? 'thongBaoBoNhiemPopupAdd' : 'chucVuDaBoNhiemPopupAdd';
        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.JobAssignment.' . $template, [
            'job_assignment' => $job_assignment,
            'infoPerson' => $infoPerson,
            'person_id' => $person_id,
            'optionChucVuNew' => $optionChucVuNew,
            'optionChucVuOld' => $optionChucVuOld,
            'job_assignment_id' => $job_assignment_id,
            'typeAction' => $typeAction,
        ], $this->viewPermission)->render();
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }

    public function postJobAssignment()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->jobAssignmentFull, $this->permission) && !in_array($this->jobAssignmentCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = Request::get('person_id', '');
        $job_assignment_id = Request::get('job_assignment_id', '');
        $id_hiden = Request::get('id_hiden', '');

        //FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if ($data['job_assignment_define_id_new'] == '' || $data['job_assignment_define_id_old'] == '') {
            $arrData = ['intReturn' => 0, 'msg' => 'Dữ liệu nhập không đủ'];
        } else {
            if ($person_id > 0) {
                $dataInput = array(
                    'job_assignment_person_id' => $person_id,
                    'job_assignment_define_id_new' => $data['job_assignment_define_id_new'],
                    'job_assignment_define_id_old' => $data['job_assignment_define_id_old'],
                    'job_assignment_date_start' => (isset($data['job_assignment_date_start']) && $data['job_assignment_date_start'] != '') ? strtotime($data['job_assignment_date_start']) : '',
                    'job_assignment_date_end' => (isset($data['job_assignment_date_end']) && $data['job_assignment_date_end'] != '') ? strtotime($data['job_assignment_date_end']) : '',
                    'job_assignment_note' => $data['job_assignment_note'],
                    'job_assignment_status' => ($data['typeAction'] == Define::JOBASSIGNMENT_THONG_BAO) ? 0 : 1,
                );

                if (isset($data['job_assignment_code']) && $data['job_assignment_code'] != '') {
                    $dataInput['job_assignment_code'] = $data['job_assignment_code'];
                }
                if (isset($data['job_assignment_date_creater']) && $data['job_assignment_date_creater'] != '') {
                    $dataInput['job_assignment_date_creater'] = strtotime($data['job_assignment_date_creater']);
                } else {
                    $dataInput['job_assignment_date_creater'] = (isset($data['job_assignment_date_start']) && $data['job_assignment_date_start'] != '') ? strtotime($data['job_assignment_date_start']) : '';
                }
                $job_assignment_id = ($job_assignment_id > 0) ? $job_assignment_id : FunctionLib::outputId($id_hiden);

                if ($job_assignment_id > 0) {
                    JobAssignment::updateItem($job_assignment_id, $dataInput);
                } else {
                    JobAssignment::createItem($dataInput);
                }
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
                //thông tin list
                $dataList = JobAssignment::getJobAssignmentByPersonId($person_id);
                $template = 'list';
                //chức vụ đảm nhiêm
                $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);

                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();
                $html = view('hr.JobAssignment.' . $template, array_merge([
                    'person_id' => $person_id,
                    'jobAssignment' => $dataList,
                    'arrChucVu' => $arrChucVu,
                ], $this->viewPermission))->render();
                $arrData['html'] = $html;
            } else {
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật' . $person_id];
            }
        }
        return response()->json($arrData);
    }

    public function deleteJobAssignment()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->jobAssignmentFull, $this->permission) && !in_array($this->jobAssignmentDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $bonusId = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');
        $person_id = FunctionLib::outputId($personId);
        $job_assignment_id = FunctionLib::outputId($bonusId);
        if ($job_assignment_id > 0 && JobAssignment::deleteItem($job_assignment_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
            //thông tin list
            $dataList = JobAssignment::getJobAssignmentByPersonId($person_id);
            $template = 'list';
            //chức vụ đảm nhiêm
            $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();
            $html = view('hr.JobAssignment.' . $template, array_merge([
                'person_id' => $person_id,
                'jobAssignment' => $dataList,
                'arrChucVu' => $arrChucVu,
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }

    public function updateStatus()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->jobAssignmentFull, $this->permission) && !in_array($this->jobAssignmentCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $status = Request::get('status', Define::STATUS_HIDE);
        $str_object_id = Request::get('str_object_id', '');
        $job_assignment_id = FunctionLib::outputId($str_object_id);
        if ($job_assignment_id > 0) {
            $dataUpdate['job_assignment_status'] = ($status == Define::STATUS_HIDE) ? Define::STATUS_SHOW : Define::STATUS_HIDE;
            if (JobAssignment::updateItem($job_assignment_id, $dataUpdate)) {
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
            } else {
                $arrData = ['intReturn' => 0, 'msg' => 'Cập nhật thất bại'];
            }
        }
        return Response::json($arrData);
    }
}
