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

class Allowance extends BaseModel
{
    protected $table = Define::TABLE_HR_ALLOWANCE;
    protected $primaryKey = 'allowance_id';
    public $timestamps = false;

    protected $fillable = array('allowance_project', 'allowance_person_id', 'allowance_type', 'allowance_method_type', 'allowance_method_value', 'allowance_month_start',
        'allowance_year_start', 'allowance_month_end', 'allowance_year_end', 'allowance_note', 'allowance_file_attack');
    public static function getAllowanceByPersonId($person_id)
    {
        if ($person_id > 0) {
            $result = Allowance::where('allowance_person_id', $person_id)
                ->orderBy('allowance_id', 'ASC')->get();
            return $result;
        }
        return array();
    }

    public static function getAllowanceByInfoSalary($allowance_person_id, $allowance_month_start, $allowance_year_start)
    {
        return Allowance::where('allowance_person_id', $allowance_person_id)
            ->where('allowance_month_start', $allowance_month_start)
            ->where('allowance_year_start', $allowance_year_start)
            ->get();
    }

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Allowance();
            $fieldInput = $checkData->checkField($data);
            $item = new Allowance();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                $user_project = app(User::class)->get_user_project();
                if($user_project > 0){
                    $item->allowance_project = $user_project;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->allowance_id,$item);
            return $item->allowance_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Allowance();
            $fieldInput = $checkData->checkField($data);
            $item = Allowance::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->allowance_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->allowance_id,$item);
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
            $item = Allowance::find($id);
            if($item){
                $checkData = new Allowance();
                $checkData->removeFile($item);
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->allowance_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public function removeFile($data){
        $aryImages = unserialize($data->allowance_file_attack);
        if(is_array($aryImages) && count($aryImages) > 0) {
            $folder_image = 'uploads/'.Define::FOLDER_ALLOWANCE;
            $folder_thumb = 'uploads/thumbs/'.Define::FOLDER_ALLOWANCE;
            foreach ($aryImages as $k => $nameImage) {
                FunctionLib::unlinkFileAndFolder($nameImage, $folder_image, true, $data->allowance_id);
                FunctionLib::unlinkFileAndFolder($nameImage, $folder_thumb, true, $data->allowance_id);
            }
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            //Cache::forget(Define::CACHE_CATEGORY_ID.$id);
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Allowance::where('allowance_id','>',0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('allowance_project', $user_project );
            }
            if (isset($dataSearch['menu_name']) && $dataSearch['menu_name'] != '') {
                $query->where('menu_name','LIKE', '%' . $dataSearch['menu_name'] . '%');
            }
            $total = $query->count();
            $query->orderBy('allowance_id', 'desc');

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
