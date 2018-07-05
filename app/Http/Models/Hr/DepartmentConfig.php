<?php
/**
 * QuynhTM
 */
namespace App\Http\Models\Hr;
use App\Http\Models\Admin\User;
use App\Http\Models\BaseModel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;

class DepartmentConfig extends BaseModel
{
    protected $table = Define::TABLE_HR_DEPARTMENT_CONFIG;
    protected $primaryKey = 'department_config_id';
    public $timestamps = false;

    protected $fillable = array('department_config_project', 'department_id', 'department_retired_age_min_girl', 'department_retired_age_max_girl', 'department_retired_age_min_boy','department_retired_age_max_boy',
        'month_regular_wage_increases','month_raise_the_salary_ahead_of_time','department_config_status');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new DepartmentConfig();
            $fieldInput = $checkData->checkField($data);
            $item = new DepartmentConfig();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->department_config_project = $user_project;
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_config_id,$item);
            return $item->department_config_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new DepartmentConfig();
            $fieldInput = $checkData->checkField($data);
            $item = DepartmentConfig::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }

            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->department_config_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_config_id,$item);
            return true;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function getItemById($id=0){
        $result = (Define::CACHE_ON) ? Cache::get(Define::CACHE_HR_DEPARTMENT_CONFIG_ID.$id) : array();
        try {
            if(empty($result)){
                $result = DepartmentConfig::where('department_config_id', $id)->first();
                if($result && Define::CACHE_ON){
                    Cache::put(Define::CACHE_HR_DEPARTMENT_CONFIG_ID.$id, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
            $item = DepartmentConfig::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_config_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            Cache::forget(Define::CACHE_HR_DEPARTMENT_CONFIG_ID.$id);
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = DepartmentConfig::where('department_config_id','>',0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('department_config_project', $user_project );
            }
            if (isset($dataSearch['department_id']) && $dataSearch['department_id'] != -1) {
                $query->where('department_id','=', $dataSearch['department_id']);
            }
            if (isset($dataSearch['department_config_status']) && $dataSearch['department_config_status'] != -1) {
                $query->where('department_config_status','=', $dataSearch['department_config_status']);
            }
            $total = $query->count();
            $query->orderBy('department_config_id', 'desc');

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
    public static function getItemByDepartmentId($department_id=0){
        $result = array();
        if($department_id > 0){
            $result = DepartmentConfig::where('department_id', $department_id)->first();
            if($result && Define::CACHE_ON){
                $id = $result->department_config_id;
                Cache::put(Define::CACHE_HR_DEPARTMENT_CONFIG_ID.$id, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $result;
    }
}
