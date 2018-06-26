//    var backLinkSys = null;
function resetWidth() {
    var widthWindow = $(window).width();
    if ($('.form-left').length > 0 && $('.form-right').length > 0 && widthWindow > 1700) {
        $('.form-right').width($('#main_content').width() - $('.form-left').widthTrue());
    }
}
var loadiframe = '0';
function rsWidth() {
    if ($('#sidebar2').width() > 100) {
        $('#main_parent').css({'margin-left': '43px'});
        $('#sidebar2').css({'width':'43px'});
    } else {
        $('#main_parent').css({'margin-left': '250px'});
        $('#sidebar2').css({'width':'250px'});
    }
}

function rsWidthBegin() {
        $('#sidebar2').css({'transition':'0.5s'});
    if ($('#sidebar2').width() > 100) {
        $('#main_parent').css({'margin-left':  '250px','transition':'0.5s'});
        $('#sidebar2').css({'width':'250px'});
    } else {
        $('#main_parent').css({'margin-left':  '43px','transition':'0.5s'});
        $('#sidebar2').css({'width':'43px'});
    }
}

function jUrlHistory(baseUrl, param, title, log) {
    var currUrl = $('#store-current-url').val();
    if (baseUrl == '') {
        var baseUrl = currUrl;
    }
    try {

        baseUrl = baseUrl.replace(/\/$/, '');
        var p = param && Object.keys(param).length ? (baseUrl.split('?')[1] ? '&' : '?') + $.param(param) : '';
        if ($.trim(title) == '') {
            title = $(document).find("title").text();
        }
        if (!log) {
            log = param;
        }
        History.pushState(log, title, baseUrl + p);
    }
    catch (err) {
    }
}

