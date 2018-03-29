<?php
/*
* @Created by: HSS
* @Author    : nguyenduypt86@gmail.com
* @Date      : 08/2016
* @Version   : 1.0
*/
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Districts;
use App\Http\Models\Admin\Province;
use App\Http\Models\Admin\Wards;

use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class AdminDistrictsProvince extends BaseAdminController{
    private $permission_view = 'DistrictsProvinceView';
    private $permission_create = 'DistrictsProvinceCreate';
    private $permission_edit = 'DistrictsProvinceEdit';
    private $permission_remove = 'DistrictsProvinceDelete';
    private $arrStatus = array();
    private $arrRoleType = array();
    private $arrSex = array();
    private $error = array();

    public function __construct(){
        parent::__construct();
    }

    public function getDataDefault(){
        $this->arrRoleType = Role::getOptionRole();
        $this->arrStatus = array(
            CGlobal::status_hide => FunctionLib::controLanguage('status_all',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_block => FunctionLib::controLanguage('status_block',$this->languageSite));
    }

    //ajax get option tỉnh thành, quận huyện hoặc phường xã
    public function ajaxGetOption(){
        $object_id = Request::get('object_id',0);
        $type = Request::get('type',1);
        $option = '';
        switch ($type){
            case 1:// quận huyển theo tỉnh thành
                $arrData = Districts::getDistrictByProvinceId($object_id);
                $option = FunctionLib::getOption($arrData,0);
                break;
            case 2: // xã phường theo quân huyện
                $arrData = Wards::getWardsByDistrictId($object_id);
                $option = FunctionLib::getOption($arrData,0);
                break;
        }
        $arrData['optionSelect'] = $option;
        $arrData['isIntOk'] = 1;
        return response()->json( $arrData );
    }

}