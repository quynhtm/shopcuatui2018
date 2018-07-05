<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
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