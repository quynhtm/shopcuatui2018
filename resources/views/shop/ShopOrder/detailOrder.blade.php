<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.managerOrderView')}}"> Danh sách đơn hàng</a></li>
            <li class="active">Thông tin chi tiết đơn hàng</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!--thông tin khách hàng-->
                <div style="float: left; width: 45%; ">
                    <div class="panel panel-info" >
                        <div class="panel-footer text-left">
                            <h3>Thông tin khách hàng</h3>
                        </div>
                        <div class="panel-body">
                            @if(isset($data->order_customer_name) && $data->order_customer_name != '')
                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Tên khách hàng:</label>
                                <label class="col-lg-8"><b>{{$data->order_customer_name}}</b></label>
                            </div>
                            @endif
                            @if(isset($data->order_customer_phone) && $data->order_customer_phone != '')
                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Điện thoại:</label>
                                <label class="col-lg-8"><b>{{$data->order_customer_phone}}</b></label>
                            </div>
                            @endif
                            @if(isset($data->order_customer_email) && $data->order_customer_email != '')
                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Email:</label>
                                <label class="col-lg-8">{{$data->order_customer_email}}</label>
                            </div>
                            @endif
                            @if(isset($data->order_customer_address) && $data->order_customer_address != '')
                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Địa chỉ:</label>
                                <label class="col-lg-8">{{$data->order_customer_address}}</label>
                            </div>
                            @endif
                            @if(isset($data->order_customer_note) && $data->order_customer_note != '')
                            <div class="form-group col-lg-12">
                                <label class="col-lg-4 text-right">Ghi chú:</label>
                                <label class="col-lg-8 red">{{$data->order_customer_note}}</label>
                            </div>
                            @endif
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
                            @if(isset($arrTypeOder[$data->order_type]))
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-4 text-right">Đơn hàng từ:</label>
                                    <label class="col-lg-8">{{$arrTypeOder[$data->order_type]}}</label>
                                </div>
                            @endif

                            @if(isset($arrStatusOder[$data->order_status]))
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-4 text-right">Trạng thái đơn hàng:</label>
                                    <label class="col-lg-8">{{$arrStatusOder[$data->order_status]}}</label>
                                </div>
                            @endif

                            @if(isset($arrCodOder[$data->order_is_cod]))
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-4 text-right">Tình trạng hàng:</label>
                                    <label class="col-lg-8">{{$arrCodOder[$data->order_is_cod]}}</label>
                                </div>
                            @endif

                            @if(isset($data->order_note) && $data->order_note != '')
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-4 text-right">Ghi chú đơn hàng</label>
                                    <div class="col-lg-8">
                                        <textarea class="form-control input-sm" id="order_note" name="order_note" rows="5">{{$data->order_note}}</textarea>
                                    </div>
                                </div>
                            @endif

                            @if(isset($data->order_user_shipper_name) && $data->order_user_shipper_name != '')
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-4 text-right">NV giao hàng:</label>
                                    <label class="col-lg-8 green">{{$data->order_user_shipper_name}}</label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!--thông tin sản phẩm trong đơn hàng-->
                <div class="clear"></div>
                <div class="panel panel-info">
                    <div class="panel-footer text-left">
                        <h3>Thông tin sản phẩm</h3>
                    </div>
                    <div class="panel-body">
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
                            @foreach($data->orderitem as $k=>$order_itm)
                                <tr>
                                    <td class="text-center text-middle">{{$k+1}}</td>
                                    <td class="text-center text-middle">
                                        <a href="{{URL::route('admin.productEdit',array('id' => $order_itm->product_id))}}" target="_blank" title="Sửa sản phẩm">{{$order_itm->product_id}}</a>
                                    </td>
                                    <td>
                                        <a href="{{FunctionLib::buildLinkDetailProduct($order_itm->product_id, $order_itm->product_name, $order_itm->product_category_name)}}" target="_blank" title="Chi tiết sản phẩm">
                                            {{ $order_itm->product_name }}
                                        </a>
                                    </td>
                                    <td>{{$order_itm->product_category_name}}</td>
                                    <td class="text-right"><b>{{FunctionLib::numberFormat($order_itm->product_price_sell)}} đ</b></td>
                                    <td class="text-center text-middle">{{$order_itm->number_buy}}</td>
                                    <td class="text-right"><b class="red">{{FunctionLib::numberFormat($order_itm->product_price_sell*$order_itm->number_buy)}} đ</b></td>
                                    <?php
                                    $total_product = $total_product + $order_itm->number_buy;
                                    $total_money = $total_money + ($order_itm->product_price_sell*$order_itm->number_buy);
                                    ?>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" class="text-right"><b>Tổng số lượng hàng:</b></td>
                                <td colspan="2" class="text-right"><b>{{FunctionLib::numberFormat($total_product)}}</b></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><b>Tổng tiền:</b></td>
                                <td colspan="2" class="text-right"><b class="red">{{FunctionLib::numberFormat($total_money)}} đ</b></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><b>Tiền ship:</b></td>
                                <td colspan="2" class="text-right"><b class="red">{{FunctionLib::numberFormat($data->order_money_ship)}} đ</b></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><b>Tổng tiền thanh toán:</b></td>
                                <td colspan="2" class="text-right"><b class="red" style="font-size: 18px">{{FunctionLib::numberFormat($total_money+$data->order_money_ship)}} đ</b></td>
                            </tr>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.page-content -->
</div>
