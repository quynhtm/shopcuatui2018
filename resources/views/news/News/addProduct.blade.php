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
            <li><a href="{{URL::route('admin.newsViewProduct')}}"> Danh sách sản phẩm</a></li>
            <li class="active">@if($id > 0)Cập nhật sản phẩm @else Tạo mới sản phẩm @endif</li>
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
                <div>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="name" class="control-label">Tiêu đề <span class="red"> (*) </span></label>
                            <input type="text" placeholder="Tên bài viết" id="news_title" name="news_title"  class="form-control input-sm" value="@if(isset($data['news_title'])){{$data['news_title']}}@endif">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <a class="btn btn-warning" href="{{URL::route('admin.newsView')}}"><i class="fa fa-reply"></i> {{FunctionLib::viewLanguage('back')}}</a>
                            <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> {{FunctionLib::viewLanguage('save')}}</button>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="name" class="control-label">Thuộc danh mục</label>
                            <select name="news_category" id="news_category" class="form-control input-sm">
                                <option value="0">--- Chọn danh mục ---</option>
                                {!! $optionCategoryNew !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="name" class="control-label">Trạng thái</label>
                            <select name="news_status" id="news_status" class="form-control input-sm">
                                {!! $optionStatus !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="control-group">
                            <label class="control-label">Hình ảnh</label>
                            <div class="controls">
                                <a href="javascript:;"class="btn btn-primary link-button" onclick="baseUpload.uploadOneImageAdvanced(1);">Upload ảnh</a>
                                <div id="sys_show_image_one">
                                    @if(isset($data['device_image']) && $data['device_image'] !='')
                                        <img src="{{ThumbImg::thumbBaseNormal(Define::FOLDER_DEVICE, $data['device_image'], Define::sizeImage_300, Define::sizeImage_300, '', true, true)}}"/>
                                        <span class="remove_file one" onclick="baseUpload.deleteOneImageAdvanced(0, '{{FunctionLib::inputId($data['device_id'])}}', '{{$data['device_image']}}', 1)">X</span>
                                    @endif
                                </div>
                                <input name="img" type="hidden" id="img" @if(isset($data['device_image']))value="{{$data['device_image']}}"@endif>
                                <input name="img_old" type="hidden" id="img_old" @if(isset($data['device_image']))value="{{$data['device_image']}}"@endif>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Mô tả ngắn</label>
                            <textarea class="form-control input-sm"  name="news_desc_sort">@if(isset($data['news_desc_sort'])){{$data['news_desc_sort']}}@endif</textarea>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Nội dung</label>
                            <textarea class="form-control input-sm"  name="news_content">@if(isset($data['news_content'])){{$data['news_content']}}@endif</textarea>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-left">
                        <a class="btn btn-warning" href="{{URL::route('admin.newsViewProduct')}}"><i class="fa fa-reply"></i> {{FunctionLib::viewLanguage('back')}}</a>
                        <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> {{FunctionLib::viewLanguage('save')}}</button>
                    </div>
                    <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div><!-- /.page-content -->
</div>
<script>
    CKEDITOR.replace('news_desc_sort', {height:300});
    CKEDITOR.replace('news_content', {height:800});
</script>

<!--Popup Upload Img-->
<div class="modal fade" id="sys_PopupUploadImgOtherPro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Upload ảnh</h4>
            </div>
            <div class="modal-body">
                <form name="uploadImage" method="post" action="#" enctype="multipart/form-data">
                    <div class="form_group">
                        <div id="sys_show_button_upload">
                            <div id="sys_mulitplefileuploader" class="btn btn-primary">Upload ảnh</div>
                        </div>
                        <div id="status"></div>

                        <div class="clearfix"></div>
                        <div class="clearfix" style='margin: 5px 10px; width:100%;'>
                            <div id="div_image"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Popup Upload Img-->
@stop
