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
                        <div class="panel-body">

                            <div class="form-group col-lg-3">
                                <label for="name"><i>{{viewLanguage('Tên Sản Phẩm')}}</i></label>
                                <input type="text" class="form-control input-sm" id="name" name="product_name" placeholder="Tên sản phẩm" @if(isset($search['product_name']))value="{{$search['product_name']}}"@endif>
                            </div>

                            {{--<div class="form-group col-lg-3">--}}
                                {{--<label for="name"><i>{{viewLanguage('Giá Bán')}}</i></label>--}}
                                {{--<input type="text" class="form-control input-sm" id="name" name="product_price_sell" placeholder="Tên bán" @if(isset($search['product_price_sell']))value="{{$search['product_price_sell']}}"@endif>--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-lg-3">--}}
                                {{--<label for="name"><i>{{viewLanguage('Giá Trị Trường')}}</i></label>--}}
                                {{--<input type="text" class="form-control input-sm" id="name" name="product_price_market" placeholder="Tên thị trường" @if(isset($search['product_price_market']))value="{{$search['product_price_market']}}"@endif>--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-lg-3">--}}
                                {{--<label for="name"><i>{{viewLanguage('Giá Nhập')}}</i></label>--}}
                                {{--<input type="text" class="form-control input-sm" id="name" name="product_price_input" placeholder="Tên nhập" @if(isset($search['product_price_input']))value="{{$search['product_price_input']}}"@endif>--}}
                            {{--</div>--}}

                            <div class="form-group col-lg-3">
                                <label for="name" class="control-label">{{viewLanguage('Trạng thái')}}</label>
                                <select name="product_status" id="name" class="form-control input-sm">
                                    {!! $optionStatus !!}}
                                </select>
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
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> item @endif </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="" >
                                <th width="3%" class="text-center">{{viewLanguage('STT')}}</th>
                                <th width="55%">{{viewLanguage('Tên Sản Phẩm')}}</th>
                                <th width="15%">{{viewLanguage('Giá Bán')}}</th>
                                <th width="15%">{{viewLanguage('Giá Nhập')}}</th>
                                <th width="5%" class="text-center">{{viewLanguage('Trạng thái')}}</th>
                                <th width="7%" class="text-center">{{viewLanguage('Thao tác')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $key => $item)
                                <tr>
                                    <td class="text-center middle">{{ $stt+$key+1 }}</td>
                                    <td>{{ $item['product_name'] }}</td>
                                    <td>{{ $item['product_price_sell'] }}</td>
                                    <td>{{ $item['product_price_input'] }}</td>
                                    <td class="text-center middle">
                                        @if($item['product_status'] == STATUS_SHOW)
                                            <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                        @else
                                            <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                        @endif
                                    </td>

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
            </div>
        </div>
    </div>
</div>
@stop