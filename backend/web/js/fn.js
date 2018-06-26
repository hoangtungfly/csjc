
$.fn.checkbox = function(options) {
    var s = $.extend({
    }, options);
    var that = $(this);
    that.each(function(i) {
        var thi = $(this);
        if (!thi.parent().hasClass('pos-rel')) {
            thi.after('<label class="pos-rel"><span class="lbl"></span></label>');
            thi.next().prepend(thi);
            thi.addClass('ace');
        }
    });
};


$.fn.setting_checkbox = function(options) {
    $(this).each(function(index) {
        var that = $(this);

        if (!that.parent().hasClass('pos-rel')) {
            var $label = '';
            if (that.attr('type') != 'checkbox')
                $label = '<input type="checkbox" ' + (that.val() == 1 ? 'checked="true"' : '') + ' class="ace" onclick="$(this).prev().val($(this).prop(\'checked\') ? 1 : 0);" />';
            else
                that.addClass('ace');
            var $html = '<label class="pos-rel">' + $label + '<span class="lbl"></span></label>';
            that.after($html);
            var obj = that.next();
            obj.prepend(that);
        }
    });
}


$.fn.multimenu = function(options) {
    var setting = $.extend({
        url1: URL_MAPPING_MULTIMENU,
        url2: URL_MAPPING_MAPPING,
        classcommon: '',
    }, options);
    if (setting.url1 != "" && setting.url2 != "") {
        $(this).each(function(index) {
            var that = $(this);
            if (!that.next().hasClass('multimenu')) {
                var data = 'id=' + that.val() + '&classcommon=' + setting.classcommon;
                $.ajax({
                    url: setting.url1,
                    type: 'GET',
                    dataType: 'text',
                    data: data,
                    success: function(rs) {
                        that.after(rs);
                    }
                });

                that.parent().on('click', '.multimenu li', function(e) {
                    var that = $(this);
                    that.parent().parent().find('input[type="hidden"]').val(that.data('id'));
                    that.parent().find('li').removeClass('active');
                    that.addClass('active');
                    $.ajax({
                        url: setting.url2,
                        data: {id: that.data('id'), classcommon: setting.classcommon},
                        dataType: 'text',
                        type: 'POST',
                        success: function(rs) {
                            var pa = that.parent();
                            if (pa.next().next().next().length > 0) {
                                pa.next().next().next().remove();
                            }
                            if (pa.next().next().length > 0) {
                                pa.next().next().remove();
                            }
                            if (pa.next().length > 0) {
                                pa.next().remove();
                            }
                            that.parent().after(rs);
                        }
                    });
                });
            }
        });
    }

}


$.fn.onoff = function(options) {
    $(this).each(function(index) {
        var that = $(this);
        if (!that.parent().hasClass('D_oo')) {
            var $label = '<input type="checkbox" ' + (that.val() == 1 ? 'checked="true"' : '') + ' class="ace ace-switch ace-switch-4" onclick="$(this).prev().prop(\'value\',$(this).prop(\'checked\') ? 1 : 0);$(this).prev().change();" />';
            var $html = '<label class="D_oo">' + $label + '<span class="lbl"></span></label>';
            that.after($html);
            var obj = that.next();
            obj.prepend(that);
            obj.click(function(e) {
                var v = $(this);
                setTimeout(function(i) {
                    v.prev().change();
                });
            });
        }
    })
}


$.fn.array = function(options) {
    function htmlArray(a, pladeHolderArray, classArray, that) {
        var count = a.length;
        var html = '<div class="col-sm-12 setting_array_div" style="margin:0px 0px 10px 0px;padding:0px;">';
        if (that.data('chosen') && that.data('chosen') == '1') {
            var chosenstr = that.parent().prev().html();
            chosenstr = chosenstr.replace('<select ', '<select class="array_chosen array1" ');
            html += '<div style="padding-left:0px;" class="col-sm-3">' + chosenstr.replace('"' + a[0] + '"', '"' + a[0] + '"' + ' selected="selected" ') + '</div>';
        } else {
            html += '<div style="padding-left:0px;" class="col-sm-3"><input type="text" placeholder="' + (pladeHolderArray[0] != 'underfined' ? pladeHolderArray[0] : '') + '"  value=\'' + a[0] + '\' class="form-control col-sm-12 array1 ' + (classArray[0] != 'underfined' ? classArray[0] : '') + '" /></div>';
        }
        if (count > 2) {
            html += '<div style="padding-left:0px;" class="col-sm-3"><input type="text" placeholder="' + (pladeHolderArray[1] != 'underfined' ? pladeHolderArray[1] : '') + '"  value=\'' + a[1] + '\' class="form-control col-sm-12 array1 ' + (classArray[1] != 'underfined' ? classArray[1] : '') + '" /></div>';
            html += '<div style="padding-left:0px;" class="col-sm-4"><input type="text" placeholder="' + (pladeHolderArray[2] != 'underfined' ? pladeHolderArray[2] : '') + '"  value=\'' + a[2] + '\' class="form-control col-sm-12 array1 ' + (classArray[2] != 'underfined' ? classArray[2] : '') + '" /></div>';
        } else {
            html += '<div class="col-sm-7"><input type="text" placeholder="' + (pladeHolderArray[1] != 'underfined' ? pladeHolderArray[1] : '') + '"  value=\'' + a[1] + '\' class="form-control col-sm-12 array1 ' + (classArray[1] != 'underfined' ? classArray[1] : '') + '" /></div>';
        }
        html += '<div style="padding-right:0px;float:right;" class="col-sm-1"><button class="btn btn-danger remove_array fr" style="border:0px;">Delete</button></div>';
        html += '<div style="padding-right:0px;float:right;" class="col-sm-1"><button class="btn btn-primary add_array fr" style="border:0px;margin-right:5px;">Add</button></div>';
        html += '</div>';
        return html;
    }
    ;
    $(this).each(function(index) {
        var that = $(this);
        var count = that.data('count') ? that.data('count') : 2;
        var pladeHolder = that.data('placeholder') ? that.data('placeholder') : 'id,value';
        var className = that.data('class') ? that.data('class') : '';
        var pladeHolderArray = explode(',', pladeHolder);
        var classArray = explode(',', className);
        var html = '';
        if (that.prop('value') != '') {
            var a = explode("|", that.prop('value'));
            var $count = a.length;
            for (var i = 0; i < $count; i++) {
                var b = explode(',', a[i]);
                html += htmlArray(b, pladeHolderArray, classArray, that);
            }
        }
        var a = [];
        for (var i = 0; i < count; i++) {
            a[i] = '';
        }
        html += htmlArray(a, pladeHolderArray, classArray, that);
        var id = 'buttonArray' + index;
        html += '<button class="btn btn-primary" id="' + id + '" style="border:0px;">Add</button>';
        that.after(html);
        if ($('.array_chosen').length > 0) {
            $('.array_chosen').chosen({disable_search_threshold: 10});
        }
        $('#' + id).click(function(e) {
            e.preventDefault();
            $(this).before(htmlArray(a, pladeHolderArray, classArray, that));
            if ($('.array_chosen').length > 0) {
                $('.array_chosen').chosen({disable_search_threshold: 10});
            }
            return false;
        });
        that.parent().on('blur', '.array1', function(e) {
            var that = $(this).parent().parent().parent().find('input[type="hidden"]');
            arrayhtml(that);
        });
        that.parent().on('change', '.array1', function(e) {
            var that = $(this).parent().parent().parent().find('input[type="hidden"]');
            arrayhtml(that);
        });
        that.parent().on('click', '.remove_array', function(e) {
            var that = $(this).parent().parent().parent().find('input[type="hidden"]');
            $(this).parent().parent().remove();
            arrayhtml(that);
        });
        that.parent().on('click', '.add_array', function(e) {
            e.preventDefault();
            $(this).parent().parent().after(htmlArray(a, pladeHolderArray, classArray, that));
            if ($('.array_chosen').length > 0) {
                $('.array_chosen').chosen({disable_search_threshold: 10});
            }
            return false;
        });
    });
    function arrayhtml(that) {
        var str = '';
        var cc = that.parent().find('.setting_array_div').length;
        that.parent().find('.setting_array_div').each(function(index) {
            kt = true;
            var c = $(this).find('.array1').length;
            $(this).find('.array1').each(function(i) {
                if ($(this).prop('value') == '' && i == 0) {
                    kt = false;
                    str = str.substr(0, str.length - 1);
                }
                if (kt) {
                    str += $(this).prop('value').replace(/,/gi, '');
                    if (i < c - 1) {
                        str += ',';
                    }
                }
            });
            if (index < cc - 1) {
                str += '|';
            }
        });
        that.prop('value', str);
    }
}

