<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 09/2018
* @Version   : 1.0
*/
namespace App\Http\Models\Shop;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;
use App\Http\Models\Admin\User;


class Infosale extends BaseModel{
    protected $table = TABLE_INFOR_SALE;
    protected $primaryKey = 'infor_sale_id';
    public $timestamps = true;
    protected $fillable = array( 'member_id', 'infor_sale_uid', 'infor_sale_name', 'infor_sale_phone',
        'infor_sale_mail', 'infor_sale_skype', 'infor_sale_address', 'infor_sale_sotaikhoan',
        'infor_sale_vanchuyen', 'created_at', 'updated_at');

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, $is_total=true){
        try {
            $query = Infosale::where('infor_sale_id', '>', 0);
            if (isset($dataSearch['infor_sale_name']) && $dataSearch['infor_sale_name'] != '') {
                $query->where('infor_sale_name', 'LIKE', '%' . $dataSearch['infor_sale_name'] . '%');
            }

            if (isset($dataSearch['member_id']) && $dataSearch['member_id'] > -1) {
                if($dataSearch['member_id'] == 0){
                    $query->where('member_id','>=', $dataSearch['member_id']);
                }else{
                    $query->where('member_id', $dataSearch['member_id']);
                }
            }

            if (isset($dataSearch['infor_sale_phone']) && $dataSearch['infor_sale_phone'] != '') {
                $query->where('infor_sale_phone', 'LIKE', '%' . $dataSearch['infor_sale_phone'] . '%');
            }
            $total = ($is_total) ? $query->count() : 0;               //$total = $query->count();
            $query->orderBy('infor_sale_id', 'desc');

            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if (!empty($fields)) {
                $result = $query->take($limit)->skip($offset)->get($fields);
            } else {
                $result = $query->take($limit)->skip($offset)->get();
            }
            return ['data' => $result, 'total' => $total];    //return $result;

        } catch (PDOException $e) {
            throw new PDOException();
        }
    }
    public function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $item = new Infosale();
            if(is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }

            $member_id = app(User::class)->getMemberIdUser();
            $item->member_id = $member_id;

            $item->save();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->infor_sale_id, $item);
            return $item->infor_sale_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public function updateItem($id, $data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $member_id = app(User::class)->getMemberIdUser();
            $item = self::getItemById($id);
            if($item && isset($item->member_id) && $item->member_id == $member_id){
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                $item->member_id = $member_id;
                $item->update();
                self::removeCache($item->infor_sale_id, $item);
            }
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public function getItemById($id){
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_INFOR_SALE_ID.$id): false;
        if (!isset($data->infor_sale_id)) {
            $data = Infosale::find($id);
            if($data){
                Cache::put(Memcache::CACHE_INFOR_SALE_ID.$id, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }
    public function deleteItem($id){
        if ($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = self::getItemById($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public function removeCache($id = 0, $data){
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_INFOR_SALE_ID.$id);
        }
        if($data){
            Cache::forget(Memcache::CACHE_INFOR_SALE_MEMBER_ID.$data->member_id);
        }
    }
    public function getItemByMemberId($member_id){
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_INFOR_SALE_MEMBER_ID.$member_id): [];
        if ( $data ) {       //if (sizeof($data) == 0)
            $data = Infosale::where('member_id', $member_id)->first();
            if($data){
                Cache::put(Memcache::CACHE_INFOR_SALE_MEMBER_ID.$member_id, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }
}
