<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
use App\Library\PHPThumb\ThumbImg;
use App\Library\AdminFunction\CGlobal;
?>

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Thông tin nhân sự</h4>
    </div>

    <div class="modal-body" style="height: 300px">
        <div class="float_left col-sm-3">
            <div style="width: 200px; height: 250px; overflow: hidden">
                @if($infoPerson->person_avatar != '')
                    <img style="width: 90%" src="{{ThumbImg::thumbBaseNormal(Define::FOLDER_PERSONAL, $infoPerson->person_avatar, Define::sizeImage_240, Define::sizeImage_300, '', true, true)}}"/>
                @else
                    <img style="width: 90%" src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/icon/no-profile-image.gif"/>
                @endif
            </div>
        </div>

        <div class="float_left col-sm-3">
            <div class="form-group">
                Họ và tên: <span class="color_msg">{{ $infoPerson->person_name }}</span>
            </div>
            <div class="form-group">
                Điện thoại: <span class="color_msg">{{ $infoPerson->person_phone }}</span>
            </div>
                <div class="form-group">
                Email: <span class="color_msg">{{ $infoPerson->person_mail }}</span>
                </div>
            <div class="form-group">
                Số CMT: <span class="color_msg">{{ $infoPerson->person_chung_minh_thu }}</span>
            </div>
            <div class="form-group">
                Ngày cấp: <span class="color_msg">{{ ($infoPerson->person_date_range_cmt != 0) ? (date('d/m/Y', $infoPerson->person_date_range_cmt)) : '' }}</span>
            </div>
            <div class="form-group">
                Nơi cấp: <span class="color_msg">{{ $infoPerson->person_issued_cmt }}</span>
            </div>
        </div>

        <div class="float_left col-sm-3">
            <div class="form-group">
            Ngày nâng lương: <span class="color_msg">{{ ($infoPerson->person_date_salary_increase != 0) ? (date('d/m/Y', $infoPerson->person_date_salary_increase)) : '' }}</span>
            </div>
            @if(isset($infoPerson->salary) && count($infoPerson->salary) > 0)
                <div class="form-group">
                    Nghạch bậc: <span class="color_msg">{{ $infoPerson->ngach_bac }}</span>
                </div>
                <div class="form-group">
                    Hệ số lương: <span class="color_msg">{{ $infoPerson->salary[count($infoPerson->salary)-1]->salary_coefficients }}</span>
                </div>
                <div class="form-group">
                    Phụ cấp: <span class="color_msg">{{ $infoPerson->phu_cap }}</span>
                </div>
            @endif
        </div>

        <div class="float_left col-sm-3">
            <div class="form-group">
                Chức danh KHCN: <span class="color_msg">{{ $infoPerson->chuc_danh }}</span>
            </div>
            <div class="form-group">
                Nơi ở hiện nay: <span class="color_msg">{{ $infoPerson->person_address_current }}</span>
            </div>
            <div class="form-group">
                Hộ chiếu phổ thông: <span class="color_msg">{{ isset($infoPerson->passport->passport_common) ? $infoPerson->passport->passport_common : '' }}</span>
            </div>
            <div class="form-group">
                Ngày hết hạn: <span class="color_msg">{{ (isset($infoPerson->passport->passport_common_date_expiration) && $infoPerson->passport->passport_common_date_expiration != 0) ? date('d/m/Y', $infoPerson->passport->passport_common_date_expiration) : '' }}</span>
            </div>
            <div class="form-group">
                Mã số thuế: <span class="color_msg">{{ isset($infoPerson->passport->passport_personal_code) ? $infoPerson->passport->passport_personal_code : '' }}</span>
            </div>
        </div>
    </div>
</div>

