$(document).ready(function(e){
    
    jsDefault();
    
    $('body').on('mousemove','.D_tooltip',function(e){
        var index = $('.D_tooltip').index($(this));
        $('.datatooltiptralai').eq(index).attr('src',$('.datatooltiptralai').eq(index).data('src'));
    });
    
    
    $('body').on('keypress', '.isnumber', function(e) {
        var key = (e.charCode) ? e.charCode : e.keyCode;
        if ((key >= 48 && key <= 57) || key == 46 || key == 8) {
        }
        else {
            return false;
        }
    });
    
    $('body').on('keypress', '.isnumberint', function(e) {
        var key = (e.charCode) ? e.charCode : e.keyCode;
        if ((key >= 48 && key <= 57) || key == 8) {
        }
        else {
            return false;
        }
    });
    
    $('body').on('input', '.numberformat', function(e) {
        var pos = $(this).getCursorPosition();
        var length = $(this).val().length;
        $(this).val(number_format($(this).val()));
        var length2 = $(this).val().length;
        $(this).setCursorPosition(pos + length2 - length);
    });
    
    $('body').on('input', '.numberpercent', function(e) {
        var pos = $(this).getCursorPosition();
        var length = $(this).val().length;
        $(this).val(number_percent($(this).val()));
        var length2 = $(this).val().length;
        $(this).setCursorPosition(pos + length2 - length);
    });
});

function confirmModal(options) {
    var s = $.extend({
        'closeHtml': 'Close',
        'confirmHtml': 'Confirm',
        'close': function() {

        },
        'message': '',
        'confirm': function() {

        },
        'contentHtml': '<div style="margin-bottom:10px;"></div>'
                + '<button type="button" class="btn al-cancel close_modal">No</button>'
                + '<button type="button" class="btn btn-default confirm_modal">Yes</button>',
    }, options);
    $(document).LoPopUp(s);
    $('.modal-body div').html(s.message);
    $('.close_modal').html(s.closeHtml);
    $('.confirm_modal').html(s.confirmHtml);
    $('.confirm_modal').click(function(e) {
        s.confirm();
        $('#IDLOpopup').modal('toggle');
        $(window).unbind(e);
    });
    $('.close_modal').click(function(e) {
        s.close();
        $('#IDLOpopup').modal('toggle');
        $(window).unbind(e);
    });
}



function loadingFull(options) {
    var s = $.extend({
        img: HTTP_HOST + DIRECTORY_MAIN_2 + '/img/indicator_blue_small.gif',
        type: 'anh',
        check: true,
    }, options);
    var content = '';
    if(s.type == 'anh') {
        content =  '<img src="' + s.img + '" style="width:60px;position:absolute;top:0px;left:0px;right:0px;bottom:0px;margin:auto;" />';
    } else {
        content = '<div id="loadThoigian" style="width:60px;position:absolute;top:0px;left:0px;right:0px;bottom:0px;';
        content += 'margin:auto;height:60px;font-size:30px;border:#ccc 5px solid;border-radius:30px;font-weight:bold;text-align:center;color:#ccc;"></div>';
    }
    var html = '<div class="DD_loadingfull" style="position:fixed;height:100%;width:100%;background-color:rgba(255,255,255,0.4);top:0px;left:0px;z-index:100000;">'
            + content
            + '</div>';
    $('body').append(html);
    handle = false;
    if(s.type != 'anh') {
        var timeload = 0;
        $('#loadThoigian').html(timeload + 's');
        var handle = setInterval(function(){
            timeload++;
            $('#loadThoigian').html(timeload + 's');
        },1000);
    }
    if(s.check) {
        $(document).ajaxComplete(function() {
            clearInterval(handle);
            $('.DD_loadingfull').remove();
        });
    }
}

