<?php use App\Library\AdminFunction\FunctionLib; ?>
<div class="panel panel-primary">
    <div class="panel-heading paddingTop1 paddingBottom1">
        <h4>
            @if(isset($data->department_id) && $data->department_id > 0)
                <i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">{{viewLanguage('Sửa')}} </span>
            @else
                <i class="fa fa-plus-square icChage" aria-hidden="true"></i> <span class="frmHead">{{viewLanguage('Thêm mới')}}</span>
            @endif
        </h4>
    </div>
    <div class="panel-body">
        <form id="formAdd" method="post">
            <input name="id_hiden" value="{{$data->department_id}}" class="form-control" id="id_hiden" type="hidden">
            <div class="form-group">
                <label for="define_name">{{viewLanguage('Tên kiểu chuyên mục')}}</label>
                <input name="department_name" title="{{viewLanguage('Tên kiểu chuyên mục')}}" class="form-control input-required" id="department_name" type="text" @if(isset($data->department_name))value="{{$data->department_name}}"@endif>
            </div>
            <div class="form-group">
                <label for="define_name">{{viewLanguage('Thứ thự')}}</label>
                <input name="department_order" title="{{viewLanguage('Thứ thự')}}" class="form-control" id="department_order" type="text" @if(isset($data->department_order))value="{{$data->department_order}}"@endif>
            </div>

            <div class="form-group">
                <label for="define_status">{{viewLanguage('Trạng thái')}}</label>
                <select class="form-control input-sm" name="department_status" id="department_status">
                    {!! $optionStatus !!}
                </select>
            </div>
            @if($is_root || $permission_full || $permission_create)
                <a class="btn btn-success" id="submit" onclick="BE.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/department/post/{{$data->department_id}}')">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> {{viewLanguage('Lưu')}}
                </a>
            @endif
            <a class="btn btn-default" id="cancel" onclick="BE.resetItem('#id_hiden', '0')">
                <i class="fa fa-undo" aria-hidden="true"></i> {{viewLanguage('Làm lại')}}
            </a>
        </form>
    </div>
</div>