$(document).ready(function(e) {

    $('body').on('click','.view_invoice',function(e){
        if($(this).closest('.table-bordered.table-hover').length) {
            var invoice_id = $(this).closest('tr').find('.invoice_id').html();
        } else {
            var invoice_id = $(this).closest('.user_item').find('.invoice_id').html();
        }
        MainAjax({
            url         : $(this).data('href'),
            success     : function(rs) {
                $(document).LoPopUp({
                    title       : 'Invoice: ' + invoice_id,
                    headerHtml  : '<a id="modal_print" class="fa fa-print" href="javascript:void(0);"></a>',
                    cssDialog   : 'modal-lg',
                    contentHtml : rs.html,
                });
            }
        });
        return false;
    })
    
    $('body').on('click','#modal_print',function(e){
        window_printfile('#IDLOpopup .modal-body', '#IDLOpopup .modal-title','/webadmanager/main/print');
        $('#IDLOpopup').closeAllPopup();
        return false;
    });

    $('body').on('click','.updateuser_information',function(e){
        MainAjax({
            url         : $(this).data('href'),
            success     : function(rs) {
                $(document).LoPopUp({
                    title       : 'Update information',
                    contentHtml : rs.html,
                });
            }
        });
    })

    var href = location.href;
    try {
        History.replaceState({state: new Date().getTime() / 1000}, null, href);
        window.onpopstate = function(event) {
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

    $('body').on('click', '.IframeEdit', function(e) {
        var that = $(this);
        var title = that.data('title');
        IframeEdit(that, title, function(parent) {

        });
        return false;
    });

    resetAjax();
    resetWidth();
    rsWidthBegin();
    $(window).resize(function(e) {
        resetWidth();
    });

    $('body').on('mousemove', '.setting_tooltip', function(e) {
        var index = $('.setting_tooltip').index($(this));
        $('.datatooltiptralai').eq(index).attr('src', $('.datatooltiptralai').eq(index).data('src'));
    });


    $('body').on('click', '.sidebar-toggle.sidebar-collapse', function(e) {
        rsWidth();
    });

    $('body').on('click', '#sidebar2 a', function(e) {
        if ($(this).data('onclick') && $(this).data('onclick') == '1') {

        } else {
            $('#sidebar2 li').removeClass('active');
            $(this).closest('li').addClass('active');
            $(this).closest('li').parent().closest('li').addClass('active');
            loadAjaxGridView($(this).attr('href'));
            return false;
        }

    });

    $('body').on('click', '.backendgridview .table-bordered thead tr th a,.create_record,.controlUpdate, .D_cancel, #buildgrid_link, #buildform_link, .breadcrumbsa, .setting_attsearch, .D_gridlink, .D_loadajax', function(e) {
        if ($(this).data('onclick') && $(this).data('onclick') == '1') {

        } else {
            loadAjaxGridView($(this).attr('href'));
            return false;
        }
    });

    $('body').on('click', '.status_click', function(e) {
        var table = $(this).data('table');
        var primaryKey = $(this).data('primarykey');
        var id = $(this).val();
        var statusName = $(this).data('statusname');
        var cl = $(this).data('class');
        if (table && primaryKey && id && !primaryKey.match(/,/gi)) {
            $.get('status', {table: table,class: cl, primaryKey: primaryKey, id: id, statusName: statusName, value: $(this).prop('checked') ? 1 : 0});
        }
    });

    $('body').on('click', '.status_a_click', function(e) {
        var that = $(this);
        var table = $(this).data('class');
        var primaryKey = $(this).data('primarykey');
        var id = $(this).data('value');
        var statusName = $(this).data('statusname');
        if (table && primaryKey && id && !primaryKey.match(/,/gi)) {
            confirmModal({
                title: 'Change status',
                confirmHtml: 'Change',
                message: 'Do you want to ' + that.data('label' + (parseInt(that.attr('data-val')) ? 0 : 1)).toLowerCase() + ' this item?',
                confirm: function() {
                    MainAjax({
                        url: 'statusa',
                        data: {table: table, primaryKey: primaryKey, id: id, statusName: statusName},
                        success: function(rs) {
                            if (rs.code == 200) {
                                that.attr('data-val', rs.data);
                                that.html(that.data('label' + rs.data));
                                notif({
                                    msg: 'Change status successfully!',
                                });
                            }
                        }
                    });


                },
            });
        }
    });

    $('body').on('click', '#main_content .pagination li a', function(e) {
        if (!$(this).parent().hasClass('active')) {
            loadAjaxGridView($(this).attr('href'));
        }
        return false;
    });

    $('body').on('change', '#change_pagesize', function(e) {
        loadAjaxGridView($(this).val());
    })

    $('body').on('change', '#main_content .render_filter input,#main_content .render_filter select', function(e) {
        e.stopPropagation();
        e.preventDefault();
        loadAjaxGridView(loadUrlSearch());
        return false;
    });


    $('body').on('click', '.range_inputs .applyBtn', function(e) {
        setTimeout(function() {
            if ($('#IDLOpopup').css('display') == 'block') {
                loadAjaxGridViewPopUp(loadUrlSearchModal());
            } else {
                loadAjaxGridView(loadUrlSearch());
            }
        }, 20);
    });

    $('body').on('keypress', '#main_content .render_filter input', function(e) {
        if (e.which == 13) {
            e.stopPropagation();
            e.preventDefault();
            loadAjaxGridView(loadUrlSearch());
            return false;
        }
    });

    $('body').on('click', '#IDLOpopup .pagination li a', function(e) {
        if (!$(this).parent().hasClass('active')) {
            loadAjaxGridViewPopUp($(this).attr('href'));
        }
        return false;
    });

    $('body').on('change', '#IDLOpopup .render_filter input,#IDLOpopup .render_filter select', function(e) {
        e.stopPropagation();
        e.preventDefault();
        loadAjaxGridViewPopUp(loadUrlSearchModal());
        return false;
    });

    $('body').on('click', '#IDLOpopup .range_inputs .applyBtn', function(e) {
        setTimeout(function() {
            loadAjaxGridViewPopUp(loadUrlSearchModal());
        }, 20);
    });

    $('body').on('keypress', '#IDLOpopup .render_filter input', function(e) {
        if (e.which == 13) {
            e.stopPropagation();
            e.preventDefault();
            loadAjaxGridViewPopUp(loadUrlSearchModal());
            return false;
        }
    });

    $('body').on('click', '.select-on-check-all', function(e) {
        $(this).closest('table').find('.gridChexbox').prop('checked', $(this).prop('checked'));
    });

    $('body').on('click', '.D_message_ajax', function(e) {
        var that = $(this);
        MainAjax({
            url: HTTP_HOST_MAIN_ROUTE + (that.data('href') ? that.data('href') : that.attr('href')),
            type: 'GET',
            dataType: 'json',
            success: function(rs) {
                if (rs.code == 200) {
                    $(document).LoPopUp({
                        title: that.data('title'),
                        contentHtml: rs.data,
                        cssDialog: 'modal-lg',
                    });
                }
            },
        });
    });
    
    $('body').on('change','.ace_datachoice',function(e){
        var obj = $(this).parent().parent().parent().find('input[type="text"]');
        obj.val($(this).val());
    });

    $('body').on('click', '.D_confirm_ajax', function(e) {
        var that = $(this);
        confirmModal({
            title: that.data('title'),
            message: that.data('message'),
            confirm: function() {
                var clinterval = false;
                MainAjax({
                    url: that.data('href') ? that.data('href') : (that.attr('datahref') ? that.attr('datahref') : that.attr('href')),
                    type: 'GET',
                    dataType: 'json',
                    success: function(rs) {
                        if (rs.code == 200) {
                            notif({
                                type: 'success',
                                position: 'bottom',
                                msg: rs.data ? rs.data : that.data('success'),
                            });
                            loadAjaxGridView(window.location.href);
                            if (that.hasClass('d_con_log')) {
                                clearInterval(clinterval);
                                $('#IDLOpopup').modal('hide');
                            }
                        }
                    },
                    error: function() {
                        clearInterval(clinterval);
                        $('#IDLOpopup').modal('hide');
                    },
                });
                if (that.hasClass('d_con_log')) {
                    setTimeout(function() {
                        $('.DD_loadingfull').remove();
                        $(document).LoPopUp({
                            title: 'Log',
                            content: '',
                            cssDialog: 'modal-lg',
                        });
                        $('#IDLOpopup .modal-body').css({height: '500px', overflow: 'scroll'});
                        var height = 0;
                        clinterval = setInterval(function() {
                            height += 500;
                            if (!$('#body_iframe_log').length) {
                                $('body').append("<iframe id='body_iframe_log' src='" + HTTP_HOST + '/admin/tool/loadtxt' + "' style='position:fixed;left:-100000px;' />");
                            } else {
                                $('#body_iframe_log').attr('src', HTTP_HOST + '/admin/tool/loadtxt');
                            }
                            var content = $('#IDLOpopup .modal-body').html();
                            var content_log = $('#body_iframe_log').contents().find('body').html();
                            var content_log = $('<div/>').html(content_log).text();
                            content_log = content_log.nl2br();
                            if (content != content_log) {
                                $('#IDLOpopup .modal-body').html(content_log);
//                                $('#IDLOpopup .modal-body').animate({scrollTop: height}, 100);
                            }

                        }, 2000);
                    }, 500);
                }
            },
        });
    })


    /*END SHOW COLUMN*/
    $('body').on('click', '.arrangeodr', function(e) {
        loadingFull();
        $.ajax({
            url: $(this).attr('href'),
            async: false,
            dataType: 'json',
            type: 'POST',
            'success': function(rs) {
                $(document).LoPopUp({
                    title: 'Order',
                    contentHtml: rs.data,
                    maxheight: 'auto',
                    afterClose: function() {
                        loadMenu();
                        setTimeout(function() {
                            $('#IDLOpopup').remove();
                        }, 600);
                    },
                });
            },
        });
        return false;
    });

    $('body').on('click', '.deleteall', function(e) {
        var ids = [];
        var that = $(this);
        var obj = that.closest('.col-sm-12').next();
        var i = 0;
        obj.find('.gridChexbox').each(function(e) {
            if ($(this).prop('checked')) {
                ids[i] = $(this).val();
                i++;
            }
        });
        if (i) {
            confirmModal({
                title: 'Delete all',
                message: 'Do you want to delete these records?',
                confirm: function() {
                    MainAjax({
                        url: that.attr('href'),
                        data: {ids: ids},
                        type: 'POST',
                        dataType: 'json',
                        success: function(rs) {
                            if (rs.code == 200) {
                                notif({
                                    type: 'success',
                                    position: 'bottom',
                                    msg: rs.data ? rs.data : 'Delete successfully!',
                                });
                                loadAjaxGridView(window.location.href);
                            } else {
                                $(document).LoPopUp({
                                    title: 'Error',
                                    contentHtml: rs.data,
                                });
                            }
                        },
                    });
                },
            });
        }
        return false;
    });


    $('body').on('click', '.copyall', function(e) {
        var ids = [];
        var that = $(this);
        var obj = that.closest('.col-sm-12').next();
        var i = 0;
        obj.find('.gridChexbox').each(function(e) {
            if ($(this).prop('checked')) {
                ids[i] = $(this).val();
                i++;
            }
        });
        if (i) {
            confirmModal({
                title: 'Copy all',
                message: 'Do you want to copy these records?',
                confirm: function() {
                    loadingFull();
                    MainAjax({
                        url: that.attr('href'),
                        data: {ids: ids},
                        type: 'POST',
                        dataType: 'json',
                        success: function(rs) {
                            if (rs.code == 200) {
                                notif({
                                    type: 'success',
                                    position: 'bottom',
                                    msg: rs.data ? rs.data : 'Copy successfully!',
                                });
                                loadAjaxGridView(window.location.href);
                            } else {
                                $(document).LoPopUp({
                                    title: 'Error',
                                    contentHtml: rs.data,
                                });
                            }
                        },
                    });
                },
            });
        }
        return false;
    });

    /*BEGIN CHOICE*/
    $('body').on('click', '.setting_index_choice', function(e) {
        e.preventDefault();
        var that = $(this);
        confirmModal({
            title: that.html(),
            closeHtml: "Cancel",
            confirmHtml: "Update",
            message: "Do you want to " + that.html().toLowerCase() + '?',
            close: function() {
            },
            confirm: function() {
                var data = [], i = 0;
                $('.gridChexbox').each(function(index) {
                    if ($(this).prop('checked')) {
                        data[i] = $(this).val();
                        i++;
                    }
                });
                if (data.length > 0) {
                    MainAjax({
                        url: that.attr('href'),
                        data: {ids: data},
                        type: 'POST',
                        success: function(rs) {
                            if (rs.code == 200) {
                                notif({
                                    type: 'success',
                                    position: 'bottom',
                                    msg: 'Update successfully!',
                                });
                                loadAjaxGridView(window.location.href);
                            }
                        },
                    });
                }
            },
        });
    });

    $('body').on('click', '.controlDelete', function(e) {
        var that = $(this);
        confirmModal({
            title: 'Delete all',
            message: 'Do you want to delete this record?',
            confirm: function() {
                MainAjax({
                    url: that.attr('href'),
                    dataType: 'json',
                    type: 'POST',
                    success: function(rs) {
                        if (rs.code == 200) {
                            notif({
                                type: 'success',
                                position: 'bottom',
                                msg: rs.data ? rs.data : 'Delete successfully!',
                            });
                            loadAjaxGridView(window.location.href);
                        }
                    },
                });
            },
        });
        return false;
    });

    /*FORM SUBMIT UPDATE*/
    $('body').on('submit', '#form_update', function(e) {
        var that = $(this);
        resetForm();
        beforeFormSave();
        if (checkPleaseField('form_update')) {
            return false;
        }
        if (!$('#D_update_submit').loadingText())
            return false;
        MainAjax({
            'url': that.attr('action'),
            'type': 'POST',
            'dataType': 'json',
            'data': that.serialize(),
            'success': function(rs) {
                afterFormSave();
                if (rs.code == 200) {
                    resetError();
                    loadMenu();
                    notif({
                        type: "success",
                        msg: "Update successfully!",
                        position: "bottom",
                    });
                } else {
                    checkDataYii2(rs);
                }
            },
        });
        return false;
    });
    /*BEGIN*/

    /*FORM SUBMIT CREATE*/
    /*BEGIN*/
    $('body').on('submit', '#D_form_create', function(e) {
        resetForm();
        beforeFormSave();
        if (checkPleaseField('D_form_create')) {
            return false;
        }
        if (!$('#D_update_submit').loadingText())
            return false;
        var that = $(this);
        loadingFull();
        MainAjax({
            url: that.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: that.serialize(),
            success: function(rs) {
                if (rs.code == 200) {
                    resetError();
                    loadMenu();
                    notif({
                        type: "success",
                        msg: 'Create successfully!',
                        position: "bottom",
                    });
                    if (rs.data && rs.data != 'OK') {
                        $('.nav.nav-list li').removeClass('active');
                        var a = rs.data.split('?');
                        var obj = $('a[href="' + a[0].replace(/\/[a-z]+$/gi, '/index') + '"]').parent();
                        obj.addClass('active');
                        obj.parent().closest('li').addClass('active');
                        loadAjaxGridView(rs.data);
                    } else {
                        $('.D_cancel').click();
                    }
                } else {
                    checkDataYii2(rs);
                }
            },
        });
        return false;
    });
    /*END*/


    /*BEGIN SHOW COLUMN*/
    $('body').on('change', '.dataTables_wrapper .multiselect-container li', function(e) {
        var $input = $(this).find('input');
        var did = $input.val();
        var $href = $(this).parent().parent().prev().data('href') + '&id=' + did;
        var check1 = $input.prop('checked') ? 1 : 0;
        if (check1 == 0) {
            $('.column_' + did).addClass('dnone');
//            $('.search_filter_' + did).addClass('dnone');
        } else {
            $('.column_' + did).removeClass('dnone');
//            $('.search_filter_' + did).removeClass('dnone');
//            $('.search_filter_' + did).find('.chosen-container').width(200);
        }
        $.ajax({
            url: $href,
            type: 'GET',
            dateType: 'json',
        });
    });
    /*END SHOW COLUMN*/


    $('body').on('click', '.tableupdate', function(e) {
        var that = $(this);
        loadingFull();
        $.ajax({
            url: that.attr('href'),
            dataType: 'text',
            type: 'GET',
            success: function(rs) {
                confirmModal({
                    title: "Update fast",
                    closeHtml: 'Cancel',
                    confirmHtml: 'Update',
                    message: rs,
                    cssDialog: 'w1000',
                    confirm: function() {
                        var thet = $('#update_fast_form');
                        if (!$('.confirm_modal').loadingText())
                            return false;
                        beforeFormSave();
                        MainAjax({
                            url: thet.attr('action'),
                            data: thet.serialize(),
                            success: function(rss) {
                                if (rss.code == 200) {
                                    var $this = that.parent().find('.D_value');
                                    var html = $this.html();
                                    var name = that.data('tablename') + '[' + that.data('nameupdate') + ']';
                                    if (thet.find('input[name="' + name + '"]').length) {
                                        var obj = thet.find('input[name="' + name + '"]');
                                        html = trim(obj.val());
                                        if (obj.val() == '0') {
                                            html = '';
                                        } else {
                                            if (obj.next().hasClass('multimenu') && obj.next().find('li.active').length) {
                                                html = obj.next().find('li.active').html();
                                                if (obj.next().next().hasClass('multimenu') && obj.next().next().find('li.active').length) {
                                                    html += ',' + obj.next().next().find('li.active').html();
                                                }
                                                if (obj.next().next().next().hasClass('multimenu') && obj.next().next().next().find('li.active').length) {
                                                    html += ',' + obj.next().next().next().find('li.active').html();
                                                }

                                            }

                                            if (obj.parent().hasClass('setting_radio')) {
                                                html = $('.setting_radio input[type="radio"]:checked').next().html();
                                            }
                                        }

                                    } else if (thet.find('select[name="' + name + '"]').length) {

                                        var obj = thet.find('select[name="' + name + '"]');
                                        if (obj.val() == 0 || obj.val() == '') {
                                            var html = '';
                                        } else {
                                            var html = '';
                                            obj.find('option').each(function(i) {
                                                if ($(this).attr('value') == obj.val()) {
                                                    html += ',' + $(this).html();
                                                }
                                            });
                                            html = html.replace(',', '');
                                        }

                                    } else if (thet.find('textarea[name="' + name + '"]').length) {
                                        var obj = thet.find('textarea[name="' + name + '"]');
                                        html = trim(obj.val());
                                    } 
                                    
                                    if ($this.find('.admin_image').length) {
                                        var html = '<img src="' + rss.data.link_30 + '" data-srca="' + rss.data.link_main + '" class="admin_image setting_tooltip D_loadingImg" />';
                                        $this.html(html);
                                        $('.setting_tooltip').datatooltip();
                                    } else {
                                        $this.html(html);
                                    }
                                    notif({
                                        type: 'success',
                                        msg: 'Update fast successfully!',
                                        position: 'bottom',
                                    });
                                } else {
                                    checkDataYii2(rss);
                                }
                            }
                        });
                    },
                    afterClose: function() {
                        setTimeout(function() {
                            $('#IDLOpopup').remove();
                        }, 200);
                    },
                });
                setTimeout(function() {
                    resetAjax();
                }, 500);
            },
        });
        return false;
    });

    $('body').on('click', '.controlView', function(e) {
        loadingFull();
        $.ajax({
            type: 'success',
            dataType: 'text',
            url: $(this).attr('href'),
            success: function(rs) {
                $(document).LoPopUp({
                    'title': '',
                    'contentHtml': rs,
                    'maxheight': 'auto',
                    cssDialog: 'modal-lg',
                });
            },
            error: function(rs) {
                $(document).LoPopUp({
                    'title': 'Error',
                    'contentHtml': rs,
                    'maxheight': 'auto',
                });
            },
        });
        return false;
    });

    $('body').on('click', '.controlCopy', function(e) {
        e.preventDefault();
        var that = $(this);
        confirmModal({
            'header': 'Copy',
            'closeHtml': 'Cancel',
            'confirmHtml': 'Copy',
            'message': 'Do you want to copy this record?',
            'close': function() {
            },
            'confirm': function() {
                MainAjax({
                    url: that.attr('href'),
                    type: 'POST',
                    dataType: 'json',
                    success: function(rs) {
                        if (rs.code == 200) {
                            notif({
                                type: 'success',
                                position: 'bottom',
                                msg: 'Copy successffuly!',
                            });
                            loadAjaxGridView(window.location.href);
                        } else {
                            $(document).LoPopUp({
                                title: 'Error',
                                contentHtml: rs.data,
                            });
                        }
                    },
                    'error': function(rs) {
                        notif({
                            type: 'success',
                            position: 'bottom',
                            msg: rs,
                        });
                    }
                });
            },
        });
    });



    $('body').on('click', '.D_replacecontent .D_left p', function(e) {
        var val = $.trim($(this).html());
        var editor = CKEDITOR.instances['D_question'];
        editor.insertHtml('<img src="/image.php?text=' + val + '" />');
    });

    $('body').on('click', '.D_replaceattribute .D_left p', function(e) {
        var flag = true;
        var val = $.trim($(this).html()) + ' <i class="fa fa-times"></i>';
        if ($('span.D_question-text.active').length) {
            $('span.D_question-text.active').html(val);
            flag = false;
        } else {

            if ($('.D_wrapper input.D_question-text.active').length) {
                var obj = $('.D_wrapper input.D_question-text.active');
                var valInput = obj.val();
                var length = valInput.length;
                var position = obj.getCursorPosition();

                if (position != length) {
                    var val1 = valInput.substr(0, position);
                    var val2 = valInput.substr(position, length);
                    obj.after('<span class="D_question-text">' + val + '</span><input type="text" class="D_question D_question-text" />');
                    obj.val(val1);
                    var obj2 = obj.next().next();
                    obj2.val(val2);
                    obj2.focus();
                    $('.D_display-input input').each(function(e) {
                        canculatorWidth($(this));
                    });
                    flag = false;
                }

            }
        }
        if (flag) {

            if ($('.D_question').eq($('.D_question').length - 1).val() == "") {
                $('.D_question').eq($('.D_question').length - 1).remove();
            }
            $('.D_display-input').append('<span class="D_question-text">' + val + '</span><input type="text" class="D_question D_question-text" />');
            setTimeout(function() {
                $('.D_question').eq($('.D_question').length - 1).focus();
            }, 10);
        }
        result();

    });

    $('body').on('click', '.D_wrapper span.D_question-text .fa', function(e) {
        var next = $(this).parent().next();
        var prev = $(this).parent().prev();
        if (prev.hasClass('D_question')) {
            var val = prev.val() + ' ' + next.val();
            val = val.replace(/(\s)+/gi, ' ');

            prev.val(val);
            next.remove();
            canculatorWidth(prev);
        }
        $(this).parent().remove();
        result();
    });

    $('body').on('keydown', '.D_wrapper input.D_question', function(e) {
        canculatorWidth($(this));
        result();
    });

    $('body').on('focus', '.D_wrapper input.D_question', function(e) {
        $('.D_wrapper input.D_question').removeClass('active');
        $(this).addClass('active');
    });

    $('body').on('click', '.D_wrapper .D_display-input-right', function(e) {
        $('.D_question').eq($('.D_question').length - 1).focus();
    });

    $('body').on('click', '.D_wrapper .D_ohidden input,.D_wrapper .D_ohidden span', function(e) {
        e.preventDefault();
        e.stopPropagation();
    })

    $('body').on('click', '.D_wrapper .D_ohidden', function(e) {
        var length = $(this).find('input').length;
        $(this).find('input').eq(length - 1).focus();
    });
    
    $('body').on('click','.create_multi_record',function(e){
        var that = $(this);
        MainAjax({
            url: that.attr('href'),
            type: 'GET',
            dataType: 'text',
            success: function(rs) {
                $(document).LoPopUp({
                    'title': 'Multi add ' + $('h1').text().replace(/\([0-9]+\)/gi,'').toLowerCase(),
                    'contentHtml': rs,
                    'maxheight': 'auto',
                    cssDialog: 'w1200',
                });
                resetAjax();
                $('.panel-collapse.collapse').addClass('in');
            },
        });
        return false;
    });
    $('body').on('click','#add_create_multi_record',function(e){
        var length = $('.div_multi_create_record').length;
        var html = $('#template_multi_add').html().replace(/key_count_html/gi,length);
        $(this).before(html);
        resetAjax();
        $('.panel-collapse.collapse').addClass('in');
        return false;
    });
    
    $('body').on('click','.delete_multi_div_recod',function(e){
        if($('.div_multi_create_record').length) {
            $(this).closest('.div_multi_create_record').remove();
            return false;
        }
    })
    
    $('body').on('submit','#content_multi_add',function(e){
        var that = $(this);
        resetForm();
        MainAjax({
            url     : that.attr('action'),
            data    : that.serialize(),
            autoShowAlert : false,
            success : function(rs) {
                if(rs.code == 200) {
                    loadAjaxGridView(location.href);
                    $('#IDLOpopup').modal('hide');
                    setTimeout(function(){
                        $('#IDLOpopup').remove();
                    },500);
                } else if(rs.code == 300) {
                    var flag = true;
                    var length = rs.data.length;
                    for(var i = 0; i < length; i++) {
                        var thet = $('.div_multi_create_record').eq(i);
                        if(rs.data[i]) {
                            $.each(rs.data[i], function(index, value) {
                                var idname = '.field-' + index;
                                var obj = thet.find(idname);
                                if(obj.length) {
                                    if (flag) {
                                        $('html, body').animate({scrollTop: (obj.offset().top - 200)}, 500);
                                    }
                                    obj.addClass('has-error');
                                    obj.find('.help-block').html(value.join('<br />'));
                                    flag = false;
                                }
                            });
                        }
                    }
                    
                }
            }
        });
        return false;
    });
    

    /*BEGIN CLICK UPLOAD IMAGE CKEDITOR*/
    $('body').on('click', '.cke_button.cke_button__image.cke_button_off', function(e) {
        var index = $('.cke_button.cke_button__image.cke_button_off').index($(this));
        setTimeout(function() {
            loadImageCk(index);
        }, 100);
    });
    /*END CLICK UPLOAD IMAGE CKEDITOR*/
    
    $('body').on('click','#delete_cache',function(e){
        var that = $(this);
        confirmModal({
            confirmHtml: 'Delete',
            title       : 'Delete cache',
            message     : 'Do you want to delete cache?',
            confirm     : function() {
                MainAjax({
                    url     : that.attr('href'),
                    success : function(rs) {
                        if(rs.code == 200) {
                            notif({
                                msg     : 'Delete cache successfully!',
                            });
                        }
                    }
                });
            },
        });
        return false;
    });
});
function loadImageCk(index) {
    var ckcontent = $('.cke_dialog_contents').eq(index);
    $('.cke_dialog_contents').each(function(e) {
        if ($(this).parents('.cke_reset_all').css('display') == 'block')
            ckcontent = $(this);
    });
    var thatimages = ckcontent.find('input[type="text"].cke_dialog_ui_input_text').eq(0);
    if (thatimages.parent().find('.D_loadingImg').length == 0) {
        thatimages.parent().addClass('ckoimage');
        thatimages.oneimage({
            success: function(data, that, thisobj, index) {
                imageidimage();
            },
        });
    }
    if (!thatimages)
        setTimeout(function() {
            loadImageCk(index);
        }, 1500);
}


function canculatorWidthRight() {
    $('.D_wrapper .D_display-input-right').width($('.D_wrapper .D_ohidden').width() - $('.D_wrapper .D_display-input').width() - 10);
}

function canculatorWidth(obj) {
    $('#tester').html(obj.val());
    obj.width($('#tester').width() + 13);
}
function result() {
    var a = [];
    $('.D_question-text').each(function(i) {
        if ($(this).hasClass('D_question')) {
            a[i] = $.trim($(this).val());
        } else {
            a[i] = '{' + $.trim($(this).html()).replace(' <i class="fa fa-times"></i>', '') + '}';
        }
    });
    var val = $.trim(a.join(' '));
    $('#D_question').val(val);
    canculatorWidthRight();
}


function resetAjax() {

    if ($('.D_display-input').length) {
        $('.D_display-input input').each(function(e) {
            canculatorWidth($(this));
        });

        canculatorWidthRight();
        if ($('.D_left_parent').length) {
            $('.D_left_parent').perfectScrollbar({
                suppressScrollX: true,
                wheelSpeed: 30,
            });
        }
    }

    if ($('.setting_tokeninput').length > 0) {
        $('.setting_tokeninput').each(function(i) {
            var url = '/settings/access/tokeninput';
            var mapping_id = $(this).data('mapping_id');
            url += '?mapping_id=' + mapping_id;
            var id = $(this).attr('id');
            $(this).tokenInput(url, {
                "allowCreation": false,
                "cacheResults": false,
                "placeholder": $(this).attr('placeholder'),
                "id": id + i,
                "prePopulate": $(this).data('vl') ? $(this).data('vl') : [],
                "preventDuplicates": true,
                "theme": "facebook",
                "searchDelay": 300,
                "queryParam": "term",
                "minChars": 1,
                "hintText": "",
                "animateDropdown": false,
            });
        });


    }

    if ($('#displayColumn').length && loadiframe == '0') {
        var array = $('#displayColumn').val();
        $('#user-grid table thead > tr > th').each(function(e) {
            var cl = $(this).attr('class').match(/column_[0-9-]+/gi);
            if (cl && $.inArray(cl[0].replace('column_', ''), array) == -1) {
                $('.' + cl[0]).addClass('dnone');
            }
        });
    }


    $('.select-on-check-all, .Pcheckbox').checkbox();

    $('.setting_checkbox').setting_checkbox();

    $('.setting_chosen').chosen();
    $('.setting_chosen_nosearch').chosen({disable_search_threshold: 10});

    if ($('.setting_onoff').length)
        $('.setting_onoff').onoff();

    if ($('.setting_array').length)
        $('.setting_array').array();

    if ($('.setting_arrayjson').length)
        $('.setting_arrayjson').arrayjson();

    if ($('.setting_json').length)
        $('.setting_json').settingjson();

    if ($('.setting_arraymanyjson').length)
        $('.setting_arraymanyjson').arraymanyjson();
    
    if ($('.setting_landingpage').length)
        $('.setting_landingpage').landingpage();

    if ($('[data-toggle="tooltip"]').length)
        $('[data-toggle="tooltip"]').tooltip();
    if ($('.setting_multiselect').length) {
        $('.setting_multiselect').multiselect({
            enableFiltering: true,
            buttonClass: 'btn btn-white btn-primary',
            templates: {
                button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"></button>',
                ul: '<ul class="multiselect-container dropdown-menu"></ul>',
                filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
                filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
                li: '<li><a href="javascript:void(0);"><label></label></a></li>',
                divider: '<li class="multiselect-item divider"></li>',
                liGroup: '<li class="multiselect-item group"><label class="multiselect-group"></label></li>'
            }
        });
    }
    if (loadiframe == '1') {
        $('#IDLOpopup .column_-2').addClass('dnone');
    }
    if ($('#displayColumn').length) {
        $('#displayColumn').multiselect({
            enableFiltering: false,
            buttonClass: 'btn btn-white btn-primary',
            buttonText: function() {
                return $('#displayColumn').data('displayname') + '<b style="margin-left:40px;" class="fa fa-caret-down"></b>';
            },
            templates: {
                button: '<button type="button" style="width:150px;text-align:left;" class="multiselect dropdown-toggle" data-toggle="dropdown"></button>',
                ul: '<ul class="multiselect-container dropdown-menu"></ul>',
                filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
                filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
                li: '<li class="D_displaycolumn"><a href="javascript:void(0);" ><label></label></a></li>',
                divider: '<li class="multiselect-item divider"></li>',
                liGroup: '<li class="multiselect-item group"><label class="multiselect-group"></label></li>'
            }
        });
        $('#D-grid-view .multiselect-container.dropdown-menu').css({left: '-24px'});
    }
    if ($('textarea.setting_limited').length) {
        $('textarea.setting_limited').each(function(i) {
            if ($(this).attr('maxlength')) {
                $(this).inputlimiter({
                    remText: '%n character once...',
                    limitText: 'max : %n.'
                });
            }
        })
    }

    if ($('.setting_date-picker').length) {
        $('.setting_date-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
        }).next().on('click', function() {
            $(this).prev().focus();
        });
    }
    if ($('.setting_timepicker').length) {
        $('.setting_timepicker').timepicker({
            minuteStep: 1,
            showSeconds: false,
            showMeridian: false
        }).next().on('click', function() {
            $(this).prev().focus();
        });
    }
    if ($('.setting_daterangepicker').length) {
        $('.setting_daterangepicker').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            }
        }).prev().on('click', function() {
            $(this).next().focus();
        });
    }
    if ($('.setting_multimenu').length) {
        $('.setting_multimenu').each(function(i) {
            $(this).multimenu({
                url1: $(this).data('url1'),
                url2: $(this).data('url2'),
                classcommon: $(this).data('classcommon') ? $(this).data('classcommon') : '',
            });
        })
    }
    if ($('.setting_datetimepicker').length) {
        $('.setting_datetimepicker').datetimepicker().next().on('click', function() {
            $(this).prev().focus();
        });
    }
    if ($('.setting_colorpicker').length) {
        $('.setting_colorpicker').colorpicker();
    }
    if ($('.setting_customercolorpicker').length) {
        $('.setting_customercolorpicker').colorpicker();
    }
    if ($('.setting_ckeditor').length > 0 && !$('.setting_ckeditor').attr('ckeditor')) {
        CKEDITOR.replaceAll("setting_ckeditor");
        $('.setting_ckeditor').attr('ckeditor', 1);
    }
    if ($('.setting_ckeditor_small').length) {
        $('.setting_ckeditor_small').each(function(i) {
            if (!$(this).attr('ckeditor')) {
                $(this).attr('ckeditor', 1);
                var name = $(this).attr('name');
                var editor = CKEDITOR.instances['name'];
                if (editor) {
                    editor.destroy(true);
                }
                CKEDITOR.replace(name, {
                    height: '200px'
                });
            }
        });
    }
    if ($('.setting_menudacap').length) {
        $('.setting_menudacap').menudacap();
    }
    if ($('.setting_menudacapsanpham').length) {
        $('.setting_menudacapsanpham').menudacapsanpham();
    }
    if ($('.setting_multiallmenu').length) {
        $('.setting_multiallmenu').multiallmenu();
    }
    if ($('.setting_checkboxsmaill').length) {
        $('.setting_checkboxsmaill').checkboxsmall();
    }
    if ($('.setting_checkboxsmall').length) {
        $('.setting_checkboxsmall').checkboxsmall();
    }
    if ($('.setting_checkboxbig').length) {
        $('.setting_checkboxbig').checkboxbig();
    }
    if ($('.setting_onoff').length) {
        $('.setting_onoff').onoff();
    }
    if ($('.ajax_chosen').length) {
        $('.ajax_chosen').each(function(index) {
            var onchange = $(this).attr('onchange');
            var name = onchange.replace("loadAjaxName($(this),'", "");
            var name = name.replace("');", "");
            loadAjaxName($(this), name);
        });
    }
    if ($('.setting_icon').length) {
        $('.setting_icon').icon();
    }
    if ($('.setting_role').length) {
        $('.setting_role').role();
    }
    if (('.panel-collapse').length) {
        $('.panel-collapse').each(function(i) {
            if ($(this).data('status') != 'in') {
                $(this).removeClass('in');
            }
        });
    }
    resetLibrary();
    $('.setting_tooltip').datatooltip();
    $('.setting_loadingImg').loadImg();
    if ($('*[data-tooltip]').length) {
        stickytooltip.init("*[data-tooltip]", "mystickytooltip");
    }
    $('.ui-dialog').remove();
    $('.D_dragin').remove();
}

function resetLibrary() {
    if ($('.setting_oneswf').length) {
        $('.setting_oneswf').oneswf({
            success: function(data, that, thisobj, index) {
                imageidimage();
            },
        });
    }
    
    if ($('.setting_oneimage').length) {
        $('.setting_oneimage').oneimage({
            success: function(data, that, thisobj, index) {
                imageidimage();
            },
        });
    }

    if ($('.setting_manyimages').length) {
        $('.setting_manyimages').manyimages({
            success: function(data, that, thisobj, index) {
                imageidimage();
            },
        });
    }

    if ($('.setting_onefile').length) {
        $('.setting_onefile').onefile({
            success: function(data, that, thisobj, index) {
                fileidfile();
            },
        });
    }



    if ($('.setting_manyfiles').length) {
        $('.setting_manyfiles').manyfiles({
            success: function(data, that, thisobj, index) {
                fileidfile();
            },
        });

    }
    if ($('.setting_loadurl').length) {
        $('.setting_loadurl').loadvalue();
    }
    $('.setting_loadingImg').loadImg();
}


function loadAjaxGridView(url) {
    loadingFull({check: false});
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'text',
        success: function(rs) {
            loadiframe = '0';
            $('.DD_loadingfull').remove();
            $('#main_content').html(rs);
            jUrlHistory(url);
            $('#mystickytooltip').html('');
            jsDefault();
            resetAjax();
            if (rs == null)
                window.location.href = url;
        },
        error: function() {
            window.location.href = url;
        },
    });
    return false;
}

