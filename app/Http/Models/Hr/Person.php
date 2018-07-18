<?php
/**
 * QuynhTM
 */

namespace App\Http\Models\Hr;

use App\Http\Models\Admin\User;
use App\Http\Models\BaseModel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;

class Person extends BaseModel
{
    protected $table = Define::TABLE_HR_PERSON;
    protected $primaryKey = 'person_id';
    public $timestamps = false;

    protected $fillable = array('person_project', 'person_depart_id', 'person_date_trial_work', 'person_date_start_work',
        'person_name', 'person_name_other', 'person_chung_minh_thu', 'person_date_range_cmt', 'person_issued_cmt',
        'person_birth', 'person_sex', 'person_mail', 'person_code', 'person_phone', 'person_telephone', 'person_position_define_id',
        'person_career_define_id', 'person_address_place_of_birth', 'person_province_place_of_birth', 'person_address_home_town', 'person_province_home_town',
        'person_address_current', 'person_province_current', 'person_wards_current', 'person_districts_current',
        'person_nation_define_id', 'person_respect', 'person_height', 'person_weight',
        'person_blood_group_define_id', 'person_date_salary_increase', 'person_status', 'person_avatar',
        'person_creater_time', 'person_creater_user_id', 'person_creater_user_name',
        'person_update_time', 'person_update_user_id', 'person_update_user_name',
        //extra
        'contracts_dealine_date', 'person_extend_trinhdo_hocvan', 'retirement_date', 'person_is_dangvien', 'person_ngayvao_dang', 'person_type_contracts',
    );

    public function putDataSalaryPerson($request)
    {
        //tính ngày tăng lương
        if (isset($request['person_id']) && isset($request['salary_id']) && isset($request['person_date_salary_increase']) && $request['person_date_salary_increase'] == Define::STATUS_SHOW) {
            $salary_id = (int)$request['salary_id'];
            $person_id = (int)$request['person_id'];
            $infoSalary = Salary::getLastItem();
            if (isset($infoSalary->salary_id)) {
                if ($infoSalary->salary_id == $salary_id) {
                    $monthTemp = ($infoSalary->salary_month < 10) ? '0' . $infoSalary->salary_month : $infoSalary->salary_month;
                    $dateApdungLuong = '01-' . $monthTemp . '-' . $infoSalary->salary_year;

                    //get ngày tăng lương theo mã ngach
                    $infoSalary->salary_tariffs;//mã ngạch
                    $infoMaNgach = HrWageStepConfig::find($infoSalary->salary_tariffs);
                    $numberThang = (isset($infoMaNgach->wage_step_config_month_salary_increase) && $infoMaNgach->wage_step_config_month_salary_increase > 0) ? $infoMaNgach->wage_step_config_month_salary_increase : 12;
                    $dataUpdatePerson['person_date_salary_increase'] = strtotime('+' . $numberThang . ' month', strtotime($dateApdungLuong));
                    Person::updateItem($person_id, $dataUpdatePerson);
                }

                //luong cua nhan sự theo thông tin mới
                $thongTinLuongNS = Payroll::getPayrollByInfoSalary($person_id, $infoSalary->salary_month, $infoSalary->salary_year);
                if ($thongTinLuongNS) {//nếu có, thì cập nhật
                    $dataNewPay['ma_ngach'] = $infoSalary->salary_tariffs;//mã nghạch
                    $dataNewPay['he_so_luong'] = $infoSalary->salary_coefficients;//hệ số lương
                    $dataNewPay['luong_co_so'] = $infoSalary->salary_salaries;//lương cơ sở
                    $payroll_id = Payroll::updateItem($thongTinLuongNS->payroll_id, $dataNewPay);
                    Payroll::checkingValuePayroll(false, $payroll_id);
                } else {//thêm mới
                    $dataNewPay['payroll_person_id'] = $person_id;
                    $dataNewPay['ma_ngach'] = $infoSalary->salary_tariffs;//mã nghạch
                    $dataNewPay['payroll_month'] = $infoSalary->salary_month;
                    $dataNewPay['payroll_year'] = $infoSalary->salary_year;
                    $dataNewPay['he_so_luong'] = $infoSalary->salary_coefficients;//hệ số lương
                    $dataNewPay['luong_co_so'] = $infoSalary->salary_salaries;//lương cơ sở
                    $payroll_id = Payroll::createItem($dataNewPay);
                }
            }
        }
    }

