<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class Product extends BaseModel
{
    protected $table = TABLE_PRODUCT;
    protected $primaryKey = 'product_id';
    public $timestamps = true;

    //cac truong trong DB
    protected $fillable = array('product_id','product_code', 'product_name', 'category_name', 'depart_id','category_id','provider_id',
        'product_price_sell', 'product_price_market', 'product_price_input', 'product_price_provider_sell','product_type_price','product_selloff',
        'product_is_hot', 'product_sort_desc', 'product_content','product_image','product_image_hover','product_image_other',
        'product_order', 'quality_input','quality_out','product_status','is_block','is_sale',
        'user_shop_id', 'user_shop_name', 'is_shop','province_id',
        'created_at','user_id_creater','user_name_creater',
        'updated_at','user_id_update','user_name_update', 'product_note');


    /**
     * @param $shop_id
     * @param $id
     * @return array
     */
    public  function getProductByShopId($shop_id,$product_id) {
        if($product_id > 0){
            $product = Product::getItemById($product_id);
            if (sizeof($product) > 0) {
                if(isset($product->user_shop_id) && (int)$product->user_shop_id == $shop_id){
                    return $product;
                }
            }
        }
        return array();
    }

    public function getListProductOfShopId($shop_id = 0, $field_get = array()) {
        if($shop_id > 0){
            $query = Product::where('user_shop_id','=',$shop_id);
            return $result = (!empty($field_get)) ? $query->get($field_get) : $query->get();
        }
        return array();
    }

    public function getProductByArrayProId($arrProId = array(),$field_get = array()) {
        if(!empty($arrProId)){
            $query = Product::where('product_id','>',0);
            $query->where('product_status','=',CGlobal::status_show);
            $query->where('is_block','=',CGlobal::PRODUCT_NOT_BLOCK);
            $query->whereIn('product_id',$arrProId);
            return $result = (!empty($field_get)) ? $query->get($field_get) : $query->get();
        }
        return array();
    }

    public function getProductHomeByDepartId($depart_id = 0,$field_get = array()) {
        if(!empty($depart_id > 0)){
            $limit = 8;
            $offset = 0;
            $query = Product::where('product_id','>',0);
            $query->where('product_status',CGlobal::status_show);
            $query->where('is_block',CGlobal::PRODUCT_NOT_BLOCK);
            $query->where('depart_id',$depart_id);
            $query->orderBy('time_update', 'desc')->orderBy('product_id', 'desc');
            $result = (!empty($field_get)) ? $query->take($limit)->skip($offset)->get($field_get) :  $query->take($limit)->skip($offset)->get();
            return ($result) ? $result: array();
        }
        return array();
    }

    public function getProductForSite($dataSearch = array(), $limit =0, $offset = 0, &$total){
        try{
            $query = Product::where('product_id','>',0);
            $query->where('product_status','=',CGlobal::status_show);
            $query->where('is_block','=',CGlobal::PRODUCT_NOT_BLOCK);
            //Duy add: get list product in array id
            if (isset($dataSearch['product_id'])) {
                if (is_array($dataSearch['product_id'])) {
                    $query->whereIn('product_id', $dataSearch['product_id']);
                }
                elseif ((int)$dataSearch['product_id'] > 0) {
                    $query->where('product_id','=', (int)$dataSearch['product_id']);
                }
            }
            if (isset($dataSearch['product_name']) && $dataSearch['product_name'] != '') {
                $query->where('product_name','LIKE', '%' . $dataSearch['product_name'] . '%');
            }
            if (isset($dataSearch['category_id'])) {
                if (is_array($dataSearch['category_id'])) {//tim theo m?ng id danh muc
                    $query->whereIn('category_id', $dataSearch['category_id']);
                }
                elseif ((int)$dataSearch['category_id'] > 0) {//theo id danh muc
                    $query->where('category_id','=', (int)$dataSearch['category_id']);
                }
            }

            if (isset($dataSearch['category_parent_id']) && $dataSearch['category_parent_id'] > 0) {
                $arrCatId = array();
                $arrChildCate = Category::getAllChildCategoryIdByParentId($dataSearch['category_parent_id']);
                if(!empty($arrChildCate)){
                    $arrCatId = array_keys($arrChildCate);
                }
                $query->whereIn('category_id', $arrCatId);
            }

            if (isset($dataSearch['user_shop_id']) && $dataSearch['user_shop_id'] != 0) {
                $query->where('user_shop_id','=', $dataSearch['user_shop_id']);
            }

            if (isset($dataSearch['depart_id']) && $dataSearch['depart_id'] > 0) {
                $query->where('depart_id','=', $dataSearch['depart_id']);
            }
            if (isset($dataSearch['product_is_hot']) && $dataSearch['product_is_hot'] != -1) {
                $query->where('product_is_hot','=', $dataSearch['product_is_hot']);
            }

            if (isset($dataSearch['shop_province']) && $dataSearch['shop_province'] != -1) {
                $query->where('shop_province','=', $dataSearch['shop_province']);
            }
            //l?y kh�c shop id n�y
            if (isset($dataSearch['shop_id_other']) && $dataSearch['shop_id_other'] > 0) {
                $query->where('user_shop_id','<>', $dataSearch['shop_id_other']);
            }

            //1: shop free, 2: shop thuong: 3 shop VIP
            if (isset($dataSearch['is_shop'])) {
                if (is_array($dataSearch['is_shop'])) {
                    $query->whereIn('is_shop', $dataSearch['is_shop']);
                }
                elseif ((int)$dataSearch['is_shop'] > 0) {
                    $query->where('is_shop', (int)$dataSearch['is_shop']);
                }
            }
            $total = $query->count();
            $query->orderBy('time_update', 'desc');

            //get field can lay du lieu
            $str_field_product_get = 'product_id,product_name,category_id,category_name,product_image,product_image_hover,product_status,product_price_sell,product_price_market,product_type_price,product_selloff,user_shop_id,user_shop_name,is_shop,is_block';//cac truong can lay
            $fields_get = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '')?trim($dataSearch['field_get']) : $str_field_product_get;
            $fields = (trim($fields_get) != '') ? explode(',',trim($fields_get)): array();
            if(!empty($fields)){
                $result = $query->take($limit)->skip($offset)->get($fields);
            }else{
                $result = $query->take($limit)->skip($offset)->get();
            }
            return $result;
        }catch (PDOException $e){
            throw new PDOException();
        }
    }

    public function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Product::where('product_id','>',0);
            if (isset($dataSearch['product_name']) && $dataSearch['product_name'] != '') {
                $query->where('product_name','LIKE', '%' . $dataSearch['product_name'] . '%');
            }
            if (isset($dataSearch['product_id']) && $dataSearch['product_id'] != 0) {
                $query->where('product_id', $dataSearch['product_id']);
            }
            if (isset($dataSearch['is_block']) && $dataSearch['is_block'] != -1) {
                $query->where('is_block', $dataSearch['is_block']);
            }
            if (isset($dataSearch['product_status']) && $dataSearch['product_status'] != -1) {
                $query->where('product_status', $dataSearch['product_status']);
            }
            if (isset($dataSearch['category_id']) && $dataSearch['category_id'] != -1) {
                $query->where('category_id', $dataSearch['category_id']);
            }
            if (isset($dataSearch['provider_id']) && $dataSearch['provider_id'] != -1) {
                $query->where('provider_id', $dataSearch['provider_id']);
            }
            if (isset($dataSearch['user_shop_id']) && $dataSearch['user_shop_id'] != 0) {
                $query->where('user_shop_id', $dataSearch['user_shop_id']);
            }
            if (isset($dataSearch['user_id_creater']) && $dataSearch['user_id_creater'] != 0) {
                $query->where('user_id_creater', $dataSearch['user_id_creater']);
            }
            if (isset($dataSearch['user_id']) && $dataSearch['user_id'] != 0) {
                $query->where('user_id_creater', $dataSearch['user_id']);
            }
            if (isset($dataSearch['depart_id']) && $dataSearch['depart_id'] > 0) {
                $query->where('depart_id','=', $dataSearch['depart_id']);
            }
            if (isset($dataSearch['not_product_id']) && $dataSearch['not_product_id'] > 0) {
                $query->whereNotIn('product_id',array( $dataSearch['not_product_id']));
            }

            if (isset($dataSearch['user_shop_id'])) {
                if (is_array($dataSearch['user_shop_id'])) {
                    $query->whereIn('user_shop_id', $dataSearch['user_shop_id']);
                }
                elseif ((int)$dataSearch['user_shop_id'] > 0) {
                    $query->where('user_shop_id','=', (int)$dataSearch['user_shop_id']);
                }
            }
            if (isset($dataSearch['product_id'])) {
                if (is_array($dataSearch['product_id'])) {
                    $query->whereIn('product_id', $dataSearch['product_id']);
                }
                elseif ((int)$dataSearch['product_id'] > 0) {
                    $query->where('product_id','=', (int)$dataSearch['product_id']);
                }
            }

            if (isset($dataSearch['product_is_hot']) && $dataSearch['product_is_hot'] > 0) {
                $query->where('product_is_hot', $dataSearch['product_is_hot']);
            }
            //lay theo id SP truyen vào và sap xep theo vi tri đã truyề vào
            if(isset($dataSearch['str_product_id']) && $dataSearch['str_product_id'] != ''){
                $arrProductId = explode(',', trim($dataSearch['str_product_id']));
                $query->whereIn('product_id', $arrProductId);
                //$query->orderBy('product_id', 'desc');
                $query->orderByRaw(DB::raw("FIELD(product_id, ".trim($dataSearch['str_product_id'])." )"));

            }else{
                $orderBy = 'desc';
                if(isset($dataSearch['orderBy']) && $dataSearch['orderBy'] !=''){
                    $orderBy = $dataSearch['orderBy'];
                }
                $query->orderBy('product_id', $orderBy);
            }

            $total = $query->count();
            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if(!empty($fields)){
                $result = $query->take($limit)->skip($offset)->get($fields);
            }else{
                $result = $query->take($limit)->skip($offset)->get();
            }
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }

    public function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $item = new Banners();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->banner_id, $item);
            return $item->id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function updateItem($id, $data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $item = self::getItemById($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->banner_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function getItemById($id) {
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_PRODUCT_ID.$id): [];
        if (sizeof($data) == 0) {
            $data = Banners::find($id);
            if($data){
                Cache::put(Memcache::CACHE_PRODUCT_ID.$id, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = self::getItemById($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_PRODUCT_ID.$id);
        }
    }
}
