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
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\HrDocument;
use App\Http\Models\Hr\Person;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class HrDocumentController extends BaseAdminController{
    private $permission_view = 'hr_document_view';
    private $permission_full = 'hr_document_full';
    private $permission_delete = 'hr_document_delete';
    private $permission_create = 'hr_document_create';
    private $permission_edit = 'hr_document_edit';
    private $arrStatus = array();
    private $arrDepartment = array();
    private $error = array();
    private $arrPersion = array();
    private $viewPermission = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý văn bản';
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
        $this->arrDepartment =  Department::getDepartmentAll();
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

    public function viewSend(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['hr_document_name'] = addslashes(Request::get('hr_document_name',''));
        $dataSearch['hr_document_promulgate'] = (int)Request::get('hr_document_promulgate', -1);
        $dataSearch['hr_document_field'] = (int)Request::get('hr_document_field', -1);
        $dataSearch['hr_document_type'] = (int)Request::get('hr_document_type', -1);
        $dataSearch['hr_document_person_send'] = $this->user['user_object_id'];
        $dataSearch['hr_document_type_view'] = Define::mail_type_0;
        $dataSearch['hr_document_status'] = Define::mail_da_gui;
        $dataSearch['field_get'] = '';

        $data = HrDocument::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();

        $optionPromulgate = FunctionLib::getOption($this->arrPromulgate, $dataSearch['hr_document_promulgate']);
        $optionType = FunctionLib::getOption($this->arrType, $dataSearch['hr_document_type']);
        $optionField = FunctionLib::getOption($this->arrField, $dataSearch['hr_document_field']);

        return view('hr.Document.viewSend',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'arrStatus'=>$this->arrStatus,
            'arrPersion'=>$this->arrPersion,

            'optionPromulgate'=>$optionPromulgate,
            'arrPromulgate'=>$this->arrPromulgate,
            'optionType'=>$optionType,
            'arrType'=>$this->arrType,
            'optionField'=>$optionField,
            'arrField'=>$this->arrField,

        ],$this->viewPermission));
    }
    public function viewGet(){
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['hr_document_name'] = addslashes(Request::get('hr_document_name',''));
        $dataSearch['hr_document_promulgate'] = (int)Request::get('hr_document_promulgate', -1);
        $dataSearch['hr_document_field'] = (int)Request::get('hr_document_field', -1);
        $dataSearch['hr_document_type'] = (int)Request::get('hr_document_type', -1);
        $dataSearch['hr_document_person_recive'] = $this->user['user_object_id'];
        $dataSearch['hr_document_type_view'] = Define::mail_type_1;
        $dataSearch['hr_document_status'] = (int)Request::get('hr_document_status', -1);
        $dataSearch['field_get'] = '';

        $data = HrDocument::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $optionPromulgate = FunctionLib::getOption($this->arrPromulgate, $dataSearch['hr_document_promulgate']);
        $optionType = FunctionLib::getOption($this->arrType, $dataSearch['hr_document_type']);
        $optionField = FunctionLib::getOption($this->arrField, $dataSearch['hr_document_field']);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Document.viewGet',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionPromulgate'=>$optionPromulgate,
            'arrPromulgate'=>$this->arrPromulgate,
            'optionType'=>$optionType,
            'arrType'=>$this->arrType,
            'optionField'=>$optionField,
            'arrField'=>$this->arrField,
        ],$this->viewPermission));
    }
    public function viewDraft(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['hr_document_name'] = addslashes(Request::get('hr_document_name',''));
        $dataSearch['hr_document_promulgate'] = (int)Request::get('hr_document_promulgate', -1);
        $dataSearch['hr_document_field'] = (int)Request::get('hr_document_field', -1);
        $dataSearch['hr_document_type'] = (int)Request::get('hr_document_type', -1);
        $dataSearch['hr_document_status'] = Define::mail_nhap;
        $dataSearch['hr_document_type_view'] = -1;
        $dataSearch['hr_document_person_send'] = $this->user['user_object_id'];
        $dataSearch['field_get'] = '';

        $data = HrDocument::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();

        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['hr_document_status']);
        $optionPromulgate = FunctionLib::getOption($this->arrPromulgate, $dataSearch['hr_document_promulgate']);
        $optionType = FunctionLib::getOption($this->arrType, $dataSearch['hr_document_type']);
        $optionField = FunctionLib::getOption($this->arrField, $dataSearch['hr_document_field']);

        return view('hr.Document.viewDraft',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'arrPersion'=>$this->arrPersion,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'optionPromulgate'=>$optionPromulgate,
            'arrPromulgate'=>$this->arrPromulgate,
            'optionType'=>$optionType,
            'arrType'=>$this->arrType,
            'optionField'=>$optionField,
            'arrField'=>$this->arrField,
        ],$this->viewPermission));
    }

    public function viewItemGet($ids) {

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        $data = $dataParent = array();
        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);
        if($id > 0) {
            $user_id = $this->user['user_object_id'];
            $data = HrDocument::getItemByIdAndPersonReciveId($id, $user_id);
            if(sizeof($data) == 0){
                return Redirect::route('hr.HrDocumentViewGet');
            }else{
                $dataUpdate['hr_document_status'] = Define::mail_da_doc;
                HrDocument::updateItem($id, $dataUpdate);
            }
        }else{
            return Redirect::route('hr.HrDocumentViewGet');
        }
        $this->getDataDefault();

        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Document.viewItemGet',array_merge([
            'data'=>$data,
            'arrUser'=>$arrUser,
            'id'=>$id,
            'arrPromulgate'=>$this->arrPromulgate,
            'arrType'=>$this->arrType,
            'arrField'=>$this->arrField,
        ],$this->viewPermission));
    }
    public function viewItemSend($ids) {

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        $data = $dataParent = array();
        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        if($id > 0) {
            $user_id = $this->user['user_object_id'];
            $data = HrDocument::getItemByIdAndPersonSendId($id, $user_id);
            if(sizeof($data) == 0){
                return Redirect::route('hr.HrDocumentViewSend');
            }
        }else{
            return Redirect::route('hr.HrDocumentViewSend');
        }
        $this->getDataDefault();


        $this->getDataDefault();

        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Document.viewItemSend',array_merge([
            'data'=>$data,
            'arrUser'=>$arrUser,
            'id'=>$id,
            'arrPromulgate'=>$this->arrPromulgate,
            'arrType'=>$this->arrType,
            'arrField'=>$this->arrField,
        ],$this->viewPermission));
    }
    public function viewItemDraft($ids){
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        $data = $dataParent = array();
        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        if($id > 0) {
            $user_id = $this->user['user_object_id'];
            $data = HrDocument::getItemDraftById($id, $user_id);
            if(sizeof($data) == 0){
                return Redirect::route('hr.HrDocumentViewDraft');
            }
        }

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();

        $optionPromulgate = FunctionLib::getOption($this->arrPromulgate, isset($data['hr_document_promulgate']) ? $data['hr_document_promulgate'] : -1);
        $optionType = FunctionLib::getOption($this->arrType, isset($data['hr_document_type']) ? $data['hr_document_type'] : -1);
        $optionField = FunctionLib::getOption($this->arrField, isset($data['hr_document_field']) ? $data['hr_document_field'] : -1);

        return view('hr.Document.viewItemDraft',array_merge([
            'data'=>$data,
            'arrUser'=>$arrUser,
            'id'=>$id,
            'arrField'=>$this->arrField,
            'optionPromulgate'=>$optionPromulgate,
            'arrPromulgate'=>$this->arrPromulgate,
            'optionType'=>$optionType,
            'arrType'=>$this->arrType,
            'arrField'=>$this->arrField,
            'optionField'=>$optionField,
            'arrDepartment'=>$this->arrDepartment,

        ],$this->viewPermission));
    }
    public function getItem($ids) {

        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = HrDocument::getItemById($id);
            if(sizeof($data) > 0){
                $user_id = $this->user['user_object_id'];
                if($data->hr_document_person_send != $user_id){
                    return Redirect::route('hr.HrDocumentEdit', array('id'=>FunctionLib::inputId(0)));
                }
            }

        }

        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        $this->getDataDefault();

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['hr_document_status'])? $data['hr_document_status']: CGlobal::status_show);
        $optionPromulgate = FunctionLib::getOption($this->arrPromulgate, isset($data['hr_document_promulgate'])? $data['hr_document_promulgate']: -1);
        $optionType = FunctionLib::getOption($this->arrType, isset($data['hr_document_type'])? $data['hr_document_type']: -1);
        $optionField = FunctionLib::getOption($this->arrField, isset($data['hr_document_field'])? $data['hr_document_field']: -1);
        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Document.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'optionStatus'=>$optionStatus,
            'optionPromulgate'=>$optionPromulgate,
            'optionType'=>$optionType,
            'optionField'=>$optionField,
            'arrUser'=>$arrUser,
            'arrDepartment'=>$this->arrDepartment,
        ],$this->viewPermission));
    }
    public function postItem($ids) {

        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $data = $_POST;
        $id_hiden = (int)FunctionLib::outputId($data['id_hiden']);
        $data['hr_document_status'] = Define::mail_nhap;

        if(isset($data['hr_document_type'])) {
            $data['hr_document_type'] = (int)$data['hr_document_type'];
        }
        if(isset($data['hr_document_date_issued'])) {
            $data['hr_document_date_issued'] = FunctionLib::convertDate($data['hr_document_date_issued']);
        }
        if(isset($data['hr_document_effective_date'])) {
            $data['hr_document_effective_date'] = FunctionLib::convertDate($data['hr_document_effective_date']);
        }
        if(isset($data['hr_document_date_expired'])) {
            $data['hr_document_date_expired'] = FunctionLib::convertDate($data['hr_document_date_expired']);
        }
        if(isset($data['hr_document_delease_date'])) {
            $data['hr_document_delease_date'] = FunctionLib::convertDate($data['hr_document_delease_date']);
        }
        if(isset($data['hr_document_update'])) {
            $data['hr_document_update'] = FunctionLib::convertDate($data['hr_document_update']);
        }
        if(isset($data['submitDocumentSend']) && $data['submitDocumentSend'] == 'submitDocumentSend'){
            $this->valid($data);
        }
        if(sizeof($this->error) == 0) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {

                $data['hr_document_status'] = -1;
                $data['hr_document_person_send'] = $this->user['user_object_id'];

                $hr_document_department_recive_list = (isset($data['hr_document_department_recive_list']) && sizeof($data['hr_document_department_recive_list']) > 0) ? $data['hr_document_department_recive_list'] : array();
                $hr_document_department_cc_list = (isset($data['hr_document_department_cc_list']) && sizeof($data['hr_document_department_cc_list']) > 0) ? $data['hr_document_department_cc_list'] : array();

                $data['hr_document_department_recive_list'] = (isset($data['hr_document_department_recive_list']) && sizeof($data['hr_document_department_recive_list']) > 0) ? implode(',', $data['hr_document_department_recive_list']) : '';
                $data['hr_document_department_cc_list'] = (isset($data['hr_document_department_cc_list']) && sizeof($data['hr_document_department_cc_list']) > 0) ? implode(',', $data['hr_document_department_cc_list']) : '';

                $data_recive = array();
                if(sizeof($hr_document_department_recive_list) > 0){
                    foreach($hr_document_department_recive_list as $depart_id){
                        $arrUsers = Person::getPersonInDepart($depart_id);
                        $data_recive += $arrUsers;
                    }
                    $data_recive = User::getUserIdInArrPersonnelId($data_recive);
                }
                $data['hr_document_person_recive_list'] = (isset($data_recive) && sizeof($data_recive) > 0) ? implode(',', $data_recive) : '';

                $data_cc = array();
                if(sizeof($hr_document_department_cc_list) > 0){
                    foreach($hr_document_department_cc_list as $depart_id){
                        $arrCC = Person::getPersonInDepart($depart_id);
                        $data_cc += $arrCC;
                    }
                    $data_cc = User::getUserIdInArrPersonnelId($data_cc);
                }
                $data['hr_document_send_cc'] = (isset($data_cc) && sizeof($data_cc) > 0) ? implode(',', $data_cc) : '';

                if(isset($data['submitDocumentDraft'])){
                    $data['hr_document_status'] = Define::mail_nhap;
                    $data['hr_document_type_view'] = -1;
                    $data['hr_document_person_send'] = $this->user['user_object_id'];
                    HrDocument::updateItem($id, $data);
                }else{
                    $data['hr_document_date_send'] = time();
                    $data['hr_document_type_view'] = Define::mail_type_0;
                    $data['hr_document_status'] = Define::mail_da_gui;
                    $documentId = HrDocument::updateItem($id, $data);
                    if($documentId > 0){
                        $getItem = HrDocument::getItemById($documentId);
                        //To
                        $hr_document_person_recive = (isset($getItem['hr_document_person_recive_list']) &&  $getItem['hr_document_person_recive_list'] != '') ? explode(',', $getItem['hr_document_person_recive_list']) : array();
                        $this->sendDataToUsers($hr_document_person_recive, $getItem);
                        //CC
                        $hr_document_send_cc = (isset($getItem['hr_document_send_cc']) && $getItem['hr_document_send_cc'] != '') ? explode(',', $getItem['hr_document_send_cc']) : array();
                        $this->sendDataToUsers($hr_document_send_cc, $getItem);
                    }
                }
                if(isset($data['submitDocumentSend']) && $data['submitDocumentSend'] == 'submitDocumentSend'){
                    return Redirect::route('hr.HrDocumentViewSend');
                }else{
                    return Redirect::route('hr.HrDocumentViewDraft');
                }
            }else{
                $data['hr_document_created'] = time();
                $data['hr_document_person_send'] = $this->user['user_object_id'];
                if(isset($data['submitDocumentDraft'])){
                    $data['hr_document_status'] = Define::mail_nhap;
                    $data['hr_document_type_view'] = -1;
                }else{
                    $data['hr_document_type_view'] = Define::mail_type_0;
                    $data['hr_document_status'] = Define::mail_da_gui;
                    $data['hr_document_date_send'] = time();
                }

                $hr_document_department_recive_list = (isset($data['hr_document_department_recive_list']) && sizeof($data['hr_document_department_recive_list']) > 0) ? $data['hr_document_department_recive_list'] : array();
                $hr_document_department_cc_list = (isset($data['hr_document_department_cc_list']) && sizeof($data['hr_document_department_cc_list']) > 0) ? $data['hr_document_department_cc_list'] : array();

                $data['hr_document_department_recive_list'] = (isset($data['hr_document_department_recive_list']) && sizeof($data['hr_document_department_recive_list']) > 0) ? implode(',', $data['hr_document_department_recive_list']) : '';
                $data['hr_document_department_cc_list'] = (isset($data['hr_document_department_cc_list']) && sizeof($data['hr_document_department_cc_list']) > 0) ? implode(',', $data['hr_document_department_cc_list']) : '';

                $data_recive = array();
                if(sizeof($hr_document_department_recive_list) > 0){
                    foreach($hr_document_department_recive_list as $depart_id){
                        $arrUsers = Person::getPersonInDepart($depart_id);
                        $data_recive += $arrUsers;
                    }

                    $data_recive = User::getUserIdInArrPersonnelId($data_recive);

                }

                $data['hr_document_person_recive_list'] = (isset($data_recive) && sizeof($data_recive) > 0) ? implode(',', $data_recive) : '';

                $data_cc = array();
                if(sizeof($hr_document_department_cc_list) > 0){
                    foreach($hr_document_department_cc_list as $depart_id){
                        $arrCC = Person::getPersonInDepart($depart_id);
                        $data_cc += $arrCC;
                    }
                    $data_cc = User::getUserIdInArrPersonnelId($data_cc);
                }
                $data['hr_document_send_cc'] = (isset($data_cc) && sizeof($data_cc) > 0) ? implode(',', $data_cc) : '';

                $documentId = HrDocument::createItem($data);

                if(!isset($data['submitDocumentDraft'])){
                    $getItem = HrDocument::getItemById($documentId);
                    //To
                    $hr_document_person_recive = (isset($getItem['hr_document_person_recive_list']) &&  $getItem['hr_document_person_recive_list'] != '') ? explode(',', $getItem['hr_document_person_recive_list']) : array();
                    $this->sendDataToUsers($hr_document_person_recive, $getItem);
                    //CC
                    $hr_document_send_cc = (isset($getItem['hr_document_send_cc']) && $getItem['hr_document_send_cc'] != '') ? explode(',', $getItem['hr_document_send_cc']) : array();
                    $this->sendDataToUsers($hr_document_send_cc, $getItem);
                }

                if(isset($data['submitDocumentSend']) && $data['submitDocumentSend'] == 'submitDocumentSend'){
                    return Redirect::route('hr.HrDocumentViewSend');
                }else{
                    return Redirect::route('hr.HrDocumentViewDraft');
                }
            }
        }

        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        $this->getDataDefault();

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['hr_document_status'])? $data['hr_document_status']: CGlobal::status_show);
        $optionPromulgate = FunctionLib::getOption($this->arrPromulgate, isset($data['hr_document_promulgate'])? $data['hr_document_promulgate']: -1);
        $optionType = FunctionLib::getOption($this->arrType, isset($data['hr_document_type'])? $data['hr_document_type']: -1);
        $optionField = FunctionLib::getOption($this->arrField, isset($data['hr_document_field'])? $data['hr_document_field']: -1);
        $this->viewPermission = $this->getPermissionPage();

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Document.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
            'optionPromulgate'=>$optionPromulgate,
            'optionType'=>$optionType,
            'optionField'=>$optionField,
            'arrUser'=>$arrUser,

        ],$this->viewPermission));
    }

    public function deleteHrDocument(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0) {
            $getItem = HrDocument::getItemById($id);
            $user_id = $this->user['user_object_id'];
            $data['isIntOk'] = 0;
            if(sizeof($getItem) > 0){
                if(($getItem->hr_document_type_view == Define::mail_type_0 || $getItem->hr_document_type_view == -1) && $getItem->hr_document_person_send == $user_id){
                    HrDocument::deleteItem($id);
                    $data['isIntOk'] = 1;
                }
                if($getItem->hr_document_type_view == Define::mail_type_1 && $getItem->hr_document_person_recive == $user_id){
                    HrDocument::deleteItem($id);
                    $data['isIntOk'] = 1;
                }
            }
        }
        return Response::json($data);
    }

    public function ajaxItemForward() {

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $id = FunctionLib::outputId(Request::get('parent_id', 0));
        $data = $dataNew = array();
        if($id > 0) {
            $data = HrDocument::getItemById($id);
        }
        if(sizeof($data) > 0){
            $dataAdd['hr_document_project'] = $data->hr_document_project;
            $dataAdd['hr_document_name'] = $data->hr_document_name;
            $dataAdd['hr_document_desc'] = $data->hr_document_desc;
            $dataAdd['hr_document_content'] = $data->hr_document_content;
            $dataAdd['hr_document_code'] = $data->hr_document_code;
            $dataAdd['hr_document_promulgate'] = $data->hr_document_promulgate;
            $dataAdd['hr_document_type'] = $data->hr_document_type;
            $dataAdd['hr_document_files'] = $data->hr_document_files;
            $dataAdd['hr_document_signer'] = $data->hr_document_signer;
            $dataAdd['hr_document_date_issued'] = $data->hr_document_date_issued;
            $dataAdd['hr_document_effective_date'] = $data->hr_document_effective_date;
            $dataAdd['hr_document_date_expired'] = $data->hr_document_date_expired;
            $dataAdd['hr_document_delease_date'] = $data->hr_document_delease_date;
            $dataAdd['hr_document_update'] = $data->hr_document_update;
            $dataAdd['hr_document_person_send'] = $data->hr_document_person_send;
            $dataAdd['hr_document_parent'] = $id;
            $dataAdd['hr_document_status'] = Define::mail_nhap;
            $dataAdd['hr_document_type_view'] = -1;
            $dataAdd['hr_document_created'] = time();
            $dataAdd['hr_document_department_recive_list'] = $data->hr_document_department_recive_list;
            $dataAdd['hr_document_department_cc_list'] = $data->hr_document_department_cc_list;

            $idNew = HrDocument::createItem($dataAdd);
            if($data->hr_document_files != '') {
                $hr_document_files = ($data->hr_document_files != '') ? unserialize($data->hr_document_files) : array();
                if(sizeof($hr_document_files) > 0) {
                    foreach ($hr_document_files as $key => $file) {
                        $folder_document = Config::get('config.DIR_ROOT').'uploads/'.Define::FOLDER_DOCUMENT;
                        $path_current = $folder_document . '/' . $data->hr_document_id . '/' . $file;
                        if(file_exists($path_current)){
                            $folder_copy = $folder_document . '/' .$idNew;
                            $path_copy = $folder_copy . '/' .$file;
                            if(!is_dir($folder_copy)){
                                @mkdir($folder_copy,0777,true);
                                @chmod($folder_copy,0777);
                            }
                            @copy($path_current, $path_copy);
                        }
                    }
                }
            }
            $dataNew = HrDocument::getItemById($idNew);
        }

        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        $this->getDataDefault();

        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Document.ajaxForward',array_merge([
            'data'=>$dataNew,
            'id'=>$idNew,
            'arrUser'=>$arrUser,
            'arrDepartment'=>$this->arrDepartment,
        ],$this->viewPermission));
    }
    public function ajaxItemReply() {

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $id = FunctionLib::outputId(Request::get('parent_id', 0));
        $data = $dataNew = array();
        if($id > 0) {
            $data = HrDocument::getItemById($id);
        }
        if(sizeof($data) > 0){
            $dataAdd['hr_document_project'] = $data->hr_document_project;
            $dataAdd['hr_document_name'] = $data->hr_document_name;
            $dataAdd['hr_document_desc'] = $data->hr_document_desc;
            $dataAdd['hr_document_content'] = $data->hr_document_content;
            $dataAdd['hr_document_code'] = $data->hr_document_code;
            $dataAdd['hr_document_promulgate'] = $data->hr_document_promulgate;
            $dataAdd['hr_document_type'] = $data->hr_document_type;
            $dataAdd['hr_document_files'] = $data->hr_document_files;
            $dataAdd['hr_document_signer'] = $data->hr_document_signer;
            $dataAdd['hr_document_date_issued'] = $data->hr_document_date_issued;
            $dataAdd['hr_document_effective_date'] = $data->hr_document_effective_date;
            $dataAdd['hr_document_date_expired'] = $data->hr_document_date_expired;
            $dataAdd['hr_document_delease_date'] = $data->hr_document_delease_date;
            $dataAdd['hr_document_update'] = $data->hr_document_update;
            $dataAdd['hr_document_person_send'] = $data->hr_document_person_send;
            $dataAdd['hr_document_person_recive_list'] = $data->hr_document_person_recive_list;
            $dataAdd['hr_document_parent'] = $id;
            $dataAdd['hr_document_status'] = Define::mail_nhap;
            $dataAdd['hr_document_type_view'] = -1;
            $dataAdd['hr_document_created'] = time();
            $dataAdd['hr_document_department_recive_list'] = $data->hr_document_department_recive_list;
            $dataAdd['hr_document_department_cc_list'] = $data->hr_document_department_cc_list;

            $idNew = HrDocument::createItem($dataAdd);

            if($data->hr_document_files != '') {
                $hr_document_files = ($data->hr_document_files != '') ? unserialize($data->hr_document_files) : array();
                if(sizeof($hr_document_files) > 0) {
                    foreach ($hr_document_files as $key => $file) {
                        $folder_document = Config::get('config.DIR_ROOT').'uploads/'.Define::FOLDER_DOCUMENT;
                        $path_current = $folder_document . '/' . $data->hr_document_id . '/' . $file;
                        if(file_exists($path_current)){
                            $folder_copy = $folder_document . '/' .$idNew;
                            $path_copy = $folder_copy . '/' .$file;
                            if(!is_dir($folder_copy)){
                                @mkdir($folder_copy,0777,true);
                                @chmod($folder_copy,0777);
                            }
                            @copy($path_current, $path_copy);
                        }
                    }
                }
            }
            $dataNew = HrDocument::getItemById($idNew);
        }

        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        $this->getDataDefault();

        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Document.ajaxItemReply',array_merge([
            'data'=>$dataNew,
            'id'=>$idNew,
            'arrUser'=>$arrUser,
            'arrDepartment'=>$this->arrDepartment,
        ],$this->viewPermission));
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(!isset($data['hr_document_department_recive_list']) && !isset($data['hr_document_department_cc_list'])){
                $this->error[] = 'Người nhận hoặc CC không được trống.';
            }
        }else{
            $this->error[] = 'Dữ liệu không được trống.';
        }
        return true;
    }

    public function getArrayUserFromData($data=array()){
        $result = array();
        if(sizeof($data) > 0){
            foreach($data as $item){
                if(!in_array($item->user_id, Define::mail_user_unset)){
                    if($item->user_full_name != ''){
                        $result[$item->user_id] = $item->user_full_name;
                    }else{
                        $result[$item->user_id] = $item->user_name;
                    }
                }
            }
        }
        return $result;
    }
    public function sendDataToUsers($dataUser, $getItem){
        if(sizeof($dataUser) > 0 && sizeof($getItem) >0){
            foreach($dataUser as $key=>$recive) {
                $dataRecive['hr_document_project'] = $getItem->hr_document_project;
                $dataRecive['hr_document_name'] = $getItem->hr_document_name;
                $dataRecive['hr_document_desc'] = $getItem->hr_document_desc;
                $dataRecive['hr_document_content'] = $getItem->hr_document_content;
                $dataRecive['hr_document_code'] = $getItem->hr_document_code;
                $dataRecive['hr_document_promulgate'] = $getItem->hr_document_promulgate;
                $dataRecive['hr_document_type'] = $getItem->hr_document_type;
                $dataRecive['hr_document_field'] = $getItem->hr_document_field;
                $dataRecive['hr_document_signer'] = $getItem->hr_document_signer;
                $dataRecive['hr_document_date_issued'] = $getItem->hr_document_date_issued;
                $dataRecive['hr_document_effective_date'] = $getItem->hr_document_effective_date;
                $dataRecive['hr_document_date_expired'] = $getItem->hr_document_date_expired;
                $dataRecive['hr_document_delease_date'] = $getItem->hr_document_delease_date;

                $dataRecive['hr_document_person_recive'] = (int)$recive;
                $dataRecive['hr_document_person_recive_list'] = $getItem->hr_document_person_recive_list;
                $dataRecive['hr_document_person_send'] = $this->user['user_object_id'];
                $dataRecive['hr_document_send_cc'] = $getItem->hr_document_send_cc;
                $dataRecive['hr_document_created'] = time();
                $dataRecive['hr_document_update'] = time();
                $dataRecive['hr_document_date_send'] = time();
                $dataRecive['hr_document_files'] = $getItem->hr_document_files;
                $dataRecive['hr_document_type_view'] = Define::mail_type_1;
                $dataRecive['hr_document_status'] = Define::mail_chua_doc;

                $dataRecive['hr_document_department_recive_list'] = $getItem->hr_document_department_recive_list;
                $dataRecive['hr_document_department_cc_list'] = $getItem->hr_document_department_cc_list;

                $idDocumentOther = HrDocument::createItem($dataRecive);

                if($getItem->hr_document_files != '') {
                    $hr_document_files = ($getItem->hr_document_files != '') ? unserialize($getItem->hr_document_files) : array();
                    if(sizeof($hr_document_files) > 0) {
                        foreach ($hr_document_files as $key => $file) {
                            $folder_document = Config::get('config.DIR_ROOT').'uploads/'.Define::FOLDER_DOCUMENT;
                            $path_current = $folder_document . '/' . $getItem->hr_document_id . '/' . $file;
                            if(file_exists($path_current)){
                                $folder_copy = $folder_document . '/' .$idDocumentOther;
                                $path_copy = $folder_copy . '/' .$file;
                                if(!is_dir($folder_copy)){
                                    @mkdir($folder_copy,0777,true);
                                    @chmod($folder_copy,0777);
                                }
                                @copy($path_current, $path_copy);
                            }
                        }
                    }
                }
            }
        }
    }
}
