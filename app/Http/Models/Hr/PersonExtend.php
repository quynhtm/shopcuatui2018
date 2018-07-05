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

class PersonExtend extends BaseModel
{
    protected $table = Define::TABLE_HR_PERSON_EXTEND;
    protected $primaryKey = 'person_extend_id';
    public $timestamps = false;

    protected $fillable = array('person_extend_project', 'person_extend_person_id', 'person_extend_chucvu_hiennay', 'person_extend_chucvu_kiemnhiem',
        'person_extend_chucdanh_khcn', 'person_extend_capuy_hiennay', 'person_extend_capuy_kiemnhiem', 'person_extend_thanhphan_giadinh', 'person_extend_nghenghiep_hiennay', 'person_extend_ngaytuyendung',
        'person_extend_name_company', 'person_extend_ngaylamviec', 'person_extend_ngaythamgia_cachmang', 'person_extend_ngayvaodang', 'person_extend_ngayvaodang_chinhthuc', 'person_extend_ngaythamgia_tochuc',
        'person_extend_ngaynhapngu', 'person_extend_ngayxuatngu', 'person_extend_chucvu_quanngu', 'person_extend_trinhdo_hocvan', 'person_extend_hoc_ham',
        'person_extend_namdat_hoc_ham', 'person_extend_hoc_vi', 'person_extend_namdat_hoc_vi', 'person_extend_lyluan_chinhtri', 'person_extend_namdat_lyluan_chinhtri',
        'person_extend_language_1', 'person_extend_trinhdo_1', 'person_extend_language_2', 'person_extend_trinhdo_2', 'person_extend_language_3', 'person_extend_trinhdo_3', 'person_extend_language_4', 'person_extend_trinhdo_4',
        'person_extend_congtac_danglam', 'person_extend_sotruong_congtac', 'person_extend_congviec_launhat', 'person_extend_trinhdo_quanly_nhanuoc',
        'person_extend_namdat_qlnn', 'person_extend_trinhdo_tinhoc', 'person_extend_namdat_tinhoc', 'person_extend_is_dangvien', 'person_extend_thuongbinh', 'person_extend_giadinh_chinhsach'
    );

    public static function getPersonExtendByPersonId($person_extend_person_id)
    {
        $data = PersonExtend::where('person_extend_person_id', $person_extend_person_id)->first();
        return $data;
    }

    public static function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new PersonExtend();
            $fieldInput = $checkData->checkField($data);
            $item = new PersonExtend();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->person_extend_project = $user_project;
            }
            $item->save();
            DB::connection()->getPdo()->commit();
            $checkData->dataSynPerson($item);
            self::removeCache($item->person_extend_id, $item);
            return $item->person_extend_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id, $data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new PersonExtend();
            $fieldInput = $checkData->checkField($data);
            $item = PersonExtend::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }

            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->person_extend_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            $checkData->dataSynPerson($item);
            self::removeCache($item->person_extend_id, $item);
            return true;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function dataSynPerson($personExtend)
    {
        if (isset($personExtend->person_extend_person_id) && $personExtend->person_extend_person_id > 0) {
            $person = Person::find((int)$personExtend->person_extend_person_id);
            if (isset($person->person_id)) {
                $dataUpdate['person_extend_trinhdo_hocvan'] = $personExtend->person_extend_trinhdo_hocvan;
                $dataUpdate['person_is_dangvien'] = $personExtend->person_extend_is_dangvien;
                $dataUpdate['person_ngayvao_dang'] = ($personExtend->person_extend_is_dangvien == 1) ? $personExtend->person_extend_ngayvaodang : 0;
                Person::updateItem($person->person_id, $dataUpdate);
            }
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
            $item = PersonExtend::find($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->person_extend_id, $item);
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
            $query = PersonExtend::where('person_extend_id', '>', 0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('person_extend_project', $user_project );
            }
            $total = $query->count();
            $query->orderBy('person_extend_id', 'desc');

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
