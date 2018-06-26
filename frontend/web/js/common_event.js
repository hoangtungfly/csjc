jQuery(document).ready(function(e){
    jQuery('body').on('submit','#contact-form',function(e){
        var that = jQuery(this);
        resetForm();
        MainAjax({
            url: that.attr('action'),
            data: that.serialize(),
            success: function (rs) {
                if (rs.code == 200) {
                    messageAlert({
                        title: rs.title,
                        message: rs.message,
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
    
    jQuery('body').on('submit','#course-form',function(e){
        var that = jQuery(this);
        resetForm();
        MainAjax({
            url: that.attr('action'),
            data: that.serialize(),
            success: function (rs) {
                if (rs.code == 200) {
                    messageAlert({
                        title: 'Đăng ký khóa học thành công',
                        message: 'Bạn đã đăng ký khóa học thành công. Chúng tôi sẽ xem xét thông tin của bạn để liên hệ lại bạn trong vòng ít phút tới!',
                        redirectUrl: '/',
                    });
                }
                else {
                    checkDataYii2(rs);
                }
            },
        });
        return false;
    })
    
    jQuery('body').on('submit','#company-form',function(e){
        var that = jQuery(this);
        console.log(11221);
        resetForm();
        MainAjax({
            url: that.attr('action'),
            data: that.serialize(),
            success: function (rs) {
                if (rs.code == 200) {
                    messageDOk({
                        title: rs.title,
                        message: rs.message,
                        onclick: true,
                    });
                }
                else {
                    checkDataYii2(rs);
                }
            },
        });
        return false;
    })
})