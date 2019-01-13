<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Shop;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class Order extends BaseModel
{
    protected $table = TABLE_ORDER;
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('order_id','order_product_id',
        'order_customer_name','order_customer_phone', 'order_customer_email', 'order_customer_address','order_customer_note',
        'order_product_id', 'order_total_money','order_total_buy','order_money_ship',
        'order_is_cod','order_user_shipper_id', 'order_user_shipper_name',
        'order_user_shop_id', 'order_user_shop_name',
        'order_status','order_type', 'order_note', 'order_time_pay',
        'order_time_creater','order_time_update');

    public function createItem($data)
    {
        try {
            $fieldInput = $this->checkFieldInTable($data);
            $item = new Order();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            //$member_id = app(User::class)->getMemberIdUser();
            //$item->member_id = $member_id;
            $item->save();

            self::removeCache($item->order_id, $item);
            return $item->id;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function updateItem($id, $data)
    {
        try {
            $fieldInput = $this->checkFieldInTable($data);
            //$member_id = app(User::class)->getMemberIdUser();
            $item = self::getItemById($id);
           // if ($item && isset($item->member_id) && $item->member_id == $member_id) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                //$item->member_id = $member_id;
                $item->update();
                self::removeCache($item->order_id, $item);
            //}
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function getItemById($id)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ORDER_ID . $id) : false;
        if ($data || $data->count() == 0) {
            $data = Order::find($id);
            if ($data) {
                Cache::put(Memcache::CACHE_ORDER_ID . $id, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            $item = $dataOld = self::getItemById($id);
            if ($item) {
                $item->delete();
            }
            self::removeCache($id, $dataOld);
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
            return false;
        }
    }

    public function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_ORDER_ID . $id);
        }
        if ($data) {
        }
    }

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total)
    {
        try {
            $query = Order::where('order_id', '>', 0);
            if (isset($dataSearch['provider_name']) && $dataSearch['provider_name'] != '') {
                $query->where('provider_name', 'LIKE', '%' . $dataSearch['provider_name'] . '%');
            }

            if (isset($dataSearch['order_id']) && $dataSearch['order_id'] > 0) {
                $query->where('order_id', $dataSearch['order_id']);
            }
            if (isset($dataSearch['member_id']) && $dataSearch['member_id'] > 0) {
                $query->where('member_id', $dataSearch['member_id']);
            }
            $total = $query->count();
            $query->orderBy('order_status', 'desc');

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if (!empty($fields)) {
                $result = $query->take($limit)->skip($offset)->get($fields);
            } else {
                $result = $query->take($limit)->skip($offset)->get();
            }
            return $result;

        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function getOrderShopByID($id, $member_id)
    {
        $provider = Order::getItemById($id);
        if (sizeof($provider) > 0) {
            if ($provider->member_id == $member_id) {
                return $provider;
            }
        }
        return array();
    }

    public function getOrderAll()
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ALL_PROVIDER) : array();
        if (sizeof($data) == 0) {
            $provider = Order::where('order_id', '>', 0)->get();
            foreach ($provider as $itm) {
                $data[$itm['order_id']] = $itm['provider_name'];
            }
            if (!empty($data) && Memcache::CACHE_ON) {
                Cache::put(Memcache::CACHE_ALL_PROVIDER, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function getListOrderByMemberId($member_id = 0)
    {
        $provider = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_LIST_PROVIDER_BY_MEMBER_ID . $member_id) : array();
        if (sizeof($provider) == 0) {
            $data = ($member_id == 0) ? Order::where('member_id', '>', $member_id)->get() : Order::where('member_id', '=', $member_id)->get();
            if (count($data) > 0) {
                foreach ($data as $itm) {
                    $provider[$itm->order_id] = $itm->provider_name;
                }
                if ($provider && Memcache::CACHE_ON) {
                    Cache::put(Memcache::CACHE_LIST_PROVIDER_BY_MEMBER_ID . $member_id, $provider, CACHE_ONE_MONTH);
                }
                return $provider;
            }
        }
        return $provider;
    }
}
