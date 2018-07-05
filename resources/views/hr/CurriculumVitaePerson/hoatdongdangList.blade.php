<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
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
    @if(sizeof($dataList) > 0)
        <tbody>
        @foreach ($dataList as $key_hdd => $item_hdd)
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