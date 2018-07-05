<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
<table class="table table-bordered table-hover">
    <thead class="thin-border-bottom">
    <tr class="">
        <th width="5%" class="text-center">STT</th>
        <th width="30%">Nghạch /Bậc</th>
        <th width="10%" class="text-center">Hệ số lương</th>
        <th width="20%" class="text-center">Lương cơ sở</th>
        <th width="10%" class="text-center">Tháng năm</th>
        <th width="10%" class="text-center">Thao tác</th>
    </tr>
    </thead>
    @if(sizeof($lương) > 0)
        <tbody>
        @foreach ($lương as $key => $item)
            <tr>
                <td class="text-center middle">{{ $key+1 }}</td>
                <td>@if(isset($arrNgachBac[$item['salary_civil_servants']])){{ $arrNgachBac[$item['salary_civil_servants']] }}@endif</td>
                <td class="text-center middle"> {{ $item['salary_coefficients'] }}</td>
                <td class="text-center middle">{{number_format($item['salary_salaries'])}}</td>
                <td class="text-center middle">{{$item['salary_month']}}/{{$item['salary_year']}}</td>
                <td class="text-center middle">
                    @if($is_root== 1 || $salaryAllowanceFull== 1 || $salaryAllowanceCreate == 1)
                        <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item['salary_person_id'])}}','{{FunctionLib::inputId($item['salary_id'])}}','salaryAllowance/editSalary',0)"title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                    @endif
                    @if($is_root== 1 || $salaryAllowanceFull== 1 || $salaryAllowanceDelete == 1)
                        <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item['salary_person_id'])}}','{{FunctionLib::inputId($item['salary_id'])}}','salaryAllowance/postSalary','div_list_lương',0)"><i class="fa fa-trash fa-2x"></i></a>
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