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
            <li class="active">{{$pageAdminTitle}}</li>
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
                <div style="float: left; width: 50%">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">{{viewLanguage('Tên người bán')}}<span class="red"> (*) </span></label>
                            <input type="text" id="infor_sale_name" name="infor_sale_name"  class="form-control input-sm" value="@if(isset($data['infor_sale_name'])){{$data['infor_sale_name']}}@endif">
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">{{viewLanguage('SĐT')}}<span class="red"> (*) </span></label>
                            <input type="text" id="infor_sale_name" name="infor_sale_name"  class="form-control input-sm" value="@if(isset($data['infor_sale_name'])){{$data['infor_sale_name']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-left">
                        <a class="btn btn-warning" href="{{URL::route('shop.infosale')}}"><i class="fa fa-reply"></i> {{viewLanguage('back')}}</a>
                        <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> {{viewLanguage('Lưu')}}</button>
                    </div>
                    <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop
