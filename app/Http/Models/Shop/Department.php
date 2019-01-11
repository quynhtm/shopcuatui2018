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
use App\Http\Models\Admin\User;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class Department extends BaseModel
{
    protected $table = TABLE_DEPARTMENT;
    protected $primaryKey = 'department_id';
    public $timestamps = true;
    protected $fillable = array('department_id', 'member_id', 'department_name', 'department_alias', 'department_order', 'department_status', 'created_at', 'updated_at');

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, $is_total = true)
    {
        try {
            $query = Department::where('department_id', '>', 0);
            if (isset($dataSearch['department_name']) && $dataSearch['department_name'] != '') {
                $query->where('department_name', 'LIKE', '%' . $dataSearch['department_name'] . '%');
            }

            if (isset($dataSearch['member_id']) && $dataSearch['member_id'] > -1) {
                if ($dataSearch['member_id'] == 0) {
                    $query->where('member_id', '>=', $dataSearch['member_id']);
                } else {
                    $query->where('member_id', $dataSearch['member_id']);
                }
            }

            if (isset($dataSearch['department_status']) && $dataSearch['department_status'] > -1) {
                $query->where('department_status', $dataSearch['department_status']);
            }

            $total = ($is_total) ? $query->count() : 0;
            $query->orderBy('department_order', 'asc');

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
            $item = new Department();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                $member_id = app(User::class)->getMemberIdUser();
                $item->member_id = $member_id;
                $item->save();
                self::removeCache($item->department_id, $item);
                return $item->department_id;
            }
            return 0;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function updateItem($id, $data)
    {
        try {
            $fieldInput = $this->checkFieldInTable($data);
            $member_id = app(User::class)->getMemberIdUser();
            $item = self::getItemById($id);
            if ($item && isset($item->member_id) && $item->member_id == $member_id) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                $item->member_id = $member_id;
                $item->update();
                self::removeCache($item->department_id, $item);
            }
            self::removeCache($item->department_id, $item);
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function getItemById($id)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_DEPARTMENT_ID . $id) : false;
        if (!$data) {
/*sửa*/        $data = Department::where('department_id',$id)->first();         /*find() k nhận được $ id  , chuyển sang dùng where để để nhận được id*/
            if ($data) {
                Cache::put(Memcache::CACHE_DEPARTMENT_ID . $id, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
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

    public function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_DEPARTMENT_ID . $id);
        }
    }
}
