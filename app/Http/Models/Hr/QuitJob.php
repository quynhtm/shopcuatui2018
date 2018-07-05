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

class QuitJob extends BaseModel
{
    protected $table = Define::TABLE_HR_QUIT_JOB;
    protected $primaryKey = 'quit_job_id';
    public $timestamps = false;

    protected $fillable = array('quit_job_project', 'quit_job_person_id','quit_job_file_attack', 'quit_job_type', 'quit_job_date_creater', 'quit_job_note','quit_job_depart_id');
    public static function getQuitJobByPersonId($quit_job_person_id,$quit_job_type)
    {
        $data = QuitJob::where('quit_job_person_id', $quit_job_person_id)
                        ->where('quit_job_type', $quit_job_type)->first();
        return $data;
    }

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new QuitJob();
            $fieldInput = $checkData->checkField($data);
            $item = new QuitJob();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->quit_job_project = $user_project;
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->quit_job_id,$item);
            return $item->quit_job_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new QuitJob();
            $fieldInput = $checkData->checkField($data);
            $item = QuitJob::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }

            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->quit_job_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->quit_job_id,$item);
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
            $item = QuitJob::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->quit_job_id,$item);
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
            $query = QuitJob::where('quit_job_id','>',0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('quit_job_project', $user_project );
            }
            $total = $query->count();
            $query->orderBy('quit_job_id', 'desc');

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
