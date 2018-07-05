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

class Retirement extends BaseModel
{
    protected $table = Define::TABLE_HR_RETIREMENT;
    protected $primaryKey = 'retirement_id';
    public $timestamps = false;

    protected $fillable = array('retirement_project', 'retirement_person_id','retirement_file_attack', 'retirement_date_creater', 'retirement_date_notification', 'retirement_date',
        'retirement_note', 'retirement_position_define_id');
    public static function getRetirementByPersonId($retirement_person_id)
    {
        $data = Retirement::where('retirement_person_id', $retirement_person_id)->first();
        return $data;
    }
    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Retirement();
            $fieldInput = $checkData->checkField($data);
            $item = new Retirement();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->retirement_project = $user_project;
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            $checkData->dataSynPerson($item);
            self::removeCache($item->retirement_id,$item);
            return $item->retirement_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Retirement();
            $fieldInput = $checkData->checkField($data);
            $item = Retirement::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }

            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->retirement_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            $checkData->dataSynPerson($item);
            self::removeCache($item->retirement_id,$item);
            return true;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function dataSynPerson($retirement)
    {
        if (isset($retirement->retirement_person_id) && $retirement->retirement_person_id > 0) {
            $person = Person::find((int)$retirement->retirement_person_id);
            if (isset($person->person_id)) {
                $dataUpdate['retirement_date'] = $retirement->retirement_date;
                Person::updateItem($person->person_id, $dataUpdate);
            }
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
            $item = Retirement::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->retirement_id,$item);
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
            $query = Retirement::where('retirement_id','>',0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('retirement_project', $user_project );
            }
            $total = $query->count();
            $query->orderBy('retirement_id', 'desc');

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
