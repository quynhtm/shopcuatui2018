<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Thêm mới / cập nhật phụ cấp</h4>
</div>
<img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none" id="img_loading_district">
<div class="modal-body" style="height: 500px; overflow-y: auto">
    @if(isset($infoPerson))
        <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
        <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
        <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
    @endif
    <hr>
    <form method="POST" action="" role="form" id="form_poup_ajax">
        <input type="hidden" name="person_id" id="person_id" value="{{$person_id}}">
        <input type="hidden" name="allowance_id" id="allowance_id" value="{{$allowance_id}}">
        <input id="id_hiden" name="id_hiden" value="{{FunctionLib::inputId($allowance_id)}}" type="hidden">
        <input id="id_hiden_person" name="id_hiden_person" value="{{FunctionLib::inputId($person_id)}}"type="hidden">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name" class="control-label">Chọn loại phụ cấp</label>
                    <select name="allowance_type" id="allowance_type"  class="form-control input-sm input-required">
                        {!! $optionAllowanceType !!}
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name" class="control-label">Từ tháng</label>
                    <select name="allowance_month_start" id="allowance_month_start"  class="form-control input-sm input-required">
                        {!! $optionMonth2 !!}
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name" class="control-label">năm</label>
                    <select name="allowance_year_start" id="allowance_year_start" class="form-control input-sm input-required">
                        {!! $optionYears2 !!}
                    </select>
                </div>
            </div>
            {{--<div class="col-sm-2">
                <div class="form-group">
                    <label for="name" class="control-label">Đến tháng</label>
                    <select name="allowance_month_end" id="allowance_month_end"  class="form-control input-sm input-required">
                        {!! $optionMonth3 !!}
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="name" class="control-label">Năm kết thúc</label>
                    <select name="allowance_year_end" id="allowance_year_end" class="form-control input-sm input-required">
                        {!! $optionYears3 !!}
                    </select>
                </div>
            </div>--}}

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="name" class="control-label col-sm-12 text-left textBold" style="text-align: left!important;">Phụ cấp trả theo hình thức</label>
                    <input type="radio" name="allowance_method_type" value="{{\App\Library\AdminFunction\Define::allowance_method_type_1}}" @if(isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_1) checked @endif > Phụ cấp trọn gói &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="allowance_method_type" value="{{\App\Library\AdminFunction\Define::allowance_method_type_2}}" @if(isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_2) checked @endif> Phụ cấp bằng % lương &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="allowance_method_type" value="{{\App\Library\AdminFunction\Define::allowance_method_type_3}}" @if(isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_3) checked @endif> Phụ cấp theo hệ số
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="name" class="control-label">Phụ cấp bằng tiền</label>
                    <input type="text" id="allowance_method_value_1" name="allowance_method_value_1"
                           class="form-control input-sm" value="@if(isset($data['allowance_method_value']) && isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_1){{$data['allowance_method_value']}}@endif">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="name" class="control-label">Phụ cấp bằng % lương</label>
                    <input type="text" id="allowance_method_value_2" name="allowance_method_value_2"
                           class="form-control input-sm" value="@if(isset($data['allowance_method_value']) && isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_2){{$data['allowance_method_value']}}@endif">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="name" class="control-label">Phụ cấp theo hệ số</label>
                    <input type="text" id="allowance_method_value_3" name="allowance_method_value_3"
                           class="form-control input-sm" value="@if(isset($data['allowance_method_value']) && isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_3){{$data['allowance_method_value']}}@endif">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="device_name" class="control-label">Ghi chú</label>
                    <textarea class="form-control input-sm" id="allowance_note" name="allowance_note" rows="3">@if(isset($data->allowance_note)){!! $data->allowance_note !!}@endif</textarea>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="controls">
                    <a href="javascript:;"class="btn btn-primary link-button" onclick="baseUpload.uploadDocumentAdvanced({{Define::FILE_TYPE_PHUCAP}});">Tải tệp đính kèm</a>
                    <div id="sys_show_file">
                        @if(isset($data->allowance_file_attack) && $data->allowance_file_attack !='')
                            <?php $arrfiles = ($data->allowance_file_attack != '') ? unserialize($data->allowance_file_attack) : array(); ?>
                            @foreach($arrfiles as $_key=>$file)
                                <div class="item-file item_{{$_key}}"><a target="_blank" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_ALLOWANCE.'/'.$allowance_id.'/'.$file}}">{{$file}}</a><span data="{{$file}}" class="remove_file" onclick="baseUpload.deleteDocumentUpload('{{FunctionLib::inputId($allowance_id)}}', {{$_key}}, '{{$file}}',{{Define::FILE_TYPE_PHUCAP}})">X</span></div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {!! csrf_field() !!}
            <div class="col-sm-6">
                <a class="btn btn-primary" href="javascript:void(0);" onclick="HR.submitPopupCommon('form#form_poup_ajax','salaryAllowance/postAllowance','div_list_phucap','submitPopup')" id="submitPopup"><i class="fa fa-floppy-o"></i> Lưu lại</a>
                <button type="button" class="btn btn-warning" data-dismiss="modal" aria-hidden="true"><i class="fa fa-reply"></i> Thoát</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        //var contracts_sign_day = $('#contracts_sign_day').datepicker({ });
        //var contracts_effective_date = $('#contracts_effective_date').datepicker({ });
    });
</script>