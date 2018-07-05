<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Thêm mới chứng chỉ, văn bằng khác</h4>
</div>
<img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none" id="img_loading_district">
<div class="modal-body">
    @if(isset($infoPerson))
        <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
        <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
        <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
    @endif
    <hr>
    <form method="POST" action="" role="form" id="form_poup_ajax">
        <input type="hidden" name="curriculum_person_id" id="curriculum_person_id" value="{{$person_id}}">
        <input type="hidden" name="curriculum_id" id="curriculum_id" value="{{$curriculum_id}}">
        <input type="hidden" name="curriculum_type" id="curriculum_type" value="{{$typeAction}}">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name" class="control-label">Nơi đào tạo<span class="red"> (*) </span></label>
                    <input type="text" id="curriculum_address_train" name="curriculum_address_train" title="Nơi đào tạo" class="form-control input-sm input-required"
                           value="@if(isset($curriculum->curriculum_address_train)){{$curriculum->curriculum_address_train}}@endif">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name" class="control-label">Tổ chức cấp bằng <span class="red"> (*) </span></label>
                    <input type="text" id="curriculum_formalities_name" name="curriculum_formalities_name" title="Tổ chức cấp bằng" class="form-control input-sm input-required"
                           value="@if(isset($curriculum->curriculum_formalities_name)){{$curriculum->curriculum_formalities_name}}@endif">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name" class="control-label">Văn bằng, Chứng chỉ<span class="red"> (*) </span></label>
                    <input type="text" id="curriculum_certificate_name" name="curriculum_certificate_name" title="Văn bằng" class="form-control input-sm input-required"
                           value="@if(isset($curriculum->curriculum_certificate_name)){{$curriculum->curriculum_certificate_name}}@endif">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name" class="control-label">Chuyên nghành</label>
                    <input type="text" id="curriculum_training_name" name="curriculum_training_name" title="Chuyên nghành" class="form-control input-sm input-required"
                           value="@if(isset($curriculum->curriculum_training_name)){{$curriculum->curriculum_training_name}}@endif">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name" class="control-label">Thời gian đào tạo<span class="red"> (*) </span></label>
                    <select name="curriculum_month_in" id="curriculum_month_in" title="Thời gian đào tạo" class="form-control input-sm input-required">
                        {!! $optionMonthIn !!}
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name" class="control-label">Năm<span class="red"> (*) </span></label>
                    <select name="curriculum_year_in"id="curriculum_year_in" title="Năm" class="form-control input-sm input-required">
                        {!! $optionYearsIn !!}
                    </select>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name" class="control-label">Đến tháng</label>
                    <select name="curriculum_month_out" id="curriculum_month_out"  class="form-control input-sm">
                        {!! $optionMonthOut !!}
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name" class="control-label">Năm</label>
                    <select name="curriculum_year_out"id="curriculum_year_out" class="form-control input-sm">
                        {!! $optionYearsOut !!}
                    </select>
                </div>
            </div>

            {!! csrf_field() !!}
            <div class="col-sm-6">
                <a class="btn btn-primary" href="javascript:void(0);" onclick="HR.submitPopupCommon('form#form_poup_ajax','curriculumVitaePerson/postStudy','div_dao_tao_khac','submitPopup')" id="submitPopup"><i class="fa fa-floppy-o"></i> Lưu lại</a>
                <button type="button" class="btn btn-warning" data-dismiss="modal" aria-hidden="true"><i class="fa fa-reply"></i> Thoát</button>
            </div>
        </div>
    </form>
</div>