$.fn.checkboxsmall = function(options) {
    var s = $.extend({
        change: function() {
        },
        success: function(that) {
        },
    }, options);
    $(this).each(function(e) {
        var that = $(this);
        var html = '';
        if (that.parent().find('.checkboxsmall').length == 0) {
            that.find('option').each(function(e) {
                var $id = $(this).val();
                var $name = $(this).html();
                var $select = ($(this).attr('selected')) ? 'active' : '';
                html += '<div class="checkboxsmall ' + $select + '" data-id="' + $id + '"><i class="fa fa-circle-o"></i> ' + $name + '</div>';
            });
            that.css('display', 'none');
            that.after(html);
            that.parent().on('click', '.checkboxsmall', function(e) {

                var kt = ($(this).hasClass('active')) ? true : false;
                $(this).parent().find('.checkboxsmall').removeClass('active');
                var $select = $(this).parent().find('select');

                if (!kt) {
                    $(this).addClass('active');
                    $select.val($(this).data('id'));
                }
                else {
                    $(this).parent().find('select').val(0);
                }
                checkResetInput($select);

                s.change($select);
            });
        }
    })
}
function checkResetInput(that) {
    if (that.data('change')) {
        var str = that.data('change');
        var a = explode(',', str);
        for (var i = 0; i < a.length; i++) {
            var obj = $('#' + a[i]);
            rsInput(obj);
        }
    }
}

function resetError() {
    $('.has-error').removeClass('has-error');
    $('.help-block').html('');
}

$.fn.checkboxbig = function(options) {
    $(this).each(function(e) {
        var that = $(this);
        that.parent().on('click', '.checkboxbig', function(e) {
            $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
            var strId = '';
            $(this).parent().find('.checkboxbig').each(function(index) {
                if ($(this).hasClass('active')) {
                    strId += $(this).data('id') + ',';
                }
            });
            $(this).parent().find('input[type="hidden"]').val(strId.substr(0, strId.length - 1));
        });
    })
}


$.fn.icon = function(options) {
    $(this).each(function(index) {
        var that = $(this);
        that.attr('autocomplete', 'off');
        if (that.data('href')) {
            $.ajax({
                async: false,
                url: $(this).data('href'),
                type: 'POST',
                dataType: 'json',
                success: function(rs) {
                    that.parent().css('position', 'relative');
                    var html = '<div class="Dicon col-sm-12">';
                    for (var key in rs.data) {
                        html += '<div class="col-sm-2 '
                                + (that.val() == rs.data[key] ? 'active' : '') + '"><i class="fa '
                                + rs.data[key] + '"></i> <span>' + rs.data[key] + '</span></div>';
                    }
                    html += '</div>';
                    that.after(html);
                    that.next().hide();
                    that.click(function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if ($(this).next().css('display') == 'none')
                            $(this).next().show();
                        else
                            $(this).next().hide();
                    });
                    that.next().click(function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    });
                    that.next().find('div').click(function(e) {
                        var val = $(this).find('span').html();
                        $(this).parent().prev().val(val);
                        $(this).parent().find('div').removeClass('active');
                        $(this).addClass('active');
                        $(this).parent().hide();
                    });
                },
            });
        }

        that.parents('body').click(function(e) {
            $('.Dicon').hide();
        });
    });
}

$.fn.role = function(options) {
    var settings = $.extend({
        url: URL_ROLE,
    }, options);
    $(this).each(function(e) {
        var that = $(this);
        $.ajax({
            async: false,
            type: 'POST',
            url: settings.url,
            dataType: 'json',
            data: 'id=' + that.data('mappingid') + '&value=' + that.val(),
            success: function(rs) {
                if (rs.code == 200) {
                    that.after(rs.data);
                    $('.setting_checkbox').checkbox();
                }
            },
        });
        that.parent().on('click', 'input[type=checkbox]', function(e) {
            var that = $(this);
            var parent = that.parent().parent().parent().first().children().eq(0).first().children();
            if (that.prop('checked')) {
                while (parent.hasClass('ace')) {
                    parent.prop('checked', that.prop('checked'));
                    parent = parent.parent().parent().parent().first().children().eq(0).first().children();
                }
            }
            totalAllRole($(this));
        });
    });
    function totalAllRole(obj) {
        var parent = obj.parents('.D_role_html');
        var str = '';
        parent.find('input[type="checkbox"]').each(function(e) {
            if ($(this).prop('checked')) {
                str += $(this).data('id') + ',';
            }
        });
        parent.prev().val(str.substr(0, str.length - 1));
    }

}

$.fn.datatooltip = function(options) {
    var s = $.extend({
        html: '<div id="sticky{index}" class="atip"><img class="datatooltiptralai" src="/img/grey.gif" data-src="{src}" alt="{alt}" /></div>',
    }, options);
    var that = $(this);
    setTimeout(function() {
        $('#mystickytooltip').html('');
        that.each(function(i) {
            var that = $(this);
            that.attr('data-tooltip', 'sticky' + i);
            var src = (that.attr('data-src') ? that.attr('data-src') : that.attr('src'));
            var alt = that.attr('alt');
            src = src.replace(/\/(\d)+x(\d)+\/|\/[wh](\d)+\//gi, '/main/');
            var html = s.html.replace('{index}', i).replace('{src}', src).replace('{alt}', alt);
            $('#mystickytooltip').append(html);
        });
        stickytooltip.init("*[data-tooltip]", "mystickytooltip");
    }, 1000);
}


$.fn.datatooltip2 = function(options) {
    var s = $.extend({
        html: '<div id="sticky{index}" class="atip"><img src="{src}" alt="{alt}" /></div>',
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
    }, 100);
}

function loadAjaxName(obj, id) {
    $.ajax({
        url: obj.data('href'),
        data: 'id=' + obj.val() + 'urlb=' + $('input[name="urlb"]').val(),
        type: 'POST',
        dataType: 'json',
        success: function(rs) {
            if (rs.code == 200) {
                var html = '';
                for (var key in rs.data) {
                    html += '<option value="' + key + '" ' + ($('#' + id).data('id') == key ? 'selected="selected"' : '') + '>' + rs.data[key] + '</option>';
                }
                $('#' + id).html(html);
                $('#' + id).chosen('destroy');
                $('#' + id).chosen();
            }
        },
    });
}

function loadAjaxChosen(obj, id) {
    var fieldid = $('#' + id).closest('.col-sm-12').data('fieldid');
    var arrayId = window.location.href.match(/\?id=([0-9]+)/gi);
//    if(arrayId.length) {
    var sst = arrayId ? arrayId[0] : '';
    var strid = sst.replace('?id=', '');
    var name = obj.attr('name').match(/\[([a-zA-Z0-9_-])+\]/gi);
    if (name.length) {
        var ssname = name[0];
        name = ssname.replace(/\[|\]/gi, '');
        $.ajax({
            url: obj.data('href'),
            data: 'value=' + obj.val() + '&fieldid=' + fieldid + '&id=' + strid + '&name=' + name,
            type: 'POST',
            dataType: 'json',
            success: function(rs) {
                if (rs.code == 200) {
                    var html = '';
                    for (var key in rs.data) {
                        html += '<option value="' + key + '" ' + ($('#' + id).data('id') == key ? 'selected="selected"' : '') + '>' + rs.data[key] + '</option>';
                    }
                    $('#' + id).html(html);
                    $('#' + id).chosen('destroy');
                    $('#' + id).chosen();
                }
            },
        });
    }
//    }
}


var index_json_content = 0;

$.fn.arraymanyjson = function(options) {
    function input_type(placeholder, cl, name, value, type) {
        value = encodeQuote(value);
        var html = '';
        switch (type) {
            case 'chosen':
                var chosenstr = $('#' + cl).html();
                chosenstr = chosenstr.replace('<select ', '<select class="json_chosen setting_array_many_json" data-namevalue="' + name + '" ');
                html = chosenstr.replace('"' + value + '"', '"' + value + '"' + ' selected="selected" ');
                break;
            case 'content':
                html = '<textarea data-namevalue="' + name + '" name="json_content_a_' + index_json_content + '" id="json_content_a_' + index_json_content + '" class="json_textarea setting_array_many_json json_content json_content_array ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                index_json_content++;
                break;
            case 'textarea':
                html = '<textarea data-namevalue="' + name + '" class="json_textarea dnone setting_array_many_json form-control col-sm-12 ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                break;
            case 'arraymany2':
                html = '<textarea data-namevalue="' + name + '" data-width="100%,100%" data-name="title,left" data-type="text,arraymany4" data-placeholder="Left,Left" class="json_textarea dnone json_arraymanjson setting_array_many_json form-control col-sm-12 ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                break;
//            case 'arraymany3':
//                html = '<textarea data-namevalue="' + name + '" data-width="1000,1000" data-name="title,questionanswer" data-type="text,arraymany4" data-placeholder="title,multi" class="json_textarea dnone json_arraymanjson setting_array_many_json form-control col-sm-12 ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
//                break;
            case 'arraymany4':
                html = '<textarea data-namevalue="' + name + '" data-width="100%,100%" data-name="question,answer" data-type="text,content" data-placeholder="Question,Answer" class="json_textarea dnone json_arraymanjson setting_array_many_json form-control col-sm-12 ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                break;
            case 'datetime':
                html = '<input type="hidden" value="' + value + '" data-namevalue="' + name + '" class="form-control col-sm-12 setting_array_many_json json_datetime' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'hidden':
                html = '<input type="hidden" value=\'' + value + '\' data-namevalue="' + name + '" class="form-control col-sm-12 setting_array_many_json ' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'number':
                value = (value < 1) ? 1 : value;
                html = '<input type="text" value=\'' + value + '\' data-namevalue="' + name + '" class="form-control col-sm-12 setting_array_many_json ' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'image':
                html = '<input type="text" value=\'' + value + '\' data-namevalue="' + name + '" class="fl form-control json_image setting_array_many_json ' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'onoff':
                html = '<input type="hidden" value=\'' + value + '\' data-namevalue="' + name + '" class="form-control col-sm-12 setting_array_many_json json_onoff ' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'swf':
                html = '<input type="text" value=\'' + value + '\' data-namevalue="' + name + '" class="fl form-control json_swf setting_array_many_json ' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'password' :
                html = '<input type="password" value=\'' + value + '\' data-namevalue="' + name + '" class="form-control col-sm-12 setting_array_many_json ' + cl + '" placeholder="' + placeholder + '" />';
            default:
                html = '<input type="text" value=\'' + value + '\' data-namevalue="' + name + '" class="form-control col-sm-12 setting_array_many_json ' + cl + '" placeholder="' + placeholder + '" />';
                break;
        }
        return html;
    }
    
    function resetJsonAjax() {
        if ($('.json_chosen').length > 0) {
            $('.json_chosen').chosen({disable_search_threshold: 10});
        }
        if ($('.json_onoff').length > 0) {
            $('.json_onoff').onoff();
        }
        if($('.json_arraymanjson').length) {
            $('.json_arraymanjson').arraymanyjson();
        }
        if ($('.json_datetime').length > 0) {
            $('.json_datetime').datetimepicker().next().on(ace.click_event, function() {
                $(this).prev().focus();
            });
        }
        if ($('.json_content_array').length > 0) {
            $('.json_content_array').each(function(i) {
                if (!$(this).attr('ckeditor')) {
                    $(this).attr('ckeditor', 1);
                    var name = $(this).attr('name');
                    var editor = CKEDITOR.instances['name'];
                    if (editor) {
                        editor.destroy(true);
                    }
                    CKEDITOR.replace(name, {
                        height: '150px'
                    });
                }
            });
        }
        if ($('.json_image').length > 0) {
            $('.json_image').oneimage({
                success: function(data, that, thisobj, index) {
                    imageidimage();
                },
            });
        }
        if ($('.json_swf').length > 0) {
            $('.json_swf').oneswf({
                success: function(data, that, thisobj, index) {
                    imageidimage();
                },
            });
        }
    }
    
    function htmlArray(value_json, that) {
        var nameArray = that.data('name') ? that.data('name').split(',') : ['id', 'value'];
        var pladeHolderArray = that.data('placeholder') ? that.data('placeholder').split(',') : ['id', 'value'];
        var clArray = that.data('cl') ? that.data('cl').split(',') : ['', ''];
        var typeArray = that.data('type') ? that.data('type').split(',') : ['', ''];
        var widthArray = that.data('width') ? String(that.data('width')).split(',') : ['', ''];

        var html = '<div class="col-sm-12 setting_array_div">';
        var count = nameArray.length ? nameArray.length : 1;

        for (var i = 0; i < count; i++) {
            var placeholder = pladeHolderArray[i] ? pladeHolderArray[i] : '';
            var cl = clArray[i] ? clArray[i] : '';
            var name = nameArray[i] ? nameArray[i] : '';
            var value = value_json[name] ? value_json[name] : '';
            var type = typeArray[i] ? typeArray[i] : '';
            var width = widthArray[i] ? widthArray[i] : '';
            var divStyle = width != "" ? 'style="width:' + width + (width.indexOf('%') > -1 ? '' : 'px') + ';"' : '';
            switch (count) {
                case 1:
                    html += '<div ' + divStyle + ' class="col-sm-10 plr0 ">' + input_type(placeholder, cl, name, value, type) + '</div>';
                    break;
                case 2:
                    html += '<div ' + divStyle + ' class="col-sm-5 ' + (i == 1 ? 'pr0' : 'pl0') + ' ">' + input_type(placeholder, cl, name, value, type) + '</div>';
                    break;
                case 3:
                    html += '<div ' + divStyle + ' class="col-sm-' + (i == 0 ? '4 pl0' : '3 pr0') + ' ">' + input_type(placeholder, cl, name, value, type) + '</div>';
                    break;
                case 4:
                    html += '<div ' + divStyle + ' class="col-sm-' + (i < 2 ? '3 pl0' : '2 pr0') + ' ">' + input_type(placeholder, cl, name, value, type) + '</div>';
                    break;
                case 5:
                    html += '<div ' + divStyle + ' class="col-sm-2 ' + (i == 4 ? ' pr0' : ' pl0') + ' ">' + input_type(placeholder, cl, name, value, type) + '</div>';
                    break;
                case 6:
                    html += '<div ' + divStyle + ' class="col-sm-' + (i > 4 ? '1' : '2') + ' ' + (i == 5 ? ' pr0' : ' pl0') + ' ">' + input_type(placeholder, cl, name, value, type) + '</div>';
                    break;
                case 7:
                    html += '<div ' + divStyle + ' class="col-sm-' + (i > 3 ? '1' : '2') + ' ' + (i == 6 ? ' pr0' : ' pl0') + ' ">' + input_type(placeholder, cl, name, value, type) + '</div>';
                    break;
                case 8:
                    html += '<div ' + divStyle + ' class="col-sm-' + (i > 2 ? '1' : '2') + ' ' + (i == 7 ? ' pr0' : ' pl0') + ' ">' + input_type(placeholder, cl, name, value, type) + '</div>';
                    break;
                case 9:
                    html += '<div ' + divStyle + ' class="col-sm-' + (i > 1 ? '1' : '2') + ' ' + (i == 8 ? ' pr0' : ' pl0') + ' ">' + input_type(placeholder, cl, name, value, type) + '</div>';
                    break;
                case 10:
                    html += '<div ' + divStyle + ' class="col-sm-1 ' + (i == 9 ? ' pr0' : ' pl0') + ' ">' + input_type(placeholder, cl, name, value, type) + '</div>';
                    break;
            }
        }
        html += '<div class="col-sm-1 setting_json_div_1"><button type="button" class="btn btn-danger remove_array fr" style="border:0px;">Delete</button></div>';
        html += '<div class="col-sm-1 setting_json_div_1"><button type="button" class="btn btn-primary add_array fr" style="border:0px;margin-right:5px;">Add</button></div>';
        html += '</div>';
        return html;
    };
    
    $(this).each(function(index) {
        var that = $(this);
        if(!that.hasClass('arraymanyjson')) {
            var html = '';
            that.addClass('arraymanyjson');
            if ($.trim(that.prop('value')) != '') {
                var main_value = JSON.parse(that.prop('value'));
                if(main_value) {
                    for (var i = 0; i < main_value.length; i++) {
                        html += htmlArray(main_value[i], that);
                    }
                } else {
                    html += htmlArray([], that);
                }
            } else {
                html += htmlArray([], that);
            }
            that.after(html);
            resetJsonAjax();
            that.parent().sortable({
            start: function(event,ui) {
                $(event.currentTarget).find('.json_content_array').each(function(){
                    if($(this).attr('ckeditor') == 1) {
                        CKEDITOR.instances[$(this).attr('name')].destroy(true);
                        $(this).removeAttr('ckeditor');
                    }
                })
            },
            stop: function(event, ui) {
                $(event.currentTarget).find('.json_content_array').each(function(){
                    if(!$(this).attr('ckeditor')) {
                        $(this).attr('ckeditor',1);
                        CKEDITOR.replace($(this).attr('name'), {
                            height: '150px'
                        });
                    }
                })
                arrayhtml(that);
            }});

            that.parent().on('change', '> .setting_array_div > div > .setting_array_many_json', function(e) {
                arrayhtml(that);
                if($(this).hasClass("json_chosen") && $(this).parent().parent().find('.price').length > 0) {
                    $(this).parent().parent().find('.price').val($(this).find("option:selected").data('price'));
                }

                var total = 0;
                that.parent().find('.setting_array_div').each(function(i) {
                    total = total + parseInt($(this).find('.number').val()) * parseFloat($(this).find('.price').val());
                });
                $('#ordersearch-sub_total').val(total);
                that.change();
            });
            that.parent().on('click', '> .setting_array_div > .col-sm-1 > .remove_array', function(e) {
                var pa = $(this).parent().parent().parent();
                var that = pa.find('.arraymanyjson');
                if (pa.find('.setting_array_div').length > 1) {
                    $(this).parent().parent().remove();
                    arrayhtml(that);
                }
                that.change();
            });

            that.parent().on('click', '> .setting_array_div > .col-sm-1 > .add_array', function(e) {
                e.preventDefault();
                $(this).parent().parent().after(htmlArray([], that));
                resetJsonAjax();
                var obj_div = that.parent().find('.setting_array_div');
                var ojb_last = obj_div.eq(obj_div.length - 1);
                if (ojb_last.length && ojb_last.find('.json_content').length) {
                    CKEDITOR.instances[ojb_last.find('.json_content').attr('id')].on('change', function() {
                        arrayhtml(that);
                    });
                }
                that.change();
                return false;
            });

            that.parent().find('> .setting_array_div > div > .json_content').each(function(i) {
                CKEDITOR.instances[$(this).attr('id')].on('change', function() {
                    arrayhtml(that);
                    that.change();
                });
            });
        }
    });
    function arrayhtml(that) {
        var array = [];
        var i = 0;
        that.parent().find('> .setting_array_div').each(function(index) {
            var array2 = {};
            $(this).find('> div > .setting_array_many_json').each(function(j) {
                var name = $(this).attr('data-namevalue');
                if ($(this).hasClass('json_content')) {
                    $(this).val(CKEDITOR.instances[$(this).attr('id')].getData());
                }
                var value = $(this).val();
                array2[name] = value;
            });
            array[index] = array2;
        });
        that.prop('value', JSON.stringify(array));
    }
}


$.fn.arrayjson = function(options) {
    function htmlArray(a, pladeHolderArray, classArray, that) {
        var count = a.length;
        var html = '<div class="col-sm-12 setting_array_div">';
        if (that.data('chosen') && that.data('chosen') == '1') {
            var chosenstr = that.parent().prev().html();
            chosenstr = chosenstr.replace('<select ', '<select class="array_chosen array1" ');
            html += '<div style="padding-left:0px;" class="col-sm-3">' + chosenstr.replace('"' + a[0] + '"', '"' + a[0] + '"' + ' selected="selected" ') + '</div>';
        } else {
            html += '<div style="padding-left:0px;" class="col-sm-3"><input type="text" placeholder="' + (pladeHolderArray[0] != 'underfined' ? pladeHolderArray[0] : '') + '"  value=\'' + a[0] + '\' class="form-control col-sm-12 array1 ' + (classArray[0] != 'underfined' ? classArray[0] : '') + '" /></div>';
        }
        html += '<div class="col-sm-7"><input type="text" placeholder="' + (pladeHolderArray[1] != 'underfined' ? pladeHolderArray[1] : '') + '"  value=\'' + a[1] + '\' class="form-control col-sm-12 array1 ' + (classArray[1] != 'underfined' ? classArray[1] : '') + '" /></div>';
        html += '<div style="padding-right:0px;float:right;" class="col-sm-1"><button type="button" class="btn btn-danger remove_array fr" style="border:0px;">Delete</button></div>';
        html += '<div style="padding-right:0px;float:right;" class="col-sm-1"><button type="button" class="btn btn-primary add_array fr" style="border:0px;margin-right:5px;">Add</button></div>';
        html += '</div>';
        return html;
    }
    ;
    $(this).each(function(index) {
        var that = $(this);
        if(!that.hasClass('hasArrayJon')) {
                that.addClass('hasArrayJon');
                var count = 2;
                var pladeHolder = that.data('placeholder') ? that.data('placeholder') : 'id,value';
                var className = that.data('class') ? that.data('class') : '';
                var pladeHolderArray = explode(',', pladeHolder);
                var classArray = explode(',', className);
                var html = '';
                var name1 = that.data('name1') && that.data('name1') != '' && that.data('name1') != 'undefined' ? that.data('name1') : 'label';
                var name2 = that.data('name2') && that.data('name2') != '' && that.data('name2') != 'undefined' ? that.data('name2') : 'value';
                if (that.prop('value') != '') {
                    var a = JSON.parse(that.prop('value'));
                    var $count = a.length;
                    var s = [];
                    for (var i = 0; i < $count; i++) {
                        s[0] = a[i][name1];
                        s[1] = a[i][name2];
                        html += htmlArray(s, pladeHolderArray, classArray, that);
                    }
                }
                var a = ['', ''];
                html += htmlArray(a, pladeHolderArray, classArray, that);
                var id = 'buttonArrayJon' + index;
                that.after(html);
                that.parent().sortable({stop: function(event, ui) {arrayhtml(that)}});
                if ($('.array_chosen').length > 0) {
                    $('.array_chosen').chosen({disable_search_threshold: 10});
                }
                $('#' + id).click(function(e) {
                    e.preventDefault();
                    $(this).before(htmlArray(a, pladeHolderArray, classArray, that));
                    if ($('.array_chosen').length > 0) {
                        $('.array_chosen').chosen({disable_search_threshold: 10});
                    }
                    return false;
                });
                that.parent().on('blur', '.array1', function(e) {
                    var that = $(this).parent().parent().parent().find('input[type="hidden"]');
                    arrayhtml(that);
                });
                that.parent().on('change', '.array1', function(e) {
                    var that = $(this).parent().parent().parent().find('input[type="hidden"]');
                    arrayhtml(that);
                });
                that.parent().on('click', '.remove_array', function(e) {
                    var pa = $(this).parent().parent().parent();
                    var that = pa.find('input[type="hidden"]');
                    if (pa.find('.setting_array_div').length > 1) {
                        $(this).parent().parent().remove();
                        arrayhtml(that);
                    }
                });

                that.parent().on('click', '.add_array', function(e) {
                    e.preventDefault();
                    $(this).parent().parent().after(htmlArray(a, pladeHolderArray, classArray, that));
                    if ($('.array_chosen').length > 0) {
                        $('.array_chosen').chosen({disable_search_threshold: 10});
                    }
                    return false;
                });
        }
    });
    function arrayhtml(that) {
        var array = [];
        var i = 0;
        var name1 = that.data('name1') && that.data('name1') != '' && that.data('name1') != 'undefined' ? that.data('name1') : 'label';
        var name2 = that.data('name2') && that.data('name2') != '' && that.data('name2') != 'undefined' ? that.data('name2') : 'value';
        that.parent().find('.setting_array_div').each(function(index) {
            var json1 = $.trim($(this).find('.array1').eq(0).val());
            var json2 = $.trim($(this).find('.array1').eq(1).val());
            var b = {};
            if (json1 != '' && json2 != '') {
                b[name1] = json1;
                b[name2] = json2;
                array[i] = b;
                i++;
            }
        });
        that.prop('value', JSON.stringify(array));
    }
}

$.fn.settingjson = function(options) {
    function htmlArray(a, pladeHolderArray, classArray, that) {
        var count = a.length;
        var html = '<div class="col-sm-12 setting_array_div">';
        if (that.data('chosen') && that.data('chosen') == '1') {
            var chosenstr = that.parent().prev().html();
            chosenstr = chosenstr.replace('<select ', '<select class="array_chosen array1" ');
            html += '<div style="padding-left:0px;" class="col-sm-3">' + chosenstr.replace('"' + a[0] + '"', '"' + a[0] + '"' + ' selected="selected" ') + '</div>';
        } else {
            html += '<div style="padding-left:0px;" class="col-sm-3"><input type="text" placeholder="' + (pladeHolderArray[0] != 'underfined' ? pladeHolderArray[0] : '') + '"  value=\'' + a[0] + '\' class="form-control col-sm-12 array1 ' + (classArray[0] != 'underfined' ? classArray[0] : '') + '" /></div>';
        }
        html += '<div class="col-sm-7"><input type="text" placeholder="' + (pladeHolderArray[1] != 'underfined' ? pladeHolderArray[1] : '') + '"  value=\'' + a[1] + '\' class="form-control col-sm-12 array1 ' + (classArray[1] != 'underfined' ? classArray[1] : '') + '" /></div>';

        html += '<div style="padding-right:0px;float:right;" class="col-sm-1"><button type="button" class="btn btn-danger remove_array fr" style="border:0px;">Delete</button></div>';
        html += '<div style="padding-right:0px;float:right;" class="col-sm-1"><button type="button" class="btn btn-primary add_array fr" style="border:0px;margin-right:5px;">Add</button></div>';
        html += '</div>';
        return html;
    }
    ;
    $(this).each(function(index) {
        var that = $(this);
        if(!that.hasClass('hasSettingJon')) {
                that.addClass('hasSettingJon');
                var count = 2;
                var pladeHolder = that.data('placeholder') ? that.data('placeholder') : 'id,value';
                var className = that.data('class') ? that.data('class') : '';
                var pladeHolderArray = explode(',', pladeHolder);
                var classArray = explode(',', className);
                var html = '';
                if (that.prop('value') != '') {
                    var a = JSON.parse(that.prop('value'));
                    var $count = a.length;
                    $.each(a, function(i, v) {
                        var b = [i, v];
                        html += htmlArray(b, pladeHolderArray, classArray, that);
                    });
                }
                var a = ['', ''];
                html += htmlArray(a, pladeHolderArray, classArray, that);
                var id = 'buttonJson' + index;
                that.after(html);
                that.parent().sortable({stop: function(event, ui) {arrayhtml(that)}});
                if ($('.array_chosen').length > 0) {
                    $('.array_chosen').chosen({disable_search_threshold: 10});
                }
                $('#' + id).click(function(e) {
                    e.preventDefault();
                    $(this).before(htmlArray(a, pladeHolderArray, classArray, that));
                    if ($('.array_chosen').length > 0) {
                        $('.array_chosen').chosen({disable_search_threshold: 10});
                    }
                    return false;
                });
                that.parent().on('blur', '.array1', function(e) {
                    var that = $(this).parent().parent().parent().find('input[type="hidden"]');
                    arrayhtml(that);
                });
                that.parent().on('change', '.array1', function(e) {
                    var that = $(this).parent().parent().parent().find('input[type="hidden"]');
                    arrayhtml(that);
                });
                that.parent().on('click', '.remove_array', function(e) {
                    var pa = $(this).parent().parent().parent();
                    var that = pa.find('input[type="hidden"]');
                    if (pa.find('.setting_array_div').length > 1) {
                        $(this).parent().parent().remove();
                        arrayhtml(that);
                    }
                });

                that.parent().on('click', '.add_array', function(e) {
                    e.preventDefault();
                    $(this).parent().parent().after(htmlArray(a, pladeHolderArray, classArray, that));
                    if ($('.array_chosen').length > 0) {
                        $('.array_chosen').chosen({disable_search_threshold: 10});
                    }
                    return false;
                });
        
        }
    });
    function arrayhtml(that) {
        var array = {};
        var i = 0;
        that.parent().find('.setting_array_div').each(function(index) {
            var json1 = $.trim($(this).find('.array1').eq(0).val());
            var json2 = $.trim($(this).find('.array1').eq(1).val());
            if (json1 != '' && json2 != '') {
                array[json1] = json2;
                i++;
            }
        });
        that.prop('value', JSON.stringify(array));
    }
    
}

var indeximage = 0;
$.fn.oneimage = function(options) {
    var s = $.extend({
        cha: '#main_content',
        init: function(that, index) {
        },
        beforesend: function(that, index) {
        },
        success: function(data, that, thisobj, index) {
        },
    }, options);
    var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
    var link = URL_IMAGE_UPLOAD + '?tmp='
            + ($('#tmp').length > 0 ? $('#tmp').val() : '')
            + ($('#did').length > 0 ? ('&did=' + $('#did').val()) : '');
    function process_success(data,that,thisobj, s) {
        if (data) {
            if (data.code == 200) {
                var link30 = HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + '30x30/' + data.name;
                var linkmain = HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + 'main/' + data.name;
                if(data.name.toLowerCase().match(/gif$/gi)) {
                    link30 = linkmain;
                }
                that.next().attr('data-imageid', data.id);
                that.next().attr('data-srca', linkmain);
                that.next().attr('src', link30);
                that.val(linkmain);
                s.success(data, that, thisobj, indeximage);
                $('.setting_tooltip').datatooltip();
                that.change();
            }
            else {
                that.next().attr('src', that.next().attr('data-src'));
                $(document).LoPopUp({
                    title: 'Error',
                    contentHtml: data.message,
                });
            }
        }
    }
    $(this).each(function(index) {
        var that = $(this);
        if(!that.hasClass('hasOneImage')) {
            that.addClass('hasOneImage');
            that.attr('srccount', indeximage);
            var next = that.next();
            if(next.length && next.hasClass('setting_tooltip')) {

            } else {
                if(that.val() != "") {
                    var link30 = HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + '30x30/' + that.val();
                    var linkmain = HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + 'main/' + that.val();
                } else {
                    var link30 = (WEB_TYPE ? '/' + WEB_TYPE : '') + '/img/grey.gif';
                    var linkmain = link30;
                }
                var html = '<img class="D_one_image setting_tooltip fl "'
                        + ' src="' + link30 + '" data-srca="' + linkmain + '"   />'
                        + '<button type="button" class="btn btn-primary fl D_brown">Browse</button>'
                        + '<button type="button" class="btn btn-primary fl D_brown D_brown_addlink">AddLink</button>';

                that.after(html);

                var idform = 'D_form_upload' + indeximage + 'images' + index;
                var id = 'DD_upload' + indeximage + 'images' + index;
                var obj = $('#' + idform);
                if (obj.length == 0) {
                    var html = '<form action="' + link + '" id="' + idform + '" method="POST">';
                    html += '<input type="file" id="' + id + '" style="display:none;" name="upload" />';
                    html += '</form>';
                    $(s.cha).append(html);
                    obj = $('#' + idform);
                }

                s.init(that, indeximage);
                if (trim(that.val()) != '') {
                    that.val(HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + 'main/' + that.val());
                }
                that.next().next().click(function(e) {
                    e.preventDefault();
                    $('#' + id).click();
                    return false;
                });

                that.next().next().next().click(function(e){
                    var val = $.trim(that.val());
                    if(val != "") {
                        var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
                        if(val.replace(HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + 'main/', '') == val) {
                            that.next().attr('data-src', that.next().attr('src'));
                            var img_loading = (WEB_TYPE ? '/' + WEB_TYPE : '') + '/img/loading.gif';
                            that.next().attr('src', img_loading);
                            $.ajax({
                                url     : URL_IMAGE_ADDLINK,
                                type    : 'POST',
                                dataType: 'json',
                                data    : {link: val,table_name: tmp},
                                success : function(data) {
                                    process_success(data,that,null, s);
                                },
                            });
                        } else {
                            that.next().attr('data-srca', val);
                            that.next().attr('src', val);
                            $('.setting_tooltip').datatooltip();
                            that.change();
                        }
                    }
                    return false;
                });

                obj.submit(function(e) {
                    var thisobj = $(this);
                    $.ajax({
                        url: $(this).attr('action'),
                        type: "POST",
                        data: new FormData(this),
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            process_success(data, that, thisobj, s);
                        },
                        error: function(rs) {
                            contentHtml = rs.responseText ? rs.responseText : rs;
                            $(document).LoPopUp({
                                title: 'Error log',
                                contentHtml: contentHtml,
                                cssDialog: 'modal-lg',
                            });
                        }
                    });
                    return false;
                });
                $('#' + id).change(function(e) {
                    $(this).parent().submit();
                    that.next().attr('data-src', that.next().attr('src'));
                    var img_loading = (WEB_TYPE ? '/' + WEB_TYPE : '') + '/img/loading.gif';
                    that.next().attr('src', img_loading);
                });
                indeximage++;
            }
        }
    });
}

