@extends('site.master')
@section('content')
    <div class="content main-view-post">
        <h1 class="title-head">
            <a href="#" title="Giỏ hàng của bạn">Giỏ hàng của bạn</a>
        </h1>
        <div class="row col-md-12">
            <div class="col-md-7">
                <h4 class="title_cart">Số Sản Phẩm: 1</h4>
                <form method="POST" action="#" accept-charset="UTF-8" id="txtFormShopCart" class="txtFormShopCart " name="txtFormShopCart"><input name="_token" type="hidden" value="8LQCbm2PMG54lwwvZxvID6AQKSXfL1f27TkiacTb">
                    <table class="list-shop-cart-item col-md-12" >
                        <tbody>
                        <tr class="first">
                            <th >Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá / 1 sản phẩm</th>
                            <th>Thao tác</th>
                        </tr>
                        <tr>
                            <td><a target="_blank" href="http://www.shopcuatui.com.vn/nuoc-hoa-chat-tao-mui/1379-nuoc-hoa-nu-tommy-girl-tropics.html">Nước hoa nữ Tommy Girl Tropics </a></td>
                            <td>
                                <select name="listCart[1379]">
                                    <option value="1" selected="selected">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </td>
                            <td>
                                1.200.000<sup>đ</sup>
                            </td>
                            <td><a href="#">Xóa</a></td>
                        </tr>

                        </tbody>
                    </table>

                </form>
            </div>
            <div class="col-md-5">
                <h4 class="title_cart text-center">Thông tin đơn hàng</h4>
                <div  class="title_cart1">
                    <span style="font-weight: bold">Tạm Tính:</span><span> 1.200.000đ</span>
                </div>
                <div class="title_cart1">
                    <span class="footer_cart" style="font-weight: bold">Tổng tiền </span><span style="color: orange">(đã bao gồm VAT):</span><span> 1.200.000đ</span>
                </div>
                <div class="list-btn-control">
                    <a id="backBuy" class="btndefault btn-primary col-md-4" href="index.html">Tiếp tục mua hàng</a>
                    <a id="sendCart" class="btndefault btn-primary col-md-4" href="gui-don-hang.html">Gửi đơn hàng</a>
                    <div class="page-order-cart"></div>
                </div>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>
@endsection