function MainAjax(options) {
    var s = $.extend({
        type            : 'POST',
        dataType        : 'json',
        objText         : false,
        titleAlert      : 'Alert',
        titleError      : 'Error log',
        autoShowAlert   : true,
        autoShowError   : true,
        success         : function(rs) {
            
        },
        error           : function(rs) {
            var contentHtml = '';
            if(rs.responseText) {
                contentHtml = rs.responseText;
            } else {
                contentHtml = rs;
            }
            if(s.autoShowError) {
                $(document).LoPopUp({
                    id          : 'IDLOpopup_error',
                    title       : s.titleError,
                    contentHtml : contentHtml,
                    cssDialog   : 'modal-lg',
                    afterClose  : function() {
                        $('#IDLOpopup_error').remove();
                    }
                });
                $('#IDLOpopup_error .modal-body').perfectScrollbar({
                        suppressScrollX: true,
                        wheelSpeed: 100,
                });
            }
        },
        afterSuccess   : function(rs) {
            
        },
    },options);
    loadingFull();
    if(s.objText) {
        s.objText.loadingText();
    }
    var function_success = s.success;
    var opt = {
        success     : function(rs) {
            function_success(rs);
            if(rs.code && rs.code != 200 && s.autoShowAlert) {
                $(document).LoPopUp({
                    id          : 'IDLOpopup_alert',
                    title       : s.titleAlert,
                    contentHtml : rs.data,
                    cssDialog   : 'modal-lg',
                    afterClose  : function() {
                        $('#IDLOpopup_alert').remove();
                    }
                });
                $('#IDLOpopup_alert .modal-body').perfectScrollbar({
                        suppressScrollX: true,
                        wheelSpeed: 100,
                });
            }
            s.afterSuccess(rs);
        }
    };
    var ajaxOption = $.extend(s,opt);
    $.ajax(ajaxOption);
}


function showmessageDialog(options) {
    $('.showmessageDialog').remove();
    var seting = $.extend({
        msg: "Successfull",
        type: "success",
        time: 5000,
        class: "body"
    }, options);
    var message = seting.msg;
    var type = seting.type;
    var class_alert = 'alert-info';
    switch (type) {
        case 'warning':
            class_alert = 'alert-warning';
            break;
        case 'success':
            class_alert = 'alert-info';
            break;
        case 'info':
            class_alert = 'alert-info';
            break;
        case 'danger':
            class_alert = 'alert-danger';
            break;
        case 'error':
            class_alert = 'alert-error';
            break;
    }
    var id = 'D_loadingfull' + Math.floor(Math.random() * 10000);
    var html = '<div id="' + id + '" class="alert alert-block ' + class_alert + ' showmessageDialog" ';
    html += (seting.class == 'body') ? 'style="position:absolute;margin:0px auto;padding:15px;float:left;max-width:800px;min-width:600px;z-index:1000000; border-color: #d6e9c6;border-radius:5px;top:-100px;"'
            : 'style="float:left;width:100%;padding:15px;border-color: #d6e9c6;border-radius:5px;background-color:#d6e9c6;z-index:1000000;"';
    html += '><button type="button" class="close" data-dismiss="alert" style="font-size:20px;" onclick="$(\'#' + id + '\').remove();"><span aria-hidden="true">×</span></button>'
            + '<span style="margin-right:10px;color: #3c763d;float:left;font-weight:bold;">' + message + '<span>'
            + '</div>';
    if ((seting.class == 'body'))
        $(seting.class).append(html);
    else
        $(seting.class).prepend(html);
    id = '#' + id;
    var left = ($(window).width() - $(id).width()) / 2;
    if (seting.class == 'body') {
        $(id).css({'left': left + 'px'});
        $(id).css({'top': ($(window).scrollTop() + 150) + 'px'});
    }

    $(id).fadeOut(seting.time);
    setTimeout(function() {
        $(id).remove();
    }, seting.time);
}

function checkDataYii2(data) {
    var msg = '';
    var i = 0;
    var aaa = [];
    $.each(data, function(index, value) {
        var idname = '.field-' + index;
        if($(idname).length) {
            if (i == 0) {
                $('html, body').animate({scrollTop: ($(idname).offset().top - 200)}, 500);
            }
            $(idname).addClass('has-error');
            $(idname).find('.help-block').html(value);
            msg += value + '<br>';
            i++;
        } else {
            console.log(index + value);
        }
    });
}
/*
 * AnhDung
 * count character when input text 
 * count character text < maxlength(ml) wheen object had attributes 'ml'
 */

