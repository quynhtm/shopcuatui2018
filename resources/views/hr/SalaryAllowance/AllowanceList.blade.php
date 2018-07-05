<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
<table class="table table-bordered table-hover">
    <thead class="thin-border-bottom">
    <tr class="">
        <th width="5%" class="text-center">STT</th>
        <th width="30%">Loại phụ cấp</th>
        <th width="20%">Phụ cấp trả theo hình thức</th>
        <th width="10%" class="text-center">Hệ số, giá trị</th>
        <th width="10%" class="text-center">Tháng năm</th>
        <th width="10%" class="text-center">Thao tác</th>
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
                <td class="text-center middle">
                    @if($is_root== 1 || $salaryAllowanceFull== 1 || $salaryAllowanceCreate == 1)
                        <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item2['allowance_person_id'])}}','{{FunctionLib::inputId($item2['allowance_id'])}}','salaryAllowance/editAllowance',0)"title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                    @endif
                    @if($is_root== 1 || $salaryAllowanceFull== 1 || $salaryAllowanceDelete == 1)
                        <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item2['allowance_person_id'])}}','{{FunctionLib::inputId($item2['allowance_id'])}}','salaryAllowance/deleteAllowance','div_list_phucap',0)"><i class="fa fa-trash fa-2x"></i></a>
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