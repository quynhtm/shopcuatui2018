<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\Bonus;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class BonusPersonController extends BaseAdminController
{
    //contracts
    private $personBonusView = 'personBonusView';
    private $personBonusFull = 'personBonusFull';
    private $personBonusDelete = 'personBonusDelete';
    private $personBonusCreate = 'personBonusCreate';

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
            'personBonusFull' => in_array($this->personBonusFull, $this->permission) ? 1 : 0,
            'personBonusView' => in_array($this->personBonusView, $this->permission) ? 1 : 0,
            'personBonusCreate' => in_array($this->personBonusCreate, $this->permission) ? 1 : 0,
            'personBonusDelete' => in_array($this->personBonusDelete, $this->permission) ? 1 : 0,
        ];
    }


    /************************************************************************************************************************************
     * Thông tin khen thưởng, danh hiệu, kỷ luật
     ************************************************************************************************************************************/
    public function viewBonus($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Thông tin khen thưởng';

        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personBonusFull, $this->permission) && !in_array($this->personBonusView, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin khen thưởng
        $khenthuong = Bonus::getBonusByType($person_id, Define::BONUS_KHEN_THUONG);
        $arrTypeKhenthuong = HrDefine::getArrayByType(Define::khen_thuong);
        //thông tin danh hieu
        $danhhieu = Bonus::getBonusByType($person_id, Define::BONUS_DANH_HIEU);
        $arrTypeDanhhieu = HrDefine::getArrayByType(Define::danh_hieu);

        //thông tin kỷ luật
        $kyluat = Bonus::getBonusByType($person_id, Define::BONUS_KY_LUAT);
        $arrTypeKyluat = HrDefine::getArrayByType(Define::ky_luat);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.BonusPerson.BonusView', array_merge([
            'person_id' => $person_id,
            'khenthuong' => $khenthuong,
            'danhhieu' => $danhhieu,
            'kyluat' => $kyluat,
            'arrTypeKhenthuong' => $arrTypeKhenthuong,
            'arrTypeDanhhieu' => $arrTypeDanhhieu,
            'arrTypeKyluat' => $arrTypeKyluat,
            'infoPerson' => $infoPerson,
        ], $this->viewPermission));
    }

    public function editBonus()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personBonusFull, $this->permission) && !in_array($this->personBonusCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $bonusId = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');

        $person_id = FunctionLib::outputId($personId);
        $bonus_id = FunctionLib::outputId($bonusId);

        $data = array();
        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin chung
        $bonus = Bonus::find($bonus_id);
        //FunctionLib::debug($contracts);

        $arrType = array();
        if ($typeAction == Define::BONUS_KHEN_THUONG) {
            $template = 'khenThuongPopupAdd';
            $arrType = HrDefine::getArrayByType(Define::khen_thuong);
        } elseif ($typeAction == Define::BONUS_DANH_HIEU) {
            $template = 'danhHieuPopupAdd';
            $arrType = HrDefine::getArrayByType(Define::danh_hieu);
        } else {
            $template = 'kyLuatPopupAdd';
            $arrType = HrDefine::getArrayByType(Define::ky_luat);
        }

        $arrYears = FunctionLib::getListYears();
        $optionYears = FunctionLib::getOption($arrYears, isset($bonus['bonus_year']) ? $bonus['bonus_year'] : (int)date('Y', time()));
        $optionType = FunctionLib::getOption($arrType, isset($bonus['bonus_type']) ? $bonus['bonus_type'] : '');

        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.BonusPerson.' . $template, [
            'bonus' => $bonus,
            'infoPerson' => $infoPerson,
            'optionType' => $optionType,
            'optionYears' => $optionYears,
            'person_id' => $person_id,
            'bonus_id' => $bonus_id,
            'typeAction' => $typeAction,
        ], $this->viewPermission)->render();
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }

    public function postBonus()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personBonusFull, $this->permission) && !in_array($this->personBonusCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = Request::get('person_id', '');
        $bonus_id = Request::get('bonus_id', '');
        $id_hiden = Request::get('id_hiden', '');
        //FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if ($data['bonus_decision'] == '' || $data['bonus_note'] == '') {
            $arrData = ['intReturn' => 0, 'msg' => 'Dữ liệu nhập không đủ'];
        } else {
            if ($person_id > 0) {
                $dataBonus = array(
                    'bonus_define_id' => $data['bonus_define_id'],
                    'bonus_year' => $data['bonus_year'],
                    'bonus_decision' => $data['bonus_decision'],
                    'bonus_number' => isset($data['bonus_number']) ?(int)$data['bonus_number'] : 0,
                    'bonus_note' => $data['bonus_note'],
                    'bonus_type' => $data['bonus_type'],
                    'bonus_person_id' => $person_id,
                );
                $bonus_id = ($bonus_id > 0) ? $bonus_id : FunctionLib::outputId($id_hiden);;
                if ($bonus_id > 0) {
                    $dataBonus['bonus_update_user_id'] = $this->user_id;
                    $dataBonus['bonus_update_user_name'] = $this->user_name;
                    $dataBonus['bonus_update_time'] = time();
                    Bonus::updateItem($bonus_id, $dataBonus);
                } else {
                    $dataBonus['bonus_creater_user_id'] = $this->user_id;
                    $dataBonus['bonus_creater_user_name'] = $this->user_name;
                    $dataBonus['bonus_creater_time'] = time();
                    Bonus::createItem($dataBonus);
                }
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];

                //thông tin view list\
                $dataList = array();
                if ($data['bonus_type'] == Define::BONUS_KHEN_THUONG) {
                    $dataList = Bonus::getBonusByType($person_id, Define::BONUS_KHEN_THUONG);
                } elseif ($data['bonus_type'] == Define::BONUS_DANH_HIEU) {
                    $dataList = Bonus::getBonusByType($person_id, Define::BONUS_DANH_HIEU);
                } else {
                    $dataList = Bonus::getBonusByType($person_id, Define::BONUS_KY_LUAT);
                }

                //thông tin template
                $arrType = array();
                if ($data['bonus_type'] == Define::BONUS_KHEN_THUONG) {
                    $template = 'khenThuongList';
                    $nameTem = 'khen thưởng';
                    $arrType = HrDefine::getArrayByType(Define::khen_thuong);
                } elseif ($data['bonus_type'] == Define::BONUS_DANH_HIEU) {
                    $template = 'danhHieuList';
                    $nameTem = 'danh hiệu';
                    $arrType = HrDefine::getArrayByType(Define::danh_hieu);
                } else {
                    $template = 'kyLuatList';
                    $nameTem = 'kỷ luật';
                    $arrType = HrDefine::getArrayByType(Define::ky_luat);
                }

                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();
                $html = view('hr.BonusPerson.' . $template, array_merge([
                    'person_id' => $person_id,
                    'dataList' => $dataList,
                    'total' => count($dataList),
                    'nameTem' => $nameTem,
                    'arrType' => $arrType,
                ], $this->viewPermission))->render();
                $arrData['html'] = $html;
            } else {
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật' . $person_id];
            }
        }
        return response()->json($arrData);
    }

    public function deleteBonus()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->personBonusFull, $this->permission) && !in_array($this->personBonusDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $bonusId = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');
        $person_id = FunctionLib::outputId($personId);
        $bonus_id = FunctionLib::outputId($bonusId);
        if ($bonus_id > 0 && Bonus::deleteItem($bonus_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
            //thông tin view list\
            $dataList = array();
            if ($typeAction == Define::BONUS_KHEN_THUONG) {
                $dataList = Bonus::getBonusByType($person_id, Define::BONUS_KHEN_THUONG);
            } elseif ($typeAction == Define::BONUS_DANH_HIEU) {
                $dataList = Bonus::getBonusByType($person_id, Define::BONUS_DANH_HIEU);
            } else {
                $dataList = Bonus::getBonusByType($person_id, Define::BONUS_KY_LUAT);
            }

            //thông tin template
            $arrType = array();
            if ($typeAction == Define::BONUS_KHEN_THUONG) {
                $template = 'khenThuongList';
                $nameTem = 'khen thưởng';
                $arrType = HrDefine::getArrayByType(Define::khen_thuong);
            } elseif ($typeAction == Define::BONUS_DANH_HIEU) {
                $template = 'danhHieuList';
                $nameTem = 'danh hiệu';
                $arrType = HrDefine::getArrayByType(Define::danh_hieu);
            } else {
                $template = 'kyLuatList';
                $nameTem = 'kỷ luật';
                $arrType = HrDefine::getArrayByType(Define::ky_luat);
            }
            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();
            $html = view('hr.BonusPerson.' . $template, array_merge([
                'person_id' => $person_id,
                'dataList' => $dataList,
                'total' => count($dataList),
                'nameTem' => $nameTem,
                'arrType' => $arrType,
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }
}