    public static function putDataAllowancePerson($request)
    {
        //tính ngày tăng lương
        if (isset($request['person_id']) && isset($request['allowance_id'])) {
            $person_id = (int)$request['person_id'];
            $allowance_id = (int)$request['allowance_id'];
            $allowance_type = (int)$request['allowance_type'];
            $allowance_method_value = $request['allowance_method_value'];
            $month = (int)$request['allowance_month_start'];
            $year = (int)$request['allowance_year_start'];
            if ($allowance_id > 0) {
                //luong cua nhan sự theo thông tin mới
                $thongTinLuongNS = Payroll::getPayrollByInfoSalary($person_id, $month, $year);
                if ($thongTinLuongNS) {//nếu có, thì cập nhật
                    $dataNewPay = Person::pushInfoAllowance($allowance_type, $allowance_method_value);
                    if (!empty($dataNewPay)) {
                        $payroll_id = Payroll::updateItem($thongTinLuongNS->payroll_id, $dataNewPay);
                        Payroll::checkingValuePayroll(false, $payroll_id);
                    }
                } else {//thêm mới
                    $dataNewPay = Person::pushInfoAllowance($allowance_type, $allowance_method_value);
                    if (!empty($dataNewPay)) {
                        Payroll::createItem($dataNewPay);
                    }
                }
            }
        }
    }

    public static function pushInfoAllowance($type, $value)
    {
        $dataNewPay = array();
        switch ($type) {
            case Define::phucap_chucvu:
                $dataNewPay['phu_cap_chuc_vu'] = $value;
                break;
            case Define::phucap_thamnienvuotkhung:
                $dataNewPay['phu_cap_tham_nien_vuot'] = $value;
                break;
            case Define::phucap_trachnhiem:
                $dataNewPay['phu_cap_trach_nhiem'] = $value;
                break;
            case Define::phucap_thamnien:
                $dataNewPay['phu_cap_tham_nien'] = $value;
                break;
            case Define::phucap_nghanh:
                $dataNewPay['phu_cap_nghanh'] = $value;
                break;
            default:
                $dataNewPay = array();
                break;
        }
        return $dataNewPay;
    }

    public function putDataSalaryPersonTuNhan($request)
    {
        //tính ngày tăng lương
        if (isset($request['person_id']) && isset($request['salary_id']) && isset($request['person_date_salary_increase']) && $request['person_date_salary_increase'] == Define::STATUS_SHOW) {
            $salary_id = (int)$request['salary_id'];
            $person_id = (int)$request['person_id'];
            $infoSalary = Salary::getLastItem();
            if (isset($infoSalary->salary_id)) {
                //luong cua nhan sự theo thông tin mới
                $thongTinLuongNS = Payroll::getPayrollByInfoSalary($person_id, $infoSalary->salary_month, $infoSalary->salary_year);
                $dataNewPay['payroll_person_id'] = $person_id;
                $dataNewPay['payroll_month'] = $infoSalary->salary_month;
                $dataNewPay['payroll_year'] = $infoSalary->salary_year;
                $dataNewPay['he_so_luong'] = $infoSalary->salary_percent;//hệ số lương thực lĩnh tư nhân
                $dataNewPay['luong_co_so'] = $infoSalary->salary_salaries;//lương cơ sở
                $dataNewPay['tong_tien_baohiem'] = $infoSalary->salary_money_insurrance;//tiền bảo hiểm
                $dataNewPay['tong_tien_tro_cap'] = $infoSalary->salary_money_allowance;//tiên phụ cấp
                $dataNewPay['tong_luong_thuc_nhan'] = ($infoSalary->salary_executance + $infoSalary->salary_money_allowance) - $infoSalary->salary_money_insurrance;//tiền thực nhận của người LĐ
                if ($thongTinLuongNS) {//nếu có, thì cập nhật
                    $payroll_id = Payroll::updateItem($thongTinLuongNS->payroll_id, $dataNewPay);
                } else {//thêm mới
                    $payroll_id = Payroll::createItem($dataNewPay);
                }
                return $payroll_id;
            }
        }
    }

