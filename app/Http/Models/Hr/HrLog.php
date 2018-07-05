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

class HrLog extends BaseModel
{
    protected $table = Define::TABLE_HR_LOG;
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = array('log_project', 'log_object', 'log_name', 'log_type', 'log_before',
        'log_after', 'log_time','log_user_id_creater','log_user_name_creater');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new HrLog();
            $fieldInput = $checkData->checkField($data);
            $item = new HrLog();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->log_project = $user_project;
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->log_id,$item);
            return $item->log_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new HrLog();
            $fieldInput = $checkData->checkField($data);
            $item = HrLog::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }

            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->log_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->log_id,$item);
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
            $item = HrLog::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->log_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            //Cache::forget(Define::CACHE_CATEGORY_ID.$id);
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = HrLog::where('log_id','>',0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('log_project', $user_project );
            }
            $total = $query->count();
            $query->orderBy('log_id', 'desc');

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
}