function removeImage(that) {
    that.prev().attr('src', '');
    that.prev().attr('data-imageid', '');
    that.prev().prev().val('');
    imageidimage();
}

$.fn.manyimages = function(options) {
    var htmlTooltip = '<div id="{indexmany}stickymany{index}" class="atip"><img src="{src}" /></div>';
    var s = $.extend({
        cha: '#main_content',
        init: function(that, index) {
        },
        beforesend: function(that, index) {
        },
        success: function(data, that, thisobj, index) {
        },
    }, options);
    var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
    var link = URL_IMAGE_UPLOAD + '?tmp='
            + ($('#tmp').length > 0 ? $('#tmp').val() : '')
            + ($('#did').length > 0 ? ('&did=' + $('#did').val()) : '');
    $(this).each(function(index) {
        var that = $(this);
        that.attr('srccount', indeximage);
        var html = '<button class="btn btn-primary" style="padding:0px 2px;">Browse</button>';
        html += '<div class="D_multiupload">';
        if (trim(that.val()) != '') {
            var $array = JSON.parse(that.val());
            var $count = $array.length;
            for (var i = 0; i < $count; i++) {
                if ($('#imageid').val() != "") {
                    $('#imageid').val($('#imageid').val() + ',' + $array[i].id);
                } else {
                    $('#imageid').val($array[i].id);
                }
                var link30 = HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + '30x30/' + $array[i].name;
                var linkmain = HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + 'main/' + $array[i].name;
                html += '<div>';
                html += '<img class="setting_imageid setting_tooltip" data-imageid="' + $array[i].id + '" src="' + link30 + '" data-srca="' + linkmain + '" />';
                html += '<i class="fa fa fa-times-circle" onclick="removeImages($(this),' + $array[i].id + ');"></i>';
                html += '</div>';
            }
        }
        html += '</div>'

        that.after(html);

        var idform = 'D_form_multiupload' + indeximage + 'images' + index;
        var id = 'DD_multiupload' + indeximage + 'images' + index;
        var obj = $('#' + idform);
        if (obj.length == 0) {
            var inputHidden = '';
            var notstr = '';
            if (that.data('not') && that.data('not') == 1) {
                inputHidden += '<input type="hidden" style="display:none;" name="notsave" value="1" />';
                notstr = 'data-not="1"';
            }
            var html = '<form action="' + link + '" id="' + idform + '" ' + notstr + ' method="POST">';
            html += '<input type="file" id="' + id + '" style="display:none;" multiple name="upload[]" />';
            html += inputHidden;
            html += '</form>';
            $(s.cha).append(html);
            obj = $('#' + idform);
        }

        s.init(that, indeximage);
        that.next().click(function(e) {
            e.preventDefault();
            $('#' + id).click();
            return false;
        });

        obj.submit(function(e) {
            var thisobj = $(this);
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var $count = data.length;
                    var html = '';
                    var strId = '';
                    if (trim(that.val()) != '') {
                        var arrayVal = JSON.parse(that.val());
                        var j = arrayVal.length;
                    } else {
                        var arrayVal = [], j = 0;
                    }
                    for (var i = 0; i < $count; i++) {
                        var d = data[i];
                        if (d.code == 200) {
                            var link30 = HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + '30x30/' + d.name;
                            var linkmain = HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + 'main/' + d.name;
                            html += '<div>';
                            html += '<img class="setting_imageid setting_tooltip" data-imageid="' + d.id + '" src="' + link30 + '" data-srca="' + linkmain + '"  />';
                            html += '<i class="fa fa fa-times-circle" onclick="removeImages($(this),' + d.id + ');"></i>';
                            html += '</div>';
                            strId += '||' + d.name;
                            arrayVal[j] = {
                                name: d.name,
                                baseUrl: d.baseUrl,
                                id: d.id,
                                odr: j,
                            };
                            j++;
                        } else {
                            $(document).LoPopUp({
                                title: 'Error',
                                contentHtml: d.message,
                            });
                        }
                    }
                    if (strId) {
                        that.next().next().append(html);
                        that.val(JSON.stringify(arrayVal));
                        s.success(data, that, thisobj, index);
                        imageidimage();
                    }
                    $('.setting_tooltip').datatooltip();
                }
            });
            return false;
        });
        $('#' + id).change(function(e) {
            loadingFull();
            obj.submit();
        });
        indeximage++;

    });
    $('.setting_tooltip').datatooltip();
}


