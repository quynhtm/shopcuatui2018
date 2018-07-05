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
                <li><a href="{{URL::route('hr.personnelView')}}"> Danh sách nhân sự</a></li>
                <li class="active">Thiết lập Nghỉ việc / Chuyển công tác nhân sự</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <form method="POST" action="" role="form">
                        @if(isset($error))
                            <div class="alert alert-danger" role="alert">
                                @foreach($error as $itmError)
                                    <p>{!! $itmError !!}</p>
                                @endforeach
                            </div>
                    @endif
                    <!--Block 1--->
                        @if(isset($infoPerson))
                            <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
                            <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
                            <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
                        @endif
                        <hr>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày ra quyết định nghỉ việc / chuyển công tác</label>
                                    <input type="text" class="form-control" id="quit_job_date_creater" name="quit_job_date_creater"  data-date-format="dd-mm-yyyy" value="@if(isset($data['quit_job_date_creater']) && $data['quit_job_date_creater'] > 0){{date('d-m-Y',$data['quit_job_date_creater'])}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ghi chú</label>
                                    <input type="text" class="form-control" id="quit_job_note" name="quit_job_note" value="@if(isset($data['quit_job_note'])){{$data['quit_job_note']}}@endif">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="controls">
                                <a href="javascript:;"class="btn btn-primary link-button" onclick="baseUpload.uploadDocumentAdvanced({{Define::FILE_TYPE_QUITJOB}});">Tải tệp đính kèm</a>
                                <div id="sys_show_file">
                                    @if(isset($data->quit_job_file_attack) && $data->quit_job_file_attack !='')
                                        <?php $arrfiles = ($data->quit_job_file_attack != '') ? unserialize($data->quit_job_file_attack) : array(); ?>
                                        @foreach($arrfiles as $_key=>$file)
                                            <div class="item-file item_{{$_key}}"><a target="_blank" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_QUITJOB.'/'.$quit_job_id.'/'.$file}}">{{$file}}</a><span data="{{$file}}" class="remove_file" onclick="baseUpload.deleteDocumentUpload('{{FunctionLib::inputId($quit_job_id)}}', {{$_key}}, '{{$file}}',{{Define::FILE_TYPE_QUITJOB}})">X</span></div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <input id="id_hiden" name="id_hiden" value="{{FunctionLib::inputId($quit_job_id)}}" type="hidden">
                        <input id="id_hiden_person" name="id_hiden_person" value="{{FunctionLib::inputId($person_id)}}"type="hidden">
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 text-left">
                            {!! csrf_field() !!}
                            <a class="btn btn-warning" href="{{URL::route('hr.personnelView')}}"><i class="fa fa-reply"></i> Trở lại</a>
                            <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var quit_job_date_creater = $('#quit_job_date_creater').datepicker({ });
        });
    </script>
@stop