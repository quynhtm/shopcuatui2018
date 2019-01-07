<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Contact;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;


class AdminContactController extends BaseAdminController
{
    private $error = array();
    private $arrStatus = array();
    private $arrMenuParent = array();
    private $viewOptionData = array();
    private $viewPermission = array();

    public function __construct()   //hàm tạo
    {
        parent::__construct();  //gọi đến hàm __construct mà hàm này kế thừa và hàm __construct này ở trong baseAdminController - hàm parent ngoài gọi để kế thừa ,mở rộng hàm cha còn có thể dùng để ghi đè lên hàm cha
        CGlobal::$pageAdminTitle = 'Quản lý liên hệ';
    }

    public function _getDataDefault()
    {
        $this->arrStatus = array(
            STATUS_BLOCK => viewLanguage('status_choose', $this->languageSite),
            STATUS_SHOW =>  viewLanguage('status_show', $this->languageSite),
            STATUS_HIDE =>  viewLanguage('status_hidden', $this->languageSite));

        //out put permiss
        $this->viewPermission = [  // cấp quyền cho người dùng
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_CONTACT_FULL),
            'permission_create' => $this->checkPermiss(PERMISS_CONTACT_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_CONTACT_DELETE),
        ];
    }
    public function _outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus, isset($data['contact_title']) ? $data['contact_title'] : STATUS_SHOW);
        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'pageTitle' => CGlobal::$pageAdminTitle,//thêm biến
        ];
    }

    public function view()     //hiển thị
    {
        //Check phan quyen.
        if (!$this->checkMultiPermiss([PERMISS_CONTACT_FULL, PERMISS_CONTACT_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $this->_getDataDefault(); //lấy dữ liệu mặc định
        $pageNo = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();

        $search['contact_title'] = addslashes(Request::get('contact_title', ''));
        $search['contact_status'] = (int)Request::get('contact_status', -1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = app(Contact::class)->searchByCondition($search, $limit, $offset);
        $paging = $data['total'] > 0 ? Pagging::getNewPager(3, $pageNo, $data['total'], $limit, $search) : '';
        $this->_outDataView($search);
        return view('admin.AdminContact.view', array_merge([
            'data' => $data['data'],
            'search' => $search,
            'total' => $data['total'],
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
        ], $this->viewPermission, $this->viewOptionData));

    }

    public function getItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_CONTACT_FULL, PERMISS_CONTACT_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $data = (($id > 0)) ? app(Contact::class)->getItemById($id) : [];
        $this->_getDataDefault();
        $this->_outDataView($data);
        return view('admin.AdminContact.add', array_merge([
            'data' => $data,
            'id' => $id,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function postItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_CONTACT_FULL, PERMISS_CONTACT_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        if ($this->_validData($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                //cap nhat
                if (app(Contact::class)->updateItem($id, $data)) {
                    return Redirect::route('admin.contactView');
                }
            } else {
                //them moi
                if (app(Contact::class)->createItem($data)) {
                    return Redirect::route('admin.contactView');
                }
            }
        }
        $this->_getDataDefault();
        $this->_outDataView($data);
        return view('admin.AdminContact.view', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function deleteContact()
    {
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_CONTACT_FULL, PERMISS_CONTACT_DELETE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id = (int)Request::get('id', 0);

        if ($id > 0 && app(Contact::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    private function _validData($data = array())
    {
        if (!empty($data)) {
            if (isset($data['contact_title']) && trim($data['contact_title']) == '') {
                $this->error[] = 'Tên contact không được bỏ trống';
            }

            if (isset($data['contact_status']) && trim($data['contact_status']) == ''){
                $this->error[] = 'Tên content không được bỏ trống';
            }
        }
        return true;
    }
}
