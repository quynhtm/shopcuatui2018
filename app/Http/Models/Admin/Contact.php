<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class Contact extends BaseModel
{
    protected $table = TABLE_CONTACT;
    protected $primaryKey = 'contact_id';
    public $timestamps = false;
    protected $fillable = array('contact_title', 'contact_content', 'contact_content_reply', 'contact_user_id_send', 'contact_user_name_send', 'contact_phone_send',
        'contact_email_send', 'contact_type', 'contact_reason', 'contact_status', 'contact_time_creater', 'contact_user_id_update', 'contact_user_name_update', 'contact_time_update');

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, $is_total = true)
    {
        try {
            $query = Contact::where('contact_id', '>', 0);
            if (isset($dataSearch['contact_title']) && $dataSearch['contact_title'] != '') {
                $query->where('contact_title', 'LIKE', '%' . $dataSearch['contact_title'] . '%');
            }
            if (isset($dataSearch['contact_status']) && $dataSearch['contact_status'] > -1) {
                $query->where('contact_status', $dataSearch['contact_status']);
            }
            $total = ($is_total) ? $query->count() : 0;
            $query->orderBy('contact_id', 'desc');

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
            $item = new Contact();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
                $item->save();
                self::removeCache($item->contact_id, $item);
                return $item->contact_id;
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
            $item->update();
            self::removeCache($item->contact_id, $item);
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public function getItemById($id)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_CONTACT_ID . $id) : false;
        if (!$data) {
            $data = Contact::find($id);
            if ($data) {
                Cache::put(Memcache::CACHE_CONTACT_ID . $id, $data, CACHE_THREE_MONTH);
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
            Cache::forget(Memcache::CACHE_CONTACT_ID . $id);
        }
        if ($data) {

        }
    }
}
