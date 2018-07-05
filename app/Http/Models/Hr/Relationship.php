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

class Relationship extends BaseModel
{
    protected $table = Define::TABLE_HR_RELATIONSHIP;
    protected $primaryKey = 'relationship_id';
    public $timestamps = false;

    protected $fillable = array('relationship_project', 'relationship_person_id', 'relationship_define_id', 'relationship_define_name', 'relationship_year_birth',
        'relationship_describe', 'relationship_human_name');

    public static function getRelationshipByPersonId($person_id)
    {
        if ($person_id > 0) {
            $result = Relationship::where('relationship_person_id', $person_id)
                ->orderBy('relationship_id', 'ASC')->get();
            return $result;
        }
        return array();
    }

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Relationship();
            $fieldInput = $checkData->checkField($data);
            $item = new Relationship();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->relationship_project = $user_project;
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->relationship_id,$item);
            return $item->relationship_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Relationship();
            $fieldInput = $checkData->checkField($data);
            $item = Relationship::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }

            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->relationship_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->relationship_id,$item);
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
            $item = Relationship::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->relationship_id,$item);
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
            $query = Relationship::where('relationship_id','>',0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('relationship_project', $user_project );
            }
            $total = $query->count();
            $query->orderBy('relationship_id', 'desc');

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
