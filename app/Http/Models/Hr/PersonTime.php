<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Hr;

use App\Http\Models\Admin\User;
use App\Http\Models\BaseModel;

use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;

class PersonTime extends BaseModel
{
    protected $table = Define::TABLE_HR_PERSONNEL_TIME;
    protected $primaryKey = 'person_time_id';
    public $timestamps = false;

    protected $fillable = array('person_time_project', 'person_time_person_id', 'person_time_type', 'person_time_day', 'person_time_month', 'person_time_year', 'person_time_year_now', 'person_time_year_next', 'person_time_full');

    public static function getPersonTimeByPersonId($person_time_person_id, $person_time_type)
    {
        $data = PersonTime::where('person_time_person_id', $person_time_person_id)
            ->where('person_time_type', $person_time_type)->first();
        return $data;
    }

    public static function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new PersonTime();
            $fieldInput = $checkData->checkField($data);
            $item = new PersonTime();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->person_time_id, $item);
            return $item->person_time_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id, $data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new PersonTime();
            $fieldInput = $checkData->checkField($data);
            $item = PersonTime::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->person_time_id, $item);
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
            $item = PersonTime::find($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->person_time_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function createDataPersonTime($person_id = 0, $dataInput)
    {
        if (!empty($dataInput) && $person_id > 0) {
            $ojb_time = new PersonTime();
            //NGÀY SINH NHẬT: hr_personnal
            if (isset($dataInput->person_birth) && abs($dataInput->person_birth) > 0) {
                $person_time_type = Define::PERSONNEL_TIME_TYPE_BIRTH;
                $ojb_time->pustDataInput($person_id, $dataInput->person_birth, $dataInput->person_project, $person_time_type);
            }

            //NGÀY ĐẾN HẠN TĂNG LƯƠNG ăn theo mã ngạch ở tăng lương: hr_personnal
            if (isset($dataInput->person_date_salary_increase) && abs($dataInput->person_date_salary_increase) > 0) {
                $person_time_type = Define::PERSONNEL_TIME_TYPE_DATE_SALARY_INCREASE;
                $ojb_time->pustDataInput($person_id, $dataInput->person_date_salary_increase, $dataInput->person_project, $person_time_type);
            }

            //NGÀY HẾT HẠN HỢP ĐỒNG: hr_personnal
            if (isset($dataInput->contracts_dealine_date) && abs($dataInput->contracts_dealine_date) > 0) {
                $person_time_type = Define::PERSONNEL_TIME_TYPE_CONTRACTS_DEALINE_DATE;
                $ojb_time->pustDataInput($person_id, $dataInput->contracts_dealine_date, $dataInput->person_project, $person_time_type);
            }
        }
    }

    public function pustDataInput($person_id, $time_int, $person_project, $person_time_type)
    {
        $dataTime = [];
        if ($person_id > 0 && $person_time_type > 0) {
            $dataTime['person_time_day'] = date('d', $time_int);
            $dataTime['person_time_month'] = date('m', $time_int);
            $dataTime['person_time_year'] = date('Y', $time_int);
            $dataTime['person_time_full'] = $time_int;
            $dataTime['person_time_year_now'] = date('Y', time()) . date('m', $time_int) . date('d', $time_int);
            $dataTime['person_time_year_next'] = (date('Y', time()) + 1) . date('m', $time_int) . date('d', $time_int);
            $dataTime['person_time_person_id'] = $person_id;
            $dataTime['person_time_type'] = $person_time_type;
            $dataTime['person_time_project'] = $person_project;
        }
        if (!empty($dataTime) && $person_time_type > 0) {
            $personTime = PersonTime::getPersonTimeByPersonId($person_id, $person_time_type);
            if (isset($personTime->person_time_id)) {
                PersonTime::updateItem($personTime->person_time_id, $dataTime);
            } else {
                PersonTime::createItem($dataTime);
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
            //FunctionLib::bug($dataSearch);
            $query = PersonTime::where('person_time_id', '>', 0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('person_time_project', $user_project );
            }
            if (isset($dataSearch['person_time_type']) && $dataSearch['person_time_type'] != 0) {
                $query->where('person_time_type', $dataSearch['person_time_type']);
            }

            if (isset($dataSearch['year_search']) && $dataSearch['year_search'] == 1) {
                if (isset($dataSearch['date_search_min']) && $dataSearch['date_search_min'] != '') {
                    $query->where('person_time_year_now', '>=', $dataSearch['date_search_min']);
                }
                if (isset($dataSearch['date_search_max']) && $dataSearch['date_search_max'] != '') {
                    $query->where('person_time_year_next', '<=', $dataSearch['date_search_max']);
                }
            } else {
                if (isset($dataSearch['date_search_min']) && $dataSearch['date_search_min'] != '') {
                    $query->where('person_time_year_now', '>=', $dataSearch['date_search_min']);
                }
                if (isset($dataSearch['date_search_max']) && $dataSearch['date_search_max'] != '') {
                    $query->where('person_time_year_now', '<=', $dataSearch['date_search_max']);
                }
            }

            //$total = $query->count();
            $query->orderBy('person_time_id', 'desc');

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

    public static function getListPersonIdByTypeTime($person_time_type, $number_date_run = Define::config_date_check_notify_7)
    {
        $checkTime = FunctionLib::checkConfigDateNotify(date('d', time()), date('m', time()), date('Y', time()), $number_date_run);
        $time_min = isset($checkTime['time_min']) ? $checkTime['time_min'] : '';
        $time_now = isset($checkTime['time_now']) ? $checkTime['time_now'] : '';
        $time_max = isset($checkTime['time_max']) ? $checkTime['time_max'] : '';

        $date_min = (int)date('Ymd', strtotime($time_min));
        $date_max = (int)date('Ymd', strtotime($time_max));
        $date_now = (int)date('Ymd', strtotime($time_now));

        $year_min = (int)date('Y', strtotime($time_min));
        $year_max = (int)date('Y', strtotime($time_max));
        if ($year_min !== $year_max) {
            $dataSearch['year_search'] = 1;
        }

        $dataSearch['date_search_min'] = $date_min;
        $dataSearch['date_search_max'] = $date_max;
        $dataSearch['person_time_type'] = $person_time_type;

        $listPerson = PersonTime::searchByCondition($dataSearch, CGlobal::number_show_1000, 0, $total);
        $arrPersonId = array();
        if (count($listPerson) > 0) {
            foreach ($listPerson as $item) {
                if ($date_now <= $item->person_time_year_now) {
                    $arrPersonId[$item->person_time_person_id] = $item->person_time_person_id;
                }
                /*if($year_min !== $year_max && $date_now <= $item->person_time_year_next){
                    $arrPersonId[$item->person_time_person_id] = $item->person_time_person_id;
                }*/
            }
        }
        return $arrPersonId;
    }
}