function loadAjaxGridViewPopUp(url) {
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'text',
        success: function(rs) {
            $('.DD_loadingfull').remove();
            $('#popup_loadajax').html(rs);
            jsDefault();
            resetAjax();
        },
    });
    return false;
}




function loadUrlSearch() {
    var array = window.location.href.split('?');
    var params = {};
    if (array.length == 2) {
        var a = array[1].split('&');
        var length = a.length;
        for (var i = 0; i < length; i++) {
            var b = a[i].split('=');
            params[b[0]] = b[1];
        }
    }
    $('#main_content .render_filter input,#main_content .render_filter select').each(function(e) {
        if ($(this).attr('name')) {
            params[$(this).attr('name')] = $(this).val();
        }
    });
    var url = array[0];
    var str = '';
    $.each(params, function(i, v) {
        if(v != "")
            str += '&' + i + '=' + v;
    });
    str = str.replace('&', '?');
    return url + str;
}


function loadUrlSearchModal() {
    var array = $('#IDLOpopup .backendgridview').data('url').split('?');
    var params = {};
    if (array.length == 2) {
        var a = array[1].split('&');
        var length = a.length;
        for (var i = 0; i < length; i++) {
            var b = a[i].split('=');
            params[b[0]] = b[1];
        }
    }
    $('#IDLOpopup .render_filter input,#IDLOpopup .render_filter select').each(function(e) {
        if ($(this).attr('name')) {
            params[$(this).attr('name')] = $(this).val();
        }
    });
    var url = array[0];
    var str = '';
    $.each(params, function(i, v) {
        str += '&' + i + '=' + v;
    });
    str = str.replace('&', '?');
    return url + str;
}

function checkShowPrice(that) {
    if (that.val() == 5) {
        $('#servicessearch-service_price').closest('.col-sm-12').hide();
        $('#servicessearch-service_price_json').closest('.col-sm-12').show();
    } else {
        $('#servicessearch-service_price').closest('.col-sm-12').show();
        $('#servicessearch-service_price_json').closest('.col-sm-12').hide();
    }
}

function beforeFormSave() {
    if ($('.setting_ckeditor, .setting_ckeditor_small').length) {
        $('.setting_ckeditor, .setting_ckeditor_small').each(function(e) {
            $(this).val(CKEDITOR.instances[$(this).attr('id')].getData());
        })
    }

    if ($('.setting_ckeditor_small_replace_content').length) {
        $('.setting_ckeditor_small_replace_content').each(function(e) {
            var val = CKEDITOR.instances[$(this).attr('id')].getData();
            if (val != "") {
                var a = val.match(/\<img src="\/image\.php\?text=([a-zA-Z0-9_])+" \/>/gi);
                for (var i = 0; i < a.length; i++) {
                    var r = $(a[i]).attr('src').replace('/image.php?text=', '');
                    val = val.replace(a[i], '{' + r + '}');
                }
                $(this).val(val);
            }
        });
    }
    var obj_image_setting = $('.setting_oneimage,.json_image,.json_swf,.setting_oneswf');
    if (obj_image_setting.length) {
        var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
        obj_image_setting.each(function(i, v) {
            var vOld = $(this).val();
            var vNew = vOld.replace(HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + 'main/', '');
            if (vOld != "" && vOld != vNew) {
                $(this).val(vNew);
            }
            if($(this).hasClass('json_image')) {
                $(this).change();
            }
            if($(this).hasClass('json_swf')) {
                $(this).change();
            }
        });
    }
    
    
    if($('.setting_landingpage').length) {
        $('.setting_landingpage').each(function(){
            $(this).change();
        })
    }
}

