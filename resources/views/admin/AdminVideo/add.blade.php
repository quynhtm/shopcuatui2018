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
                <li><a href="{{URL::route('admin.videoView')}}"> Quản lý video</a></li>
                <li class="active">@if($id > 0)Cập nhật video @else Tạo mới video @endif</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    {{Form::open(array('method' => 'POST','role'=>'form','files' => true))}}
                    @if(isset($error))
                        <div class="alert alert-danger" role="alert">
                            @foreach($error as $itmError)
                                <p>{{ $itmError }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div style="float: left; width: 50%">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Tên Video<span class="red"> (*) </span></label>
                                <input type="text" placeholder="Tên video" id="video_name" name="video_name"
                                       class="form-control input-sm"
                                       value="@if(isset($data['video_name'])){{$data['video_name']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Link Video</label>
                                <input type="text" placeholder="Link Video" id="video_link" name="video_link"
                                       class="form-control input-sm"
                                       value="@if(isset($data['video_link'])){{$data['video_link']}}@endif">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Trạng thái</label>
                                <select name="video_status" id="video_status" class="form-control input-sm">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Mô Tả Ngắn<span class="red"> (*) </span></label>
                                <textarea class="form-control input-sm" id="video_sort_desc" name="video_sort_desc"
                                          placeholder="Mô tả ngắn">@if(isset($data['video_sort_desc']) && $data['video_sort_desc'] != '')
                                        {{$data['video_sort_desc']}}@endif</textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 text-left">
                            <a class="btn btn-warning" href="{{URL::route('admin.videoView')}}"><i
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
