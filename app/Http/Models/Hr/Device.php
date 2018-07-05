<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/
namespace App\Http\Models\Hr;
use App\Http\Models\Admin\User;
use App\Http\Models\BaseModel;

use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Device extends BaseModel{
    protected $table = Define::TABLE_HR_DEVICE;
    protected $primaryKey = 'device_id';
    public $timestamps = false;

    protected $fillable = array('device_project', 'device_person_id', 'device_code', 'device_name', 'device_type',
        'device_depart_id', 'device_describe','device_image','device_infor_technical'
        , 'device_date_of_manufacture','device_date_warranty', 'device_date_return','device_date_use','device_date_resfun', 'device_price','device_status');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Device();
            $fieldInput = $checkData->checkField($data);
            $item = new Device();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->device_project = $user_project;
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->device_id,$item);
            return $item->device_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Device();
            $fieldInput = $checkData->checkField($data);
            $item = Device::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }

            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->device_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->device_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function getItemById($id=0){
        $result = (Define::CACHE_ON) ? Cache::get(Define::CACHE_DEVICE_ID . $id) : array();
        try {
            if (empty($result)) {
                $result = Device::where('device_id', $id)->first();
                if ($result && Define::CACHE_ON) {
                    Cache::put(Define::CACHE_DEVICE_ID . $id, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (PDOException $e) {
            throw new PDOException();
        }
        return $result;
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
            $item = Device::find($id);
            if($item){

                //Remove Img
                $device_image = ($item->device_image != '') ? $item->device_image : '';
                if($device_image != ''){
                    FunctionLib::deleteFileUpload($item->device_image, Define::FOLDER_DEVICE);
                    $arrSizeThumb = Define::$arrSizeImage;
                    foreach($arrSizeThumb as $k=>$size){
                        $sizeThumb = $size['w'].'x'.$size['h'];
                        FunctionLib::deleteFileThumb($item->device_image, Define::FOLDER_DEVICE, $sizeThumb);
                    }
                }
                //End Remove Img

                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->device_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public static function removeCache($id = 0,$data){
        if($id > 0){
            Cache::forget(Define::CACHE_DEVICE_ID.$id);
        }
    }
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{

            $query = Device::where('device_id','>',0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('device_project', $user_project );
            }
            if (isset($dataSearch['device_name']) && $dataSearch['device_name'] != '') {
                $query->where('device_name','LIKE', '%' . $dataSearch['device_name'] . '%');
            }
            if (isset($dataSearch['device_status']) && $dataSearch['device_status'] != -2) {
                $query->where('device_status',$dataSearch['device_status']);
            }
            if (isset($dataSearch['device_type']) && $dataSearch['device_type'] != -1) {
                $query->where('device_type',$dataSearch['device_type']);
            }
            if (isset($dataSearch['device_person_id'])) {
                if($dataSearch['device_person_id'] == 0){
                    $query->where('device_person_id', 0);
                }elseif($dataSearch['device_person_id'] == 1){
                    $query->where('device_person_id', '>', 0);
                }else{
                    //show all
                    $query->where('device_person_id', '>=', 0);
                }
            }

            if (isset($dataSearch['device_depart_id']) && $dataSearch['device_depart_id'] > 0) {
                $query->where('device_depart_id',$dataSearch['device_depart_id']);
            }

            if (isset($dataSearch['order_sort_device_id']) && $dataSearch['order_sort_device_id'] == 'asc') {
                $query->orderBy('device_id', 'asc');
            }else{
                $query->orderBy('device_id', 'desc');
            }

            $total = $query->count();

            //get field can lay du lieu
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
    public static function searchExport($dataSearch = array(), $limit =0){
        try{
            $query = Device::where('device_id','>',0);
            if (isset($dataSearch['device_name']) && $dataSearch['device_name'] != '') {
                $query->where('device_name','LIKE', '%' . $dataSearch['device_name'] . '%');
            }
            if (isset($dataSearch['device_status']) && $dataSearch['device_status'] != -2) {
                $query->where('device_status',$dataSearch['device_status']);
            }
            if (isset($dataSearch['device_type']) && $dataSearch['device_type'] != -1) {
                $query->where('device_type',$dataSearch['device_type']);
            }
            if (isset($dataSearch['device_person_id']) && $dataSearch['device_person_id'] > 0) {
                $query->where('device_person_id', '>', 0);
            }
            if (isset($dataSearch['device_person_id']) && $dataSearch['device_person_id'] == 0) {
                $query->where('device_person_id', 0);
            }
            if (isset($dataSearch['device_depart_id']) && $dataSearch['device_depart_id'] != -1) {
                $query->where('device_depart_id',$dataSearch['device_depart_id']);
            }
            if (isset($dataSearch['order_sort_device_id']) && $dataSearch['order_sort_device_id'] == 'asc') {
                $query->orderBy('device_id', 'asc');
            }else{
                $query->orderBy('device_id', 'desc');
            }

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if($limit > 0){
                $query->take($limit);
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
}
