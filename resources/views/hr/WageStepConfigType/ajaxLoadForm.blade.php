<?php use App\Library\AdminFunction\FunctionLib; ?>
<div class="panel panel-primary">
    <div class="panel-heading paddingTop1 paddingBottom1">
        <h4>
            @if(isset($data['wage_step_config_id']) && $data['wage_step_config_id'] > 0)
                <i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">Sửa</span>
            @else
                <i class="fa fa-plus-square icChage" aria-hidden="true"></i> <span class="frmHead">Thêm mới</span>
            @endif
        </h4>
    </div>
    <div class="panel-body">
        <form id="formAdd" method="post">
            <input type="hidden" name="id" @if(isset($data['wage_step_config_id']))value="{{FunctionLib::inputId($data['wage_step_config_id'])}}"@endif class="form-control" id="id">
            <div class="form-group">
                <label for="define_name">Tên thang bảng lương</label>
                <input type="text" name="wage_step_config_name" title="Tên thang bảng lương" class="form-control input-required" id="wage_step_config_name" @if(isset($data['wage_step_config_name']))value="{{$data['wage_step_config_name']}}"@endif>
            </div>
            <div class="form-group">
                <label for="define_order">Thứ tự hiển thị</label>
                <input type="text" name="wage_step_config_order" title="Thứ tự hiển thị" class="form-control" id="wage_step_config_order" @if(isset($data['wage_step_config_order']))value="{{$data['wage_step_config_order']}}"@endif>
            </div>
            <div class="form-group">
                <label for="define_status">Trạng thái</label>
                <select class="form-control input-sm" name="wage_step_config_status" id="wage_step_config_status">
                    {!! $optionStatus !!}
                </select>
            </div>
            <a class="btn btn-success" id="submit" onclick="HR.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/wage-step-config/edit/' + '{{FunctionLib::inputId($data["wage_step_config_id"])}}')">
                <i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu
            </a>
            <a class="btn btn-default" id="cancel" onclick="HR.resetItem('#id', '{{FunctionLib::inputId($data["wage_step_config_id"])}}')">
                <i class="fa fa-undo" aria-hidden="true"></i> Làm lại
            </a>
        </form>
    </div>
</div>