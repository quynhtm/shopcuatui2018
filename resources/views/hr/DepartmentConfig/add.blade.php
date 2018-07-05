<?php use App\Library\AdminFunction\FunctionLib; ?>
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
        @if(isset($error) && sizeof($error) > 0)
            <div class="alert alert-warning">
                <?= implode('<br>', $error) ?>
            </div>
        @endif
        <div class="line">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix paddingTop1 paddingBottom1">
                    <div class="panel-title pull-left">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Thêm cấu hình đơn vị</h4>
                    </div>
                    <div class="btn-group btn-group-sm pull-right mgt3">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.departmentConfigView')}}"><i class="fa fa-arrow-left"></i>&nbsp;Quay lại</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>(<span class="clred">*</span>) Là trường bắt buộc phải nhập</p>
                            <form id="adminForm" name="adminForm" method="post" enctype="multipart/form-data" action="" novalidate="novalidate">
                                @if(isset($data->department_id) && $data->department_id > 0)
                                    <input class="form-control input-sm input-required" title="Đơn vị/phòng ban" id="department_id" name="department_id" @isset($data['department_id'])value="{{$data['department_id']}}"@endif type="hidden">
                                @else
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Đơn vị/phòng ban</label>
                                                <select class="form-control input-sm" name="department_id" id="department_id">
                                                    {!! $optionDepartment !!}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tuổi tối thiểu về hưu với nữ <span class="clred">(*)</span></label>
                                            <input class="form-control input-sm input-required" title="Tuổi tối thiểu về hưu với nữ" id="department_retired_age_min_girl" name="department_retired_age_min_girl" @isset($data['department_retired_age_min_girl'])value="{{$data['department_retired_age_min_girl']}}" @else value="55" @endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tuổi tối đa về hưu với nữ <span class="clred">(*)</span></label>
                                            <input class="form-control input-sm input-required" title="Tuổi tối đa về hưu với nữ" id="department_retired_age_max_girl" name="department_retired_age_max_girl" @isset($data['department_retired_age_max_girl'])value="{{$data['department_retired_age_max_girl']}}" @else value="60" @endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tuổi tối thiểu về hưu với nam <span class="clred">(*)</span></label>
                                            <input class="form-control input-sm input-required" title="Tuổi tối thiểu về hưu với nam" id="department_retired_age_min_boy" name="department_retired_age_min_boy" @isset($data['department_retired_age_min_boy'])value="{{$data['department_retired_age_min_boy']}}" @else value="55" @endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tuổi tối đa về hưu với nam <span class="clred">(*)</span></label>
                                            <input class="form-control input-sm input-required" title="Tuổi tối đa về hưu với nam" id="department_retired_age_max_boy" name="department_retired_age_max_boy" @isset($data['department_retired_age_max_boy'])value="{{$data['department_retired_age_max_boy']}}" @else value="65" @endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="display: none">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Số tháng cần sét tăng lương thường xuyên(mặc định 36 tháng) <span class="clred">(*)</span></label>
                                            <input class="form-control input-sm input-required" title="Số tháng cần sét tăng lương thường xuyên(mặc định 36 tháng)" id="month_regular_wage_increases" name="month_regular_wage_increases"
                                                   @if(isset($data['month_regular_wage_increases']) && $data['month_regular_wage_increases'] > 0)value="{{$data['month_regular_wage_increases']}}"@else value="36" @endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Số tháng tối thiểu để sét tăng lương trước thời hạn(mặc định 24 tháng) <span class="clred">(*)</span></label>
                                            <input class="form-control input-sm input-required" title="Số tháng tối thiểu để sét tăng lương trước thời hạn(mặc định 24 tháng)" id="month_raise_the_salary_ahead_of_time" name="month_raise_the_salary_ahead_of_time"
                                                   @if(@isset($data['month_raise_the_salary_ahead_of_time'])&& $data['month_raise_the_salary_ahead_of_time'] > 0)value="{{$data['month_raise_the_salary_ahead_of_time']}}"@else value="24" @endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Trạng thái</label>
                                            <select class="form-control input-sm" name="department_config_status" id="department_config_status">
                                                {!! $optionStatus !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! csrf_field() !!}
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;Lưu</button>
                                    </div>
                                    <input id="id_hiden" name="id_hiden" @isset($data['department_config_id'])rel="{{$data['department_config_id']}}" value="{{FunctionLib::inputId($data['department_config_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop