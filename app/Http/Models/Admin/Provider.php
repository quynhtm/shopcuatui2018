<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class Provider extends BaseModel
{
    protected $table = TABLE_PROVIDER;
    protected $primaryKey = 'provider_id';
    public $timestamps = true;
    protected $fillable = array('member_id','provider_id','provider_name', 'provider_phone','provider_address','provider_email',
        'provider_shop_id','provider_shop_name','provider_status',
        'provider_note', 'provider_time_creater', 'created_at', 'updated_at');

    public function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $item = new Provider();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->provider_id, $item);
            return $item->id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function updateItem($id, $data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $item = self::getItemById($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->provider_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function getItemById($id) {
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_PROVIDER_ID.$id): [];
        if (sizeof($data) == 0) {
            $data = Provider::find($id);
            if($data){
                Cache::put(Memcache::CACHE_PROVIDER_ID.$id, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = $dataOld = self::getItemById($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($id, $dataOld);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_PROVIDER_ID.$id);
        }
        if($data){
            Cache::forget(Memcache::CACHE_LIST_PROVIDER_BY_MEMBER_ID.$data->member_id);
        }
        Cache::forget(Memcache::CACHE_ALL_PROVIDER);
    }

    public function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Provider::where('provider_id','>',0);
            if (isset($dataSearch['provider_name']) && $dataSearch['provider_name'] != '') {
                $query->where('provider_name','LIKE', '%' . $dataSearch['provider_name'] . '%');
            }
            if (isset($dataSearch['provider_phone']) && $dataSearch['provider_phone'] != '') {
                $query->where('provider_phone','LIKE', '%' . $dataSearch['provider_phone'] . '%');
            }
            if (isset($dataSearch['provider_email']) && $dataSearch['provider_email'] != '') {
                $query->where('provider_email','LIKE', '%' . $dataSearch['provider_email'] . '%');
            }
            if (isset($dataSearch['provider_status']) && $dataSearch['provider_status'] != -1) {
                $query->where('provider_status', $dataSearch['provider_status']);
            }
            if (isset($dataSearch['provider_id']) && $dataSearch['provider_id'] > 0) {
                $query->where('provider_id', $dataSearch['provider_id']);
            }
            if (isset($dataSearch['member_id']) && $dataSearch['member_id'] > 0) {
                $query->where('member_id', $dataSearch['member_id']);
            }
            $total = $query->count();
            $query->orderBy('provider_status', 'desc');

            //get field can lay du lieu
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

    public function getProviderShopByID($id,$member_id) {
        $provider = Provider::getItemById($id);
        if (sizeof($provider) > 0) {
            if($provider->member_id == $member_id){
                return $provider;
            }
        }
        return array();
    }

    public function getProviderAll() {
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_ALL_PROVIDER) : array();
        if (sizeof($data) == 0) {
            $provider = Provider::where('provider_id', '>', 0)->get();
            foreach($provider as $itm) {
                $data[$itm['provider_id']] = $itm['provider_name'];
            }
            if(!empty($data) && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_ALL_PROVIDER, $data,CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function getListProviderByMemberId($member_id = 0) {
        $provider = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_LIST_PROVIDER_BY_MEMBER_ID.$member_id) : array();
        if (sizeof($provider) == 0) {
            $data = ($member_id == 0)? Provider::where('member_id','>' ,$member_id)->get(): Provider::where('member_id','=' ,$member_id)->get();
            if(count($data) > 0){
                foreach($data as $itm) {
                    $provider[$itm->provider_id] = $itm->provider_name;
                }
                if($provider && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_LIST_PROVIDER_BY_MEMBER_ID.$member_id, $provider,CACHE_ONE_MONTH);
                }
                return $provider;
            }
        }
        return $provider;
    }
}
