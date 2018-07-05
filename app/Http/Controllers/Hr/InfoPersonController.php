<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\HrContracts;
use App\Http\Models\Hr\HrDefine;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class InfoPersonController extends BaseAdminController
{
    //contracts
    private $personContractsView = 'personContractsView';
    private $personContractsFull = 'personContractsFull';
    private $personContractsDelete = 'personContractsDelete';
    private $personContractsCreate = 'personContractsCreate';

    //tao user login
    private $permission_createrUser_view = 'personCreaterUser_view';
    private $permission_createrUser_full = 'personCreaterUser_full';
    private $permission_createrUser_delete = 'personCreaterUser_delete';
    private $permission_createrUser_create = 'personCreaterUser_create';

    private $arrStatus = array(1=>'hiển thị',2=>'Ẩn');
    private $error = array();
    private $arrMenuParent = array();
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
            'personContractsFull' => in_array($this->personContractsFull, $this->permission) ? 1 : 0,
            'personContractsView' => in_array($this->personContractsView, $this->permission) ? 1 : 0,
            'personContractsCreate' => in_array($this->personContractsCreate, $this->permission) ? 1 : 0,
            'personContractsDelete' => in_array($this->personContractsDelete, $this->permission) ? 1 : 0,

            //creater User
            'personCreaterUser_view' => in_array($this->permission_createrUser_view, $this->permission) ? 1 : 0,
            'personCreaterUser_create' => in_array($this->permission_createrUser_create, $this->permission) ? 1 : 0,
            'personCreaterUser_delete' => in_array($this->permission_createrUser_delete, $this->permission) ? 1 : 0,
            'personCreaterUser_full' => in_array($this->permission_createrUser_full, $this->permission) ? 1 : 0,
        ];
    }

    /************************************************************************************************************************************
     * Thông tin thêm của nhân sự
     ************************************************************************************************************************************/
    public function viewInfoPersonOther($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Thông tin thêm của nhân sự';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $contracts = HrContracts::getListContractsByPersonId($person_id);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.InfoPerson.editInfoPersonOtherView', array_merge([
            'contracts' => $contracts,
            'total' => count($contracts),
        ], $this->viewPermission));
    }

    /************************************************************************************************************************************
     * Thông tin hợp đồng lao động
     ************************************************************************************************************************************/
    public function viewContracts($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Thông tin hợp đồng lao động';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personContractsFull, $this->permission) && !in_array($this->personContractsView, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin hợp đồng
        $contracts = HrContracts::getListContractsByPersonId($person_id);

        $arrChedothanhtoan = HrDefine::getArrayByType(Define::che_do_thanh_toan);
        $arrLoaihopdong = HrDefine::getArrayByType(Define::loai_hop_dong);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.InfoPerson.contractsView', array_merge([
            'person_id' => $person_id,
            'contracts' => $contracts,
            'total' => count($contracts),
            'infoPerson' => $infoPerson,
            'arrChedothanhtoan' => $arrChedothanhtoan,
            'arrLoaihopdong' => $arrLoaihopdong,
        ], $this->viewPermission));
    }
    public function editContracts()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personContractsFull, $this->permission) && !in_array($this->personContractsCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('person_id', '');
        $contractsId = Request::get('contracts_id', '');

        $person_id = FunctionLib::outputId($personId);
        $contracts_id = FunctionLib::outputId($contractsId);

        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin hợp đồng
        $contracts = HrContracts::find($contracts_id);

        $arrChedothanhtoan = HrDefine::getArrayByType(Define::che_do_thanh_toan);
        $arrLoaihopdong = HrDefine::getArrayByType(Define::loai_hop_dong);
        $optionPayment = FunctionLib::getOption($arrChedothanhtoan, isset($contracts['contracts_payment_define_id']) ? $contracts['contracts_payment_define_id'] :'');
        $optionTypeContract = FunctionLib::getOption($arrLoaihopdong, isset($contracts['contracts_type_define_id']) ? $contracts['contracts_type_define_id'] : '');
        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.InfoPerson.contractsPopupAdd', [
            'contracts' => $contracts,
            'infoPerson' => $infoPerson,
            'optionPayment' => $optionPayment,
            'optionTypeContract' => $optionTypeContract,
            'person_id' => $person_id,
            'contracts_id' => $contracts_id,
        ], $this->viewPermission)->render();
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }
    public function postContracts()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personContractsFull, $this->permission) && !in_array($this->personContractsCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = Request::get('person_id', '');
        //$contracts_id = Request::get('contracts_id', '');
        $id_hiden = Request::get('id_hiden', '');
        //$person_id = FunctionLib::outputId($personId);
        $contracts_id = FunctionLib::outputId($id_hiden);
        //FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if($data['contracts_sign_day'] == '' || $data['contracts_effective_date'] == ''|| $data['contracts_dealine_date'] == ''){
            $arrData = ['intReturn' => 0, 'msg' => 'Dữ liệu nhập không đủ'];
        }else{
            if($person_id > 0){
                $dataContracts = array('contracts_code'=>$data['contracts_code'],
                    'contracts_type_define_id'=>$data['contracts_type_define_id'],
                    'contracts_payment_define_id'=>$data['contracts_payment_define_id'],
                    'contracts_money'=>$data['contracts_money'],
                    'contracts_describe'=>$data['contracts_describe'],
                    'contracts_sign_day'=>($data['contracts_sign_day'] != '')? strtotime($data['contracts_sign_day']):'',
                    'contracts_effective_date'=>($data['contracts_effective_date'] != '')? strtotime($data['contracts_effective_date']):'',
                    'contracts_dealine_date'=>($data['contracts_dealine_date'] != '')? strtotime($data['contracts_dealine_date']):'',
                    'contracts_person_id'=>$person_id,
                );
                if($contracts_id > 0){
                    $dataContracts['contracts_update_user_id'] = $this->user_id;
                    $dataContracts['contracts_update_user_name'] = $this->user_name;
                    $dataContracts['contracts_update_time'] = time();
                    HrContracts::updateItem($contracts_id,$dataContracts);
                }else{
                    $dataContracts['contracts_creater_user_id'] = $this->user_id;
                    $dataContracts['contracts_creater_user_name'] = $this->user_name;
                    $dataContracts['contracts_creater_time'] = time();
                    HrContracts::createItem($dataContracts);
                }
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
                //thông tin hợp đồng
                $contracts = HrContracts::getListContractsByPersonId($person_id);
                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();

                $arrChedothanhtoan = HrDefine::getArrayByType(Define::che_do_thanh_toan);
                $arrLoaihopdong = HrDefine::getArrayByType(Define::loai_hop_dong);
                $html = view('hr.InfoPerson.contractsList', array_merge([
                    'arrChedothanhtoan' => $arrChedothanhtoan,
                    'arrLoaihopdong' => $arrLoaihopdong,
                    'person_id' => $person_id,
                    'contracts' => $contracts,
                    'total' => count($contracts)
                ], $this->viewPermission))->render();
                $arrData['html'] = $html;
            }else{
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật'.$person_id];
            }
        }
        return response()->json($arrData);
    }
    public function deleteContracts()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->personContractsFull, $this->permission) && !in_array($this->personContractsDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('person_id', '');
        $contractsId = Request::get('contracts_id', '');
        $person_id = FunctionLib::outputId($personId);
        $contracts_id = FunctionLib::outputId($contractsId);
        if ($contracts_id > 0 && HrContracts::deleteItem($contracts_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
            //thông tin hợp đồng
            $contracts = HrContracts::getListContractsByPersonId($person_id);
            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();

            $arrChedothanhtoan = HrDefine::getArrayByType(Define::che_do_thanh_toan);
            $arrLoaihopdong = HrDefine::getArrayByType(Define::loai_hop_dong);
            $html = view('hr.InfoPerson.contractsList', array_merge([
                'arrChedothanhtoan' => $arrChedothanhtoan,
                'arrLoaihopdong' => $arrLoaihopdong,
                'person_id' => $person_id,
                'contracts' => $contracts,
                'total' => count($contracts)
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }


    /************************************************************************************************************************************
     * Tạo tài khoản sử dụng hệ thống
     ************************************************************************************************************************************/
    public function getInfoPerson($personId)
    {
        CGlobal::$pageAdminTitle = 'Tạo tài khoản sử dụng hệ thống';
        $id = FunctionLib::outputId($personId);
        if (!$this->is_root && !in_array($this->permission_createrUser_full, $this->permission) && !in_array($this->permission_createrUser_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($id > 0) {
            $data = Person::find($id);
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active']) ? $data['active'] : CGlobal::status_show);
        $optionShowContent = FunctionLib::getOption($this->arrStatus, isset($data['showcontent']) ? $data['showcontent'] : CGlobal::status_show);
        $optionShowPermission = FunctionLib::getOption($this->arrStatus, isset($data['show_permission']) ? $data['show_permission'] : CGlobal::status_hide);
        $optionShowMenu = FunctionLib::getOption($this->arrStatus, isset($data['show_menu']) ? $data['show_menu'] : CGlobal::status_show);
        $optionMenuParent = FunctionLib::getOption($this->arrMenuParent, isset($data['parent_id']) ? $data['parent_id'] : 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.InfoPerson.addUserToPerson', array_merge([
            'data' => $data,
            'id' => $id,
            'arrStatus' => $this->arrStatus,
            'optionStatus' => $optionStatus,
            'optionShowContent' => $optionShowContent,
            'optionShowPermission' => $optionShowPermission,
            'optionShowMenu' => $optionShowMenu,
            'optionRoleType' => $optionShowMenu,
            'optionSex' => $optionShowMenu,
            'optionMenuParent' => $optionMenuParent,
        ], $this->viewPermission));
    }

    public function postInfoPerson($personId)
    {
        CGlobal::$pageAdminTitle = 'Tạo tài khoản sử dụng hệ thống';
        $id = FunctionLib::outputId($personId);
        if (!$this->is_root && !in_array($this->permission_createrUser_full, $this->permission) && !in_array($this->permission_createrUser_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        //$data['ordering'] = (int)($data['ordering']);
        if ($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                //cap nhat
                if (Person::updateItem($id, $data)) {
                    return Redirect::route('hr.personnelView');
                }
            } else {
                //them moi
                if (Person::createItem($data)) {
                    return Redirect::route('hr.personnelView');
                }
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active']) ? $data['active'] : CGlobal::status_hide);
        $optionShowContent = FunctionLib::getOption($this->arrStatus, isset($data['showcontent']) ? $data['showcontent'] : CGlobal::status_show);
        $optionShowMenu = FunctionLib::getOption($this->arrStatus, isset($data['show_menu']) ? $data['show_menu'] : CGlobal::status_show);
        $optionShowPermission = FunctionLib::getOption($this->arrStatus, isset($data['show_permission']) ? $data['show_permission'] : CGlobal::status_hide);
        $optionMenuParent = FunctionLib::getOption($this->arrMenuParent, isset($data['parent_id']) ? $data['parent_id'] : 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.InfoPerson.addUserToPerson', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
            'arrStatus' => $this->arrStatus,
            'optionStatus' => $optionStatus,
            'optionShowContent' => $optionShowContent,
            'optionShowPermission' => $optionShowPermission,
            'optionShowMenu' => $optionShowMenu,
            'optionMenuParent' => $optionMenuParent,
        ], $this->viewPermission));
    }

}
