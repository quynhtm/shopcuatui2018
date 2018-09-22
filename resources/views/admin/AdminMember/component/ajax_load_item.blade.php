<?php use App\Library\AdminFunction\FunctionLib; ?>
<div class="panel panel-primary">
    <div class="panel-heading paddingTop1 paddingBottom1">
        <h4>
            @if(isset($data['id']) && $data['id'] > 0)
                <i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">{{viewLanguage('Sửa')}} </span>
            @else
                <i class="fa fa-plus-square icChage" aria-hidden="true"></i> <span class="frmHead">{{viewLanguage('Thêm mới')}}</span>
            @endif
        </h4>
    </div>
    <div class="panel-body">
        <form id="formAdd" method="post">
            <input type="hidden" name="id_hiden" @if(isset($data['id']))value="{{$data['id']}}"@endif class="form-control" id="id_hiden">
            <div class="form-group">
                <label for="member_name">{{viewLanguage('Tên member')}}</label>
                <input name="member_name" title="{{viewLanguage('Tên member')}}" class="form-control input-required" id="member_name" type="text" @if(isset($data['member_name']))value="{{$data['member_name']}}"@endif>
            </div>
            <div class="form-group">
                <label for="member_name">{{viewLanguage('Mô tả')}}</label>
                <textarea name="define_note" id="define_note" cols="30" rows="2" class="form-control input-required">@if(isset($data['define_note'])){{$data['define_note']}}@endif</textarea>
            </div>
            <div class="form-group">
                <label for="member_status">{{viewLanguage('Trạng thái')}}</label>
                <select class="form-control input-sm" name="member_status" id="member_status">
                    {!! $optionStatus !!}
                </select>
            </div>
            <a class="btn btn-success" id="submit" onclick="VM.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/member/post/' + '{{$data["id"]}}')">
                <i class="fa fa-floppy-o" aria-hidden="true"></i> {{viewLanguage('Lưu')}}
            </a>
            <a class="btn btn-default" id="cancel" onclick="VM.resetItem('#id_hiden', '0')">
                <i class="fa fa-undo" aria-hidden="true"></i> {{viewLanguage('Làm lại')}}
            </a>
        </form>
    </div>
</div>