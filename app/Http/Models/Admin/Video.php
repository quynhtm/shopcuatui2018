<?php
namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use App\Library\AdminFunction\FunctionLib;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use Illuminate\Support\Facades\Cache;

class Video extends BaseModel{
    protected $table = Define::TABLE_VIDEO;
    protected $primaryKey = 'video_id';
    public $timestamps = false;

    protected $fillable = array('video_project','video_name','video_name_alias', 'video_sort_desc', 'video_content',
        'video_link', 'video_file', 'video_img', 'video_img_temp', 'video_status', 'video_view',
        'video_hot','video_time_creater','video_category','type_language','video_time_update','video_meta_title','video_meta_keyword','video_meta_description');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Video();
            $fieldInput = $checkData->checkField($data);
            $item = new Video();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->video_id,$item);
            return $item->video_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Video();
            $fieldInput = $checkData->checkField($data);
            $item = Video::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->video_id,$item);
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
            $item = Video::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->video_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Video::where('video_id','>',0);
            if (isset($dataSearch['video_name']) && $dataSearch['video_name'] != '') {

                $query->where('video_name','LIKE', '%' . $dataSearch['video_name'] . '%');
            }
            if (isset($dataSearch['video_sort_desc']) && $dataSearch['video_sort_desc'] != '') {

                $query->where('video_sort_desc','LIKE', '%' . $dataSearch['video_sort_desc'] . '%');
            }
            if (isset($dataSearch['video_status']) && $dataSearch['video_status'] != -2) {

                $query->where('video_status', $dataSearch['video_status'] );
            }
            $total = $query->count();
            $query->orderBy('video_id', 'asc');

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
        $query = Video::where('video_id','>',0);
        $query->where('role_status','=', Define::STATUS_SHOW);
        $list = $query->orderBy('role_order','ASC')->get();
        return $list;
    }

    public static function getOptionVideo() {
        $data = Cache::get(Define::CACHE_OPTION_ROLE);
        if (sizeof($data) == 0) {
            $arr =  Video::getListAll();
            foreach ($arr as $value){
                $data[$value->video_id] = $value->role_name;
            }
            if(!empty($data)){
                Cache::put(Define::CACHE_OPTION_ROLE, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }
}
