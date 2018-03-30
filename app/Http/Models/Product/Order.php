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

class Order extends BaseModel
{
    protected $table = Define::TABLE_ORDER;
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('order_id','order_project','order_product_id',
        'order_customer_name','order_customer_phone', 'order_customer_email', 'order_customer_address','order_customer_note',
        'order_product_id', 'order_total_money','order_total_buy','order_money_ship',
        'order_is_cod','order_user_shipper_id', 'order_user_shipper_name',
        'order_user_shop_id', 'order_user_shop_name',
        'order_status','order_type', 'order_note', 'order_time_pay',
        'order_time_creater','order_time_update');

    public function orderItem(){
        return $this->hasMany('OrderItem','order_id');
    }

    public static function getOrderById($id){
        try {
            $orders = Order::find($id);
            if ($orders) {
                $orders->orderitem;
                return $orders;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            throw new PDOException();
            return false;
        }
    }

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Order();
            $fieldInput = $checkData->checkField($data);
            $item = new Order();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->order_id,$item);
            return $item->order_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Order();
            $fieldInput = $checkData->checkField($data);
            $item = Order::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->order_id,$item);
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
            $item = Order::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->order_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            //Cache::forget(Define::CACHE_PRO_CATEGORY_ID.$id);
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Order::where('order_id','>',0);
            if (isset($dataSearch['product_name']) && $dataSearch['product_name'] != '') {
                $query->where('product_name','LIKE', '%' . $dataSearch['product_name'] . '%');
            }

            $total = $query->count();
            $query->orderBy('order_id', 'ASC');

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
