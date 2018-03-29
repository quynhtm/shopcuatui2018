<?php
namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use Illuminate\Support\Facades\Cache;

class Member extends BaseModel{
    protected $table = Define::TABLE_MEMBER;
    protected $primaryKey = 'member_id';
    public $timestamps = false;

    protected $fillable = array('member_name','member_address', 'member_type','member_status', 'member_time_start','member_time_end', 'member_payment');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Member();
            $fieldInput = $checkData->checkField($data);
            $item = new Member();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->member_id,$item);
            return $item->member_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Member();
            $fieldInput = $checkData->checkField($data);
            $item = Member::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->member_id,$item);
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
            $item = Member::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->member_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Member::where('member_id','>',0);
            if (isset($dataSearch['member_name']) && $dataSearch['member_name'] != '') {
                $query->where('member_name','LIKE', '%' . $dataSearch['member_name'] . '%');
            }
            $total = $query->count();
            $query->orderBy('member_id', 'asc');

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
    public static function removeCache($id = 0,$data){
        if($id > 0){
            //Cache::forget(Define::CACHE_ROLE_ID.$id);
        }
        Cache::forget(Define::CACHE_OPTION_MEMBER);
        Cache::forget(Define::CACHE_MEMBER_LIST);
    }

    public static function getListAll() {
        $data = Cache::get(Define::CACHE_MEMBER_LIST);
        if (sizeof($data) == 0) {
            $query = Member::where('member_id','>',0);
            $query->where('member_status','=', Define::STATUS_SHOW);
            $list = $query->orderBy('member_id','ASC')->get();
            foreach ($list as $value){
                $data[$value->member_id] = $value;
            }
            if(!empty($data)){
                Cache::put(Define::CACHE_MEMBER_LIST, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

    public static function getOptionMember() {
        $data = Cache::get(Define::CACHE_OPTION_MEMBER);
        if (sizeof($data) == 0) {
            $arr =  Member::getListAll();
            foreach ($arr as $value){
                $data[$value->member_id] = $value->member_name;
            }
            if(!empty($data)){
                Cache::put(Define::CACHE_OPTION_MEMBER, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }
}
