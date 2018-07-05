<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
namespace App\Http\Models\News;

use App\Library\AdminFunction\Define;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminNews extends Model{

	protected $table = Define::TABLE_NEWS;
	protected $primaryKey = 'news_id';
	public $timestamps = false;

    protected $fillable = array(
	    	'news_id', 'news_title', 'news_title_alias', 'news_intro',
    		'news_content', 'news_image','news_created', 'news_order_no', 'news_status'
    );
    //ADMIN
  	public static function searchByCondition($dataSearch=array(), $limit=0, $offset=0, &$total){
	  	try{
	  		
	  		$query = AdminNews::where('news_id','>',0);
	  		
	  		if (isset($dataSearch['news_id']) && $dataSearch['news_id'] != '') {
	  			$query->where('news_id','=', $dataSearch['news_id']);
	  		}
	  		if (isset($dataSearch['news_title']) && $dataSearch['news_title'] != '') {
	  			$query->where('news_title','LIKE', '%' . $dataSearch['news_title'] . '%');
	  		}
	  		if (isset($dataSearch['news_status']) && $dataSearch['news_status'] != -1){
	  			$query->where('news_status', $dataSearch['news_status']);
	  		}
	  		$total = $query->count();
	  		$query->orderBy('news_id', 'desc');
	  	
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
  	
  	public static function getItemById($id=0){
  		$result = (Define::CACHE_ON) ? Cache::get(Define::CACHE_NEWS_ID.$id) : array();
  		try {
  			if(empty($result)){
	  			$result = AdminNews::where('news_id', $id)->first();
	  			if($result && Define::CACHE_ON){
	  				Cache::put(Define::CACHE_NEWS_ID.$id, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
	  			}
	  		}
	  	} catch (PDOException $e) {
	  		throw new PDOException();
	  	}
	  	return $result;
  	}
  	
  	public static function updateItem($id=0, $dataInput=array()){
		try {
			DB::connection()->getPdo()->beginTransaction();
			$checkData = new AdminNews();
			$fieldInput = $checkData->checkField($dataInput);
			$item = AdminNews::find($id);
			foreach ($fieldInput as $k => $v) {
				$item->$k = $v;
			}
			$item->update();
			DB::connection()->getPdo()->commit();
			self::removeCacheId($item->news_id,$item);
			return true;
		} catch (PDOException $e) {
			DB::connection()->getPdo()->rollBack();
			throw new PDOException();
		}
  	}
  	
  	public static function createItem($dataInput=array()){
        try {
            DB::connection()->getPdo()->beginTransaction();
			$checkData = new AdminNews();
			$fieldInput = $checkData->checkField($dataInput);
			$item = new AdminNews();
			if(is_array($fieldInput) && count($fieldInput) > 0) {
				foreach ($fieldInput as $k => $v) {
					$item->$k = $v;
				}
			}
			$item->save();
			DB::connection()->getPdo()->commit();
			self::removeCacheId($item->news_id,$item);
			return $item->news_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

  	public static function deleteItem($id=0){
  		try {
  			DB::connection()->getPdo()->beginTransaction();
  			$data = AdminNews::find($id);
  			if($data != null){
  				$data->delete();
  				if(isset($data->news_id) && $data->news_id > 0){
  					self::removeCacheId($data->news_id);
  				}
  				DB::connection()->getPdo()->commit();
  			}
  			return true;
  		} catch (PDOException $e) {
  			DB::connection()->getPdo()->rollBack();
  			throw new PDOException();
  		}
  	}
  	
  	public static function removeCacheId($id=0){
  		if($id>0){
  			Cache::forget(Define::CACHE_NEWS_ID.$id);
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
}