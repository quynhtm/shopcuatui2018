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
use App\Library\AdminFunction\FunctionLib;

class MemberSite extends BaseModel
{
    protected $table = Define::TABLE_MEMBER;
    protected $primaryKey = 'member_id';
    public $timestamps = false;

    protected $fillable = array('member_name', 'member_type', 'member_total_item', 'member_limit_item', 'member_status', 'member_phone', 'member_address',
        'member_mail', 'member_pay_money', 'member_date_pay', 'member_time_live',
        'member_creater_time', 'member_creater_user_id', 'member_creater_user_name',
        'member_update_time', 'member_update_user_id', 'member_update_user_name');

    public function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new MemberSite();
            $fieldInput = $checkData->checkField($data);
            $item = new MemberSite();
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
            $checkData = new MemberSite();
            $fieldInput = $checkData->checkField($data);
            $item = MemberSite::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->member_id, $item);
            return true;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function checkField($dataInput)
    {
        $fields = $this->fillable;
        $dataDB = array();
        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (isset($dataInput[$field])) {
                    $dataDB[$field] = $dataInput[$field];
                }
            }
        }
        return $dataDB;
    }

    public function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = MemberSite::find($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->member_id, $item);
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
            Cache::forget(Define::CACHE_INFO_MEMBER_ID . $id);
        }
        Cache::forget(Define::CACHE_ALL_MEMBER);
    }

    public function getInforMemberById($member_id = 0)
    {
        if ($member_id > 0) {
            $data = (Define::CACHE_ON) ? Cache::get(Define::CACHE_INFO_MEMBER_ID . $member_id) : array();
            if (sizeof($data) == 0) {
                $data = MemberSite::find($member_id);
                if (!empty($data)) {
                    Cache::put(Define::CACHE_INFO_MEMBER_ID . $member_id, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
            return $data;
        }
        return [];
    }

    public function getTypeMemberById($member_id = 0)
    {
        $member = self::getInforMemberById($member_id);
        $member_type = (!empty($member) && isset($member->member_type)) ? $member->member_type : CGlobal::hr_tu_nhan;
        return $member_type;
    }

    public function getAllMember()
    {
        $data = (Define::CACHE_ON) ? Cache::get(Define::CACHE_ALL_MEMBER) : array();
        if (sizeof($data) == 0) {
            $result = MemberSite::where('member_id', '>', 0)
                ->where('member_status', Define::STATUS_SHOW)
                ->orderBy('member_id', 'desc')->get();
            if ($result) {
                foreach ($result as $itm) {
                    $data[$itm['member_id']] = $itm['member_name'];
                }
            }
            if (!empty($data)) {
                Cache::put(Define::CACHE_ALL_MEMBER, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total)
    {
        try {
            $query = MemberSite::where('member_id', '>', 0);
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
