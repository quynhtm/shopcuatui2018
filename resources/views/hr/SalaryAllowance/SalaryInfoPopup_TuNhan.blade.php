<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
use App\Library\PHPThumb\ThumbImg;
use App\Library\AdminFunction\CGlobal;
?>

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Thông tin Lương nhân sự</h4>
    </div>

    <div class="modal-body" style="height: 400px">
        @if(isset($infoPerson))
            <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
            <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
            <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
        @endif
        <hr>
            <div class="float_left col-sm-6">
                <div class="form-group">
                    <div class="col-sm-6">
                        <b>Thời gian</b>:
                    </div>
                    <div class="col-sm-6">{{ $salary->salary_month }}- {{ $salary->salary_year }}</div>
                </div>
                <div class="clear"></div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <b>Thang bảng lương</b>:
                    </div>
                    <div class="col-sm-6">@if(isset($arrThangbangluong[$salary->salary_wage_table])){{ $arrThangbangluong[$salary->salary_wage_table] }}@endif</div>
                </div>
                <div class="clear"></div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <b>Ngạch công chức</b>:
                    </div>
                    <div class="col-sm-6">@if(isset($arrNghachcongchuc[$salary->salary_civil_servants])){{ $arrNghachcongchuc[$salary->salary_civil_servants] }}@endif</div>
                </div>
                <div class="clear"></div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <b>Mã nghạch</b>:
                    </div>
                    <div class="col-sm-6">@if(isset($arrMaNgach[$salary->salary_tariffs])){{ $arrMaNgach[$salary->salary_tariffs] }}@endif</div>
                </div>
                <div class="clear"></div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <b>Bậc lương</b>:
                    </div>
                    <div class="col-sm-6">@if(isset($arrBacluong[$salary->salary_wage])){{ $arrBacluong[$salary->salary_wage] }}@endif</div>
                </div>
                <div class="clear"></div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <b>Hệ số</b>:
                    </div>
                    <div class="col-sm-6">{{ $salary->salary_coefficients }}</div>
                </div>
                <div class="clear"></div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <b>Lương cơ sở</b>:
                    </div>
                    <div class="col-sm-6">{{ $salary->salary_salaries }}</div>
                </div>
                <div class="clear"></div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <b>% lương thực hưởng</b>:
                    </div>
                    <div class="col-sm-6">{{ $salary->salary_percent }}</div>
                </div>
            </div>

            <div class="float_left col-sm-6">
                <div class="form-group">
                    <div class="col-sm-12">
                        <b>Ghi chú</b>:
                    </div>
                    <div class="col-sm-12">
                        {!! $salary->salary_note !!}
                    </div>
                </div>
                <div class="clear"></div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <b>Tệp đính kèm</b>:
                    </div>
                    <div class="col-sm-12">
                        @if(isset($salary->salary_file_attach) && $salary->salary_file_attach !='')
                            <?php $arrfiles = ($salary->salary_file_attach != '') ? unserialize($salary->salary_file_attach) : array(); ?>
                            @foreach($arrfiles as $_key=>$file)
                                <div class="item-file item_{{$_key}}"><a target="_blank" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_SALARY.'/'.$salary->salary_id.'/'.$file}}">{{$file}}</a><span data="{{$file}}" class="remove_file" onclick="baseUpload.deleteDocumentUpload('{{FunctionLib::inputId($salary->salary_id)}}', {{$_key}}, '{{$file}}',11)">X</span></div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
    </div>
</div>

