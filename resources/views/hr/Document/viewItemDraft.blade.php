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
                <li class="active">Quản văn bản nháp</li>
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
                            <h4><i class="fa fa-list" aria-hidden="true"></i> Thêm văn bản</h4>
                        </div>
                        <div class="btn-group btn-group-sm pull-right mgt3">
                            <a class="btn btn-danger btn-sm" href="{{URL::route('hr.HrDocumentViewDraft')}}"><i class="fa fa-arrow-left"></i>&nbsp;Quay lại</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>(<span class="clred">*</span>) Là trường bắt buộc phải nhập</p>
                                <form id="adminForm" name="adminForm adminFormDevidetAdd" method="post" enctype="multipart/form-data" action="{{URL::route('hr.HrDocumentEdit')}}/{{FunctionLib::inputId($data['hr_document_id'])}}" novalidate="novalidate">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Người nhận</label>
                                                <div class="multipleSelectRecive" multiple style="display: none">
                                                    <?php
                                                    $hr_document_department_recive_list = isset($data['hr_document_department_recive_list']) ? explode(',', $data['hr_document_department_recive_list']) : array();
                                                    ?>
                                                    @foreach($arrDepartment as $k=>$val)
                                                        <option value="{{$k}}" @if(in_array($k, $hr_document_department_recive_list)) selected="selected" @endif>{{$val}}</option>
                                                    @endforeach
                                                </div>
                                                <script>
                                                    $('.multipleSelectRecive').fastselect({
                                                        placeholder: 'Chọn người nhận',
                                                        searchPlaceholder: 'Tìm kiếm',
                                                        noResultsText: 'Không có kết quả',
                                                        userOptionPrefix: 'Thêm ',
                                                        nameElement:'hr_document_department_recive_list'
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
                                                    <?php
                                                    $hr_document_department_cc_list = isset($data['hr_document_department_cc_list']) ? explode(',', $data['hr_document_department_cc_list']) : array();
                                                    ?>
                                                    @foreach($arrDepartment as $k=>$val)
                                                        <option value="{{$k}}" @if(in_array($k, $hr_document_department_cc_list)) selected="selected" @endif>{{$val}}</option>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <script>
                                                $('.multipleSelectCC').fastselect({
                                                    placeholder: 'Chọn người nhận',
                                                    searchPlaceholder: 'Tìm kiếm',
                                                    noResultsText: 'Không có kết quả',
                                                    userOptionPrefix: 'Thêm ',
                                                    nameElement:'hr_document_department_cc_list'
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tên văn bản</label>
                                                <input class="form-control input-sm input-required" title="Tên văn bản" id="hr_document_name" name="hr_document_name" @isset($data['hr_document_name'])value="{{$data['hr_document_name']}}"@endif type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Số/ký hiệu</label>
                                                <input class="form-control input-sm input-required" title="Số/ký hiệu" id="hr_document_code" name="hr_document_code" @isset($data['hr_document_code'])value="{{$data['hr_document_code']}}"@endif type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Cơ quan ban hành</label>
                                                <select class="form-control input-sm"  id="hr_document_promulgate" name="hr_document_promulgate">
                                                    {!! $optionPromulgate !!}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Loại văn bản</label>
                                                <select class="form-control input-sm"  id="hr_document_type" name="hr_document_type">
                                                    {!! $optionType !!}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Lĩnh vực</label>
                                                <select class="form-control input-sm"  id="hr_document_field" name="hr_document_field">
                                                    {!! $optionField !!}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Người ký</label>
                                                <input class="form-control input-sm input-required" title="Người ký" id="hr_document_signer" name="hr_document_signer" @isset($data['hr_document_signer'])value="{{$data['hr_document_signer']}}"@endif type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ngày Ban hành</label>
                                                <input class="form-control input-sm input-required date" title="Ngày Ban hành" id="hr_document_date_issued" name="hr_document_date_issued" @isset($data['hr_document_date_issued'])value="{{date('d-m-Y', $data['hr_document_date_issued'])}}"@endif type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ngày Hiệu lực</label>
                                                <input class="form-control input-sm input-required date" title="Ngày Hiệu lực" id="hr_document_effective_date" name="hr_document_effective_date" @isset($data['hr_document_effective_date'])value="{{date('d-m-Y', $data['hr_document_effective_date'])}}"@endif type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ngày hết Hiệu lực</label>
                                                <input class="form-control input-sm input-required date" title="Ngày hết Hiệu lực" id="hr_document_date_expired" name="hr_document_date_expired" @isset($data['hr_document_date_expired'])value="{{date('d-m-Y', $data['hr_document_date_expired'])}}"@endif type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ngày đưa tin</label>
                                                <input class="form-control input-sm input-required date" title="Ngày đưa tin" id="hr_document_delease_date" name="hr_document_delease_date" @isset($data['hr_document_delease_date'])value="{{date('d-m-Y', $data['hr_document_delease_date'])}}"@endif type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Mô tả</label>
                                                <textarea class="form-control input-sm input-required" name="hr_document_desc" id="hr_document_desc" cols="30" rows="5">@isset($data['hr_document_desc']){{$data['hr_document_desc']}}@endif</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Nội dung</label>
                                                <textarea class="form-control input-sm input-required" name="hr_document_content" id="hr_document_content" cols="30" rows="5">@isset($data['hr_document_content']){{$data['hr_document_content']}}@endif</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">&nbsp;</label>
                                                <div class="controls">
                                                    <a href="javascript:;"class="btn btn-primary link-button" onclick="baseUpload.uploadDocumentAdvanced({{Define::FILE_TYPE_DOCUMENT}});">Tải tệp đính kèm</a>
                                                    <div id="sys_show_file">
                                                        @if(isset($data['hr_document_files']) && $data['hr_document_files'] !='')
                                                            <?php $arrfiles = ($data['hr_document_files'] != '') ? unserialize($data['hr_document_files']) : array();
                                                            ?>
                                                            @foreach($arrfiles as $_key=>$file)
                                                                <div class="item-file item_{{$_key}}"><a target="_blank" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_DOCUMENT.'/'.$id.'/'.$file}}">{{$file}}</a><span data="{{$file}}" class="remove_file" onclick="baseUpload.deleteDocumentUpload('{{FunctionLib::inputId($id)}}', {{$_key}}, '{{$file}}',{{Define::FILE_TYPE_DOCUMENT}})">X</span></div>
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
                                            <button type="submit" class="btn btn-success btn-sm submitDocumentSend"><i class="fa fa-save"></i>&nbsp;Lưu hoàn thành</button>
                                            <button type="submit" class="btn btn-success btn-sm submitDocumentDraft"><i class="fa fa-save"></i>&nbsp;Lưu nháp</button>
                                            <input id="id_hiden" name="id_hiden" @isset($data['hr_document_id'])rel="{{$data['hr_document_id']}}" value="{{FunctionLib::inputId($data['hr_document_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
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
        CKEDITOR.replace('hr_document_content');
        var dateToday = new Date();
        jQuery('.date').datetimepicker({
            timepicker:false,
            format:'d-m-Y',
            lang:'vi',
        });
    </script>
@stop