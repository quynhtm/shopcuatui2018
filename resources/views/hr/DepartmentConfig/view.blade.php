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
            <li class="active">Quản lý cấu hình đơn vị</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="line">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix paddingTop1 paddingBottom1">
                    <div class="panel-title pull-left">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Quản lý cấu hình đơn vị</h4>
                    </div>
                    <div class="btn-group btn-group-sm pull-right mgt3">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.departmentConfigEdit',array('id' => FunctionLib::inputId(0)))}}"><i class="fa fa-file"></i>&nbsp;Thêm mới</a>
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
                                    <th>Đơn vị/phòng ban</th>
                                    <th>Tuổi tối thiểu về hưu với nữ</th>
                                    <th>Tuổi tối đa về hưu với nữ</th>
                                    <th>Tuổi tối thiểu về hưu với nam</th>
                                    <th>Tuổi tối đa về hưu với nam</th>
                                    {{--<th>Số tháng cần sét tăng lương thường xuyên(mặc định 36 tháng)</th>
                                    <th>Số tháng tối thiểu để sét tăng lương trước thời hạn(mặc định 24 tháng)</th>--}}
                                    <th>Trạng thái</th>
                                    <th class="text-center">Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k=>$item)
                                    <tr>
                                        <td class="text-center">{{$stt + $k +1}}</td>
                                        <td>
                                            @if(isset($arrDepartment[$item['department_id']]))
                                                {{$arrDepartment[$item['department_id']]}}
                                            @else
                                                Chưa xác định
                                            @endif
                                        </td>
                                        <td>{{$item->department_retired_age_min_girl}}</td>
                                        <td>{{$item->department_retired_age_max_girl}}</td>
                                        <td>{{$item->department_retired_age_min_boy}}</td>
                                        <td>{{$item->department_retired_age_max_boy}}</td>
                                        {{--<td>{{$item->month_regular_wage_increases}}</td>
                                        <td>{{$item->month_raise_the_salary_ahead_of_time}}</td>--}}
                                        <td>
                                            @if(isset($arrStatus[$item['department_config_status']]) && $arrStatus[$item['department_config_status']] != -1)
                                                {{$arrStatus[$item['department_config_status']]}}
                                            @else
                                                Chưa xác định
                                            @endif
                                        </td>
                                        <td align="center">
                                            @if($is_root || $permission_edit)
                                            <a href="{{URL::route('hr.departmentConfigEdit',array('id' => FunctionLib::inputId($item['department_config_id'])))}}" title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_boss || $permission_remove)
                                            <a class="deleteItem" title="Xóa" onclick="HR.deleteItem('{{FunctionLib::inputId($item['department_config_id'])}}', WEB_ROOT + '/manager/departmentconfig/deleteDepartmentConfig')"><i class="fa fa-trash fa-2x"></i></a>
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