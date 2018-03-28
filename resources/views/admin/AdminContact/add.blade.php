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
                <li><a href="{{URL::route('admin.videoView')}}"> Quản lý liên hệ </a></li>
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
                                <label for="name" class="control-label">Tiêu đề<span class="red"> (*) </span></label>
                                <input type="text" placeholder="Tiêu đề" id="contact_title" name="contact_title"
                                       class="form-control input-sm"
                                       value="@if(isset($data['contact_title'])){{$data['contact_title']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Nội dung<span class="red"> (*) </span></label>
                                <textarea class="form-control input-sm" id="contact_content" name="contact_content"
                                          placeholder="Nội dung">@if(isset($data['contact_content']) && $data['contact_content'] != '')
                                        {{$data['contact_content']}}@endif</textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12">
                            <div class="form-group ">
                                <label for="name" class="control-label">Thông tin người liên hệ:</label><br>
                                Tên: <input type="text" placeholder="Tên" id="contact_user_name_send" name="contact_user_name_send"
                                       class="form-control input-sm"
                                       value="@if(isset($data['contact_user_name_send'])){{$data['contact_user_name_send']}}@endif">
                                Số ĐT: <input type="text" placeholder="Số Điện thoại" id="contact_phone_send" name="contact_phone_send"
                                       class="form-control input-sm"
                                       value="@if(isset($data['contact_phone_send'])){{$data['contact_phone_send']}}@endif">
                                Email: <input type="email" placeholder="Email" id="contact_email_send" name="contact_email_send"
                                       class="form-control input-sm"
                                       value="@if(isset($data['contact_email_send'])){{$data['contact_email_send']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày gửi</label>
                                <input type="text"  id="contact_email_send" name="contact_email_send"
                                       class="form-control input-sm"
                                       value="@if(isset($data['contact_time_creater'])){{date('d-m-Y',$data['contact_time_creater'])}}@endif">
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 text-left">
                            <a class="btn btn-warning" href="{{URL::route('admin.contactView')}}"><i
                                        class="fa fa-reply"></i> {{FunctionLib::viewLanguage('back')}}</a>
                        </div>
                        <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
    <script>
        CKEDITOR.replace('contact_content', {height: 600});
    </script>
@stop
