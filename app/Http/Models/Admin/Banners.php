<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class Banners extends BaseModel
{
    protected $table = TABLE_BANNER;
    protected $primaryKey = 'banner_id';
    public $timestamps = false;
    protected $fillable = array('banner_name', 'banner_image','banner_link' , 'banner_status', 'created_at', 'updated_at');

    //, 'position', 'url_image' sau banner_status
    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, $is_total = true)
    {
        try {
            $query = Banners::where('banner_id', '>', 0);
            if (isset($dataSearch['banner_name']) && $dataSearch['banner_name'] != '') {
                $query->where('banner_name', 'LIKE', '%' . $dataSearch['banner_name'] . '%');
            }
            if (isset($dataSearch['banner_status']) && $dataSearch['banner_status'] > -1) {
                $query->where('banner_status', $dataSearch['banner_status']);
            }
            $total = ($is_total) ? $query->count() : 0;
            $query->orderBy('banner_id', 'desc');

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
            $item = new Banners();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                $item->save();
                self::removeCache($item->banner_id, $item);
                return $item->banner_id;
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
            self::removeCache($item->banner_id, $item);
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function getItemById($id)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_BANNER_ID . $id) : false;
        if (!$data) {
            $data = Banners::find($id);
            if ($data) {
                Cache::put(Memcache::CACHE_BANNER_ID . $id, $data, CACHE_THREE_MONTH);
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
            Cache::forget(Memcache::CACHE_BANNER_ID . $id);
        }
        if ($data) {

        }
    }
}
