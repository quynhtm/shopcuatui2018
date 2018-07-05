<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\Device;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\Person;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;
use App\Library\AdminFunction\Upload;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;

class DeviceController extends BaseAdminController{
    private $permission_view = 'device_view';
    private $permission_full = 'device_full';
    private $permission_delete = 'device_delete';
    private $permission_create = 'device_create';
    private $permission_edit = 'device_edit';
    private $permission_export = 'device_export';
    private $arrStatus = array();
    private $error = array();
    private $arrDeviceType = array();
    private $arrDepartment = array();
    private $arrPersion = array();
    private $viewPermission = array();

    private $arrDepartmentType = array();
    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý tài sản';
    }
    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite)
        );
        $this->arrDeviceType = HrDefine::getArrayByType(Define::loai_thiet_bi);

        $totalPerson = 0;
        $dataSearchPerson['person_status'] = CGlobal::status_show;
        $dataSearchPerson['field_get'] = 'person_id,person_name';
        $listPerton = Person::searchByCondition($dataSearchPerson, 0, 0, $totalPerson);
        $arrPersion = array();
        if(sizeof($listPerton) > 0){
            foreach($listPerton as $persion){
                $arrPersion[$persion->person_id] = $persion->person_name;
            }
        }
        $this->arrPersion = array('--Chọn--') + $arrPersion;
    }
    public function getPermissionPage(){
        return $this->viewPermission = [
            'is_root'=> $this->is_root ? 1:0,
            'permission_edit'=>in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create'=>in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_remove'=>in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full'=>in_array($this->permission_full, $this->permission) ? 1 : 0,
            'permission_export' => in_array($this->permission_export, $this->permission) ? 1 : 0,
        ];
    }
    public function view(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['device_name'] = addslashes(Request::get('device_name',''));
        $dataSearch['device_type'] = addslashes(Request::get('device_type', -1));
        $dataSearch['device_depart_id'] = ($this->is_root) ? (int)Request::get('device_depart_id', 0) : $this->user_depart_id;
        $dataSearch['device_status'] = (int)Request::get('device_status', -2);
        $dataSearch['device_person_id'] = (int)Request::get('device_person_id', -1);// ca hai: chua su dung va da su dung
        $dataSearch['field_get'] = '';

        $data = Device::searchByCondition($dataSearch, $limit, $offset,$total);
        //FunctionLib::debug($data);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        //Get data department
        $totalCat = 0;
        $strDeparment = '';
        $dataSearchCatDepartment['department_status'] = -1;
        $dataDepartmentCateSearch = Department::searchByCondition($dataSearchCatDepartment, 2000, 0, $totalCat);
        $this->showCategoriesOption($dataDepartmentCateSearch, 0, '', $strDeparment, $dataSearch['device_depart_id']);

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['device_status']);
        $optionDeviceType = FunctionLib::getOption($this->arrDeviceType, isset($data['device_type'])? $data['device_type']: CGlobal::status_show);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.view',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'arrDeviceType'=>$this->arrDeviceType,
            'optionDeviceType'=>$optionDeviceType,
            'arrPersion'=>$this->arrPersion,
            'optionDepartment'=>$strDeparment,
        ],$this->viewPermission));
    }
    public function viewDeviceUse(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['device_name'] = addslashes(Request::get('device_name',''));
        $dataSearch['device_type'] = addslashes(Request::get('device_type', -1));
        $dataSearch['device_depart_id'] = ($this->is_root) ? (int)Request::get('device_depart_id', 0) : $this->user_depart_id;
        $dataSearch['device_status'] = (int)Request::get('device_status', CGlobal::status_show);
        $dataSearch['device_person_id'] = (int)Request::get('device_person_id', 1);// true: da su dung

        $dataSearch['field_get'] = '';

        $data = Device::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        //Get data department
        $totalCat = 0;
        $strDeparment = '';
        $dataSearchCatDepartment['department_status'] = -1;
        $dataDepartmentCateSearch = Department::searchByCondition($dataSearchCatDepartment, 2000, 0, $totalCat);
        $this->showCategoriesOption($dataDepartmentCateSearch, 0, '', $strDeparment, $dataSearch['device_depart_id']);


        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['device_status']);
        $optionDeviceType = FunctionLib::getOption($this->arrDeviceType, isset($data['device_type'])? $data['device_type']: CGlobal::status_show);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.view',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'arrDeviceType'=>$this->arrDeviceType,
            'optionDeviceType'=>$optionDeviceType,
            'arrPersion'=>$this->arrPersion,
            'optionDepartment'=>$strDeparment,
        ],$this->viewPermission));
    }
    public function viewDeviceNotUse(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['device_name'] = addslashes(Request::get('device_name',''));
        $dataSearch['device_type'] = addslashes(Request::get('device_type', -1));
        $dataSearch['device_depart_id'] = ($this->is_root) ? (int)Request::get('device_depart_id', 0) : $this->user_depart_id;
        $dataSearch['device_status'] = (int)Request::get('device_status', CGlobal::status_show);
        $dataSearch['device_person_id'] = (int)Request::get('device_person_id', 0);// false: chua su dung

        $dataSearch['field_get'] = '';

        $data = Device::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        //Get data department
        $totalCat = 0;
        $strDeparment = '';
        $dataSearchCatDepartment['department_status'] = -1;
        $dataDepartmentCateSearch = Department::searchByCondition($dataSearchCatDepartment, 2000, 0, $totalCat);
        $this->showCategoriesOption($dataDepartmentCateSearch, 0, '', $strDeparment, $dataSearch['device_depart_id']);

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['device_status']);
        $optionDeviceType = FunctionLib::getOption($this->arrDeviceType, isset($data['device_type'])? $data['device_type']: CGlobal::status_show);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.view',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'arrDeviceType'=>$this->arrDeviceType,
            'optionDeviceType'=>$optionDeviceType,
            'arrPersion'=>$this->arrPersion,
            'optionDepartment'=>$strDeparment,
        ],$this->viewPermission));
    }
    public function getItem($ids) {

        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);
        Loader::loadJS('lib/number/autoNumeric.js', CGlobal::$POS_HEAD);

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        $department_id = 0;
        if($id > 0) {
            $data = Device::getItemById($id);
            $department_id = $data->device_depart_id;
        }

        $this->getDataDefault();

        //Get data department
        $totalCat = 0;
        $strDeparment = '';
        $dataSearchCatDepartment['department_status'] = -1;
        $dataDepartmentCateSearch = Department::searchByCondition($dataSearchCatDepartment, 2000, 0, $totalCat);
        $this->showCategoriesOption($dataDepartmentCateSearch, 0, '', $strDeparment, $department_id);

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['device_status'])? $data['device_status']: CGlobal::status_show);
        $optionDeviceType = FunctionLib::getOption($this->arrDeviceType, isset($data['device_type'])? $data['device_type']: CGlobal::status_show);
        $optionPersion = FunctionLib::getOption($this->arrPersion, isset($data['device_person_id'])? $data['device_person_id'] : FunctionLib::inputId(-1));

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'optionStatus'=>$optionStatus,
            'optionDeviceType'=>$optionDeviceType,
            'optionDepartment'=>$strDeparment,
            'optionPersion'=>$optionPersion,
        ],$this->viewPermission));
    }
    public function postItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        if(isset($data['device_type'])) {
            $data['device_type'] = (int)$data['device_type'];
        }

        if(isset($data['device_date_use'])) {
            $data['device_date_use'] = FunctionLib::convertDate($data['device_date_use']);
        }
        if(isset($data['device_date_return'])) {
            $data['device_date_return'] = FunctionLib::convertDate($data['device_date_return']);
        }
        if(isset($data['device_date_of_manufacture'])) {
            $data['device_date_of_manufacture'] = FunctionLib::convertDate($data['device_date_of_manufacture']);
        }
        if(isset($data['device_date_warranty'])) {
            $data['device_date_warranty'] = FunctionLib::convertDate($data['device_date_warranty']);
        }
        if(isset($data['device_depart_id'])) {
            $data['device_depart_id'] = FunctionLib::outputId($data['device_depart_id']);
        }
        if(isset($data['device_price'])) {
            $data['device_price'] = str_replace('.', '', $data['device_price']);
        }
        $data['device_status'] = (int)($data['device_status']);
        $img_current = '';
        if($id > 0){
            $dataCurrent = Device::getItemById($id);
            if(sizeof($dataCurrent) > 0){
                $img_current = $dataCurrent->device_image;
            }
        }
        $data['device_image'] = Upload::check_upload_file('device_image', $img_current, Define::FOLDER_DEVICE);

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {
                if(Device::updateItem($id, $data)) {
                    if(isset($data['clickPostPageNext'])){
                        return Redirect::route('hr.deviceEdit', array('id'=>FunctionLib::inputId(0)));
                    }else{
                        return Redirect::route('hr.deviceView');
                    }
                }
            }else{
                if(Device::createItem($data)) {
                    if(isset($data['clickPostPageNext'])){
                        return Redirect::route('hr.deviceEdit', array('id'=>FunctionLib::inputId(0)));
                    }else{
                        return Redirect::route('hr.deviceView');
                    }
                }
            }
        }

        $this->getDataDefault();
        //Get data department
        $department_id = 0;
        if(isset($data['device_depart_id'])){
            $department_id = $data['device_depart_id'];
        }
        $totalCat = 0;
        $strDeparment = '';
        $dataSearchCatDepartment['department_status'] = -1;
        $dataDepartmentCateSearch = Department::searchByCondition($dataSearchCatDepartment, 2000, 0, $totalCat);
        $this->showCategoriesOption($dataDepartmentCateSearch, 0, '', $strDeparment, $department_id);

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['device_status'])? $data['device_status']: CGlobal::status_show);
        $optionDeviceType = FunctionLib::getOption($this->arrDeviceType, isset($data['device_type'])? $data['device_type']: CGlobal::status_show);
        $optionPersion = FunctionLib::getOption($this->arrPersion, isset($data['device_person_id'])? $data['device_person_id']: -1);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
            'optionDeviceType'=>$optionDeviceType,
            'optionDepartment'=>$strDeparment,
            'optionPersion'=>$optionPersion,

        ],$this->viewPermission));
    }
    public function deleteDevice(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0 && Device::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['device_type']) && trim($data['device_type']) == '') {
                $this->error[] = 'Loại thiết bị không được rỗng';
            }
            if(isset($data['device_name']) && trim($data['device_name']) == '') {
                $this->error[] = 'Tên thiết bị không được rỗng';
            }
        }
        return true;
    }
    public static function showCategoriesOption($categories, $parent_id = 0, $char='-', &$str, $default=0){
        foreach($categories as $key => $item){
            if($item['department_parent_id'] == $parent_id){
                if($default == $item['department_id']){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
                $str .= '<option '.$selected.' value="'.FunctionLib::inputId($item['department_id']).'">'.$char. $item['department_name'].'</option>';
                unset($categories[$key]);

                self::showCategoriesOption($categories, $item['department_id'], $char.'---', $str, $default);
            }
        }
    }
    public function exportDevice(){
        if (!$this->is_root && !in_array($this->permission_export, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        $dataSearch['device_name'] = Request::get('device_name', '');
        $dataSearch['device_type'] = Request::get('device_type', -1);
        $dataSearch['device_depart_id'] = FunctionLib::outputId(Request::get('device_depart_id', -1));
        $dataSearch['device_status'] = Request::get('device_status', -2);
        $dataSearch['order_sort_device_id'] = 'asc';

        $data = Device::searchExport($dataSearch, 0);

        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load(Config::get('config.DIR_ROOT') ."app/Http/Controllers/Hr/report/reportdevice.xls");
        $generatedDate = date("d-m-Y");

        $this->getDataDefault();
        $arrDeviceType = $this->arrDeviceType;
        $arrStatus = $this->arrStatus;

        $i=1;
        if($data){
            foreach ($data as $item){
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(15);
                $objDepartment = Department::getItemById($item->device_depart_id);
                $objPerson = Person::getPersonById($item->device_person_id);

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $item->device_name)
                    ->setCellValue('B'.$i, $item->device_code)
                    ->setCellValue('C'.$i, isset($arrDeviceType[$item->device_type]) ? $arrDeviceType[$item->device_type] : '')
                    ->setCellValue('D'.$i, isset($objDepartment->department_name) ? $objDepartment->department_name : '')
                    ->setCellValue('E'.$i, ($item->device_date_of_manufacture > 0) ? date('d/m/Y', $item->device_date_of_manufacture) : '')
                    ->setCellValue('F'.$i, ($item->device_date_warranty > 0) ? date('d/m/Y', $item->device_date_warranty) : '')
                    ->setCellValue('G'.$i, ($item->device_date_return > 0) ? date('d/m/Y', $item->device_date_return) : '')
                    ->setCellValue('H'.$i, ($item->device_date_use > 0) ? date('d/m/Y', $item->device_date_use) : '')
                    ->setCellValue('I'.$i, isset($objPerson->person_name) ? $objPerson->person_name : '')
                    ->setCellValue('J'.$i, $item->device_describe)
                    ->setCellValue('K'.$i, $item->device_infor_technical)
                    ->setCellValue('L'.$i, isset($arrStatus[$item->device_status]) ? $arrStatus[$item->device_status] : 'Chưa xác định');
            }
        }
        $filename = 'reportDevice';
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$filename.'_'.$generatedDate.'.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        die;
    }
}
