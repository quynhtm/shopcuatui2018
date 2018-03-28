<?php use App\Library\AdminFunction\FunctionLib; ?>
<div class="panel panel-primary">
    <div class="panel-heading paddingTop1 paddingBottom1">
        <h4>
            @if(isset($data['role_id']) && $data['role_id'] != '')
                <i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">Sửa quyền</span>
            @else
                <i class="fa fa-plus-square icChage" aria-hidden="true"></i> <span class="frmHead">Thêm mới</span>
            @endif
        </h4>
    </div>
    <div class="panel-body">
        <form id="form" method="post">
            <input type="hidden" name="id" @if(isset($data['role_id']))value="{{$data['role_id']}}"@endif class="form-control" id="id">
            <div class="form-group">
                <label for="role_name">Tên role</label>
                <input type="text" name="role_name" title="Tên role" class="form-control input-required" id="role_name" @if(isset($data['role_name']))value="{{$data['role_name']}}"@endif>
            </div>
            <div class="form-group">
                <label for="role_order">Thứ tự hiển thị</label>
                <input type="text" name="role_order" title="Thứ tự hiển thị" class="form-control input-required" id="role_order" @if(isset($data['role_order']))value="{{$data['role_order']}}"@endif>
            </div>
            <div class="form-group">
                <label for="role_status">Trạng thái</label>
                <select class="form-control input-sm" name="role_status" id="role_status">
                    {!! $optionStatus !!}
                </select>
            </div>
            <a class="btn btn-success" id="submit" onclick="HR.addItem('form#form', 'form#form :input', '#submit', WEB_ROOT + '/manager/role/addRole/' + '{{FunctionLib::inputId($data['role_id'])}}')"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</a>
            <a class="btn btn-default" id="cancel" onclick="HR.resetItem('#id', '{{FunctionLib::inputId($data["role_id"])}}')"><i class="fa fa-undo" aria-hidden="true"></i> Reset</a>
        </form>
    </div>
</div>
