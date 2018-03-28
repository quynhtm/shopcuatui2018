$(document).ready(function () {
    HR.clickAddParentDepartment();
    HR.clickPostPageNext();
    HR.showDate();
});
HR = {
    editItem: function (id, $url) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        $("#loading").fadeIn().fadeOut(10);
        $.ajax({
            type: "POST",
            url: $url,
            data: {id: id},
            headers: {'X-CSRF-TOKEN': _token},
            success: function (data) {
                $('.loadForm').html(data);
                return false;
            }
        });
    },
    deleteItem: function (id, url) {
        var a = confirm(lng['txt_mss_confirm_delete']);
        var _token = $('meta[name="csrf-token"]').attr('content');
        $("#loading").fadeIn().fadeOut(10);
        if (a) {
            $.ajax({
                type: 'get',
                url: url,
                data: {'id': id},
                headers: {'X-CSRF-TOKEN': _token},
                success: function (data) {
                    if ((data.errors)) {
                        alert(data.errors)
                    } else {
                        window.location.reload();
                    }
                },
            });
        }
    },
    getFormData: function (frmElements) {
        var out = {};
        var s_data = $(frmElements).serializeArray();
        for (var i = 0; i < s_data.length; i++) {
            var record = s_data[i];
            out[record.name] = record.value;
        }
        return out;
    },
    addItem: function (elementForm, elementInput, btnSubmit, $url) {
        $("#loading").fadeIn().fadeOut(10);
        var isError = false;
        var msg = {};
        $(elementInput).each(function () {
            var input = $(this);
            if ($(this).hasClass("input-required") && input.val() == '') {
                msg[$(this).attr("name")] = "※" + $(this).attr("title") + lng['is_required'];
                isError = true;
            }
        });
        if (isError == true) {
            var error_msg = '';
            $.each(msg, function (key, value) {
                error_msg = error_msg + value + "\n";
            });
            alert(error_msg);
            return false;
        } else {
            $(btnSubmit).attr("disabled", 'true');
            var data = HR.getFormData(elementForm);
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                url: $url,
                data: data,
                headers: {'X-CSRF-TOKEN': _token},
                success: function (data) {
                    $(btnSubmit).removeAttr("disabled");
                    if ((data.isOk == 0)) {
                        alert(data.errors)
                    } else {
                        window.location.href = data.url;
                    }
                },
            });
        }
    },
    resetItem: function (elementKey, elementValue) {
        $("#loading").fadeIn().fadeOut(10);
        $('input[type="text"]').val('');
        $(elementKey).val(elementValue);
        $('.frmHead').text('Thêm mới');
        $('.icChage').removeClass('fa-edit').addClass('fa-plus-square');
    },
    clickAddParentDepartment: function () {
        $('.list-group.ext li').click(function () {
            $('.list-group.ext li').removeClass('act');
            var parent_id = $(this).attr('data');
            var parent_title = $(this).attr('title');
            var Prel = $(this).attr('rel');
            var Psrel = $(this).attr('psrel');
            var Crel = $('#id_hiden').attr('rel');
            if (Prel != Crel) {
                if (Psrel != Crel) {
                    $(this).addClass('act');
                    $('#sps').show();
                    $('#department_parent_id').val(parent_id);
                    $('#orgname').text(parent_title);
                    $('#department_type').attr('disabled', 'disabled');
                } else {
                    alert('Bạn không thể chọn danh mục con làm cha.');
                }
            } else {
                alert('Bạn chọn danh mục cha khác.');
                $('#sps').hide();
                $('#orgname').text('');
                var datatmp = $('#department_parent_id').attr('datatmp');
                $('#department_parent_id').val(datatmp);
                $('#department_type').removeAttr('disabled');
            }
        });
    },
    clickPostPageNext: function () {
        $('.submitNext').click(function () {
            var department_name = $('#department_name').val();
            if (department_name != '') {
                $('#adminForm').append('<input id="clickPostPageNext" name="clickPostPageNext" value="clickPostPageNext" type="hidden">');
            } else {
                var _alert = "※" + $('#department_name').attr("title") + lng['is_required'];
                alert(_alert);
                return false;
            }
            $('#adminForm').submit();
        });
    },
    showDate: function () {
        var dateToday = new Date();
        jQuery('.date').datetimepicker({
            timepicker: false,
            format: 'd-m-Y',
            lang: 'vi',
        });
    },
    getInfoContractsPerson: function (person_id, contracts_id) {
        $('#sys_showPopupCommon').modal('show');
        $('#img_loading').show();
        $('#sys_show_infor').html('');
        $.ajax({
            type: "GET",
            url: WEB_ROOT + '/manager/infoPerson/EditContracts',
            data: {person_id: person_id, contracts_id: contracts_id},
            dataType: 'json',
            success: function (res) {
                $('#img_loading').hide();
                if (res.intReturn == 1) {
                    $('#sys_show_infor').html(res.html);
                } else {
                    alert(res.msg);
                    $('#sys_showPopupCommon').modal('hide');
                }
            }
        });
    },

    /**
     * QuynhTM add
     */
    contractsSubmit: function (elementForm, btnSubmit) {
        $("#loading").fadeIn().fadeOut(10);
        var isError = false;
        var msg = {};
        $(elementForm+' :input').each(function () {
            var input = $(this);
            if ($(this).hasClass("input-required") && input.val() == '') {
                msg[$(this).attr("name")] = "※" + $(this).attr("title") + ' không được bỏ trống';
                isError = true;
            }
        });
        if (isError == true) {
            var error_msg = '';
            $.each(msg, function (key, value) {
                error_msg = error_msg + value + "\n";
            });
            alert(error_msg);
            return false;
        } else {
            $('#'+btnSubmit).attr("disabled", 'true');
            var data = HR.getFormData(elementForm);
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                url: WEB_ROOT + '/manager/infoPerson/PostContracts',
                data: data,
                headers: {'X-CSRF-TOKEN': _token},
                success: function (data) {
                    $(btnSubmit).removeAttr("disabled");
                    if ((data.intReturn == 0)) {
                        alert(data.msg);
                    } else {
                        $('#sys_showPopupCommon').modal('hide');
                        $('#show_list_contracts').html(data.html);
                    }
                },
            });
        }
    },
    deleteComtracts: function (person_id,contracts_id) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        if(confirm('Bạn có muốn xóa item này?')){
            $.ajax({
                type: 'post',
                url: WEB_ROOT + '/manager/infoPerson/DeleteContracts',
                data: {person_id: person_id,contracts_id: contracts_id},
                headers: {'X-CSRF-TOKEN': _token},
                success: function (data) {
                    if ((data.intReturn == 0)) {
                        alert(data.msg);
                    } else {
                        $('#show_list_contracts').html(data.html);
                    }
                },
            });
        }
    },
    /**
     * QuynhTM add use common
     * @param person_id
     * @param contracts_id
     */
    getAjaxCommonInfoPopup: function (str_person_id, str_object_id, urlAjax,typeAction) {
        $('#sys_showPopupCommon').modal('show');
        $('#img_loading').show();
        $('#sys_show_infor').html('');
        $.ajax({
            type: "GET",
            //url: WEB_ROOT + '/manager/infoPerson/EditContracts',
            url: WEB_ROOT + '/manager/'+urlAjax,
            data: {str_person_id: str_person_id, str_object_id: str_object_id, typeAction: typeAction},
            dataType: 'json',
            success: function (res) {
                $('#img_loading').hide();
                if (res.intReturn == 1) {
                    $('#sys_show_infor').html(res.html);
                } else {
                    alert(res.msg);
                    $('#sys_showPopupCommon').modal('hide');
                }
            }
        });
    },
    submitPopupCommon: function (elementForm, urlAjax, divShow, btnSubmit) {
        $("#loading").fadeIn().fadeOut(10);
        var isError = false;
        var msg = {};
        $(elementForm+' :input').each(function () {
            var input = $(this);
            if ($(this).hasClass("input-required") && input.val() == '') {
                msg[$(this).attr("name")] = "※" + $(this).attr("title") + ' không được bỏ trống';
                isError = true;
            }
        });
        if (isError == true) {
            var error_msg = '';
            $.each(msg, function (key, value) {
                error_msg = error_msg + value + "\n";
            });
            alert(error_msg);
            return false;
        } else {
            $('#'+btnSubmit).attr("disabled", 'true');
            var data = HR.getFormData(elementForm);
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                //url: WEB_ROOT + '/manager/infoPerson/PostContracts',
                url: WEB_ROOT + '/manager/'+urlAjax,
                data: data,
                headers: {'X-CSRF-TOKEN': _token},
                success: function (data) {
                    $(btnSubmit).removeAttr("disabled");
                    if ((data.intReturn == 0)) {
                        alert(data.msg);
                    } else {
                        $('#sys_showPopupCommon').modal('hide');
                        $('#'+divShow).html(data.html);
                    }
                },
            });
        }
    },
    deleteAjaxCommon: function (str_person_id, str_object_id, urlAjax, divShow, typeAction) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        if(confirm('Bạn có muốn xóa item này?')){
            $.ajax({
                type: 'post',
                url: WEB_ROOT + '/manager/'+urlAjax,
                data: {str_person_id: str_person_id, str_object_id: str_object_id, typeAction: typeAction},
                headers: {'X-CSRF-TOKEN': _token},
                success: function (data) {
                    if ((data.intReturn == 0)) {
                        alert(data.msg);
                    } else {
                        $('#'+divShow).html(data.html);
                    }
                },
            });
        }
    },
}
