<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
namespace App\Http\Controllers\News;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\News\AdminNews;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class AdminNewsController extends BaseAdminController{

	private $permission_view = 'news_view';
	private $permission_create = 'news_create';
	private $permission_edit = 'news_edit';
	private $permission_delete = 'news_delete';
	private $arrStatus = array();
	private $error = '';

	public function __construct(){
		parent::__construct();
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý tin tức';
	}

	public function getDataDefault(){
		$this->arrStatus = array(
			CGlobal::status_block => FunctionLib::controLanguage('status_choose', $this->languageSite),
			CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
			CGlobal::status_hide => FunctionLib::controLanguage('status_hidden', $this->languageSite));
	}
	public function view(){

        if(!$this->is_root &&!in_array($this->permission_view, $this->permission)){
			return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

		$page_no = (int)Request::get('page_no', 1);
		$limit = CGlobal::number_show_20;
		$offset = ($page_no - 1) * $limit;
		$search = $data = array();
		$total = 0;

		$search['news_title'] = addslashes(Request::get('news_title', ''));
		$search['news_status'] = (int)Request::get('news_status', -1);
		$search['field_get'] = '';

		$data = AdminNews::searchByCondition($search, $limit, $offset, $total);
		$paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        $this->getDataDefault();

        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['news_status']);

        return view('news.view',[
                    'total'=>$total,
                    'paging'=>$paging,
                    'data'=>$data,
                    'stt' => ($page_no - 1) * $limit,
                    'arrStatus'=>$this->arrStatus,
                    'optionStatus'=>$optionStatus,
                    'search'=>$search,
                ]);
	}
	public function getItem($ids=0){

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = AdminNews::getItemById($id);
        }
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['news_status'])? $data['news_status']: CGlobal::status_show);
        return view('news.add',[
                    'id'=>$id,
                    'data'=>$data,
                    'optionStatus'=>$optionStatus
                ]);


	}
    public function postItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {
                if(AdminNews::updateItem($id, $data)) {
                    return Redirect::route('admin.newsView');
                }
            }else{
                if(AdminNews::createItem($data)) {
                    return Redirect::route('admin.newsView');
                }
            }
        }

        $this->getDataDefault();
		
		$optionStatus = Utility::getOption($this->arrStatus, isset($data['news_status'])? $data['news_status'] : -1);

        return view('news.add',[
                    'id'=>$id,
                    'data'=>$data,
                    'optionStatus'=>$optionStatus,
                    'error'=>$this->error,
                ]);
	}
	public function deleteNews(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0 && AdminNews::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
	}
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['news_title']) && trim($data['news_title']) == '') {
                $this->error[] = 'Tên bài viết không được rỗng';
            }
        }
        return true;
    }

    //View cho nguoi dung

    public function viewShow(){
        $page_no = (int)Request::get('page_no', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['news_title'] = addslashes(Request::get('news_title', ''));
        $search['news_status'] = (int)Request::get('news_status', -1);
        $search['field_get'] = '';

        $data = AdminNews::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';
        $this->getDataDefault();

        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['news_status']);

        return view('news.viewShow',[
            'total'=>$total,
            'paging'=>$paging,
            'data'=>$data,
            'stt' => ($page_no - 1) * $limit,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
            'search'=>$search,
        ]);
    }
    public function newsViewItem($name='', $id=0){
        $data = array();
        if($id > 0) {
            $data = AdminNews::getItemById($id);
        }
        if(sizeof($data) == 0){
            return Redirect::route('admin.viewShow');
        }

        return view('news.viewShowItem',[
            'id'=>$id,
            'data'=>$data,
        ]);
    }
}