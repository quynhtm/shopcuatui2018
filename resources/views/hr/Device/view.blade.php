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
            <li class="active">Quản lý tài sản</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-info">
                    <form method="get" action="" role="form" id="formSearchDevice">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group col-sm-2">
                                <label for="device_name" class="control-label"><i>Tên thiết bị</i></label>
                                <input type="text" class="form-control input-sm" id="device_name" name="device_name" autocomplete="off" placeholder="Tên thiết bị" @if(isset($dataSearch['device_name']))value="{{$dataSearch['device_name']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Thuộc loại</i></label>
                                <select name="device_type" id="device_type" class="form-control input-sm" tabindex="12" data-placeholder="Thuộc loại">
                                    <option value="-1">--- Chọn thuộc loại ---</option>
                                    {!! $optionDeviceType !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Thuộc phòng ban</i></label>
                                <select name="device_depart_id" id="device_depart_id" class="form-control input-sm" tabindex="12" data-placeholder="Thuộc phòng ban">
                                    <option value="-1">--- Chọn thuộc phòng ban ---</option>
                                    {!! $optionDepartment !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Trạng thái</i></label>
                                <select name="device_status" id="device_status" class="form-control input-sm" tabindex="12" data-placeholder="Trạng thái">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-4">

                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <span>
                                <a class="btn btn-danger btn-sm" href="{{URL::route('hr.deviceEdit',array('id' => FunctionLib::inputId(0)))}}">
                                    <i class="ace-icon fa fa-plus-circle"></i>
                                    Thêm mới
                                </a>
                            </span>
                            <span>
                                <a href="{{URL::route('hr.exportDevice')}}" class="btn btn-default btn-sm exportDevice">
                                    <i class="fa fa-file-excel-o"></i> Xuất ra file</a>
                            </span>
                            <span>
                                <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                            </span>
                        </div>
                    </form>
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> thiết bị @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr>
                            <th width="2%" class="text-center">STT</th>
                            <th width="20%">Tên thiết bị</th>
                            <th width="10%">Mã</th>
                            <th width="10%">Giá</th>
                            <th width="12%">Loại thiết bị</th>
                            <th width="10%">Ngày bàn giao</th>
                            <th width="10%">Người sử dụng</th>
                            <th width="8%">Trạng thái</th>
                            <th width="10%" class="text-center">Chức năng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $k=>$item)
                            <tr>
                                <td class="text-center">{{$k+1}}</td>
                                <td>{{$item->device_name}}</td>
                                <td>{{$item->device_code}}</td>
                                <td>{{FunctionLib::numberFormat($item->device_price)}}đ</td>
                                <td>
                                    @if(isset($arrDeviceType[$item['device_type']]))
                                        {{$arrDeviceType[$item['device_type']]}}
                                    @else
                                        Chưa xác định
                                    @endif
                                </td>
                                <td>{{date('d-m-Y', $item['device_date_return'])}}</td>
                                <td>
                                    @if($item['device_person_id'] > 0 )
                                        @if(isset($arrPersion[$item['device_person_id']]))
                                            {{$arrPersion[$item['device_person_id']]}}
                                        @else
                                            <span class="red">NS này đã bị xóa</span>
                                        @endif
                                    @else
                                       <span class="red">Chưa xác định</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($arrStatus[$item['device_status']]) && $arrStatus[$item['device_status']] != -1)
                                        {{$arrStatus[$item['device_status']]}}
                                    @else
                                        Chưa xác định
                                    @endif
                                </td>
                                <td align="center">
                                    @if($is_root || $permission_edit)
                                        <a href="{{URL::route('hr.deviceEdit',array('id' => FunctionLib::inputId($item['device_id'])))}}" title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                    @endif
                                    @if($is_boss || $permission_remove)
                                        <a class="deleteItem" title="Xóa" onclick="HR.deleteItem('{{FunctionLib::inputId($item['device_id'])}}', WEB_ROOT + '/manager/device/deleteDevice')"><i class="fa fa-trash fa-2x"></i></a>
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
@stop