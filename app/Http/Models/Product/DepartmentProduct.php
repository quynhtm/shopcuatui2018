<?php
/**
 * QuynhTM
 */
namespace App\Http\Models\Product;
use App\Http\Models\BaseModel;

use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;

class DepartmentProduct extends BaseModel
{
    protected $table = Define::TABLE_PRO_DEPARTMENT;
    protected $primaryKey = 'department_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('department_project','department_name','department_alias','department_status','department_logo',
        'department_status_home','department_type','department_layouts', 'department_order');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new DepartmentProduct();
            $fieldInput = $checkData->checkField($data);
            $item = new DepartmentProduct();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_id,$item);
            return $item->department_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new DepartmentProduct();
            $fieldInput = $checkData->checkField($data);
            $item = DepartmentProduct::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_id,$item);
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
            $item = DepartmentProduct::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            //Cache::forget(Define::CACHE_PRO_CATEGORY_ID.$id);
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = DepartmentProduct::where('department_id','>',0);
            if (isset($dataSearch['department_name']) && $dataSearch['department_name'] != '') {
                $query->where('department_name','LIKE', '%' . $dataSearch['department_name'] . '%');
            }

            $total = $query->count();
            $query->orderBy('department_id', 'ASC');

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if(!empty($fields)){
                $result = $query->take($limit)->skip($offset)->get($fields);
            }else{
                $result = $query->take($limit)->skip($offset)->get();
            }
            //dd($query->toSql());
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }
}
