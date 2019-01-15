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
                <li class="active"> <a href="{{URL::route('shop.category')}}"> {{$pageAdminTitle}} </a></li>
                <li class="active">@if($id > 0){{viewLanguage('Cập nhật')}}@else {{viewLanguage('Tạo mới')}} @endif</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="row" style="width: 50%">
                <div class="col-md-12">
                    {{Form::open(array('method' => 'POST','role'=>'form','files' => true))}}
                    @if(isset($error) && !empty($error))
                        <div class="alert alert-danger" role="alert">
                            @foreach($error as $itmError)
                                <p>{{ $itmError }}</p>
                            @endforeach
                        </div>
                    @endif


                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label"><i>{{viewLanguage('Tên danh mục')}}<span class="red"> (*) </span></i></label>
                                <input type="text" id="name" name="category_name"  class="form-control input-sm" value="@if(isset($data['category_name'])){{$data['category_name']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label"><i>{{viewLanguage('Thuộc danh mục cha')}}</i></label>
                                <select name="category_parent_id" id="name" class="form-control input-sm">
                                {!! $optionCategoryParent !!}
                                </select>
                            </div>
                        </div>


                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label"><i>{{viewLanguage('Thứ tự hiển thị')}}</i></label>
                                <input type="text" id="name" name="category_order"  class="form-control input-sm" value="@if(isset($data['category_order'])){{$data['category_order']}}@endif">
                            </div>
                        </div>



                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label"><i>{{viewLanguage('Trạng thái')}}</i></label>
                                <select name="category_status" id="name" class="form-control input-sm">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                        </div>
                    <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label"><i>{{viewLanguage('Hiển thị ở menu')}}</i></label>
                                <select name="category_status" id="name" class="form-control input-sm">
                                    {!! $optionMenu !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label"><i>{{viewLanguage('Menu Tin bên phải')}}</i></label>
                                <select name="category_menu_right" id="name" class="form-control input-sm">
                                    {!! $optionMenuRight !!}
                                </select>
                            </div>
                        </div>


                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label"><i>{{viewLanguage('Meta title')}}</i></label>
                                <input type="text" id="name" name="meta_title"  class="form-control input-sm" cols="30" rows="5" value="@if(isset($data['meta_title'])){{$data['meta_title']}}@endif">
                            </div>
                        </div>


                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="name" class="control-label"><i>{{viewLanguage('Meta keyword')}}</i></label>
                                <textarea name="meta_keywords" id="name" class="form-control input-sm" cols="30" rows="5">
                                    @if(isset($data['meta_keywords'])){!! $data['meta_keywords'] !!}@endif
                                </textarea>
                            </div>
                        </div>


                        <div class="col-sm-6 ">
                            <div class="form-group">
                                <label for="name" class="control-label"><i>{{viewLanguage('Meta description')}}</i></label>
                                <textarea name="meta_description" id="name" class="form-control input-sm" cols="30" rows="5">
                                    @if(isset($data['meta_description'])){!! $data['meta_description'] !!}@endif
                                </textarea>
                            </div>
                        </div>


                        <div class="clearfix"></div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <a class="btn btn-warning" href="{{URL::route('shop.category')}}"><i class="fa fa-reply"></i> {{viewLanguage('back')}}</a>
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
