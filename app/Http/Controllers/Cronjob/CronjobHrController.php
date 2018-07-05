<?php
/**
 * QuynhTM
 */

namespace App\Http\Controllers\Cronjob;

use App\Http\Controllers\BaseCronjobController;
use App\Http\Models\Admin\Cronjob;

use App\Http\Models\Hr\DepartmentConfig;
use App\Http\Models\Hr\Payroll;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\QuitJob;

use App\Http\Models\Hr\Retirement;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Curl;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class CronjobHrController extends BaseCronjobController{

    private $limit = CGlobal::number_show_1000;
    private $total = 0;
    private $offset = 0;

	public function __construct(){
		parent::__construct();
	}
    //Auth system
    public function lcsSystem(){
        $lcs = date('d', time());
        if((int)$lcs == 1 || (int)$lcs == 15){
            FunctionLib::lcsSystem();
            $data['name_job'] = 'sys';
            return $this->returnResultSuccess($data);
        }
    }
	//quét cac cronjob để run
	public function callRunCronjob(){
        $listCronjob = Cronjob::getListData();
        if(!empty($listCronjob)){
            foreach ($listCronjob as $val){
                if($val['cronjob_router'] != ''){
                    $dateRun = $val['cronjob_date_run'];
                    $timeRun = date('Ymd',$dateRun);
                    $timeNow = date('Ymd',time());
                    if($timeNow != $timeRun){
                        $curl = Curl::getInstance();
                        $call = $curl->get(URL::route($val['cronjob_router']));
                        $dataCurl = json_decode($call, true);
                        if(!empty($dataCurl)){
                            //cap nhat bang cronjob
                            $dataUpdateCronjob['cronjob_date_run'] = time();
                            $dataUpdateCronjob['cronjob_number_running'] = $val->cronjob_number_running+1;
                            $dataUpdateCronjob['cronjob_result'] = $val->cronjob_number_running.'<br/>'.$call;
                            Cronjob::updateItem($val->cronjob_id,$dataUpdateCronjob);
                        }
                    }
                }
            }
        }
    }

	//nghỉ việc
    public function runCronjobQuitJob(){
        $dataSearch['person_status'] = Define::PERSON_STATUS_DANGLAMVIEC;
        $dataSearch['person_search_job_project'] = 1;
        $dataSearch['field_get'] = 'person_id';
        $dataPerson = Person::searchByCondition($dataSearch, $this->limit, $this->offset, $this->total);
        $arrPersonId = array();
        if(count($dataPerson) > 0){
            foreach ($dataPerson as $va){
                $arrPersonId [$va->person_id] = $va->person_id;
            }

            if(!empty($arrPersonId)){
                $dataQuitJob = QuitJob::whereIn('quit_job_person_id', $arrPersonId)
                    ->where('quit_job_type', Define::QUITJOB_THOI_VIEC)->get();
                if(count($dataQuitJob) > 0){
                    foreach ($dataQuitJob as $val){
                        $person_id = $val->quit_job_person_id;
                        $dateAction = $val->quit_job_date_creater;
                        $timeRun = date('Ymd',$dateAction);
                        $timeNow = date('Ymd',time());
                        if($timeRun <= $timeNow){
                            $updateStatusPerson['person_status'] = Define::PERSON_STATUS_NGHIVIEC;
                            if(Person::updateItem($person_id,$updateStatusPerson)){
                                $updateStatusPerson['name_job'] = 'Cập nhật thành công NS nghỉ việc';
                                $updateStatusPerson['person_id'] = $person_id;
                                $updateStatusPerson['date'] = date('d-m-Y H:i:s', time());
                                return $this->returnResultSuccess($updateStatusPerson);
                            }
                        }
                    }
                }else{
                    $data['name_job'] = 'Không có thông tin nghỉ việc';
                    $data['person_id'] = $arrPersonId;
                    $data['date'] = date('d-m-Y H:i:s', time());
                    return $this->returnResultSuccess($data);
                }

            }else{
                $data['name_job'] = 'Không có thông tin nhân sự 1';
                $data['person_id'] = $arrPersonId;
                $data['date'] = date('d-m-Y H:i:s', time());
                return $this->returnResultSuccess($data);
            }

        }else{
            $data['name_job'] = 'Không có thông tin nhân sự 2';
            $data['person_id'] = $arrPersonId;
            $data['date'] = date('d-m-Y H:i:s', time());
            return $this->returnResultSuccess($data);
        }
        return $this->returnResultError($dataQuitJob);
    }

    //Chuyển công tác, nghỉ việc
    public function runCronjobMoveJob(){
        $dataSearch['person_status'] = Define::PERSON_STATUS_DANGLAMVIEC;
        $dataSearch['person_search_job_project'] = 1;
        $dataSearch['field_get'] = 'person_id';
        $dataPerson = Person::searchByCondition($dataSearch, $this->limit, $this->offset, $this->total);
        $arrPersonId = array();
        if(count($dataPerson) > 0){
            foreach ($dataPerson as $va){
                $arrPersonId [$va->person_id] = $va->person_id;
            }

            if(!empty($arrPersonId)){
                $dataQuitJob = QuitJob::whereIn('quit_job_person_id', $arrPersonId)
                    ->where('quit_job_type', Define::QUITJOB_CHUYEN_CONGTAC)->get();
                if(count($dataQuitJob) > 0){
                    foreach ($dataQuitJob as $val){
                        $person_id = $val->quit_job_person_id;
                        $dateAction = $val->quit_job_date_creater;
                        $timeRun = date('Ymd',$dateAction);
                        $timeNow = date('Ymd',time());
                        if($timeRun <= $timeNow){
                            $updateStatusPerson['person_status'] = Define::PERSON_STATUS_CHUYENCONGTAC;
                            if(Person::updateItem($person_id,$updateStatusPerson)){
                                $updateStatusPerson['name_job'] = 'Cập nhật thành công NS chuyển công tác';
                                $updateStatusPerson['person_id'] = $person_id;
                                $updateStatusPerson['date'] = date('d-m-Y H:i:s', time());
                                return $this->returnResultSuccess($updateStatusPerson);
                            }
                        }
                    }
                }else{
                    $data['name_job'] = 'Không có thông tin chuyển công tác';
                    $data['person_id'] = $arrPersonId;
                    $data['date'] = date('d-m-Y H:i:s', time());
                    return $this->returnResultSuccess($data);
                }

            }else{
                $data['name_job'] = 'Không có thông tin nhân sự 1';
                $data['person_id'] = $arrPersonId;
                $data['date'] = date('d-m-Y H:i:s', time());
                return $this->returnResultSuccess($data);
            }

        }else{
            $data['name_job'] = 'Không có thông tin nhân sự 2';
            $data['person_id'] = $arrPersonId;
            $data['date'] = date('d-m-Y H:i:s', time());
            return $this->returnResultSuccess($data);
        }
        return $this->returnResultError($dataQuitJob);
    }

    //tính ngày nghỉ hưu lại theo số năm nghỉ hưu trong depart của mỗi user
    public function runPustDateRetirement(){
        $search['person_status'] = Define::$arrStatusPersonAction;
        $search['person_search_job_project'] = 1;
        $search['field_get'] = 'person_id,person_depart_id,person_sex,person_birth';//cac truong can lay
        $data = Person::searchByCondition($search, CGlobal::number_show_1000, 0, $total);

        if(!empty($data)){
            foreach ($data as $k =>$person){
                //Tính ngày nghỉ hưu: hr_retirement field: retirement_date
                if (isset($person->person_depart_id) && isset($person->person_sex) && $person->person_depart_id > 0 && isset($person->person_birth) && abs($person->person_birth) > 0) {
                    //lấy thông tin số năm nghỉ hưu từ depart
                    $depart_config = DepartmentConfig::getItemByDepartmentId($person->person_depart_id);

                    if (isset($depart_config->department_config_id) && $depart_config->department_config_id > 0) {
                        $year_now = date('Y', time());
                        $year_brith = date('Y', $person->person_birth);
                        $month_brith = date('m', $person->person_birth);
                        $date_brith = date('d', $person->person_birth);

                        // tính số ngày nghỉ hưu của nhân sự theo setup của depart
                        $numberYearNghihuu = ($person->person_sex == CGlobal::status_hide) ? $depart_config->department_retired_age_min_girl : $depart_config->department_retired_age_min_boy;
                        $soTuoiHienTai = abs($year_now - $year_brith);
                        $soNamSapNghiHuu = $numberYearNghihuu - $soTuoiHienTai;
                        if($soNamSapNghiHuu > 0){
                            $yearNghiHuuChinhThuc = $year_now +$soNamSapNghiHuu;
                            $timeNghiHuuChinhThuc = $yearNghiHuuChinhThuc.'/'.$month_brith.'/'.$date_brith;
                            $dataUpdateRetirement['retirement_date'] = strtotime ($timeNghiHuuChinhThuc) ;
                        }else{
                            $dataUpdateRetirement['retirement_date'] = $person->person_birth ;
                        }
                        if(!empty($dataUpdateRetirement)){
                            $dataUpdateRetirement['retirement_person_id'] = $person->person_id;
                            $dataUpdateRetirement['retirement_project'] = $person->person_project;
                            $retirement = Retirement::getRetirementByPersonId($person->person_id);
                            if (isset($retirement->retirement_id)) {
                                Retirement::updateItem($retirement->retirement_id, $dataUpdateRetirement);
                            } else {
                                $dataUpdateRetirement['retirement_note'] = 'Tính ngày nghỉ hưu từ ngày sinh và thuộc depart';
                                Retirement::createItem($dataUpdateRetirement);
                            }
                        }
                    }
                }
            }
        }
    }
    //Sắp nghỉ hưu và nghỉ hưu
    public function runCronjobRetirement(){
        $dataSearch['person_status'] = array(Define::PERSON_STATUS_DANGLAMVIEC, Define::PERSON_STATUS_SAPNGHIHUU);
        $dataSearch['person_search_job_project'] = 1;
        $dataSearch['field_get'] = 'person_id';
        $dataPerson = Person::searchByCondition($dataSearch, $this->limit, $this->offset, $this->total);
        $arrPersonId = array();
        if(count($dataPerson) > 0){
            foreach ($dataPerson as $va){
                $arrPersonId [$va->person_id] = $va->person_id;
            }

            if(!empty($arrPersonId)){
                $dataRetirement = Retirement::whereIn('retirement_person_id', $arrPersonId)->get();
                $msg = ' cronjob nghỉ hưu';
                if(count($dataRetirement) > 0){
                    foreach ($dataRetirement as $val){
                        $person_id = $val->retirement_person_id;
                        $date1 = $val->retirement_date_creater;//ngày ra quyết định nghỉ hưu
                        $date2 = $val->retirement_date_notification;//ngày ra thông báo nghỉ hưu
                        $date3 = $val->retirement_date;//ngày nghỉ hưu chính thức

                        $time1 = date('Ymd',$date1);
                        $time2 = date('Ymd',$date2);
                        $time3 = date('Ymd',$date3);
                        $timeNow = date('Ymd',time());
                        $time7day = date('Ymd',time()-7*24*60*60);

                        $update_flg = false;
                        $person_status = Define::PERSON_STATUS_DANGLAMVIEC;
                        if(($time7day <= $time1 && $time1 <= $timeNow) || $time7day <= $time2 && $time2 <= $timeNow){
                            $update_flg = true;
                            $person_status = Define::PERSON_STATUS_SAPNGHIHUU;
                            $msg = ' sắp nghỉ hưu';
                        }
                        if(($timeNow <= $time1) || ($timeNow <= $time2) || ($timeNow <= $time3)){
                            $update_flg = true;
                            $person_status = Define::PERSON_STATUS_NGHIHUU;
                            $msg = ' đã nghỉ hưu';
                        }
                        if($update_flg){
                            $updateStatusPerson['person_status'] = $person_status;
                            if(Person::updateItem($person_id,$updateStatusPerson)){
                                $updateStatusPerson['name_job'] = 'Cập nhật thành công NS'. $msg;
                                $updateStatusPerson['person_id'] = $person_id;
                                $updateStatusPerson['date'] = date('d-m-Y H:i:s', time());
                                return $this->returnResultSuccess($updateStatusPerson);
                            }
                        }
                    }
                }else{
                    $data['name_job'] = 'Không có thông tin '. $msg;
                    $data['person_id'] = $arrPersonId;
                    $data['date'] = date('d-m-Y H:i:s', time());
                    return $this->returnResultSuccess($data);
                }

            }else{
                $data['name_job'] = 'Không có thông tin nhân sự 1';
                $data['person_id'] = $arrPersonId;
                $data['date'] = date('d-m-Y H:i:s', time());
                return $this->returnResultSuccess($data);
            }

        }else{
            $data['name_job'] = 'Không có thông tin nhân sự 2';
            $data['person_id'] = $arrPersonId;
            $data['date'] = date('d-m-Y H:i:s', time());
            return $this->returnResultSuccess($data);
        }
        return $this->returnResultError($dataRetirement);
    }

    //Tính lương cho tháng hiện tại của NS
    public function runCronjobPayroll(){
        $dataSearch['person_status'] = Define::$arrStatusPersonAction;
        $dataSearch['person_search_job_project'] = 1;
        $dataSearch['field_get'] = 'person_id';
        $dataPerson = Person::searchByCondition($dataSearch, $this->limit, $this->offset, $this->total);
        $arrPersonId = array();
        if(count($dataPerson) > 0){
            foreach ($dataPerson as $va){
                $arrPersonId [$va->person_id] = $va->person_id;
            }

            if(empty($arrPersonId)){
                $data['name_job'] = 'Không có thông tin dữ';
                $data['person_id'] = $arrPersonId;
                $data['date'] = date('d-m-Y H:i:s', time());
                return $this->returnResultError(array());
            }
            $infoPayrollNow = array();
            $infoPayrollFirst = array();
            $month_now = date('m',time());
            $year_now = date('Y',time());
            $month_first = ((int)$month_now == 1) ? 12 :date('m',strtotime ( '-1 month' , time() ) );
            $year_first = ((int)$month_now == 1) ? $year_now -1 :date('Y',strtotime ( '-1 year' , time() ) );

            //data month first
            $dataSearch1['reportMonth'] = $month_now;
            $dataSearch1['reportYear'] = $year_now;
            $dataSearch1['payroll_person_id'] = $arrPersonId;
            $data1 = Payroll::searchByCondition($dataSearch1, count($arrPersonId), $this->offset, $total1);
            if($total1 > 0){
                foreach ($data1 as $v1){
                    $infoPayrollNow[$v1->payroll_person_id] = $v1->payroll_person_id;
                }
            }

            //data month firth
            $dataSearch2['reportMonth'] = $month_first;
            $dataSearch2['reportYear'] = $year_first;
            $dataSearch2['payroll_person_id'] = $arrPersonId;
            $data2 = Payroll::searchByCondition($dataSearch2, count($arrPersonId), $this->offset, $total2);
            if($total2 > 0){
                foreach ($data2 as $v2){
                    $infoPayrollFirst[$v2->payroll_person_id] = array(
                        'payroll_project'=>$v2->payroll_project,
                        'payroll_person_id'=>$v2->payroll_person_id,
                        'payroll_month'=>$v2->payroll_month,
                        'payroll_year'=>$v2->payroll_year,
                        'ma_ngach'=>$v2->ma_ngach,
                        'he_so_luong'=>$v2->he_so_luong,
                        'phu_cap_chuc_vu'=>$v2->phu_cap_chuc_vu,
                        'phu_cap_tham_nien_vuot'=>$v2->phu_cap_tham_nien_vuot,
                        'phu_cap_tham_nien_vuot_heso'=>$v2->phu_cap_tham_nien_vuot_heso,
                        'phu_cap_trach_nhiem'=>$v2->phu_cap_trach_nhiem,
                        'phu_cap_tham_nien'=>$v2->phu_cap_tham_nien,
                        'phu_cap_tham_nien_heso'=>$v2->phu_cap_tham_nien_heso,
                        'phu_cap_nghanh'=>$v2->phu_cap_nghanh,
                        'phu_cap_nghanh_heso'=>$v2->phu_cap_nghanh_heso,
                        'tong_he_so'=>$v2->tong_he_so,
                        'luong_co_so'=>$v2->luong_co_so,
                        'tong_tien'=>$v2->tong_tien,
                        'tong_tien_luong'=>$v2->tong_tien_luong,
                        'tong_tien_baohiem'=>$v2->tong_tien_baohiem,
                        'tong_luong_thuc_nhan'=>$v2->tong_luong_thuc_nhan,
                    );
                }
            }

            //action creater
            $count = 0;
            if(!empty($infoPayrollFirst)){
                foreach ($infoPayrollFirst as $personId => $val){
                    if(!isset($infoPayrollNow[$personId])){
                        $dataCreater = $val;
                        $dataCreater['payroll_month'] = ($val['payroll_month'] == 12)? 1: $val['payroll_month']+1;
                        $dataCreater['payroll_year'] = ($val['payroll_month'] == 12)? $val['payroll_year']+1: $val['payroll_year'];
                        $p = Payroll::createItem($dataCreater);
                        if($p > 0){
                            $count ++;
                        }
                    }
                }
            }

            $data['name_job'] = 'Them luong cho payroll';
            $data['number_action'] = $count;
            $data['date'] = date('d-m-Y H:i:s', time());
            return $this->returnResultSuccess($data);
        }else{
            $data['name_job'] = 'Không có thông tin nhân sự 2';
            $data['person_id'] = $arrPersonId;
            $data['date'] = date('d-m-Y H:i:s', time());
            return $this->returnResultSuccess($data);
        }
        return $this->returnResultError(array());
    }

}
