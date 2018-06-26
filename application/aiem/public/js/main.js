function widthBlockText() {
    if($('.block.block-text').length) {
        if($(window).width() > 650) {
            $('.block.block-text').eq(0).css({'padding-top':'100px'});
            $('.block.block-text').eq($('.block.block-text').length - 1).css({'padding-bottom':'200px'});
        } else {
            $('.block.block-text').eq(0).css({'padding-top':'20px'});
            $('.block.block-text').eq($('.block.block-text').length - 1).css({'padding-bottom':'20px'});
        }
    }
}

function resetAjax() {
    $('.settings_chosen').chosen({disable_search_threshold: 10});
}
$(document).ready(function (e) {
    resetAjax();
    if ($('#owl-main').length) {
        $('#owl-main').owlCarousel({
            navigation : false, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem:true,
            autoPlay:4000,
        });

    }
    widthBlockText();
    setTimeout(function(){
        widthBlockText();
    },2000);
    checkFooter();
    $(window).resize(function(e){
        widthBlockText();
        checkFooter();
    })
    
    showTextTransform();
    $(window).scroll(function (e) {
        showTextTransform();
        checkFooter();
    })
    
    $('body').on('click','.block-faq-left a',function(e){
        var id = $(this).attr('href');
        var a = id.split('#');
        if(a.length == 2) {
            $(this).closest('.block-faq-left').find('a').removeClass('active');
            $(this).addClass('active');
            var id = '#' + a[1];
            $(this).closest('.block-faq-content').find('.block-faq-right .div-faq-block').hide();
            $(this).closest('.block-faq-content').find('.div-faq-content-content').hide();
            $('.div-faq-block-header a.active').removeClass('active');
            $(id).fadeIn(300);
            checkFooter();
        }
    })
    
    
    $('body').on('click','.div-faq-block-header a',function(e){
        var that = $(this);
        var parent = $(this).closest('.div-faq-block-header');
        
        $('.div-faq-block-header a.active').removeClass('active');
        if(parent.next().css('display') == 'none') {
            parent.closest('.div-faq-block').find('.div-faq-content-content').each(function(i,v){
                if ($(this).css('display') == 'block') {
                    $(this).slideToggle();
                }
            })
            that.addClass('active');
        }
        
        parent.next().slideToggle();
        checkFooter();
        return false;
    });
    
    $('body').on('click','.block-faq-header div a',function(e){
        $('.block-faq-header div a').removeClass('active');
        $(this).addClass('active');
        $('.block-faq-content').hide();
        var id = '#' + $(this).attr('href').replace(/.*\#/gi,'');
        $(id).fadeIn(300);
        $(id).find('.block-faq-left a').removeClass('active');
        $(id).find('.block-faq-left a').eq(0).addClass('active');
        $(id).find('.div-faq-block').hide();
        $(id).find('.div-faq-block').eq(0).show();
        $('.div-faq-block-header a.active').removeClass('active');
        checkFooter();
    })
    
    if($('.block-faq').length) {
        var hash = window.location.hash.replace('#','');
       if(!hash) {
           hash = 'ios';
       }
        var a = hash.split('-');
        var tab_id = '#' + hash;
        var left_id = false;
        if(a.length > 1) {
            tab_id = '#' + a[0];
            left_id = '#' + hash;
        }
        $('.block-faq-header div a').removeClass('active');
        $('.block-faq-header div a').each(function(){
            var hash_a = $(this).attr('href').replace(/.*\#/gi,'');
            if(hash_a == tab_id.replace('#','')) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        })
        $('.block-faq-content').hide();
        $(tab_id).show();
        if(!left_id) {
            left_id = '#' + $(tab_id).find('.div-faq-block').eq(0).attr('id');
        }
        $(tab_id).find('.block-faq-left a').each(function(){
            var hash_a = $(this).attr('href').replace(/.*\#/gi,'');
            if(hash_a == left_id.replace('#','')) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        })
        $(tab_id).find('.div-faq-block').hide();
        $(left_id).show();
    }
});

function checkFooter() {
    if($('body').height() < $(window).height()) {
        $('#footer').css({'position':'fixed','left':'0px','bottom':'0px'});
    } else {
        $('#footer').removeAttr('style');
    }
}

function showTextTransform() {
    var scroll = $(window).scrollTop();
    var begin = scroll;
    var end = scroll + $(window).height();
    if ($('.transform_text_begin').length) {
        $('.transform_text_begin').each(function (e) {
            var top = $(this).offset().top;
            var height = $(this).height();
            if ((top > begin && top < end) || (top + height > begin && top + height < end)) {
                $(this).removeClass('transform_text_begin');
                $(this).addClass('transform_text_end');
                if ($(this).find('.transform_text_top').length) {
                    $(this).find('.transform_text_top').each(function () {
                        $(this).removeClass('transform_text_top');
                        $(this).addClass('transform_text_top_end');
                    })
                }
                if ($(this).find('.transform_text_bottom').length) {
                    $(this).find('.transform_text_bottom').each(function () {
                        $(this).removeClass('transform_text_bottom');
                        $(this).addClass('transform_text_bottom_end');
                    })
                }
            }
        })
    }
}