function imageidimage() {
    if ($('#imageid').length > 0) {
        var strId = '';
        $('.setting_imageid').each(function(index) {
            if ($(this).data('imageid') && $(this).data('imageid') != '') {
                strId += $(this).data('imageid') + ',';
            }
        });
        $('#imageid').val(strId.substr(0, strId.length - 1));
    }
}



function removeImages(that, id) {
    var input = that.closest('.form-group-input-child').find('input[type="hidden"]');
    var array = JSON.parse(input.val());
    var count = array.length;
    var arrayNew = [];
    var j = 0;
    for (var i = 0; i < count; i++) {
        if (array[i].id != id) {
            arrayNew[j] = array[i];
            j++;
        }
    }
    input.val(JSON.stringify(arrayNew));
    that.parent().remove();
    imageidimage();
}

$.fn.onefile = function(options) {
    var s = $.extend({
        cha: '#main_content',
        init: function(that, index) {
        },
        beforesend: function(that, index) {
        },
        success: function(data, that, thisobj, index) {
        },
    }, options);
    $(this).each(function(index) {
        var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
        var that = $(this);
        var html = '<button class="btn btn-primary fl setting_fileid" style="padding:0px;height:28px;">Upload File</button><div class="clear"></div>';
        if (that.val() != '') {
            html += '<div class="showlink">';
            html += '<div><a href="' + HOST_MEDIA_FILES + tmp + '/main/' + that.val() + '">' + HOST_MEDIA_FILES + tmp + '/main/' + that.val() + '</a>';
            html += ' <i class="fa fa fa-times-circle" onclick="removeFile($(this));"></i></div>';
            html += '</div>';
        } else {
            html += '<div class="showlink"></div>';
        }
        that.after(html);
        
        var link = URL_FILE_UPLOAD + '?tmp='
                + ($('#tmp').length > 0 ? $('#tmp').val() : '')
                + ($('#did').length > 0 ? ('&did=' + $('#did').val()) : '');

        var idform = 'D_form_uploadfile' + indeximage + 'files' + index;
        var id = 'DD_uploadfile' + indeximage + 'files' + index;
        indeximage++;
        var obj = $('#' + idform);
        if (obj.length == 0) {
            var html = '<form action="' + link + '" id="' + idform + '" method="POST">';
            html += '<input type="file" id="' + id + '" style="display:none;" name="upload" />';
            html += '</form>';
            $(s.cha).append(html);
            obj = $('#' + idform);
        }
        that.next().click(function(e) {
            e.preventDefault();
            $('#' + id).click();
            return false;
        });
        $('#' + id).change(function(e) {
            $(this).parent().submit();
        });
        var ktsubmit = true;
        obj.submit(function(e) {
            if (ktsubmit) {
                var thisobj = $(this);
                ktsubmit = false;
                loadingFull();
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    error: function() {
                        ktsubmit = true;
                        $('.DD_loadingfull').remove();
                    },
                    success: function(data) {
                        $('.DD_loadingfull').remove();
                        ktsubmit = true;
                        var div = that.next().next();
                        if (data && data.code == 200) {
                            that.val(data.name);
                            var html = '<div><a class="setting_imageid" data-fieldid="' + data.id + '" href="' + HOST_MEDIA_FILES + tmp + '/main/' + data.name + '">' + HOST_MEDIA_FILES + tmp + '/main/' + data.name + '</a>';
                            html += ' <i class="fa fa fa-times-circle" onclick="removeFile($(this),' + data.id + ');"></i></div>';
                            that.parent().find('.showlink').html(html);
                            //                        s.success(data, that, thisobj, index);
                        }
                        else {
                            $(document).LoPopUp({
                                title: 'Error',
                                contentHtml: data ? data.message : 'Can not upload file!',
                            });
                        }
                    }
                });
            }
            return false;
        });

    });

}


