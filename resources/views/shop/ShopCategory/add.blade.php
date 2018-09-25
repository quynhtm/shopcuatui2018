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
                <div class="line">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Tên danh mục')}}<span class="red"> (*) </span></i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <input type="text" id="category_name" name="category_name"  class="form-control input-sm" value="@if(isset($data['category_name'])){{$data['category_name']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Thuộc danh mục cha')}}</i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <select name="category_parent_id" id="category_parent_id">
                                {!! $optionCategoryParent !!}
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Thứ tự hiển thị')}}</i></label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <input type="text" id="category_order" name="category_order"  class="form-control input-sm" value="@if(isset($data['category_order'])){{$data['category_order']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Trạng thái')}}<span class="red"> (*) </span></i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <select name="category_status" id="category_status">
                                {!! $optionStatus !!}
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Hiển thị ở menu')}}</i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <select name="category_status" id="category_status">
                                {!! $optionMenu !!}
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Menu Tin bên phải')}}</i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <select name="category_menu_right" id="category_menu_right">
                                {!! $optionMenuRight !!}
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Meta title')}}</i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <input type="text" id="meta_title" name="meta_title"  class="form-control input-sm" value="@if(isset($data['meta_title'])){{$data['meta_title']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Meta keyword')}}</i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <textarea name="meta_keywords" id="meta_keywords" class="form-control input-sm" cols="30" rows="5">
                                @if(isset($data['meta_keywords'])){!! $data['meta_keywords'] !!}@endif
                            </textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label"><i>{{viewLanguage('Meta description')}}</i></label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <textarea name="meta_description" id="meta_description" class="form-control input-sm" cols="30" rows="5">
                                @if(isset($data['meta_description'])){!! $data['meta_description'] !!}@endif
                            </textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>

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
@stop