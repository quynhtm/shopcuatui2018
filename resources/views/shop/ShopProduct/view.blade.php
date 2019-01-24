<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed top_nav" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Trang chủ</a>
            </li>
            <li class="active">
                <a href="{{URL::route('shop.productView')}}">Quản lý Sản Phẩm</a>
            </li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
                <div class="col-xs-12">
                <div class="panel panel-info">

                    <form method="Post" action="" role="form">
                        {{ csrf_field() }}
                        <div class="panel-body" >
                            <div class="form-group col-lg-3">
                                <label for="name"><i>{{viewLanguage('ID sản phẩm')}}</i></label>
                                <input type="text" class="form-control input-sm" id="name" name="product_code" placeholder="Tên bán" @if(isset($search['product_code']))value="{{$search['product_code']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="name"><i>{{viewLanguage('Tên Sản Phẩm')}}</i></label>
                                <input type="text" class="form-control input-sm" id="name" name="product_name" placeholder="Tên sản phẩm" @if(isset($search['product_name']))value="{{$search['product_name']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="name" class="control-label">{{viewLanguage('Trạng thái')}}</label>
                                <select name="product_status" id="name" class="form-control input-sm">
                                    {!! $optionStatus !!}}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="name" class="control-label">{{viewLanguage('Loại sản phẩm')}}</label>
                                <select name="product_is_hot" id="name" class="form-control input-sm">
                                    {!! $optionProducttype !!}}
                                </select>
                            </div>

                            <div class="clearfix"></div>
                            <div class="form-group col-lg-3">
                                <label for="name" class="control-label">{{viewLanguage('Thuộc chuyên mục')}}</label>
                                <select name="category_id"  id="name" class="form-control input-sm">
                                    {!! $optionCategory !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="name" class="control-label">{{viewLanguage('Thuộc tính danh mục')}}</label>
                                <select name="depart_id" id="name" class="form-control input-sm">
                                    {!! $optionDepart !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="name" class="control-label">{{viewLanguage('Sản phẩm của shop')}}</label>
                                <select name="user_shop_name" id="name" class="form-control input-sm">
{{--user_shop_name--}}              {!! $optionProvider !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="name"><i>{{viewLanguage('Người đăng sản phẩm')}}</i></label>
{{--user_name_creater--}}       <input type="text" class="form-control input-sm" id="name" name="user_name_creater" placeholder="Chọn người đăng sản phẩm" @if(isset($search['user_name_creater']))value="{{$search['user_name_creater']}}"@endif>
                            </div>

                        </div>
                        <div class="panel-footer text-right">
                            @if($is_root || $permission_full || $permission_create)
                                 <a class="btn btn-danger btn-sm" href="{{URL::route('shop.productEdit',array('id' => 0))}}">
                                    <i class="ace-icon fa fa-plus-circle"></i>
                                     {{viewLanguage('add')}}
                                 </a>
                            @endif
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> {{viewLanguage('search')}}</button>
                        </div>
                    </form>

                </div>

{{--xóa nhiều sản phẩm và đổi trạng thái--}}
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-info">
                            <div class="row">
                            <form method="Post" action="" role="form">
                                {{ csrf_field() }}
                                <div class="panel-body">
                                    <div class="form-group col-lg-3">
                                        <label for="name" class="control-label">{{viewLanguage('Trạng thái chuyển đổi')}}</label>
                                        <select name="product_status" id="name" class="form-control input-sm">
                                            {!! $optionStatus !!}}
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label for="name"><i>{{viewLanguage('Người đăng sản phẩm')}}</i></label>
                                        <input type="text" class="form-control input-sm" id="name" name="product_price_input" placeholder="Chọn người đăng sản phẩm" @if(isset($search['product_price_input']))value="{{$search['product_price_input']}}"@endif>
                                    </div>

                                    <div class="text-right marginTop20">
                                        @if($is_root || $permission_full || $permission_create)
                                            <a class="btn btn-warning btn-sm" href="{{URL::route('shop.productEdit',array('id' => 0))}}">
                                                <i class="ace-icon fa fa-trash"></i>
                                                {{viewLanguage('Xóa Nhiều SP')}}
                                            </a>
                                        @endif
                                        <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-spinner"></i> {{viewLanguage('Đổi Trạng Thái')}}</button>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> item @endif </div>
                    <br>
{{----}}<div class="col-md-13 panel-content loadForm">
            <div class="panel panel-primary">
    {{----}}    <div class=" paddingTop1 paddingBottom1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom" >
                                <tr class="">
                                    <th width="3%" class="text-center" >{{viewLanguage('STT')}}</th>
                                    <th width="7%" class="img text-center">{{viewLanguage('Ảnh')}}</th>
                                    <th width="42%" class="text-center">{{viewLanguage('Thông tin Sản Phẩm')}}</th>
                                    <th width="13%" class="text-center">{{viewLanguage('Giá bán')}}</th>
                                    <th width="14%" class="text-center">{{viewLanguage('Thông tin khác')}}</th>
                                    <th width="10%" class="text-center">{{viewLanguage('Ngày')}}</th>
                                    <th width="6%" class="text-center">{{viewLanguage('Thao tác')}}</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach ($data as $key => $item)
                                <tr>
                                    <td class="text-center middle" >{{ $stt+$key+1 }}</td>

                                    <td align="center"><img src="{{Config::get('config.WEB_ROOT')}}/uploads/product/{{$item->product_image}}" height="70px" width="100px"></td>
                                    <td>
                                        @if($item->product_name != '')<b>SP:</b> {{ $item->product_name }}<br/>@endif
                                        @if($item->category_name != '')<b>CM:</b> {{ $item->category_name }}<br/>@endif
                                        @if($item->depart_id != '')<b>DM:</b> {{ $item->depart_id }}<br/>@endif
                                    </td>

                                    <td>
                                        @if($item->product_price_sell != '')<b>Giá Bán:</b> <b>{{ $item->product_price_sell }}</b><br/>@endif
                                        @if($item->product_price_market != '')<b>Giá Thị trường:</b> {{ $item->product_price_market }}<br/>@endif
                                        @if($item->product_price_input != '')<b>Giá nhập:</b> {{ $item->product_price_input }}<br/>@endif
                                    </td>


                                    <td align="center">
                                        @if($item['is_sale'] > 1)
                                            <p>Tình trạng : <b>Hết hàng</b></p>
                                        @else
                                            <p>Tình trạng : <b>Còn hàng</b></p>
                                        @endif
                                    </td>
                                    <td align="center">{{ 'chưa có cột hiển thị ngày '}}</td>

                                    <td class="text-center middle">
                                        @if($is_root || $permission_full || $permission_create)
                                            <a href="{{URL::route('shop.productEdit',array('id' => $item['product_id']))}}" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>&nbsp;&nbsp;&nbsp;
                                        @endif
                                        @if($is_root || $permission_full || $permission_delete)
                                            <a href="javascript:void(0);" onclick="Admin.deleteItem({{$item['product_id']}},11)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                        @endif
                                        <span class="img_loading" id="img_loading_{{$item['menu_id']}}"></span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        {!! $paging !!}
                    </div>
                @else
                    <div class="alert">
                        Không có dữ liệu
                    </div>
                @endif
{{----}}    </div>
        </div>
    </div>{{----}}
            </div>






{{--add--}}{{-- <div class="col-md-4 panel-content loadForm">--}}
                {{--<div class="panel panel-primary">--}}
                    {{--<div class="panel-heading paddingTop1 paddingBottom1">--}}
                        {{--<h4>--}}
                            {{--<i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">{{viewLanguage('Thêm mới')}}</span>--}}
                        {{--</h4>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<form id="formAdd" method="post">--}}
                            {{--<input name="id_hiden" value="0" class="form-control" id="id_hiden" type="hidden">--}}
                            {{--<div class="form-group col-lg-12">--}}
                                {{--<label for="define_name">{{viewLanguage('Tên NCC')}} <span class="red"> (*) </span></label>--}}
                                {{--<input name="provider_name" title="{{viewLanguage('Tên NCC')}}" class="form-control input-required" id="provider_name" type="text">--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-lg-6">--}}
                                {{--<label for="define_name">{{viewLanguage('Số điện thoại')}}</label>--}}
                                {{--<input name="provider_phone" id="provider_phone" title="{{viewLanguage('Phone nhà cung cấp')}}" class="form-control"  type="text">--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-lg-6">--}}
                                {{--<label for="define_name">{{viewLanguage('Email')}}</label>--}}
                                {{--<input name="provider_email" id="provider_email" title="{{viewLanguage('Email nhà cung cấp')}}" class="form-control"  type="text">--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-lg-12">--}}
                                {{--<label for="define_name">{{viewLanguage('Địa chỉ nhà cung cấp')}}</label>--}}
                                {{--<textarea name="provider_address" id="provider_address" rows="2" class="form-control "></textarea>--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-lg-12">--}}
                                {{--<label for="define_name">{{viewLanguage('Ghi chú nhà cung cấp')}}</label>--}}
                                {{--<textarea name="provider_note" id="provider_note" rows="2" class="form-control"></textarea>--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-lg-12">--}}
                                {{--<label for="define_status">{{viewLanguage('Trạng thái')}}</label>--}}
                                {{--<select class="form-control input-sm" name="provider_status" id="provider_status">--}}
                                    {{--{!! $optionStatus !!}--}}
                                {{--</select>--}}
                            {{--</div>--}}
                            {{--@if($is_root || $permission_full || $permission_create)--}}
                                {{--<a class="btn btn-success" id="submit" onclick="BE.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/provider/post/0')">--}}
                                    {{--<i class="fa fa-floppy-o" aria-hidden="true"></i> {{viewLanguage('Lưu')}}--}}
                                {{--</a>--}}
                            {{--@endif--}}
                            {{--<a class="btn btn-default" id="cancel" onclick="BE.resetItem('#id_hiden', '0')">--}}
                                {{--<i class="fa fa-undo" aria-hidden="true"></i> {{viewLanguage('Làm lại')}}--}}
                            {{--</a>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

        </div>
    </div>

</div>
@stop