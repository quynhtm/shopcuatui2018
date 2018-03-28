<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\News\CategoryNew;
use App\Http\Models\News\News;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;

use App\Library\AdminFunction\Upload;

use App\Library\PHPThumb\ThumbImg;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class NewsController extends BaseAdminController
{
    private $permission_view = 'newsView';
    private $permission_full = 'newsFull';
    private $permission_delete = 'newsDelete';
    private $permission_create = 'newsCreate';
    private $permission_edit = 'newsEdit';

    private $permission_view_product = 'newsViewProduct';
    private $permission_full_product = 'newsFullProduct';
    private $permission_delete_product = 'newsDeleteProduct';
    private $permission_create_product = 'newsCreateProduct';
    private $permission_edit_product = 'newsEditProduct';

    private $arrStatus = array();
    private $arrCategoryNew = array();

    private $arrTypeNews = array();
    private $arrCategoryNewProduct = array();

    private $error = array();
    private $viewPermission = array();//check quyen

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý Tin tức';

        $this->arrCategoryNew = CategoryNew::getAllParentCategoryId();
    }

    public function getDataDefault()
    {
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden', $this->languageSite));

        $this->arrTypeNews = Define::$arrTypeNews;
        $this->arrCategoryNew = CategoryNew::getCategoryNews();
        $this->arrCategoryNewProduct = CategoryNew::getCategoryProduct();
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_root' => $this->is_root ? 1 : 0,
            'permission_edit' => in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create' => in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_delete' => in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full' => in_array($this->permission_full, $this->permission) ? 1 : 0,

            'permission_edit_product' => in_array($this->permission_edit_product, $this->permission) ? 1 : 0,
            'permission_create_product' => in_array($this->permission_create_product, $this->permission) ? 1 : 0,
            'permission_delete_product' => in_array($this->permission_delete_product, $this->permission) ? 1 : 0,
            'permission_full_product' => in_array($this->permission_full_product, $this->permission) ? 1 : 0,
        ];
    }

    /*********************************************************************************************************************************
     * @return TIn tức
     *********************************************************************************************************************************/
    public function view()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $pageNo = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = 200;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['news_title'] = addslashes(Request::get('news_title', ''));
        $search['news_status'] = (int)Request::get('news_status', -1);
        $search['news_category'] = Define::news_type_new;
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $dataSearch = News::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['news_status']);

        $optionCategoryNew = FunctionLib::getOption($this->arrCategoryNew, $search['news_category']);

        $this->viewPermission = $this->getPermissionPage();
        return view('news.News.view', array_merge([
            'data' => $dataSearch,
            'search' => $search,
            'arrCategoryNew' => $this->arrCategoryNew,
            'arrTypeNews' => $this->arrTypeNews,
            'total' => $total,
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'optionStatus' => $optionStatus,
            'optionCategoryNew' => $optionCategoryNew,
        ], $this->viewPermission));
    }
    public function getItem($ids)
    {
        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);

        $data = $news_image_other = array();
        $news_image = '';
        if ($id > 0) {
            $data = News::find($id);
            if($data != null){
                if($data->news_image_other != ''){
                    $newsImageOther = unserialize($data->news_image_other);
                    if(!empty($newsImageOther)){
                        foreach($newsImageOther as $k=>$v){
                            $folder = Define::FOLDER_NEWS.'/'.$id;
                            $url_thumb = ThumbImg::thumbBaseNormal($folder, $v, 300, 300, '', true, true);
                            $news_image_other[] = array('img_other'=>$v,'src_img_other'=>$url_thumb);
                        }
                    }
                }
                //Main Img
                $news_image = trim($data->news_image);
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['news_status']) ? $data['news_status'] : CGlobal::status_show);
        $optionType = FunctionLib::getOption($this->arrTypeNews, isset($data['news_type']) ? $data['news_type'] : Define::news_type_new);
        $optionCategoryNew = FunctionLib::getOption($this->arrCategoryNew, isset($data['news_category']) ? $data['news_category'] : CGlobal::status_hide);

        $this->viewPermission = $this->getPermissionPage();


        return view('news.News.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'news_image'=>$news_image,
            'news_image_other'=>$news_image_other,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
            'optionType'=>$optionType,
            'optionCategoryNew'=>$optionCategoryNew ,
        ],$this->viewPermission));
    }
    public function postItem($ids){
        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        $data['news_type'] = Define::news_type_new;
        if(isset($data['news_order_no'])){
            $data['news_order_no'] = (int)$data['news_order_no'];
        }

        //Main Img
        $image_primary = addslashes(Request::get('image_primary', ''));
        //Other Img
        $arrInputImgOther = array();
        $getImgOther = Request::get('img_other',array());
        if(!empty($getImgOther)){
            foreach($getImgOther as $k=>$val){
                if($val !=''){
                    $arrInputImgOther[] = $val;
                }
            }
        }
        if (!empty($arrInputImgOther) && count($arrInputImgOther) > 0) {
            //Neu Ko chon Anh Chinh, Lay Anh Chinh La Cai Dau Tien
            $data['news_image'] = ($image_primary != '') ? $image_primary : $arrInputImgOther[0];
            $data['news_image_other'] = serialize($arrInputImgOther);
        }

        if ($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {

                if (News::updateItem($id, $data)) {
                    return Redirect::route('admin.newsView');
                }
            } else {
                if (News::createItem($data)) {
                    return Redirect::route('admin.newsView');
                }
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['news_status']) ? $data['news_status'] : CGlobal::status_show);
        $optionType = FunctionLib::getOption($this->arrTypeNews, isset($data['news_type']) ? $data['news_type'] : Define::news_type_new);
        $optionCategoryNew = FunctionLib::getOption($this->arrCategoryNew, isset($data['news_category']) ? $data['news_category'] : CGlobal::status_hide);

        $this->viewPermission = $this->getPermissionPage();
        return view('news.News.add', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
            'optionStatus' => $optionStatus,
            'optionType' => $optionType,
            'optionCategoryNew' => $optionCategoryNew
        ], $this->viewPermission));
    }


    /*********************************************************************************************************************************
     * @return Sản phẩm
     *********************************************************************************************************************************/
    public function viewProduct()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $pageNo = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = 200;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['news_title'] = addslashes(Request::get('news_title', ''));
        $search['news_status'] = (int)Request::get('news_status', -1);
        $search['news_category'] = Define::news_type_product;
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $dataSearch = News::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['news_status']);

        $this->viewPermission = $this->getPermissionPage();
        return view('news.News.viewProduct', array_merge([
            'data' => $dataSearch,
            'search' => $search,
            'arrCategoryNew' => $this->arrCategoryNew,
            'arrTypeNews' => $this->arrTypeNews,
            'total' => $total,
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'optionStatus' => $optionStatus,
        ], $this->viewPermission));
    }
    public function getItemProduct($ids)
    {
        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full_product, $this->permission) && !in_array($this->permission_edit_product, $this->permission) && !in_array($this->permission_create_product, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($id > 0) {
            $data = News::find($id);
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['news_status']) ? $data['news_status'] : CGlobal::status_show);
        $optionType = FunctionLib::getOption($this->arrTypeNews, isset($data['news_type']) ? $data['news_type'] : Define::news_type_product);
        $optionCategoryNew = FunctionLib::getOption($this->arrCategoryNewProduct, isset($data['news_category']) ? $data['news_category'] : CGlobal::status_hide);

        $this->viewPermission = $this->getPermissionPage();
        return view('news.News.addProduct', array_merge([
            'data' => $data,
            'id' => $id,
            'optionStatus' => $optionStatus,
            'optionType' => $optionType,
            'optionCategoryNew' => $optionCategoryNew,
        ], $this->viewPermission));
    }
    public function postItemProduct($ids)
    {
        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full_product, $this->permission) && !in_array($this->permission_edit_product, $this->permission) && !in_array($this->permission_create_product, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        $data['news_type'] = Define::news_type_product;
        if ($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                //cap nhat
                if (News::updateItem($id, $data)) {
                    return Redirect::route('admin.newsViewProduct');
                }
            } else {
                //them moi
                if (News::createItem($data)) {
                    return Redirect::route('admin.newsViewProduct');
                }
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['news_status']) ? $data['news_status'] : CGlobal::status_show);
        $optionType = FunctionLib::getOption($this->arrTypeNews, isset($data['news_type']) ? $data['news_type'] : Define::news_type_product);
        $optionCategoryNew = FunctionLib::getOption($this->arrCategoryNewProduct, isset($data['news_category']) ? $data['news_category'] : CGlobal::status_hide);

        $this->viewPermission = $this->getPermissionPage();
        return view('news.News.addProduct', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
            'optionStatus' => $optionStatus,
            'optionType' => $optionType,
            'optionCategoryNew' => $optionCategoryNew
        ], $this->viewPermission));
    }

    public function deleteNews()
    {
        $data = array('isIntOk' => 0);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_delete, $this->permission) && !in_array($this->permission_full_product, $this->permission) && !in_array($this->permission_edit_product, $this->permission) && !in_array($this->permission_create_product, $this->permission)) {
            return Response::json($data);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && News::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    private function valid($data = array())
    {
        if (!empty($data)) {
            if (isset($data['news_title']) && trim($data['news_title']) == '') {
                $this->error[] = 'Tiêu đề tin bài không được bỏ trống';
            }
            if (isset($data['news_category']) && (int)trim($data['news_category']) <= 0) {
                $this->error[] = 'Chưa chọn danh mục';
            }
        }
        return true;
    }
}
