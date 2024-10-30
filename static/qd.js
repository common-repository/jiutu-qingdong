var QD = {
    qd_modal: function (data) {
        if (data.states == 1) {
            var states = 'checked';
        } else {
            var states = '';
        }
        var html = '<div class="modal fade" id="editShop" >\
        <div class="modal-dialog modal-dialog-centered modal-lg" style="top: 30px;">\
        <div class="modal-content">\
        <div class="modal-header">\
        <h4 class="modal-title">修改商品数据</h4>\
        <button type="button" class="close" data-dismiss="modal">×</button>\
        </div>\
        <div class="modal-body">\
        <div class="col-md-12 order-md-1">\
        <form class="needs-validation" id="shopdata" onsubmit="return false;">\
        <div class="row">\
        <div class="col-md-8 mb-0">\
        <label for="firstName">商品标题</label>\
        <div class="input-group mb-0">\
        <div class="input-group-prepend">\
        <div class="input-group-text">上架 \
        <input name="states" type="checkbox" '+ states + '>\
        <input name="id" type="hidden" value="'+ data.id + '">\
        </div>\
        </div>\
        <input type="text" value="'+ data.title + '" name="title" class="form-control">\
        </div>\
        </div>\
        <div class="col-md-4 mb-0">\
        <label for="lastName">商品价格</label>\
        <div class="input-group">\
        <div class="input-group-prepend">\
        <span class="input-group-text">¥</span>\
        </div>\
        <input type="text" value="'+ data.price + '" class="form-control" name="price">\
        <div class="input-group-append">\
        <span class="input-group-text">.00</span>\
        </div>\
        </div>\
        </div>\
        <div class="col-md-12 mb-0">\
        <label for="username">商品图片</label>\
        <div class="input-group mb-0">\
        <input type="text" value="'+ data.img + '" class="form-control" name="img" id="img">\
        <div class="input-group-append">\
        <button class="upload_button btn btn-outline-secondary" id="img" type="button">照片展示图</button>\
        </div>\
        </div>\
        </div>\
        <div class="col-md-12 mb-0">\
        <label for="username">商品介绍</label>\
        <div class="input-group">\
        <textarea class="form-control" rows="5" name="introduce" aria-label="With textarea">'+ data.introduce + '</textarea>\
        </div>\
        </div>\
        </div>\
        <hr class="mb-4">\
        <div class="col-md-12 mb-0">\
        <label for="address">详情图片</label>\
        <div class="row">\
        <div class="col-md-6 input-group mb-1">\
        <input type="text" class="form-control" value="'+ data.upload[0] + '" name="upload[]" id="image1">\
        <div class="input-group-append">\
        <button class="upload_button btn btn-outline-secondary" id="image1" type="button">一</button>\
        </div>\
        </div>\
        <div class="col-md-6 input-group mb-1">\
        <input type="text" class="form-control" value="'+ data.upload[1] + '" name="upload[]" id="image2">\
        <div class="input-group-append">\
        <button class="upload_button btn btn-outline-secondary" id="image2" type="button">二</button>\
        </div>\
        </div>\
        </div>\
        <div class="row">\
        <div class="col-md-6 input-group mb-1">\
        <input type="text" class="form-control" value="'+ data.upload[2] + '" name="upload[]" id="image3">\
        <div class="input-group-append">\
        <button class="upload_button btn btn-outline-secondary" id="image3" type="button">三</button>\
        </div>\
        </div>\
        <div class="col-md-6 input-group mb-1">\
        <input type="text" class="form-control" value="'+ data.upload[3] + '" name="upload[]" id="image4">\
        <div class="input-group-append">\
        <button class="upload_button btn btn-outline-secondary" id="image4" type="button">四</button>\
        </div>\
        </div>\
        </div>\
        <div class="row">\
        <div class="col-md-6 input-group mb-1">\
        <input type="text" class="form-control" value="'+ data.upload[4] + '" name="upload[]" id="image5">\
        <div class="input-group-append">\
        <button class="upload_button btn btn-outline-secondary" id="image5" type="button">五</button>\
        </div>\
        </div>\
        <div class="col-md-6 input-group mb-1">\
        <input type="text" class="form-control" value="'+ data.upload[5] + '" name="upload[]" id="image6">\
        <div class="input-group-append">\
        <button class="upload_button btn btn-outline-secondary" id="image6" type="button">六</button>\
        </div>\
        </div>\
        </div>\
        </div>\
        <hr class="mb-4">\
        <button class="btn btn-primary btn-sm btn-block" id="submitshop" type="button">确定提交</button>\
        </form></div></div>\
        <div class="modal-footer">\
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">关闭</button>\
        </div></div></div></div>';
        jQuery('#showAdd').html(html);
        jQuery('#editShop').modal('show');
    }
}


jQuery(document).ready(function () {
    jQuery('#submitshop').live('click', function (event) {
        jQuery.ajax({
            type: 'post',
            url: qd_ajax_url.ajax_url,
            data: 'action=qd_addshop&' + jQuery('#shopdata').serialize(),
            success: function (data) {
                var obj = JSON.parse(data);
                alert(obj.msg);
                if (obj.code) {
                    return location.reload()
                }
            }
        });
    });
});

jQuery(document).ready(function () {
    var upload_frame;
    var value_id;
    jQuery('.upload_button').live('click', function (event) {
        value_id = jQuery(this).attr('id');
        event.preventDefault();
        if (upload_frame) {
            upload_frame.open();
            return;
        }
        upload_frame = wp.media({
            title: '选择图片',
            button: {
                text: '选择',
            },
            multiple: false
        });
        upload_frame.on('select', function () {
            attachment = upload_frame.state().get('selection').first().toJSON();
            jQuery('input[id=' + value_id + ']').val(attachment.url);
        });
        upload_frame.open();
    });
});   