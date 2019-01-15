<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class Districts extends BaseModel
{
    protected $table = TABLE_DISTRICTS;
    protected $primaryKey = 'district_id';
    public $timestamps = false;
    protected $fillable = array('district_name', 'district_province_id', 'district_status', 'district_position', 'district_in_area',
    'user_id_creater','user_name_creater','user_id_update','user_name_update', 'created_at', 'updated_at');

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, $is_total = true)
    {
        try {
            $query = Districts::where('district_id', '>', 0);
            if (isset($dataSearch['district_name']) && $dataSearch['district_name'] != '') {
                $query->where('district_name', 'LIKE', '%' . $dataSearch['district_name'] . '%');
            }
            if (isset($dataSearch['district_status']) && $dataSearch['district_status'] > -1) {
                $query->where('district_status', $dataSearch['district_status']);
            }
/**/        if (isset($dataSearch['district_province_id']) && $dataSearch['district_province_id'] != "" && !is_array($dataSearch['district_province_id'])){
                $query->where('district_province_id' , $dataSearch['district_province_id']);
            }
/**/        if (isset($dataSearch['district_province_id'])&& is_array($dataSearch['district_province_id'])){
                $query->whereIn('district_province_id' , $dataSearch['district_province_id']);
            }


            $total = ($is_total) ? $query->count() : 0;

            $query->orderBy('district_province_id', 'asc');  //và những quận thuộc 1 tỉnh hiển thị liền nhau . thứ tự hiển thị tỉnh  trước khi
            $query->orderBy('district_id', 'desc');          // hiển thị quận (mới thêm)

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if (!empty($fields)) {
                $result = $query->take($limit)->skip($offset)->get($fields);
            } else {
                $result = $query->take($limit)->skip($offset)->get();
            }
            return ['data' => $result, 'total' => $total];

        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function createItem($data)
    {
        try {
            $fieldInput = $this->checkFieldInTable($data);
            $item = new Districts();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                $item->user_id_creater = app(User::class)->user_id();
                $item->user_name_creater = app(User::class)->user_name();
                $item->created_at = getCurrentFull();
                $item->save();
                self::removeCache($item->district_id, $item);
                return $item->district_id;
            }
            return false;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function updateItem($id, $data)
    {
        try {
            $fieldInput = $this->checkFieldInTable($data);
            if(empty($fieldInput))
                return false;
            $item = self::getItemById($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->user_id_update = app(User::class)->user_id();
            $item->user_name_update = app(User::class)->user_name();
            $item->updated_at = getCurrentFull();
            $item->update();
            self::removeCache($item->district_id, $item);
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            $item = $dataOld = self::getItemById($id);
            if ($item) {
                $item->delete();
                self::removeCache($id, $dataOld);
            }
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
            return false;
        }
    }

    public function getItemById($id)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_DISTRICTS_ID . $id) : false;
        if (!$data) {
            $data = Districts::find($id);
            if ($data) {
                Cache::put(Memcache::CACHE_DISTRICTS_ID . $id, $data, CACHE_THREE_MONTH);
            }
        }
        return $data;
    }

    public function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_DISTRICTS_ID . $id);
        }
        if ($data) {

        }
    }

    public function getListDistrictsNameById($id) { //
        $data = array();
        if(is_array($id)) //kiểm tra $id là 1 mảng
        {
            $data = (is_array($id)) ? Districts::whereIn('district_id',$id)->get(array('district_id','district_province_id','district_name')) : Districts::where('district_id',$id) -> get(array('district_id','district_province_id','district_name'));
        // $data = $id là 1 mảng thì vào districts với district_id truyền biến theo dạng mảng với các biến ......
        }
        return $data;
    }

}
