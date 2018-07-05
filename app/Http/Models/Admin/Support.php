<?php
namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use Illuminate\Support\Facades\Cache;

class Support extends BaseModel{
    protected $table = Define::TABLE_SUPPORT;
    protected $primaryKey = 'support_id';
    public $timestamps = false;

    protected $fillable = array('support_name','support_content', 'support_reply_parent_id', 'support_user_ask',
        'support_user_reply', 'support_user_reply_project', 'support_create_time');

    public function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Support();
            $fieldInput = $checkData->checkField($data);
            $item = new Support();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->support_id,$item);
            return $item->support_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Support();
            $fieldInput = $checkData->checkField($data);
            $item = Support::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->support_id,$item);
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
    public function deleteItem($id){
        if($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = Support::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->support_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Support::where('support_id','>',0);
            if (isset($dataSearch['role_name']) && $dataSearch['role_name'] != '') {
                $query->where('role_name','LIKE', '%' . $dataSearch['role_name'] . '%');
            }
            $total = $query->count();
            $query->orderBy('role_order', 'asc');

            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if($limit > 0){
                $query->take($limit);
            }
            if($offset > 0){
                $query->skip($offset);
            }
            if(!empty($fields)){
                $result = $query->get($fields);
            }else{
                $result = $query->get();
            }
            return $result;
        }catch (PDOException $e){
            throw new PDOException();
        }
    }
    public function removeCache($id = 0,$data){
        if($id > 0){
            //Cache::forget(Define::CACHE_ROLE_ID.$id);
        }
        Cache::forget(Define::CACHE_OPTION_ROLE);
    }

    public function getListAll() {
        $query = Support::where('support_id','>',0);
        $query->where('role_status','=', Define::STATUS_SHOW);
        $list = $query->orderBy('role_order','ASC')->get();
        return $list;
    }

    public function getOptionRole() {
        $data = (Define::CACHE_ON)? Cache::get(Define::CACHE_OPTION_ROLE):array();
        if (sizeof($data) == 0) {
            $arr =  Support::getListAll();
            foreach ($arr as $value){
                $data[$value->support_id] = $value->role_name;
            }
            if(!empty($data)){
                Cache::put(Define::CACHE_OPTION_ROLE, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }
}
