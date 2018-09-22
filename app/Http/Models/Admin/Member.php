<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\Memcache;

class Member extends BaseModel
{
    protected $table = TABLE_MEMBER;
    protected $primaryKey = 'member_id';
    public $timestamps = true;

    protected $fillable = array('member_name', 'member_type', 'member_total_item', 'member_limit_item', 'member_status', 'member_phone', 'member_address',
        'member_mail', 'member_pay_money', 'member_date_pay', 'member_time_live', 'created_at', 'updated_at',
        'member_creater_user_id', 'member_creater_user_name', 'member_update_user_id', 'member_update_user_name');

    public function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $fieldInput = $this->checkFieldInTable($data);
            $item = new Member();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->member_id, $item);
            return $item->member_id;
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
            self::removeCache($item->member_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function getItemById($id) {
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_INFO_MEMBER_ID.$id): false;
        if (!$data || $data->count()==0) {
            $data = Member::find($id);
            if($data){
                Cache::put(Memcache::CACHE_INFO_MEMBER_ID.$id, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function deleteItem($id)
    {
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

    public function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_INFO_MEMBER_ID . $id);
        }
        Cache::forget(Memcache::CACHE_ALL_MEMBER);
    }

    public function getTypeMemberById($id = 0)
    {
        $member = self::getItemById($id);
        $member_type = (!empty($member) && isset($member->member_type)) ? $member->member_type : CGlobal::member_type_C;
        return $member_type;
    }

    public function getAllMember()
    {
        $data = (Define::CACHE_ON) ? Cache::get(Memcache::CACHE_ALL_MEMBER) : array();
        if (sizeof($data) == 0) {
            $result = Member::where('member_id', '>', 0)
                ->where('member_status', Define::STATUS_SHOW)
                ->orderBy('member_id', 'desc')->get();
            if ($result) {
                foreach ($result as $itm) {
                    $data[$itm['member_id']] = $itm['member_name'];
                }
            }
            if (!empty($data)) {
                Cache::put(Memcache::CACHE_ALL_MEMBER, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total)
    {
        try {
            $query = Member::where('member_id', '>', 0);
            if (isset($dataSearch['member_name']) && trim($dataSearch['member_name']) != '') {
                $query->where('member_name', 'LIKE', '%' . $dataSearch['member_name'] . '%');
            }
            if (isset($dataSearch['member_type']) && $dataSearch['member_type'] > 0) {
                $query->where('member_type', $dataSearch['member_type']);
            }
            $total = $query->count();
            $query->orderBy('member_id', 'desc');

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if (!empty($fields)) {
                $result = $query->take($limit)->skip($offset)->get($fields);
            } else {
                $result = $query->take($limit)->skip($offset)->get();
            }
            return $result;

        } catch (PDOException $e) {
            throw new PDOException();
        }
    }
}
