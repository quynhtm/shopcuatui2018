<?php
/**
 * QuynhTM
 */
namespace App\Http\Models\Admin;
use App\Http\Models\BaseModel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;

class Cronjob extends BaseModel
{
    protected $table = Define::TABLE_CRONJOB;
    protected $primaryKey = 'cronjob_id';
    public $timestamps = false;

    protected $fillable = array('cronjob_project', 'cronjob_name', 'cronjob_router', 'cronjob_type', 'cronjob_date_run',
        'cronjob_number_plan', 'cronjob_number_running', 'cronjob_status', 'cronjob_result');

    public static function getListData(){
        $result = (Define::CACHE_ON)? Cache::get(Define::CACHE_ALL_CRONJOB):array();
        try {
            if (empty($result)) {
                $listItem = Cronjob::where('cronjob_status', Define::STATUS_SHOW)
                    ->orderBy('cronjob_id', 'ASC')->get();
                foreach ($listItem as $item) {
                    $result[$item->cronjob_id] = $item;
                }
                if ($result && Define::CACHE_ON) {
                    Cache::put(Define::CACHE_ALL_CRONJOB, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (PDOException $e) {
            throw new PDOException();
        }
        return $result;
    }

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Cronjob();
            $fieldInput = $checkData->checkField($data);
            $item = new Cronjob();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->cronjob_id,$item);
            return $item->cronjob_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Cronjob();
            $fieldInput = $checkData->checkField($data);
            $item = Cronjob::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->cronjob_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function getItemById($id=0){
        $result = (Define::CACHE_ON) ? Cache::get(Define::CACHE_CRONJOB_ID_ . $id) : array();
        try {
            if (empty($result)) {
                $result = Cronjob::where('cronjob_id', $id)->first();
                if ($result && Define::CACHE_ON) {
                    Cache::put(Define::CACHE_CRONJOB_ID_ . $id, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (PDOException $e) {
            throw new PDOException();
        }
        return $result;
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
            $item = Cronjob::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->cronjob_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            Cache::forget(Define::CACHE_CRONJOB_ID_.$id);
        }
        Cache::forget(Define::CACHE_ALL_CRONJOB);
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Cronjob::where('cronjob_id','>',0);
            if (isset($dataSearch['cronjob_name']) && $dataSearch['cronjob_name'] != '') {
                $query->where('cronjob_name','LIKE', '%' . $dataSearch['cronjob_name'] . '%');
            }
            if (isset($dataSearch['cronjob_status']) && $dataSearch['cronjob_status'] != -1) {
                $query->where('cronjob_status', $dataSearch['cronjob_status']);
            }

            $total = $query->count();
            $query->orderBy('cronjob_id', 'desc');

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
}
