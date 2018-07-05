<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Thêm mới quan hệ gia đình</h4>
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
            <input type="hidden" name="person_id" id="person_id" value="{{$person_id}}">
            <input type="hidden" name="relationship_id" id="relationship_id" value="{{$relationship_id}}">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Quan hệ<span class="red"> (*) </span></label>
                        <select name="relationship_define_id" id="relationship_define_id" title="Quan hệ"  class="form-control input-sm input-required">
                            {!! $optionType !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Họ và tên<span class="red"> (*) </span></label>
                        <input type="text" id="relationship_human_name" name="relationship_human_name" class="form-control input-sm"
                               value="@if(isset($data->relationship_human_name)){{$data->relationship_human_name}}@endif">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Năm sinh</label>
                        <select name="relationship_year_birth"id="relationship_year_birth" title="Năm sinh" class="form-control input-sm input-required">
                            {!! $optionYears !!}
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Mô tả Quê quán, nghề nghiệp, chức danh, nơi ở ...vv</label>
                        <input type="text" id="relationship_describe" name="relationship_describe"
                               class="form-control input-sm"
                               value="@if(isset($data->relationship_describe)){{$data->relationship_describe}}@endif">
                    </div>
                </div>
                {!! csrf_field() !!}
                <div class="col-sm-6">
                    <a class="btn btn-primary" href="javascript:void(0);" onclick="HR.submitPopupCommon('form#form_poup_ajax','curriculumVitaePerson/postFamily','div_quan_he_gia_dinh','submitPopup')" id="submitPopup"><i class="fa fa-floppy-o"></i> Lưu lại</a>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" aria-hidden="true"><i class="fa fa-reply"></i> Thoát</button>
                </div>
            </div>
        </form>
</div>

<script>
    $(document).ready(function(){
        var contracts_sign_day = $('#contracts_sign_day').datepicker({ });
        var contracts_effective_date = $('#contracts_effective_date').datepicker({ });
    });
</script>