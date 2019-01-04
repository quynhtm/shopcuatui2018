<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class Wards extends BaseModel
{
    protected $table = TABLE_WARDS;
    protected $primaryKey = 'wards_id';
    public $timestamps = false;
    protected $fillable = array('wards_name', 'district_id', 'wards_status', 'wards_alias', 'wards_order',
    'user_id_creater','user_name_creater','user_id_update','user_name_update', 'created_at', 'updated_at');

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, $is_total = true)
    {
        try {
            $query = Wards::where('wards_id', '>', 0);
            if (isset($dataSearch['wards_name']) && $dataSearch['wards_name'] != '') {
                $query->where('wards_name', 'LIKE', '%' . $dataSearch['wards_name'] . '%');
            }
            if (isset($dataSearch['district_status']) && $dataSearch['district_status'] > -1) {
                $query->where('district_status', $dataSearch['district_status']);
            }
            $total = ($is_total) ? $query->count() : 0;
            $query->orderBy('wards_id', 'desc');

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
            $item = new Wards();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                $item->user_id_creater = app(User::class)->user_id();
                $item->user_name_creater = app(User::class)->user_name();
                $item->created_at = getCurrentFull();
                $item->save();
                self::removeCache($item->wards_id, $item);
                return $item->wards_id;
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
            $item->user_id_update = app(User::class)->user_id();
            $item->user_name_update = app(User::class)->user_name();
            $item->updated_at = getCurrentFull();
            $item->update();
            self::removeCache($item->wards_id, $item);
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
        }
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

    public function getItemById($id)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_WARDS_ID . $id) : false;
        if (!$data) {
            $data = Wards::find($id);
            if ($data) {
                Cache::put(Memcache::CACHE_WARDS_ID . $id, $data, CACHE_THREE_MONTH);
            }
        }
        return $data;
    }

    public function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_WARDS_ID . $id);
        }
        if ($data) {

        }
    }
}
