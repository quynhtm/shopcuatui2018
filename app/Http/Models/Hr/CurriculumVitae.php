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

class CurriculumVitae extends BaseModel
{
    protected $table = Define::TABLE_HR_CURRICULUM_VITAE;
    protected $primaryKey = 'curriculum_id';
    public $timestamps = false;
    protected $fillable = array('curriculum_project', 'curriculum_person_id', 'curriculum_type',
        'curriculum_month_in', 'curriculum_month_out','curriculum_year_in', 'curriculum_year_out',
        'curriculum_address_train', 'curriculum_classic', 'curriculum_formalities_id', 'curriculum_certificate_id', 'curriculum_training_id',
        'curriculum_formalities_name','curriculum_certificate_name','curriculum_training_name',
        'curriculum_name','curriculum_chibo','curriculum_chucvu_id','curriculum_cap_uykiem',
        'curriculum_desc_history1','curriculum_desc_history2','curriculum_foreign_relations1','curriculum_foreign_relations2');

    public static function getCurriculumVitaeByType($person_id, $type = 0)
    {
        if ($person_id > 0 && $type > 0) {
            $result = CurriculumVitae::where('curriculum_type', $type)
                ->where('curriculum_person_id', $person_id)
                ->orderBy('curriculum_id', 'ASC')->get();
            return $result;
        }
        return array();
    }

    public static function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new CurriculumVitae();
            $fieldInput = $checkData->checkField($data);
            $item = new CurriculumVitae();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->curriculum_project = $user_project;
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->curriculum_id, $item);
            return $item->curriculum_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id, $data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new CurriculumVitae();
            $fieldInput = $checkData->checkField($data);
            $item = CurriculumVitae::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->curriculum_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->curriculum_id, $item);
            return true;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function checkField($dataInput)
    {
        $fields = $this->fillable;
        $dataDB = array();
        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (isset($dataInput[$field])) {
                    $dataDB[$field] = $dataInput[$field];
                }
            }
        }
        return $dataDB;
    }

    public static function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = CurriculumVitae::find($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->curriculum_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            //Cache::forget(Define::CACHE_CATEGORY_ID.$id);
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total)
    {
        try {
            $query = CurriculumVitae::where('curriculum_id', '>', 0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('curriculum_project', $user_project );
            }
            $total = $query->count();
            $query->orderBy('curriculum_id', 'desc');

            //get field can lay du lieu
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
    public static function checkCurriculumVitaeByType($person_id, $type = 0){
        if ($person_id > 0 && $type > 0) {
            $result = CurriculumVitae::where('curriculum_type', $type)
                ->where('curriculum_person_id', $person_id)
                ->orderBy('curriculum_id', 'ASC')->first();
            return $result;
        }
        return array();
    }
}
