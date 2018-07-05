<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
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
            <li class="active">Soạn thư mới</li>
        </ul>
    </div>
    <div class="page-content">
        @if(isset($error))
            <div class="alert alert-warning">
                <?= implode('<br>', $error) ?>
            </div>
        @endif
        <div class="line">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix paddingTop1 paddingBottom1">
                    <div class="panel-title pull-left">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Thêm thư, tin nhắn</h4>
                    </div>
                    <div class="btn-group btn-group-sm pull-right mgt3">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.HrMailViewGet')}}"><i class="fa fa-arrow-left"></i>&nbsp;Quay lại</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>(<span class="clred">*</span>) Là trường bắt buộc phải nhập</p>
                            <form id="adminForm" name="adminForm adminFormDevidetAdd" method="post" enctype="multipart/form-data" action="" novalidate="novalidate">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Người nhận</label>
                                            <div class="multipleSelectRecive" multiple style="display: none">
                                                @foreach($arrDepartment as $k=>$val)
                                                    <option value="{{$k}}">{{$val}}</option>
                                                @endforeach
                                            </div>
                                            <script>
                                                $('.multipleSelectRecive').fastselect({
                                                    placeholder: 'Chọn người nhận',
                                                    searchPlaceholder: 'Tìm kiếm',
                                                    noResultsText: 'Không có kết quả',
                                                    userOptionPrefix: 'Thêm ',
                                                    nameElement:'hr_mail_department_recive_list'
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>CC</label>
                                            <div class="multipleSelectCC" multiple style="display: none">
                                                @foreach($arrDepartment as $k=>$val)
                                                    <option value="{{$k}}">{{$val}}</option>
                                                @endforeach
                                            </div>
                                        </div>
                                        <script>
                                            $('.multipleSelectCC').fastselect({
                                                placeholder: 'Chọn người nhận',
                                                searchPlaceholder: 'Tìm kiếm',
                                                noResultsText: 'Không có kết quả',
                                                userOptionPrefix: 'Thêm ',
                                                nameElement:'hr_mail_department_cc_list'
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Chủ đề</label>
                                            <input class="form-control input-sm input-required" title="Tên thư, tin nhắn" id="hr_mail_name" name="hr_mail_name" @isset($data['hr_mail_name'])value="{{$data['hr_mail_name']}}"@endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Nội dung</label>
                                            <textarea class="form-control input-sm input-required" name="hr_mail_content" id="hr_mail_content" cols="30" rows="5">@isset($data['hr_mail_content']){{$data['hr_mail_content']}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">&nbsp;</label>
                                            <div class="controls">
                                                <a href="javascript:;"class="btn btn-primary link-button" onclick="baseUpload.uploadDocumentAdvanced({{Define::FILE_TYPE_MAIL}});">Tải tệp đính kèm</a>
                                                <div id="sys_show_file">
                                                    @if(isset($data['hr_mail_files']) && $data['hr_mail_files'] !='')
                                                        <?php $arrfiles = ($data['hr_mail_files'] != '') ? unserialize($data['hr_mail_files']) : array(); ?>
                                                        @foreach($arrfiles as $_key=>$file)
                                                            <div class="item-file item_{{$_key}}"><a target="_blank" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_MAIL.'/'.$id.'/'.$file}}">{{$file}}</a><span data="{{$file}}" class="remove_file" onclick="baseUpload.deleteDocumentUpload('{{FunctionLib::inputId($id)}}', {{$_key}}, '{{$file}}',{{Define::FILE_TYPE_MAIL}})">X</span></div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! csrf_field() !!}
                                        <button type="submit" class="btn btn-success btn-sm submitMailSend"><i class="fa fa-save"></i>&nbsp;Gửi</button>
                                        <button type="submit" class="btn btn-success btn-sm submitMailDraft"><i class="fa fa-save"></i>&nbsp;Lưu nháp</button>
                                        <input id="id_hiden" name="id_hiden" @isset($data['hr_mail_id'])rel="{{$data['hr_mail_id']}}" value="{{FunctionLib::inputId($data['hr_mail_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
                                        <input id="id_hiden_person" name="id_hiden_person" value="{{FunctionLib::inputId(0)}}"type="hidden">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace('hr_mail_content');
</script>
@stop