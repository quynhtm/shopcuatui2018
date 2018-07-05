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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;

class Department extends BaseModel{

    protected $table = Define::TABLE_HR_DEPARTMENT;
    protected $primaryKey = 'department_id';
    public $timestamps = false;

    protected $fillable = array('department_id', 'department_type', 'department_parent_id', 'department_name', 'department_project', 'department_level',
        'department_link', 'department_status','department_order','department_creater_time','department_user_id_creater','department_user_name_creater',
        'department_update_time','department_user_id_update','department_user_name_update',
        'department_leader', 'department_phone', 'department_email', 'department_fax', 'department_postion','department_num_tax',
        'department_num_bank', 'department_name_bank', 'department_position_bank'
        );

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Department();
            $fieldInput = $checkData->checkField($data);
            $item = new Department();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->department_project = $user_project;
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_id, $item->department_parent_id, $item->department_project);
            return $item->department_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Department();
            $fieldInput = $checkData->checkField($data);
            $item = Department::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }

            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->department_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($id, $item->department_parent_id, $item->department_project);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function getItemById($id=0){
        $result = (Define::CACHE_ON) ? Cache::get(Define::CACHE_DEPARTMENT_ID.$id) : array();
        try {
            if(empty($result)){
                $result = Department::where('department_id', $id)->first();
                if($result && Define::CACHE_ON){
                    Cache::put(Define::CACHE_DEPARTMENT_ID.$id, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
            $item = Department::find($id);
            $department_parent_id = 0;
            $department_project = 0;
            if($item){
                $department_parent_id = $item->department_parent_id;
                $department_project = $item->department_project;
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($id,$department_parent_id,$department_project);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{

            $query = Department::where('department_id','>',0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('department_project', $user_project );
            }
            if (isset($dataSearch['department_name']) && $dataSearch['department_name'] != '') {
                $query->where('department_name','LIKE', '%' . $dataSearch['department_name'] . '%');
            }
            if (isset($dataSearch['department_status']) && $dataSearch['department_status']!= -1) {
                $query->where('department_status', $dataSearch['department_status']);
            }
            if (isset($dataSearch['department_id']) && $dataSearch['department_id'] > 0) {
                //$query->where('department_id', $dataSearch['department_id']);
                $catid = $dataSearch['department_id'];
                $arrCat = array($catid);
                Department::makeListCatId($catid, 0, $arrCat);
                if(is_array($arrCat) && !empty($arrCat)){
                    $query->whereIn('department_id', $arrCat);
                }
            }

            if (isset($dataSearch['department_type']) && $dataSearch['department_type']!= -1) {
                $query->where('department_type', $dataSearch['department_type']);
            }

            $total = $query->count();
            $query->orderBy('department_order', 'asc');

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
    public static function removeCache($id = 0,$department_parent_id,$department_project){
        Cache::forget(Define::CACHE_DEPARTMENT_ID.$id);
        Cache::forget(Define::CACHE_DEPARTMENT_PARENT_ID.$department_parent_id);
        Cache::forget(Define::CACHE_ALL_DEPARTMENT.$department_project);
    }
    public static function getLevelParentId($id){
        $level = 0;
        if($id > 0){
            $category = Department::getItemById($id);
            if(!empty($category)){
                $level = isset($category->department_level) ? $category->department_level + 1 : 0;
            }
        }
        return $level;
    }
    public static function getDepartmentAll(){
        $user_project = app(User::class)->get_user_project();
        $data = (Define::CACHE_ON)? Cache::get(Define::CACHE_ALL_DEPARTMENT.$user_project) : array();
        if (sizeof($data) == 0) {
            $query = Department::where('department_id', '>', 0)
                ->where('department_status', '=', CGlobal::status_show)

                ->orderBy('department_order', 'asc')
                ->orderBy('department_parent_id', 'desc');
            if($user_project > 0){
                $query->where('department_project', $user_project);
            }
            $categories = $query->get();
            if($categories){
                foreach($categories as $itm) {
                    $data[$itm->department_id] = $itm->department_name;
                }
                if(!empty($data) && Define::CACHE_ON){
                    Cache::put(Define::CACHE_ALL_DEPARTMENT.$user_project, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        }
        return $data;
    }

    public static function getDepartmentByParentId($department_parent_id){
        $data = (Define::CACHE_ON)? Cache::get(Define::CACHE_DEPARTMENT_PARENT_ID.$department_parent_id) : array();
        if (sizeof($data) == 0) {
            $categories = Department::where('department_id', '>', 0)
                ->where('department_status', '=', CGlobal::status_show)
                ->where('department_parent_id', '=', $department_parent_id)
                ->orderBy('department_order', 'asc')
                ->orderBy('department_parent_id', 'desc')->get();
            if($categories){
                foreach($categories as $itm) {
                    $data[$itm->department_id] = $itm->department_name;
                }
                if(!empty($data) && Define::CACHE_ON){
                    Cache::put(Define::CACHE_DEPARTMENT_PARENT_ID.$department_parent_id, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        }
        return $data;
    }

    public static function makeListCatId($catid=0, $level=0, &$arrCat){
        $listcat = explode(',', $catid);
        if(!empty($listcat)){
            $query = Department::where('department_status', '=', CGlobal::status_show);
            foreach($listcat as $cat){
                if($cat != end($listcat)){
                    $query->orWhere('department_parent_id',$cat);
                }else{
                    $query->where('department_parent_id', $cat);
                }
            }
            $result = $query->get();
        }
        if ($result != null){
            foreach ($result as $k => $v){
                array_push($arrCat, $v->department_id);
                self::makeListCatId($v->department_id, $level+1, $arrCat);
            }
        }
        return true;
    }
}
