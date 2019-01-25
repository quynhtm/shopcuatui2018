<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('shop.order')}}"> Danh sách đơn hàng</a></li>
            <li class="active">Thông tin đơn hàng</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                {{Form::open(array('method' => 'POST','role'=>'form','files' => true))}}
                        <!--thông tin khách hàng-->
                <div style="float: left; width: 45%;">
                    <div class="panel panel-info" >
                        <div class="panel-footer text-left">
                            <h3>Thông tin khách hàng</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Tên khách hàng:</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control input-sm" id="order_customer_name" name="order_customer_name" placeholder="Tên khách hàng"
                                           @if(isset($data['order_customer_name']))value="{{$data['order_customer_name']}}"@endif>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Điện thoại:</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control input-sm" id="order_customer_phone" name="order_customer_phone" placeholder="Tên khách hàng"
                                           @if(isset($data['order_customer_phone']))value="{{$data['order_customer_phone']}}"@endif>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Email:</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control input-sm" id="order_customer_email" name="order_customer_email" placeholder="Email"
                                           @if(isset($data['order_customer_email']))value="{{$data['order_customer_email']}}"@endif>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Địa chỉ:</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control input-sm" id="order_customer_address" name="order_customer_address" placeholder="Địa chỉ"
                                           @if(isset($data['order_customer_address']))value="{{$data['order_customer_address']}}"@endif>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Ghi chú:</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control input-sm" id="order_customer_note" name="order_customer_note" placeholder="Ghi chú"
                                           @if(isset($data['order_customer_note']))value="{{$data['order_customer_note']}}"@endif>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--thông tin khách hàng-->
                <div style="float: left; width: 45%;margin-left: 5%">
                    <div class="panel panel-info">
                        <div class="panel-footer text-left">
                            <h3>Thông tin đơn hàng</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Đơn hàng từ:</label>
                                <div class="col-lg-8">
                                    <select name="order_type" id="order_type" class="form-control input-sm">
                                        {{--{{$optionTypeOder}}--}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Trạng thái ĐH:</label>
                                <div class="col-lg-8">
                                    <select name="order_status" id="order_status" class="form-control input-sm">
                                        {{--{{$optionStatusOder}}--}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Tình trạng hàng:</label>
                                <div class="col-lg-8">
                                    <select name="order_is_cod" id="order_is_cod" class="form-control input-sm">
{{--                                        {{$optionCodOder}}--}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Ghi chú đơn hàng</label>
                                <div class="col-lg-8">
                                    <textarea class="form-control input-sm" id="order_note" name="order_note" rows="5">{{$data['order_note']}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-lg-12" style="display: none">
                                <label class="col-lg-4 text-right">NV giao hàng:</label>
                                <div class="col-lg-8">
                                    <select name="order_user_shipper_id" id="order_user_shipper_id" class="form-control input-sm">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--thông tin sản phẩm trong đơn hàng-->
                <div class="clear"></div>
                <div class="panel panel-info">
                    <div class="panel-footer text-left">
                        <h3>Thông tin sản phẩm</h3>
                    </div>
                    <div class="form-group marginTop20 marginBottom20">
                        <label class="col-lg-3 text-right">Danh sách ID sản phẩm</label>
                        <div class="col-lg-5"><input type="text" class="form-control input-sm" id="sys_order_product_id" name="order_product_id" placeholder="Mã sản phẩm: 1,2,3" @if(isset($data->order_product_id))value="{{$data->order_product_id}}"@endif></div>
                        @if($id == 0)
                            <div class="col-lg-4">
                                <a class="btn btn-success btn-sm" href="javascript:void(0);" onclick="Order.getInforProduct();">
                                    <i class="fa fa-search"></i> Tìm kiếm
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="clear"></div>
                    <div class="panel-body">
                        <div id="sys_show_infor_cart">
                            @if(isset($data->orderitem) && !empty($data->orderitem))
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
                                    @foreach($data->orderitem as $k=> $product)
                                        <tr>
                                            <td class="text-center text-middle">{{$k+1}}</td>
                                            <td class="text-center text-middle">
                                                <a href="{{URL::route('admin.productEdit',array('id' => $product->product_id))}}" target="_blank" title="Sửa sản phẩm">{{$product->product_id}}</a>
                                            </td>
                                            <td>
                                                <a href="{{FunctionLib::buildLinkDetailProduct($product->product_id, $product->product_name, $product->product_category_name)}}" target="_blank" title="Chi tiết sản phẩm">
                                                    {{ $product->product_name }}
                                                </a>
                                            </td>
                                            <td>{{$product->category_name}}</td>
                                            <td class="text-right text-middle"><b>{{FunctionLib::numberFormat($product->product_price_sell)}} đ</b></td>
                                            <td class="text-center text-middle">
                                                <div>
                                                    <input type="text" class="form-control text-center input-sm number_buy_product" id="sys_number_buy_{{$product->product_id}}" name="number_buy_{{$product->product_id}}" placeholder="Mã sản phẩm: 1,2,3" value="{{$product->number_buy}}" onblur="Order.changeNumberBuy({{$product->product_id}})">
                                                </div>
                                            </td>
                                            <td class="text-right text-middle"><b class="red" id="sys_total_product_price_sell_{{$product->product_id}}">{{FunctionLib::numberFormat($product->product_price_sell*$product->number_buy)}} đ</b></td>

                                            <input type="hidden" id="sys_product_price_sell_{{$product->product_id}}" name="product_price_sell_{{$product->product_id}}" class="form-control" value="{{$product->product_price_sell}}" >
                                            <input type="hidden" id="total_product_price_sell_hiden_{{$product->product_id}}" name="total_product_price_sell_hiden[]" class="total_product_price_sell_hiden" value="{{$product->product_price_sell*$product->number_buy}}" >
                                            <?php
                                            $total_product = $total_product + $product->number_buy;
                                            $total_money = $total_money + ($product->product_price_sell*$product->number_buy);
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
                                            <div><input type="text" class="formatMoney text-right form-control" data-v-max="999999999999999" data-v-min="0" data-a-sep="." data-a-dec="," data-a-sign=" đ" data-p-sign="s" id="sys_order_money_ship" name="order_money_ship" value="@if(isset($data['order_money_ship'])){{$data['order_money_ship']}}@endif" onblur="Order.changeNumberMoneyShip();"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Tổng tiền thanh toán:</b></td>
                                        <td colspan="2" class="text-right"><b class="red" style="font-size: 18px" id="sys_total_order_money">
                                                @if(isset($data->order_total_money))
                                                    {{FunctionLib::numberFormat($data->order_total_money)}} đ
                                                @else
                                                    {{FunctionLib::numberFormat($total_money)}} đ
                                                @endif
                                            </b></td>
                                    </tr>
                                </table>
                            @endif
                        </div>
                        <div class="form-group col-sm-12 text-right">
                            <a class="btn btn-warning" href="javascript:void(0);"><i class="fa fa-reply"></i> Trở lại</a>
                            <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Cập nhật đơn hàng</button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery('.formatMoney').autoNumeric('init');

</script>
