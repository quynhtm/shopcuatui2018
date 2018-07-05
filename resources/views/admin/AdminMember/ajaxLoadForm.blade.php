<?php use App\Library\AdminFunction\FunctionLib; ?>
<div class="panel panel-primary">
    <div class="panel-heading paddingTop1 paddingBottom1">
        <h4>
            @if(isset($data['member_id']) && $data['member_id'] > 0)
                <i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">Sửa </span>
            @else
                <i class="fa fa-plus-square icChage" aria-hidden="true"></i> <span class="frmHead">Thêm mới</span>
            @endif
        </h4>
    </div>
    <div class="panel-body">
        <form id="formAdd" method="post">
            <input type="hidden" name="id" value="{{FunctionLib::inputId(0)}}" class="form-control" id="id">
            <div class="form-group col-sm-12">
                <label for="define_name">Tên Member</label>
                <input type="text" name="member_name" title="Tên member" class="form-control input-required" id="member_name" @if(isset($data['member_name']))value="{{$data['member_name']}}"@endif>
            </div>
            <div class="form-group col-sm-12">
                <label for="define_order">Địa chỉ</label>
                <input type="text" name="member_address" class="form-control" id="member_address"  @if(isset($data['member_address']))value="{{$data['member_address']}}"@endif>
            </div>
            <div class="form-group col-sm-6">
                <label for="define_order">Phone</label>
                <input type="text" name="member_phone" class="form-control" id="member_phone"  @if(isset($data['member_phone']))value="{{$data['member_phone']}}"@endif>
            </div>
            <div class="form-group col-sm-6">
                <label for="define_order">Mail</label>
                <input type="text" name="member_mail" class="form-control" id="member_mail"  @if(isset($data['member_mail']))value="{{$data['member_mail']}}"@endif>
            </div>
            <div class="form-group col-sm-6">
                <label for="define_status">Kiểu member</label>
                <select class="form-control input-sm" name="member_type" id="member_type">
                    {!! $optionDefinedType !!}
                </select>
            </div>
            <div class="form-group col-sm-6">
                <label for="define_status">Trạng thái</label>
                <select class="form-control input-sm" name="member_status" id="member_status">
                    {!! $optionStatus !!}
                </select>
            </div>
            <div class="form-group col-sm-6">
                <label for="define_order">Số tiền thanh toán</label>
                <input type="text" name="member_pay_money" class="form-control" id="member_pay_money"  @if(isset($data['member_pay_money']))value="{{$data['member_pay_money']}}"@endif>
            </div>
            <div class="form-group col-sm-6">
                <label for="define_order">Ngày thanh toán</label>
                <input type="text" name="member_date_pay" class="form-control" id="member_date_pay"  @if(isset($data['member_date_pay']))value="{{$data['member_date_pay']}}"@endif>
            </div>
            <div class="form-group col-sm-6">
                <label for="define_order">Ngày giới hạn live</label>
                <input type="text" name="member_time_live" class="form-control" id="member_time_live"  @if(isset($data['member_time_live']))value="{{$data['member_time_live']}}"@endif>
            </div>
            <div class="form-group col-sm-6">
                <label for="define_order">Limit Item</label>
                <input type="text" name="member_limit_item" class="form-control" id="member_limit_item"  @if(isset($data['member_limit_item']))value="{{$data['member_limit_item']}}"@endif>
            </div>
            <div class="form-group col-sm-12">
                <a class="btn btn-success" id="submit" onclick="HR.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/member/edit/' + '{{FunctionLib::inputId($data["member_id"])}}')">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu
                </a>
                <a class="btn btn-default" id="cancel" onclick="HR.resetItem('#id', '{{FunctionLib::inputId($data["member_id"])}}')">
                    <i class="fa fa-undo" aria-hidden="true"></i> Làm lại
                </a>
            </div>
        </form>

    </div>
</div>