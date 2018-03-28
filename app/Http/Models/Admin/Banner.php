<?php
namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use Illuminate\Support\Facades\Cache;

class Banner extends BaseModel{
    protected $table = Define::TABLE_WEB_BANNER;
    protected $primaryKey = 'banner_id';
    public $timestamps = false;

    protected $fillable = array('banner_name','banner_image', 'banner_image_temp', 'banner_link',
        'banner_order', 'banner_total_click', 'banner_time_click', 'banner_is_target', 'banner_is_rel', 'banner_type',
        'banner_page','banner_category_id','banner_status','banner_is_run_time',
        'banner_start_time','banner_end_time','banner_is_shop','banner_shop_id','banner_create_time','banner_update_time');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Banner();
            $fieldInput = $checkData->checkField($data);
            $item = new Banner();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->banner_id,$item);
            return $item->banner_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Banner();
            $fieldInput = $checkData->checkField($data);
            $item = Banner::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->banner_id,$item);
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
            $item = Banner::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->banner_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Banner::where('banner_id','>',0);
            if (isset($dataSearch['banner_name']) && $dataSearch['banner_name'] != '') {
                $query->where('banner_name','LIKE', '%' . $dataSearch['banner_name'] . '%');
            }if (isset($dataSearch['banner_page']) && $dataSearch['banner_page'] > 0) {
                $query->where('banner_page', $dataSearch['banner_page'] );
            }if (isset($dataSearch['banner_status']) && $dataSearch['banner_status'] != -2) {
                $query->where('banner_status', $dataSearch['banner_status'] );
            }if (isset($dataSearch['banner_type']) && $dataSearch['banner_type'] >0 ) {
                $query->where('banner_type',$dataSearch['banner_type']);
            }
            $total = $query->count();
            $query->orderBy('banner_id', 'asc');

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
        $query = Banner::where('banner_id','>',0);
        $query->where('role_status','=', Define::STATUS_SHOW);
        $list = $query->orderBy('role_order','ASC')->get();
        return $list;
    }

    public static function getOptionBanner() {
        $data = Cache::get(Define::CACHE_OPTION_ROLE);
        if (sizeof($data) == 0) {
            $arr =  Banner::getListAll();
            foreach ($arr as $value){
                $data[$value->banner_id] = $value->role_name;
            }
            if(!empty($data)){
                Cache::put(Define::CACHE_OPTION_ROLE, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }
}
