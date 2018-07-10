<?php
/**
 * QuynhTM
 */
namespace App\Http\Models\Product;
use App\Http\Models\BaseModel;

use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;

class Product extends BaseModel
{
    protected $table = Define::TABLE_PRODUCT;
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('product_id','product_project','product_code', 'product_name', 'category_name', 'depart_id','product_id','provider_id',
        'product_price_sell', 'product_price_market', 'product_price_input', 'product_price_provider_sell','product_type_price','product_selloff',
        'product_is_hot', 'product_sort_desc', 'product_content','product_image','product_image_hover','product_image_other',
        'product_order', 'quality_input','quality_out','product_status','is_block','is_sale',
        'user_shop_id', 'user_shop_name', 'is_shop','province_id',
        'time_created','user_id_creater','user_name_creater',
        'time_update','user_id_update','user_name_update', 'product_note');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Product();
            $fieldInput = $checkData->checkField($data);
            $item = new Product();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->product_id,$item);
            return $item->product_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Product();
            $fieldInput = $checkData->checkField($data);
            $item = Product::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->product_id,$item);
            return true;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function checkField($dataInput) {
        $fields = $this->fillable;
        $dataDB = array();
        if(!empty($fields)) {
            foreach($fields as $field) {
                if(isset($dataInput[$field])) {
                    $dataDB[$field] = $dataInput[$field];
                }
            }
        }
        return $dataDB;
    }

    public static function deleteItem($id){
        if($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = Product::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->product_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public function removeCache($id = 0,$data){
        if($id > 0){
            //Cache::forget(Define::CACHE_PRO_CATEGORY_ID.$id);
        }
    }

    public function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Product::where('product_id','>',0);
            if (isset($dataSearch['product_name']) && $dataSearch['product_name'] != '') {
                $query->where('product_name','LIKE', '%' . $dataSearch['product_name'] . '%');
            }

            $total = $query->count();
            $query->orderBy('product_id', 'ASC');

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if(!empty($fields)){
                $result = $query->take($limit)->skip($offset)->get($fields);
            }else{
                $result = $query->take($limit)->skip($offset)->get();
            }
            //dd($query->toSql());
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }
}
