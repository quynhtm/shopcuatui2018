@extends('site.master')
@section('content')
    <div class="main-view-post">
        <h1 class="title-head">
            <a href="#" title="Chi tiết đơn hàng">Chi tiết đơn hàng</a>
        </h1>
        <div class="row">
            <div class="pay">
                <div class="left-order-send">
                    <div class="content-post-cart col-md-8">
                        <div class="title-pay-cart">Chi tiết đơn hàng</div>
                        <table class="list-pay">
                            <tbody>
                            <tr>
                                <td width="10%">
                                    <a class="img" target="_blank" href="http://www.shopcuatui.com.vn/my-pham/1177-nivea-q10-plus-bo-kem-ngay-dem-chong-nhan-hieu-qua.html">
                                        <img alt="Nivea Q10 plus (bộ kem ngày + đêm chống nhăn hiệu quả)" src="http://www.shopcuatui.com.vn/uploads/thumbs/product/1177/80x80/1509208279-img5444.jpg">
                                    </a>
                                </td>
                                <td>
                                    <span class="title">Nivea Q10 plus (bộ kem ngày + đêm chống nhăn hiệu quả)</span>
                                </td>
                                <td width="20%">
											<span class="price">790.000<sup>đ</sup> x 1</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Tổng số tiền thanh toán:</b></td>
                                <td colspan="1"><b>790.000</b><sup>đ</sup></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="content-post-cart col-md-4 ">
                    <div class="title-pay-cart">Địa chỉ giao hàng</div>
                    <form method="POST" action="http://www.shopcuatui.com.vn/gui-don-hang.html" accept-charset="UTF-8" id="txtFormPaymentCart" class="txtFormPaymentCart" name="txtFormPaymentCart"><input name="_token" type="hidden" value="xDKJcPvS2d0Gsace4ODvS7hZ4iK7KyJm3JGRAPQA">
                        <div class="form-group">
                            <label class="control-label">Số điện thoại<span>(*)</span></label>
                            <input id="txtMobile" name="txtMobile" class="form-control" maxlength="255" type="text">
                        </div>
                        <div class="form-group">
                            <label>Họ và tên<span>(*)</span></label>
                            <input id="txtName" class="form-control" name="txtName" maxlength="255" type="text">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input id="txtEmail" name="txtEmail" class="form-control" maxlength="255" type="text">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Địa chỉ<span>(*)</span></label>
                            <input id="txtAddress" name="txtAddress" class="form-control" maxlength="255" type="text">
                        </div>
                        <div class="form-group">
                            <label>Ghi chú</label>
                            <textarea id="txtMessage" class="form-control" rows="3" name="txtMessage" maxlength="1000"></textarea>
                            <span class="des">VD: thời gian nhận hàng...</span>
                        </div>
                        <button type="submit" id="submitPaymentOrder" class="btndefault btn btn-primary">Gửi đơn hàng</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection