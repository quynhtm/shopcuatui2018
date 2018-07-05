var baseUpload = {
    //Upload Multiple image
    uploadMultipleImages: function(type) {
        jQuery('#sys_PopupUploadImgOtherPro').modal('show');
        jQuery('.ajax-upload-dragdrop').remove();
        var urlAjaxUpload = WEB_ROOT+'/ajax/upload?act=upload_image';
        var id_hiden = document.getElementById('id_hiden').value;
        var _token = $('meta[name="csrf-token"]').val();
        var settings = {
            url: urlAjaxUpload,
            method: "POST",
            allowedTypes:"jpg,png,jpeg",
            fileName: "multipleFile",
            formData: {id: id_hiden, type: type, _token: _token},
            multiple: (id_hiden==0)? false: true,
            onSubmit:function(){
                jQuery( "#sys_show_button_upload").hide();
                jQuery("#status").html("<span color='green'>Đang upload...</span>");
            },
            onSuccess:function(files,xhr,data){
                dataResult = JSON.parse(xhr);
                if(dataResult.intIsOK === 1){
                    //Gan lai id item cho id hiden: dung cho them moi, sua item
                    jQuery('#id_hiden').val(dataResult.id_item);
                    jQuery( "#sys_show_button_upload").show();

                    //Add
                    var checked_img_pro = "<div class='clear'></div><input type='radio' id='checked_image_"+dataResult.info.id_key+"' name='checked_image' value='"+dataResult.info.id_key+"' onclick='baseUpload.checkedImage(\""+dataResult.info.name_img+"\",\"" + dataResult.info.id_key + "\")'><label for='checked_image_"+dataResult.info.id_key+"' style='font-weight:normal'>Ảnh đại diện</label><br/>";

                    var delete_img = "<a href='javascript:void(0);' id='sys_delete_img_other_" + dataResult.info.id_key + "' onclick='baseUpload.removeImage(\""+dataResult.info.id_key+"\",\""+dataResult.id_item+"\",\""+dataResult.info.name_img+"\",\""+type+"\")' >Xóa ảnh</a>";
                    var html= "<li id='sys_div_img_other_" + dataResult.info.id_key + "'>";
                    html += "<div class='div_img_upload' >";
                    html += "<img height='80' src='" + dataResult.info.src + "'/>";
                    html += "<input type='hidden' id='sys_img_other_" + dataResult.info.id_key + "' class='sys_img_other' name='img_other[]' value='" + dataResult.info.name_img + "'/>";
                    html += checked_img_pro;
                    html += delete_img;
                    html +="</div></li>";
                    jQuery('#sys_drag_sort').append(html);

                    jQuery('#sys_PopupImgOtherInsertContent #div_image').html('');
                    baseUpload.getInsertImageContent(type, 'off');

                    //Sucsess
                    jQuery("#status").html("<span color='green'>Upload is success</span>");
                    setTimeout( "jQuery('.ajax-file-upload-statusbar').hide();",2000 );
                    setTimeout( "jQuery('#status').hide();",2000 );
                    setTimeout( "jQuery('#sys_PopupUploadImgOtherPro').modal('hide');",2500 );

                }
            },
            onError: function(files,status,errMsg){
                jQuery("#status").html("<span color='red'>Upload is Failed</span>");
            }
        }
        jQuery("#sys_mulitplefileuploader").uploadFile(settings);
    },
    //Upload One image
    uploadOneImageAdvanced: function(type) {
        jQuery('#sys_PopupUploadImgOtherPro').modal('show');
        jQuery('.ajax-upload-dragdrop').remove();
        var urlAjaxUpload = WEB_ROOT+'/ajax/upload?act=upload_image';
        var id_hiden = document.getElementById('id_hiden').value;
        var _token = $('meta[name="csrf-token"]').val();
        var settings = {
            url: urlAjaxUpload,
            method: "POST",
            allowedTypes:"jpg,png,jpeg,gif",
            fileName: "multipleFile",
            formData: {id: id_hiden, type: type, _token: _token},
            multiple: false,
            onSubmit:function(){
                jQuery( "#sys_show_button_upload").hide();
                jQuery("#status").html("<span color='green'>Đang upload...</span>");
            },
            onSuccess:function(files,xhr,data){
                dataResult = JSON.parse(xhr);
                if(dataResult.intIsOK === 1){
                    //gan lai id item cho id hiden: dung cho them moi, sua item
                    jQuery('#id_hiden').val(dataResult.id_item);
                    jQuery( "#sys_show_button_upload").show();

                    //show ảnh
                    var html = "<img src='" + dataResult.info.src + "'/><span class='remove_file one' onclick='baseUpload.deleteOneImageAdvanced(0, \""+dataResult.id_item+"\",\""+dataResult.info.name_img+"\", "+type+")'>X</span>";
                    jQuery('#sys_show_image_one').html(html);

                    var img_new = dataResult.info.name_img;
                    if(img_new != ''){
                        jQuery("#img").attr('value', img_new);
                    }
                    //thanh cong
                    jQuery("#status").html("<span color='green'>Upload is success</span>");
                    setTimeout( "jQuery('.ajax-file-upload-statusbar').hide();",2000 );
                    setTimeout( "jQuery('#status').hide();",2000 );
                    setTimeout( "jQuery('#sys_PopupUploadImgOtherPro').modal('hide');",2500 );
                }
            },
            onError: function(files,status,errMsg){
                jQuery("#status").html("<spant color='red'>Upload is Failed</spant>");
            }
        }
        jQuery("#sys_mulitplefileuploader").uploadFile(settings);
    },
    checkedImage: function(nameImage,key){
        if (confirm('Bạn có muốn chọn ảnh này làm ảnh đại diện?')) {
            jQuery('#image_primary').val(nameImage);
            jQuery('#sys_delete_img_other_'+key).hide();

            //luu lai key anh chinh
            var key_pri = document.getElementById('sys_key_image_primary').value;
            jQuery('#sys_delete_img_other_'+key_pri).show();
            jQuery('#sys_key_image_primary').val(key);

        }
    },
    checkedImageHover: function(nameImage,key){
        jQuery('#image_primary_hover').val(nameImage);
    },
    removeImage: function(key,id,nameImage,type){

        if(jQuery("#image_primary_hover").length ){
            var img_hover = jQuery("#image_primary_hover").val();
            if(img_hover == nameImage){
                jQuery("#image_primary_hover").val('');
            }
        }

        if (confirm('Bạn có chắc xóa ảnh này?')) {
            var urlAjaxUpload = WEB_ROOT+'/ajax/upload?act=remove_image';
            var _token = $('meta[name="csrf-token"]').val();
            jQuery.ajax({
                type: "POST",
                url: urlAjaxUpload,
                data: {id : id, nameImage : nameImage, type: type, _token: _token},
                responseType: 'json',
                success: function(data) {
                    dataResult = JSON.parse(data);
                    if(dataResult.intIsOK === 1){
                        jQuery('#sys_div_img_other_'+key).hide();
                        jQuery('#sys_img_other_'+key).val('');
                        jQuery('#sys_new_img_'+key).hide();
                    }else{
                        jQuery('#sys_msg_return').html(data.msg);
                    }
                }
            });
        }
        jQuery('#sys_PopupImgOtherInsertContent #div_image').html('');
        baseUpload.getInsertImageContent(type, 'off');
    },
    getInsertImageContent: function(type, popup) {
        if(popup == 'open'){
            jQuery('#sys_PopupImgOtherInsertContent').modal('show');
        }
        var urlAjaxUpload = WEB_ROOT+'/ajax/upload?act=get_image_insert_content';
        var id_hiden = document.getElementById('id_hiden').value;
        var _token = $('meta[name="csrf-token"]').val();
        jQuery.ajax({
            type: "POST",
            url: urlAjaxUpload,
            data: "id_hiden=" + encodeURI(id_hiden) + "&type=" + encodeURI(type) + "&_token=" + encodeURI(_token),
            success: function(data){
                dataResult = JSON.parse(data);
                if(dataResult.intIsOK === 1){
                    var imagePopup = '';
                    for(var i = 0; i < dataResult['item'].length; i++) {
                        imagePopup += "<span class='float_left image_insert_content'>";
                        var insert_img = "<a class='img_item' href='javascript:void(0);' onclick='insertImgContent(\""+dataResult['item'][i]['large']+"\")' >";
                        imagePopup += insert_img;
                        imagePopup += "<img height=80 src='" + dataResult['item'][i]['small'] + "'/> </a>";
                        imagePopup += "</span>";
                    }
                    jQuery('#sys_PopupImgOtherInsertContent #div_image').html('');
                    jQuery('#sys_PopupImgOtherInsertContent #div_image').append(imagePopup);
                }
            }
        });
    },
    deleteOneImageAdvanced: function(key,id,nameImage,type){

        if (confirm('Bạn có chắc xóa ảnh này?')) {
            var urlAjaxUpload = WEB_ROOT+'/ajax/upload?act=remove_image';
            var _token = $('meta[name="csrf-token"]').val();
            jQuery.ajax({
                type: "POST",
                url: urlAjaxUpload,
                data: {id : id, nameImage : nameImage, type: type, _token: _token},
                responseType: 'json',
                success: function(data) {
                    dataResult = JSON.parse(data);
                    if(dataResult.intIsOK === 1){
                        jQuery('#sys_show_image_one').html('');
                    }
                }
            });
        }
    },
    //Upload document
    uploadDocumentAdvanced: function(type) {
        jQuery('#sys_PopupUploadFileCommon').modal('show');
        jQuery('.ajax-upload-dragdrop').remove();
        var urlAjaxUpload = WEB_ROOT+'/ajax/upload?act=upload_ext';
        var id_hiden = document.getElementById('id_hiden').value;
        var id_hiden_person = document.getElementById('id_hiden_person').value;
        var _token = $('meta[name="csrf-token"]').val();
        var settings = {
            url: urlAjaxUpload,
            method: "POST",
            allowedTypes:"jpg,jpeg,png,gif,txt,ppt,pptx,xls,xlsx,doc,docx,pdf,rar,zip,tar,mp4,flv,avi,3gp,mov",
            fileName: "multipleFile",
            formData: {id:id_hiden,type:type, _token:_token, id_hiden_person:id_hiden_person},
            multiple: false,
            onSubmit:function(){
                jQuery( "#sys_show_button_upload_file").hide();
                jQuery("#status_file").html("<span color='green'>Đang upload...</span>");
            },
            onSuccess:function(files,xhr,data){
                dataResult = JSON.parse(xhr);
                if(dataResult.intIsOK === 1){
                    //gan lai id item cho id hiden: dung cho them moi, sua item
                    jQuery('#id_hiden').val(dataResult.id_item);
                    jQuery( "#sys_show_button_upload_file").show();

                    //show file
                    var html = '<div class="item-file item_'+dataResult.info.name_key+'"><a target="_blank" href="' + dataResult.info.src + '">'+dataResult.info.name_file+'</a><span class="remove_file" onclick=\"baseUpload.deleteDocumentUpload(\''+dataResult.id_item+'\',\''+dataResult.info.name_key+'\',\''+dataResult.info.name_file+'\',\''+type+'\')\">X</span></div>';
                    jQuery('#sys_show_file').append(html);
                    var file_new = dataResult.info.name_file;
                    if(file_new != ''){
                        jQuery("#file").attr('value', file_new);
                    }
                    //thanh cong
                    jQuery("#status_file").html("<span color='green'>Upload is success</span>");
                    setTimeout( "jQuery('.ajax-file-upload-statusbar').hide();",2000 );
                    setTimeout( "jQuery('#status_file').hide();",2000 );
                    setTimeout( "jQuery('#sys_PopupUploadFileCommon').modal('hide');",2500 );
                }
            },
            onError: function(files,status,errMsg){
                jQuery("#status_file").html("<span color='red'>Upload is Failed</span>");
            }
        }
        jQuery("#sys_mulitplefileuploaderFile").uploadFile(settings);
    },
    deleteDocumentUpload:function(id, key, nameImage,type){
        if(confirm('Bạn muốn xóa [OK]:Đồng ý [Cancel]:Bỏ qua?)')){
            //Unlink
            $('.item-file.item_'+key).remove();
            var urlAjaxUpload = WEB_ROOT+'/ajax/upload?act=remove_image';
            var _token = $('meta[name="csrf-token"]').val();
            jQuery.ajax({
                type: "POST",
                url: urlAjaxUpload,
                data: {id:id, key:key, nameImage:nameImage, type:type, _token:_token},
                responseType: 'json',
                success: function(data) {
                    dataResult = JSON.parse(data);
                    if(dataResult.intIsOK === 1){
                    }else{
                        jQuery('#status_file').html(data.msg);
                    }
                }
            });

            return true;
        }
    },
};