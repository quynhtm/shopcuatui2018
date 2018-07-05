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
                <li class="active">Bổ xung thêm thông tin nhân sự</li>
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
                        <div class="clear"></div>
                        @if(isset($infoPerson))
                            <div class="col-sm-12">
                                <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
                                <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
                                <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
                            </div>
                            <hr/>
                        @endif
                        <div class="clear"></div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="control-label">Chức vụ (vị trí việc làm)<span class="red"> (*) </span></label>
                                <select name="person_extend_chucvu_hiennay" id="person_extend_chucvu_hiennay" class="form-control input-sm">
                                    {!! $optionChucVu !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Chức vụ kiêm nhiệm</label>
                                <input type="text" id="person_extend_chucvu_kiemnhiem" name="person_extend_chucvu_kiemnhiem"  class="form-control input-sm" value="@if(isset($data['person_extend_chucvu_kiemnhiem'])){{$data['person_extend_chucvu_kiemnhiem']}}@endif">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Chức danh KHCN</label>
                                <select name="person_extend_chucdanh_khcn" id="person_extend_chucdanh_khcn" class="form-control input-sm">
                                    {!! $optionChucDanhKHCN !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Cấp ủy hiện tại</label>
                                <select name="person_extend_capuy_hiennay" id="person_extend_capuy_hiennay" class="form-control input-sm">
                                    {!! $optionCapUy_hiennay !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Cấp ủy kiêm nhiệm</label>
                                <select name="person_extend_capuy_kiemnhiem" id="person_extend_capuy_kiemnhiem" class="form-control input-sm">
                                    {!! $optionCapUy_kiemnhiem !!}
                                </select>
                            </div>
                        </div>

                        <div class="clear mgt10"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Thành phần gia đình xuất thân</label>
                                <select name="person_extend_thanhphan_giadinh" id="person_extend_thanhphan_giadinh" class="form-control input-sm">
                                    {!! $optionThanhphan_giadinh !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Nghề nghiệp trước khi được tuyển dụng</label>
                                <input type="text" id="person_extend_nghenghiep_hiennay" name="person_extend_nghenghiep_hiennay"  class="form-control input-sm" value="@if(isset($data['person_extend_nghenghiep_hiennay'])){{$data['person_extend_nghenghiep_hiennay']}}@endif">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày được tuyển dụng</label>
                                <input type="text" class="form-control" id="person_extend_ngaytuyendung" name="person_extend_ngaytuyendung" value="@if(isset($data['person_extend_ngaytuyendung']) && $data['person_extend_ngaytuyendung'] != 0){{date('d-m-Y',$data['person_extend_ngaytuyendung'])}}@endif">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Vào cơ quan nào ở đâu</label>
                                <input type="text" id="person_extend_name_company" name="person_extend_name_company"  class="form-control input-sm" value="@if(isset($data['person_extend_name_company'])){{$data['person_extend_name_company']}}@endif">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày làm việc</label>
                                <input type="text" class="form-control" id="person_extend_ngaylamviec" name="person_extend_ngaylamviec" value="@if(isset($data['person_extend_ngaylamviec']) && $data['person_extend_ngaylamviec'] != 0){{date('d-m-Y',$data['person_extend_ngaylamviec'])}}@endif">
                            </div>
                        </div>

                        <div class="clear mgt10"></div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="control-label">Công tác chính đang làm</label>
                                <select name="person_extend_congtac_danglam" id="person_extend_congtac_danglam" class="form-control input-sm">
                                    {!! $optionCongtac_danglam !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="control-label">Sở trường công tác</label>
                                <input type="text" id="person_extend_sotruong_congtac" name="person_extend_sotruong_congtac"  class="form-control input-sm" value="@if(isset($data['person_extend_sotruong_congtac'])){{$data['person_extend_sotruong_congtac']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Trình độ quản lý nhà nước</label>
                                <select name="person_extend_trinhdo_quanly_nhanuoc" id="person_extend_trinhdo_quanly_nhanuoc" class="form-control input-sm">
                                    {!! $optionQL_nha_nuoc !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Năm đạt</label>
                                <select name="person_extend_namdat_qlnn" id="person_extend_namdat_qlnn" class="form-control input-sm">
                                    {!! $optionYears_namdat_qlnn !!}
                                </select>
                            </div>
                        </div>


                        <div class="clear mgt10"></div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày tham gia cách mạng</label>
                                <input type="text" class="form-control" id="person_extend_ngaythamgia_cachmang" name="person_extend_ngaythamgia_cachmang" value="@if(isset($data['person_extend_ngaythamgia_cachmang']) && $data['person_extend_ngaythamgia_cachmang'] != 0){{date('d-m-Y',$data['person_extend_ngaythamgia_cachmang'])}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label for="name" class="control-label">Đảng viên</label>
                                <select name="person_extend_is_dangvien" id="person_extend_is_dangvien" class="form-control input-sm">
                                    {!! $optionDangVien !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày vào Đảng</label>
                                <input type="text" class="form-control" id="person_extend_ngayvaodang" name="person_extend_ngayvaodang" value="@if(isset($data['person_extend_ngayvaodang']) && $data['person_extend_ngayvaodang'] != 0){{date('d-m-Y',$data['person_extend_ngayvaodang'])}}@endif">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày vào Đảng chính thức</label>
                                <input type="text" class="form-control" id="person_extend_ngayvaodang_chinhthuc" name="person_extend_ngayvaodang_chinhthuc" value="@if(isset($data['person_extend_ngayvaodang_chinhthuc']) && $data['person_extend_ngayvaodang_chinhthuc'] != 0){{date('d-m-Y',$data['person_extend_ngayvaodang_chinhthuc'])}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày tham gia các tổ chức</label>
                                <input type="text" class="form-control" id="person_extend_ngaythamgia_tochuc" name="person_extend_ngaythamgia_tochuc" value="@if(isset($data['person_extend_ngaythamgia_tochuc']) && $data['person_extend_ngaythamgia_tochuc'] != 0){{date('d-m-Y',$data['person_extend_ngaythamgia_tochuc'])}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Công việc làm lâu nhất</label>
                                <select name="person_extend_congviec_launhat" id="person_extend_congviec_launhat" class="form-control input-sm">
                                    {!! $optionCongviec_launhat !!}
                                </select>
                            </div>
                        </div>



                        <div class="clear"></div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name" class="control-label">Thương binh hạng</label>
                                <select name="person_extend_thuongbinh" id="person_extend_thuongbinh" class="form-control input-sm">
                                    {!! $optionThuongbinh !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="name" class="control-label">Gia đình chính sách</label>
                                <input type="text" class="form-control" id="person_extend_giadinh_chinhsach" name="person_extend_giadinh_chinhsach" value="@if(isset($data['person_extend_giadinh_chinhsach'])){{$data['person_extend_giadinh_chinhsach']}}@endif">
                            </div>
                        </div>



                        <div class="clear"></div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày nhập ngũ</label>
                                <input type="text" class="form-control" id="person_extend_ngaynhapngu" name="person_extend_ngaynhapngu" value="@if(isset($data['person_extend_ngaynhapngu']) && $data['person_extend_ngaynhapngu'] != 0){{date('d-m-Y',$data['person_extend_ngaynhapngu'])}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngày xuất ngũ</label>
                                <input type="text" class="form-control" id="person_extend_ngayxuatngu" name="person_extend_ngayxuatngu" value="@if(isset($data['person_extend_ngayxuatngu']) && $data['person_extend_ngayxuatngu'] != 0){{date('d-m-Y',$data['person_extend_ngayxuatngu'])}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Quân hàm, chức vụ cao nhất</label>
                                <select name="person_extend_chucvu_quanngu" id="person_extend_chucvu_quanngu" class="form-control input-sm">
                                    {!! $optionQuanham !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Trình độ học vấn</label>
                                <select name="person_extend_trinhdo_hocvan" id="person_extend_trinhdo_hocvan" class="form-control input-sm">
                                    {!! $optionHocvan !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Trình độ tin học</label>
                                <select name="person_extend_trinhdo_tinhoc" id="person_extend_trinhdo_tinhoc" class="form-control input-sm">
                                    {!! $optionTinhoc !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Năm đạt</label>
                                <select name="person_extend_namdat_tinhoc" id="person_extend_namdat_tinhoc" class="form-control input-sm">
                                    {!! $optionYears_namdat_tinhoc !!}
                                </select>
                            </div>
                        </div>

                        <div class="clear"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Học hàm</label>
                                <select name="person_extend_hoc_ham" id="person_extend_hoc_ham" class="form-control input-sm">
                                    {!! $optionHocHam !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <div class="form-group">
                                <label for="name" class="control-label">Năm đạt</label>
                                <select name="person_extend_namdat_hoc_ham" id="person_extend_namdat_hoc_ham" class="form-control input-sm">
                                    {!! $optionYears_namdat_hoc_ham !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Học vị</label>
                                <select name="person_extend_hoc_vi" id="person_extend_hoc_vi" class="form-control input-sm">
                                    {!! $optionHocvi !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <div class="form-group">
                                <label for="name" class="control-label">Năm đạt</label>
                                <select name="person_extend_namdat_hoc_vi" id="person_extend_namdat_hoc_vi" class="form-control input-sm">
                                    {!! $optionYears_namdat_hoc_vi !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="control-label">Lý luận chính trị</label>
                                <select name="person_extend_lyluan_chinhtri" id="person_extend_lyluan_chinhtri" class="form-control input-sm">
                                    {!! $optionLyluan_chinhtri !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <div class="form-group">
                                <label for="name" class="control-label">Năm đạt</label>
                                <select name="person_extend_namdat_lyluan_chinhtri" id="person_extend_namdat_lyluan_chinhtri" class="form-control input-sm">
                                    {!! $optionYears_namdat_lyluan_chinhtri !!}
                                </select>
                            </div>
                        </div>

                        <div class="clear mgt10"></div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngoại ngữ 1</label>
                                <select name="person_extend_language_1" id="person_extend_language_1" class="form-control input-sm">
                                    {!! $optionNgoaiNgu_1 !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label for="name" class="control-label">Trình độ</label>
                                <select name="person_extend_trinhdo_1" id="person_extend_trinhdo_1" class="form-control input-sm">
                                    {!! $optionTrinhdoNgoaiNgu_1 !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngoại ngữ 2</label>
                                <select name="person_extend_language_2" id="person_extend_language_2" class="form-control input-sm">
                                    {!! $optionNgoaiNgu_2 !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label for="name" class="control-label">Trình độ</label>
                                <select name="person_extend_trinhdo_2" id="person_extend_trinhdo_2" class="form-control input-sm">
                                    {!! $optionTrinhdoNgoaiNgu_2 !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngoại ngữ 3</label>
                                <select name="person_extend_language_3" id="person_extend_language_3" class="form-control input-sm">
                                    {!! $optionNgoaiNgu_3 !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label for="name" class="control-label">Trình độ</label>
                                <select name="person_extend_trinhdo_3" id="person_extend_trinhdo_3" class="form-control input-sm">
                                    {!! $optionTrinhdoNgoaiNgu_3 !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="control-label">Ngoại ngữ 4</label>
                                <select name="person_extend_language_4" id="person_extend_language_4" class="form-control input-sm">
                                    {!! $optionNgoaiNgu_4 !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label for="name" class="control-label">Trình độ</label>
                                <select name="person_extend_trinhdo_4" id="person_extend_trinhdo_4" class="form-control input-sm">
                                    {!! $optionTrinhdoNgoaiNgu_4 !!}
                                </select>
                            </div>
                        </div>



                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 text-left">
                            {!! csrf_field() !!}
                            <input id="id_hiden" name="id_hiden" @isset($data['person_extend_person_id'])rel="{{$data['person_extend_person_id']}}" value="{{FunctionLib::inputId($data['person_extend_person_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
                            <a class="btn btn-warning" href="{{URL::route('hr.personnelView')}}"><i class="fa fa-reply"></i> Trở lại</a>
                            <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var dateToday = new Date();
            var person_extend_ngaytuyendung = $('#person_extend_ngaytuyendung').datepicker({timepicker:false,dateFormat: 'dd-mm-yy',lang:'vi'});
            var person_extend_ngaylamviec = $('#person_extend_ngaylamviec').datepicker({timepicker:false,dateFormat: 'dd-mm-yy',lang:'vi',});
            var person_extend_ngaythamgia_cachmang = $('#person_extend_ngaythamgia_cachmang').datepicker({timepicker:false,dateFormat: 'dd-mm-yy',lang:'vi'});
            var person_extend_ngayvaodang = $('#person_extend_ngayvaodang').datepicker({timepicker:false,dateFormat: 'dd-mm-yy',lang:'vi'});
            var person_extend_ngayvaodang_chinhthuc = $('#person_extend_ngayvaodang_chinhthuc').datepicker({timepicker:false,dateFormat: 'dd-mm-yy',lang:'vi'});
            var person_extend_ngaythamgia_tochuc = $('#person_extend_ngaythamgia_tochuc').datepicker({timepicker:false,dateFormat: 'dd-mm-yy',lang:'vi'});
            var person_extend_ngaynhapngu = $('#person_extend_ngaynhapngu').datepicker({timepicker:false,dateFormat: 'dd-mm-yy',lang:'vi'});
            var person_extend_ngayxuatngu = $('#person_extend_ngayxuatngu').datepicker({timepicker:false,dateFormat: 'dd-mm-yy',lang:'vi'});
        });
    </script>
@stop