function afterFormSave() {
    if ($('.setting_oneimage,.json_image,.json_swf,.setting_oneswf').length) {
        var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
        $('.setting_oneimage,.json_image,.json_swf').each(function(i, v) {
            var value = $(this).val();
            if (value != "" && value.replace(HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + 'main/', '') == value) {
                $(this).val(HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + 'main/' + value);
            }
        });
    }
}

function loadMenu() {
    var url = window.location.href;
    if (url.match(/settings\/menuadmin\//gi, '')) {
        $('#sidebar').load(URL_MENUADMIN_LOAD);
    }
    if (url.match(/settings\/statistical\//gi, '')) {
        $('#left_detail').load(URL_STATISTICAL_LOAD);
    }
}

function checkPleaseField(idForm) {
    var array = $('#' + idForm).serialize().split('&');
    var flag = false;
    var count = array.length;
    var object = {};
    for (var i = 0; i < count; i++) {
        var a = array[i].split('=');
        if (!a[0].match(/^(fileid|fileiddelete|fileiddeletename|imageid|imageiddelete|imageiddeletename|urlb)$/gi))
            object[a[0]] = a[1];
    }
    $.each(object, function(i, v) {
        var i = decodeURIComponent(i);
        var obj = $('[name="' + i + '"]');
        var error = $('[name="' + i + '"]').closest('.col-sm-12').find('.D-form-label span').length;
        var id = '.field-' + obj.attr('id');
        if (obj.val() == "" && error && $(id).length) {
            $(id).addClass('has-error');
            $(id).find('.help-block').html('Please complete this field');
            if (!flag)
                $('html,body').animate({scrollTop: $(id).offset().top - 200}, 500);
            flag = true;
        }
    });
    return flag;
}


function IframeEdit(that, title, func_call) {
    var href = that.attr('href');
    href += (href.indexOf('?') != -1 ? '&' : '?') + 'loadiframe=1';
    var parent = that.parent();
    MainAjax({
        url: href,
        dataType: 'text',
        success: function(rs) {
            $(document).LoPopUp({
                title: title,
                contentHtml: rs,
                cssDialog: 'modal-lg100',
                afterClose: function() {
                    func_call(parent);
                },
            });
            setTimeout(function() {
                resetAjax();
            }, 500);
        },
    });
}

function checkjoinwidth() {
    
}
