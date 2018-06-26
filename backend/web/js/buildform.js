$(document).ready(function(e) {
    /*CLICK ADDTABLEFORM*/
    /*BEGIN*/
    $('body').on('click', '#addtableform,#addmapping', function(e) {
        var that = $(this);
        DAjax({
            'url': that.data('href'),
            'success': function(rs) {
                if (rs.code == 200) {
                    $(document).LoPopUp({
                        'title': 'Create',
                        'contentHtml': rs.data,
                        'maxheight': 'auto',
                    });
                }
            },
        });
    });
    /*END*/
    /*CLICK CREATE FORM*/
    /*BEGIN*/
    $('body').on('click', '#addnewitem', function(e) {
        var href = $(this).attr('href');
        if (!$(this).loadingText())
            return false;
        loadingFull();
        $.ajax({
            'url': href,
            'type': 'POST',
            'data': {
                count: $('.edit_item_new').length,
            },
            'dataType': 'json',
            'success': function(rs) {
                if (rs.code == 200) {
                    $("#multi-section").remove();
                    $('.D_dragin').remove();
                    $('#load_section').html(rs.data);
                    owlCaro();
                    $('.edit_item_new').eq($('.edit_item_new').length - 1).click();
                }
            },
        });
        return false;
    });
    /*END*/
    /*CLICK DELETE FORM*/
    /*BEGIN*/
    $('body').on('click', '.delete-item-down', function(e) {
        loadingFull();
        var that = $(this);
        $.ajax({
            'url': that.data('href'),
            'type': 'POST',
            'dataType': 'json',
            'success': function(rs) {
                $("#multi-section").remove();
                $('.D_dragin').remove();
                $('#load_section').html(rs.data);
                owlCaro();
                $('.edit_item_new').eq(0).click();
            },
        });
    });
    /*END*/

    /*CLICK EDIT FORM*/
    /*BEGIN*/
    $('body').on('click', '.edit_item_new', function(e) {
        var that = $(this);
        loadingFull();
        var url = that.attr('href');
        $.ajax({
            'url': url,
            'type': 'POST',
            'dataType': 'json',
            'success': function(rs) {
                if (rs.code == 200) {
                    $('.D_dragin').remove();
                    $('.owl-item .li').removeClass('active');
                    that.parent().addClass('active');
                    $('#D_formbuilder').html(rs.data);
                    resetAjax();
                }
            },
        });
        return false;
    });
    /*END*/

    /*CHANGE TABLE*/
    /*BEGIN*/
    $('body').on('change', '#SettingsFieldSearch_table_id,#SettingsFieldSearch_multi_add', function(e) {
        loadingFull();
        var that = $('#SettingsFieldSearch_table_id');
        var multi_add = $('#SettingsFieldSearch_multi_add').val();
        $.ajax({
            'url': that.data('href'),
            'type': 'POST',
            'dataType': 'json',
            'data': {'id': that.val(),multi_add:multi_add},
            'success': function(rs) {
                if (rs.code == 200) {
                    $('.D_dragin').remove();
                    $('#buildform_update').html(rs.data);
                    owlCaro();
                    var url = explode('?', window.location.href);
                    var href = url[0] + '?table_id=' + that.val();
                    jUrlHistory(href);
                    resetAjax();
                }
            },
        });
    });
    /*END*/
    /*SAVE FORM*/
    /*BEGIN*/
    $('body').on('submit', '#buildform_update', function(e) {
        $('.errorMessage').hide();
        var fbvalue = encodeURIComponent(JSON.stringify(fb.mainView.collection.toJSON()));
        $('#SettingsFormSearch_fields').val(fbvalue);
        if (!$('#updateform').loadingText())
            return false;
        loadingFull();
        $.ajax({
            'url': $(this).attr('action'),
            'data': $(this).serialize(),
            'type': 'POST',
            'dataType': 'json',
            'success': function(rs) {
                if (rs.code == 200) {
                    $('.D_dragin').remove();
                    showmessageDialog({'msg': 'Update build form successfull!', 'class': '#savebuildform'});
                }
            },
        });
        return false;
    });
    /*END*/
    scrollsavebuildform();
    $(window).scroll(function(e) {
        scrollsavebuildform();
    });
});
function scrollsavebuildform() {
    if ($('.btn-section-frm').length == 0 || $('#savebuildform').length == 0)
        return false;
    if ($(window).height() + $(window).scrollTop() > $('.btn-section-frm').offset().top) {
        $('#savebuildform').attr('style', 'margin-right:0px;');
    }
    else {
        $('#savebuildform').css({'z-index':1000,position: 'fixed', bottom: '0px', width: ($('#savebuildform').parent().width() - 25) + 'px'});
    }
}

function ktform($class) {
    if ($($class).val() == '') {
        $($class).next().show();
        $($class).focus();
        $('html, body').animate({scrollTop: $($class).offset().top - 300}, 500);
        return false;
    }
    return true;
}
function owlCaro() {
    // slide section
    var numberSections = $("#multi-section .li").length;
    var currentSectionAct = $('#multi-section .li').index($('#multi-section .li.active'));
    //alert(currentSectionAct);
    var max_section = 8;
    var owl_section = $("#multi-section");
    if (owl_section.find('.li').length > 0) {
        owl_section.owlCarousel({
            items: max_section,
            rewindNav: false,
            afterAction: statunav
        });
        var owl = owl_section.data('owlCarousel');
        owl.goTo(currentSectionAct);
    }
    function statunav() {
        var cr = this.owl.currentItem;
        if ((this.owl.currentItem + max_section) >= (numberSections)) {
            $('.in-tab-section .right-btn').hide();
            $('.in-tab-section .left-btn').show();
        }
        else if (this.owl.currentItem == 0) {
            $('.in-tab-section .right-btn').show();
            $('.in-tab-section .left-btn').hide();
        } else {
            $('.in-tab-section .right-btn,.in-tab-section .left-btn').show();
        }
    }
    $(".left-btn").click(function() {
        owl_section.trigger('owl.prev');
    });
    $(".right-btn").click(function() {
        owl_section.trigger('owl.next');
    });
    $('.newsection-tab').click(function() {
        owl.goTo(numberSections);
        $(".right-btn").hide();
    });
    if (numberSections <= max_section)
    {
        $(".left-btn,.right-btn").hide();
    }
}