function countChareter(id, idCount) {
    if ($(id).length > 0 && $(idCount).length > 0) {
        if ($(id).attr('ml')) {
            var maxlength = parseInt($(id).attr('ml'));
            if ($(id).val().length > maxlength)
                $(id).val($(id).val().substr(0, maxlength));
        }
        $(idCount).html($(id).val().length);
    }
}

function jsDefault() {
    $('.D_loadingImg').loadImg();
    $('.setting_tooltip').datatooltip();
}


function resetForm() {
    $('.help-block').empty();
    $('.has-error').removeClass('has-error');
}

function messageAlert(options){
    var settings = $.extend({
        'contentHtml'   : '<p></p><div class="col-sm-12" style="text-align:center;"><button type="button" class="btn btn-primary messageAlert" data-id="" data-type="">OK</button></div>',
        'success'       : function(that){
            
        },
        title           : 'Alert',
        closeX          : true,
        'redirectUrl'   : false,
        message         : 'Alert',
        modal_setting   : {
            keyboard    : false,
            backdrop    : 'static',
        },
    },options);
    $(document).LoPopUp(settings);
    if(settings.message)
        $('#IDLOpopup').find('.modal-body p').html(settings.message);
    
    $('.messageAlert').click(function(e){
        if(!settings.redirectUrl) {
            var that = $(this);
            settings.success(that);
            $('#IDLOpopup').modal('hide');
        }
        else {
            window.location.href = settings.redirectUrl;
        }
    });
}


function jsDefault() {
    $('.D_loadingImg').loadImg();
    $('.D_tooltip').datatooltip();

}


$.fn.datatooltip = function(options) {
    var s = $.extend({
        html: '<div id="sticky{index}" class="atip"><img class="datatooltiptralai" src="/' + WEB_TYPE + '/img/grey.gif" data-src="{src}" alt="{alt}" /></div>',
    }, options);
    var that = $(this);
    setTimeout(function() {
        $('#mystickytooltip').html('');
        that.each(function(i) {
            var that = $(this);
            that.attr('data-tooltip', 'sticky' + i);
            var src = that.attr('data-srca') ? that.attr('data-srca') : '';
            var alt = that.attr('alt') ? that.attr('alt') : '';
            var html = s.html.replace('{index}', i).replace('{src}', src).replace('{alt}',alt);
            $('#mystickytooltip').append(html);
        });
        stickytooltip.init("*[data-tooltip]", "mystickytooltip");
    }, 1000);
}

$.fn.loadImg = function(options) {
    var that = new Array();
    var $count = $(this).length;
    var $i = 0;
    function loadImg(that) {
        if (that.attr('data-src')) {
            if (that.offset().top <= $(window).scrollTop() + $(window).height()) {
                $('<img/>').load(function() {
                    that.attr('src', that.attr('data-src'));
                    $i++;
                    if ($i == $count && options && typeof (options) === 'function') {
                        options();
                    }
                }).attr('src', that.attr('data-src'));
                return true;
            }
        }
        else {
            that.load(function() {
                $i++;
                if ($i == $count && options) {
                    options();
                }
            });
            return true;
        }
        return false;
    }
    $(this).each(function(index) {
        that[index] = $(this);
        loadImg(that[index]);
        $(window).scroll(function(e) {
            if (loadImg(that[index])) {
                $(this).unbind(e);
            }
        });
    });
};


$.fn.vi = function(prop, value) {
    if($(this).length > 0 ) {
        return (value) ? $(this).css(prop, value + 'px') : parseFloat($(this).css(prop).replace('px', ''));
    }
    else {
        return 0;
    }
}
$.fn.heightTrue = function() {
    return $(this).height() + $(this).vi('margin-top') + $(this).vi('margin-bottom') + $(this).vi('padding-top') + $(this).vi('padding-bottom') + $(this).vi('border-top-width') + $(this).vi('border-bottom-width');
}

/**
 * All width + margin-left + margin-right + padding-left + padding-right
 * @returns {jQuery}
 */
