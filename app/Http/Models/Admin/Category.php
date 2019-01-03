<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 09/2018
* @Version   : 1.0
*/

namespace App\Http\Models\Admin;

use App\Http\Models\BaseModel;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Memcache;

class Category extends BaseModel
{
    protected $table = TABLE_CATEGORY;
    protected $primaryKey = 'category_id';
    public $timestamps = true;
    protected $fillable = array('category_id', 'member_id', 'category_name', 'category_parent_id', 'category_depart_id',
        'category_type', 'category_level', 'category_image_background', 'category_icons',
        'category_status', 'category_menu_status', 'category_order', 'category_menu_right',
        'meta_title', 'meta_keywords', 'meta_description', 'created_at', 'updated_at');

    public function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, $is_total = true)
    {
        try {
            $query = Category::where('category_id', '>', 0);
            if (isset($dataSearch['category_name']) && $dataSearch['category_name'] != '') {
                $query->where('category_name', 'LIKE', '%' . $dataSearch['category_name'] . '%');
            }
            if (isset($dataSearch['category_status']) && $dataSearch['category_status'] != -1) {
                $query->where('category_status', $dataSearch['category_status']);
            }
            if (isset($dataSearch['category_depart_id']) && $dataSearch['category_depart_id'] != -1) {
                $query->where('category_depart_id', $dataSearch['category_depart_id']);
            }
            if (isset($dataSearch['category_type']) && $dataSearch['category_type'] > 0) {
                $query->where('category_type', $dataSearch['category_type']);
            }
            if (isset($dataSearch['string_depart_id']) && $dataSearch['string_depart_id'] != '') {
                $query->whereIn('category_depart_id', explode(',', $dataSearch['string_depart_id']));
            }
            if (isset($dataSearch['category_menu_right']) && $dataSearch['category_menu_right'] != -1) {
                $query->where('category_menu_right', $dataSearch['category_menu_right']);
            }

            $total = ($is_total) ? $query->count() : 0;
            $query->orderBy('category_id', 'desc');

            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if (!empty($fields)) {
                $result = $query->take($limit)->skip($offset)->get($fields);
            } else {
                $result = $query->take($limit)->skip($offset)->get();
            }
            return ['data' => $result, 'total' => $total];

        } catch (PDOException $e) {
            return $e->getMessage();
            throw new PDOException();
        }
    }

    public function createItem($data)
    {
        try {
            $fieldInput = $this->checkFieldInTable($data);
            $item = new Category();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }

            $member_id = app(User::class)->getMemberIdUser();
            $item->member_id = $member_id;
            $item->save();
            self::removeCache($item->category_id, $item);
            return $item->category_id;
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
                self::removeCache($item->category_id, $item);
            }
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

