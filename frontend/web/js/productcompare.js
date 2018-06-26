/*BEGIN CART*/
jQuery('body').on('click', '.settings_add_to_productcompare', function (e) {
    var id = jQuery(this).data('id');
    loadingFull();
    MainAjax({
        url: HTTP_MEDIA + '/productcompare/add',
        data: {id: id},
        dataType: 'text',
        success: function (rs) {
            if (rs === null) {
                window.location.href = HTTP_MEDIA + '/sosanh.html';
            } else {
                jQuery('.popgiohang-boc').remove();
                jQuery('body').append(rs);
            }
        }
    });
    return false;
});

jQuery('body').on('click', '#productcompare_count', function (e) {
    MainAjax({
        url: HTTP_MEDIA + '/productcompare/add',
        dataType: 'text',
        success: function (rs) {
            if(DEVICE == 1) {
                window.location.href = '/so-sanh';
            } else {
                jQuery('.popgiohang-boc').remove();
                jQuery('body').append(rs);
            }
        }
    });
    return false;
});
jQuery('body').on('click', '.popgiohang-tat,.popgiohang-an', function (e) {
    jQuery(this).parents('.popgiohang-boc').remove();
});

jQuery('body').on('click', '.deleteproductcompare', function (e) {
    var id = jQuery(this).data('id');
    MainAjax({
        url: HTTP_MEDIA + '/productcompare/delete',
        data: {id: id},
        dataType: 'text',
        success: function (rs) {
            jQuery('.popgiohang-boc').remove();
            jQuery('body').append(rs);
        }
    });
    return false;
})

jQuery('body').on('click', '.deleteproductcomparedetail', function (e) {
    var id = jQuery(this).data('id');
    MainAjax({
        url: HTTP_MEDIA + '/productcompare/deletedetail',
        data: {id: id},
        dataType: 'text',
        success: function (rs) {
            jQuery('.popgiohang-boc').remove();
            jQuery('#settings_productcomparedetail').html(rs);
        }
    });
    return false;
});
/*END CART*/