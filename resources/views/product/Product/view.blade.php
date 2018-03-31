<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li class="active">Quản lý sản phẩm</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="panel panel-info">
                    {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                    <div class="panel-body">
                        <div class="form-group col-lg-3">
                            <label for="order_product_name">ID sản phẩm</label>
                            <input type="text" class="form-control input-sm" id="product_id" name="product_id" @if(isset($search['product_id']) && $search['product_id'] > 0)value="{{$search['product_id']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_product_name">Tên sản phẩm</label>
                            <input type="text" class="form-control input-sm" id="product_name" name="product_name" placeholder="Tên sản phẩm" @if(isset($search['product_name']) && $search['product_name'] != '')value="{{$search['product_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_status">Trạng thái</label>
                            <select name="product_status" id="product_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_status">Loại sản phẩm</label>
                            <select name="product_is_hot" id="product_is_hot" class="form-control input-sm">
                                {{$optionType}}
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_status">Thuộc chuyên mục</label>
                            <select name="depart_id" id="depart_id" class="form-control input-sm">
                                {{$optionDepart}}
                            </select>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="order_status">Thuộc danh mục</label>
                            <select name="category_id" id="category_id" class="form-control input-sm">
                                {{$optionCategory}}
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_status">Sản phẩm của Shop</label>
                            <select name="user_shop_id" id="user_shop_id" class="form-control input-sm chosen-select-deselect" tabindex="12" data-placeholder="Chọn tên shop">
                                <option value=""></option>
                                @foreach($arrShop as $shop_id => $shopName)
                                    <option value="{{$shop_id}}" @if($search['user_shop_id'] == $shop_id) selected="selected" @endif>{{$shopName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_status">Người đăng sản phẩm</label>
                            <select name="user_id_creater" id="user_id_creater" class="form-control input-sm chosen-select-deselect" tabindex="12" data-placeholder="Chọn tên người tạo sản phẩm">
                                <option value=""></option>
                                @foreach($arrUser as $userId => $userName)
                                    <option value="{{$userId}}" @if($search['user_id_creater'] == $userId) selected="selected" @endif>{{$userName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-12 text-right">
                            @if($is_root || $permission_full ==1 || $permission_create == 1)
                                <span class="">
                                    <a class="btn btn-danger btn-sm" href="{{URL::route('admin.productEdit')}}">
                                        <i class="ace-icon fa fa-plus-circle"></i>
                                        Thêm mới
                                    </a>
                                </span>
                            @endif
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>

                    @if($is_root)
                    <div class="panel-footer text-right">
                        <a class="btn btn-warning btn-sm" href="javascript:void(0);" onclick="Admin.removeAllItems(1);"><i class="fa fa-trash"></i> Xóa nhiều SP </a>
                        <div class="col-lg-3">
                            <select name="product_status_update" id="product_status_update" class="form-control input-sm">
                                {{$optionStatusUpdate}}
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <select name="sale_creater" id="sale_creater" class="form-control input-sm chosen-select-deselect" tabindex="12" data-placeholder="Chọn tên người tạo sản phẩm">
                                <option value=""></option>
                                @foreach($arrUser as $userId2 => $userName2)
                                    <option value="{{$userId2}}")>{{$userName2}}</option>
                                @endforeach
                            </select>
                        </div>
                        <a class="btn btn-success btn-sm" href="javascript:void(0);" onclick="Admin.setStastusProduct();"><i class="fa fa-refresh"></i> Đổi trạng thái </a>
                        <span class="img_loading" id="img_loading_delete_all"></span>
                    </div>
                    @endif
                    {{ Form::close() }}
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> sản  phẩm @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="3%" class="text-center">STT <input type="checkbox" class="check" id="checkAll"></th>
                            <th width="8%" class="text-center">Ảnh SP</th>
                            <th width="24%">Thông tin sản phẩm</th>
                            <th width="15%">Giá bán</th>
                            <th width="15%">Thông tin khác</th>
                            <th width="15%">Ngày</th>
                            <th width="10%" class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center text-middle">
                                    {{ $stt + $key+1 }}<br/>
                                    <input class="check" type="checkbox" name="checkItems[]" id="sys_checkItems" value="{{$item->product_id}}">
                                </td>
                                <td class="text-center text-middle">
                                    <img src="{{ ThumbImg::getImageThumb(CGlobal::FOLDER_PRODUCT, $item->product_id, $item->product_image, CGlobal::sizeImage_100)}}">
                                </td>
                                <td class="text-left text-middle">
                                    @if($item->product_status == CGlobal::status_show)
                                        [<b>{{ $item->product_id }}</b>]
                                        <a href="{{FunctionLib::buildLinkDetailProduct($item->product_id, $item->product_name, $item->category_name)}}" target="_blank" title="Chi tiết sản phẩm">
                                             {{ $item->product_name }}
                                        </a>
                                    @else
                                        [<b>{{ $item->product_id }}</b>] {{ $item->product_name }}
                                    @endif
                                    @if(isset($arrDepart[$item->depart_id]))
                                        <br/><b>Chuyên mục:</b> {{ $arrDepart[$item->depart_id] }}
                                    @endif
                                    @if($item->category_name != '')
                                        <br/><b>Danh mục:</b> {{ $item->category_name }}
                                    @endif
                                </td>
                                <td class="text-middle">
                                    @if($item->product_price_market > 0)Thị trường: <b class="green">{{ FunctionLib::numberFormat($item->product_price_market) }} đ</b><br/>@endif
                                    Giá bán: <b class="red">{{ FunctionLib::numberFormat($item->product_price_sell) }} đ</b>
                                    @if($item->product_price_input > 0)<br/>Giá nhập: <b>{{ FunctionLib::numberFormat($item->product_price_input) }} đ</b>@endif

                                    @if(isset($arrTypePrice[$item->product_type_price]))
                                        <br/><b class="red">{{ $arrTypePrice[$item->product_type_price] }}</b>
                                    @endif
                                    @if(isset($arrTypeProduct[$item->product_is_hot]) && $item->product_is_hot != CGlobal::PRODUCT_NOMAL)
                                        <br/><b class="red">{{ $arrTypeProduct[$item->product_is_hot] }}</b>
                                    @endif
                                </td>
                                <td class="text-left text-middle">
                                    @if(isset($arrIsSale[$item->is_sale]))
                                        Tình trạng: <b>{{ $arrIsSale[$item->is_sale] }}</b>
                                    @endif
                                </td>
                                <td class="text-left text-middle">
                                      Tạo: <b>{{$item->user_name_creater}}</b> <br/>{{date ('d-m-Y H:i',$item->time_created)}}
                                      <br/>Sửa: <b>{{$item->user_name_update}}</b> <br/>{{date ('d-m-Y H:i',$item->time_update)}}
                                </td>
                                <td class="text-center text-middle">
                                    @if($item->is_block == CGlobal::PRODUCT_BLOCK)
                                        <i class="fa fa-lock fa-2x red" title="Bị khóa"></i>
                                    @else
                                        @if($item->product_status == CGlobal::status_show)
                                            <i class="fa fa-check fa-2x green" title="Hiển thị"></i>
                                        @endif
                                        @if($item->product_status == CGlobal::status_hide)
                                            <i class="fa fa-close fa-2x red" title="Đang ẩn"></i>
                                        @endif
                                        @if($item->product_status == CGlobal::IMAGE_ERROR)
                                            <i class="fa fa-bug fa-2x red" title="Sản phẩm bị lỗi"></i>
                                        @endif
                                    @endif
                                    @if($is_root || $permission_full ==1|| $permission_edit ==1  )
                                        <a href="{{URL::route('admin.productEdit',array('id' => $item->product_id))}}" title="Sửa sản phẩm"><i class="fa fa-edit fa-2x"></i></a>
                                    @endif
                                    <span class="img_loading" id="img_loading_{{$item->product_id}}"></span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        {{$paging}}
                    </div>
                @else
                    <div class="alert">
                        Không có dữ liệu
                    </div>
                @endif
            </div>
        </div>
    </div><!-- /.page-content -->
</div>
<script type="text/javascript">
    //tim kiem cho shop
    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Không có kết quả'}
        //      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
</script>