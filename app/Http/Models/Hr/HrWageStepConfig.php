<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/
namespace App\Http\Models\Hr;
use App\Http\Models\Admin\User;
use App\Http\Models\BaseModel;

use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HrWageStepConfig extends BaseModel{
    protected $table = Define::TABLE_HR_WAGE_STEP_CONFIG;
    protected $primaryKey = 'wage_step_config_id';
    public $timestamps = false;

    protected $fillable = array('wage_step_config_project', 'wage_step_config_parent_id', 'wage_step_config_name', 'wage_step_config_value', 'wage_step_config_type',
        'wage_step_config_order', 'wage_step_config_status', 'wage_step_config_month_salary_increase');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new HrWageStepConfig();
            $fieldInput = $checkData->checkField($data);
            $item = new HrWageStepConfig();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->wage_step_config_project = $user_project;
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->wage_step_config_id,$item->wage_step_config_type);
            return $item->wage_step_config_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new HrWageStepConfig();
            $fieldInput = $checkData->checkField($data);
            $item = HrWageStepConfig::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }

            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->wage_step_config_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->wage_step_config_id,$item->wage_step_config_type);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function getItemById($id=0){
        $result = (Define::CACHE_ON) ? Cache::get(Define::CACHE_HR_WAGE_STEP_CONFIG_ID . $id) : array();
        try {
            if (empty($result)) {
                $result = HrWageStepConfig::where('wage_step_config_id', $id)->first();
                if ($result && Define::CACHE_ON) {
                    Cache::put(Define::CACHE_HR_WAGE_STEP_CONFIG_ID . $id, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
            $item = HrWageStepConfig::find($id);
            $type = 0;
            if($item){
                $type = $item->wage_step_config_type;
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($id,$type);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public static function removeCache($id = 0,$type = 0){
        if($id > 0){
            Cache::forget(Define::CACHE_HR_WAGE_STEP_CONFIG_ID.$id);
        }
        if($type > 0){
            Cache::forget(Define::CACHE_WAGE_STEP_CONFIG_TYPE.$type);
        }
    }
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = HrWageStepConfig::where('wage_step_config_id','>',0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('wage_step_config_project', $user_project );
            }
            if (isset($dataSearch['wage_step_config_name']) && $dataSearch['wage_step_config_name'] != '') {
                $query->where('wage_step_config_name','LIKE', '%' . $dataSearch['wage_step_config_name'] . '%');
            }
            if (isset($dataSearch['wage_step_config_status']) && $dataSearch['wage_step_config_status'] != -2) {
                $query->where('wage_step_config_status',$dataSearch['wage_step_config_status']);
            }
            if (isset($dataSearch['wage_step_config_type']) && $dataSearch['wage_step_config_type'] != -1) {
                $query->where('wage_step_config_type',$dataSearch['wage_step_config_type']);
            }
            if (isset($dataSearch['order_sort_wage_step_config_id']) && $dataSearch['order_sort_wage_step_config_id'] == 'asc') {
                $query->orderBy('wage_step_config_id', 'asc');
            }else{
                $query->orderBy('wage_step_config_id', 'desc');
            }

            $total = $query->count();

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if($limit > 0){
                $query->take($limit);
            }
            if($offset > 0){
                $query->skip($offset);
            }
            if(!empty($fields)){
                $result = $query->get($fields);
            }else{
                $result = $query->get();
            }
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }

    public static function getArrayByType($config_type = 0){
        $results = (Define::CACHE_ON)? Cache::get(Define::CACHE_WAGE_STEP_CONFIG_TYPE.$config_type):array();
        if (sizeof($results) == 0) {
            if($config_type > 0){
                $result = HrWageStepConfig::where('wage_step_config_id','>', 0)
                            ->where('wage_step_config_type', $config_type)
                            ->where('wage_step_config_status', Define::STATUS_SHOW)->get();
                if(sizeof($result) > 0){
                    foreach($result as $item){
                        $results[$item->wage_step_config_id] = $item->wage_step_config_name;
                    }
                }
                if(!empty($results)){
                    Cache::put(Define::CACHE_WAGE_STEP_CONFIG_TYPE.$config_type, $results, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        }
        return $results;
    }

    public static function getDataOption($object_id,$config_type = 0){
        $results = array();
        if($object_id > 0 && $config_type > 0){
            $result = HrWageStepConfig::where('wage_step_config_id','>', 0)
                ->where('wage_step_config_status', Define::STATUS_SHOW)
                ->where('wage_step_config_type', $config_type)
                ->where('wage_step_config_parent_id', $object_id)
                ->get();
            if(sizeof($result) > 0){
                foreach($result as $item){
                    $results[$item->wage_step_config_id] = $item->wage_step_config_name;
                }
            }
        }
        return $results;
    }
}
