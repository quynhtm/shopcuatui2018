<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
<?php use App\Library\AdminFunction\CGlobal; ?>

@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed top_nav" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">{{viewLanguage('Trang chủ')}}</a>
            </li>
            <li class="active">
                <a href="{{URL::route('shop.provider')}}">{{$pageAdminTitle}}</a>
            </li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-8 panel-content">
                <div class="panel panel-primary">
                    <div class="panel-heading paddingTop1 paddingBottom1">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> {{$pageAdminTitle}}</h4>
                    </div>
                    <form method="get" action="" role="form">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group col-lg-3">
                                <label><i>{{viewLanguage('Tên kiểu chuyên mục')}}</i></label>
                                <input type="text" class="form-control input-sm" name="provider_name" placeholder="{{viewLanguage('Tên kiểu chuyên mục')}}" @if(isset($search['provider_name']))value="{{$search['provider_name']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="define_status" class="control-label">{{viewLanguage('Trạng thái')}}</label>
                                <select name="provider_status" id="provider_status" class="form-control input-sm">
                                    {!! $optionStatusSearch !!}}
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="search" class="control-label col-lg-12 text-left">&nbsp;</label>
                                @if($is_root || $permission_full || $permission_create)
                                    <a class="btn btn-danger btn-sm" href="{{URL::route('shop.provider')}}">
                                        <i class="ace-icon fa fa-plus-circle"></i>
                                        {{viewLanguage('Thêm mới')}}
                                    </a>
                                @endif
                                <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> {{viewLanguage('Tìm kiếm')}}</button>
                            </div>
                        </div>
                    </form>
                    <div class="panel-body line" id="element">
                        <table class="table table-bordered bg-head-table">
                            <thead>
                            <tr>
                                <th>{{viewLanguage('Thông tin NCC')}}</th>
                                <th>{{viewLanguage('TT liên hệ')}}</th>
                                <th class="text-center">{{viewLanguage('Thao tác')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($data) && sizeof($data))
                                @foreach($data as $k=>$item)
                                <tr>
                                    <td>
                                        @if($item->provider_name != '')<b>{{$item->provider_name}}</b> <br/>@endif
                                        @if($item->provider_phone != '')<b>P:</b> {{$item->provider_phone}} <br/>@endif
                                        @if($item->provider_address != '')<b>Ad:</b> {{$item->provider_address}} <br/>@endif
                                        @if($item->provider_email != '')<b>E:</b> {{$item->provider_email}} <br/>@endif
                                    </td>
                                    <td>{{$item->provider_note}}</td>
                                    <td class="text-center middle" align="center">
                                        @if($is_root || $permission_full || $permission_create)

                                            <a class="editItem" onclick="BE.editItem('{{$item->provider_id}}', WEB_ROOT + '/manager/provider/ajaxLoad')" title="{{viewLanguage('Sửa')}}">
                                                <i class="fa fa-edit fa-2x"></i>
                                            </a>
                                        @endif
                                        <br/>
                                        @if($item->provider_status == STATUS_SHOW)
                                            <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                        @else
                                            <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                        @endif

                                        @if($is_root || $permission_full || $permission_delete)
                                            <br/>
                                            <a href="javascript:void(0);" onclick="BE.deleteItem('{{$item->provider_id}}', WEB_ROOT + '/manager/provider/delete')" title="{{viewLanguage('Xóa')}}">
                                                <i class="fa fa-trash fa-2x"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {!! $paging !!}
                    </div>
                </div>
            </div>
            <div class="col-md-4 panel-content loadForm">
                <div class="panel panel-primary">
                    <div class="panel-heading paddingTop1 paddingBottom1">
                        <h4>
                            <i class="fa fa-edit icChage" aria-hidden="true"></i> <span class="frmHead">{{viewLanguage('Thêm mới')}}</span>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <form id="formAdd" method="post">
                            <input name="id_hiden" value="0" class="form-control" id="id_hiden" type="hidden">
                            <div class="form-group col-lg-12">
                                <label for="define_name">{{viewLanguage('Tên NCC')}} <span class="red"> (*) </span></label>
                                <input name="provider_name" title="{{viewLanguage('Tên NCC')}}" class="form-control input-required" id="provider_name" type="text">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="define_name">{{viewLanguage('Số điện thoại')}}</label>
                                <input name="provider_phone" id="provider_phone" title="{{viewLanguage('Phone nhà cung cấp')}}" class="form-control"  type="text">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="define_name">{{viewLanguage('Email')}}</label>
                                <input name="provider_email" id="provider_email" title="{{viewLanguage('Email nhà cung cấp')}}" class="form-control"  type="text">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="define_name">{{viewLanguage('Địa chỉ nhà cung cấp')}}</label>
                                <textarea name="provider_address" id="provider_address" rows="2" class="form-control "></textarea>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="define_name">{{viewLanguage('Ghi chú nhà cung cấp')}}</label>
                                <textarea name="provider_note" id="provider_note" rows="2" class="form-control"></textarea>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="define_status">{{viewLanguage('Trạng thái')}}</label>
                                <select class="form-control input-sm" name="provider_status" id="provider_status">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                            @if($is_root || $permission_full || $permission_create)
                            <a class="btn btn-success" id="submit" onclick="BE.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/provider/post/0')">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> {{viewLanguage('Lưu')}}
                            </a>
                            @endif
                            <a class="btn btn-default" id="cancel" onclick="BE.resetItem('#id_hiden', '0')">
                                <i class="fa fa-undo" aria-hidden="true"></i> {{viewLanguage('Làm lại')}}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        BE.scrolleTop();
    });
</script>
@stop