$.fn.widthTrue = function() {
    return $(this).width() + $(this).vi('margin-left') + $(this).vi('margin-right') + $(this).vi('padding-left') + $(this).vi('padding-right') + $(this).vi('border-left-width') + $(this).vi('border-right-width');
};
/**
 * Loading image 
 * @param {type} options
 * @returns {load scroll image}
 */

$.fn.loadingText = function(str) {
    var that = $(this);
    if (that.hasClass('disable'))
        return false;
    that.addClass('disable');
    var text = '';
    if (!str) {
        str = 'Loading';
    }
    if (that.val() != '') {
        text = that.val();
        that.val(str);
    }
    else {
        text = that.html();
        that.html(str);
    }
    $(document).ajaxComplete(function(e) {
        that.removeClass('disable');
        (that.val() != '') ? that.val(text) : that.html(text);
    });
    return true;
}



$.fn.getCursorPosition = function() {
    var input = this.get(0);
    if (!input) return; // No (input) element found
    if ('selectionStart' in input) {
        // Standard-compliant browsers
        return input.selectionStart;
    } else if (document.selection) {
        // IE
        input.focus();
        var sel = document.selection.createRange();
        var selLen = document.selection.createRange().text.length;
        sel.moveStart('character', -input.value.length);
        return sel.text.length - selLen;
    }
}

$.fn.setCursorPosition = function(pos) {
    var elem = this.get(0);
    if (!elem)
        return; // No (input) element found
    if(elem != null) {
        if(elem.createTextRange) {
            var range = elem.createTextRange();
            range.move('character', pos);
            range.select();
        }
        else {
            if(elem.selectionStart) {
                elem.focus();
                elem.setSelectionRange(pos, pos);
            }
            else
                elem.focus();
        }
    }

}


$.fn.numberpercent = function() {
    $(this).each(function(i) {
        if (!$(this).next().hasClass('numberpercent')) {
            $(this).hide();
            $(this).after('<input id="numberpercent' + $(this).attr('id') + '" type="text" value="' + number_percent($(this).val()) + '" class="numberpercent ' + $(this).attr('class') + '" onblur="$(this).prev().val($(this).val().replace(/\%/gi,\'\'));" />');
        }
    });
}
$.fn.numberformat = function() {
    $(this).each(function(i) {
        if (!$(this).next().hasClass('numberformat')) {
            $(this).hide();
            $(this).after('<input id="numberformat' + $(this).attr('id') + '" type="text" value="' + number_format($(this).val()) + '" class="isnumber numberformat ' + $(this).attr('class') + '" onblur="$(this).prev().val($(this).val().replace(/,/gi,\'\'));" />');
        }
    });
}

