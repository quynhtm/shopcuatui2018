<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
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
    @if(sizeof($dataList) > 0)
        <tbody>
        @foreach ($dataList as $key => $item_vbcc)
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