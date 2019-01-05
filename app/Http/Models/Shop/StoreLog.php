<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Shop;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class StoreLog extends BaseModel
{
    protected $table = TABLE_PRODUCT_STORAGE;
    protected $primaryKey = 'storage_log_id';
    public $timestamps = true;
    protected $fillable = array('storage_id', 'member_id', 'number_item', 'type_action', 'note',
        'created_at','updated_at','user_id_creater','user_name_creater');

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, $is_total = true)
    {
        try {
            $query = StoreLog::where('storage_log_id', '>', 0);
            if (isset($dataSearch['user_name_creater']) && $dataSearch['user_name_creater'] != '') {
                $query->where('user_name_creater', 'LIKE', '%' . $dataSearch['user_name_creater'] . '%');
            }
            if (isset($dataSearch['storage_id']) && $dataSearch['storage_id'] > -1) {
                $query->where('storage_id', $dataSearch['storage_id']);
            }
            if (isset($dataSearch['member_id']) && $dataSearch['member_id'] > -1) {
                $query->where('member_id', $dataSearch['member_id']);
            }
            if (isset($dataSearch['type_action']) && $dataSearch['type_action'] > -1) {
                $query->where('type_action', $dataSearch['type_action']);
            }
            $total = ($is_total)?$query->count():0;
            $query->orderBy('storage_log_id', 'desc');

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if (!empty($fields)) {
                $result = $query->take($limit)->skip($offset)->get($fields);
            } else {
                $result = $query->take($limit)->skip($offset)->get();
            }
            return ['data'=>$result,'total'=>$total];

        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $item = new StoreLog();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->user_id_creater = app(User::class)->user_id();
            $item->user_name_creater = app(User::class)->user_name();
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->storage_log_id, $item);
            return $item->storage_log_id;
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
            self::removeCache($item->storage_log_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = $dataOld = self::getItemById($id);
            if ($item) {
                $item->delete();
                self::removeCache($id, $dataOld);
            }
            DB::connection()->getPdo()->commit();

            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public function getItemById($id) {
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_PRODUCT_STORAGE_ID.$id):false;
        if (!$data) {
            $data = StoreLog::find($id);
            if($data){
                Cache::put(Memcache::CACHE_PRODUCT_STORAGE_ID.$id, $data, CACHE_THREE_MONTH);
            }
        }
        return $data;
    }

    public function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_PRODUCT_STORAGE_ID.$id);
        }
        if($data){

        }
    }
}
