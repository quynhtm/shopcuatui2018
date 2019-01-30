<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
@section('css')
    <style>
        .thumbnail-img{
            border: 1px solid #DD0000;
            padding: 4px;
            border-radius: 4px;
            padding-bottom: 10px;
            margin-right: 3px;
            width: 24%;
        }
        .css_radio span{
            display: inline-block;
            width: 100%;
            margin-bottom: 10px;
        }
        .css_radio span input[type='radio']{
            margin-top: 3px;
            margin-right: 5px;
        }
        .css_radio a{
            padding: 5px 10px;
            background: #880000;
            color: #fff;
            border-radius: 4px;
        }
        .fileUpload {
            position: relative;
            overflow: hidden;
            margin: 10px;
        }
        .fileUpload input.upload {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            padding: 0;
            font-size: 20px;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
        }

    </style>


@endsection
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed top_nav" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">{{viewLanguage('Trang chủ')}}</a>
            </li>
            <li><a href="{{URL::route('shop.productView')}}"> {{viewLanguage('Danh sách sản phẩm')}}</a></li>
            <li class="active">@if($id > 0){{viewLanguage('Cập nhật')}}@else {{viewLanguage('Tạo mới')}} @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>
        <div class="page-content">
            <div class="row" >
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
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">{{viewLanguage('Tên sản phẩm')}}<span class="red"> (*) </span></label>
                                    <input type="text" id="name" name="product_name"  class="form-control input-sm" value="@if(isset($data['product_name'])){{$data['product_name']}}@endif">
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{viewLanguage('Thuộc chuyên mục')}}</label>
                                <select name="category_id" id="name" class="form-control input-sm">
                                      {!! $optionCategory !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{viewLanguage('Danh mục')}}</label>
                                <select name="depart_id" id="name" class="form-control input-sm">
                                    {!! $optionDepart !!}
                                </select>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{viewLanguage('Loại sản phẩm')}}</label>
                                <select name="product_is_hot" id="name" class="form-control input-sm">
                                    {!! $optionProducttype !!}}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{viewLanguage('Kiểu hiển thị giá')}}</label>
                                <select name="product_type_price" id="name" class="form-control input-sm">
                                      {!! $optionProductPrice !!}
                                </select>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{viewLanguage('Trạng thái')}}</label>
                                <select name="status" id="name" class="form-control input-sm">
                                      {!! $optionStatus !!}}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                  <label for="name" class="control-label">{{viewLanguage('Giá bán')}}</label>
                                <input type="text" id="name" name="product_price_sell"  class="form-control input-sm" value="@if(isset($data['product_price_sell'])){{$data['product_price_sell']}}@endif">
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{viewLanguage('Tình trạng hàng ')}}</label>
                                <select name="is_sale" id="name" class="form-control input-sm">
                                     {!! $optionProducSale !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                 <label for="name" class="control-label">{{viewLanguage('Giá nhập')}}</label>
                                <input type="text" id="name" name="product_price_input"  class="form-control input-sm" value="@if(isset($data['product_price_input'])){{$data['product_price_input']}}@endif">
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{viewLanguage('Nhà cung cấp')}}</label>
                                <select name="provider_id" id="name" class="form-control input-sm">
                                      {!! $optionProvider !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                            <label for="name" class="control-label">{{viewLanguage('Giá thị trường')}}</label>
                                <input type="text" id="name" name="product_price_market"  class="form-control input-sm" value="@if(isset($data['product_price_market'])){{$data['product_price_market']}}@endif">
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="name" class="control-label"><i>{{viewLanguage('Thông tin khuyến mại')}}</i></label>
                                <textarea name="product_selloff" id="name" class="form-control input-sm" cols="30" rows="5">
                                    @if(isset($data['product_selloff'])){!! $data['product_selloff'] !!}@endif
                                </textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="name" class="control-label"><i>{{viewLanguage('Ghi chú nhập hàng')}}</i></label>
                                <textarea name="product_content" id="name" class="form-control input-sm" cols="30" rows="5">
                                    @if(isset($data['product_content'])){!! $data['product_content'] !!}@endif
                                </textarea>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label"><i>{{viewLanguage('Mô tả ngắn')}}</i></label>
                                <textarea name="product_sort_desc" id="name" class="form-control input-sm" cols="30" rows="5">
                                    @if(isset($data['product_sort_desc'])){!! $data['product_sort_desc'] !!}@endif
                                </textarea>
                            </div>
                        </div>

                        {{--<div class="clearfix"></div>--}}
                        {{--<div class="form-group col-sm-12 text-left">--}}
                            {{--<a class="btn btn-warning" href="{{URL::route('shop.productView')}}"><i class="fa fa-reply"></i> {{viewLanguage('back')}}</a>--}}
                            {{--<button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> {{viewLanguage('save')}}</button>--}}
                        {{--</div>--}}
                        <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                    </div>
{{--Thêm nhiều file cùng lúc--}}
                    {{--<form class="fileUpload btn btn-primary" id="upload" method="post" action="{{URL::route('shop.productView')}}" enctype="multipart/form-data">--}}
                        {{--<span>Upload Ảnh SP</span>--}}
                        {{--<input type="file" name="upload" multiple />--}}
                        {{--<ul id="fileList">--}}
                            {{--<!-- The file list will be shown here -->--}}
                        {{--</ul>--}}
                    {{--</form>--}}
                    <form action=" {{URL::route('shop.productView')}}" id="upload" method="post" enctype="multipart/form-data">
                           <div class="fileUpload btn btn-primary" >
                                <span>Upload Ảnh SP</span>
                                <input type="file" class="upload" multiple name="img" />
                            </div>
                            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                            <a class="btn btn-warning" href="{{URL::route('shop.productView')}}"><i class="fa fa-reply"></i> {{viewLanguage('back')}}</a>
                            <button  class="btn btn-primary" name="submit"><i class="fa fa-floppy-o"></i> {{viewLanguage('save')}}</button>

                            <div class="col-xs-6">
                        <div class="row" align="center">
                            <div class="col-xs-6 col-md-3 thumbnail-img">
                                <a href="#" class="thumbnail">
                                    <img src="..." alt="...">
                                </a>
                                {{--dùng code html--}}
                                {{--<ul style="list-style-type:none;">--}}
                                {{--<li><input type="checkbox" name="">aaa</li>--}}
                                {{--<li><input type="checkbox" name="">bb</li>--}}
                                {{--<li><a href="">xóa ảnh</a></li>--}}
                                {{--</ul>--}}
                                <div class="css_radio" >
                                    <span><input  type="radio" name="product_image">Ảnh Đại Diện</span>
                                    <span><input type="radio" name="product_image">Ảnh Hover</span>
                                    <center><a href="">xóa ảnh</a></center>
                                </div>

                            </div>
                            <div class="col-xs-6 col-md-3 thumbnail-img">
                                <a href="" class="thumbnail">
                                    <img src="..." alt="...">
                                </a>
                                <div class="css_radio">
                                    <span><input type="radio" name="product_image">Ảnh Đại Diện</span>
                                    <span><input type="radio" name="product_image">Ảnh Hover</span>
                                    <center><a href="">xóa ảnh</a></center>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3 thumbnail-img">
                                <a href="#" class="thumbnail">
                                    <img src="..." alt="...">
                                </a>
                                <div class="css_radio">
                                    <span><input type="radio" name="product_image">Ảnh Đại Diện</span>
                                    <span><input type="radio" name="product_image">Ảnh Hover</span>
                                    <center><a href="">xóa ảnh</a></center>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3 thumbnail-img">
                                <a href="#" class="thumbnail">
                                    <img src="..." alt="...">
                                </a>
                                <div class="css_radio">
                                    <span><input type="radio" name="product_image">Ảnh Đại Diện</span>
                                    <span><input type="radio" name="product_image">Ảnh Hover</span>
                                    <center><a href="" >xóa ảnh</a></center>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>

            </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div><!-- /.page-content -->
@stop
