jQuery(document).ready(function ($) {
    jsDefault();
    
    jQuery('body').on('beforeValidate',function(e){
        loadingFull();
    });
    
    jQuery('body').on('afterValidate',function(e){
        jQuery('.DD_loadingfull').remove();
    });
    
    jQuery('body').on('pjax:start',function(e){
        loadingFull();
    });
    
    jQuery('body').on('pjax:end',function(e){
        jQuery('.DD_loadingfull').remove();
    })
    
    
    jQuery('body').on('submit','#change-password-form,#forgot-password-form,#reset-password-form',function(e){
        MainAjax({
            url         : jQuery(this).attr('action'),
            data        : jQuery(this).serialize(),
            success     : function(rs) {
                if(rs.code == 200) {
                    messageAlert({
                        title       : rs.title,
                        message     : rs.message,
                        redirectUrl : '/',
                    });
                } else {
                    checkDataYii2(rs);
                }
            }
            
        });
        return false;
    })
    
    jQuery('body').on('submit', '#search_mini_form', function () {
        var input = jQuery(this).find('#search');
        var category = $(this).find('select[name="category"]');
        var value = locdau(jQuery.trim(input.val()));
        if (value == '' && (!category.length || !category.val())) {
            return false;
        }
        
        var action = jQuery(this).attr('action');
        var val_category = category.length ? $.trim(category.val()) : '';
        
        if(val_category) {
            action += (action.match(/\/$/gi) ? '' : '/') + 'cate-' + val_category;
        }
        
        if (value != '') {
            action += (action.match(/\/$/gi) ? '' : '/') + value;
        }
        jQuery(this).attr('action', action);
    })

    jQuery(window).resize(function () {
        resize_popup();
    })

    jQuery('body').on('keypress', '.isnumber', function (e) {
        var key = (e.charCode) ? e.charCode : e.keyCode;
        if ((key >= 48 && key <= 57) || key == 46 || key == 8) {
        }
        else {
            return false;
        }
    });

    jQuery('body').on('keypress', '.isnumberint', function (e) {
        var key = (e.charCode) ? e.charCode : e.keyCode;
        if ((key >= 48 && key <= 57) || key == 8) {
        }
        else {
            return false;
        }
    });

    jQuery('body').on('input', '.numberformat', function (e) {
        var pos = jQuery(this).getCursorPosition();
        var length = jQuery(this).val().length;
        jQuery(this).val(number_format(jQuery(this).val()));
        var length2 = jQuery(this).val().length;
        jQuery(this).setCursorPosition(pos + length2 - length);
    });

    jQuery('body').on('input', '.numberpercent', function (e) {
        var pos = jQuery(this).getCursorPosition();
        var length = jQuery(this).val().length;
        jQuery(this).val(number_percent(jQuery(this).val()));
        var length2 = jQuery(this).val().length;
        jQuery(this).setCursorPosition(pos + length2 - length);
    });


    var href = location.href;
    try {
        History.replaceState({state: new Date().getTime() / 1000}, null, href);
        window.onpopstate = function (event) {
            try {
                var backLinkSys = History.getState().cleanUrl;
                if (event && typeof event.state != 'undefined' && event.state !== null) {
                    window.location.href = backLinkSys;
                }
            } catch (err) {
                return true;
            }

        };
    } catch (err) {
    }
})

function jUrlHistory(baseUrl, param, title, log) {
//    try {

    baseUrl = baseUrl.replace(/\/$/, '');
    var p = param && Object.keys(param).length ? (baseUrl.split('?')[1] ? '&' : '?') + $.param(param) : '';
    if (!log) {
        log = param;
    }
    console.log(baseUrl + p);
    History.pushState(log, '', baseUrl + p);
//    }
//    catch (err) {
//    }
}


