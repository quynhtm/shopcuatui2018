@if(isset($inforProduct) && !empty($inforProduct))
    <table class="table table-bordered table-hover">
        <tr style="background-color: #c0a16b">
            <th width="2%" class="text-center text-middle">STT</th>
            <th width="5%" class="text-center text-middle">ID SP</th>
            <th width="35%">Sản phẩm</th>
            <th width="20%">Danh mục</th>
            <th width="10%" class="text-right">Giá bán</th>
            <th width="5%" class="text-center text-middle">SL</th>
            <th width="10%" class="text-right">Tổng tiền</th>
        </tr>
        <?php $total_product = 0; $total_money = 0;?>
        @foreach($inforProduct as $k=> $product)
            <tr>
                <td class="text-center text-middle">{{$k+1}}</td>
                <td class="text-center text-middle">{{$product->product_id}}</td>
                <td>{{$product->product_name}}</td>
                <td>{{$product->category_name}}</td>
                <td class="text-right text-middle"><b>{{FunctionLib::numberFormat($product->product_price_sell)}} đ</b></td>
                <td class="text-center text-middle">
                    <div>
                        <input type="text" class="form-control input-sm text-center number_buy_product" id="sys_number_buy_{{$product->product_id}}" name="number_buy_{{$product->product_id}}" placeholder="Mã sản phẩm: 1,2,3" value="{{$product->number_buy}}" onblur="Order.changeNumberBuy({{$product->product_id}})">
                    </div>
                </td>
                <td class="text-right text-middle"><b class="red" id="sys_total_product_price_sell_{{$product->product_id}}">{{FunctionLib::numberFormat($product->product_price_sell)}} đ</b></td>

                <input type="hidden" id="sys_product_price_sell_{{$product->product_id}}" name="product_price_sell_{{$product->product_id}}" class="form-control" value="{{$product->product_price_sell}}" >
                <input type="hidden" id="total_product_price_sell_hiden_{{$product->product_id}}" name="total_product_price_sell_hiden[]" class="total_product_price_sell_hiden" value="{{$product->product_price_sell}}" >
                <?php
                $total_product = $total_product + $product->number_buy;
                $total_money = $total_money + ($product->product_price_sell);
                ?>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" class="text-right"><b>Tổng số lượng hàng:</b></td>
            <td colspan="2" class="text-right"><b id="sys_total_number_buy_product">{{FunctionLib::numberFormat($total_product)}}</b></td>
        </tr>
        <tr>
            <td colspan="5" class="text-right"><b>Tổng tiền:</b></td>
            <td colspan="2" class="text-right"><b class="red" id="sys_total_money">{{FunctionLib::numberFormat($total_money)}} đ</b></td>
        </tr>
        <tr>
            <td colspan="5" class="text-right"><b>Tiền ship:</b></td>
            <td colspan="2" class="text-right">
                <div><input type="text" class="formatMoney text-right form-control" data-v-max="999999999999999" data-v-min="0" data-a-sep="." data-a-dec="," data-a-sign=" đ" data-p-sign="s" id="sys_order_money_ship" name="order_money_ship" value="" onblur="Order.changeNumberMoneyShip();"></div>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="text-right"><b>Tổng tiền thanh toán:</b></td>
            <td colspan="2" class="text-right"><b class="red" style="font-size: 18px" id="sys_total_order_money">{{FunctionLib::numberFormat($total_money+15000)}} đ</b></td>
        </tr>
    </table>
@endif

<script type="text/javascript">
    jQuery('.formatMoney').autoNumeric('init');

</script>