$.fn.LoPopUp = function(options) {
    var settings = $.extend({
        id  : 'IDLOpopup',
        clearBefore: false,
        classModal: 'modal-fixed modal-verify',
        contentHtml: '',
        timeoutAppend: '',
        header: '&nbsp;',
        top: '',
        topchild: '',
        left: '',
        right: '',
        bottom: '',
        margin: '',
        position: '',
        width: '',
        maxwidth: '',
        minwidth: '',
        maxheight: '',
        minheight: '',
        loadingStart: false,
        closePopup: false,
        afterSuccess: null,
        'cssDialog': '',
        modal_setting : 'show',
        closeX: false,
        headerHtml: '',
        afterClose: function() {
            
        },
    }, options);
    if ($('#' + settings.id).length) {
        $('#' + settings.id).remove();
    }
    var htmlP = '<div class="modal fade ' + settings.classModal + '" tabindex="-1" role="dialog" aria-hidden="true" id="' + settings.id + '">'
            + '<div class="modal-dialog ' + settings.cssDialog + ' ">'
            + '<div class="modal-content">'
            + '<div class="modal-header">'
            + '<button onclick="$(this).closeAllPopup({id:\'' + settings.id + '\'}); return false;" type="button" class="close close_modal_business" aria-hidden="true">×</button>'
            + settings.headerHtml
            + '<h4 class="modal-title" >' + settings.header + '</h4>'
            + '</div>'
            + '<div class="modal-body" style="width:100%;background-color:#FFF;float:left;"></div>'
            + '</div>'
            + '</div>'
            + '</div>';
    $('body').append(htmlP);

    var objDialog = $('#' + settings.id);
    if (settings.clearBefore) {
        objDialog.find('.modal-body').html('');
    }

    if (settings.closeX) {
        $('.close_modal_business').remove();
    }

    objDialog.modal(settings.modal_setting);

    if (settings.loadingStart) {
        objDialog.find('.loading').show();
    }

    if ($.trim(settings.contentHtml) != '') {
        if ($.trim(settings.timeoutAppend) == '') {
            objDialog.find('.modal-body').html(settings.contentHtml);
            if ($.isFunction(settings.afterSuccess)) {
                settings.afterSuccess.call(this);
            }
        } else {
            setTimeout(function() {
                objDialog.find('.modal-body').html(settings.contentHtml);
                if ($.isFunction(settings.afterSuccess)) {
                    settings.afterSuccess.call(this);
                }
            }, settings.timeoutAppend);
        }
    }


    if ($.trim(settings.title) != '') {
        objDialog.find('.modal-title').html(settings.title);
    }

    if ($.trim(settings.top) != '') {
        objDialog.css('top', settings.top);
    }
    if ($.trim(settings.width) != '') {
        objDialog.find('.modal-dialog ').css('width', settings.width);
    }
    if ($.trim(settings.maxwidth) != '') {
        objDialog.find('.modal-dialog ').css('max-width', settings.maxwidth);
    }

    if ($.trim(settings.minwidth) != '') {
        objDialog.find('.modal-dialog ').css('min-width', settings.minwidth);
    }

    if ($.trim(settings.maxheight) != '') {
        objDialog.find('.modal-dialog ').css('max-height', settings.maxheight);
    }

    if ($.trim(settings.minheight) != '') {
        objDialog.find('.modal-body ').css('min-height', settings.minheight);
    }
    if ($.trim(settings.topchild) != '') {
        objDialog.find('.modal-dialog ').css('top', settings.topchild);
    }
    if ($.trim(settings.left) != '') {
        objDialog.find('.modal-dialog ').css('left', settings.left);
    }
    if ($.trim(settings.right) != '') {
        objDialog.find('.modal-dialog ').css('right', settings.right);
    }
    if ($.trim(settings.bottom) != '') {
        objDialog.find('.modal-dialog ').css('bottom', settings.bottom);
    }
    if ($.trim(settings.margin) != '') {
        objDialog.find('.modal-dialog ').css('margin', settings.margin);
    }
    if ($.trim(settings.position) != '') {
        objDialog.find('.modal-dialog ').css('position', settings.position);
    }
    objDialog.on('hidden.bs.modal',function(e){
        settings.afterClose();
    });
    return this;
};

$.fn.closeAllPopup = function(options) {
    var settings = $.extend({
        id      : 'IDLOpopup',
    },options);
    $('#' + settings.id).modal('hide');
};

function window_printfile(id_content, id_title, link_action) {
    var html = '<form id="form_window_print" action="/home/printfile" method="POST" style="display: none;">';
    html += '<input type="text" name="file" value="" />';
    html += '<input type="text" name="title" value="" />';
    html += '<textarea name="content"></textarea>';
    html += '</form>';
    if (!jQuery('#form_window_print').length) {
        jQuery('body').append(html);
    }
    if(!link_action) {
        link_action = '/home/printfile';
    }
    jQuery('#form_window_print').attr('action', link_action);
    jQuery('#form_window_print input[name="file"]').val(window.location.href);
    jQuery('#form_window_print input[name="title"]').val(jQuery(id_title).html());
    jQuery('#form_window_print textarea').val(jQuery(id_content).html());
    var myForm = document.getElementById('form_window_print');
    myForm.onsubmit = function () {
        var w = window.open('about:blank', 'Popup_Window', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=' + screen.width + ',height=' + screen.height + ',left = 0,top = 0');
        this.target = 'Popup_Window';
    };
    jQuery('#form_window_print').submit();
}