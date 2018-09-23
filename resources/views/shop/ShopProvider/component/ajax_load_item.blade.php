<?php use App\Library\AdminFunction\FunctionLib; ?>
<div class="panel panel-primary">
    <div class="panel-heading paddingTop1 paddingBottom1">
        <h4>
            @if(isset($data->provider_id) && $data->provider_id > 0)
                <i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">{{viewLanguage('Sửa')}} </span>
            @else
                <i class="fa fa-plus-square icChage" aria-hidden="true"></i> <span class="frmHead">{{viewLanguage('Thêm mới')}}</span>
            @endif
        </h4>
    </div>
    <div class="panel-body">
        <form id="formAdd" method="post">
            <input name="id_hiden" value="{{$data->provider_id}}" class="form-control" id="id_hiden" type="hidden">
            <div class="form-group col-lg-12">
                <label for="define_name">{{viewLanguage('Tên NCC')}} <span class="red"> (*) </span></label>
                <input name="provider_name" title="{{viewLanguage('Tên NCC')}}" class="form-control input-required" id="provider_name" type="text" @if(isset($data->provider_name))value="{{$data->provider_name}}"@endif>
            </div>
            <div class="form-group col-lg-6">
                <label for="define_name">{{viewLanguage('Số điện thoại')}}</label>
                <input name="provider_phone" id="provider_phone" title="{{viewLanguage('Phone nhà cung cấp')}}" class="form-control"  type="text" @if(isset($data->provider_phone))value="{{$data->provider_phone}}"@endif>
            </div>
            <div class="form-group col-lg-6">
                <label for="define_name">{{viewLanguage('Email')}}</label>
                <input name="provider_email" id="provider_email" title="{{viewLanguage('Email nhà cung cấp')}}" class="form-control"  type="text" @if(isset($data->provider_email))value="{{$data->provider_email}}"@endif>
            </div>
            <div class="form-group col-lg-12">
                <label for="define_name">{{viewLanguage('Địa chỉ nhà cung cấp')}}</label>
                <textarea name="provider_address" id="provider_address" rows="2" class="form-control ">@if(isset($data->provider_address)){{$data->provider_address}}@endif</textarea>
            </div>
            <div class="form-group col-lg-12">
                <label for="define_name">{{viewLanguage('Ghi chú nhà cung cấp')}}</label>
                <textarea name="provider_note" id="provider_note" rows="2" class="form-control">@if(isset($data->provider_note)){{$data->provider_note}}@endif</textarea>
            </div>

            <div class="form-group col-lg-12">
                <label for="define_status">{{viewLanguage('Trạng thái')}}</label>
                <select class="form-control input-sm" name="provider_status" id="provider_status">
                    {!! $optionStatus !!}
                </select>
            </div>
            @if($is_root || $permission_full || $permission_create)
                <a class="btn btn-success" id="submit" onclick="BE.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/provider/post/{{$data->provider_id}}')">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> {{viewLanguage('Lưu')}}
                </a>
            @endif
            <a class="btn btn-default" id="cancel" onclick="BE.resetItem('#id_hiden', '0')">
                <i class="fa fa-undo" aria-hidden="true"></i> {{viewLanguage('Làm lại')}}
            </a>
        </form>
    </div>
</div>