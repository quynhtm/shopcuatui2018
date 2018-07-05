<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
<table class="table table-bordered table-hover">
    <thead class="thin-border-bottom">
    <tr class="">
        <th width="15%">Thời gian</th>
        <th width="75%">Chức danh, chức vụ, công tác</th>
        <th width="10%" class="text-center">Thao tác</th>
    </tr>
    </thead>
    @if(sizeof($dataList) > 0)
        <tbody>
        @foreach ($dataList as $key_ct=> $item_ct)
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