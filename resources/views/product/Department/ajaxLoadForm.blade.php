<?php use App\Library\AdminFunction\FunctionLib; ?>
<div class="panel panel-primary">
    <div class="panel-heading paddingTop1 paddingBottom1">
        <h4>
            @if(isset($data['department_id']) && $data['department_id'] != '')
                <i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">Sửa quyền</span>
            @else
                <i class="fa fa-plus-square icChage" aria-hidden="true"></i> <span class="frmHead">Thêm mới</span>
            @endif
        </h4>
    </div>
    <div class="panel-body">
        <form id="form" method="post">
            <input type="hidden" name="id" @if(isset($data['department_id']))value="{{$data['department_id']}}"@endif class="form-control" id="id">
            <div class="form-group">
                <label for="role_name">Tên depart</label>
                <input type="text" name="department_name" title="Tên depart" class="form-control input-required" id="department_name" @if(isset($data['department_name']))value="{{$data['department_name']}}"@endif>
            </div>
            <div class="form-group">
                <label for="role_order">Thứ tự hiển thị</label>
                <input type="text" name="department_order" title="Thứ tự hiển thị" class="form-control input-required" id="department_order" @if(isset($data['department_order']))value="{{$data['department_order']}}"@endif>
            </div>
            <div class="form-group">
                <label for="role_status">Trạng thái</label>
                <select class="form-control input-sm" name="department_status" id="department_status">
                    {!! $optionStatus !!}
                </select>
            </div>
            <a class="btn btn-success" id="submit" onclick="HR.addItem('form#form', 'form#form :input', '#submit', WEB_ROOT + '/manager/proDepart/addProDepart/' + '{{FunctionLib::inputId($data['department_id'])}}')"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</a>
            <a class="btn btn-default" id="cancel" onclick="HR.resetItem('#id', '{{FunctionLib::inputId($data["department_id"])}}')"><i class="fa fa-undo" aria-hidden="true"></i> Reset</a>
        </form>
    </div>
</div>
