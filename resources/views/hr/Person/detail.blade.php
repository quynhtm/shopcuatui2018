<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
<?php use App\Library\PHPThumb\ThumbImg; ?>
@extends('admin.AdminLayouts.index')
@section('content')
    <div class="main-content-inner">
        <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{URL::route('admin.dashboard')}}">Trang chủ</a>
                </li>
                <li class="active">Chi tiết nhân sự 2C</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-default">
                {{ csrf_field() }}
                <div class="panel-body-ns">
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <div class="line">
                                <div class="panel-heading clearfix">
                                    <h4 class="panel-title pull-left">Chi tiết nhân sự 2C</h4>
                                    <div class="btn-group btn-group-sm pull-right">
                                        <span>
                                            <a class="btn btn-warning btn-sm" href="{{URL::route('hr.personnelEdit',array('id' => FunctionLib::inputId($infoPerson['person_id'])))}}"><i class="fa fa-edit"></i> Sửa thông tin nhân sự</a>&nbsp;
                                        </span>
                                        <span>
                                            <a class="btn btn-danger btn-sm" href="{{URL::route('hr.personnelView')}}"><i class="fa fa-arrow-left"></i> Quay lại</a>
                                        </span>

                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="line">
                                        <table class="table table-bordered table-condensed detailPerson">
                                            <tbody>
                                            <tr>
                                                <td rowspan="7" width="10%">
                                                    @if(isset($infoPerson['person_avatar']) && $infoPerson['person_avatar'] !='')
                                                        <img width="100%" src="{{ThumbImg::thumbBaseNormal(Define::FOLDER_PERSONAL, $infoPerson['person_avatar'], Define::sizeImage_240, Define::sizeImage_300, '', true, true)}}"/>
                                                    @else
                                                        <img width="100%" src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/icon/no-profile-image.gif"/>
                                                    @endif
                                                </td>
                                                <td><span class="lbl text-nowrap">Phòng ban/ Đơn vị</span></td>
                                                <td><span class="val">@if(isset($arrDepart[$infoPerson['person_depart_id']])){{$arrDepart[$infoPerson['person_depart_id']]}}@endif</span></td>
                                                <td><span class="lbl text-nowrap">Số hiệu cán bộ, công chức</span></td>
                                                <td><span class="val">{{$infoPerson->person_code}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Họ và tên khai sinh</span></td>
                                                <td><span class="val">{{$infoPerson->person_name}}</span></td>
                                                <td><span class="lbl text-nowrap">Tên gọi khác</span></td>
                                                <td><span class="val">{{$infoPerson->person_name_other}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Email</span></td>
                                                <td><span class="val">{{$infoPerson->person_mail}}</span></td>
                                                <td><span class="lbl text-nowrap">Điện thoại</span></td>
                                                <td><span class="val">{{$infoPerson->person_phone}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Ngày sinh</span></td>
                                                <td><span class="val">{{($infoPerson['person_birth'] != 0) ? date('d/m/Y', $infoPerson['person_birth']) : ''}}</span></td>
                                                <td><span class="lbl text-nowrap">Giới tính</span></td>
                                                <td><span class="val">{{(isset($item->person_sex) && $item->person_sex == 1) ? 'Nam' : 'Nữ'}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Chức vụ (Vị trí việc làm)</span></td>
                                                <td><span class="val">@if(isset($arrChucVu[$infoPerson['person_position_define_id']])){{$arrChucVu[$infoPerson['person_position_define_id']]}}@endif</span></td>
                                                <td><span class="lbl text-nowrap">Chức danh</span></td>
                                                <td colspan="2"><span class="val">@if(isset($arrChucDanhNgheNghiep[$infoPerson['person_career_define_id']])){{$arrChucDanhNgheNghiep[$infoPerson['person_career_define_id']]}}@endif</span></td>
                                            </tr>

                                            <tr>
                                                <td><span class="lbl text-nowrap">Chức danh KHCN</span></td>
                                                <td><span class="val">
                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrChucDanhKHCN[$dataExtend->person_extend_chucdanh_khcn]))
                                                            {{$arrChucDanhKHCN[$dataExtend->person_extend_chucdanh_khcn]}}
                                                        @endif
                                                    </span></td>
                                                <td><span class="lbl text-nowrap">Cấp ủy hiện tại, cấp ủy kiêm</span></td>
                                                <td colspan="2">
                                                    <span class="val">
                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrCapUy[$dataExtend->person_extend_capuy_hiennay]))
                                                            {{$arrCapUy[$dataExtend->person_extend_capuy_hiennay]}}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><span class="lbl text-nowrap">Nơi sinh</span></td>
                                                <td><span class="val">{{$infoPerson['person_address_place_of_birth']}}</span></td>
                                                <td><span class="lbl text-nowrap">Quê quán</span></td>
                                                <td colspan="2"><span class="val">{{$infoPerson['person_address_home_town']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Dân tộc</span></td>
                                                <td><span class="val">@if(isset($arrDanToc[$infoPerson['person_nation_define_id']])){{$arrDanToc[$infoPerson['person_nation_define_id']]}}@endif</span></td>
                                                <td><span class="lbl text-nowrap">Tôn giáo</span></td>
                                                <td  colspan="2"><span class="val">@if(isset($arrTonGiao[$infoPerson['person_respect']])){{$arrTonGiao[$infoPerson['person_respect']]}}@endif</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Nơi ở hiện nay</span></td>
                                                <td><span class="val" colspan="3">{{$infoPerson['person_address_current']}}</span></td>
                                                <td><span class="lbl text-nowrap">Thành phần gia đình xuất thân</span></td>
                                                <td colspan="2">
                                                    <span class="val">
                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrThanhphangiadinh[$dataExtend->person_extend_thanhphan_giadinh]))
                                                            {{$arrThanhphangiadinh[$dataExtend->person_extend_thanhphan_giadinh]}}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><span class="lbl text-nowrap">Nghề nghiệp bản thân trước khi được tuyển dụng</span></td>
                                                <td>
                                                    <span class="val">
                                                        @if(isset($dataExtend->person_extend_nghenghiep_hiennay))
                                                            {{$dataExtend->person_extend_nghenghiep_hiennay}}
                                                        @endif
                                                    </span></td>
                                                <td><span class="lbl text-nowrap">Vào cơ quan nào, ở đâu</span></td>
                                                <td>
                                                    <span class="val">
                                                         @if(isset($dataExtend->person_extend_name_company))
                                                            {{$dataExtend->person_extend_name_company}}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>

                                                <td><span class="lbl text-nowrap">Ngày được tuyển dụng</span></td>
                                                <td colspan="2"><span class="val">{{($infoPerson['person_date_trial_work']  != 0) ? date('d/m/Y', $infoPerson['person_date_trial_work']) : ''}}</span></td>
                                                <td><span class="lbl text-nowrap">Ngày vào cơ quan đang công tác</span></td>
                                                <td colspan="2"><span class="val">{{($infoPerson['person_date_trial_work']  != 0) ? date('d/m/Y', $infoPerson['person_date_trial_work']) : ''}}</span></td>
                                            </tr>

                                            <tr>
                                                <td><span class="lbl text-nowrap">Ngày tham gia cách mạng</span></td>
                                                <td><span class="val">
                                                        @if(isset($dataExtend->person_extend_ngaythamgia_cachmang) && $dataExtend->person_extend_ngaythamgia_cachmang != 0)
                                                            {{date('d/m/Y', $dataExtend->person_extend_ngaythamgia_cachmang)}}
                                                        @endif
                                                    </span></td>
                                                <td><span class="lbl text-nowrap">Ngày vào Đảng</span></td>
                                                <td colspan="2">
                                                    <span class="val">
                                                        @if(isset($dataExtend->person_extend_ngayvaodang) && $dataExtend->person_extend_ngayvaodang != 0)
                                                            {{date('d/m/Y', $dataExtend->person_extend_ngayvaodang)}}
                                                        @endif
                                                    </span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Ngày tham gia các tổ chức chính trị, xã hội</span></td>
                                                <td>
                                                    <span class="val">
                                                         @if(isset($dataExtend->person_extend_ngaythamgia_tochuc) && $dataExtend->person_extend_ngaythamgia_tochuc != 0)
                                                            {{date('d/m/Y', $dataExtend->person_extend_ngaythamgia_tochuc)}}
                                                        @endif
                                                    </span></td>
                                                <td><span class="lbl text-nowrap">Ngày nhập ngũ</span></td>
                                                <td colspan="2">
                                                    <span class="val">
                                                        @if(isset($dataExtend->person_extend_ngaynhapngu) && $dataExtend->person_extend_ngaynhapngu != 0)
                                                            {{date('d/m/Y', $dataExtend->person_extend_ngaynhapngu)}}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Ngày xuất ngũ</span></td>
                                                <td>
                                                    <span class="val">
                                                         @if(isset($dataExtend->person_extend_ngayxuatngu) && $dataExtend->person_extend_ngayxuatngu != 0)
                                                            {{date('d/m/Y', $dataExtend->person_extend_ngayxuatngu)}}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td><span class="lbl text-nowrap">Quân hàm, Chức vụ cao nhất (năm)</span></td>
                                                <td colspan="2">
                                                    <span class="val">
                                                         @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrQuanham[$dataExtend->person_extend_chucvu_quanngu]))
                                                            {{$arrQuanham[$dataExtend->person_extend_chucvu_quanngu]}}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Trình độ học vấn: GDPT</span></td>
                                                <td>
                                                    <span class="val">
                                                         @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrHocvan[$dataExtend->person_extend_trinhdo_hocvan]))
                                                            {{$arrHocvan[$dataExtend->person_extend_trinhdo_hocvan]}}
                                                        @endif
                                                    </span></td>
                                                <td><span class="lbl text-nowrap">Học hàm</span></td>
                                                <td colspan="2">
                                                    <span class="val">
                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrHocHam[$dataExtend->person_extend_hoc_ham]))
                                                            {{$arrHocHam[$dataExtend->person_extend_hoc_ham]}}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Học vị</span></td>
                                                <td>
                                                    <span class="val">
                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrHocvi[$dataExtend->person_extend_hoc_vi]))
                                                            {{$arrHocvi[$dataExtend->person_extend_hoc_vi]}}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td><span class="lbl text-nowrap">Lý luận chính trị</span></td>
                                                <td colspan="2">
                                                    <span class="val">
                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrLyluan_chinhtri[$dataExtend->person_extend_lyluan_chinhtri]))
                                                            {{$arrLyluan_chinhtri[$dataExtend->person_extend_lyluan_chinhtri]}}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Trình độ ngoại ngữ</span></td>
                                                <td><span class="val">
                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrNgoaiNgu[$dataExtend->person_extend_language_1]))
                                                            {{$arrNgoaiNgu[$dataExtend->person_extend_language_1]}}
                                                        @endif

                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrNgoaiNgu[$dataExtend->person_extend_language_2]))
                                                            , {{$arrNgoaiNgu[$dataExtend->person_extend_language_2]}}
                                                        @endif

                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrNgoaiNgu[$dataExtend->person_extend_language_3]))
                                                            , {{$arrNgoaiNgu[$dataExtend->person_extend_language_3]}}
                                                        @endif

                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrNgoaiNgu[$dataExtend->person_extend_language_4]))
                                                            , {{$arrNgoaiNgu[$dataExtend->person_extend_language_4]}}
                                                        @endif

                                                    </span></td>
                                                <td><span class="lbl text-nowrap">Công tác chính đang làm</span></td>
                                                <td colspan="2">
                                                    <span class="val">
                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrCongtac_danglam[$dataExtend->person_extend_congtac_danglam]))
                                                            {{$arrCongtac_danglam[$dataExtend->person_extend_congtac_danglam]}}
                                                        @endif
                                                    </span>
                                                </td>

                                            </tr>
                                            <!--<tr>
                                                <td><span class="lbl text-nowrap">Khen thưởng</span></td>
                                                <td><span class="val">-------<span></td>
                                                <td><span class="lbl text-nowrap">Danh hiệu được phong</span></td>
                                                <td colspan="2"><span class="val">------</span></td>
                                            </tr>-->
                                            <tr>
                                                <td><span class="lbl text-nowrap">Sở trường công tác</span></td>
                                                <td><span class="val">
                                                        @if(isset($dataExtend->person_extend_sotruong_congtac))
                                                            {{$dataExtend->person_extend_sotruong_congtac}}
                                                        @endif
                                                    </span></td>
                                                <td><span class="lbl text-nowrap">Công việc làm lâu nhất</span></td>
                                                <td colspan="2">
                                                    <span class="val">
                                                     @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrCongtac_danglam[$dataExtend->person_extend_congviec_launhat]))
                                                        {{$arrCongtac_danglam[$dataExtend->person_extend_congviec_launhat]}}
                                                     @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <!--<tr>
                                                <td><span class="lbl text-nowrap">Kỷ luật</span></td>
                                                <td><span class="val">------</span></td>
                                                <td><span class="lbl text-nowrap">Tình trạng sức khỏe</span></td>
                                                <td colspan="2"><span class="val">-------</span></td>
                                            </tr>-->
                                            <tr>
                                                <td><span class="lbl text-nowrap">Chiều cao</span></td>
                                                <td><span class="val">{{$infoPerson->person_height}}</span></td>
                                                <td><span class="lbl text-nowrap">Cân nặng</span></td>
                                                <td colspan="2"><span class="val">{{$infoPerson->person_weight}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Nhóm máu</span></td>
                                                <td><span class="val">@if(isset($arrNhomMau[$infoPerson['person_blood_group_define_id']])){{$arrNhomMau[$infoPerson['person_blood_group_define_id']]}}@endif</span></td>
                                                <td><span class="lbl text-nowrap">Số chứng minh thư</span></td>
                                                <td colspan="2"><span class="val">{{$infoPerson['person_chung_minh_thu']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Ngày cấp</span></td>
                                                <td><span class="val">{{($infoPerson['person_date_range_cmt']  != 0) ? date('d/m/Y', $infoPerson['person_date_range_cmt']) : ''}}</span></td>
                                                <td><span class="lbl text-nowrap">Nơi cấp</span></td>
                                                <td colspan="2"><span class="val">{{$infoPerson['person_issued_cmt']}}</span></td>
                                            </tr>

                                            <tr>
                                                <td><span class="lbl text-nowrap">Thương binh hạng</span></td>
                                                <td>
                                                    <span class="val">
                                                        @if(isset($dataExtend) && sizeof($dataExtend) > 0 && isset($arrHangthuongbinh[$dataExtend->person_extend_thuongbinh]))
                                                            {{$arrHangthuongbinh[$dataExtend->person_extend_thuongbinh]}}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td><span class="lbl text-nowrap">Gia đình chính sách</span></td>
                                                <td colspan="2">
                                                    <span class="val">
                                                        @if(isset($dataExtend->person_extend_giadinh_chinhsach))
                                                        {{$dataExtend->person_extend_giadinh_chinhsach}}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><span class="lbl text-nowrap">Hộ chiếu phổ thông</span></td>
                                                <td>
                                                    <span class="val">{{isset($infoPassPort->passport->passport_common) ? $infoPassPort->passport->passport_common : ''}}</span>

                                                    <span class="lbl"> - Cấp từ ngày: </span>
                                                    <span class="val">{{isset($infoPassPort->passport->passport_common_date_range) ? date('d/m/Y', $infoPassPort->passport->passport_common_date_range) : ''}}</span>
                                                    <span class="lbl"> - Đến ngày:</span>
                                                    <span class="val ">{{isset($infoPassPort->passport->passport_common_date_expiration) ? date('d/m/Y', $infoPassPort->passport->passport_common_date_expiration) : ''}}</span>
                                                </td>
                                                <td><span class="lbl text-nowrap">Hộ chiếu công vụ</span></td>
                                                <td colspan="2">
                                                    <span class="val">{{isset($infoPassPort->passport->passport_equitment) ? $infoPassPort->passport->passport_equitment : ''}}</span>

                                                    <span class="lbl"> - Cấp từ ngày: </span>
                                                    <span class="val">{{isset($infoPassPort->passport->passport_equitment_date_range) ? date('d/m/Y', $infoPassPort->passport->passport_equitment_date_range) : ''}}</span>
                                                    <span class="lbl"> - Đến ngày:</span>
                                                    <span class="val ">{{isset($infoPassPort->passport->passport_equitment_date_expiration) ? date('d/m/Y', $infoPassPort->passport->passport_equitment_date_expiration) : ''}}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><span class="lbl text-nowrap">Mã số thuế cá nhân</span></td>
                                                <td><span class="val">{{isset($infoPassPort->passport->passport_personal_code) ? $infoPassPort->passport->passport_personal_code : ''}}</span></td>
                                                <td><span class="lbl text-nowrap">Tài khoản ngân hàng</span></td>
                                                <td colspan="2"><span class="val">{{isset($infoPassPort->passport->passport_bank_account_number) ? $infoPassPort->passport->passport_bank_account_number : ''}}</span></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">ĐÀO TẠO BỒI DƯỠNG VỀ CHUYÊN MÔN NGHIỆP VỤ, LÝ LUÂN CHÍNH TRỊ, NGOẠI NGỮ</div>
                                            <p><strong class="text-uppercase">Các khóa đào tạo có chuyên ngành trong hệ thống</strong></p>
                                            <table class="table table-bordered" id="tblDiploma">
                                                <tbody>
                                                <tr>
                                                    <th>Tên trường</th>
                                                    <th>Ngành học hoặc tên lớp học</th>
                                                    <th>Thời gian học</th>
                                                    <th>Hình thức học</th>
                                                    <th>Văng bằng, chứng chỉ, trình độ gì</th>
                                                </tr>
                                                @if(sizeof($arrCurriculumVitaeMain) > 0)
                                                    @foreach($arrCurriculumVitaeMain as $item)
                                                    <tr>
                                                        <td>{{$item->curriculum_address_train}}</td>
                                                        <td>@if(isset($arrChuyenNghanhDaoTao[$item['curriculum_training_id']])){{$arrChuyenNghanhDaoTao[$item['curriculum_training_id']]}}@endif, Lớp học: {{$item->curriculum_classic}}</td>
                                                        <td>{{$item['curriculum_month_in']}}/{{$item['curriculum_year_in']}} -{{$item['curriculum_month_out']}}/{{$item['curriculum_year_out']}}</td>
                                                        <td>@if(isset($arrHinhThucHoc[$item['curriculum_formalities_id']])){{$arrHinhThucHoc[$item['curriculum_formalities_id']]}}@endif</td>
                                                        <td>
                                                            @if(isset($arrVanBangChungChi[$item['curriculum_certificate_id']])){{$arrVanBangChungChi[$item['curriculum_certificate_id']]}}@endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop10 row">
                                        <div class="col-md-12">
                                            <p><strong class="text-uppercase">Các khóa đào tạo có chuyên ngành khác</strong></p>
                                            <table class="table table-bordered" id="tblDiploma">
                                                <tbody>
                                                    <tr>
                                                        <th>Tên trường, địa điểm</th>
                                                        <th>Ngành học hoặc tên lớp học</th>
                                                        <th>Thời gian học</th>
                                                        <th>Văng bằng, chứng chỉ, trình độ gì</th>
                                                        <th>Tổ chức cấp</th>
                                                    </tr>
                                                    @if(sizeof($arrCurriculumVitaeOther) > 0)
                                                        @foreach($arrCurriculumVitaeOther as $item)
                                                            <tr>
                                                                <td>{{$item->curriculum_address_train}}</td>
                                                                <td>{{$item->curriculum_training_name}}</td>
                                                                <td>{{$item['curriculum_month_in']}}/{{$item['curriculum_year_in']}} -{{$item['curriculum_month_out']}}/{{$item['curriculum_year_out']}}</td>
                                                                <td>{{$item->curriculum_certificate_name}}</td>
                                                                <td>{{$item->curriculum_formalities_name}}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">TÓM TẮT QUÁ TRÌNH CÔNG TÁC</div>
                                            <table class="table table-bordered" id="tblWork">
                                                <tbody>
                                                    <tr>
                                                        <th>Từ tháng năm đến tháng năm</th>
                                                        <th>Chức danh, Chức vụ, Đơn vị công tác</th>
                                                    </tr>
                                                    @if(sizeof($arrQuaTrinhCongTac) > 0)
                                                        @foreach($arrQuaTrinhCongTac as $item)
                                                            <tr>
                                                                <td>{{$item['curriculum_month_in']}}/{{$item['curriculum_year_in']}} -{{$item['curriculum_month_out']}}/{{$item['curriculum_year_out']}}</td>
                                                                <td>{{$item->curriculum_name}}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12">
                                            <div class="tit">TÓM TẮT QUÁ TRÌNH HOẠT ĐỘNG ĐẢNG, CHÍNH QUYỀN, ĐOÀN THỂ</div>
                                            <table class="table table-bordered" id="tblPoliticActivity">
                                                <tbody>
                                                <tr>
                                                    <th>Chi bộ</th>
                                                    <th>Thời gian</th>
                                                    <th>Chức vụ</th>
                                                    <th>Cấp ủy kiêm</th>
                                                </tr>
                                                @if(sizeof($arrHoatDongDang) > 0)
                                                    @foreach($arrHoatDongDang as $item)
                                                        <td>{{$item->curriculum_chibo}}</td>
                                                        <td>{{$item['curriculum_month_in']}}/{{$item['curriculum_year_in']}} -{{$item['curriculum_month_out']}}/{{$item['curriculum_year_out']}}</td>
                                                        <td>@if(isset($arrChucVuDang[$item['curriculum_chucvu_id']])){{$arrChucVuDang[$item['curriculum_chucvu_id']]}}@endif</td>
                                                        <td>{{$item['curriculum_cap_uykiem']}}</td>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop20">
                                        <div class="block_title">KHEN THƯỞNG</div>
                                        <div id="div_list_khenthuong">
                                            <table class="table table-bordered" id="tblWork">
                                                <tbody>
                                                    <tr>
                                                        <th width="20%">Thành tích</th>
                                                        <th width="15%">Năm đạt</th>
                                                        <th>Quyết định đính kèm</th>
                                                        <th>Thưởng</th>
                                                        <th>Ghi chú</th>
                                                    </tr>
                                                    @if(sizeof($khenthuong) > 0)
                                                        @foreach ($khenthuong as $key => $item)
                                                        <tr>
                                                            <td>@if(isset($arrTypeKhenthuong[$item['bonus_define_id']])){{ $arrTypeKhenthuong[$item['bonus_define_id']] }}@endif</td>
                                                            <td>{{ $item['bonus_year'] }}</td>
                                                            <td>{{$item['bonus_decision']}}</td>
                                                            <td>{{ number_format($item['bonus_number'])}}</td>
                                                            <td>{{$item['bonus_note']}}</td>
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="5"> Chưa có dữ liệu</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{----danh hiệu--}}
                                    <div class="marginTop20">
                                        <div class="block_title">DANH HIỆU</div>
                                        <div id="div_list_danhhieu">
                                            <table class="table table-bordered" id="tblWork">
                                                <tbody>
                                                <tr>
                                                    <th width="20%">Danh hiệu</th>
                                                    <th width="15%">Năm đạt</th>
                                                    <th>Quyết định đính kèm</th>
                                                    <th>Ghi chú</th>
                                                </tr>
                                                @if(sizeof($danhhieu) > 0)
                                                    @foreach ($danhhieu as $key2 => $item2)
                                                        <tr>
                                                            <td>@if(isset($arrTypeDanhhieu[$item2['bonus_define_id']])){{ $arrTypeDanhhieu[$item2['bonus_define_id']] }}@endif</td>
                                                            <td>{{$item2['bonus_year'] }}</td>
                                                            <td>{{$item2['bonus_decision']}}</td>
                                                            <td>{{$item2['bonus_note']}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4"> Chưa có dữ liệu</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{----Kỷ luật--}}
                                    <div class="marginTop20">
                                        <div class="block_title">KỶ LUẬT</div>
                                        <div id="div_list_kyluat">

                                            <table class="table table-bordered" id="tblWork">
                                                <tbody>
                                                <tr>
                                                    <th width="20%">Hình thức</th>
                                                    <th width="15%">Năm bị kỷ luật</th>
                                                    <th>Quyết định đính kèm</th>
                                                    <th>Ghi chú</th>
                                                </tr>
                                                @if(sizeof($kyluat) > 0)
                                                    @foreach ($kyluat as $key3 => $item3)
                                                        <tr>
                                                            <td>@if(isset($arrTypeKyluat[$item3['bonus_define_id']])){{ $arrTypeKyluat[$item3['bonus_define_id']] }}@endif</td>
                                                            <td>{{$item3['bonus_year'] }}</td>
                                                            <td>{{$item3['bonus_decision']}}</td>
                                                            <td>{{$item3['bonus_note']}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4"> Chưa có dữ liệu</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12">
                                            <div class="tit mgt-20">ĐẶC ĐIỂM LỊCH SỬ BẢN THÂN</div>
                                            <div class="form-group">
                                                <label>Khai rõ: Bị bắt, bị tù (từ ngày, tháng, năm nào đến ngày, tháng, năm nào, ở đâu), đã khai báo cho ai, những vấn đề gì</label>
                                                <div class="mgt-15 lh22">
                                                    {{isset($dataQuanHeDacDiemBanThan->curriculum_desc_history1) ? stripcslashes($dataQuanHeDacDiemBanThan->curriculum_desc_history1) : ''}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Bản thân có làm việc trong chế độ cũ (Cơ quan, đơn vị nào, địa điểm, chức danh, chức vụ, thời gian làm việc)</label>
                                                <div class="mgt-15 lh22">
                                                    {{isset($dataQuanHeDacDiemBanThan->curriculum_desc_history2) ? stripcslashes($dataQuanHeDacDiemBanThan->curriculum_desc_history2) : ''}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12">
                                            <div class="tit mgt-20">QUAN HỆ VỚI NƯỚC NGOÀI</div>
                                            <div class="form-group">
                                                <label>Tham gia hoặc có quan hệ với các tổ chức chính trị, kinh tế, xã hội nào ở nước ngoài (làm gì, tổ chức nào, đặt trụ sở ở đâu)</label>
                                                <div class="mgt-15 lh22">
                                                    {{isset($dataQuanHeDacDiemBanThan->curriculum_foreign_relations1) ? stripcslashes($dataQuanHeDacDiemBanThan->curriculum_foreign_relations1) : ''}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Có thân nhân (bố mẹ, vợ chồng, con, anh chị em ruột) ở nước ngoài (làm gì, địa chỉ)...</label>
                                                <div class="mgt-15 lh22">
                                                    {{isset($dataQuanHeDacDiemBanThan->curriculum_foreign_relations2) ? stripcslashes($dataQuanHeDacDiemBanThan->curriculum_foreign_relations2) : ''}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">QUAN HỆ GIA ĐÌNH</div>
                                            <table class="table table-bordered" id="tblWork">
                                                <tbody><tr>
                                                    <th class="text-nowrap">Quan hệ</th>
                                                    <th class="text-nowrap">Họ và tên</th>
                                                    <th class="text-nowrap">Năm sinh</th>
                                                    <th>Quê quán, nghề nghiệp, chức danh, chức vụ, đơn vị công tác, học tập, nơi ở; Thành viên các tổ chức chính trị xã hội</th>
                                                </tr>
                                                @if(sizeof($quanHeGiaDinh) > 0)
                                                    @foreach ($quanHeGiaDinh as $item)
                                                    <tr>
                                                        <td>@if(isset($arrQuanHeGiaDinh[$item['relationship_define_id']])){{ $arrQuanHeGiaDinh[$item['relationship_define_id']] }}@endif</td>
                                                        <td>{{$item['relationship_human_name']}}</td>
                                                        <td>{{$item['relationship_year_birth']}}</td>
                                                        <td>{{$item['relationship_describe']}}</td>
                                                    </tr>
                                                    @endforeach
                                                 @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">LỊCH SỬ LƯƠNG</div>
                                            <div id="div_list_lương">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="thin-border-bottom">
                                                    <tr class="">
                                                        <th width="5%" class="text-center">STT</th>
                                                        <th width="30%">Nghạch /Bậc</th>
                                                        <th width="10%" class="text-center">Hệ số lương</th>
                                                        <th width="20%" class="text-center">Lương thực nhận</th>
                                                        <th width="10%" class="text-center">Tháng năm</th>
                                                    </tr>
                                                    </thead>
                                                    @if(sizeof($luong) > 0)
                                                        <tbody>
                                                        @foreach ($luong as $key => $item)
                                                            <tr>
                                                                <td class="text-center middle">{{ $key+1 }}</td>
                                                                <td>@if(isset($arrNgachBac[$item['salary_civil_servants']])){{ $arrNgachBac[$item['salary_civil_servants']] }}@endif</td>
                                                                <td class="text-center middle"> {{ $item['salary_coefficients'] }}%</td>
                                                                <td class="text-center middle">{{number_format($item['salary_salaries'])}}</td>
                                                                <td class="text-center middle">{{$item['salary_month']}}/{{$item['salary_year']}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    @else
                                                        <tr>
                                                            <td colspan="5">Chưa có dữ liệu</td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">LỊCH SỬ PHỤ CẤP</div>
                                            <div id="div_list_lương">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="thin-border-bottom">
                                                    <tr class="">
                                                        <th width="5%" class="text-center">STT</th>
                                                        <th width="30%">Loại phụ cấp</th>
                                                        <th width="20%">Phụ cấp trả theo hình thức</th>
                                                        <th width="10%" class="text-center">Hệ số, giá trị</th>
                                                        <th width="10%" class="text-center">Tháng năm</th>
                                                    </tr>
                                                    </thead>
                                                    @if(sizeof($phucap) > 0)
                                                        <tbody>
                                                        @foreach ($phucap as $key => $item2)
                                                            <tr>
                                                                <td class="text-center middle">{{ $key+1 }}</td>
                                                                <td>@if(isset($arrOptionPhuCap[$item2['allowance_type']])){{ $arrOptionPhuCap[$item2['allowance_type']] }}@endif</td>
                                                                <td>@if(isset($arrMethodPhuCap[$item2['allowance_method_type']])){{ $arrMethodPhuCap[$item2['allowance_method_type']] }}@endif</td>
                                                                <td class="text-center middle"> {{ $item2['allowance_method_value'] }}</td>
                                                                <td class="text-center middle">{{$item2['allowance_month_start']}}/{{$item2['allowance_year_start']}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    @else
                                                        <tr>
                                                            <td colspan="5">Chưa có dữ liệu</td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">DANH SÁCH HỢP ĐỒNG LAO ĐỘNG ĐÃ KÝ</div>
                                            <table class="table table-bordered" id="tblContact">
                                                <tbody><tr>
                                                    <th>STT</th>
                                                    <th>Loại hợp đồng</th>
                                                    <th>Chế độ thanh toán (Trả lương)</th>
                                                    <th>Mã hợp đồng</th>
                                                    <th>Mức lương</th>
                                                    <th>Ngày ký</th>
                                                    <th>Ngày hiệu lực</th>
                                                    <th>Thỏa thuận khác</th>
                                                </tr>
                                                @if(sizeof($contractsPerson) > 0)
                                                    @foreach($contractsPerson as $k=>$item)
                                                    <tr>
                                                        <td>{{$k+1}}</td>
                                                        <td>@if(isset($arrLoaihopdong[$item['contracts_type_define_id']])){{ $arrLoaihopdong[$item['contracts_type_define_id']] }} @endif</td>
                                                        <td>@if(isset($arrChedothanhtoan[$item['contracts_payment_define_id']])){{ $arrChedothanhtoan[$item['contracts_payment_define_id']] }} @endif</td>
                                                        <td>{{$item['contracts_code']}}</td>
                                                        <td>{{ number_format($item['contracts_money'])}}</td>
                                                        <td>{{date('d-m-Y',$item['contracts_sign_day'])}}</td>
                                                        <td>{{date('d-m-Y',$item['contracts_effective_date'])}}</td>
                                                        <td>{{$item['contracts_describe']}}</td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop