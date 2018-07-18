<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Hr;

use App\Http\Models\Admin\User;
use App\Http\Models\BaseModel;

use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;

class Payroll extends BaseModel
{
    protected $table = Define::TABLE_HR_PAYROLL;
    protected $primaryKey = 'payroll_id';
    public $timestamps = false;

    protected $fillable = array('payroll_project',
        'payroll_person_id',
        'payroll_month',
        'payroll_year',
        'ma_ngach',                     //ma nghạch theo bảng lương
        'he_so_luong',                  //1 luong
        'phu_cap_chuc_vu',              //2
        'phu_cap_tham_nien_vuot',       //3
        'phu_cap_tham_nien_vuot_heso',  //4=1*3
        'phu_cap_trach_nhiem',          //5
        'phu_cap_tham_nien',            //6
        'phu_cap_tham_nien_heso',       //7=(1+2+4)*6
        'phu_cap_nghanh',               //8
        'phu_cap_nghanh_heso',          //9=1*8
        'tong_he_so',                   //10=1+2+4+5+7+9
        'luong_co_so',                  //11 luong
        'tong_tien_tro_cap',            //tiền trợ cấp tư nhân
        'tong_tien',                    //12=10*11
        'tong_tien_luong',              //13=12
        'tong_tien_baohiem',            //14= (1+2+4+5+7)*11*0.105 (10.5% BHXH + BHYT + BHTN)
        'tong_luong_thuc_nhan'          //15=13-14
    );

    public static function getPayrollByPersonId($payroll_person_id)
    {
        return Payroll::where('payroll_person_id', $payroll_person_id)->first();
    }

    public static function getPayrollByInfoSalary($payroll_person_id, $payroll_month, $payroll_year)
    {
        return Payroll::where('payroll_person_id', $payroll_person_id)
            ->where('payroll_month', $payroll_month)
            ->where('payroll_year', $payroll_year)
            ->first();
    }

