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

class Bonus extends BaseModel
{
    protected $table = Define::TABLE_HR_BONUS;
    protected $primaryKey = 'bonus_id';
    public $timestamps = false;

    protected $fillable = array('bonus_project', 'bonus_person_id', 'bonus_type', 'bonus_define_id', 'bonus_define_name',
        'bonus_year', 'bonus_note', 'bonus_decision', 'bonus_number', 'bonus_file_attack');

    public static function getBonusByType($person_id, $type = 0)
    {
        if ($person_id > 0 && $type > 0) {
            $result = Bonus::where('bonus_type', $type)
                ->where('bonus_person_id', $person_id)
                ->orderBy('bonus_id', 'ASC')->get();
            return $result;
        }
        return array();
    }

    public static function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Bonus();
            $fieldInput = $checkData->checkField($data);
            $item = new Bonus();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->bonus_project = $user_project;
            }

            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->bonus_id, $item);
            return $item->bonus_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id, $data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Bonus();
            $fieldInput = $checkData->checkField($data);
            $item = Bonus::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->bonus_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->bonus_id, $item);
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
            $item = Bonus::find($id);
            if ($item) {
                $checkData = new Bonus();
                $checkData->removeFile($item);
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->bonus_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public function removeFile($data){
        $aryImages = unserialize($data->bonus_file_attack);
        if(is_array($aryImages) && count($aryImages) > 0) {
            $folder_image = 'uploads/'.Define::FOLDER_BONUS;
            $folder_thumb = 'uploads/thumbs/'.Define::FOLDER_BONUS;
            foreach ($aryImages as $k => $nameImage) {
                FunctionLib::unlinkFileAndFolder($nameImage, $folder_image, true, $data->bonus_id);
                FunctionLib::unlinkFileAndFolder($nameImage, $folder_thumb, true, $data->bonus_id);
            }
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
            $query = Bonus::where('bonus_id', '>', 0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('bonus_project', $user_project );
            }
            $total = $query->count();
            $query->orderBy('bonus_id', 'desc');

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
}