function fileidfile() {
    if ($('#fileid').length > 0) {
        var strId = '';
        $('.setting_imageid').each(function(index) {
            if ($(this).data('fileid') && $(this).data('fileid') != '') {
                strId += $(this).data('fileid') + ',';
            }
        });
        $('#fileid').val(strId.substr(0, strId.length - 1));
    }
}


function removeFile(that, id) {
    var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
    if (id) {
        $('#fileiddelete').val($('#fileiddelete').val() + ($('#fileiddelete').val() != '' ? ',' : '') + id);
    } else {
        var name = that.prev().html().replace(HOST_MEDIA_FILES + tmp, '');
        $('#fileiddeletename').val($('#fileiddeletename').val() + ($('#fileiddeletename').val() != '' ? ',' : '') + name);
    }
    var parent = that.parent().parent().parent();
    that.parent().remove();
    var str = '';
    parent.find('.showlink a').each(function(i) {
        str += '||' + $(this).attr('href').replace(HOST_MEDIA_FILES + tmp, '');
    });
    parent.find('input').val(str.substr(2, str.length));
    fileidfile();
}


$.fn.manyfiles = function(options) {
    var s = $.extend({
        cha: '#main_content',
        init: function(that, index) {
        },
        beforesend: function(that, index) {
        },
        success: function(data, that, thisobj, index) {
        },
    }, options);
    $(this).each(function(index) {
        var that = $(this);
        var html = '<button class="btn btn-primary fl" style="padding:0px;height:28px;">Upload File</button><div class="clear"></div>';
        if (that.val() != '') {
            html += '<div class="showlink">';

            var array = that.val().split('||');
            var count = array.length;
            for (var i = 0; i < count; i++) {
                html += '<div><a href="' + HOST_MEDIA_FILES + tmp + array[i] + '">' + HOST_MEDIA_FILES + tmp + array[i] + '</a>';
                html += ' <i class="fa fa fa-times-circle" onclick="removeFile($(this));"></i></div>';
            }
            html += '</div>';
        } else {
            html += '<div class="showlink"></div>';
        }
        that.after(html);
        var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
        var link = URL_FILE_UPLOAD + '?tmp='
                + ($('#tmp').length > 0 ? $('#tmp').val() : '')
                + ($('#did').length > 0 ? ('&did=' + $('#did').val()) : '');

        var idform = 'D_form_uploadfile' + indeximage + 'files' + index;
        var id = 'DD_uploadfilemany' + indeximage + 'files' + index;
        indeximage++;
        var obj = $('#' + idform);
        if (obj.length == 0) {
            var html = '<form action="' + link + '" id="' + idform + '" method="POST">';
            html += '<input type="file" id="' + id + '" style="display:none;" multiple name="upload[]" />';
            html += '</form>';
            $(s.cha).append(html);
            obj = $('#' + idform);
        }
        that.next().click(function(e) {
            e.preventDefault();
            $('#' + id).click();
            return false;
        });
        $('#' + id).change(function(e) {
            $(this).parent().submit();
        });
        obj.submit(function(e) {
            var thisobj = $(this);
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {


                    var $count = data.length;
                    var html = '';
                    var strId = '';
                    for (var i = 0; i < $count; i++) {
                        var d = data[i];
                        if (d.code == 200) {
                            html += '<div><a class="setting_imageid" data-fieldid="' + d.id + '" href="' + HOST_MEDIA_FILES + tmp + d.name + '">' + HOST_MEDIA_FILES + tmp + d.name + '</a>';
                            html += ' <i class="fa fa fa-times-circle" onclick="removeFile($(this),' + d.id + ');"></i></div>';
                            strId += '||' + d.name;
                        } else {
                            $(document).LoPopUp({
                                title: 'Error',
                                contentHtml: d.message,
                            });
                        }
                    }
                    if (strId != '') {
                        that.parent().find('.showlink').html(html);
                        if (trim(that.val()) != '') {
                            that.val(that.val() + strId);
                        } else {
                            that.val(strId.substr(2, strId.length));
                        }
                        s.success(data, that, thisobj, index);
                        fileidfile();
                    }
                }
            });
            return false;
        });

    });

}

