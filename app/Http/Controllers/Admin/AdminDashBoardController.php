<?php
/**
 * Created by PhpStorm.
 * User: Quynhtm
 * Date: 29/05/2015
 * Time: 8:24 CH
 */

namespace App\Http\Controllers\Admin;

use App\Http\Models\Hr\HrContracts;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\PersonTime;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\BaseAdminController;
use Illuminate\Support\Facades\Session;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;

class AdminDashBoardController extends BaseAdminController
{
    private $error = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard()
    {

        $total = 0;
        $data = array();
        $listLink = CGlobal::$arrLinkListDash;
        $arrNotify = $this->getNotifyList();
        return view('admin.AdminDashBoard.index', [
            'user' => $this->user,
            'menu' => $this->menuSystem,
            'data' => $data,
            'listLink' => $listLink,
            'arrNotify' => $arrNotify,
            'lang' => $this->languageSite,
            'is_root' => $this->is_root]);
    }

    public function getNotifyList()
    {
        return array();
        $depar_id = ($this->is_root) ? (int)Define::STATUS_HIDE : (int)$this->user_depart_id;
        $listLink = CGlobal::$arrLinkListNotify;
        $arrCacheNotify = array();
        foreach ($listLink as $val) {
            $total = self::sumTotalData($val['cacheNotify'], $depar_id);
            $arrCacheNotify[$val['cacheNotify']] = ($total == Define::TOTAL_MAX) ? 0 : $total;
        }
        return $arrCacheNotify;
    }

    public function sumTotalData($nameCache, $depart_id)
    {
        if ($nameCache != '') {
            $total_item = (Define::CACHE_ON) ? Cache::get($nameCache . '_' . $depart_id) : array();
            if (!$total_item || empty($total_item)) {
                $total_item = Define::TOTAL_MAX;
                $limit = CGlobal::number_show_20;
                $offset = 0;
                switch ($nameCache) {
                    case 'viewBirthday';
                        $arrPersonId = PersonTime::getListPersonIdByTypeTime(Define::PERSONNEL_TIME_TYPE_BIRTH, Define::config_date_check_notify_7);
                        if (sizeof($arrPersonId) > 0) {
                            $search['person_depart_id'] = $depart_id;
                            $search['person_status'] = Define::$arrStatusPersonAction;
                            $search['list_person_id'] = $arrPersonId;
                            $search['field_get'] = 'person_id';
                            $data = Person::searchByCondition($search, $limit, $offset, $total_item, true);
                        }
                        break;
                    case 'viewDealineSalary';//đến hạn tăng lương
                        $arrPersonId = PersonTime::getListPersonIdByTypeTime(Define::PERSONNEL_TIME_TYPE_DATE_SALARY_INCREASE, Define::config_date_check_notify_7);
                        if (!empty($arrPersonId)) {
                            $search['person_depart_id'] = $depart_id;
                            $search['person_status'] = Define::$arrStatusPersonAction;
                            $search['field_get'] = 'person_id';
                            $search['list_person_id'] = $arrPersonId;
                            $data = Person::searchByCondition($search, $limit, $offset, $total_item, true);
                        }
                        break;
                    case 'viewDealineContract';//đến hạn tăng hợp đồng
                        $arrPersonId = PersonTime::getListPersonIdByTypeTime(Define::PERSONNEL_TIME_TYPE_CONTRACTS_DEALINE_DATE, Define::config_date_check_notify_7);
                        if (!empty($arrPersonId)) {
                            $search['person_depart_id'] = $depart_id;
                            $search['person_status'] = Define::$arrStatusPersonAction;
                            $search['list_person_id'] = $arrPersonId;
                            $search['field_get'] = 'person_id';
                            $data = Person::searchByCondition($search, $limit, $offset, $total_item, true);
                        }
                        break;
                    case 'viewQuitJob';// nghỉ việc
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_NGHIVIEC;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item, true);
                        break;
                    case 'viewMoveJob';//chuyển công tác
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_CHUYENCONGTAC;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item, true);
                        break;
                    case 'viewRetired';//nghỉ hưu
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_NGHIHUU;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item, true);
                        break;
                    case 'viewPreparingRetirement';//sắp nghỉ hưu
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_SAPNGHIHUU;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item, true);
                        break;
                    case 'viewDangVienPerson';//Đảng viên
                        $search['person_depart_id'] = $depart_id;
                        $search['person_is_dangvien'] = Define::DANG_VIEN;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item, true);
                        break;
                    default:
                        break;
                }
                $total_item = ($total_item == 0) ? Define::TOTAL_MAX : $total_item;
                if ($total_item != 0) {
                    Cache::put($nameCache . '_' . $depart_id, $total_item, Define::CACHE_TIME_TO_LIVE_HALF_DAY_DAY);
                }
            }
            return $total_item;
        }
    }
}