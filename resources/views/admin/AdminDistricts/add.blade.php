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
                <li><a href="{{URL::route('admin.districtsView')}}"> {{viewLanguage('Quản Lý Quận Huyện')}}</a></li>
                <li class="active">@if($id > 0){{viewLanguage('Cập nhật')}}@else {{$pageTitle}} @endif</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
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
                                <label for="name" class="control-label">{{viewLanguage('Tên Quận')}}<span class="red"> (*) </span></label>
                                <input type="text" id="name" name="district_name"  class="form-control input-sm" value="@if(isset($data['district_name'])){{$data['district_name']}}@endif">
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{viewLanguage('Tỉnh thành')}}</label>
                                <select name="district_status" id="status" class="form-control input-sm">
                                    {!! $optionProvince !!}}
                                </select>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{viewLanguage('Trạng thái')}}</label>
                                <select name="district_status" id="status" class="form-control input-sm">
                                    {!! $optionStatus !!}}
                                </select>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 text-left">
                            <a class="btn btn-warning" href="{{URL::route('admin.districtsView')}}"><i class="fa fa-reply"></i> {{viewLanguage('back')}}</a>
                            <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> {{viewLanguage('save')}}</button>
                        </div>
                        <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
@stop