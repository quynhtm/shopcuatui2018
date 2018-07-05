<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
use App\Library\PHPThumb\ThumbImg;
use App\Library\AdminFunction\CGlobal;
?>
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
                <li class="active">@if($id == 0) Thêm mới nhân sự @else Sửa thông tin nhân sự@endif</li>
            </ul><!-- /.breadcrumb -->
        </div>
        <div class="clear"></div>
        <div class="page-content">
            <div class="row">
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
                    <!--Block 1--->
                        <div class="form-group">
                            <div class="col-md-2" >
                                <div class="control-group">
                                    <div class="controls">
                                        <label for="name" class="control-label">
                                            <a class="text-italic" href="javascript:void(0);" onclick="baseUpload.uploadOneImageAdvanced(2);">Up ảnh nhân sự</a>
                                        </label>
                                        <div id="sys_show_image_one" style="width:100%; height: 240px; overflow: hidden">
                                            @if(isset($data['person_id']) &&isset($data['person_avatar']) && $data['person_avatar'] !='')
                                                <img src="{{ThumbImg::thumbBaseNormal(Define::FOLDER_PERSONAL, $data['person_avatar'], Define::sizeImage_240, Define::sizeImage_300, '', true, true)}}"/>
                                                <span class="remove_file one" onclick="baseUpload.deleteOneImageAdvanced(0, '{{FunctionLib::inputId($data['person_id'])}}', '{{$data['person_avatar']}}', 2)">X</span>
                                            @else
                                                <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/icon/no-profile-image.gif"/>
                                            @endif
                                        </div>
                                        <input name="img" type="hidden" id="img" @if(isset($data['person_avatar']))value="{{$data['person_avatar']}}"@endif>
                                        <input name="img_old" type="hidden" id="img_old" @if(isset($data['person_avatar']))value="{{$data['person_avatar']}}"@endif>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Họ và tên khai sinh<span class="red"> (*) </span></label>
                                    <input type="text" id="person_name" name="person_name"  class="form-control input-sm" value="@if(isset($data['person_name'])){{$data['person_name']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tên gọi khác</label>
                                    <input type="text"  id="person_name_other" name="person_name_other" class="form-control input-sm" value="@if(isset($data['person_name_other'])){{$data['person_name_other']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số di động</label>
                                    <input type="text"  id="person_phone" name="person_phone"  class="form-control input-sm" value="@if(isset($data['person_phone'])){{$data['person_phone']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">ĐT nhà riêng/cơ quan</label>
                                    <input type="text" id="person_telephone" name="person_telephone"  class="form-control input-sm" value="@if(isset($data['person_telephone'])){{$data['person_telephone']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày sinh</label>
                                    <input type="text" class="form-control" id="person_birth" name="person_birth" value="@if(isset($data['person_birth']) && $data['person_birth'] != 0){{date('d-m-Y',$data['person_birth'])}}@endif">
                                </div>
                            </div>


                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="control-label">Phòng ban đơn vị<span class="red"> (*) </span></label>
                                    <select name="person_depart_id" id="person_depart_id" class="form-control input-sm">
                                        {!! $optionDepart !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số hiệu công chức</label>
                                    <input type="text" id="person_code" name="person_code"  class="form-control input-sm" value="@if(isset($data['person_code'])){{$data['person_code']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Giới tính</label>
                                    <select name="person_sex" id="person_sex" class="form-control input-sm">
                                        {!! $optionSex !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Email</label>
                                    <input type="text" id="person_mail" name="person_mail"  class="form-control input-sm" value="@if(isset($data['person_mail'])){{$data['person_mail']}}@endif">
                                </div>
                            </div>



                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số CMT<span class="red"> (*) </span></label>
                                    <input type="text"  id="person_chung_minh_thu" name="person_chung_minh_thu"  class="form-control input-sm" value="@if(isset($data['person_chung_minh_thu'])){{$data['person_chung_minh_thu']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Nơi cấp</label>
                                    <input type="text"  id="person_issued_cmt" name="person_issued_cmt"  class="form-control input-sm" value="@if(isset($data['person_issued_cmt'])){{$data['person_issued_cmt']}}@endif">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="control-label">Chức vụ</label>
                                    <select name="person_position_define_id" id="person_position_define_id" class="form-control input-sm">
                                        {!! $optionChucVu !!}
                                    </select>
                                </div>
                            </div>
                            {{--<div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Chức danh nghề nghiệp</label>
                                    <select name="person_career_define_id" id="person_career_define_id" class="form-control input-sm">
                                        {!! $optionChucDanhNgheNghiep !!}
                                    </select>
                                </div>
                            </div>--}}
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Nhóm máu</label>
                                    <select name="person_blood_group_define_id" id="person_blood_group_define_id" class="form-control input-sm">
                                        {!! $optionNhomMau !!}
                                    </select>
                                </div>
                            </div>
                        </div>


                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày cấp CMT<span class="red"> (*) </span></label>
                                    <input type="text" class="form-control" id="person_date_range_cmt" name="person_date_range_cmt"  data-date-format="dd-mm-yyyy" value="@if(isset($data['person_date_range_cmt']) && $data['person_date_range_cmt'] != 0){{date('d-m-Y',$data['person_date_range_cmt'])}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày thử việc</label>
                                    <input type="text" class="form-control" id="person_date_trial_work" name="person_date_trial_work"  data-date-format="dd-mm-yyyy"value="@if(isset($data['person_date_trial_work']) && $data['person_date_trial_work'] != 0){{date('d-m-Y',$data['person_date_trial_work'])}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày làm chính thức<span class="red"> (*) </span></label>
                                    <input type="text" class="form-control" id="person_date_start_work" name="person_date_start_work"  data-date-format="dd-mm-yyyy" value="@if(isset($data['person_date_start_work']) && $data['person_date_start_work'] != 0){{date('d-m-Y',$data['person_date_start_work'])}}@endif">
                                </div>
                            </div>

                    <!--Block 2--->
                        <div class="clear"></div>
                        <div class="form-group">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Địa chỉ nơi sinh<span class="red"> (*) </span></label>
                                    <input type="text"  id="person_address_place_of_birth" name="person_address_place_of_birth"  class="form-control input-sm" value="@if(isset($data['person_address_place_of_birth'])){{$data['person_address_place_of_birth']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tỉnh thành nơi sinh<span class="red"> (*) </span></label>
                                    <select name="person_province_place_of_birth" id="person_province_place_of_birth" class="form-control input-sm">
                                        {!! $optionProvincePlaceBirth !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Địa chỉ quê quán<span class="red"> (*) </span></label>
                                    <input type="text"  id="person_address_home_town" name="person_address_home_town"  class="form-control input-sm" value="@if(isset($data['person_address_home_town'])){{$data['person_address_home_town']}}@endif">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tỉnh thành quê quán<span class="red"> (*) </span></label>
                                    <select name="person_province_home_town" id="person_province_home_town" class="form-control input-sm">
                                        {!! $optionProvinceHomeTown !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Dân tộc</label>
                                    <select name="person_nation_define_id" id="person_nation_define_id" class="form-control input-sm">
                                        {!! $optionDanToc !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tôn giáo</label>
                                    <select name="person_respect" id="person_respect" class="form-control input-sm">
                                        {!! $optionTonGiao !!}
                                    </select>
                                </div>
                            </div>

                            <div class="clear"></div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Địa chỉ hiện tại<span class="red"> (*) </span></label>
                                    <input type="text"  id="person_address_current" name="person_address_current"  class="form-control input-sm" value="@if(isset($data['person_address_current'])){{$data['person_address_current']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tỉnh thành hiện tại<span class="red"> (*) </span></label>
                                    <select name="person_province_current" id="person_province_current" class="form-control input-sm" onchange="Admin.getAjaxDistrictsProvince(this,1,'person_districts_current')">
                                        {!! $optionProvinceCurrent !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Quận huyện hiện tại<span class="red"> (*) </span></label>
                                    <div id="show_person_districts_current">
                                        <select name="person_districts_current" id="person_districts_current" class="form-control input-sm" onchange="Admin.getAjaxDistrictsProvince(this,2,'person_wards_current')">
                                            {!! $optionDistrictsCurrent !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Phường xã hiện tại<span class="red"> (*) </span></label>
                                    <div id="show_person_wards_current">
                                        <select name="person_wards_current" id="person_wards_current" class="form-control input-sm">
                                            {!! $optionWardsCurrent !!}
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Chiều cao</label>
                                    <input type="text"  id="person_height" name="person_height"  class="form-control input-sm" value="@if(isset($data['person_height'])){{$data['person_height']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Cân nặng</label>
                                    <input type="text" id="person_weight" name="person_weight"  class="form-control input-sm" value="@if(isset($data['person_weight'])){{$data['person_weight']}}@endif">
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 text-left">
                            {!! csrf_field() !!}
                            <input id="id_hiden" name="id_hiden" @isset($data['person_id'])rel="{{$data['person_id']}}" value="{{FunctionLib::inputId($data['person_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
                            <a class="btn btn-warning" href="{{URL::route('hr.personnelView')}}"><i class="fa fa-reply"></i> Trở lại</a>
                            <button class="btn btn-primary" name="save_form" value="{{\App\Library\AdminFunction\Define::SUBMIT_BACK_LIST}}"><i class="fa fa-floppy-o"></i> Lưu lại</button>
                            <button class="btn btn-success" name="save_form" value="{{\App\Library\AdminFunction\Define::SUBMIT_BACK_NEXT}}"><i class="fa fa-forward"></i> Lưu và tiếp tục</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var dateToday = new Date();
            var person_birth = $('#person_birth').datepicker({
                timepicker:false,
                dateFormat: 'dd-mm-yy',
                lang:'vi',
            });
            var person_date_trial_work = $('#person_date_trial_work').datepicker({
                timepicker:false,
                dateFormat: 'dd-mm-yy',
                lang:'vi',
                minDate: dateToday,
            });
            var person_date_start_work = $('#person_date_start_work').datepicker({
                timepicker:false,
                dateFormat: 'dd-mm-yy',
                lang:'vi',
                minDate: dateToday,
            });
            var person_date_range_cmt = $('#person_date_range_cmt').datepicker({
                timepicker:false,
                format:'d-m-Y',
                lang:'vi',
            });
        });
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