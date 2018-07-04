var index_landingpage = 0;
var ind = 0;
function repairJsonChosen() {
        if($('.json_chosen').length) {
            $('.json_chosen').each(function(){
                if(!$(this).next().width()) {
                    $(this).next().width(140);
                }
            })
        }
    }
$.fn.landingpage = function(options) {
    function input_type(placeholder, cl, name, value, type) {
        var html = '';
        if(value) {
            value = encodeQuote(value);
        }
        switch (type) {
            case 'chosen':
                var chosenstr = $('#' + cl).html();
                chosenstr = chosenstr.replace('<select ', '<select class="json_chosen setting_array_landingpage landingpage_cl_' + name + '" data-namevalue="' + name + '" ');
                html = chosenstr.replace('"' + value + '"', '"' + value + '"' + ' selected="selected" ');
                break;
            case 'radio':
                var chosenstr = $('#' + cl).html();
                chosenstr = chosenstr.replace('<select ', '<select class="json_radio setting_array_landingpage landingpage_cl_' + name + '" data-namevalue="' + name + '" ');
                html = chosenstr.replace('"' + value + '"', '"' + value + '"' + ' selected="selected" ');
                break;
            case 'content':
                html = '<textarea data-namevalue="' + name + '" name="json_content_' + index_landingpage + '" id="json_content_' + index_landingpage + '" class="landingpage_cl_' + name + ' json_textarea setting_array_landingpage json_content ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                index_landingpage++;
                break;
            case 'faq': 
                html = '<textarea data-namevalue="' + name + '" data-width="100%,100%" data-name="title,faq" data-placeholder="Header,Faq" data-type="text,arraymany2" class="landingpage_cl_' + name + ' dnone json_textarea setting_array_landingpage json_manyjson ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                break;
            case 'textarea':
                html = '<textarea data-namevalue="' + name + '" class="json_textarea setting_array_landingpage landingpage_cl_' + name + ' form-control col-sm-12 ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                break;
            case 'datetime':
                html = '<input type="hidden" value="' + value + '" data-namevalue="' + name + '" class="form-control landingpage_cl_' + name + ' col-sm-12 setting_array_landingpage json_datetime' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'hidden':
                html = '<input type="hidden" value=\'' + value + '\' data-namevalue="' + name + '" class="form-control landingpage_cl_' + name + ' col-sm-12 setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'image':
                html = '<input type="text" value=\'' + value + '\' data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_image setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'file':
                html = '<input type="hidden" value=\'' + value + '\' data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_file setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'password' :
                html = '<input type="text" value=\'' + value + '\' data-namevalue="' + name + '" class="form-control landingpage_cl_' + name + ' col-sm-12 setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '" />';
                break;
            case 'arraymanyjson':
                html = '<textarea style="display:none;" data-name="name,link,image,altimage" data-placeholder="name,link,image,altimage" data-type="textarea,text,image,text" data-width="780,780,600,600" data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_manyjson setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                break;
            case 'arraymanyjsoncolumns':
                html = '<textarea style="display:none;" data-name="name,link,image,altimage,description" data-placeholder="name,link,image,title,description" data-type="textarea,text,image,text,textarea" data-width="780,780,600,600,780" data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_manyjson setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                break;
            case 'arraymanyjsondescription':
                html = '<textarea style="display:none;" data-name="name,link,image,altimage,description" data-placeholder="name,link,image,altimage,description" data-type="text,text,image,text,textarea" data-width="600,600,600,600,900" data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_manyjson setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                break;
            case 'feautures':
                html = '<textarea style="display:none;" data-name="title,image,altimage,name,description" data-placeholder="title,image,altimage,name,description" data-type="textarea,image,text,text,textarea" data-width="800,600,600,600,600" data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_manyjson setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '">' + value + '</textarea>';
                break;
            case 'arraymanyjsoncategory':
                html = '<textarea style="display:none;" data-name="category,name,url,description,image,altimage" data-placeholder="category,name,surl,description,image,altimage" data-type="text,text,text,textarea,image,text" data-width="780,780,780,780,780,780" data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_manyjson setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '" >' + value + '</textarea>';
                break;
            case 'slider':
                html = '<textarea style="display:none;" data-name="image,title,link" data-placeholder="Ảnh,Tiêu đề,Link" data-type="image,text,text" data-width="780,780,780" data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_manyjson setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '" >' + value + '</textarea>';
                break;
            case 'arraymanyjsontext':
                html = '<textarea style="display:none;" data-name="icon,name" data-placeholder="icon,name" data-type="text,text" data-width="780,780" data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_manyjson setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '" >' + value + '</textarea>';
                break;
            case 'membership':
                if(!$('#chosen_membership').length) {
                    $('body').append('<div id="chosen_membership" style="display:none;"><select style="width:200px;" data-abc="1aa"><option value="0">Không</option><option value="1">Có</option><option value="2">Giới hạn</option></select></div>');
                }
                html = '<textarea style="display:none;" data-name="feature,blue,silver,gold" data-placeholder="Tính năng, Hội viên đồng, Hội viên bạc,Hội viên vàng" data-type="textarea,chosen,chosen,chosen" data-width="380,140,140,140" data-cl="membership_title,chosen_membership,chosen_membership,chosen_membership" data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_manyjson setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '" >' + value + '</textarea>';
                break;
            case 'swf':
                html = '<input type="text" value=\'' + value + '\' data-namevalue="' + name + '" class="fl form-control landingpage_cl_' + name + ' json_swf setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '" />';
                break;
            default:
                html = '<input type="text" value=\'' + value + '\' data-namevalue="' + name + '" class="form-control landingpage_cl_' + name + ' col-sm-12 setting_array_landingpage ' + cl + '" placeholder="' + placeholder + '" />';
                break;
        }
        return html;
    }
    function resetJsonAjax() {
        if ($('.json_chosen').length > 0) {
            $('.json_chosen').chosen({disable_search_threshold: 10});
        }
        if ($('.json_radio').length > 0) {
            $('.json_radio').checkboxsmall({
                change: function(that) {
                    that.change();
                }
            });
        }
        if ($('.json_manyjson').length) {
            $('.json_manyjson').arraymanyjson();
        }
        if ($('.json_datetime').length > 0) {
            $('.json_datetime').datetimepicker().next().on(ace.click_event, function() {
                $(this).prev().focus();
            });
        }
        setTimeout(function() {
            if ($('.setting_tooltip').length) {
                $('.setting_tooltip').datatooltip2();
            }
        }, 1000);

        if ($('.json_content').length > 0) {
            $('.json_content').each(function(i) {
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
        if ($('.json_file').length) {
            $('.json_file').onefile({
                success: function(data, that, thisobj, index) {
                    fileidfile();
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
    
    function showAttribute(that) {
        var data_show = that.data('show');
        if(data_show) {
            that.parent().find('.landingpage_cl_' + data_show).each(function() {
                var value = $(this).val();
                var show = that.data(data_show + '_' + value);
                if (value !== '') {
                    if (show) {
                        var array_show = show.split(',');
                        var obj = $(this).closest('.setting_arraylanding_div');
                        var length = array_show.length;
                        obj.find('.div_landingpage_12').hide();
                        for (var i = 0; i < length; i++) {
                            obj.find('.div_landingpage_cl_' + array_show[i]).show();
                        }
                    }
                }
            });
        }
    }

    function countReset(that) {
        that.parent().find('h4 > span').each(function(i) {
            $(this).html(i + 1);
        })
    }

    function htmlArray(value_json, that, count_i, display_flag) {
        var nameArray = that.data('name') ? that.data('name').split(',') : ['id', 'value'];
        var pladeHolderArray = that.data('placeholder') ? that.data('placeholder').split(',') : ['id', 'value'];
        var clArray = that.data('cl') ? that.data('cl').split(',') : ['', ''];
        var typeArray = that.data('type') ? that.data('type').split(',') : ['', ''];

        var html = '<div class="col-sm-12 setting_arraylanding_div">';
        html += '<h4 onclick="$(this).next().css(\'display\') == \'none\' ? $(this).next().show() : $(this).next().hide();repairJsonChosen();">Block <span>' + (count_i + 1) + '</span></h4>';
        html += '<div class="col-sm-12 form-group-input plr0" ' + (display_flag ? 'style="display:none;"' : '') + '>';
        var count = nameArray.length ? nameArray.length : 1;
        var data_show = that.data('show');
        for (var i = 0; i < count; i++) {
            var placeholder = pladeHolderArray[i] ? pladeHolderArray[i] : '';
            var cl = clArray[i] ? clArray[i] : '';
            var name = nameArray[i] ? nameArray[i] : '';
            var value = value_json[name] ? value_json[name] : '';
            var type = typeArray[i] ? typeArray[i] : '';
            var display_none = data_show ? (data_show == name ? '' : 'display:none;') : '';
            html += '<div class="col-sm-12 plr0 div_landingpage_12 div_landingpage_cl_' + name + '" style="margin-bottom:10px;' + display_none + '">';
            html += '<label class="col-sm-2 control-label no-padding-right D-form-label">' + placeholder + '</label>';
            html += '<div class="col-sm-10 plr0">' + input_type(placeholder, cl, name, value, type) + '</div>';
            html += '</div>';
        }
        html += '</div>';
        html += '<div class="col-sm-1 setting_json_div_1"><button type="button" class="btn btn-danger remove_array_landingpage fr" style="border:0px;">Delete</button></div>';
        html += '<div class="col-sm-1 setting_json_div_1"><button type="button" class="btn btn-primary add_array_landingpage fr" style="border:0px;margin-right:5px;">Add</button></div>';
        html += '</div>';
        return html;
    };
    
    $(this).each(function(index) {
        var that = $(this);
        if(!that.hasClass('arraylandingpagejson')) {
            var html = '';
            that.addClass('arraylandingpagejson');
            if (that.prop('value') != '') {
                var main_value = JSON.parse(that.prop('value'));
                for (var i = 0; i < main_value.length; i++) {
                    html += htmlArray(main_value[i], that, i, true);
                }
            } else {
                html += htmlArray([], that, 0, false);
            }
            that.after(html);
            resetJsonAjax();
            showAttribute(that);
            var flag_image_array = false;
            that.parent().find('.setting_array_landingpage').each(function() {
                var obj = $(this).next().find('.chosen-single span');
                if (obj.length) {
                    var html = obj.html().replace(/(\&lt;)/gi, '<').replace(/(\&gt;)/gi, '>');
                    obj.html(html);
                    flag_image_array = true;
                }
            })
            if (flag_image_array) {
                setTimeout(function() {
                    if ($('.setting_tooltip').length) {
                        $('.setting_tooltip').datatooltip2();
                    }
                }, 10);
            }
            that.parent().sortable({
                start: function(event,ui) {
                    $(event.currentTarget).find('.json_content').each(function(){
                        if($(this).attr('ckeditor') == 1) {
                            CKEDITOR.instances[$(this).attr('name')].destroy(true);
                            $(this).removeAttr('ckeditor');
                        }
                    })
                },
                stop: function(event, ui) {
                    $(event.currentTarget).find('.json_content').each(function(){
                        if(!$(this).attr('ckeditor')) {
                            $(this).attr('ckeditor',1);
                            CKEDITOR.replace($(this).attr('name'), {
                                height: '150px'
                            });
                        }
                    })
                    arrayhtmllandingpage(that);
                }});
            that.parent().on('blur', '.setting_array_landingpage', function(e) {
                var that = $(this).closest('.form-group-input-child').find('.arraylandingpagejson');
                arrayhtmllandingpage(that);
            });
            that.parent().on('change', '.setting_array_landingpage', function(e) {
                var that = $(this).closest('.form-group-input-child').find('.arraylandingpagejson');
                showAttribute(that);
                repairJsonChosen();
                arrayhtmllandingpage(that);
            });
            that.parent().on('click', '.remove_array_landingpage', function(e) {
                if (confirm("Are you sure to delete this block?")) {
                    var pa = $(this).closest('.form-group-input-child');
                    var that = pa.find('.arraylandingpagejson');
                    if (pa.find('.setting_arraylanding_div').length > 1) {
                        $(this).closest('.setting_arraylanding_div').remove();
                        arrayhtmllandingpage(that);
                    }
                    countReset(that);
                } else {
                    return false;
                }
            });

            that.parent().on('click', '.add_array_landingpage', function(e) {
                e.preventDefault();
                $(this).closest('.setting_arraylanding_div').after(htmlArray([], that));
                resetJsonAjax();
                showAttribute(that);
                var obj_div = that.parent().find('.setting_arraylanding_div');
                var ojb_last = obj_div.eq(obj_div.length - 1);
                if (ojb_last.length && ojb_last.find('.json_content').length) {
                    CKEDITOR.instances[ojb_last.find('.json_content').attr('id')].on('change', function() {
                        arrayhtmllandingpage(that);
                    });
                }
                countReset(that);
                return false;
            });

            that.parent().find('.json_content').each(function(i) {
                CKEDITOR.instances[$(this).attr('id')].on('change', function() {
                    arrayhtmllandingpage(that);
                });
            });
            that.change(function(e){
                arrayhtmllandingpage($(this));
            })
        }
    });
    function arrayhtmllandingpage(that) {
        countReset(that);
        var array = [];
        that.parent().find('.setting_arraylanding_div').each(function(index) {
            var array2 = {};
            $(this).find('.setting_array_landingpage').each(function(j) {
                var name = $(this).attr('data-namevalue');
                if($(this).closest('.div_landingpage_cl_' + name).css('display') != 'none') {
                    if ($(this).hasClass('json_content')) {
                        $(this).val(CKEDITOR.instances[$(this).attr('id')].getData());
                    }
                    var value = $(this).val();
                    array2[name] = value;
                }
            });
            array[index] = array2;
        });
        that.prop('value', JSON.stringify(array));
        that.val(JSON.stringify(array));
    }
}

$('body').on('click', '.div_landingpage_cl_type .chosen-single', function(e) {
//    if(!$(this).hasClass('show_tooltip')) {
//        $(this).addClass('show_tooltip');
    setTimeout(function() {
        if ($('.setting_tooltip').length) {
            $('.setting_tooltip').datatooltip2();
        }
    }, 10);
//    }
})

$('body').on('change', '.setting_array_landingpage', function(e) {
    var obj = $(this).next().find('.chosen-single span');
    var val = $.trim(obj.html());
    if (val.replace(/(\&lt;)/gi, '<') != val) {
        var html = val.replace(/(\&lt;)/gi, '<').replace(/(\&gt;)/gi, '>');
        obj.html(html);
        setTimeout(function() {
            if ($('.setting_tooltip').length) {
                $('.setting_tooltip').datatooltip2();
            }
        }, 10);
    }
})