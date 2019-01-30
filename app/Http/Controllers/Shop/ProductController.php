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
    private $arrStatus = array();

    private $arrProducttype = array();
    private $arrProductTypePrice = array();
    private $arrProductSale = array();

//E245030

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
        $search['user_shop_name'] = (int)Request::get('user_shop-name', -1);
        $search['category_id'] = (int)Request::get('category_id', -1);
        $search['depart_id'] = (int)Request::get('depart_id', -1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//các trường cần lấy

        $data = app(Product::class)->searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';
        $optionStatus = getOption($this->arrStatus, $search['product_status']);
        $optionProducttype = getOption($this->arrProducttype, $search['product_is_hot']);

        /*lấy tên chuyên mục category*/
        $dataProductCategory = app(Category::class)->searchByCondition($search, $limit, $offset);
        $arrProductCategory = app(Product::class)->getAllDataFromCategory($dataProductCategory['data']);
        $optionCategory = getOption($arrProductCategory, $search['category_id']);

        /*lấy tên danh mục depart*/
        $dataProductDepart = app(Department::class)->searchByCondition($search, $limit, $offset);
        $arrProductDepart = app(Product::class)->getAllDataFromDepart($dataProductDepart['data']);
        $optionDepart = getOption($arrProductDepart, $search['depart_id']);

        /*lấy tên nhà cung cấp provider*/
        $dataProductProvider = app(Provider::class)->searchByCondition($search, $limit, $offset);
        $arrProductProvider = app(Product::class)->getAllDataFromProvider($dataProductProvider['data']);
        $optionProvider = getOption($arrProductProvider, $search['user_shop_name']);

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

    public function postItem($id = 0)
    {
        if (!$this->checkMultiPermiss([PERMISS_PRODUCT_FULL, PERMISS_PRODUCT_CREATE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;


            if (isset($_FILES['product_image']) && count($_FILES['product_image']) > 0 && $_FILES['product_image']['name'] != '') {
                $folder = 'product';
                $_max_file_size = 10 * 1024 * 1024;
                $_file_ext = 'jpg,jpeg,png,gif,JPG,PNG,GIF';
                $pathFileUpload = app(Upload::class)->uploadFile('product_image', $_file_ext, $_max_file_size, $folder);
                $data['product_image'] = trim($pathFileUpload) != '' ? $pathFileUpload : '';
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

    public function deleteProduct()
    {
        $data = array('isIntOk' => 0);
        if (!$this->checkMultiPermiss([PERMISS_PRODUCT_FULL, PERMISS_PRODUCT_DELETE])) {
            return Redirect::route('admin.dashboard', array('error' => ERROR_PERMISSION));
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && app(Product::class)->deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

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

    public function _outDataView($data)
    {
        $optionStatus = getOption($this->arrStatus, isset($data['product_status']) ? $data['product_status'] : STATUS_SHOW);
        $optionProducttype = getOption($this->arrProducttype, isset($data['product_is_hot']) ? $data['product_is_hot'] : STATUS_SHOW);
        $optionProductPrice = getOption($this->arrProductTypePrice, isset($data['product_type_price']) ? $data['product_type_price'] : STATUS_SHOW);
        $optionProducSale = getOption($this->arrProductSale, isset($data['is_sale']) ? $data['is_sale'] : STATUS_SHOW);

        $dataProductCategory = app(Category::class)->searchByCondition([], 50, 0);
        $arrProductCategory = app(Product::class)->getAllDataFromCategory($dataProductCategory['data']);
        $optionCategory = getOption($arrProductCategory, isset($data['category_id']) ? $data['category_id'] : STATUS_SHOW);

        $dataProductDepart = app(Department::class)->searchByCondition([], 50, 0);
        $arrProductDepart = app(Product::class)->getAllDataFromDepart($dataProductDepart['data']);
        $optionDepart = getOption($arrProductDepart, isset($data['depart_id']) ? $data['depart_id'] : STATUS_SHOW);

        $dataProductProvider = app(Provider::class)->searchByCondition([], 50, 0);
        $arrProductProvider = app(Product::class)->getAllDataFromProvider($dataProductProvider['data']);
        $optionProvider = getOption($arrProductProvider, isset($data['user_shop_name']) ? $data['user_shop_name'] : STATUS_SHOW);

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

    private function _validData($data = array())
    {
        if (!empty($data)) {
            if (isset($data['product_name']) && trim($data['product_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required',
            'product_image' => 'image|nullable|max:1999',
        ]);

        $product = new Product();

        $product->product_id = $request->input('product_id');
        $product->product_code = $request->input('product_code');
        $product->product_name = $request->input('product_name');
        $product->category_name = $request->input('category_name');
        $product->depart_id = $request->input('depart_id');
        $product->category_id = $request->input('category_id');
        $product->provider_id = $request->input('provider_id');
        $product->product_price_sell = $request->input('product_price_sell');
        $product->product_price_market = $request->input('product_price_market');
        $product->product_price_input = $request->input('product_price_input');
        $product->product_price_provider_sell = $request->input('product_price_provider_sell');
        $product->product_type_price = $request->input('product_type_price');
        $product->product_selloff = $request->input('product_selloff');
        $product->product_is_hot = $request->input('product_is_hot');
        $product->product_sort_desc = $request->input('product_sort_desc');
        $product->product_content = $request->input('product_content');
        $product->product_image = $request->input('product_image');
        $product->product_image_hover = $request->input('product_image_hover');
        $product->product_image_other = $request->input('product_image_other');
        $product->product_order = $request->input('quality_input');
        $product->quality_out = $request->input('quality_out');
        $product->product_status = $request->input('product_status');
        $product->is_block = $request->input('is_block');
        $product->is_sale = $request->input('is_sale');
        $product->user_shop_id = $request->input('user_shop_id');
        $product->user_shop_name = $request->input('user_shop_name');
        $product->is_shop = $request->input('is_shop');
        $product->province_id = $request->input('province_id');
        $product->product_note = $request->input('product_note');
        $product->save();

        // Save Images path and upload to storage
        if($request->hasFile('product_image')) {
            foreach($request->file('product_image') as $image) {
                $filenameWithExt = $image->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $image->getClientOriginalExtension();

                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                $path = $image->storeAs('public/cover_images', $fileNameToStore);

                $image = new Product([
                    'product_id' => $product->id,
                    'product_image' => $fileNameToStore,
                ]);

                $image->save();

                // Overide $product
                $product->cover_image = $image['path'];
                $product->save();
            }
        }else{
            $fileNameToStore = 'noimage.png';
            // Overide $product
            $product->cover_image = $fileNameToStore;
            $product->save();
        }

        return redirect('/')->with('success', 'Created Successfully');
    }

}