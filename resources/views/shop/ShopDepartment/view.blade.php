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
                <a href="{{URL::route('shop.department')}}">{{$pageAdminTitle}}</a>
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
                                <input type="text" class="form-control input-sm" name="department_name" placeholder="{{viewLanguage('Tên kiểu chuyên mục')}}" @if(isset($search['department_name']))value="{{$search['department_name']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="define_status" class="control-label">{{viewLanguage('Trạng thái')}}</label>
                                <select name="department_status" id="department_status" class="form-control input-sm">
                                    {!! $optionStatusSearch !!}}
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            @if($is_root || $permission_full || $permission_create)
                                <a class="btn btn-danger btn-sm" href="{{URL::route('shop.department')}}">
                                    <i class="ace-icon fa fa-plus-circle"></i>
                                    {{viewLanguage('Thêm mới')}}
                                </a>
                            @endif
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> {{viewLanguage('Tìm kiếm')}}</button>
                        </div>
                    </form>
                    <div class="panel-body line" id="element">
                        <table class="table table-bordered bg-head-table">
                            <thead>
                            <tr>
                                <th class="text-center w10">{{viewLanguage('STT')}}</th>
                                <th>{{viewLanguage('Tên kiểu')}}</th>
                                <th>{{viewLanguage('Thứ tự')}}</th>
                                <th>{{viewLanguage('Ngày tạo')}}</th>
                                <th>{{viewLanguage('Ngày cập nhật')}}</th>
                                <th>{{viewLanguage('Trạng thái')}}</th>
                                <th class="text-center" width="9%">{{viewLanguage('Hoạt động')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($data) && sizeof($data))
                                @foreach($data as $k=>$item)
                                <tr align="center">
                                    <td class="text-center" width="1%">{{$stt + $k + 1 }}</td>
                                    <td>{{$item['department_name']}}</td>
                                    <td width="3%">{{$item['department_order']}}</td>
                                    <td>{{$item['created_at']}}</td>
                                    <td>{{$item['updated_at']}}</td>
                                    <td width="3%">
                                    @if($item['department_status'] == STATUS_SHOW)
                                        <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                    @else
                                        <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                    @endif
                                    </td>
                                    <td class="text-center middle" align="center">
                                        @if($is_root || $permission_full || $permission_create)
                                            <a class="editItem" onclick="BE.editItem('{{$item->department_id}}', WEB_ROOT + '/manager/department/ajaxLoad' )" title="{{viewLanguage('Sửa')}}">
                                                <i class="fa fa-edit fa-2x"></i>
                                            </a>
                                            
                                        @endif
                                        @if($is_root || $permission_full || $permission_delete)
                                            <a href="javascript:void(0);" onclick="BE.deleteItem('{{$item->department_id}}', WEB_ROOT + '/manager/department/delete')" title="{{viewLanguage('Xóa')}}">
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
                            <div class="form-group">
                                <label for="define_name">{{viewLanguage('Tên kiểu chuyên mục')}}</label>
                                <input name="department_name" title="{{viewLanguage('Tên kiểu chuyên mục')}}" class="form-control input-required" id="department_name" type="text">
                            </div>
                            <div class="form-group">
                                <label for="define_name">{{viewLanguage('Thứ thự')}}</label>
                                <input name="department_order" title="{{viewLanguage('Thứ thự')}}" class="form-control" id="department_order" type="text">
                            </div>

                            <div class="form-group">
                                <label for="define_status">{{viewLanguage('Trạng thái')}}</label>
                                <select class="form-control input-sm" name="department_status" id="department_status">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                            @if($is_root || $permission_full || $permission_create)
                            <a class="btn btn-success" id="submit" onclick="BE.addItem('form#formAdd', 'form#formAdd :input', '#submit', WEB_ROOT + '/manager/department/post/0')">
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