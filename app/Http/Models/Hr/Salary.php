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

class Salary extends BaseModel
{
    protected $table = Define::TABLE_HR_SALARY;
    protected $primaryKey = 'salary_id';
    public $timestamps = false;

    protected $fillable = array('salary_project', 'salary_person_id', 'salary_month', 'salary_year', 'salary_percent',
        'salary_salaries', 'salary_wage_table', 'salary_civil_servants','salary_tariffs', 'salary_wage', 'salary_coefficients', 'salary_note', 'salary_file_attach'
        , 'salary_executance', 'salary_money_insurrance', 'salary_money_allowance');

    public static function getLastItem(){
        $salary = Salary::where('salary_id','>', 0)->orderBy('salary_id', 'DESC')->first();
        return $salary;
    }

    public static function getSalaryByPersonId($person_id){
        if ($person_id > 0) {
            $result = Salary::where('salary_person_id', $person_id)
                ->orderBy('salary_id', 'ASC')->get();
            return $result;
        }
        return array();
    }

    public static function getSalaryByPersonIdAndYear($person_id=0, $year=0){
        if ($person_id > 0 && $year > 0) {
            $result = Salary::where('salary_person_id', $person_id)
                    ->where('salary_year', $year)
                    ->orderBy('salary_id', 'DESC')->first();
            return $result;
        }
        return array();
    }

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Salary();
            $fieldInput = $checkData->checkField($data);
            $item = new Salary();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }

            $item->salary_executance = ((int)$item->salary_percent > 0 && (int)$item->salary_salaries) ?(($item->salary_salaries*$item->salary_percent)/100): $item->salary_salaries;
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->salary_project = $user_project;
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->salary_id,$item);
            return $item->salary_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Salary();
            $fieldInput = $checkData->checkField($data);
            $item = Salary::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->salary_executance = ((int)$item->salary_percent > 0 && (int)$item->salary_salaries) ?(($item->salary_salaries*$item->salary_percent)/100): $item->salary_salaries;
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->salary_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->salary_id,$item);
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
            $item = Salary::find($id);
            if($item){
                $checkData = new Salary();
                $checkData->removeFile($item);
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->salary_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public function removeFile($data){
        $aryImages = unserialize($data->salary_file_attach);
        if(is_array($aryImages) && count($aryImages) > 0) {
            $folder_image = 'uploads/'.Define::FOLDER_SALARY;
            $folder_thumb = 'uploads/thumbs/'.Define::FOLDER_SALARY;
            foreach ($aryImages as $k => $nameImage) {
                FunctionLib::unlinkFileAndFolder($nameImage, $folder_image, true, $data->salary_id);
                FunctionLib::unlinkFileAndFolder($nameImage, $folder_thumb, true, $data->salary_id);
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
            $query = Salary::where('salary_id','>',0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('salary_project', $user_project );
            }
            $total = $query->count();
            $query->orderBy('salary_id', 'desc');

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
