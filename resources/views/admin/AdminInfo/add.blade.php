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
                <li><a href="{{URL::route('admin.videoView')}}"> Quản lý thông tin liên hệ </a></li>
                <li class="active">@if($id > 0)Cập nhật thông tin @else Tạo mới thông tin @endif</li>
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
                    <div style="float: left; width: 100%">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Tiêu đề<span class="red"> (*) </span></label>
                                <input type="text" placeholder="Tiêu đề" id="info_title" name="info_title"
                                       class="form-control input-sm"
                                       value="@if(isset($data['info_title'])){{$data['info_title']}}@endif">
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Từ khóa</label>
                                <input type="text" placeholder="Từ khóa" id="info_keyword" name="info_keyword"
                                       class="form-control input-sm"
                                       value="@if(isset($data['info_keyword'])){{$data['info_keyword']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Trạng thái</label>
                                <select name="info_status" id="info_status" class="form-control input-sm">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Vị trí</label>
                                <input type="text" placeholder="Vị trí" id="info_order" name="info_order"
                                       class="form-control input-sm"
                                       value="@if(isset($data['info_order'])){{$data['info_order']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày tạo</label>
                                <input type="date" placeholder="Ngày tạo" id="info_created" name="info_created"
                                       class="form-control input-sm"
                                       value="@if(isset($data['info_created']) && $data['info_created'] != '' )
                                           {{date('dd/mm/yyyy',$data['info_created'])}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Nội dung<span class="red"> (*) </span></label>
                                <textarea class="form-control input-sm" id="video_sort_desc" name="info_content"
                                          placeholder="Nội dung">@if(isset($data['info_content']) && $data['info_content'] != '')
                                        {{$data['info_content']}}@endif</textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 text-left">
                            <a class="btn btn-warning" href="{{URL::route('admin.infoView')}}"><i
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
