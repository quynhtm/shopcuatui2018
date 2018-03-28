<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.newsView')}}"> Danh sách Tin tức</a></li>
            <li class="active">@if($id > 0)Cập nhật tin tức @else Tạo mới tin tức @endif</li>
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
                            <label for="name" class="control-label">Tiêu đề tin<span class="red"> (*) </span></label>
                            <input type="text" placeholder="Tên bài viết" id="news_title" name="news_title"  class="form-control input-sm" value="@if(isset($data['news_title'])){{$data['news_title']}}@endif">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Thuộc danh mục</label>
                            <select name="news_category" id="news_category" class="form-control input-sm">
                                <option value="0">--- Chọn danh mục ---</option>
                                {!! $optionCategoryNew !!}}
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Thuộc loại</label>
                            <select name="news_type" id="news_type" class="form-control input-sm">
                                <option value="0">--- Chọn danh mục ---</option>
                                {!! $optionType !!}}
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Mô tả ngắn</label>
                            <textarea class="form-control input-sm"  name="news_desc_sort">@if(isset($data['news_desc_sort'])){{$data['news_desc_sort']}}@endif</textarea>
                        </div>
                    </div>
                    <div style="float: left; width: 70%">
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <a href="javascript:;"class="btn btn-primary" onclick="baseUpload.uploadMultipleImages(2);">Upload ảnh</a>
                                                <input name="image_primary" type="hidden" id="image_primary" value="@if(isset($data['news_image'])){{$data['news_image']}}@endif">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-sm-12">
                                            <!--Hien Thi Anh-->
                                            <ul id="sys_drag_sort" class="ul_drag_sort">
                                                @if(isset($news_image_other))
                                                    @foreach($news_image_other as $k=>$v)
                                                        <li id="sys_div_img_other_{{$k}}">
                                                            <div class="div_img_upload">
                                                                <img src="{{$v['src_img_other']}}" height="80">
                                                                <input type="hidden" id="sys_img_other_{{$k}}" name="img_other[]" value="{{$v['img_other']}}" class="sys_img_other">
                                                                <div class='clear'></div>
                                                                <input type="radio" id="checked_image_{{$k}}" name="checked_image" value="{{$k}}"
                                                                       @if(isset($news_image) && ($news_image == $v['img_other'])) checked="checked" @endif
                                                                       onclick="baseUpload.checkedImage('{{$v['img_other']}}','{{$k}}');">
                                                                <label for="checked_image_{{$k}}" style='font-weight:normal'>Ảnh đại diện</label>
                                                                <br/>
                                                                <a href="javascript:void(0);" id="sys_delete_img_other_{{$k}}" onclick="baseUpload.removeImage('{{$k}}', '{{FunctionLib::inputId($data['news_id'])}}', '{{$v['img_other']}}', '2');">Xóa ảnh</a>
                                                                <span style="display: none"><b>{{$k}}</b></span>
                                                            </div>
                                                        </li>
                                                        @if(isset($news_image) && $news_image == $v['img_other'])
                                                            <input type="hidden" id="sys_key_image_primary" name="sys_key_image_primary" value="{{$k}}">
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <input type="hidden" id="sys_key_image_primary" name="sys_key_image_primary" value="-1">
                                                @endif
                                            </ul>
                                            <input name="list1SortOrder" id ='list1SortOrder' type="hidden" />
                                            <!--Hien Thi Anh-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Nội dung chi tiết</label>
                            <div class="form-group">
                                <div class="controls"><button type="button" onclick="baseUpload.getInsertImageContent(2)" class="btn btn-primary">Chèn ảnh vào nội dung</button></div>
                                <textarea class="form-control input-sm"  name="news_content">@if(isset($data['news_content'])){{$data['news_content']}}@endif</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name" class="control-label">Thứ tự hiển thị</label>
                            <input type="text" placeholder="Thứ tự hiển thị" id="news_order_no" name="news_order_no"  class="form-control input-sm" value="@if(isset($data['news_order_no'])){{$data['news_order_no']}}@endif">
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
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-left">
                        <a class="btn btn-warning" href="{{URL::route('admin.newsView')}}"><i class="fa fa-reply"></i> {{FunctionLib::viewLanguage('back')}}</a>
                        <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> {{FunctionLib::viewLanguage('save')}}</button>
                    </div>
                    <input type="hidden" id="id_hiden" name="id_hiden" value="{{FunctionLib::inputId($id)}}"/>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div><!-- /.page-content -->
</div>
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

<!--Popup chen anh vào noi dung-->
<div class="modal fade" id="sys_PopupImgOtherInsertContent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Click ảnh để chèn vào nội dung</h4>
            </div>
            <div class="modal-body">
                <form name="uploadImage" method="post" action="#" enctype="multipart/form-data">
                    <div class="form_group">
                        <div class="clearfix"></div>
                        <div class="clearfix" style='margin: 5px 10px; width:100%;'>
                            <div id="div_image" class="float_left"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Popup chen anh vào noi dung-->
<script>
    CKEDITOR.replace('news_content', {height:800});
    //Keo Tha Anh
    jQuery("#sys_drag_sort").dragsort({ dragSelector: "div", dragBetween: true, dragEnd: saveOrder });
    function saveOrder() {
        var data = jQuery("#sys_drag_sort li div span").map(function() { return jQuery(this).children().html(); }).get();
        jQuery("input[name=list1SortOrder]").val(data.join(","));
    };
    //Chen Anh Vao Noi Dung
    function insertImgContent(src){
        CKEDITOR.instances.news_content.insertHtml('<img src="'+src+'"/>');
    }
</script>
@stop
