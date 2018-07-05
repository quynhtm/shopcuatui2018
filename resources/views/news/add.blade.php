<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\CGlobal;
?>
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
                <li class="active">Quản lý tin tức</li>
            </ul>
        </div>
        <div class="page-content">
            @if(isset($error) && sizeof($error) > 0))
                <div class="alert alert-warning">
                    <?= implode('<br>', $error) ?>
                </div>
            @endif
            <div class="line">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix paddingTop1 paddingBottom1">
                        <div class="panel-title pull-left">
                            <h4><i class="fa fa-list" aria-hidden="true"></i> Thêm bài viết</h4>
                        </div>
                        <div class="btn-group btn-group-sm pull-right mgt3">
                            <a class="btn btn-danger btn-sm" href="{{URL::route('admin.newsView')}}"><i class="fa fa-arrow-left"></i>&nbsp;Quay lại</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>(<span class="clred">*</span>) Là trường bắt buộc phải nhập</p>
                                <form id="adminForm" name="adminForm" method="post" enctype="multipart/form-data" action="" novalidate="novalidate">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Tên bài(<span class="clred">*</span>)</label>
                                                <input class="form-control input-sm input-required" title="Tên bài" id="news_title" name="news_title" @isset($data['news_title'])value="{{$data['news_title']}}"@endif type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Mô tả</label>
                                                <textarea class="form-control input-sm" name="news_intro">@if(isset($data['news_intro'])){{stripslashes($data['news_intro'])}}@endif</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Nội dung</label>
                                                <textarea class="form-control input-sm" name="news_content">@if(isset($data['news_content'])){{stripslashes($data['news_content'])}}@endif</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Trạng thái</label>
                                                <select class="form-control input-sm" name="news_status" id="news_status">
                                                    {!! $optionStatus !!}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            {!! csrf_field() !!}
                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-forward"></i>&nbsp;Lưu</button>
                                        </div>
                                        <input id="id_hiden" name="id_hiden" @isset($data['device_id'])rel="{{$data['device_id']}}" value="{{FunctionLib::inputId($data['device_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
	CKEDITOR.replace('news_content');
</script>
@stop