/*lý do phải sửa sizeof($data) -> chuyển thành (!$data)  và Array()-> chuyển thành (false)  --- ở phần getItembyId($id)
 * vì mỗi bản php có 1 cách viết , hỗ trợ khác nhau nên ở đây ta phải chuyển thành như vậy
 */
    public function getItemById($id)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_CATEGORY_ID . $id) : false;
        if (!$data) {
            $data = Category::find($id);
            if ($data) {
                Cache::put(Memcache::CACHE_CATEGORY_ID . $id, $data, CACHE_ONE_MONTH);
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
            }
            self::removeCache($id, $dataOld);
            return true;
        } catch (PDOException $e) {
            throw new PDOException();
            return false;
        }
    }

    public function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            Cache::forget(Memcache::CACHE_CATEGORY_ID . $id);
            Cache::forget(Memcache::CACHE_ALL_CHILD_CATEGORY_BY_PARENT_ID . $id);
        }
        if ($data) {
            Cache::forget(Memcache::CACHE_CATEGORY_MEMBER_ID . $data->member_id);
            Cache::forget(Memcache::CACHE_ALL_PARENT_CATEGORY . '_' . $data->category_type);
            Cache::forget(Memcache::CACHE_ALL_CATEGORY_BY_TYPE . $data->category_type);
        }
        Cache::forget(Memcache::CACHE_ALL_CATEGORY);
        Cache::forget(Memcache::CACHE_ALL_PARENT_CATEGORY);
        Cache::forget(Memcache::CACHE_ALL_SHOW_CATEGORY_FRONT);
        Cache::forget(Memcache::CACHE_ALL_CATEGORY_RIGHT);

    }

    public function getItemByMemberId($member_id)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_CATEGORY_MEMBER_ID . $member_id) : false;  //[]
        if (!$data) {
            $data = Category::where('member_id', $member_id)->first();
            if ($data) {
                Cache::put(Memcache::CACHE_CATEGORY_MEMBER_ID . $member_id, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function getCategoryNameByID($id)
    {
        $category = Category::getByID($id);
        return (sizeof($category) > 0) ? $category->category_name : '';
    }

    public function getOptionAllCategory()
    {
        $data = array();
        $category = Category::where('category_id', '>', 0)->orderBy('category_id', 'asc')->get();
        foreach ($category as $itm) {
            $data[$itm['category_id']] = $itm['category_name'];
        }
        return $data;
    }

    public function getCategoryByArrayId($arrCate = array())
    {
        $data = array();
        if (!empty($arrCate)) {
            $category = Category::whereIn('category_id', $arrCate)->orderBy('category_id', 'asc')->get();
            foreach ($category as $itm) {
                $data[$itm['category_id']] = $itm['category_name'];
            }
            return $data;
        }
        return $data;
    }

    public function getCategoryByDepartId($depart_id = 0)
    {
        $data = array();
        if ($depart_id > 0) {
            $category = Category::where('category_depart_id', $depart_id)->orderBy('category_id', 'asc')->get();
            foreach ($category as $itm) {
                $data[$itm['category_id']] = $itm['category_name'];
            }
            return $data;
        }
        return $data;
    }

    public function getDepartIdByCategoryId($category_id = 0)
    {
        $category_depart_id = 0;
        if ($category_id > 0) {
            $category = Category::getByID($category_id);
            if (sizeof($category) !== 0) {
                $category_depart_id = isset($category->category_depart_id) ? $category->category_depart_id : 0;
            }
        }
        return $category_depart_id;
    }

    public function getAllParentCategoryId()
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ALL_PARENT_CATEGORY) : false;
        if (!$data) {
            $category = Category::where('category_id', '>', 0)
                ->where('category_parent_id', 0)
                ->where('category_status', CGlobal::status_show)
                ->orderBy('category_order', 'asc')->get();
            if ($category) {
                foreach ($category as $itm) {
                    $data[$itm['category_id']] = $itm['category_name'];
                }
            }
            if ($data && Memcache::CACHE_ON) {
                Cache::put(Memcache::CACHE_ALL_PARENT_CATEGORY, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function getAllParentCateWithType($category_type)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ALL_PARENT_CATEGORY . '_' . $category_type) : false;
        if (!$data) {
            $category = Category::where('category_id', '>', 0)
                ->where('category_parent_id', 0)
                ->where('category_status', CGlobal::status_show)
                ->where('category_type', $category_type)
                ->orderBy('category_order', 'asc')->get();
            if ($category) {
                foreach ($category as $itm) {
                    $data[$itm['category_id']] = $itm['category_name'];
                }
            }
            if ($data && Memcache::CACHE_ON) {
                Cache::put(Memcache::CACHE_ALL_PARENT_CATEGORY . '_' . $category_type, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function getAllChildCategoryIdByParentId($parentId = 0)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ALL_CHILD_CATEGORY_BY_PARENT_ID . $parentId) : false;
        if (!$data == 0 && $parentId > 0) {
            $category = Category::where('category_id', '>', 0)
                ->where('category_parent_id', '=', $parentId)
                ->where('category_status', CGlobal::status_show)
                ->orderBy('category_order', 'asc')->get();
            if ($category) {
                foreach ($category as $itm) {
                    $data[$itm['category_id']] = $itm['category_name'];
                }
            }
            if ($data && Memcache::CACHE_ON) {
                Cache::put(Memcache::CACHE_ALL_CHILD_CATEGORY_BY_PARENT_ID . $parentId, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function buildTreeCategory($category_type = 0)
    {
        if ($category_type > 0) {
            $categories = Category::where('category_id', '>', 0)
                ->where('category_status', '=', CGlobal::status_show)
                ->where('category_type', '=', $category_type)
                ->get();
        } else {
            $categories = Category::where('category_id', '>', 0)
                ->where('category_status', '=', CGlobal::status_show)
                ->get();
        }
        return $treeCategroy = self::getTreeCategory($categories);
    }

    public function getTreeCategory($data)
    {
        $max = 0;
        $aryCategoryProduct = $arrCategory = array();
        if (!empty($data)) {
            foreach ($data as $k => $value) {
                $max = ($max < $value->category_parent_id) ? $value->category_parent_id : $max;
                $arrCategory[$value->category_id] = array(
                    'category_id' => $value->category_id,
                    'member_id' => $value->member_id,
                    'category_depart_id' => $value->category_depart_id,
                    'category_parent_id' => $value->category_parent_id,
                    'category_type' => $value->category_type,
                    'category_level' => $value->category_level,
                    'category_image_background' => $value->category_image_background,
                    'category_icons' => $value->category_icons,
                    'category_order' => $value->category_order,
                    'category_status' => $value->category_status,
                    'category_menu_status' => $value->category_menu_status,
                    'category_name' => $value->category_name,
                    'category_menu_right' => $value->category_menu_right);
            }
        }

        if ($max > 0) {
            $aryCategoryProduct = self::showCategory($max, $arrCategory);
        }
        return $aryCategoryProduct;
    }

    public function showCategory($max, $aryDataInput)
    {
        $aryData = array();
        if (is_array($aryDataInput) && count($aryDataInput) > 0) {
            foreach ($aryDataInput as $k => $val) {
                if ((int)$val['category_parent_id'] == 0) {
                    $val['padding_left'] = '';
                    $val['category_parent_name'] = '';
                    $aryData[] = $val;
                    self::showSubCategory($val['category_id'], $val['category_name'], $max, $aryDataInput, $aryData);
                }
            }
        }
        return $aryData;
    }

    public function showSubCategory($cat_id, $cat_name, $max, $aryDataInput, &$aryData)
    {
        if ($cat_id <= $max) {
            foreach ($aryDataInput as $chk => $chval) {
                if ($chval['category_parent_id'] == $cat_id) {
                    $chval['padding_left'] = '--- ';
                    $chval['category_parent_name'] = $cat_name;
                    $aryData[] = $chval;
                    self::showSubCategory($chval['category_id'], $chval['category_name'], $max, $aryDataInput, $aryData);
                }
            }
        }
    }

    public function getAllCategoryByType($type = 0, $limit = 5)
    {
        $data = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ALL_CATEGORY_BY_TYPE . $type) : false;
        if (!$data) {
            $data = Category::where('category_id', '>', 0)
                ->where('category_status', CGlobal::status_show)
                ->where('category_type', $type)
                ->take($limit)
                ->orderBy('category_order', 'asc')->get();
            if ($data && Memcache::CACHE_ON) {
                Cache::put(Memcache::CACHE_ALL_CATEGORY_BY_TYPE . $type, $data, CACHE_ONE_MONTH);
            }
        }
        return $data;
    }

    public function makeListCatId($catid = 0, $level = 0, &$arrCat)
    {
        $listcat = explode(',', $catid);
        if (!empty($listcat)) {
            $query = Category::where('category_status', '=', CGlobal::status_show);
            foreach ($listcat as $cat) {
                if ($cat != end($listcat)) {
                    $query->orWhere('category_parent_id', $cat);
                } else {
                    $query->where('category_parent_id', $cat);
                }
            }
            $result = $query->get();
        }
        if ($result != null) {
            foreach ($result as $k => $v) {
                array_push($arrCat, $v->category_id);
                self::makeListCatId($v->category_id, $level + 1, $arrCat);
            }
        }
        return true;
    }

    public function searchCategoryRightByCondition($dataSearch = array(), $limit = 0)
    {
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ALL_CATEGORY_RIGHT) : array();

        try {
            if (sizeof($result) == 0) {
                $query = Category::where('category_id', '>', 0);

                if (isset($dataSearch['category_menu_right']) && $dataSearch['category_menu_right'] != -1) {
                    $query->where('category_menu_right', $dataSearch['category_menu_right']);
                }
                if (isset($dataSearch['category_type']) && $dataSearch['category_type'] > 0) {
                    $query->where('category_type', $dataSearch['category_type']);
                }

                $query->orderBy('category_id', 'asc');

                //get field can lay du lieu
                $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
                if (!empty($fields)) {
                    $result = $query->take($limit)->get($fields);
                } else {
                    $result = $query->take($limit)->get();
                }

                if ($result && Memcache::CACHE_ON) {
                    Cache::put(Memcache::CACHE_ALL_CATEGORY_RIGHT, $result, CACHE_ONE_MONTH);
                }
            }
            return $result;

        } catch (PDOException $e) {
            return $e->getMessage();
            throw new PDOException();
        }
    }
}
