$(document).ready(function () {
    HR.clickAddParentDepartment();
    HR.clickPostPageNext();
    HR.showDate();

    HR.clickSubmitMailDraft();
    HR.submitMailSend();
    HR.clickMailForward();
    HR.clickMailReply();

    HR.clickViewDocument();
    HR.clickDocumentForward();
    HR.clickSubmitDocumentDraft();
    HR.submitDocumentSend();
    HR.clickDocumentReply();

    HR.exportDevice();
    HR.exportViewTienLuongCongChuc();

    HR.changeValueViewCurriculumVitae('curriculum_desc_history1', 1);
    HR.changeValueViewCurriculumVitae('curriculum_desc_history2', 2);
    HR.changeValueViewCurriculumVitae('curriculum_foreign_relations1', 3);
    HR.changeValueViewCurriculumVitae('curriculum_foreign_relations2', 4);
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
        /*
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
        */
        $('select#department_type').change(function(){
           var dataType= $(this).val();
            if(dataType == 43){
                $('select#department_parent_id').attr('disabled', 'disabled');
                $('select#department_parent_id option').removeAttr('selected');
            }else{
                $('select#department_parent_id').removeAttr('disabled');
            }
        });
        var dataType= $('select#department_type').val();
        if(dataType == 43){
            $('select#department_parent_id').attr('disabled', 'disabled');
            $('select#department_parent_id option').removeAttr('selected');
        }else{
            $('select#department_parent_id').removeAttr('disabled');
        }
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
    clickSubmitMailDraft:function(){
        $('.submitMailDraft').click(function(){
            $('#adminForm').append('<input id="submitMailDraft" name="submitMailDraft" value="submitMailDraft" type="hidden">');
            $('#adminForm').submit();
        });
    },
    submitMailSend:function(){
        $('.submitMailSend').click(function(){
            $('#adminForm').append('<input id="submitMailSend" name="submitMailSend" value="submitMailSend" type="hidden">');
            $('#adminForm').submit();
        });
    },
    clickMailForward:function(){
        $('.replyline .forward').click(function(){
            $('.replyline').hide();

            $("#getItemCurrent").css('height', 500);
            $('html,body').animate({scrollTop: $("#getItemCurrent").offset().top - 50},'slow');

            var parent_id = $('#parent_id').val();
            $.ajax({
                type: "GET",
                url: WEB_ROOT + '/manager/mail/ajaxItemForward',
                data: {parent_id: parent_id},
                success: function (res) {
                   $('#getItemCurrent').append(res);
                   HR.multipleSelect('.multipleSelectRecive', 'hr_mail_person_recive_list', 'Chọn người nhận');
                   HR.multipleSelect('.multipleSelectCC', 'hr_mail_send_cc', 'Chọn người CC');
                   CKEDITOR.replace('hr_mail_content');
                   HR.clickSubmitMailDraft();
                   HR.submitMailSend();
                }
            });
        });
    },
    clickMailReply:function(){
        $('.replyline .reply').click(function(){
            $('.replyline').hide();

            $("#getItemCurrent").css('height', 500);
            $('html,body').animate({scrollTop: $("#getItemCurrent").offset().top - 50},'slow');

            var parent_id = $('#parent_id').val();
            $.ajax({
                type: "GET",
                url: WEB_ROOT + '/manager/mail/ajaxItemReply',
                data: {parent_id: parent_id},
                success: function (res) {
                    $('#getItemCurrent').append(res);
                    HR.multipleSelect('.multipleSelectRecive', 'hr_mail_person_recive_list', 'Chọn người nhận');
                    HR.multipleSelect('.multipleSelectCC', 'hr_mail_send_cc', 'Chọn người CC');
                    CKEDITOR.replace('hr_mail_content');
                    HR.clickSubmitMailDraft();
                    HR.submitMailSend();
                }
            });
        });
    },
    multipleSelect:function(nameElement, nameIput, placeholder){
        $(nameElement).fastselect({
            placeholder: placeholder,
            searchPlaceholder: 'Tìm kiếm',
            noResultsText: 'Không có kết quả',
            userOptionPrefix: 'Thêm ',
            nameElement:nameIput
        });
    },
    showDate: function () {
        var dateToday = new Date();
        if($('input').hasClass('.date')){
            jQuery('input.date').datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                lang: 'vi',
            });
        }
    },
    clickViewDocument:function(){
        jQuery('.iclick').click(function(event){
            event.stopPropagation();
        });
        $('.list-view-file .one-item-file').click(function(){
            if($(this).hasClass('act')){
                $(this).removeClass('act');
            }else{
                $('.list-view-file .one-item-file').removeClass('act');
                $(this).addClass('act');
            }
        });
    },
    clickSubmitDocumentDraft:function(){
        $('.submitDocumentDraft').click(function(){
            $('#adminForm').append('<input id="submitDocumentDraft" name="submitDocumentDraft" value="submitDocumentDraft" type="hidden">');
            $('#adminForm').submit();
        });
    },
    submitDocumentSend:function(){
        $('.submitDocumentSend').click(function(){
            $('#adminForm').append('<input id="submitDocumentSend" name="submitDocumentSend" value="submitDocumentSend" type="hidden">');
            $('#adminForm').submit();
        });
    },
    clickDocumentForward:function(){
        $('.replyline .forwardDocument').click(function(){
            $('.replyline').hide();

            $("#getItemCurrent").css('height', 500);
            $('html,body').animate({scrollTop: $("#getItemCurrent").offset().top - 50},'slow');

            var parent_id = $('#parent_id').val();
            $.ajax({
                type: "GET",
                url: WEB_ROOT + '/manager/document/ajaxItemForward',
                data: {parent_id: parent_id},
                success: function (res) {
                    $('#getItemCurrent').append(res);
                    HR.multipleSelect('.multipleSelectRecive', 'hr_document_person_recive_list', 'Chọn người nhận');
                    HR.multipleSelect('.multipleSelectCC', 'hr_document_send_cc', 'Chọn người CC');
                    CKEDITOR.replace('hr_document_content');
                    HR.clickSubmitDocumentDraft();
                    HR.submitDocumentSend();
                }
            });
        });
    },
    clickDocumentReply:function(){
        $('.replyline .replyDocument').click(function(){
            $('.replyline').hide();

            $("#getItemCurrent").css('height', 500);
            $('html,body').animate({scrollTop: $("#getItemCurrent").offset().top - 50},'slow');

            var parent_id = $('#parent_id').val();
            $.ajax({
                type: "GET",
                url: WEB_ROOT + '/manager/document/ajaxItemReply',
                data: {parent_id: parent_id},
                success: function (res) {
                    $('#getItemCurrent').append(res);
                    HR.multipleSelect('.multipleSelectRecive', 'hr_document_person_recive_list', 'Chọn người nhận');
                    HR.multipleSelect('.multipleSelectCC', 'hr_document_send_cc', 'Chọn người CC');
                    CKEDITOR.replace('hr_document_content');
                    HR.clickSubmitDocumentDraft();
                    HR.submitDocumentSend();
                }
            });
        });
    },
    /**
     * QuynhTM add
     */
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
    updateStatusAjaxCommon: function (str_object_id, status, urlAjax) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        if(confirm('Bạn có muốn cập nhật?')){
            $.ajax({
                type: 'post',
                url: WEB_ROOT + '/manager/'+urlAjax,
                data: {status: status, str_object_id: str_object_id},
                headers: {'X-CSRF-TOKEN': _token},
                success: function (data) {
                    if ((data.intReturn == 0)) {
                        alert(data.msg);
                    } else {
                        window.location.reload();
                    }
                },
            });
        }
    },
    getInfoPersonPopup: function (str_person_id) {
        $('#sys_showPopupCommon').modal('show');
        $('#img_loading').show();
        $('#sys_show_infor').html('');
        $.ajax({
            type: "GET",
            url: WEB_ROOT + '/manager/personnel/infoPerson/'+str_person_id,
            data: {str_person_id: str_person_id},
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
    getInfoSalaryPopup: function (str_salary_id) {
        $('#sys_showPopupCommon').modal('show');
        $('#img_loading').show();
        $('#sys_show_infor').html('');
        $.ajax({
            type: "GET",
            url: WEB_ROOT + '/manager/salaryAllowance/getInfoSalary/'+str_salary_id,
            data: {str_salary_id: str_salary_id},
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

    /** DuyNX export data **/
    exportDevice:function(){
        $('.exportDevice').click(function(){
            var r = confirm("Bạn muốn xuất excel [OK]:Yes[Cancel]:No?");
            if(r){
                url = WEB_ROOT + '/manager/device/export';
                $('#formSearchDevice').attr('action', url);
                $('#formSearchDevice').submit();
                return false;
            }
            return false;
        });
    },
    exportViewTienLuongCongChuc:function(){
        $('.exportViewTienLuongCongChuc').click(function(){
            var r = confirm("Bạn muốn xuất excel [OK]:Yes[Cancel]:No?");
            if(r){
                url = WEB_ROOT + '/manager/report/exportTienLuongCongChuc';
                $('#adminFormExportViewTienLuongCongChuc').attr('action', url);
                $('#adminFormExportViewTienLuongCongChuc').submit();
                return false;
            }
            return false;
        });
    },

    changeValueViewCurriculumVitae:function(nameField, type){
        $('#' + nameField).change(function(){
            var data = $(this).val();
            var uid = $(this).attr('dataPerson');
            if(data != ''){
                $.ajax({
                    type: "POST",
                    url: WEB_ROOT + '/manager/curriculumVitaePerson/changeValueViewCurriculumVitae',
                    data: {uid: uid, nameField: nameField, dataField: data, type: type},
                    dataType: 'json',
                    success: function (res) {
                        return false;
                    }
                });
            }
        });
    },
    onclickActionDeletePerson: function (msg,url) {
        var a = confirm(msg);
        if (a) {
            window.location.href = url;
        }
    }
}