jQuery.fn.getCursorPosition = function () {
    var input = this.get(0);
    if (!input)
        return; // No (input) element found
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

jQuery.fn.setCursorPosition = function (pos) {
    var elem = this.get(0);
    if (!elem)
        return; // No (input) element found
    if (elem != null) {
        if (elem.createTextRange) {
            var range = elem.createTextRange();
            range.move('character', pos);
            range.select();
        }
        else {
            if (elem.selectionStart) {
                elem.focus();
                elem.setSelectionRange(pos, pos);
            }
            else
                elem.focus();
        }
    }

}


jQuery.fn.numberpercent = function () {
    jQuery(this).each(function (i) {
        if (!jQuery(this).next().hasClass('numberpercent')) {
            jQuery(this).hide();
            jQuery(this).after('<input id="numberpercent' + jQuery(this).attr('id') + '" type="text" value="' + number_percent(jQuery(this).val()) + '" class="numberpercent ' + jQuery(this).attr('class') + '" onblur="jQuery(this).prev().val(jQuery(this).val().replace(/\%/gi,\'\'));" />');
        }
    });
}
jQuery.fn.numberformat = function () {
    jQuery(this).each(function (i) {
        if (!jQuery(this).next().hasClass('numberformat')) {
            jQuery(this).hide();
            jQuery(this).after('<input id="numberformat' + jQuery(this).attr('id') + '" type="text" value="' + number_format(jQuery(this).val()) + '" class="isnumber numberformat ' + jQuery(this).attr('class') + '" onblur="jQuery(this).prev().val(jQuery(this).val().replace(/,/gi,\'\'));" />');
        }
    });
}

function share_twitter() {
    u = location.href;
    t = document.title;
    window.open("http://twitter.com/home?status=" + encodeURIComponent(u));
}
function share_facebook() {
    u = location.href;
    t = document.title;
    window.open("http://www.facebook.com/share.php?u=" + encodeURIComponent(u) + "&t=" + encodeURIComponent(t));
}
function share_google() {
    u = location.href;
    t = document.title;
    window.open("http://plus.google.com/share?url=" + encodeURIComponent(u) + "&title=" + t + "&annotation=" + t);
}
function share_pinterest() {
    u = location.href;
    t = document.title;
    window.open("http://pinterest.com/pin/create/button/?url=" + encodeURIComponent(u) + "&media=" + jQuery('meta[property="og:image"]').attr('content') + "&description=" + t);
}
function share_buzz() {
    u = location.href;
    t = document.title;
    window.open("http://buzz.yahoo.com/buzz?publisherurn=" + t + "&targetUrl=" + encodeURIComponent(u));
}

function window_savefile(id_content, id_title) {
    var html = '<form id="form_window_save" action="/home/savefile" method="POST" style="display: none;">';
    html += '<input type="text" name="file" value="" />';
    html += '<input type="text" name="title" value="" />';
    html += '<textarea name="content"></textarea>';
    html += '</form>';
    if (!jQuery('#form_window_save').length) {
        jQuery('body').append(html);
    }
    jQuery('#form_window_save').attr('action', '/home/savefile');
    jQuery('#form_window_save input[name="file"]').val(window.location.href);
    jQuery('#form_window_save input[name="title"]').val(jQuery(id_title).html());
    jQuery('#form_window_save textarea').val(jQuery(id_content).html());
    jQuery('#form_window_save').submit();
}

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

function window_exportfile(link_action, data) {
    var html = '<form id="form_window_export" action="/home/export" method="POST" style="display: none;">';
    html += '<textarea name="content">' + data + '</textarea>';
    html += '</form>';
    if (!jQuery('#form_window_export').length) {
        jQuery('body').append(html);
    }
    if(!link_action) {
        link_action = '/home/export';
    }
    jQuery('#form_window_export').attr('action', link_action);
    jQuery('#form_window_export').submit();
}

function loadingFull(options) {
    var s = jQuery.extend({
        img: '/backend/web/img/indicator_blue_small.gif',
        type: 'anh',
        check: true,
    }, options);
    var content = '';
    if (s.type == 'anh') {
        content = '<img src="' + s.img + '" style="width:60px;position:absolute;top:0px;left:0px;right:0px;bottom:0px;margin:auto;" />';
    } else {
        content = '<div id="loadThoigian" style="width:60px;position:absolute;top:0px;left:0px;right:0px;bottom:0px;';
        content += 'margin:auto;height:60px;font-size:30px;border:#ccc 5px solid;border-radius:30px;font-weight:bold;text-align:center;color:#ccc;"></div>';
    }
    var html = '<div class="DD_loadingfull" style="position:fixed;height:100%;width:100%;background-color:rgba(255,255,255,0.4);top:0px;left:0px;z-index:100000;">'
            + content
            + '</div>';
    jQuery('body').append(html);
    handle = false;
    if (s.type != 'anh') {
        var timeload = 0;
        jQuery('#loadThoigian').html(timeload + 's');
        var handle = setInterval(function () {
            timeload++;
            jQuery('#loadThoigian').html(timeload + 's');
        }, 1000);
    }
    if (s.check) {
        jQuery(document).ajaxComplete(function () {
            clearInterval(handle);
            jQuery('.DD_loadingfull').remove();
        });
    }
}

function getParamByLink(link, pr) {
    var result = {};
    if (link) {
        var paramStr = link.replace(/.*\?/gi, '');
        if (paramStr) {
            var param = paramStr.split('&');
            if (param.length) {
                var length = param.length;
                for (var i = 0; i < length; i++) {
                    var a = param[i].split('=');
                    result[a[0]] = a[1];
                    if (pr == a[0]) {
                        return a[1];
                    }
                }
            }
        }
    }
    return result;
}

function messageDOk(options) {
    var s = jQuery.extend({
        title: '',
        message: '',
        url: '/',
    }, options);
    var html = '';
    html += '<div id="messageDOk" style="position:fixed;width:100%;height:100%;background:#000;z-index:10000;top:0px;left:0px;">';
    html += '<div style="width:50%;height:200px;position:fixed;background:#FFF;top:0px;left:0px;bottom:0px;right:0px;margin:auto;border-radius:5px;font-size:15px;font-weight:bold;">';
    html += '<div style="margin:10px;border-bottom:#ccc 1px solid;overflow:hidden;padding:10px 0px;">';
    html += s.title;
    html += '</div>';
    html += '<div style="overflow:hidden;padding:10px;font-size:15px;line-height:23px;">';
    html += s.message;
    html += '</div>';
    html += '<div style="overflow:hidden;text-align:center;padding:10px;">';
    if (s.url != '') {
        html += '<a style="padding:5px 10px;background:green;border-radius:5px;color:#FFF;" href="' + s.url + '" ' + s.onclick + '>Ok</a>';
    }
    html += '</div>';
    html += '</div>';
    html += '</div>';
    jQuery('body').append(html);
    jQuery('body').on('click', '#messageDOk a', function () {
        jQuery(this).parents('#messageDOk').remove();
        if (!s.onclick) {
            return false;
        }
    })
}
function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function confirmModal(options) {
    var s = jQuery.extend({
        'closeHtml': 'Close',
        'confirmHtml': 'Confirm',
        'classCancel': 'btn al-cancel',
        'classConfirm': 'btn btn-default',
        'closeHtmlButton': 'Cancel',
        'close': function () {

        },
        'message': '',
        'confirm': function () {

        },
        'contentHtml': '<div style="margin-bottom:10px;"></div>'
                + '<button type="button" class=" close_modal">No</button>'
                + '<button type="button" class=" confirm_modal">Yes</button>',
    }, options);
    jQuery(document).LoPopUp(s);
    jQuery('#IDLOpopup').find('.close_modal').addClass(s.classCancel);
    jQuery('#IDLOpopup').find('.confirm_modal').addClass(s.classConfirm);
    jQuery('.modal-body div').html(s.message);
    jQuery('.close_modal').html(s.closeHtmlButton);
    jQuery('.confirm_modal').html(s.confirmHtml);
    jQuery('.confirm_modal').click(function (e) {
        s.confirm();
        jQuery('#IDLOpopup').modal('toggle');
        jQuery(window).unbind(e);
    });
    jQuery('.close_modal').click(function (e) {
        s.close();
        jQuery('#IDLOpopup').modal('toggle');
        jQuery(window).unbind(e);
    });
}



function loadingFull(options) {
    var s = jQuery.extend({
        img: HTTP_HOST + '/backend/web/img/indicator_blue_small.gif',
        type: 'anh',
        check: true,
    }, options);
    var content = '';
    if (s.type == 'anh') {
        content = '<img src="' + s.img + '" style="width:60px;position:absolute;top:0px;left:0px;right:0px;bottom:0px;margin:auto;" />';
    } else {
        content = '<div id="loadThoigian" style="width:60px;position:absolute;top:0px;left:0px;right:0px;bottom:0px;';
        content += 'margin:auto;height:60px;font-size:30px;border:#ccc 5px solid;border-radius:30px;font-weight:bold;text-align:center;color:#ccc;"></div>';
    }
    var html = '<div class="DD_loadingfull" style="position:fixed;height:100%;width:100%;background-color:rgba(255,255,255,0.4);top:0px;left:0px;z-index:100000;">'
            + content
            + '</div>';
    jQuery('body').append(html);
    handle = false;
    if (s.type != 'anh') {
        var timeload = 0;
        jQuery('#loadThoigian').html(timeload + 's');
        var handle = setInterval(function () {
            timeload++;
            jQuery('#loadThoigian').html(timeload + 's');
        }, 1000);
    }
    if (s.check) {
        jQuery(document).ajaxComplete(function () {
            clearInterval(handle);
            jQuery('.DD_loadingfull').remove();
        });
    }
}
var main_ajax_submit = false;
function MainAjax(options) {
    var s = jQuery.extend({
        type: 'POST',
        dataType: 'json',
        objText: false,
        titleAlert: 'Alert',
        titleError: 'Error log',
        autoShowAlert: false,
        autoShowError: true,
        submit: false,
        success: function (rs) {

        },
        error: function (rs) {
            
        },
        afterSuccess: function (rs) {

        },
    }, options);
    loadingFull();
    if (s.objText) {
        s.objText.loadingText();
    }
    var function_success = s.success;
    var function_error = s.error;
    var opt = {
        success: function (rs) {
            main_ajax_submit = false;
            function_success(rs);
            if (rs.code && rs.code != 200 && s.autoShowAlert) {
                jQuery(document).LoPopUp({
                    id: 'IDLOpopup_alert',
                    title: s.titleAlert,
                    contentHtml: rs.data,
                    cssDialog: 'modal-lg',
                    afterClose: function () {
                        jQuery('#IDLOpopup_alert').remove();
                    }
                });
            }
            s.afterSuccess(rs);
        },
        error: function(rs) {
            main_ajax_submit = false;
            function_error(rs);
            var contentHtml = '';
            if (rs.responseText) {
                contentHtml = rs.responseText;
            } else {
                contentHtml = rs;
            }
            if (s.autoShowError) {
                jQuery(document).LoPopUp({
                    id: 'IDLOpopup_error',
                    title: s.titleError,
                    contentHtml: contentHtml,
                    cssDialog: 'modal-lg',
                    afterClose: function () {
                        jQuery('#IDLOpopup_error').remove();
                    }
                });
            }
        }
    };
    var ajaxOption = jQuery.extend(s, opt);
    if(!ajaxOption.submit || (ajaxOption.submit && !main_ajax_submit)) {
        main_ajax_submit = true;
        jQuery.ajax(ajaxOption);
    }
}


function showmessageDialog(options) {
    jQuery('.showmessageDialog').remove();
    var seting = jQuery.extend({
        msg: "Successfull",
        type: "success",
        time: 5000,
        classname: "body"
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
    html += (seting.classname == 'body') ? 'style="position:absolute;margin:0px auto;padding:15px;float:left;max-width:800px;min-width:600px;z-index:1000000; border-color: #d6e9c6;border-radius:5px;top:-100px;"'
            : 'style="float:left;width:100%;padding:15px;border-color: #d6e9c6;border-radius:5px;background-color:#d6e9c6;z-index:1000000;"';
    html += '><button type="button" class="close" data-dismiss="alert" style="font-size:20px;" onclick="jQuery(\'#' + id + '\').remove();"><span aria-hidden="true">×</span></button>'
            + '<span style="margin-right:10px;color: #3c763d;float:left;font-weight:bold;">' + message + '<span>'
            + '</div>';
    if ((seting.classname == 'body'))
        jQuery(seting.classname).append(html);
    else
        jQuery(seting.classname).prepend(html);
    id = '#' + id;
    var left = (jQuery(window).width() - jQuery(id).width()) / 2;
    if (seting.classname == 'body') {
        jQuery(id).css({'left': left + 'px'});
        jQuery(id).css({'top': (jQuery(window).scrollTop() + 150) + 'px'});
    }

    jQuery(id).fadeOut(seting.time);
    setTimeout(function () {
        jQuery(id).remove();
    }, seting.time);
}

function checkDataYii2(data) {
    var msg = '';
    var i = 0;
    var aaa = [];
    jQuery.each(data, function (index, value) {
        var idname = '.field-' + index;
        if (jQuery(idname).length) {
            if (i == 0) {
                jQuery('html, body').animate({scrollTop: (jQuery(idname).offset().top - 200)}, 500);
            }
            jQuery(idname).addClass('has-error');
            jQuery(idname).find('.help-block').html(value);
            msg += value + '<br>';
            i++;
        } else {
            console.log(index + value);
        }
    });
}

function resetForm() {
    jQuery('.help-block').empty();
    jQuery('.has-error').removeClass('has-error');
}

function messageAlert(options){
    var settings = $.extend({
        'contentHtml'   : '<p></p><div class="col-sm-12" style="text-align:center;"><button type="button" class="btn btn-primary messageAlert" data-id="" data-type="">OK</button></div>',
        'success'       : function(that){
            
        },
        title           : 'Alert',
        closeX          : false,
        'redirectUrl'   : false,
        message         : 'Alert',
        modal_setting   : {
            keyboard    : false,
            backdrop    : 'static',
        },
    },options);
    
    
    jQuery(document).LoPopUp(settings);
    jQuery('#IDLOpopup .modal-header button').remove();
    if(settings.message)
        jQuery('#IDLOpopup').find('.modal-body p').html(settings.message);
    
    jQuery('.messageAlert').click(function(e){
        if(!settings.redirectUrl) {
            var that = jQuery(this);
            settings.success(that);
            jQuery('#IDLOpopup').modal('hide');
        }
        else {
            window.location.href = settings.redirectUrl;
        }
    });
}


function jsDefault() {
    jQuery('.D_loadingImg').loadImg();
//    jQuery('.D_tooltip').datatooltip();

}


jQuery.fn.datatooltip = function (options) {
    var s = jQuery.extend({
        html: '<div id="sticky{index}" class="atip"><img class="datatooltiptralai" src="/' + WEB_TYPE + '/img/grey.gif" data-src="{src}" alt="{alt}" /></div>',
    }, options);
    var that = jQuery(this);
    setTimeout(function () {
        jQuery('#mystickytooltip').html('');
        that.each(function (i) {
            var that = jQuery(this);
            that.attr('data-tooltip', 'sticky' + i);
            var src = that.attr('data-srca') ? that.attr('data-srca') : '';
            var alt = that.attr('alt') ? that.attr('alt') : '';
            var html = s.html.replace('{index}', i).replace('{src}', src).replace('{alt}', alt);
            jQuery('#mystickytooltip').append(html);
        });
        stickytooltip.init("*[data-tooltip]", "mystickytooltip");
    }, 1000);
}

jQuery.fn.loadImg = function (options) {
    var that = new Array();
    var $count = jQuery(this).length;
    var $i = 0;
    function loadImg(that) {
        if (that.attr('data-src')) {
            if (that.offset().top <= jQuery(window).scrollTop() + jQuery(window).height()) {
                jQuery('<img/>').load(function () {
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
            that.load(function () {
                $i++;
                if ($i == $count && options) {
                    options();
                }
            });
            return true;
        }
        return false;
    }
    jQuery(this).each(function (index) {
        that[index] = jQuery(this);
        loadImg(that[index]);
        jQuery(window).scroll(function (e) {
            if (loadImg(that[index])) {
                jQuery(this).unbind(e);
            }
        });
    });
};


jQuery.fn.vi = function (prop, value) {
    if (jQuery(this).length > 0) {
        return (value) ? jQuery(this).css(prop, value + 'px') : parseFloat(jQuery(this).css(prop).replace('px', ''));
    }
    else {
        return 0;
    }
}
jQuery.fn.heightTrue = function () {
    return jQuery(this).height() + jQuery(this).vi('margin-top') + jQuery(this).vi('margin-bottom') + jQuery(this).vi('padding-top') + jQuery(this).vi('padding-bottom') + jQuery(this).vi('border-top-width') + jQuery(this).vi('border-bottom-width');
}

/**
 * All width + margin-left + margin-right + padding-left + padding-right
 * @returns {jQuery}
 */
jQuery.fn.widthTrue = function () {
    return jQuery(this).width() + jQuery(this).vi('margin-left') + jQuery(this).vi('margin-right') + jQuery(this).vi('padding-left') + jQuery(this).vi('padding-right') + jQuery(this).vi('border-left-width') + jQuery(this).vi('border-right-width');
};
/**
 * Loading image 
 * @param {type} options
 * @returns {load scroll image}
 */

jQuery.fn.loadingText = function (str) {
    var that = jQuery(this);
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
    jQuery(document).ajaxComplete(function (e) {
        that.removeClass('disable');
        (that.val() != '') ? that.val(text) : that.html(text);
    });
    return true;
}


jQuery.fn.LoPopUp = function (options) {
    var settings = jQuery.extend({
        id: 'IDLOpopup',
        clearBefore: false,
        classModal: 'modal-fixed modal-verify',
        contentHtml: '',
        timeoutAppend: '',
        header: 'Delete',
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
        modal_setting: 'show',
        closeX: false,
        closeHtml: '×',
        headerHtml: '',
        afterClose: function () {

        },
    }, options);
    if (jQuery('#' + settings.id).length) {
        jQuery('#' + settings.id).remove();
    }
    var htmlP = '<div class="modal fade ' + settings.classModal + '" tabindex="-1" role="dialog" aria-hidden="true" id="' + settings.id + '">'
            + '<div class="modal-dialog ' + settings.cssDialog + ' ">'
            + '<div class="modal-content">'
            + '<div class="modal-header">'
            + '<button onclick="jQuery(this).closeAllPopup({id:\'' + settings.id + '\'}); return false;" type="button" class="close close_modal_business" aria-hidden="true">' + settings.closeHtml + '</button>'
            + settings.headerHtml
            + '<h4 class="modal-title" >' + settings.header + '</h4>'
            + '</div>'
            + '<div class="modal-body" style="width:100%;background-color:#FFF;float:left;"></div>'
            + '</div>'
            + '</div>'
            + '</div>';
    jQuery('body').append(htmlP);

    var objDialog = jQuery('#' + settings.id);
    if (settings.clearBefore) {
        objDialog.find('.modal-body').html('');
    }

    if (settings.closeX) {
        jQuery('.close_modal_business').remove();
    }

    objDialog.modal(settings.modal_setting);

    if (settings.loadingStart) {
        objDialog.find('.loading').show();
    }

    if (jQuery.trim(settings.contentHtml) != '') {
        if (jQuery.trim(settings.timeoutAppend) == '') {
            objDialog.find('.modal-body').html(settings.contentHtml);
            if (jQuery.isFunction(settings.afterSuccess)) {
                settings.afterSuccess.call(this);
            }
        } else {
            setTimeout(function () {
                objDialog.find('.modal-body').html(settings.contentHtml);
                if (jQuery.isFunction(settings.afterSuccess)) {
                    settings.afterSuccess.call(this);
                }
            }, settings.timeoutAppend);
        }
    }


    if (jQuery.trim(settings.title) != '') {
        objDialog.find('.modal-title').html(settings.title);
    }

    if (jQuery.trim(settings.top) != '') {
        objDialog.css('top', settings.top);
    }
    if (jQuery.trim(settings.width) != '') {
        objDialog.find('.modal-dialog ').css('width', settings.width);
    }
    if (jQuery.trim(settings.maxwidth) != '') {
        objDialog.find('.modal-dialog ').css('max-width', settings.maxwidth);
    }

    if (jQuery.trim(settings.minwidth) != '') {
        objDialog.find('.modal-dialog ').css('min-width', settings.minwidth);
    }

    if (jQuery.trim(settings.maxheight) != '') {
        objDialog.find('.modal-dialog ').css('max-height', settings.maxheight);
    }

    if (jQuery.trim(settings.minheight) != '') {
        objDialog.find('.modal-body ').css('min-height', settings.minheight);
    }
    if (jQuery.trim(settings.topchild) != '') {
        objDialog.find('.modal-dialog ').css('top', settings.topchild);
    }
    if (jQuery.trim(settings.left) != '') {
        objDialog.find('.modal-dialog ').css('left', settings.left);
    }
    if (jQuery.trim(settings.right) != '') {
        objDialog.find('.modal-dialog ').css('right', settings.right);
    }
    if (jQuery.trim(settings.bottom) != '') {
        objDialog.find('.modal-dialog ').css('bottom', settings.bottom);
    }
    if (jQuery.trim(settings.margin) != '') {
        objDialog.find('.modal-dialog ').css('margin', settings.margin);
    }
    if (jQuery.trim(settings.position) != '') {
        objDialog.find('.modal-dialog ').css('position', settings.position);
    }
    objDialog.on('hidden.bs.modal', function (e) {
        settings.afterClose();
    });
    return this;
};

jQuery.fn.closeAllPopup = function (options) {
    var settings = jQuery.extend({
        id: 'IDLOpopup',
    }, options);
    jQuery('#' + settings.id).modal('hide');
};

function resetForm() {
    jQuery('.help-block').empty();
    jQuery('.has-error').removeClass('has-error');
}

function refreshCaptcha() {
    jQuery.ajax({
        url: '/site/captcha?refresh=1',
        dataType: 'json',
        success: function (rs) {
            jQuery('.settings_captcha').each(function () {
                jQuery(this).attr('src', rs.url);
            });
        }
    });
}

function resetForm() {
    jQuery('.help-block').empty();
    jQuery('.has-error').removeClass('has-error');
}

function trim(str) {
    return str.replace(/^( )+|( )+$/gi, '');
}
function locdau(str)
{
    str = trim(str);
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/[^a-z0-9]/g, " ");
    str = str.replace(/[ ]+/g, ' ');
    str = trim(str);
    str = str.replace(/ /g, '-');
    return str;
}

function resize_popup() {
    setTimeout(function () {

        if (jQuery('.popgiohang').length && jQuery('.popgiohang_main').length) {
            if (jQuery('.popgiohang_main').height() + 20 > jQuery('.popgiohang').height()) {
                jQuery('.popgiohang').css({'overflow-y': 'scroll'});
                jQuery('.popgiohang_main').css({'float': 'left', 'width': '100%'});
            } else {
                jQuery('.popgiohang').removeAttr('style');
                jQuery('.popgiohang_main').removeAttr('style');
            }
        }
        if (jQuery('.product_compare_wrap').length && jQuery('#product_compare_table').length) {
            var length = jQuery('#product_compare_table tr').eq(0).find('td').length - 1;
            var width = 100 + 150 * length;
            if (width > jQuery('.product_compare_wrap').width()) {
                jQuery('.product_compare_wrap').css({'overflow-x': 'scroll'});
//                jQuery('#product_compare_table').width(width);
            } else {
                jQuery('#product_compare_table').removeAttr('style');
                jQuery('.product_compare_wrap').removeAttr('style');
            }
        }
    }, 100);
}
