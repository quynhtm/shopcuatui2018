<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class OrderItem extends BaseModel
{
    protected $table = TABLE_ORDER_ITEM;
    protected $primaryKey = 'order_item_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('order_item_id','order_id',
        'product_id', 'product_name', 'product_price_sell',
        'product_price_input', 'product_image', 'product_category_id',
        'product_category_name', 'product_type_price', 'product_province', 'product_provider', 'number_buy');

    public function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $item = new OrderItem();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $member_id = app(User::class)->getMemberIdUser();
            $item->member_id = $member_id;
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->order_item_id, $item);
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
            $member_id = app(User::class)->getMemberIdUser();
            $item = self::getItemById($id);
            if ($item && isset($item->member_id) && $item->member_id == $member_id) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                $item->member_id = $member_id;
                $item->update();
                self::removeCache($item->order_item_id, $item);
            }
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function getItemById($id)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_PROVIDER_ID . $id) : false;
        if ($data || $data->count() == 0) {
            $data = OrderItem::find($id);
            if ($data) {
                Cache::put(Memcache::CACHE_PROVIDER_ID . $id, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = $dataOld = self::getItemById($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($id, $dataOld);
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
            Cache::forget(Memcache::CACHE_PROVIDER_ID . $id);
        }
        if ($data) {
            Cache::forget(Memcache::CACHE_LIST_PROVIDER_BY_MEMBER_ID . $data->member_id);
        }
        Cache::forget(Memcache::CACHE_ALL_PROVIDER);
    }

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total)
    {
        try {
            $query = OrderItem::where('order_item_id', '>', 0);
            if (isset($dataSearch['provider_name']) && $dataSearch['provider_name'] != '') {
                $query->where('provider_name', 'LIKE', '%' . $dataSearch['provider_name'] . '%');
            }
            if (isset($dataSearch['provider_phone']) && $dataSearch['provider_phone'] != '') {
                $query->where('provider_phone', 'LIKE', '%' . $dataSearch['provider_phone'] . '%');
            }
            if (isset($dataSearch['provider_email']) && $dataSearch['provider_email'] != '') {
                $query->where('provider_email', 'LIKE', '%' . $dataSearch['provider_email'] . '%');
            }
            if (isset($dataSearch['provider_status']) && $dataSearch['provider_status'] != -1) {
                $query->where('provider_status', $dataSearch['provider_status']);
            }
            if (isset($dataSearch['member_id']) && $dataSearch['member_id'] != -1) {
                if ($dataSearch['member_id'] == 0) {
                    $query->where('member_id', '>=', $dataSearch['member_id']);
                } else {
                    $query->where('member_id', $dataSearch['member_id']);
                }
            }
            if (isset($dataSearch['order_item_id']) && $dataSearch['order_item_id'] > 0) {
                $query->where('order_item_id', $dataSearch['order_item_id']);
            }
            if (isset($dataSearch['member_id']) && $dataSearch['member_id'] > 0) {
                $query->where('member_id', $dataSearch['member_id']);
            }
            $total = $query->count();
            $query->orderBy('provider_status', 'desc');

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

    public function getOrderItemShopByID($id, $member_id)
    {
        $provider = OrderItem::getItemById($id);
        if (sizeof($provider) > 0) {
            if ($provider->member_id == $member_id) {
                return $provider;
            }
        }
        return array();
    }

    public function getOrderItemAll()
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ALL_PROVIDER) : array();
        if (sizeof($data) == 0) {
            $provider = OrderItem::where('order_item_id', '>', 0)->get();
            foreach ($provider as $itm) {
                $data[$itm['order_item_id']] = $itm['provider_name'];
            }
            if (!empty($data) && Memcache::CACHE_ON) {
                Cache::put(Memcache::CACHE_ALL_PROVIDER, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function getListOrderItemByMemberId($member_id = 0)
    {
        $provider = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_LIST_PROVIDER_BY_MEMBER_ID . $member_id) : array();
        if (sizeof($provider) == 0) {
            $data = ($member_id == 0) ? OrderItem::where('member_id', '>', $member_id)->get() : OrderItem::where('member_id', '=', $member_id)->get();
            if (count($data) > 0) {
                foreach ($data as $itm) {
                    $provider[$itm->order_item_id] = $itm->provider_name;
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
