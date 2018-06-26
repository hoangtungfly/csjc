/*BEGIN CART*/
jQuery('body').on('click', '.settings_add_to_wishlist', function (e) {
    var id = jQuery(this).data('id');
    loadingFull();
    MainAjax({
        url: HTTP_MEDIA + '/wishlist/add',
        data: {id: id},
        dataType: 'text',
        success: function (rs) {
            if (rs === null || DEVICE == 1) {
                window.location.href = HTTP_MEDIA + '/yeu-thich';
            } else {
                jQuery('.popgiohang-boc').remove();
                jQuery('body').append(rs);
            }
        }
    });
    return false;
});

jQuery('body').on('click', '#wishlist_count', function (e) {
    MainAjax({
        url: HTTP_MEDIA + '/wishlist/add',
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

jQuery('body').on('click', '.deletewishlist', function (e) {
    var id = jQuery(this).data('id');
    MainAjax({
        url: HTTP_MEDIA + '/wishlist/delete',
        data: {id: id},
        dataType: 'text',
        success: function (rs) {
            jQuery('.popgiohang-boc').remove();
            jQuery('body').append(rs);
        }
    });
    return false;
})

jQuery('body').on('click', '.deletewishlistdetail', function (e) {
    var id = jQuery(this).data('id');
    MainAjax({
        url: HTTP_MEDIA + '/wishlist/deletedetail',
        data: {id: id},
        dataType: 'text',
        success: function (rs) {
            jQuery('.popgiohang-boc').remove();
            jQuery('#settings_wishlistdetail').html(rs);
        }
    });
    return false;
});
/*END CART*/