function calc(that) {
    var val = "";
    var count = that.parent().find('.multiallmenu').length - 1;
    that.parent().find('.multiallmenu').each(function(i) {
        if ($(this).val() !== null)
            val += implode(',', $(this).val()) + ',';
    });
    that.val(val.replace(/,$/gi, ''));
}

function removeMultiallmenu(objParent,id) {
    var obj = objParent.find('#div-multiallmenu-' + id);
    if (obj.length) {
        var that = objParent.find('input');
        var b = obj.find('select').val();
        if (b !== null && b.length) {
            for (var i = 0; i < b.length; i++) {
                removeMultiallmenu(objParent,b[i]);
            }
        }
        obj.remove();
        calc(that);
    }
}

$.fn.multiallmenu = function(options) {
    var setting = $.extend({
        url1: URL_LOAD_MULTIMENU,
        url2: URL_LOAD_MAPPINGMULTIMENU,
    }, options);

    $(this).each(function(index) {
        var that = $(this);
        if (!that.next().length || !that.next().hasClass('fl')) {
            var data = 'mappingid=' + that.data('mappingid') + '&value=' + that.val();
            var a = getGet(window.location.href);
            if (a.id) {
                data += '&menudid=' + a.id;
            }
            if (that.data('type')) {
                var array = explode(',', that.data('type'));
                for (var i = 0; i < array.length; i++) {
                    var obj = $('#' + array[i]);
                    var name = obj.attr('name').replace(/([a-zA-Z0-9_-])+\[|\]/gi, '');
                    var value = obj.val();
                    data += '&' + name + '=' + value;
                }
            }
            $.ajax({
                url: setting.url1,
                type: 'POST',
                dataType: 'json',
                data: data,
                async: false,
                success: function(rs) {
                    if (rs.code == 200) {
                        that.after(rs.data);
                    }
                }
            });

            that.parent().on('click', '.multiallmenu', function(e) {
                var thet = $(this);
                var objParent = thet.closest('.col-sm-12');
                var obj = objParent.find('input');
                calc(obj);
                var data_new_val = thet.val();
                var counta = data_new_val && data_new_val.length !== null ? data_new_val.length : 0;

                if (thet.attr('data-val') && thet.attr('data-val') != '0') {
                    var data_old_val = explode(',', thet.attr('data-val'));
                    for (var i = 0; i < data_old_val.length; i++) {
                        var fl = true;
                        for (var j = 0; j < counta; j++) {
                            if (data_old_val[i] == data_new_val[j]) {
                                fl = false;
                                break;
                            }
                        }
                        if (fl) {
                            removeMultiallmenu(objParent,data_old_val[i]);
//                            break;
                        }
                    }
                }
                if (!data_new_val.length)
                    return false;

                thet.attr('data-val', implode(',', data_new_val));
                for (var i = 0; i < data_new_val.length; i++) {
                    var theti = objParent.find('#div-multiallmenu-' + data_new_val[i]);
                    var str = thet.find('option[value="' + data_new_val[i] + '"]').html();
                    if (str.match(/\⇒$/gi) && !theti.length) {
                        $.ajax({
                            url: setting.url2,
                            data: 'id=' + thet.data('mappingid') + '&pid=' + data_new_val[i],
                            dataType: 'json',
                            type: 'POST',
                            success: function(rs) {
                                if (rs.code == 200) {
                                    obj.parent().append(rs.data);
                                }
                            }
                        });
                    }
                }
            });
        }
    });

}

