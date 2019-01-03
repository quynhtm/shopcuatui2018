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
            <li class="active">{{$pageTitle}}</li>
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
                                <label for="name"><i>{{viewLanguage('Tên banner')}}</i></label>
                                <input type="text" class="form-control input-sm" id="name" name="banner_name" placeholder="Tên banner" @if(isset($search['banner_name']))value="{{$search['banner_name']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="status" class="control-label">{{viewLanguage('Trạng thái')}}</label>
                                <select name="banner_status" id="status" class="form-control input-sm">
                                    {!! $optionStatus !!}}
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            @if($is_root || $permission_full || $permission_create)
                                 <a class="btn btn-danger btn-sm" href="{{URL::route('admin.bannerEdit',array('banner_id' => 0))}}">
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
                            <tr class="">
                                <th width="5%" class="text-center">{{viewLanguage('STT')}}</th>
                                <th width="10%">{{viewLanguage('Image')}}</th>
                                <th width="30%">{{viewLanguage('Tên banner')}}</th>
                                <th width="30%">{{viewLanguage('Url')}}</th>
                                <th width="5%" class="text-center">{{viewLanguage('Trạng thái')}}</th>
                                <th width="20%" class="text-center">{{viewLanguage('Thao tác')}}</th>

                            </tr>

                            </thead>
                            <tbody>
                            @foreach ($data as $key => $item)
                                <tr>
                                    <td class="text-center middle">{{ $stt+1 }}</td>
{{--image--}}                       <td><img src="{{Config::get('config.WEB_ROOT')}}/uploads/banner/{{$item->banner_image}}" height="70px" width="100px"></td>
                                    <td>{{ $item['banner_name'] }}</td>
                                    <td>{{ $item['banner_link'] }}</td>
                                    <td class="text-center middle">
                                        @if($item['banner_status'] == STATUS_SHOW)
                                            <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                        @else
                                            <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                        @endif
                                    </td>

                                    <td class="text-center middle">
                                        @if($is_root || $permission_full || $permission_create)
                                            <a href="{{URL::route('admin.bannerEdit',array('id' => $item['banner_id']))}}" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>&nbsp;&nbsp;&nbsp;
                                        @endif
                                        @if($is_root || $permission_full || $permission_delete)
                                            <a href="javascript:void(0);" onclick=" Admin.deleteItem({{$item['banner_id']}},5)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
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