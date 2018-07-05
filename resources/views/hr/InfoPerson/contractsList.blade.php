<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
<div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> hợp đồng @endif </div>
<br>
<table class="table table-bordered table-hover">
    <thead class="thin-border-bottom">
    <tr class="">
        <th width="3%" class="text-center">STT</th>
        <th width="8%">Loại hợp đồng</th>
        <th width="20%">Chế độ thanh toán(trả lương)</th>
        <th width="10%" class="text-center">Mã hợp đồng</th>
        <th width="10%" class="text-center">Mức lương</th>
        <th width="8%" class="text-center">Ngày ký</th>
        <th width="8%" class="text-center">Ngày hiệu lực</th>
        <th width="8%" class="text-center">Ngày hết HĐ</th>
        <th width="15%" class="text-center">Thỏa thuận khác</th>
        <th width="8%" class="text-center">Thao tác</th>
    </tr>
    </thead>
    @if(sizeof($contracts) > 0)
        <tbody>
        @foreach ($contracts as $key => $item)
            <tr>
                <td class="text-center middle">{{ $key+1 }}</td>
                <td class="text-center middle">@if(isset($arrLoaihopdong[$item['contracts_type_define_id']])){{ $arrLoaihopdong[$item['contracts_type_define_id']] }} @endif</td>
                <td class="text-center middle">@if(isset($arrChedothanhtoan[$item['contracts_payment_define_id']])){{ $arrChedothanhtoan[$item['contracts_payment_define_id']] }} @endif</td>
                <td class="text-center middle">{{$item['contracts_code']}}</td>
                <td class="text-center middle">{{ number_format($item['contracts_money'])}}</td>
                <td class="text-center middle">{{date('d-m-Y',$item['contracts_sign_day'])}}</td>
                <td class="text-center middle">{{date('d-m-Y',$item['contracts_effective_date'])}}</td>
                <td class="text-center middle">{{date('d-m-Y',$item['contracts_dealine_date'])}}</td>
                <td class="text-center middle">{{$item['contracts_describe']}}</td>
                <td class="text-center middle">
                    @if($is_root== 1 || $personContracts_full== 1 || $personContracts_create == 1)
                        <a href="#" onclick="HR.getInfoContractsPerson('{{FunctionLib::inputId($item['contracts_person_id'])}}','{{FunctionLib::inputId($item['contracts_id'])}}')"
                           title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                    @endif
                    @if($is_root== 1 || $personContracts_full== 1 || $personContracts_delete == 1)
                            <a class="deleteItem" title="Xóa" onclick="HR.deleteComtracts('{{FunctionLib::inputId($item['contracts_person_id'])}}','{{FunctionLib::inputId($item['contracts_id'])}}')"><i class="fa fa-trash fa-2x"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    @else
        <tr>
            <td colspan="8"> Chưa có dữ liệu</td>
        </tr>
    @endif
</table>