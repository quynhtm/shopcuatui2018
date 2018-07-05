<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/

namespace App\Http\Models\Hr;

use App\Http\Models\BaseModel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\library\AdminFunction\FunctionLib;

class HrDefine extends BaseModel
{

    protected $table = Define::TABLE_HR_DEFINE;
    protected $primaryKey = 'define_id';
    public $timestamps = false;

    protected $fillable = array('define_project', 'define_name', 'define_type', 'define_order', 'define_status',
        'creater_time', 'user_id_creater', 'user_name_creater', 'update_time', 'user_id_update', 'user_name_update');

    public static function insertMultiple($dataInput)
    {
        $define = new HrDefine();
        $str_sql = $define->buildSqlInsertMultiple(Define::TABLE_HR_DEFINE, $dataInput);
        if (trim($str_sql) != '') {
            DB::statement($str_sql);
            return true;
        } else {
            return false;
        }
    }

    public function buildSqlInsertMultiple($table, $arrInput)
    {
        if (!empty($arrInput)) {
            $arrSql = array();
            $arrField = array_keys($arrInput[0]);
            foreach ($arrInput as $k => $row) {
                $strVals = '';
                foreach ($arrField as $key => $field) {
                    $strVals .= "'" . trim($row[$field]) . '\',';
                }
                if ($strVals != '')
                    $strVals = rtrim($strVals, ',');
                if ($strVals != '')
                    $arrSql[] = '(' . $strVals . ')';
            }

            $fields = implode(',', $arrField);
            if (!empty($arrSql)) {
                $query = 'INSERT INTO `' . $table . '` (' . $fields . ') VALUES ' . implode(',', $arrSql);
                return $query;
            }
        }
        return '';
    }

    public static function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new HrDefine();
            $fieldInput = $checkData->checkField($data);
            $item = new HrDefine();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->define_id, $item);
            return $item->define_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id, $data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new HrDefine();
            $fieldInput = $checkData->checkField($data);
            $item = HrDefine::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->define_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function getItemById($id = 0)
    {
        $result = (Define::CACHE_ON) ? Cache::get(Define::CACHE_HR_DEFINED_ID . $id) : array();
        try {
            if (empty($result)) {
                $result = HrDefine::where('define_id', $id)->first();
                if ($result && Define::CACHE_ON) {
                    Cache::put(Define::CACHE_HR_DEFINED_ID . $id, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (PDOException $e) {
            throw new PDOException();
        }
        return $result;
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
            $item = HrDefine::find($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->define_id, $item);
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
            Cache::forget(Define::CACHE_HR_DEFINED_ID . $id);
        }
        Cache::forget(Define::CACHE_DEFINED_ALL);
    }

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total)
    {
        try {
            $query = HrDefine::where('define_id', '>', 0);
            if (isset($dataSearch['define_name']) && $dataSearch['define_name'] != '') {
                $query->where('define_name', 'LIKE', '%' . $dataSearch['define_name'] . '%');
            }
            if (isset($dataSearch['define_type']) && $dataSearch['define_type'] != -1) {
                $query->where('define_type', $dataSearch['define_type']);
            }
            $total = $query->count();
            $query->orderBy('define_order', 'asc');

            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if ($limit > 0) {
                $query->take($limit);
            }
            if ($offset > 0) {
                $query->skip($offset);
            }
            if (!empty($fields)) {
                $result = $query->get($fields);
            } else {
                $result = $query->get();
            }
            return $result;

        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    //QuynhTM edit
    public static function getArrayByType($define_type = 0)
    {
        $listDefine = self::getDataDefine();
        $result = ($define_type > 0 && isset($listDefine[$define_type])) ? $listDefine[$define_type] : array();
        return $result;
    }

    //QuynhTM add
    public static function getDataDefine()
    {
        $result = (Define::CACHE_ON) ? Cache::get(Define::CACHE_DEFINED_ALL) : array();
        try {
            if (empty($result)) {
                $listItem = HrDefine::where('define_status', Define::STATUS_SHOW)
                    ->orderBy('define_order', 'ASC')->get();
                foreach ($listItem as $item) {
                    $result[$item->define_type][$item->define_id] = $item->define_name;
                }
                if ($result && Define::CACHE_ON) {
                    Cache::put(Define::CACHE_DEFINED_ALL, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (PDOException $e) {
            throw new PDOException();
        }
        return $result;
    }
}