    public static function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Payroll();
            $fieldInput = $checkData->checkField($data);
            $item = new Payroll();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }

            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->payroll_project = $user_project;
            }
            $item->save();
            DB::connection()->getPdo()->commit();
            if($item->payroll_project == CGlobal::hr_hanchinh_2c){//2C thì mới cập nhật lại
                Payroll::checkingValuePayroll($item);
            }
            self::removeCache($item->payroll_id, $item);
            return $item->payroll_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }


    public static function updateItem($id, $data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Payroll();
            $fieldInput = $checkData->checkField($data);
            $item = Payroll::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $user_project = app(User::class)->get_user_project();
            if($user_project > 0){
                $item->payroll_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            if($item->payroll_project == CGlobal::hr_hanchinh_2c){//2C thì mới cập nhật lại
                Payroll::checkingValuePayroll($item);
            }
            self::removeCache($item->payroll_id, $item);
            return $item->payroll_id;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function checkingValuePayroll($payroll, $payroll_id = 0)
    {
        $payroll = ($payroll_id > 0)? Payroll::find($payroll_id) : $payroll;
        $phu_cap_tham_nien_vuot_heso = round(($payroll->he_so_luong * $payroll->phu_cap_tham_nien_vuot)/100,4);
        $phu_cap_tham_nien_heso = round((($payroll->he_so_luong + $payroll->phu_cap_chuc_vu + $phu_cap_tham_nien_vuot_heso) * $payroll->phu_cap_tham_nien)/100,4);
        $phu_cap_nghanh_heso = round(($payroll->he_so_luong * $payroll->phu_cap_nghanh)/100,4);
        $tong_he_so = round(($payroll->he_so_luong + $payroll->phu_cap_chuc_vu + $phu_cap_tham_nien_vuot_heso + $payroll->phu_cap_trach_nhiem + $phu_cap_tham_nien_heso + $phu_cap_nghanh_heso),4);
        $tong_tien = $tong_he_so * $payroll->luong_co_so;
        $tong_tien_baohiem = round(($payroll->he_so_luong + $payroll->phu_cap_chuc_vu + $phu_cap_tham_nien_vuot_heso + $payroll->phu_cap_trach_nhiem + $phu_cap_tham_nien_heso) * $payroll->luong_co_so * 0.105,4);
        $arrCheck = array(
            'ma_ngach' => $payroll->ma_ngach,                       //1 mã nghạch
            'he_so_luong' => $payroll->he_so_luong,                       //1 luong
            'phu_cap_chuc_vu' => $payroll->phu_cap_chuc_vu,              //2
            'phu_cap_tham_nien_vuot' => $payroll->phu_cap_tham_nien_vuot,       //3
            'phu_cap_tham_nien_vuot_heso' => $phu_cap_tham_nien_vuot_heso,  //4=1*3
            'phu_cap_trach_nhiem' => $payroll->phu_cap_trach_nhiem,          //5
            'phu_cap_tham_nien' => $payroll->phu_cap_tham_nien,            //6
            'phu_cap_tham_nien_heso' => $phu_cap_tham_nien_heso,       //7=(1+2+4)*6
            'phu_cap_nghanh' => $payroll->phu_cap_nghanh,               //8
            'phu_cap_nghanh_heso' => $phu_cap_nghanh_heso,          //9=1*8
            'tong_he_so' => $tong_he_so,                   //10=1+2+4+5+7+9
            'luong_co_so' => $payroll->luong_co_so,                  //11 luong
            'tong_tien' => $tong_tien,                    //12=10*11
            'tong_tien_luong' => $tong_tien,              //13=12
            'tong_tien_baohiem' => $tong_tien_baohiem,            //14= (1+2+4+5+7)*11*0.105 (10.5% BHXH + BHYT + BHTN)
            'tong_luong_thuc_nhan' => $tong_tien - $tong_tien_baohiem        //15=13-14
        );
        Payroll::updateItem($payroll->payroll_id,$arrCheck);
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

    public static function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = Payroll::find($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->payroll_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            //Cache::forget(Define::CACHE_CATEGORY_ID.$id);
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total)
    {
        try {
            $query = Payroll::where('payroll_id', '>', 0);
            $user_project = app(User::class)->get_project_search();
            if($user_project > Define::STATUS_SEARCH_ALL){
                $query->where('payroll_project', $user_project );
            }
            if(isset($dataSearch['arrPerson']) && sizeof($dataSearch['arrPerson']) > 0){
                $query->whereIn('payroll_person_id',  $dataSearch['arrPerson']);
            }

            if(isset($dataSearch['payroll_person_id']) && is_array($dataSearch['payroll_person_id'])){
                $query->whereIn('payroll_person_id',  $dataSearch['payroll_person_id']);
            }elseif(isset($dataSearch['payroll_person_id']) && $dataSearch['payroll_person_id'] > -2){
                $query->where('payroll_person_id',  $dataSearch['payroll_person_id']);
            }

            if (isset($dataSearch['reportMonth']) && $dataSearch['reportMonth'] > 0) {
                $query->where('payroll_month', $dataSearch['reportMonth']);
            }
            if (isset($dataSearch['reportYear']) && $dataSearch['reportYear'] > 0) {
                if(!isset($dataSearch['reportMonth'])){
                    $timeCurrentLast = date('d', time()).'-'.date('m', time()).'-'.$dataSearch['reportYear'].' '.date('H', time()).':'.date('i', time()) ;
                    $timeCurrentLast = strtotime($timeCurrentLast);
                    $timeCurrentFirst = '01-01-'.$dataSearch['reportYear'].' 00:00';
                    $timeCurrentFirst = strtotime($timeCurrentFirst);
                    $query->whereBetween('payroll_year', array($timeCurrentFirst, $timeCurrentLast));
                }else{
                    $query->where('payroll_year', $dataSearch['reportYear']);
                }
            }

            $total = $query->count();
            $query->orderBy('payroll_id', 'desc');
            $query->orderBy('payroll_month', 'asc');
            $query->orderBy('payroll_year', 'asc');
            //return $query->toSql();

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if($limit > 0){
                $query->take($limit);
            }
            if($offset > 0){
                $query->take($offset);
            }
            if(!empty($fields)) {
                $result = $query->get($fields);
            } else {
                $result = $query->get();
            }
            return $result;

        } catch (PDOException $e) {
            throw new PDOException();
        }
    }
}
