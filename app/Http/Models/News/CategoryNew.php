<?php
/**
 * QuynhTM
 */
namespace App\Http\Models\News;
use App\Http\Models\BaseModel;

use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;

class CategoryNew extends BaseModel
{
    protected $table = Define::TABLE_WEB_CATEGORY;
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $fillable = array('category_name', 'category_parent_id', 'category_depart_id', 'category_type', 'category_level',
        'category_image_background', 'category_icons','category_status','category_menu_status'
    ,'category_menu_right','category_order');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new CategoryNew();
            $fieldInput = $checkData->checkField($data);
            $item = new CategoryNew();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->category_id,$item);
            return $item->category_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new CategoryNew();
            $fieldInput = $checkData->checkField($data);
            $item = CategoryNew::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->category_id,$item);
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
            $item = CategoryNew::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->category_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            Cache::forget(Define::CACHE_CATEGORY_ID.$id);
        }
        Cache::forget(Define::CACHE_ALL_PARENT_CATEGORY);
        Cache::forget(Define::CACHE_ALL_PARENT_CATEGORY.'_'.$data->category_type);
        Cache::forget(Define::CACHE_ALL_CHILD_CATEGORY_BY_PARENT_ID.$data->category_parent_id);
        Cache::forget(Define::CACHE_CATEGORY_NEWS);
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = CategoryNew::where('category_id','>',0);
            if (isset($dataSearch['category_name']) && $dataSearch['category_name'] != '') {
                $query->where('category_name','LIKE', '%' . $dataSearch['category_name'] . '%');
            }if (isset($dataSearch['category_type']) && $dataSearch['category_type'] >0) {
                $query->where('category_type', $dataSearch['category_type'] );
            }if (isset($dataSearch['category_order']) && $dataSearch['category_order'] != '') {
                $query->where('category_order',$dataSearch['category_order']);
            }if (isset($dataSearch['category_status']) && $dataSearch['category_status'] != -2) {
                $query->where('category_status',$dataSearch['category_status'] );
            }
            $total = $query->count();
            $query->orderBy('category_type', 'ASC');

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
    public static function getOptionAllCategory() {
        $data = array();
        $category = CategoryNew::where('category_id','>',0)->orderBy('category_id','asc')->get();
        foreach($category as $itm) {
            $data[$itm['category_id']] = $itm['category_name'];
        }
        return $data;
    }
    public static function getAllParentCategoryId() {
        $data = (Define::CACHE_ON)? Cache::get(Define::CACHE_ALL_PARENT_CATEGORY) : array();
        if (sizeof($data) == 0) {
            $category = CategoryNew::where('category_id', '>', 0)
                ->where('category_parent_id',0)
                ->where('category_status',CGlobal::status_show)
                ->orderBy('category_order','asc')->get();
            if($category){
                foreach($category as $itm) {
                    $data[$itm['category_id']] = $itm['category_name'];
                }
            }
            if($data && Define::CACHE_ON){
                Cache::put(Define::CACHE_ALL_PARENT_CATEGORY, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
			}
		}
	}	

    public static function getCategoryNews(){
        $data = Cache::get(Define::CACHE_CATEGORY_NEWS);
        if (sizeof($data) == 0) {
            $result = CategoryNew::where('category_id', '>', 0)
                ->whereIn('category_type',array(Define::Category_News_Menu,Define::Category_News_News,Define::Category_News_Note))
                ->where('category_status',Define::STATUS_SHOW)
                ->orderBy('category_order','asc')->get();
            if($result){
                foreach($result as $itm) {
                    $data[$itm['category_id']] = $itm['category_name'];
                }
            }
            if(!empty($data)){
                Cache::put(Define::CACHE_CATEGORY_NEWS, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }
    public static function getAllParentCateWithType($category_type) {
        $data = (Define::CACHE_ON)? Cache::get(Define::CACHE_ALL_PARENT_CATEGORY.'_'.$category_type) : array();
        if (sizeof($data) == 0) {
            $category = CategoryNew::where('category_id', '>', 0)
                ->where('category_parent_id',0)
                ->where('category_status',CGlobal::status_show)
                ->where('category_type',$category_type)
                ->orderBy('category_order','asc')->get();
            if($category){
                foreach($category as $itm) {
                    $data[$itm['category_id']] = $itm['category_name'];
                }
            }
            if($data && Define::CACHE_ON){
                Cache::put(Define::CACHE_ALL_PARENT_CATEGORY.'_'.$category_type, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }
    public static function getAllChildCategoryIdByParentId($parentId = 0) {
        $data = (Memcache::CACHE_ON)? Cache::get(Define::CACHE_ALL_CHILD_CATEGORY_BY_PARENT_ID.$parentId) : array();
        if (sizeof($data) == 0 && $parentId > 0) {
            $category = CategoryNew::where('category_id' ,'>', 0)
                ->where('category_parent_id','=',$parentId)
                ->where('category_status',CGlobal::status_show)
                ->orderBy('category_order','asc')->get();
            if($category){
                foreach($category as $itm) {
                    $data[$itm['category_id']] = $itm['category_name'];
                }
            }
            if($data && Define::CACHE_ON){
                Cache::put(Define::CACHE_ALL_CHILD_CATEGORY_BY_PARENT_ID.$parentId, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
			}
		}
	}	
    public static function getCategoryProduct(){
        $data = Cache::get(Define::CACHE_CATEGORY_PRODUCT);
        if (sizeof($data) == 0) {
            $result = CategoryNew::where('category_id', '>', 0)
                ->whereIn('category_type',array(Define::Category_News_Product))
                ->where('category_status',Define::STATUS_SHOW)
                ->orderBy('category_order','asc')->get();
            if($result){
                foreach($result as $itm) {
                    $data[$itm['category_id']] = $itm['category_name'];
                }
            }
            if(!empty($data)){
                Cache::put(Define::CACHE_CATEGORY_PRODUCT, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

    public static function buildTreeCategory($category_type = 0){
        if($category_type > 0){
            $categories = CategoryNew::where('category_id', '>', 0)
                ->where('category_status', '=', CGlobal::status_show)
                ->where('category_type', '=', $category_type)
                ->get();
        }else{
            $categories = CategoryNew::where('category_id', '>', 0)
                ->where('category_status', '=', CGlobal::status_show)
                ->get();
        }
        return $treeCategroy = self::getTreeCategory($categories);
    }
    public static function getTreeCategory($data){
        $max = 0;
        $aryCategoryProduct = $arrCategory = array();
        if(!empty($data)){
            foreach ($data as $k=>$value){
                $max = ($max < $value->category_parent_id)? $value->category_parent_id : $max;
                $arrCategory[$value->category_id] = array(
                    'category_id'=>$value->category_id,
                    'category_depart_id'=>$value->category_depart_id,
                    'category_parent_id'=>$value->category_parent_id,
                    'category_type'=>$value->category_type,
                    'category_level'=>$value->category_level,
                    'category_image_background'=>$value->category_image_background,
                    'category_icons'=>$value->category_icons,
                    'category_order'=>$value->category_order,
                    'category_status'=>$value->category_status,
                    'category_menu_status'=>$value->category_menu_status,
                    'category_name'=>$value->category_name,
                    'category_menu_right'=>$value->category_menu_right);
            }
        }

        if($max > 0){
            $aryCategoryProduct = self::showCategory($max, $arrCategory);
        }
        return $aryCategoryProduct;
    }
    public static function showCategory($max, $aryDataInput) {
        $aryData = array();
        if(is_array($aryDataInput) && count($aryDataInput) > 0) {
            foreach ($aryDataInput as $k => $val) {
                if((int)$val['category_parent_id'] == 0) {
                    $val['padding_left'] = '';
                    $val['category_parent_name'] = '';
                    $aryData[] = $val;
                    self::showSubCategory($val['category_id'],$val['category_name'], $max, $aryDataInput, $aryData);
                }
            }
        }
        return $aryData;
    }
    public static function showSubCategory($cat_id,$cat_name, $max, $aryDataInput, &$aryData) {
        if($cat_id <= $max) {
            foreach ($aryDataInput as $chk => $chval) {
                if($chval['category_parent_id'] == $cat_id) {
                    $chval['padding_left'] = '--- ';
                    $chval['category_parent_name'] = $cat_name;
                    $aryData[] = $chval;
                    self::showSubCategory($chval['category_id'],$chval['category_name'], $max, $aryDataInput, $aryData);
                }
            }
        }
    }
}
