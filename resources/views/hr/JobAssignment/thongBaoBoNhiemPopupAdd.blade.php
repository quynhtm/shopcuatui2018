<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Thông báo/ Đề nghị bổ nhiệm chức vụ</h4>
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
            <input type="hidden" name="job_assignment_id" id="job_assignment_id" value="{{$job_assignment_id}}">
            <input type="hidden" name="typeAction" id="typeAction" value="{{$typeAction}}">
            <input id="id_hiden" name="id_hiden" value="{{FunctionLib::inputId($job_assignment_id)}}" type="hidden">
            <input id="id_hiden_person" name="id_hiden_person" value="{{FunctionLib::inputId($person_id)}}"type="hidden">

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Chức vụ cũ<span class="red"> (*) </span></label>
                        <select name="job_assignment_define_id_old" id="job_assignment_define_id_old"  class="form-control input-sm input-required">
                            {!! $optionChucVuOld !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Chức vụ mới<span class="red"> (*) </span></label>
                        <select name="job_assignment_define_id_new"id="job_assignment_define_id_new" class="form-control input-sm input-required">
                            {!! $optionChucVuNew !!}
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Bổ nhiệm từ ngày</label>
                        <input type="text" class="form-control" id="job_assignment_date_start" name="job_assignment_date_start"  data-date-format="dd-mm-yyyy" value="@if(isset($data['job_assignment_date_start']) && $data['job_assignment_date_start'] > 0){{date('d-m-Y',$data['job_assignment_date_start'])}}@endif">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Bổ nhiệm đến ngày</label>
                        <input type="text" class="form-control" id="job_assignment_date_end" name="job_assignment_date_end"  data-date-format="dd-mm-yyyy" value="@if(isset($data['job_assignment_date_end']) && $data['job_assignment_date_end'] > 0){{date('d-m-Y',$data['job_assignment_date_end'])}}@endif">
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Ghi chú</label>
                        <input type="text" class="form-control" id="job_assignment_note" name="job_assignment_note"  value="@if(isset($data['job_assignment_note'])){{$data['job_assignment_note']}}@endif">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="controls">
                        <a href="javascript:;"class="btn btn-primary link-button" onclick="baseUpload.uploadDocumentAdvanced({{Define::FILE_TYPE_JOB_ASSIGNMENT}});">Tải tệp đính kèm</a>
                        <div id="sys_show_file">
                            @if(isset($data->job_assignment_file_attack) && $data->job_assignment_file_attack !='')
                                <?php $arrfiles = ($data->job_assignment_file_attack != '') ? unserialize($data->job_assignment_file_attack) : array(); ?>
                                @foreach($arrfiles as $_key=>$file)
                                    <div class="item-file item_{{$_key}}"><a target="_blank" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_JOB_ASSIGNMENT.'/'.$job_assignment_id.'/'.$file}}">{{$file}}</a><span data="{{$file}}" class="remove_file" onclick="baseUpload.deleteDocumentUpload('{{FunctionLib::inputId($job_assignment_id)}}', {{$_key}}, '{{$file}}',{{Define::FILE_TYPE_JOB_ASSIGNMENT}})">X</span></div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                {!! csrf_field() !!}
                <div class="col-sm-6">
                    <a class="btn btn-primary" href="javascript:void(0);" onclick="HR.submitPopupCommon('form#form_poup_ajax','jobAssignment/postJobAssignment','div_list_bo_nhiem','submitPopup')" id="submitPopup"><i class="fa fa-floppy-o"></i> Lưu lại</a>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" aria-hidden="true"><i class="fa fa-reply"></i> Thoát</button>
                </div>
            </div>
        </form>
</div>
<script>
    $(document).ready(function(){
        var time1 = $('#job_assignment_date_start').datepicker({ });
        var time2 = $('#job_assignment_date_end').datepicker({ });
    });
</script>