<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use Illuminate\Support\Facades\Cache;

class Wards extends BaseModel{
    protected $table = Define::TABLE_WARDS;
    protected $primaryKey = 'wards_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('district_id','wards_name','wards_alias','wards_order','wards_status');

    public static function getWardsByDistrictId($district_id) {
        $data = (Define::CACHE_ON)? Cache::get(Define::CACHE_WARDS_WITH_DISTRICT_ID.$district_id) : array();
        if (sizeof($data) == 0) {
            $district = Wards::where('wards_id', '>', 0)
                ->where('district_id', '=',$district_id)
                ->where('wards_status', '=',Define::STATUS_SHOW)
                ->orderBy('wards_order', 'asc')->get();
            foreach($district as $itm) {
                $data[$itm['wards_id']] = $itm['wards_name'];
            }
            if(!empty($data) && Define::CACHE_ON){
                Cache::put(Define::CACHE_WARDS_WITH_DISTRICT_ID.$district_id, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Wards::where('wards_id','>',0);
            if (isset($dataSearch['district_name']) && $dataSearch['district_name'] != '') {
                $query->where('district_name','LIKE', '%' . $dataSearch['district_name'] . '%');
            }
            if (isset($dataSearch['wards_id']) && $dataSearch['wards_id'] > 0) {
                $query->where('wards_id', $dataSearch['wards_id']);
            }
            if (isset($dataSearch['district_city_id']) && $dataSearch['district_city_id'] > 0) {
                $query->where('district_city_id', $dataSearch['district_city_id']);
            }
            if (isset($dataSearch['district_status']) && $dataSearch['district_status'] > -1) {
                $query->where('district_status', $dataSearch['district_status']);
            }

            $total = $query->count();
            $query->orderBy('district_city_id', 'asc');

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
    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Role();
            $fieldInput = $checkData->checkField($data);
            $item = new Role();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->wards_id,$item);
            return $item->wards_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Role();
            $fieldInput = $checkData->checkField($data);
            $item = Role::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->wards_id,$item);
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
            $item = Role::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->wards_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            Cache::forget(Define::CACHE_WARDS_WITH_DISTRICT_ID.$data->district_id);
        }
    }

}