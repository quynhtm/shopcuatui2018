<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Thông tin khen thưởng</h4>
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
        <input type="hidden" name="bonus_id" id="bonus_id" value="{{$bonus_id}}">
        <input id="id_hiden" name="id_hiden" value="{{FunctionLib::inputId($bonus_id)}}" type="hidden">
        <input type="hidden" name="bonus_type" id="bonus_type" value="{{$typeAction}}">
        <input id="id_hiden_person" name="id_hiden_person" value="{{FunctionLib::inputId($person_id)}}"type="hidden">
        <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Khen thưởng<span class="red"> (*) </span></label>
                <select name="bonus_define_id" id="bonus_define_id"  class="form-control input-sm input-required">
                    {!! $optionType !!}
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Năm đạt<span class="red"> (*) </span></label>
                <select name="bonus_year"id="bonus_year" class="form-control input-sm input-required">
                    {!! $optionYears !!}
                </select>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Quyết định kèm theo</label>
                <input type="text" id="bonus_decision" name="bonus_decision" class="form-control input-sm"
                       value="@if(isset($bonus->bonus_decision)){{$bonus->bonus_decision}}@endif">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Thưởng</label>
                <input type="text" id="bonus_number" name="bonus_number"
                       class="form-control input-sm" value="@if(isset($bonus->bonus_number)){{$bonus->bonus_number}}@endif">
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name" class="control-label">Ghi chú</label>
                <input type="text" id="bonus_note" name="bonus_note"
                       class="form-control input-sm"
                       value="@if(isset($bonus->bonus_note)){{$bonus->bonus_note}}@endif">
            </div>
        </div>

        <div class="col-sm-12">
            <div class="controls">
                @if($bonus_id == 0)
                <a href="javascript:;"class="btn btn-primary link-button" onclick="baseUpload.uploadDocumentAdvanced({{Define::FILE_TYPE_KHENTHUONG}});">Tải tệp đính kèm</a>
                @endif
                <div id="sys_show_file">
                    @if(isset($bonus->bonus_file_attack) && $bonus->bonus_file_attack !='')
                        <?php $arrfiles = ($bonus->bonus_file_attack != '') ? unserialize($bonus->bonus_file_attack) : array(); ?>
                        @foreach($arrfiles as $_key=>$file)
                            <div class="item-file item_{{$_key}}"><a target="_blank" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_BONUS.'/'.$bonus_id.'/'.$file}}">{{$file}}</a><span data="{{$file}}" class="remove_file" onclick="baseUpload.deleteDocumentUpload('{{FunctionLib::inputId($bonus_id)}}', {{$_key}}, '{{$file}}',{{Define::FILE_TYPE_KHENTHUONG}})">X</span></div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {!! csrf_field() !!}
        <div class="col-sm-6">
            @if($bonus_id == 0)
            <a class="btn btn-primary" href="javascript:void(0);" onclick="HR.submitPopupCommon('form#form_poup_ajax','bonusPerson/postBonus','div_list_khenthuong','submitPopup')" id="submitPopup"><i class="fa fa-floppy-o"></i> Lưu lại</a>
            @endif
            <button type="button" class="btn btn-warning" data-dismiss="modal" aria-hidden="true"><i class="fa fa-reply"></i> Thoát</button>
        </div>
    </div>
    </form>
</div>
