<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed top_nav" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">{{viewLanguage('Trang chủ')}}</a>
            </li>
            <li class="active"><a href="{{URL::route('shop.infosale')}}">{{$pageAdminTitle}}</a></li>
            <li class="active">@if($id > 0){{viewLanguage('Cập nhật')}}@else {{viewLanguage('Tạo mới')}} @endif</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                {{Form::open(array('method' => 'POST','role'=>'form','files' => true))}}
                @if(isset($error) && !empty($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{{ $itmError }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="line">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Tên người bán')}}<span class="red"> (*) </span></i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <input type="text" id="infor_sale_name" name="infor_sale_name"  class="form-control input-sm" value="@if(isset($data['infor_sale_name'])){{$data['infor_sale_name']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('SĐT')}}<span class="red"> (*) </span></i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <input type="text" id="infor_sale_phone" name="infor_sale_phone"  class="form-control input-sm" value="@if(isset($data['infor_sale_phone'])){{$data['infor_sale_phone']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Email')}}<span class="red"> (*) </span></i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <input type="text" id="infor_sale_mail" name="infor_sale_mail"  class="form-control input-sm" value="@if(isset($data['infor_sale_mail'])){{$data['infor_sale_mail']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Địa chỉ')}}<span class="red"> (*) </span></i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <input type="text" id="infor_sale_address" name="infor_sale_address"  class="form-control input-sm" value="@if(isset($data['infor_sale_address'])){{$data['infor_sale_address']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Skype')}}</i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <input type="text" id="infor_sale_skype" name="infor_sale_skype"  class="form-control input-sm" value="@if(isset($data['infor_sale_skype'])){{$data['infor_sale_skype']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Số tài khoản')}}</i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <textarea name="infor_sale_sotaikhoan" id="infor_sale_sotaikhoan" class="form-control input-sm" cols="30" rows="5">
                                @if(isset($data['infor_sale_sotaikhoan'])){!! $data['infor_sale_sotaikhoan'] !!}@endif
                            </textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Thông tin vận chuyển')}}</i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <textarea name="infor_sale_vanchuyen" id="infor_sale_vanchuyen" class="form-control input-sm" cols="30" rows="5">
                                @if(isset($data['infor_sale_vanchuyen'])){!! $data['infor_sale_vanchuyen'] !!}@endif
                            </textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">

                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <a class="btn btn-warning" href="{{URL::route('shop.infosale')}}"><i class="fa fa-reply"></i> {{viewLanguage('back')}}</a>
                            <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> {{viewLanguage('Lưu')}}</button>
                            <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace('infor_sale_sotaikhoan');
    CKEDITOR.replace('infor_sale_vanchuyen');
</script>
@stop
