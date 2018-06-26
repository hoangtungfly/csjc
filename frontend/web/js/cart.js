/*BEGIN CART*/
$.ajax({
    url     : '/cart/count',
    dataType: 'json',
    success : function(rs) {
        jQuery('#cart_count').html(rs.count);
        jQuery('#cart_price').html(rs.price);
    }
});
jQuery('body').on('click', '.settings_add_to_cart', function (e) {
    var count = $('.product_count_detail').length ? parseInt($('.product_count_detail').val()) : 1;
    if(jQuery(this).prev().length && jQuery(this).prev().hasClass('countwishlist')) {
        count = parseInt(jQuery(this).prev().val());
    }
    var id = jQuery(this).data('id');
    loadingFull();
    MainAjax({
        url: HTTP_MEDIA + '/cart/add',
        data: {id: id, count: count},   
        dataType: 'text',
        success: function (rs) {
            if (rs === null || DEVICE == 1) {
                window.location.href = HTTP_MEDIA + '/gio-hang';
            } else {
                jQuery('.popgiohang-boc').remove();
                jQuery('body').append(rs);
            }
        }
    });
    return false;
});

jQuery('body').on('submit', '#cart-form', function (e) {
    var that = jQuery(this);
    var submit = that.find('input[type=submit]');
    if (!submit.loadingText())
        return false;
    resetForm();
    MainAjax({
        url: that.attr('action'),
        data: that.serialize(),
        success: function (rs) {
            if (rs.code == 200) {
                messageAlert({
                    title: 'Cám ơn bạn đã đặt hàng trên ' + document.domain,
                    message: 'Đơn hàng của bạn sẽ được nhân viên ' + document.domain + ' liên hệ lại để xác nhận,'
                            + ' đơn hàng sẽ được xử lý trong thời gian sớm nhất có thể.<br>'
                            + 'Số hotline liên hệ trực tiếp ' + HOTLINE,
                    redirectUrl: '/',
                });
            }
            else {
                checkDataYii2(rs);
            }
        },
    });
    return false;
});

jQuery('body').on('click', '#cart_count', function (e) {
    MainAjax({
        url: HTTP_MEDIA + '/cart/add',
        dataType: 'text',
        success: function (rs) {
            jQuery('.popgiohang-boc').remove();
            jQuery('body').append(rs);
        }
    });
    return false;
});
jQuery('body').on('click', '.popgiohang-tat,.popgiohang-an', function (e) {
    jQuery(this).parents('.popgiohang-boc').remove();
});
jQuery('body').on('blur', '.soluonggiohang', function (e) {
    if (trim(jQuery(this).val()) == '' || trim(jQuery(this).val()) == '0') {
        jQuery(this).val(jQuery(this).data('value'));
        return false;
    }
    if (jQuery(this).val() == jQuery(this).data('value')) {
        return false;
    }
    jQuery(this).attr('data-value', jQuery(this).val());
    var id = jQuery(this).data('id');
    MainAjax({
        url: HTTP_MEDIA + '/cart/edit',
        data: {id: id, count: jQuery(this).val()},
        dataType: 'text',
        success: function (rs) {
            jQuery('.popgiohang-boc').remove();
            jQuery('body').append(rs);
        }
    });
    return false;
});
jQuery('body').on('click', '.nutxoagiohang', function (e) {
    var id = jQuery(this).data('id');
    MainAjax({
        url: HTTP_MEDIA + '/cart/delete',
        data: {id: id},
        dataType: 'text',
        success: function (rs) {
            jQuery('.popgiohang-boc').remove();
            jQuery('body').append(rs);
        }
    });
    return false;
})
jQuery('body').on('blur', '.soluonggiohangdetail', function (e) {
    var id = jQuery(this).data('id');
    MainAjax({
        url: HTTP_MEDIA + '/cart/editdetail',
        data: {id: id, count: jQuery(this).val()},
        dataType: 'text',
        success: function (rs) {
            jQuery('.popgiohang-boc').remove();
            jQuery('#settings_cartdetail').html(rs);
        }
    });
    return false;
});
jQuery('body').on('click', '.nutxoagiohangdetail', function (e) {
    var id = jQuery(this).data('id');
    MainAjax({
        url: HTTP_MEDIA + '/cart/deletedetail',
        data: {id: id},
        dataType: 'text',
        success: function (rs) {
            jQuery('.popgiohang-boc').remove();
            jQuery('#settings_cartdetail').html(rs);
        }
    });
    return false;
});
/*END CART*/