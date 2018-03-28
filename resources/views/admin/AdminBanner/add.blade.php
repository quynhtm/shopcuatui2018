<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
    <div class="main-content-inner">
        <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{URL::route('admin.dashboard')}}">Home</a>
                </li>
                <li><a href="{{URL::route('admin.videoView')}}"> Quản lý banner</a></li>
                <li class="active">@if($id > 0)Cập nhật banner @else Tạo mới banner @endif</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    {{Form::model(array('method' => 'POST','role'=>'form','files' => true,'enctype'=>'multipart/form-data'))}}
                    @if(isset($error))
                        <div class="alert alert-danger" role="alert">
                            @foreach($error as $itmError)
                                <p>{{ $itmError }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div style="float: left; width: 50%">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">Tên Banner<span class="red"> (*) </span></label>
                                <input type="text" placeholder="Tên Banner" id="banner_name" name="banner_name"
                                       class="form-control input-sm"
                                       value="@if(isset($data['banner_name'])){{$data['banner_name']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">Upload File<span class="red"> (*) </span></label>
                                <input type='file'  id="file_img" name="file_img" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Link URL</label>
                                <input type="text" placeholder="Link Banner" id="banner_link" name="banner_link"
                                       class="form-control input-sm"
                                       value="@if(isset($data['banner_link'])){{$data['banner_link']}}@endif">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Page quảng cáo</label>
                                <select name="banner_page" id="banner_page" class="form-control input-sm">
                                    {!! $optionPage !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Loại quảng cáo</label>
                                <select name="banner_type" id="banner_type" class="form-control input-sm">
                                    {!! $optionType !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Mở tab mới?</label>
                                <select name="banner_is_target" id="banner_is_target" class="form-control input-sm">
                                    {!! $optionTarget !!}
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Thứ tự hiển thị</label>
                                <input type="text" placeholder="Thứ tự hiển thị" id="banner_order" name="banner_order"
                                       class="form-control input-sm"
                                       value="@if(isset($data['banner_order'])){{$data['banner_order']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Trạng thái</label>
                                <select name="banner_status" id="banner_status" class="form-control input-sm">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Thời gian chạy QC</label>
                                <select name="banner_is_run_time" id="banner_is_run_time" class="form-control input-sm">
                                    {!! $optionRunTime !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày bắt đầu</label>
                                <input type="text" placeholder="Link Banner" id="banner_start_time" name="banner_start_time"
                                       class="form-control input-sm"
                                       value="@if(isset($data['banner_start_time'])){{$data['banner_start_time']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày kết thúc</label>
                                <input type="text" placeholder="Link Banner" id="banner_end_time" name="banner_end_time"
                                       class="form-control input-sm"
                                       value="@if(isset($data['banner_end_time'])){{$data['banner_end_time']}}@endif">
                            </div>
                        </div>
                    </div>
                    <div></div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 text-left">
                            <a class="btn btn-warning" href="{{URL::route('admin.bannerView')}}"><i
                                        class="fa fa-reply"></i> {{FunctionLib::viewLanguage('back')}}</a>
                            <button class="btn btn-primary"><i
                                        class="fa fa-floppy-o"></i> {{FunctionLib::viewLanguage('save')}}</button>
                        </div>
                        <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
    <script>
        CKEDITOR.replace('video_sort_desc', {height: 600});
    </script>
@stop
