<?php
/**Hiep*/

namespace App\Http\Models\Shop;

use App\Http\Models\BaseModel;
use App\Library\AdminFunction\Memcache;
use Illuminate\Support\Facades\Cache;

class Provider extends BaseModel
{

    protected $table = TABLE_PROVIDER;
    protected $primaryKey = 'provider_id';
    public $timestamps = false;
    protected $fillable = array('member_id','provider_name','provider_phone','provider_address','provider_email','provider_shop_id','provider_shop_name','provider_status','provider_note','created_at','updated_at');

    //, 'position', 'url_image' sau banner_status
    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, $is_total = true)
    {
        try {
            $query = Provider::where('provider_id', '>', 0);
            if (isset($dataSearch['provider_name']) && $dataSearch['provider_name'] != '') {
                $query->where('provider_name', 'LIKE', '%' . $dataSearch['provider_name'] . '%');
            }
            if (isset($dataSearch['provider_status']) && $dataSearch['provider_status'] > -1) {
                $query->where('provider_status', $dataSearch['provider_status']);
            }
            $total = ($is_total) ? $query->count() : 0;
            $query->orderBy('provider_id', 'desc');

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if (!empty($fields)) {
                $result = $query->take($limit)->skip($offset)->get($fields);
            } else {
                $result = $query->take($limit)->skip($offset)->get();
            }
            return ['data' => $result, 'total' => $total];

        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function createItem($data)
    {
        try {
            $fieldInput = $this->checkFieldInTable($data);
            $item = new Provider();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                $item->save();
                self::removeCache($item->provider_id, $item);
                return $item->provider_id;
            }
            return false;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function updateItem($id, $data)
    {
        try {
            $fieldInput = $this->checkFieldInTable($data);
            if(empty($fieldInput))
                return false;
            $item = self::getItemById($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            self::removeCache($item->provider_id, $item);
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function getItemById($id)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_PROVIDER_ID . $id) : false;
        if (!$data) {
            $data = Provider::find($id);
            if ($data) {
                Cache::put(Memcache::CACHE_PROVIDER_ID . $id, $data, CACHE_THREE_MONTH);
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
                self::removeCache($id, $dataOld);
            }
            return true;
        } catch (PDOException $e) {
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

        }
    }

}
