<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 09/2018
* @Version   : 1.0
*/
namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class Department extends BaseModel{
    protected $table = TABLE_DEPARTMENT;
    protected $primaryKey = 'department_id';
    public $timestamps = true;
    protected $fillable = array('department_id', 'department_name', 'department_alias', 'department_order', 'department_status', 'created_at', 'updated_at');

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total){
        try {
            $query = Department::where('department_id', '>', 0);
            if (isset($dataSearch['department_name']) && $dataSearch['department_name'] != '') {
                $query->where('department_name', 'LIKE', '%' . $dataSearch['department_name'] . '%');
            }
            if (isset($dataSearch['department_status']) && $dataSearch['department_status'] > -1) {
                $query->where('department_status', $dataSearch['department_status']);
            }
            $total = $query->count();
            $query->orderBy('department_order', 'asc');

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
    public function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $item = new Department();
            if(is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_id, $item);
            return $item->department_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public function updateItem($id, $data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $item = self::getItemById($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public function getItemById($id){
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_DEPARTMENT_ID.$id): [];
        if (sizeof($data) == 0) {
            $data = Department::find($id);
            if($data){
                Cache::put(Memcache::CACHE_DEPARTMENT_ID.$id, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }
    public function deleteItem($id){
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
    public function removeCache($id = 0, $data){
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_DEPARTMENT_ID.$id);
        }
    }
}
