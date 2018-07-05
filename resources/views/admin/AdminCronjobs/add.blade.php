<?php use App\Library\AdminFunction\CGlobal; ?>
<?php use App\Library\AdminFunction\Define; ?>
<?php use App\Library\AdminFunction\FunctionLib; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Trang chủ</a>
            </li>
            <li class="active">Sửa cronjob</li>
        </ul>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <form method="POST" action="" role="form">
                @if(isset($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{!! $itmError !!}</p>
                        @endforeach
                    </div>
                @endif

                <div style="float: left; width: 50%">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="user_name"><i>Tên cronjob</i></label>
                            <input type="text" class="form-control input-sm" id="cronjob_name" name="cronjob_name" autocomplete="off" placeholder="Tên cronjob" @if(isset($data['cronjob_name']))value="{{$data['cronjob_name']}}"@endif>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_email"><i>Router cronjob</i></label>
                            <input type="text" class="form-control input-sm" id="cronjob_router" name="cronjob_router" autocomplete="off" placeholder="Router cronjob" @if(isset($data['cronjob_router']))value="{{$data['cronjob_router']}}"@endif>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_phone" title="(1:chạy ngày 1 lần, 2: chạy theo số lần config)"><i>Số lần chạy cronjob</i></label>
                            <input type="text" class="form-control input-sm" id="cronjob_type" name="cronjob_type" autocomplete="off" placeholder="Type cronjob" @if(isset($data['cronjob_type']))value="{{$data['cronjob_type']}}" @else value="1" @endif>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_phone"><i>Số lần chạy trong 1 ngày</i></label>
                            <input type="text" class="form-control input-sm" id="cronjob_number_plan" name="cronjob_number_plan" autocomplete="off" placeholder="Số lần chạy trong 1 ngày" @if(isset($data['cronjob_number_plan']))value="{{$data['cronjob_number_plan']}}"@endif>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="cronjob_status" class="control-label">Trạng thái</label>
                            <select name="cronjob_status" class="form-control input-sm">
                                {!! $optionStatus !!}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-sm-12 text-left">
                    {!! csrf_field() !!}
                    <a class="btn btn-warning" href="{{URL::route('admin.CronjobView')}}"><i class="fa fa-reply"></i> Trở lại</a>
                    <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> Lưu lại</button>
                    <input id="id_hiden" name="id_hiden" @isset($data['cronjob_id'])rel="{{$data['cronjob_id']}}" value="{{FunctionLib::inputId($data['cronjob_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        var dateToday = new Date();
        jQuery('.date').datetimepicker({
            timepicker:false,
            format:'d-m-Y H:i',
            lang:'vi',
        });
    });
</script>
@stop