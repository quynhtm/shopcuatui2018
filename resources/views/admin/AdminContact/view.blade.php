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
                <li class="active">Quản lý liên hệ </li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="panel panel-info">
                        {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                        <div class="panel-body">
                            <div class="form-group col-lg-3">
                                <label for="banner_name">Tiêu Đề </label>
                                <input type="text" class="form-control input-sm" id="contact_title" name="contact_title"
                                       placeholder="Tiêu đề"
                                       @if(isset($search['contact_title']) && $search['contact_title'] != '')value="{{$search['contact_title']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="banner_name">Nội dung</label>
                                <input type="text" class="form-control input-sm" id="contact_content"
                                       name="contact_content" placeholder="Nội dung"
                                       @if(isset($search['contact_content']) && $search['contact_content'] != '')value="{{$search['contact_content']}}"@endif>
                            </div>

                            <div class="form-group col-lg-3">
                                <label for="category_status">Tên người liên hệ</label>
                                <input type="text" class="form-control input-sm" id="contact_user_name_send"
                                       name="contact_user_name_send" placeholder="Tên người liên hệ"
                                       @if(isset($search['contact_user_name_send']) && $search['contact_user_name_send'] != '')value="{{$search['contact_user_name_send']}}"@endif>
                            </div>
                            <div class="form-group col-lg-12 text-right">
                                <button class="btn btn-primary btn-sm" type="submit" name="submit" value="1"><i
                                            class="fa fa-search"></i> {{FunctionLib::viewLanguage('search')}}</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                    @if($data && sizeof($data) > 0)
                        <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> item @endif </div>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="5%" class="text-center">STT</th>
                                    <th width="20%">Tiêu đề</th>
                                    <th width="30%">Nội dung</th>
                                    <th width="20%">Thông tin người liên hệ</th>
                                    <th width="15%" class="text-center">Ngày</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $key => $item)

                                    <tr @if($item['parent_id'] == 0)style="background-color:#d6f6f6"@endif>
                                        <td class="text-center text-middle">{!! $stt + $key+1 !!}</td>
                                        <td>
                                            {!! isset($item['contact_title'])?$item['contact_title']:'' !!}
                                        </td>
                                        <td class="text-center text-middle">
                                            {!! isset($item['contact_content'])?$item['contact_content']:'' !!}
                                        </td>
                                        <td class="text-middle">
                                            Tên: {!! isset($item['contact_user_name_send'])?$item['contact_user_name_send']:'' !!}<br>
                                            Số ĐT: {!! isset($item['contact_phone_send'])?$item['contact_phone_send']:'' !!}<br>
                                            Email: {!! isset($item['contact_email_send'])?$item['contact_email_send']:'' !!}
                                        </td>
                                        <td class="text-center text-middle">
                                           {!! isset($item['contact_time_creater'])?date('d-m-Y', $item['contact_time_creater']):'' !!}
                                        </td>
                                        <td class="text-center text-middle">
                                            @if($is_root || $permission_full ==1|| $permission_edit ==1  )
                                                <a href="{{URL::route('admin.contactEdit',array('id' => FunctionLib::inputId($item['contact_id'])))}}"
                                                   title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_boss)
                                                <a href="javascript:void(0);"
                                                   onclick="Admin.deleteItem({{$item['contact_id']}},18)" title="Xóa Item"><i
                                                            class="fa fa-trash fa-2x"></i></a>
                                            @endif
                                            <span class="img_loading" id="img_loading_{{$item['contact_id']}}"></span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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