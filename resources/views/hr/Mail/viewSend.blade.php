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
            <li class="active">Quản lý thư, tin nhắn đã gửi</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-info">
                    <form method="get" action="" role="form">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group col-sm-2">
                                <label for="hr_mail_name" class="control-label"><i>Tên thư, tin nhắn</i></label>
                                <input type="text" class="form-control input-sm" id="hr_mail_name" name="hr_mail_name" autocomplete="off" placeholder="Tên thư, tin nhắn" @if(isset($dataSearch['hr_mail_name']))value="{{$dataSearch['hr_mail_name']}}"@endif>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                    <span class="">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.HrMailEdit',array('id' => FunctionLib::inputId(0)))}}">
                            <i class="ace-icon fa fa-plus-circle"></i>
                            Soạn thư
                        </a>
                    </span>
                            <span class="">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                    </span>
                        </div>
                    </form>
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> thư, tin nhắn @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr>
                            <th width="2%" class="text-center">STT</th>
                            <th width="10%">Chủ đề</th>
                            <th width="30%">Nội dung</th>
                            <th width="5%" class="text-center">Chức năng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $k=>$item)
                            <tr>
                                <td class="text-center">{{$k+1}}</td>
                                <td>{{$item->hr_mail_name}}</td>
                                <td>
                                    {{FunctionLib::cutWord(strip_tags(stripcslashes($item->hr_mail_content)), 20, '...')}}
                                </td>
                                <td align="center">
                                    <a href="{{URL::route('hr.HrMailViewItemSend',array('id' => FunctionLib::inputId($item['hr_mail_id'])))}}" title="Xem"><i class="fa fa-eye fa-2x"></i></a>
                                    @if($is_root || $permission_remove)
                                        <a class="deleteItem" title="Xóa" onclick="HR.deleteItem('{{FunctionLib::inputId($item['hr_mail_id'])}}', WEB_ROOT + '/manager/mail/deleteHrMail')"><i class="fa fa-trash fa-2x"></i></a>
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