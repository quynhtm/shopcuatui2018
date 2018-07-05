<?php use App\Library\AdminFunction\FunctionLib; ?>
<div class="panel panel-primary">
    <div class="panel-heading paddingTop1 paddingBottom1">
        <h4>
            @if(isset($data['define_id']) && $data['define_id'] > 0)
                <i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">Sửa </span>
            @else
                <i class="fa fa-plus-square icChage" aria-hidden="true"></i> <span class="frmHead">Thêm mới</span>
            @endif
        </h4>
    </div>
    <div class="panel-body">
        <form id="formAdd" method="post">
            <input type="hidden" name="id" @if(isset($data['define_id']))value="{{FunctionLib::inputId($data['define_id'])}}"@endif class="form-control" id="id">
            <div class="form-group">
                <label for="define_name">Tên định nghĩa</label>
                <input type="text" name="define_name" title="Tên định nghĩa" class="form-control input-required" id="define_name" @if(isset($data['define_name']))value="{{$data['define_name']}}"@endif>
            </div>
            <div class="form-group">
                <label for="define_order">Thứ tự hiển thị</label>
                <input type="text" name="define_order" title="Thứ tự hiển thị" class="form-control" id="define_order" @if(isset($data['define_order']))value="{{$data['define_order']}}"@endif>
            </div>
            <div class="form-group">
                <label for="define_status">Kiểu định nghĩa</label>
                <select class="form-control input-sm" name="define_type" id="define_type">
                    {!! $optionDefinedType !!}
                </select>
            </div>
            <div class="form-group">
                <label for="define_status">Trạng thái</label>
                <select class="form-control input-sm" name="define_status" id="define_status">
                    {!! $optionStatus !!}
                </select>
            </div>
            <a class="btn btn-success" id="submit" onclick="HR.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/defined/edit/' + '{{FunctionLib::inputId($data["define_id"])}}')">
                <i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu
            </a>
            <a class="btn btn-default" id="cancel" onclick="HR.resetItem('#id', '{{FunctionLib::inputId($data["define_id"])}}')">
                <i class="fa fa-undo" aria-hidden="true"></i> Làm lại
            </a>
        </form>
    </div>
</div>