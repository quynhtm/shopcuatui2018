<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">{{FunctionLib::viewLanguage('home')}}</a>
            </li>
            <li class="active">Quản lý đơn vị - phòng ban</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="line">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix paddingTop1 paddingBottom1">
                    <div class="panel-title pull-left">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Quản lý đơn vị - phòng ban</h4>
                    </div>
                    <div class="btn-group btn-group-sm pull-right mgt3">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.departmentEdit',array('id' => FunctionLib::inputId(0)))}}"><i class="fa fa-file"></i>&nbsp;Thêm mới</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if(sizeof($data) > 0)
                            <table class="table table-bordered not-bg">
                                <thead>
                                <tr>
                                    <th class="text-center w10">STT</th>
                                    <th>Tên đơn vị/ Phòng ban</th>
                                    <th class="text-center">Điện thoại</th>
                                    <th class="text-center">Fax</th>
                                    <th>Loại đơn vị/ phòng ban</th>
                                    <th>Đơn vị/ Phòng ban quản lý trực tiếp</th>
                                    <th class="text-center">Cập nhật</th>
                                    <th class="text-center">Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k=>$item)
                                    <tr>
                                        <td class="text-center">{{$stt + $k +1}}</td>
                                        <td>
                                            <a href="{{URL::route('hr.departmentEdit',array('id' => FunctionLib::inputId($item['department_id'])))}}" title="{{$item->department_name}}">{{$item->department_name}}</a>
                                        </td>
                                        <td>{{$item->department_phone}}</td>
                                        <td>{{$item->department_fax}}</td>
                                        <td>
                                            @if(isset($arrDepartmentType[$item['department_type']]) && $item['department_type'] != -1)
                                                {{$arrDepartmentType[$item['department_type']]}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($arrDepartment[$item['department_parent_id']]) && $item['department_parent_id'] != -1)
                                                {{$arrDepartment[$item['department_parent_id']]}}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($item['department_update_time'] > 0)
                                                {{date('d/m/Y', $item['department_update_time'])}}
                                            @else
                                                {{date('d/m/Y', $item['department_creater_time'])}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            @if($is_root || $permission_edit)
                                            <a href="{{URL::route('hr.departmentEdit',array('id' => FunctionLib::inputId($item['department_id'])))}}" title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_boss || $permission_remove)
                                            <a class="deleteItem" title="Xóa" onclick="HR.deleteItem('{{FunctionLib::inputId($item['department_id'])}}', WEB_ROOT + '/manager/department/deleteDepartment')"><i class="fa fa-trash fa-2x"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        </div>
    </div>
</div>
@stop