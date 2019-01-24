<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Shop\Category;
use App\Http\Models\Shop\Department;
use App\Http\Models\Shop\Product;
use App\Http\Models\Shop\Provider;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Upload;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class ProductController extends BaseAdminController
{
    private $error = array();
    private $viewOptionData = array();
    private $viewPermission = array();
    private $arrStatus =  array();

    private $arrProducttype = array();
    private $arrProductTypePrice = array();
    private $arrProductSale = array();

//E245030

    public function __construct()
    {
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý Sản phẩm';
    }

    public function _getDataDefault()
    {
        $this->arrStatus = array(
            STATUS_BLOCK => viewLanguage('status_choose'),
            STATUS_SHOW => viewLanguage('status_show'),
            STATUS_HIDE => viewLanguage('status_hidden'));

        //out put permiss
        $this->viewPermission = [
            'is_root' => $this->is_root,
            'permission_full' => $this->checkPermiss(PERMISS_PRODUCT_FULL),
            'permission_create' => $this->checkPermiss(PERMISS_PRODUCT_CREATE),
            'permission_delete' => $this->checkPermiss(PERMISS_PRODUCT_DELETE),
        ];

        $this->arrProducttype = [
            0 => '---Chọn Loại Sản Phẩm---',
            1 => 'Sản phẩm bình thường',
            2 => 'Sản phẩm nổi bật',
            3 => 'Sản phẩm giảm giá',
        ];

        $this->arrProductTypePrice = [
            0 => '---Kiểu hiển thị giá---',
            1 => 'Hiển thị giá số',
            2 => 'Hiển thị giá liên hệ',
        ];

        $this->arrProductSale = [
            0 => '---Tình trạng hàng---',
            1 => 'Còn hàng',
            2 => 'Hết hàng',
        ];
    }

    public function view()
    {
        //Check phan quyen.
        if (!$this->checkMultiPermiss([PERMISS_PRODUCT_FULL, PERMISS_PRODUCT_VIEW])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $this->_getDataDefault();
        $pageNo = (int)Request::get('page_no', 1);
        $limit = LIMIT_RECORD_30;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['product_name'] = addslashes(Request::get('product_name', ''));
        $search['product_status'] = (int)Request::get('product_status', -1);
        $search['product_is_hot'] = (int)Request::get('product_is_hot', -1);
        $search['user_shop_name'] = (int)Request::get('user_shop-name',-1);
        $search['category_id'] = (int)Request::get('category_id', -1);
        $search['depart_id'] = (int)Request::get('depart_id', -1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//các trường cần lấy

        $data = app(Product::class)->searchByCondition($search, $limit, $offset , $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total , $limit, $search) : '';
        $optionStatus = getOption($this->arrStatus, $search['product_status']);
        $optionProducttype = getOption($this->arrProducttype, $search['product_is_hot']);

/*lấy tên chuyên mục category*/
        $dataProductCategory = app(Category::class)->searchByCondition($search, $limit, $offset);
        $arrProductCategory=app(Product::class)->getAllDataFromCategory($dataProductCategory['data']);
        $optionCategory = getOption($arrProductCategory, $search['category_id']);

/*lấy tên danh mục depart*/
        $dataProductDepart = app(Department::class)->searchByCondition($search, $limit, $offset);
        $arrProductDepart = app(Product::class)->getAllDataFromDepart($dataProductDepart['data']);
        $optionDepart = getOption ($arrProductDepart, $search['depart_id']);

/*lấy tên nhà cung cấp provider*/
        $dataProductProvider = app(Provider::class)->searchByCondition($search,$limit ,$offset);
        $arrProductProvider = app(Product::class)->getAllDataFromProvider($dataProductProvider['data']);
        $optionProvider = getOption ($arrProductProvider , $search['user_shop_name']);

        $this->_getDataDefault();
        $this->_outDataView($search);

        return view('shop.ShopProduct.view', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($pageNo - 1) * $limit,
            'paging' => $paging,
            'optionStatus' => $optionStatus,
            'optionProducttype' => $optionProducttype,
            'optionProvider' => $optionProvider,
            'optionDepart' => $optionDepart,
            'optionCategory' => $optionCategory
        ], $this->viewPermission));

    }

    public function getItem($id)
    {
        if (!$this->checkMultiPermiss([PERMISS_PRODUCT_FULL, PERMISS_PRODUCT_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $data = (($id > 0)) ? app(Product::class)->getItemById($id) : [];

        $this->_getDataDefault();
        $this->_outDataView($data);
        return view('shop.ShopProduct.add', array_merge([
            'data' => $data,
            'id' => $id,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function postItem($id=0)
    {
        if (!$this->checkMultiPermiss([PERMISS_PRODUCT_FULL, PERMISS_PRODUCT_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        if(isset($_FILES['product_image']) && count($_FILES['product_image'])>0 && $_FILES['product_image']['name'] != '') {
            $folder = 'product';
            $_max_file_size = 10 * 1024 * 1024;
            $_file_ext = 'jpg,jpeg,png,gif';
            $pathFileUpload = app(Upload::class)->uploadFile('product_image', $_file_ext, $_max_file_size, $folder);
            $data['product_image'] = trim($pathFileUpload) != ''? $pathFileUpload: '';
        }

        if ($this->_validData($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;

            if ($id > 0) {
                //Cập nhật
                if (app(Product::class)->updateItem($id, $data)) {
                    return Redirect::route('shop.productView');
                }
            } else {
                //Thêm mới
                if (app(Product::class)->createItem($data)) {
                    return Redirect::route('shop.productView');
                }
            }
        }

        $this->_getDataDefault();
        $this->_outDataView($data);
        return view('shop.ShopProduct.add', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
        ], $this->viewPermission, $this->viewOptionData));
    }

    public function _outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus, isset($data['product_status']) ? $data['product_status'] : STATUS_SHOW);
        $optionProducttype = getOption($this->arrProducttype,isset($data['product_is_hot']) ? $data['product_is_hot'] : STATUS_SHOW);
        $optionProductPrice = getOption($this->arrProductTypePrice, isset($data['product_type_price']) ? $data['product_type_price'] : STATUS_SHOW );
        $optionProducSale = getOption($this->arrProductSale, isset($data['is_sale']) ? $data['is_sale'] : STATUS_SHOW );

        $dataProductCategory = app(Category::class)->searchByCondition([], 50, 0);
        $arrProductCategory = app(Product::class)->getAllDataFromCategory($dataProductCategory['data']);
        $optionCategory = getOption($arrProductCategory, isset($data['category_id']) ? $data['category_id'] : STATUS_SHOW );

        $dataProductDepart = app(Department::class)->searchByCondition([], 50, 0);
        $arrProductDepart = app(Product::class)->getAllDataFromDepart($dataProductDepart['data']);
        $optionDepart = getOption($arrProductDepart, isset($data['depart_id']) ? $data['depart_id'] : STATUS_SHOW );

        $dataProductProvider = app(Provider::class)->searchByCondition([],50 ,0);
        $arrProductProvider = app(Product::class)->getAllDataFromProvider($dataProductProvider['data']);
        $optionProvider = getOption ($arrProductProvider , isset($data['user_shop_name']) ? $data['user_shop_name'] : STATUS_SHOW );

        return $this->viewOptionData = [
            'optionStatus' => $optionStatus,
            'optionProducttype' => $optionProducttype,
            'optionProductPrice' => $optionProductPrice,
            'optionProducSale' => $optionProducSale,
            'optionCategory' => $optionCategory,
            'optionDepart' => $optionDepart,
            'optionProvider' => $optionProvider

        ];
    }

    public function deleteProduct()
    {
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_PRODUCT_FULL, PERMISS_PRODUCT_DELETE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id = (int)Request::get('id', 0 );
        if ($id > 0 && app(Product::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    private function _validData($data = array())
    {
        if (!empty($data)) {
            if (isset($data['product_name']) && trim($data['product_name']) == '') {                     
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
