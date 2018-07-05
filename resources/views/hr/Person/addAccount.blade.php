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
            <li class="active">Sửa tài khoản đăng nhập hệ thống</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content marginTop30" >
        <div class="row" >
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form method="POST" action="" role="form">
                @if(isset($error) && !empty($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{!! $itmError !!}</p>
                        @endforeach
                    </div>
                @endif

                <div style="float: left; width: 50%">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Tên đăng nhập<span class="red"> (*) </span></label>
                            <input type="text" placeholder="Tên đăng nhập" id="user_name" name="user_name"  class="form-control input-sm" value="@if(isset($data['user_name'])){{$data['user_name']}}@endif">
                        </div>
                    </div>
                    @if($user_id == 0)
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Mật khẩu<span class="red"> (* Mặc định: Nhansu@!2017) </span></label>
                            <input type="password"  id="user_password" name="user_password" class="form-control input-sm" value="Nhansu@!2017">
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Tên nhân viên<span class="red"> (*) </span></label>
                            <input type="text" placeholder="Tên nhân viên" id="user_full_name" name="user_full_name"  class="form-control input-sm" value="@if(isset($data['user_full_name'])){{$data['user_full_name']}}@endif">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Mã nhân sự</label>
                            <input type="text" placeholder="Mã nhân sự" id="number_code" name="number_code"  class="form-control input-sm" value="@if(isset($data['number_code'])){{$data['number_code']}}@endif">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Thuộc phòng ban<span class="red"> (*) </span></label>
                            <select name="user_depart_id" id="user_depart_id" class="form-control input-sm">
                                {!! $optionDepart !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Kiểu User<span class="red"> (*) </span></label>
                            <select name="role_type" id="role_type" class="form-control input-sm">
                                {!! $optionRoleType !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Email<span class="red"> (*) </span></label>
                            <input type="text" placeholder="Email" id="user_email" name="user_email"  class="form-control input-sm" value="@if(isset($data['user_email'])){{$data['user_email']}}@endif">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Phone</label>
                            <input type="text" placeholder="Phone" id="user_phone" name="user_phone"  class="form-control input-sm" value="@if(isset($data['user_phone'])){{$data['user_phone']}}@endif">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Telephone</label>
                            <input type="text" placeholder="Telephone" id="telephone" name="telephone"  class="form-control input-sm" value="@if(isset($data['telephone'])){{$data['telephone']}}@endif">
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Địa chỉ</label>
                            <input type="text" placeholder="Địa chỉ hiện tại" id="address_register" name="address_register"  class="form-control input-sm" value="@if(isset($data['address_register'])){{$data['address_register']}}@endif">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Giới tính</label>
                            <select name="user_sex" id="user_sex" class="form-control input-sm">
                                {!! $optionSex !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Trạng thái</label>
                            <select name="user_status" id="user_status" class="form-control input-sm">
                                {!! $optionStatus !!}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                    <input type="hidden" value="{{$user_id}}" id="user_id" name="user_id">
                <div class="form-group col-sm-12 text-left">
                    {!! csrf_field() !!}
                    <a class="btn btn-warning" href="{{URL::route('hr.personnelView')}}"><i class="fa fa-reply"></i> Trở lại</a>
                    @if($user_id == 0)
                        <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> Lưu lại</button>
                    @endif
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop