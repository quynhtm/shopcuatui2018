<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\HrDefine;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

use PHPExcel_IOFactory;
use Illuminate\Support\Facades\Input;


class HrDefinedController extends BaseAdminController
{

    private $permission_view = 'hrDefined_view';
    private $permission_full = 'hrDefined_full';
    private $permission_delete = 'hrDefined_delete';
    private $permission_create = 'hrDefined_create';
    private $permission_edit = 'hrDefined_edit';

    private $arrStatus = array();
    private $error = array();
    private $viewPermission = array();
    private $arrDefinedType = array();

    public function __construct()
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý định nghĩa chung';
    }

    public function getDataDefault()
    {
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden', $this->languageSite)
        );
        $this->arrDefinedType = Define::$arrOptionDefine;
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_boss' => $this->is_boss ? 1 : 0,
            'is_root' => $this->is_root ? 1 : 0,
            'permission_edit' => in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create' => in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_remove' => in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full' => in_array($this->permission_full, $this->permission) ? 1 : 0,
        ];
    }

    public function view()
    {

        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        $pageNo = (int)Request::get('page_no', 1);
        $limit = 0;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['define_name'] = addslashes(Request::get('define_name_s'));
        $search['define_type'] = (int)Request::get('define_type', Define::chuc_vu);
        $search['field_get'] = '';

        $dataSearch = HrDefine::searchByCondition($search, $limit, $offset, $total);
        $paging = '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($search['define_status']) ? $search['define_status'] : CGlobal::status_show);
        $optionDefinedType = FunctionLib::getOption($this->arrDefinedType, isset($search['define_type']) ? $search['define_type'] : Define::chuc_vu);

        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Defined.view', array_merge([
            'data' => $dataSearch,
            'search' => $search,
            'total' => $total,
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'optionStatus' => $optionStatus,
            'arrStatus' => $this->arrStatus,
            'optionDefinedType' => $optionDefinedType,
            'arrDefinedType' => $this->arrDefinedType,
        ], $this->viewPermission));
    }

    public function postItem($ids)
    {
        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $arrSucces = ['isOk' => 0];
        $id_hiden = (int)Request::get('id', 0);
        $data = $_POST;
        unset($data['id']);
        $data['define_order'] = (int)($data['define_order']);
        if ($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                $data['update_time'] = time();
                $data['user_id_update'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;
                $data['user_name_update'] = isset($this->user['user_name']) ? $this->user['user_name'] : 0;
                HrDefine::updateItem($id, $data);
            } else {
                $data['creater_time'] = time();
                $data['user_id_creater'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;
                $data['user_name_creater'] = isset($this->user['user_name']) ? $this->user['user_name'] : 0;
                HrDefine::createItem($data);
            }
            $arrSucces['isOk'] = 1;
            $arrSucces['url'] = URL::route('hr.definedView', array('define_type' => $data['define_type']));
            return $arrSucces;
        }
        return $arrSucces;
    }

    public function deleteDefined()
    {
        $data = array('isIntOk' => 0);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_delete, $this->permission)) {
            return Response::json($data);
        }
        $id = isset($_GET['id']) ? FunctionLib::outputId($_GET['id']) : 0;
        if ($id > 0 && HrDefine::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    public function ajaxLoadForm()
    {
        $ids = $_POST['id'];
        $id = FunctionLib::outputId($ids);
        $data = [];
        $data['define_id'] = 0;
        if ($id > 0) {
            $data = HrDefine::find($id);
        }
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['define_status']) ? $data['define_status'] : CGlobal::status_show);
        $optionDefinedType = FunctionLib::getOption($this->arrDefinedType, isset($data['define_type']) ? $data['define_type'] : Define::chuc_vu);

        return view('hr.Defined.ajaxLoadForm',
            array_merge([
                'data' => $data,
                'optionStatus' => $optionStatus,
                'optionDefinedType' => $optionDefinedType,
            ], $this->viewPermission));
    }

    private function valid($data = array())
    {
        if (!empty($data)) {
            if (isset($data['define_name']) && trim($data['define_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }

    public function importDataToExcel()
    {
        require(dirname(__FILE__) . '/../../../Library/ClassPhpExcel/PHPExcel/IOFactory.php');
        $rowsExcel = [];
        if (Input::hasFile('file_excel_define')) {
            $file = Input::file('file_excel_define');
            //$nameFileUpload = Input::file('file_excel_sms_clever')->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            switch ($ext) {
                case 'xls':
                case 'xlsx':
                    $objPHPExcel = PHPExcel_IOFactory::load($file);
                    $objPHPExcel->setActiveSheetIndex(0);
                    $rowsExcel = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    break;
                default:
                    $error[] = "Invalid file type";
            }
        } else {
            $error[] = "Not found file input";
        }
        $arrDataInput = array();
        if (!empty($rowsExcel)) {
            unset($rowsExcel[1]);
            $key1 = 0;
            foreach ($rowsExcel as $key => $val) {
                if (isset($val['A']) && trim($val['A']) != '') {
                    $arrDataInput[$key1]['define_name'] = trim($val['A']);
                    $arrDataInput[$key1]['define_type'] = (isset($val['B']) && trim($val['B']) != '') ? trim($val['B']) : 1;
                    $arrDataInput[$key1]['define_order'] = (isset($val['C']) && trim($val['C']) != '') ? trim($val['C']) : 1;
                    $arrDataInput[$key1]['define_status'] = 1;
                    $key1++;
                }
            }
        }
        if (!empty($arrDataInput)) {
            DB::table(Define::TABLE_HR_DEFINE)->truncate();
            HrDefine::insertMultiple($arrDataInput);
            return Redirect::route('hr.definedView');
        }
    }
}