    public static function getPersonById($person_id)
    {
        if ((int)$person_id > 0) {
            $data = (Define::CACHE_ON)? Cache::get(Define::CACHE_PERSON . $person_id):array();
            if (sizeof($data) == 0) {
                $data = Person::find($person_id);
                if ($data && !empty($data)) {
                    Cache::put(Define::CACHE_PERSON . $person_id, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
            return $data;
        }
        return false;
    }

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total, $get_total = false)
    {
        try {
            $query = Person::where('person_id', '>', 0);
            if (!isset($dataSearch['person_search_job_project'])) {
                $user_project = app(User::class)->get_project_search();
                if ($user_project > Define::STATUS_SEARCH_ALL) {
                    $query->where('person_project', $user_project);
                }
            } else {
                $query->where('person_project', '>', -1);
            }


            if (isset($dataSearch['person_name']) && $dataSearch['person_name'] != '') {
                $query->where('person_name', 'LIKE', '%' . $dataSearch['person_name'] . '%');
            }
            if (isset($dataSearch['person_mail']) && $dataSearch['person_mail'] != '') {
                $query->where('person_mail', 'LIKE', '%' . $dataSearch['person_mail'] . '%');
            }

            if (isset($dataSearch['person_code']) && $dataSearch['person_code'] != '') {
                $query->where('person_code', 'LIKE', '%' . $dataSearch['person_code'] . '%');
            }

            if (isset($dataSearch['person_id']) && $dataSearch['person_id'] > 0) {
                $query->where('person_id', $dataSearch['person_id']);
            }
            if (isset($dataSearch['person_depart_id']) && $dataSearch['person_depart_id'] > 0) {
                $arrDepart = Department::getDepartmentByParentId($dataSearch['person_depart_id']);
                $arrDepartSearch = (!empty($arrDepart)) ? array_keys($arrDepart) : array();
                $arrDepartSearch = (!empty($arrDepartSearch)) ? array_merge($arrDepartSearch, array($dataSearch['person_depart_id'])) : array($dataSearch['person_depart_id']);
                $query->whereIn('person_depart_id', $arrDepartSearch);
            }
            //là đảng viên
            if (isset($dataSearch['person_is_dangvien']) && $dataSearch['person_is_dangvien'] > -1) {
                $query->where('person_is_dangvien', '=', $dataSearch['person_is_dangvien']);
            }
            //loại hợp đồng
            if (isset($dataSearch['person_type_contracts']) && $dataSearch['person_type_contracts'] > 0) {
                $query->where('person_type_contracts', '>', $dataSearch['person_type_contracts']);
            }
            //sinh nhật
            if (isset($dataSearch['start_birth']) && $dataSearch['start_birth'] > 0) {
                $query->where('person_birth', '>=', $dataSearch['start_birth']);
            }
            if (isset($dataSearch['end_birth']) && $dataSearch['end_birth'] > 0) {
                $query->where('person_birth', '<=', $dataSearch['end_birth']);
            }
            //đến hạn tăng lương
            if (isset($dataSearch['start_dealine_salary']) && $dataSearch['start_dealine_salary'] > 0) {
                $query->where('person_date_salary_increase', '>=', $dataSearch['start_dealine_salary']);
            }
            if (isset($dataSearch['end_dealine_salary']) && $dataSearch['end_dealine_salary'] > 0) {
                $query->where('person_date_salary_increase', '<=', $dataSearch['end_dealine_salary']);
            }

            if (isset($dataSearch['person_status']) && is_array($dataSearch['person_status'])) {
                $query->whereIn('person_status', $dataSearch['person_status']);
            } elseif (isset($dataSearch['person_status']) && $dataSearch['person_status'] != '') {
                $query->where('person_status', $dataSearch['person_status']);
            }

            if (isset($dataSearch['list_person_id']) && is_array($dataSearch['list_person_id']) && count($dataSearch['list_person_id']) > 0) {
                $query->whereIn('person_id', $dataSearch['list_person_id']);
            }

            $total = $query->count();

            if ($get_total)
                return true;

            if (isset($dataSearch['orderBy']) && $dataSearch['orderBy'] != '' && isset($dataSearch['sortOrder']) && $dataSearch['sortOrder'] != '') {
                $query->orderBy($dataSearch['orderBy'], $dataSearch['sortOrder']);
            } else {
                $query->orderBy('person_id', 'desc');
            }

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if ($limit > 0) {
                $query->take($limit);
            }
            if ($offset > 0) {
                $query->skip($offset);
            }
            if (!empty($fields)) {
                $result = $query->get($fields);
            } else {
                $result = $query->get();
            }
            return $result;

        } catch (PDOException $e) {
            throw new PDOException();
        }
    }

    public static function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Person();
            $fieldInput = $checkData->checkField($data);
            $item = new Person();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $user_project = app(User::class)->get_user_project();
            if ($user_project > 0) {
                $item->person_project = $user_project;
            }

            $item->save();

            DB::connection()->getPdo()->commit();
            PersonTime::createDataPersonTime($item->person_id, $item);
            self::removeCache($item->person_id, $item);
            return $item->person_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id, $data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Person();
            $fieldInput = $checkData->checkField($data);
            $item = Person::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $user_project = app(User::class)->get_user_project();
            if ($user_project > 0) {
                $item->person_project = $user_project;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            PersonTime::createDataPersonTime($item->person_id, $item);
            self::removeCache($item->person_id, $item);
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

    public static function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = Person::find($id);
            $person_id = 0;
            if ($item) {
                $person_id = $item->person_id;
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            $checkData = new Person();
            $checkData->deleteAllInfoPerson($person_id);
            self::removeCache($person_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public function deleteAllInfoPerson($person_id)
    {
        if ($person_id > 0) {
            DB::table(Define::TABLE_USER)->where('user_object_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_PERSON_EXTEND)->where('person_extend_person_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_PERSONNEL_TIME)->where('person_time_person_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_DEVICE)->where('device_person_id', $person_id)->delete();

            DB::table(Define::TABLE_HR_BONUS)->where('bonus_person_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_RELATIONSHIP)->where('relationship_person_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_CONTRACTS)->where('contracts_person_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_CURRICULUM_VITAE)->where('curriculum_person_id', $person_id)->delete();

            DB::table(Define::TABLE_HR_DOCUMENT)->where('hr_document_person_recive', $person_id)->delete();
            DB::table(Define::TABLE_HR_MAIL)->where('hr_mail_person_recive', $person_id)->delete();
            DB::table(Define::TABLE_HR_JOB_ASSIGNMENT)->where('job_assignment_person_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_RETIREMENT)->where('retirement_person_id', $person_id)->delete();

            DB::table(Define::TABLE_HR_QUIT_JOB)->where('quit_job_person_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_PASSPORT)->where('passport_person_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_SALARY)->where('salary_person_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_ALLOWANCE)->where('allowance_person_id', $person_id)->delete();
            DB::table(Define::TABLE_HR_PAYROLL)->where('payroll_person_id', $person_id)->delete();
        }
        return true;
    }

    public static function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            Cache::forget(Define::CACHE_PERSON . $id);
        }
    }

    public static function getInfoPerson($person_id)
    {
        if ($person_id > 0) {
            $person = Person::find($person_id);
            $person->contracts;
            $person->passport;
            $person->salary;
            return $person;
        }
    }

    public static function getInfoPersonPassport($person_id)
    {
        if ($person_id > 0) {
            $person = Person::find($person_id);
            $person->passport;
            return $person;
        }
    }

    public static function getPersonInDepart($person_depart_id = 0)
    {
        $person = array();
        if ($person_depart_id > 0) {
            $result = Person::where('person_depart_id', $person_depart_id)->get();
            if (sizeof($result) > 0) {
                foreach ($result as $item) {
                    $person[] = $item->person_id;
                }
            }
        }
        return $person;
    }

    public function contracts()
    {
        return $this->hasOne('App\Http\Models\Hr\HrContracts', 'contracts_person_id', 'person_id');
    }

    public function salary()
    {
        return $this->hasMany('App\Http\Models\Hr\Salary', 'salary_person_id', 'person_id');
    }

    public function allowance()
    {
        return $this->hasOne('App\Http\Models\Hr\Allowance', 'allowance_person_id', 'person_id');
    }

    public function passport()
    {
        return $this->hasOne('App\Http\Models\Hr\Passport', 'passport_person_id', 'person_id');
    }
}
