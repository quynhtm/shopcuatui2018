<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 02/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\User;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\HrDocument;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class HrStaffController extends BaseAdminController{
    private $permission_view = 'hr_staff_view';
    private $permission_full = 'hr_staff_full';
    private $permission_delete = 'hr_staff_delete';
    private $permission_create = 'hr_staff_create';
    private $permission_edit = 'hr_staff_edit';
    private $arrStatus = array();
    private $error = array();
    private $arrPersion = array();
    private $viewPermission = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý lương';
    }
    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite)
        );
        $this->arrPromulgate = array(-1 => '-- Chọn --') + HrDefine::getArrayByType(Define::co_quan_ban_hanh);
        $this->arrType = array(-1 => '-- Chọn --') + HrDefine::getArrayByType(Define::loai_van_ban);
        $this->arrField = array(-1 => '-- Chọn --') + HrDefine::getArrayByType(Define::linh_vuc);
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

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        return view('hr.Staff.view',array_merge([

        ],$this->viewPermission));
    }
}
