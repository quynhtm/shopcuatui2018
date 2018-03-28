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
            <li class="active">Quản lý sản phẩm</li>
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
                            <label for="banner_name">Tên</label>
                            <input type="text" class="form-control input-sm" id="news_title" name="news_title" placeholder="Tiêu đề" @if(isset($search['news_title']) && $search['news_title'] != '')value="{{$search['news_title']}}"@endif>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="category_status">Trạng thái</label>
                            <select name="news_status" id="news_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>

                        <div class="form-group col-lg-12 text-right">
                            @if($is_root || $permission_full ==1 || $permission_create == 1)
                                <a class="btn btn-danger btn-sm" href="{{URL::route('admin.newsEditProduct',array('id' => FunctionLib::inputId(0)))}}">
                                    <i class="ace-icon fa fa-plus-circle"></i>
                                    {{FunctionLib::viewLanguage('add')}}
                                </a>
                            @endif
                                {{--<button class="btn btn-warning btn-sm" type="submit" name="submit" value="2"><i class="fa fa-file-excel-o"></i> Xuất Excel</button>--}}
                                <button class="btn btn-primary btn-sm" type="submit" name="submit" value="1"><i class="fa fa-search"></i> {{FunctionLib::viewLanguage('search')}}</button>
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
                            <th width="5%" class="text-center">TT</th>
                            <th width="10%">Ảnh</th>
                            <th width="35%">Tiêu đề</th>
                            <th width="15%" class="text-center">Thuộc danh mục</th>
                            <th width="15%" class="text-center">Loại tin</th>
                            <th width="10%" class="text-center">Trạng thái</th>
                            <th width="10%" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr @if($item['parent_id'] == 0)style="background-color:#d6f6f6"@endif>
                                <td class="text-center text-middle">{!! $stt + $key+1 !!}</td>
                                <td></td>
                                <td>{!! $item['news_title'] !!}</td>
                                <td class="text-center text-middle">
                                    @if(isset($arrCategoryNew[$item['news_category']]))
                                        {{$arrCategoryNew[$item['news_category']]}}
                                    @endif
                                </td>
                                <td class="text-center text-middle">
                                    @if(isset($arrTypeNews[$item['news_type']]))
                                        {{$arrTypeNews[$item['news_type']]}}
                                    @endif
                                </td>
                                <td class="text-center text-middle">
                                    @if($item['news_status'] == 1)
                                        <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                    @else
                                        <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                    @endif
                                </td>

                                <td class="text-center text-middle">
                                    @if($is_root || $permission_full ==1|| $permission_edit ==1  )
                                        <a href="{{URL::route('admin.newsEditProduct',array('id' => FunctionLib::inputId($item['news_id'])))}}" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                    @endif
                                    @if($is_boss)
                                        <a href="javascript:void(0);" onclick="Admin.deleteItem({{$item['news_id']}},2)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                    @endif
                                    <span class="img_loading" id="img_loading_{{$item['news_id']}}"></span>
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