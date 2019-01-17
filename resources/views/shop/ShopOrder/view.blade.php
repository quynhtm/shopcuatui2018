<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
<?php use App\Library\AdminFunction\CGlobal; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li class="active">Đơn hàng</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="panel panel-info">
                    {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                    <div class="panel-body">
                        <div class="form-group col-lg-3">
                            <label for="order_product_name">Tên sản phẩm</label>
                            <input type="text" class="form-control input-sm" id="order_product_name" name="order_product_name" placeholder="Tên sản phẩm" @if(isset($search['order_product_name']))value="{{$search['order_product_name']}}"@endif>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="order_customer_name">Tên khách hàng</label>
                            <input type="text" class="form-control input-sm" id="order_customer_name" name="order_customer_name" placeholder="Tên khách hàng" @if(isset($search['order_customer_name']))value="{{$search['order_customer_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_customer_phone">SĐT khách hàng</label>
                            <input type="text" class="form-control input-sm" id="order_customer_phone" name="order_customer_phone" placeholder="SĐT khách hàng" @if(isset($search['order_customer_phone']))value="{{$search['order_customer_phone']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_customer_email">Email khách hàng</label>
                            <input type="text" class="form-control input-sm" id="order_customer_email" name="order_customer_email" placeholder="Email khách hàng" @if(isset($search['order_customer_email']))value="{{$search['order_customer_email']}}"@endif>
                        </div>

                        {{--<div class="form-group col-lg-3">--}}
                            {{--<label for="name" class="control-label">Đặt hàng từ ngày </label>--}}
                            {{--<div class="form-group">--}}
                                {{--<input type="text" class="form-control" id="time_start_time" name="time_start_time"  data-date-format="dd-mm-yyyy" value="@if(isset($data['time_start_time'])){{date('d-m-Y',$data['time_start_time'])}}@endif">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group col-lg-3">--}}
                            {{--<label for="name" class="control-label">đến ngày</label>--}}
                            {{--<div class="form-group">--}}
                                {{--<input type="text" class="form-control" id="time_end_time" name="time_end_time"  data-date-format="dd-mm-yyyy" value="@if(isset($data['time_end_time'])){{date('d-m-Y',$data['time_end_time'])}}@endif">--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="form-group col-lg-3">
                            <label for="order_status">Trạng thái</label>
                            <select name="order_status" id="order_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                        <div class="form-group col-lg-12 text-right">
                            @if($is_root || $permission_full ==1 || $permission_create == 1)
                                <a class="btn btn-success btn-sm" href="{{URL::route('shop.order')}}"> {{--shop.addOrder--}}
                                    <i class="ace-icon fa fa-plus-circle"></i>
                                    Bán hàng tại shop
                                </a>
                            @endif
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
                @if((!$data) > 0) {{--sizeof($data) > 0--}}
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> đơn hàng @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="5%" class="text-center">STT</th>
                            <th width="15%">Thông tin đơn hàng</th>
                            <th width="8%" class="text-right">Phí ship</th>
                            <th width="10%" class="text-right">Tổng tiền</th>
                            <th width="25%" class="text-left">Thông tin khách hàng</th>
                            <th width="6%" class="text-center">Ngày đặt</th>

                            <th width="6%" class="text-center">Trạng thái</th>
                            <th width="6%" class="text-center">Vận chuyển</th>
                            <th width="10%" class="text-center">Tình trạng ĐH</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center text-middle">{{ $stt + $key+1 }}</td>
                                <td>
                                    Mã ĐH: <b>{{ $item->order_id }}</b>
                                    <br/>Mã SP: <b>{{ $item->order_product_id }}</b>
                                    <br/>Tổng SL: <b>{{ $item->order_total_buy }}</b> SP
                                    @if($item->order_note != '')
                                        <br/>Note ĐH:<span class="red">{{$item->order_note}}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <b class="red">{{ FunctionLib::numberFormat($item->order_money_ship) }} đ</b>
                                </td>
                                <td class="text-right">
                                    <b class="red">{{ FunctionLib::numberFormat($item->order_total_money) }} đ</b>
                                </td>
                                <td>
                                    @if($item->order_customer_name != '')N: <b>{{ $item->order_customer_name }}</b><br/>@endif
                                    @if($item->order_customer_phone != '')P: {{ $item->order_customer_phone }}<br/>@endif
                                    @if($item->order_customer_email != '')E: {{ $item->order_customer_email }}<br/>@endif
                                    @if($item->order_customer_address != '')Add: {{ $item->order_customer_address }}<br/>@endif
                                    @if($item->order_customer_note != '')<span class="red">**KH Ghi chú: {{ $item->order_customer_note }}</span>@endif
                                </td>
                                <td class="text-center text-middle">
                                    @if($item->order_type == CGlobal::order_type_site)
                                        <a href="javascript:void(0);" title="Đặt hàng online-{{$item->order_type}}">
                                            <i class="fa fa-shopping-cart fa-2x"></i>
                                        </a><br/>
                                    @endif
                                    @if($item->order_type == CGlobal::order_type_shop)
                                        <a href="javascript:void(0);" title="Đặt hàng từ shop-{{$item->order_type}}">
                                            <i class="fa fa-home fa-2x"></i>
                                        </a><br/>
                                    @endif
                                    {{ date ('H:i:s d-m-Y',$item->order_time_creater) }}
                                </td>

                                <!--Trạng thái-->
                                <td class="text-center text-middle">
                                    @if($item->order_status == CGlobal::order_status_new)
                                        <a href="javascript:void(0);" title="Đơn hàng mới -{{$item->order_status}}">
                                            <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/order/new.png">
                                        </a>
                                    @endif
                                    @if($item->order_status == CGlobal::order_status_confirm)
                                        <a href="javascript:void(0);" title="Đơn hàng đã xác nhận -{{$item->order_status}}">
                                            <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/order/da-xac-nhan.png">
                                        </a>
                                    @endif
                                    @if($item->order_status == CGlobal::order_status_succes)
                                        <a href="javascript:void(0);" title="Đơn hàng hoàn thành -{{$item->order_status}}">
                                            <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/order/hoan-thanh.png">
                                        </a>
                                    @endif
                                    @if($item->order_status == CGlobal::order_status_remove)
                                        <a href="javascript:void(0);" title="Đơn hàng hủy -{{$item->order_status}}">
                                            <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/order/huy.png">
                                        </a>
                                    @endif
                                </td>

                                <!--Vận chuyển-->
                                <td class="text-center text-middle">
                                    @if($item->order_is_cod == CGlobal::order_cod_chuagiao)
                                        <a href="javascript:void(0);" title="Chưa chuyển hàng -{{$item->order_is_cod}}">
                                            <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/order/delivery_miss.png">
                                        </a>
                                    @endif
                                    @if($item->order_is_cod == CGlobal::order_cod_da_gan)
                                        <a href="javascript:void(0);" title="Đã gán cho COD -{{$item->order_is_cod}}">
                                            <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/order/COD.png">
                                        </a>
                                        <br/>{{$item->order_user_shipper_name}}
                                    @endif
                                    @if($item->order_is_cod == CGlobal::order_cod_danggiao)
                                        <a href="javascript:void(0);" title="COD đang giao hàng -{{$item->order_is_cod}}">
                                            <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/order/delivery_move.png">
                                        </a>
                                        <br/>{{$item->order_user_shipper_name}}
                                    @endif
                                    @if($item->order_is_cod == CGlobal::order_cod_da_giaohang)
                                        <a href="javascript:void(0);" title="COD đã giao hàng -{{$item->order_is_cod}} ">
                                            <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/order/delivery_suss.png">
                                        </a>
                                        <br/>{{$item->order_user_shipper_name}}
                                    @endif
                                    @if($item->order_is_cod == CGlobal::order_cod_hoantra)
                                        <a href="javascript:void(0);" title="COD hoàn trả hàng-{{$item->order_is_cod}}">
                                            <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/order/icon-delivery-cancel.png">
                                        </a>
                                        <br/>{{$item->order_user_shipper_name}}
                                    @endif
                                </td>


                                <td class="text-center text-middle">
                                    @if($is_root || $permission_full || $permission_view_detail)
                                        <a href="{{URL::route('shop.order',array('order_id' => $item->order_id))}}" title="Chi tiết đơn hàng"><i class="fa fa-file-text-o fa-2x"></i></a>   {{--shop.detailOrder--}}
                                    @endif
                                    @if($is_root || $permission_full || $permission_edit ==1  )
                                        &nbsp;&nbsp;<a href="{{URL::route('shop.order',array('order_id' => $item->order_id))}}" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>        {{--shop.detailOrder--}}
                                    @endif
                                    @if($item->order_status != CGlobal::order_status_succes)
                                        @if($is_root || $permission_full || $permission_delete)
                                            &nbsp;&nbsp;<a href="javascript:void(0);" onclick="Admin.deleteItem({{$item->order_id}},4)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                         @endif
                                     @endif
                                    <span class="img_loading" id="img_loading_{{$item->order_id}}"></span>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        {{$paging}}
                    </div>
                @else
                <div class="alert">
                    Không có dữ liệu
                </div>
                @endif
                            <!-- PAGE CONTENT ENDS -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.page-content -->
</div>
@stop
<script>
    $(document).ready(function(){
        var checkin = $('#time_start_time').datepicker({ });
        var checkout = $('#time_end_time').datepicker({ });
    });
    //tim kiem cho shop
    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Không có kết quả'}
        //      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
</script>