$(document).ready(function(e){
    $('body').on('click','.D_brown_addlink',function(e){
        var that = $(this);
        var val = $.trim(that.parent().find('input').val());
        if(val != "") {
            var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
            if(val.replace(HOST_MEDIA_IMAGES + tmp + (tmp ? '/' : '') + 'main/', '') == val) {
                MainAjax({
                    url     : '',
                    success : function(rs) {
                        
                    },
                });
            }
        }
    });
});

var indexswf = 0;
$.fn.oneswf = function(options) {
    var s = $.extend({
        cha: '#main_content',
        init: function() {
        },
        beforesend: function() {
        },
        success: function() {
        },
    }, options);
    var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
    var link = URL_SWF_UPLOAD + '?tmp='
            + ($('#tmp').length > 0 ? $('#tmp').val() : '')
            + ($('#did').length > 0 ? ('&did=' + $('#did').val()) : '');
    function process_success(data,that,thisobj, s) {
        if (data) {
            if (data.code == 200) {
                var linkmain = HOST_MEDIA_SWF + tmp + (tmp ? '/' : '') + 'main/' + data.name;
                that.next().attr('data-imageid', data.id);
                that.next().attr('data', linkmain);
                that.val(linkmain);
                s.success(data, that, thisobj, indexswf);
                that.change();
            }
            else {
                that.next().attr('src', that.next().attr('data-src'));
                $(document).LoPopUp({
                    title: 'Error',
                    contentHtml: data.message,
                });
            }
        }
    }
    $(this).each(function(index) {
        var that = $(this);
        that.attr('srccount', indexswf);
        var next = that.next();
        if(next.length && next.hasClass('setting_tooltip')) {
            
        } else {
            if(that.val() != "") {
                var linkmain = HOST_MEDIA_SWF + tmp + (tmp ? '/' : '') + 'main/' + that.val();
            } else {
                var linkmain = '';
            }
            var html = '<object width="30" height="30" class="D_one_image setting_tooltip fl "'
                    + ' data="' + linkmain + '"></object>'
                    + '<button type="button" class="btn btn-primary fl D_brown">Browse</button>'
                    + '<button type="button" class="btn btn-primary fl D_brown D_brown_addlink">AddLink</button>';

            that.after(html);

            var idform = 'D_form_upload' + indexswf + 'swf' + index;
            var id = 'DD_upload' + indexswf + 'swf' + index;
            var obj = $('#' + idform);
            if (obj.length == 0) {
                var html = '<form action="' + link + '" id="' + idform + '" method="POST">';
                html += '<input type="file" id="' + id + '" style="display:none;" name="upload" />';
                html += '</form>';
                $(s.cha).append(html);
                obj = $('#' + idform);
            }

            s.init(that, indexswf);
            if (trim(that.val()) != '') {
                that.val(HOST_MEDIA_SWF + tmp + (tmp ? '/' : '') + 'main/' + that.val());
            }
            that.next().next().click(function(e) {
                e.preventDefault();
                $('#' + id).click();
                return false;
            });
            
            that.next().next().next().click(function(e){
                var val = $.trim(that.val());
                if(val != "") {
                    var tmp = ($('#tmp').length > 0 ? $('#tmp').val() : '');
                    if(val.replace(HOST_MEDIA_SWF + tmp + (tmp ? '/' : '') + 'main/', '') == val) {
                        MainAjax({
                            url     : URL_SWF_ADDLINK,
                            type    : 'POST',
                            dataType: 'json',
                            data    : {link: val,table_name: tmp},
                            success : function(data) {
                                process_success(data,that,null, s);
                            },
                        });
                    } else {
                        that.next().attr('data', val);
                        that.change();
                    }
                }
                return false;
            });

            obj.submit(function(e) {
                var thisobj = $(this);
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        process_success(data, that, thisobj, s);
                    },
                    error: function(rs) {
                        $(document).LoPopUp({
                            title: 'Error log',
                            contentHtml: rs.responseText ? rs.responseText : rs,
                            cssDialog: 'modal-lg',
                        });
                    }
                });
                return false;
            });
            $('#' + id).change(function(e) {
                $(this).parent().submit();
            });
            indexswf++;
        }
    });
}