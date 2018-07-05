<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
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
    @if(sizeof($dataList) > 0)
        <tbody>
        @foreach ($dataList as $key => $item_qtdt)
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