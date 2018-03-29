<?php
namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use Illuminate\Support\Facades\Cache;

class InfoSite extends BaseModel{
    protected $table = Define::TABLE_INFO;
    protected $primaryKey = 'info_id';
    public $timestamps = false;

    protected $fillable = array('info_title', 'info_keyword', 'info_intro',
        'info_content', 'info_img', 'info_created', 'info_order', 'info_status', 'meta_title',
        'meta_keywords','meta_description');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new InfoSite();
            $fieldInput = $checkData->checkField($data);
            $item = new InfoSite();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->info_id,$item);
            return $item->info_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new InfoSite();
            $fieldInput = $checkData->checkField($data);
            $item = InfoSite::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->info_id,$item);
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
            $item = InfoSite::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->info_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = InfoSite::where('info_id','>',0);
            if (isset($dataSearch['info_title']) && $dataSearch['info_title'] != '') {
                $query->where('info_title','LIKE', '%' . $dataSearch['info_title'] . '%');
            }if (isset($dataSearch['info_status']) && $dataSearch['info_status'] != -2) {
                $query->where('info_status', $dataSearch['info_status'] );
            }
            $total = $query->count();
            $query->orderBy('info_id', 'asc');

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
        Cache::forget(Define::CACHE_OPTION_ROLE);
    }

    public static function getListAll() {
        $query = InfoSite::where('info_id','>',0);
        $query->where('role_status','=', Define::STATUS_SHOW);
        $list = $query->orderBy('role_order','ASC')->get();
        return $list;
    }

    public static function getOptionInfoSite() {
        $data = Cache::get(Define::CACHE_OPTION_ROLE);
        if (sizeof($data) == 0) {
            $arr =  InfoSite::getListAll();
            foreach ($arr as $value){
                $data[$value->info_id] = $value->role_name;
            }
            if(!empty($data)){
                Cache::put(Define::CACHE_OPTION_ROLE, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }
}
