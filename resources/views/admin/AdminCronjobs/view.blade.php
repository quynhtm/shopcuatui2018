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
                <a href="{{URL::route('admin.dashboard')}}">Trang chủ</a>
            </li>
            <li class="active">Quản lý cronjobs</li>
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
                            <label for="user_name"><i>Tên cronjob</i></label>
                            <input type="text" class="form-control input-sm" id="cronjob_name" name="cronjob_name" autocomplete="off" placeholder="Tên cronjob" @if(isset($dataSearch['cronjob_name']))value="{{$dataSearch['cronjob_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="user_phone"><i>Trạng thái</i></label>
                            <select name="cronjob_status" class="form-control input-sm">
                                <option value="-1">Tất cả</option>
                                {!! $optionStatus !!}
                            </select>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <span class="">
                            <a class="btn btn-danger btn-sm" href="{{URL::route('admin.CronjobEdit',array('id' => FunctionLib::inputId(0)))}}">
                                <i class="ace-icon fa fa-plus-circle"></i>
                                Thêm mới
                            </a>
                        </span>
                        <span class="">
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </span>
                    </div>
                    </form>
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if(count($data) >0) Có tổng số <b>{{count($data)}}</b> tài khoản  @endif </div>
                    <br>
                    <table class="table table-bordered">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="5%" class="text-center">STT</th>
                            <th width="20%">Tên cronjob</th>
                            <th width="10%">Router cronjob</th>
                            <th width="20%">Thông tin khác</th>
                            <th width="10%" class="text-center">Trạng thái</th>
                            <th width="15%" class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr @if($item['user_status'] == \App\Library\AdminFunction\Define::STATUS_BLOCK)class="red bg-danger middle" {else} class="middle" @endif>
                                <td class="text-center middle">{{ $stt+$key+1 }}</td>
                                <td>{{$item['cronjob_name']}}</td>
                                <td>{{$item['cronjob_router']}}</td>
                                <td>
                                    cronjob_type: {{$item['cronjob_type']}}<br/>
                                    cronjob_date_run: {{date('d-m-Y H:i', $item['cronjob_date_run'])}}<br/>
                                    cronjob_number_plan: {{$item['cronjob_number_plan']}}<br/>
                                    cronjob_number_running: {{$item['cronjob_number_running']}}<br/>
                                    cronjob_result: {{$item['cronjob_result']}}<br/>
                                </td>
                                <td class="text-center">
                                    @if(isset($arrStatus[$item['cronjob_status']])) {{$arrStatus[$item['cronjob_status']]}} @endif
                                </td>
                                <td class="text-center middle" align="center">
                                    <a href="{{URL::route('admin.CronjobEdit',array('id' => FunctionLib::inputId($item['cronjob_id'])))}}" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>&nbsp;&nbsp;&nbsp;
                                    <a class="deleteItem" onclick="HR.deleteItem('{{FunctionLib::inputId($item['cronjob_id'])}}', WEB_ROOT + '/manager/cronjob/deleteCronjob')"><i class="fa fa-trash fa-2x"></i></a>
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
<script type="text/javascript">
    $('[data-rel=popover]').popover({container: 'body'});
</script>
@stop