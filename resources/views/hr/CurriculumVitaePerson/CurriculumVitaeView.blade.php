<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
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
                <li class="active">Lý lịch 2C</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    {{ csrf_field() }}
                    @if(isset($infoPerson))
                        <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
                        <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
                        <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
                    @endif
                    <!---ĐÀO TẠO BỒI DƯỠNG VỀ CHUYÊN MÔN NGHIỆP VỤ, LÝ LUÂN CHÍNH TRỊ, NGOẠI NGỮ-->
                    <div class="marginTop20">
                        <div class="block_title">ĐÀO TẠO BỒI DƯỠNG VỀ CHUYÊN MÔN NGHIỆP VỤ, LÝ LUÂN CHÍNH TRỊ, NGOẠI NGỮ</div>
                        <div id="div_khoa_dao_tao">
                            <div class="span clearfix"> <b>Các khóa đào tạo có chuyên nghành trong hệ thống</b></div>
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="20%">Tên trường địa điểm</th>
                                    <th width="30%">Nghành, lớp học</th>
                                    <th width="10%" class="text-center">Thời gian học</th>
                                    <th width="15%">Hình thức học</th>
                                    <th width="15%">Văn bằng, chứng chỉ, trình độ</th>
                                    <th width="10%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                @if(sizeof($quatrinhdaotao) > 0)
                                    <tbody>
                                    @foreach ($quatrinhdaotao as $key => $item_qtdt)
                                        <tr>
                                            <td>{{$item_qtdt['curriculum_address_train']}}</td>
                                            <td>@if(isset($arrChuyenNghanhDaoTao[$item_qtdt['curriculum_training_id']])){{ $arrChuyenNghanhDaoTao[$item_qtdt['curriculum_training_id']] }}@endif</td>
                                            <td class="text-center middle">
                                                {{$item_qtdt['curriculum_month_in'].'/'.$item_qtdt['curriculum_year_in'].' - '.$item_qtdt['curriculum_month_out'].'/'.$item_qtdt['curriculum_year_out']}}
                                            </td>
                                            <td>@if(isset($arrHinhThucHoc[$item_qtdt['curriculum_formalities_id']])){{ $arrHinhThucHoc[$item_qtdt['curriculum_formalities_id']] }}@endif</td>
                                            <td>@if(isset($arrVanBangChungChi[$item_qtdt['curriculum_certificate_id']])){{ $arrVanBangChungChi[$item_qtdt['curriculum_certificate_id']] }}@endif</td>
                                            <td class="text-center middle">
                                                @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personCurriculumVitaeCreate == 1)
                                                    <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item_qtdt['curriculum_person_id'])}}','{{FunctionLib::inputId($item_qtdt['curriculum_id'])}}','curriculumVitaePerson/editStudy',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_DAO_TAO}})"title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                                @endif
                                                @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personContracts_delete == 1)
                                                    <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item_qtdt['curriculum_person_id'])}}','{{FunctionLib::inputId($item_qtdt['curriculum_id'])}}','curriculumVitaePerson/deleteStudy','div_khoa_dao_tao',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_DAO_TAO}})"><i class="fa fa-trash fa-2x"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td colspan="7"> Chưa có dữ liệu</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <a class="btn btn-success updowTop10" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','curriculumVitaePerson/editStudy',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_DAO_TAO}})"><i class="fa fa-reply"></i> Thêm khóa đào tạo dài hạn</a>

                        <div id="div_dao_tao_khac" class="marginTop20">
                            <div class="span clearfix"> <b>Các khóa đào tạo có chuyên nghành khác</b></div>
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="20%">Tên trường địa điểm</th>
                                    <th width="15%">Nghành, lớp học</th>
                                    <th width="10%" class="text-center">Thời gian học</th>
                                    <th width="15%">Tổ chức cấp</th>
                                    <th width="30%">Văn bằng, chứng chỉ, trình độ</th>
                                    <th width="10%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                @if(sizeof($vanbangchungchikhac) > 0)
                                    <tbody>
                                    @foreach ($vanbangchungchikhac as $key => $item_vbcc)
                                        <tr>
                                            <td>{{$item_vbcc['curriculum_address_train']}}</td>
                                            <td>{{$item_vbcc['curriculum_training_name']}}</td>
                                            <td class="text-center middle">
                                                {{$item_vbcc['curriculum_month_in'].'/'.$item_vbcc['curriculum_year_in'].' - '.$item_vbcc['curriculum_month_out'].'/'.$item_vbcc['curriculum_year_out']}}
                                            </td>
                                            <td>{{$item_vbcc['curriculum_formalities_name']}}</td>
                                            <td>{{$item_vbcc['curriculum_certificate_name']}}</td>
                                            <td class="text-center middle">
                                                @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personCurriculumVitaeCreate == 1)
                                                    <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item_vbcc['curriculum_person_id'])}}','{{FunctionLib::inputId($item_vbcc['curriculum_id'])}}','curriculumVitaePerson/editStudy',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_CHUNG_CHI_KHAC}})"title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                                @endif
                                                @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personContracts_delete == 1)
                                                    <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item_vbcc['curriculum_person_id'])}}','{{FunctionLib::inputId($item_vbcc['curriculum_id'])}}','curriculumVitaePerson/deleteStudy','div_dao_tao_khac',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_CHUNG_CHI_KHAC}})"><i class="fa fa-trash fa-2x"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td colspan="7"> Chưa có dữ liệu</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <a class="btn btn-success updowTop10" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','curriculumVitaePerson/editStudy',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_CHUNG_CHI_KHAC}})"><i class="fa fa-reply"></i> Thêm khóa đào tạo khác</a>
                    </div>

                    {{----TÓM TẮT QUÁ TRÌNH CÔNG TÁC--}}
                    <div class="marginTop20">
                        <div class="block_title">TÓM TẮT QUÁ TRÌNH CÔNG TÁC</div>
                        <div id="div_qua_trinh_cong_tac">
                            <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="15%">Thời gian</th>
                                <th width="75%">Chức danh, chức vụ, công tác</th>
                                <th width="10%" class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            @if(sizeof($quatrinhcongtac) > 0)
                                <tbody>
                                @foreach ($quatrinhcongtac as $key_ct=> $item_ct)
                                    <tr>
                                        <td>{{$item_ct['curriculum_month_in'].'/'.$item_ct['curriculum_year_in'].' - '.$item_ct['curriculum_month_out'].'/'.$item_ct['curriculum_year_out']}}</td>
                                        <td>{{$item_ct['curriculum_name']}}</td>
                                        <td class="text-center middle">
                                            @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personCurriculumVitaeCreate == 1)
                                                <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item_ct['curriculum_person_id'])}}','{{FunctionLib::inputId($item_ct['curriculum_id'])}}','curriculumVitaePerson/editStudy',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_CONG_TAC}})"title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personContracts_delete == 1)
                                                <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item_ct['curriculum_person_id'])}}','{{FunctionLib::inputId($item_ct['curriculum_id'])}}','curriculumVitaePerson/deleteStudy','div_qua_trinh_cong_tac',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_CONG_TAC}})"><i class="fa fa-trash fa-2x"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <td colspan="7"> Chưa có dữ liệu</td>
                                </tr>
                            @endif
                            </table>
                        </div>
                        <a class="btn btn-success updowTop10" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','curriculumVitaePerson/editStudy',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_CONG_TAC}})"><i class="fa fa-reply"></i> Thêm quá trình công tác</a>
                    </div>

                    {{----TÓM TẮT QUÁ TRÌNH HOẠT ĐỘNG ĐẢNG, CHÍNH QUYỀN, ĐOÀN THỂ--}}
                    <div class="marginTop20">
                        <div class="block_title">TÓM TẮT QUÁ TRÌNH HOẠT ĐỘNG ĐẢNG, CHÍNH QUYỀN, ĐOÀN THỂ</div>
                        <div id="div_hoat_dong_dang">
                            <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="30%">Chi bộ</th>
                                <th width="10%" class="text-center">Thời gian</th>
                                <th width="20%">Chức vụ</th>
                                <th width="30%">Cấp ủy kiêm</th>
                                <th width="10%" class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            @if(sizeof($hoatdongdang) > 0)
                                <tbody>
                                @foreach ($hoatdongdang as $key_hdd => $item_hdd)
                                    <tr>
                                        <td>{{ $item_hdd['curriculum_chibo'] }}</td>
                                        <td class="text-center middle">
                                            {{$item_hdd['curriculum_month_in'].'/'.$item_hdd['curriculum_year_in'].' - '.$item_hdd['curriculum_month_out'].'/'.$item_hdd['curriculum_year_out']}}
                                        </td>
                                        <td>@if(isset($arrChucVuDang[$item_hdd['curriculum_chucvu_id']])){{ $arrChucVuDang[$item_hdd['curriculum_chucvu_id']] }}@endif</td>
                                        <td>{{$item_hdd['curriculum_cap_uykiem']}}</td>
                                        <td class="text-center middle">
                                            @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personCurriculumVitaeCreate == 1)
                                                <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item_hdd['curriculum_person_id'])}}','{{FunctionLib::inputId($item_hdd['curriculum_id'])}}','curriculumVitaePerson/editStudy',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_HOAT_DONG_DANG}})"title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personContracts_delete == 1)
                                                <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item_hdd['curriculum_person_id'])}}','{{FunctionLib::inputId($item_hdd['curriculum_id'])}}','curriculumVitaePerson/deleteStudy','div_hoat_dong_dang',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_HOAT_DONG_DANG}})"><i class="fa fa-trash fa-2x"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <td colspan="7"> Chưa có dữ liệu</td>
                                </tr>
                            @endif
                            </table>
                        </div>
                        <a class="btn btn-success updowTop10" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','curriculumVitaePerson/editStudy',{{\App\Library\AdminFunction\Define::CURRICULUMVITAE_HOAT_DONG_DANG}})"><i class="fa fa-reply"></i> Thêm quá trình hoạt động Đảng, Đoàn thể</a>
                    </div>

                    {{----ĐẶC ĐIỂM LỊCH SỬ BẢN THÂN--}}
                    <div class="marginTop20">
                        <div class="block_title">ĐẶC ĐIỂM LỊCH SỬ BẢN THÂN</div>
                        <div id="div_lich_su_ban_than">
                            <div class="form-group">
                                <label for="curriculum_desc_history1" class="control-label"><i>Khai rõ: Bị bắt, bị tù (từ ngày, tháng, năm nào đến ngày, tháng, năm nào, ở đâu), đã khai báo cho ai, những vấn đề gì</i></label>
                                <textarea class="form-control input-sm" dataPerson="{{FunctionLib::inputId($person_id)}}" id="curriculum_desc_history1" name="curriculum_desc_history1" rows="5">{{isset($dataQuanHeDacDiemBanThan->curriculum_desc_history1) ? stripcslashes($dataQuanHeDacDiemBanThan->curriculum_desc_history1) : ''}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="curriculum_desc_history2" class="control-label"><i>Bản thân có làm việc trong chế độ cũ (Cơ quan, đơn vị nào, địa điểm, chức danh, chức vụ, thời gian làm việc)</i></label>
                                <textarea class="form-control input-sm" dataPerson="{{FunctionLib::inputId($person_id)}}" id="curriculum_desc_history2" name="curriculum_desc_history2" rows="5">{{isset($dataQuanHeDacDiemBanThan->curriculum_desc_history2) ? stripcslashes($dataQuanHeDacDiemBanThan->curriculum_desc_history2) : ''}}</textarea>
                            </div>
                        </div>
                    </div>

                    {{----QUAN HỆ VỚI NƯỚC NGOÀI--}}
                    <div class="marginTop20">
                        <div class="block_title">QUAN HỆ VỚI NƯỚC NGOÀI</div>
                        <div id="div_quan_he_nuoc_ngoai">
                            <div class="form-group">
                                <label for="curriculum_foreign_relations1" class="control-label"><i>Tham gia hoặc có quan hệ với các tổ chức chính trị, kinh tế, xã hội nào ở nước ngoài (làm gì, tổ chức nào, đặt trụ sở ở đâu)</i></label>
                                <textarea class="form-control input-sm" dataPerson="{{FunctionLib::inputId($person_id)}}" id="curriculum_foreign_relations1" name="curriculum_foreign_relations1" rows="5">{{isset($dataQuanHeDacDiemBanThan->curriculum_foreign_relations1) ? stripcslashes($dataQuanHeDacDiemBanThan->curriculum_foreign_relations1) : ''}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="curriculum_foreign_relations2" class="control-label"><i>Có thân nhân (bố mẹ, vợ chồng, con, anh chị em ruột) ở nước ngoài (làm gì, địa chỉ)...</i></label>
                                <textarea class="form-control input-sm" dataPerson="{{FunctionLib::inputId($person_id)}}" id="curriculum_foreign_relations2" name="curriculum_foreign_relations2" rows="5">{{isset($dataQuanHeDacDiemBanThan->curriculum_foreign_relations2) ? stripcslashes($dataQuanHeDacDiemBanThan->curriculum_foreign_relations2) : ''}}</textarea>
                            </div>
                        </div>
                    </div>

                    {{----QUAN HỆ GIA ĐÌNH--}}
                    <div class="marginTop20">
                        <div class="block_title">QUAN HỆ GIA ĐÌNH</div>
                        <div id="div_quan_he_gia_dinh">
                            <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="10%">Quan hệ</th>
                                <th width="20%">Họ và tên</th>
                                <th width="8%" class="text-center">Năm sinh</th>
                                <th width="52%">Quê quán, nghề nghiệp, chức danh, chức vụ, đơn vị công tác, học tập, nơi ở (trong, ngoài nước); Thành viên các tổ chức chính trị xã hội</th>
                                <th width="10%" class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            @if(sizeof($quanhegiadinh) > 0)
                                <tbody>
                                @foreach ($quanhegiadinh as $k_qhgd => $item_qhgd)
                                    <tr>
                                        <td>@if(isset($arrQuanHeGiaDinh[$item_qhgd['relationship_define_id']])){{ $arrQuanHeGiaDinh[$item_qhgd['relationship_define_id']] }}@endif</td>
                                        <td>{{ $item_qhgd['relationship_human_name'] }}</td>
                                        <td class="text-center middle">{{$item_qhgd['relationship_year_birth']}}</td>
                                        <td>{{$item_qhgd['relationship_describe']}}</td>
                                        <td class="text-center middle">
                                            @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personCurriculumVitaeCreate == 1)
                                                <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item_qhgd['relationship_person_id'])}}','{{FunctionLib::inputId($item_qhgd['relationship_id'])}}','curriculumVitaePerson/editFamily',0)"title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personContracts_delete == 1)
                                                <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item_qhgd['relationship_person_id'])}}','{{FunctionLib::inputId($item_qhgd['relationship_id'])}}','curriculumVitaePerson/deleteFamily','div_quan_he_gia_dinh',0)"><i class="fa fa-trash fa-2x"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <td colspan="7"> Chưa có dữ liệu</td>
                                </tr>
                            @endif
                        </table>
                        </div>
                        <a class="btn btn-success updowTop10" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','curriculumVitaePerson/editFamily',0)"><i class="fa fa-reply"></i> Thêm mới quan hệ gia đình</a>
                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
@stop