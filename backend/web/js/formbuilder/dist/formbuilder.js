var optionName = '';
var mappingName = '';
(function() {
      rivets.binders.input = {
        publishes: true,
        routine: rivets.binders.value.routine,
        bind: function(el) {
          return $(el).bind('input.rivets', this.publish);
        },
        unbind: function(el) {
          return $(el).unbind('input.rivets');
        }
      };

      rivets.configure({
        prefix: "rv",
        adapter: {
          subscribe: function(obj, keypath, callback) {
            callback.wrapped = function(m, v) {
              return callback(v);
            };
            return obj.on('change:' + keypath, callback.wrapped);
          },
          unsubscribe: function(obj, keypath, callback) {
            return obj.off('change:' + keypath, callback.wrapped);
          },
          read: function(obj, keypath) {
            if (keypath === "cid") {
              return obj.cid;
            }
            return obj.get(keypath);
          },
          publish: function(obj, keypath, value) {
            if (obj.cid) {
              return obj.set(keypath, value);
            } else {
              return obj[keypath] = value;
            }
          }
        }
      });

    }).call(this);

    (function() {
      var BuilderView, EditFieldView, Formbuilder, FormbuilderCollection, FormbuilderModel, ViewFieldView, _ref, _ref1, _ref2, _ref3, _ref4,
        __hasProp = {}.hasOwnProperty,
        __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

      FormbuilderModel = (function(_super) {
        __extends(FormbuilderModel, _super);

        function FormbuilderModel() {
          _ref = FormbuilderModel.__super__.constructor.apply(this, arguments);
          return _ref;
        }

        FormbuilderModel.prototype.sync = function() {};

        FormbuilderModel.prototype.indexInDOM = function() {
          var $wrapper,
            _this = this;
          $wrapper = $(".fb-field-wrapper").filter((function(_, el) {
            return $(el).data('cid') === _this.cid;
          }));
          return $(".fb-field-wrapper").index($wrapper);
        };

        FormbuilderModel.prototype.is_input = function() {
          return Formbuilder.inputFields[this.get(Formbuilder.options.mappings.FIELD_TYPE)] != null;
        };

        return FormbuilderModel;

      })(Backbone.DeepModel);

      FormbuilderCollection = (function(_super) {
        __extends(FormbuilderCollection, _super);

        function FormbuilderCollection() {
          _ref1 = FormbuilderCollection.__super__.constructor.apply(this, arguments);
          return _ref1;
        }

        FormbuilderCollection.prototype.initialize = function() {
          return this.on('add', this.copyCidToModel);
        };

        FormbuilderCollection.prototype.model = FormbuilderModel;

        FormbuilderCollection.prototype.comparator = function(model) {
          return model.indexInDOM();
        };

        FormbuilderCollection.prototype.copyCidToModel = function(model) {
          return model.attributes.cid = model.cid;
        };

        return FormbuilderCollection;

      })(Backbone.Collection);

      ViewFieldView = (function(_super) {
        __extends(ViewFieldView, _super);

        function ViewFieldView() {
          _ref2 = ViewFieldView.__super__.constructor.apply(this, arguments);
          return _ref2;
        }

        ViewFieldView.prototype.className = "fb-field-wrapper";

        ViewFieldView.prototype.events = {
          'click .subtemplate-wrapper': 'focusEditView',
          'click .js-duplicate': 'duplicate',
          'click .js-clear': 'clear'
        };

        ViewFieldView.prototype.initialize = function(options) {
          this.parentView = options.parentView;
          this.listenTo(this.model, "change", this.render);
          return this.listenTo(this.model, "destroy", this.remove);
        };

        ViewFieldView.prototype.render = function() {
          this.$el.addClass('response-field-' + this.model.get(Formbuilder.options.mappings.FIELD_TYPE)).data('cid', this.model.cid).html(Formbuilder.templates["view/base" + (!this.model.is_input() ? '_non_input' : '')]({
            rf: this.model
          }));
          return this;
        };

        ViewFieldView.prototype.focusEditView = function() {
          return this.parentView.createAndShowEditView(this.model);
        };

        ViewFieldView.prototype.clear = function(e) {
          var cb, x,
            _this = this;
          e.preventDefault();
          e.stopPropagation();
          cb = function() {
            _this.parentView.handleFormUpdate();
            return _this.model.destroy();
          };
          x = Formbuilder.options.CLEAR_FIELD_CONFIRM;
          switch (typeof x) {
            case 'string':
              if (confirm(x)) {
                return cb();
              }
              break;
            case 'function':
              return x(cb);
            default:
              return cb();
          }
        };

        ViewFieldView.prototype.duplicate = function() {
          var attrs;
          attrs = _.clone(this.model.attributes);
          delete attrs['id'];
          attrs['label'] += ' Copy';
          return this.parentView.createField(attrs, {
            position: this.model.indexInDOM() + 1
          });
        };

        return ViewFieldView;

      })(Backbone.View);

      EditFieldView = (function(_super) {
        __extends(EditFieldView, _super);

        function EditFieldView() {
          _ref3 = EditFieldView.__super__.constructor.apply(this, arguments);
          return _ref3;
        }

        EditFieldView.prototype.className = "edit-response-field";

        EditFieldView.prototype.events = {
          'click .js-add-option': 'addOption',
          'click .js-add-option-attributes': 'addOptionAttributes',
          'click .js-remove-option-attributes': 'removeOptionAttributes',
          'click .js-remove-option': 'removeOption',
          'click .js-default-updated': 'defaultUpdated',
          'input .option-label-input': 'forceRender',
          'change #builder_name': 'ChangeBuilderName',
          'click #button_add_attribute_name': 'AddAtributeName',
        };
        
        EditFieldView.prototype.AddAtributeName = function(e) {
            var that = $(e.currentTarget);
            var val = that.prev().val();
            var html = '<option value="' + val + '">' + val + '</option>';
            optionName += html;
            $('#builder_name').append(html);
            that.prev().val('');
            $('#builder_name').val(val);
            this.ChangeBuilderName();
        }

        EditFieldView.prototype.initialize = function(options) {
            //console.log('EditFieldView initialize');
          this.parentView = options.parentView;
          return this.listenTo(this.model, "destroy", this.remove);
        };

        EditFieldView.prototype.render = function() {
            //console.log('EditFieldView render');
            //console.log(this.$el);
          this.$el.html(Formbuilder.templates["edit/base" + (!this.model.is_input() ? '_non_input' : '')]({
            rf: this.model
          }));
          rivets.bind(this.$el, {
            model: this.model
          });
          return this;
        };

        EditFieldView.prototype.ChangeBuilderName = function(e) {
            var $el = $('#builder_name');
            this.model.attributes.name = $el.val();
            this.model.attributes.label = upperCaseFirstLetter($el.val().replace(/_/gi,' '));
            this.forceRender();
            return this.render();
        };

        EditFieldView.prototype.remove = function() {
            //console.log('EditFieldView remove');
          this.parentView.editView = void 0;
          this.parentView.$el.find("[data-target=\"#addField\"]").click();
          return EditFieldView.__super__.remove.apply(this, arguments);
        };

        EditFieldView.prototype.addOption = function(e) {
            //console.log('EditFieldView addOption');
          var $el, i, newOption, options;
          $el = $(e.currentTarget);
          i = this.$el.find('.option').index($el.closest('.option'));
          options = this.model.get(Formbuilder.options.mappings.OPTIONS) || [];
          newOption = {
            label: "",
            checked: false
          };
          if (i > -1) {
            options.splice(i + 1, 0, newOption);
          } else {
            options.push(newOption);
          }
          this.model.set(Formbuilder.options.mappings.OPTIONS, options);
          this.model.trigger("change:" + Formbuilder.options.mappings.OPTIONS);
          return this.forceRender();
        };


        EditFieldView.prototype.addOptionAttributes = function(e) {
            //console.log('EditFieldView addOption');
          var $el, i, newOption, options;
          $el = $(e.currentTarget);
          i = this.$el.find('.option_atrributes').index($el.closest('.option_atrributes'));
          var options = this.model.get(Formbuilder.options.mappings.ATTRIBUTES) || [];
          newOption = {
            label: "",
            value: "",
          };
          if (i > -1) {
            options.splice(i + 1, 0, newOption);
          } else {
            options.push(newOption);
          }
          this.model.set(Formbuilder.options.mappings.ATTRIBUTES, options);
          this.model.trigger("change:" + Formbuilder.options.mappings.ATTRIBUTES);
        };

        EditFieldView.prototype.removeOptionAttributes = function(e) {
            //console.log('EditFieldView removeOption');
          var $el, index, options;
          $el = $(e.currentTarget);
          index = this.$el.find(".js-remove-option-attributes").index($el);
          options = this.model.get(Formbuilder.options.mappings.ATTRIBUTES);
          options.splice(index, 1);
          this.model.set(Formbuilder.options.mappings.ATTRIBUTES, options);
          this.model.trigger("change:" + Formbuilder.options.mappings.ATTRIBUTES);
          return this.forceRender();
        };
        
        EditFieldView.prototype.removeOption = function(e) {
            //console.log('EditFieldView removeOption');
          var $el, index, options;
          $el = $(e.currentTarget);
          index = this.$el.find(".js-remove-option").index($el);
          options = this.model.get(Formbuilder.options.mappings.OPTIONS);
          options.splice(index, 1);
          this.model.set(Formbuilder.options.mappings.OPTIONS, options);
          this.model.trigger("change:" + Formbuilder.options.mappings.OPTIONS);
          return this.forceRender();
        };

        EditFieldView.prototype.defaultUpdated = function(e) {
            //console.log('EditFieldView defaultUpdated');
          var $el;
          $el = $(e.currentTarget);
          if (this.model.get(Formbuilder.options.mappings.FIELD_TYPE) !== 'checkboxes') {
            this.$el.find(".js-default-updated").not($el).attr('checked', false).trigger('change');
          }
          return this.forceRender();
        };

        EditFieldView.prototype.forceRender = function() {
            //console.log('EditFieldView forceRender');
          return this.model.trigger('change');
        };

        return EditFieldView;

      })(Backbone.View);

      BuilderView = (function(_super) {
        __extends(BuilderView, _super);

        function BuilderView() {
          _ref4 = BuilderView.__super__.constructor.apply(this, arguments);
          return _ref4;
        }

        BuilderView.prototype.SUBVIEWS = [];

        BuilderView.prototype.events = {
          'click .js-save-form': 'saveForm',
          'click .fb-tabs a': 'showTab',
          'click .fb-add-field-types a': 'addField',
          'mouseover .fb-add-field-types': 'lockLeftWrapper',
          'mouseout .fb-add-field-types': 'unlockLeftWrapper'
        };

        BuilderView.prototype.initialize = function(options) {
          //console.log('BuilderView initialize');
          ot = JSON.parse(options.optionName);
          optionName = '<option value="">-- Select attribute --</option>';
          $.each(ot,function(key,name){
              optionName += '<option value="'+key+'">'+name+'</option>\n';
          });
          mt = JSON.parse(options.mappingName);
          mappingName = '<option value="0">Chọn mapping</option>\n';
          $.each(mt,function(key,name){
              mappingName += '<option value="'+key+'">'+name+'</option>\n';
          });
          var selector;
          selector = options.selector, this.formBuilder = options.formBuilder, this.bootstrapData = options.bootstrapData;
          if (selector != null) {
            this.setElement($(selector));
          }
          this.collection = new FormbuilderCollection;
          this.collection.bind('add', this.addOne, this);
          this.collection.bind('reset', this.reset, this);
          this.collection.bind('change', this.handleFormUpdate, this);
          this.collection.bind('destroy add reset', this.hideShowNoResponseFields, this);
          this.collection.bind('destroy', this.ensureEditViewScrolled, this);
          this.render();
          this.collection.reset(this.bootstrapData);
          return this.bindSaveEvent();
        };

        BuilderView.prototype.bindSaveEvent = function() {
            //console.log('BuilderView bindSaveEvent');
          var _this = this;
          this.formSaved = true;
          this.saveFormButton = this.$el.find(".js-save-form");
          this.saveFormButton.attr('disabled', true).text(Formbuilder.options.dict.ALL_CHANGES_SAVED);
          if (!!Formbuilder.options.AUTOSAVE) {
            setInterval(function() {
              return _this.saveForm.call(_this);
            }, 5000);
          }
          return $(window).bind('beforeunload', function() {
            if (_this.formSaved) {
              return void 0;
            } else {
              return Formbuilder.options.dict.UNSAVED_CHANGES;
            }
          });
        };

        BuilderView.prototype.reset = function() {
            //console.log('BuilderView reset');
          this.$responseFields.html('');
          return this.addAll();
        };

        BuilderView.prototype.render = function() {
            //console.log('BuilderView render');
          var subview, _i, _len, _ref5;
          this.$el.html(Formbuilder.templates['page']());
          this.$fbLeft = this.$el.find('.fb-left');
          this.$responseFields = this.$el.find('.fb-response-fields');
          this.bindWindowScrollEvent();
          this.hideShowNoResponseFields();
          _ref5 = this.SUBVIEWS;
          for (_i = 0, _len = _ref5.length; _i < _len; _i++) {
            subview = _ref5[_i];
            new subview({
              parentView: this
            }).render();
          }
          return this;
        };

        BuilderView.prototype.bindWindowScrollEvent = function() {
            //console.log('BuilderView bindWindowScrollEvent');
          var _this = this;
          return $(window).on('scroll', function() {
            var maxMargin, newMargin;
            if (_this.$fbLeft.data('locked') === true) {
              return;
            }
            newMargin = Math.max(0, $(window).scrollTop() - _this.$el.offset().top);
            maxMargin = _this.$responseFields.height();
            
            return _this.$fbLeft.css({
              'margin-top': 0,
            });
          });
        };

        BuilderView.prototype.showTab = function(e) {
            //console.log('BuilderView showTab');
          var $el, first_model, target;
          $el = $(e.currentTarget);
          target = $el.data('target');
//          $el.closest('li').addClass('active').siblings('li').removeClass('active');
    //      $(target).addClass('active').siblings('.fb-tab-pane').removeClass('active');
          if (target !== '#editField') {
            this.unlockLeftWrapper();
          }
          if (target === '#editField' && !this.editView && (first_model = this.collection.models[0])) {
            return this.createAndShowEditView(first_model);
          }
        };

        BuilderView.prototype.addOne = function(responseField, _, options) {
            //console.log('BuilderView addOne');
          var $replacePosition, view;
          view = new ViewFieldView({
            model: responseField,
            parentView: this
          });
          if (options.$replaceEl != null) {
            return options.$replaceEl.replaceWith(view.render().el);
          } else if ((options.position == null) || options.position === -1) {
            return this.$responseFields.append(view.render().el);
          } else if (options.position === 0) {
            return this.$responseFields.prepend(view.render().el);
          } else if (($replacePosition = this.$responseFields.find(".fb-field-wrapper").eq(options.position))[0]) {
            return $replacePosition.before(view.render().el);
          } else {
            return this.$responseFields.append(view.render().el);
          }
        };

        BuilderView.prototype.setSortable = function() {
            //console.log('BuilderView setSortable');
          var _this = this;
          if (this.$responseFields.hasClass('ui-sortable')) {
            this.$responseFields.sortable('destroy');
          }
          this.$responseFields.sortable({
            forcePlaceholderSize: true,
            placeholder: 'sortable-placeholder',
            stop: function(e, ui) {
              var rf;
              if (ui.item.data('field-type')) {
                rf = _this.collection.create(Formbuilder.helpers.defaultFieldAttrs(ui.item.data('field-type')), {
                  $replaceEl: ui.item
                });
                _this.createAndShowEditView(rf);
              }
              _this.handleFormUpdate();
              return true;
            },
            update: function(e, ui) {
              if (!ui.item.data('field-type')) {
                return _this.ensureEditViewScrolled();
              }
            }
          });
          return this.setDraggable();
        };

        BuilderView.prototype.setDraggable = function() {
            //console.log('BuilderView setDraggable');
          var $addFieldButtons,
            _this = this;
          $addFieldButtons = this.$el.find("[data-field-type]");
          return $addFieldButtons.draggable({
            connectToSortable: this.$responseFields,
            helper: function() {
              var $helper;
              $helper = $("<div class='response-field-draggable-helper' />");
              $helper.css({
                width: _this.$responseFields.width(),
                height: '80px'
              });
              return $helper;
            }
          });
        };

        BuilderView.prototype.addAll = function() {
            //console.log('BuilderView addAll');
            //console.log('addAll');
          this.collection.each(this.addOne, this);
          return this.setSortable();
        };

        BuilderView.prototype.hideShowNoResponseFields = function() {
            //console.log('BuilderView hideShowNoResponseFields');
            $('.D_dragin').hide();
          return this.$el.find(".fb-no-response-fields")[this.collection.length > 0 ? 'hide' : 'show']();
        };

        BuilderView.prototype.addField = function(e) {
            //console.log('BuilderView addField');
          var field_type;
          field_type = $(e.currentTarget).data('field-type');
          return this.createField(Formbuilder.helpers.defaultFieldAttrs(field_type));
        };

        BuilderView.prototype.createField = function(attrs, options) {
          var rf;
          rf = this.collection.create(attrs, options);
          this.createAndShowEditView(rf);
          return this.handleFormUpdate();
        };

        BuilderView.prototype.createAndShowEditView = function(model) {
          //console.log('BuilderView createAndShowEditView');
          if($('.D_dragin').length == 0)
            $('body').append('<div class="D_dragin fb-tabs"><a  data-target=\'#editField\' style="width:0px;height:0px;"></a><div class=\'fb-tab-pane\' id=\'editField\'>\n  <div class=\'fb-edit-field-wrapper\'></div>\n</div></div>');
          else
              $('.D_dragin').show();
          var $newEditEl, $responseFieldEl;
          $responseFieldEl = this.$el.find(".fb-field-wrapper").filter(function() {
            return $(this).data('cid') === model.cid;
          });
          if($responseFieldEl.hasClass('editing')) return false;
          $responseFieldEl.addClass('editing').siblings('.fb-field-wrapper').removeClass('editing');
          
          if (this.editView) {
//            if (this.editView.model.cid === model.cid) {
//              $("a[data-target=\"#editField\"]").click();
//              this.scrollLeftWrapper($responseFieldEl);
//              return;
//            }
//            this.editView.remove();
          }
          this.editView = new EditFieldView({
            model: model,
            parentView: this
          });
          $newEditEl = this.editView.render().$el;
          $(".fb-edit-field-wrapper").html($newEditEl);
          $("a[data-target=\"#editField\"]").click();
          this.scrollLeftWrapper($responseFieldEl);
//          //console.log($responseFieldEl);
        var top = $responseFieldEl.offset().top - $('.D_dragin').height()/2;
        if(top < 0) top = 0;
          $('.D_dragin').css('top',top + 'px');
          return this;
        };

        BuilderView.prototype.ensureEditViewScrolled = function() {
          if (!this.editView) {
            return;
          }
          return this.scrollLeftWrapper($(".fb-field-wrapper.editing"));
        };

        BuilderView.prototype.scrollLeftWrapper = function($responseFieldEl) {
//          var _this = this;
//          this.unlockLeftWrapper();
//          if (!$responseFieldEl[0]) {
//            return;
//          }
//          return $.scrollWindowTo((this.$el.offset().top + $responseFieldEl.offset().top) - this.$responseFields.offset().top, 200, function() {
//            return _this.lockLeftWrapper();
//          });
        };

        BuilderView.prototype.lockLeftWrapper = function() {
          return this.$fbLeft.data('locked', true);
        };

        BuilderView.prototype.unlockLeftWrapper = function() {
          return this.$fbLeft.data('locked', false);
        };

        BuilderView.prototype.handleFormUpdate = function() {
          if (this.updatingBatch) {
            return;
          }
          this.formSaved = false;
          return this.saveFormButton.removeAttr('disabled').text(Formbuilder.options.dict.SAVE_FORM);
        };

        BuilderView.prototype.saveForm = function(e) {
          var payload;
          if (this.formSaved) {
            return;
          }
          this.formSaved = true;
          this.saveFormButton.attr('disabled', true).text(Formbuilder.options.dict.ALL_CHANGES_SAVED);
          this.collection.sort();
          payload = JSON.stringify({
            fields: this.collection.toJSON()
          });
          if (Formbuilder.options.HTTP_ENDPOINT) {
            this.doAjaxSave(payload);
          }
          return this.formBuilder.trigger('save', payload);
        };

        BuilderView.prototype.doAjaxSave = function(payload) {
          var _this = this;
          return $.ajax({
            url: Formbuilder.options.HTTP_ENDPOINT,
            type: Formbuilder.options.HTTP_METHOD,
            data: payload,
            contentType: "application/json",
            success: function(data) {
              var datum, _i, _len, _ref5;
              _this.updatingBatch = true;
              for (_i = 0, _len = data.length; _i < _len; _i++) {
                datum = data[_i];
                if ((_ref5 = _this.collection.get(datum.cid)) != null) {
                  _ref5.set({
                    id: datum.id
                  });
                }
                _this.collection.trigger('sync');
              }
              return _this.updatingBatch = void 0;
            }
          });
        };

        return BuilderView;

      })(Backbone.View);

      Formbuilder = (function() {
        Formbuilder.helpers = {
          defaultFieldAttrs: function(field_type) {
            var attrs, _base;
            attrs = {};
            attrs[Formbuilder.options.mappings.LABEL] = 'Untitled';
            attrs[Formbuilder.options.mappings.FIELD_TYPE] = field_type;
            attrs[Formbuilder.options.mappings.REQUIRED] = true;
            attrs['field_options'] = {};
            return (typeof (_base = Formbuilder.fields[field_type]).defaultAttributes === "function" ? _base.defaultAttributes(attrs) : void 0) || attrs;
          },
          simple_format: function(x) {
            return x != null ? x.replace(/\n/g, '<br />') : void 0;
          }
        };

        Formbuilder.options = {
          BUTTON_CLASS: 'fb-button',
          HTTP_ENDPOINT: '',
          HTTP_METHOD: 'POST',
          AUTOSAVE: true,
          CLEAR_FIELD_CONFIRM: false,
          mappings: {
            SIZE: 'field_options.size',
            UNITS: 'field_options.units',
            LABEL: 'label',
            NAME: 'name',
            STATUS: 'status',
            MAPPING_ID: 'mapping_id',
            FIELD_TYPE: 'field_type',
            REQUIRED: 'required',
            ADMIN_ONLY: 'admin_only',
            JS: 'js',
            CALLFUNCTION: 'field_options.callfunction',
            NAME2: 'field_options.name2',
            ATTRIBUTES: 'field_options.attributes',
            OPTIONS: 'field_options.options',
            DESCRIPTION: 'field_options.description',
            INCLUDE_OTHER: 'field_options.include_other_option',
            INCLUDE_BLANK: 'field_options.include_blank_option',
            INTEGER_ONLY: 'field_options.integer_only',
            NAME2: 'field_options.name2',
            MIN: 'field_options.min',
            MAX: 'field_options.max',
            MINLENGTH: 'field_options.minlength',
            MAXLENGTH: 'field_options.maxlength',
            LENGTH_UNITS: 'field_options.min_max_length_units'
          },
          dict: {
            ALL_CHANGES_SAVED: 'All changes saved',
            SAVE_FORM: 'Save form',
            UNSAVED_CHANGES: 'You have unsaved changes. If you leave this page, you will lose those changes!'
          }
        };

        Formbuilder.fields = {};

        Formbuilder.inputFields = {};

        Formbuilder.nonInputFields = {};

        Formbuilder.registerField = function(name, opts) {
          var x, _i, _len, _ref5;
          _ref5 = ['view', 'edit'];
          for (_i = 0, _len = _ref5.length; _i < _len; _i++) {
            x = _ref5[_i];
            opts[x] = _.template(opts[x]);
          }
          opts.field_type = name;
          Formbuilder.fields[name] = opts;
          if (opts.type === 'non_input') {
            return Formbuilder.nonInputFields[name] = opts;
          } else {
            return Formbuilder.inputFields[name] = opts;
          }
        };

        function Formbuilder(opts) {
          var args;
          if (opts == null) {
            opts = {};
          }
          _.extend(this, Backbone.Events);
          args = _.extend(opts, {
            formBuilder: this
          });
          this.mainView = new BuilderView(args);
        }

        return Formbuilder;

      })();

      window.Formbuilder = Formbuilder;

      if (typeof module !== "undefined" && module !== null) {
        module.exports = Formbuilder;
      } else {
        window.Formbuilder = Formbuilder;
      }

    }).call(this);

    
    (function() {
      Formbuilder.registerField('checkbox', {
        order: 9,
        view: "<% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>\n  <div>\n    <label class='fb-option'>\n      <input type='checkbox' <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'checked' %> onclick=\"javascript: return false;\" />\n      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>\n    </label>\n  </div>\n<% } %>\n\n<% if (rf.get(Formbuilder.options.mappings.INCLUDE_OTHER)) { %>\n  <div class='other-option'>\n    <label class='fb-option'>\n      <input type='checkbox' />\n      Other\n    </label>\n\n    <input type='text' />\n  </div>\n<% } %>",
        edit: "<%= Formbuilder.templates['edit/checkbox']({ includeOther: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Checkbox",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
          attrs.field_options.options = [
            {
              label: "",
              checked: false
            }
          ];
          return attrs;
        }
      });

    }).call(this);
    
    (function() {
      Formbuilder.registerField('multiselect', {
        order: 11,
        view: "<% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>\n  <div>\n    <label class='fb-option'>\n      <input type='checkbox' <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'checked' %> onclick=\"javascript: return false;\" />\n      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>\n    </label>\n  </div>\n<% } %>\n\n<% if (rf.get(Formbuilder.options.mappings.INCLUDE_OTHER)) { %>\n  <div class='other-option'>\n    <label class='fb-option'>\n      <input type='checkbox' />\n      Other\n    </label>\n\n    <input type='text' />\n  </div>\n<% } %>",
        edit: "<%= Formbuilder.templates['edit/options']({ includeOther: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-caret-down\"></span></span> Multi select",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
          attrs.field_options.options = [
            {
              label: "",
              checked: false
            }, {
              label: "",
              checked: false
            }
          ];
          return attrs;
        }
      });

    }).call(this);

    
    (function() {
      Formbuilder.registerField('checkboxsmall', {
        order: 9,
        view: "<% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>\n  <div>\n    <label class='fb-option'>\n      <input type='checkbox' <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'checked' %> onclick=\"javascript: return false;\" />\n      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>\n    </label>\n  </div>\n<% } %>\n\n<% if (rf.get(Formbuilder.options.mappings.INCLUDE_OTHER)) { %>\n  <div class='other-option'>\n    <label class='fb-option'>\n      <input type='checkbox' />\n      Other\n    </label>\n\n    <input type='text' />\n  </div>\n<% } %>",
        edit: "<%= Formbuilder.templates['edit/options']({ includeOther: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Checkbox small",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
          attrs.field_options.options = [
            {
              label: "",
              checked: false
            }, {
              label: "",
              checked: false
            }
          ];
          return attrs;
        }
      });

    }).call(this);


    
    (function() {
      Formbuilder.registerField('checkboxbig', {
        order: 10,
        view: "<% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>\n  <div>\n    <label class='fb-option'>\n      <input type='checkbox' <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'checked' %> onclick=\"javascript: return false;\" />\n      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>\n    </label>\n  </div>\n<% } %>\n\n<% if (rf.get(Formbuilder.options.mappings.INCLUDE_OTHER)) { %>\n  <div class='other-option'>\n    <label class='fb-option'>\n      <input type='checkbox' />\n      Other\n    </label>\n\n    <input type='text' />\n  </div>\n<% } %>",
        edit: "<%= Formbuilder.templates['edit/options']({ includeOther: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Checkbox big",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
          attrs.field_options.options = [
            {
              label: "",
              checked: false
            }, {
              label: "",
              checked: false
            }
          ];
          return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('date', {
        order: 20,
        view: "<div class='input-line'>"+FORMAT_DATE_INPUT+"</div>",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-calendar\"></span></span> Date",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('daterangepicker', {
        order: 20,
        view: "<div class='input-line'>"+FORMAT_DATE_INPUT+" - "+FORMAT_DATE_INPUT+"</div>",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-calendar\"></span></span> DR Picker",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('datetimepicker', {
        order: 20,
        view: "<div class='input-line'>"+FORMAT_DATE_INPUT+" - H:i:s AM/PM</div>",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-calendar\"></span></span> DT Picker",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('rangepicker', {
        order: 20,
        view: "<div class='input-line'>"+FORMAT_DATE_INPUT+" - "+FORMAT_DATE_INPUT+"</div>",
        edit: "<%= Formbuilder.templates['edit/rangepicker']({ includeBlank: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-calendar\"></span></span> Range Picker",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        }
      });

    }).call(this);
    
    
    (function() {
      Formbuilder.registerField('dropdown', {
        order: 12,
        view: "<select>\n  <% if (rf.get(Formbuilder.options.mappings.INCLUDE_BLANK)) { %>\n    <option value=''></option>\n  <% } %>\n\n  <% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>\n    <option <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'selected' %>>\n      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>\n    </option>\n  <% } %>\n</select>",
        edit: "<%= Formbuilder.templates['edit/options']({ includeBlank: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-caret-down\"></span></span> Dropdown",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            attrs.field_options.attributes = [
                {
                    label: "",
                    value: "",
                }
            ];
            attrs.field_options.options = [
                {
                  label: "",
                  checked: false
                }, {
                  label: "",
                  checked: false
                }
            ];
          attrs.field_options.include_blank_option = false;
          return attrs;
        }
      });

    }).call(this);
    
    (function() {
      Formbuilder.registerField('dropdowntext', {
        order: 12,
        view: "<select>\n  <% if (rf.get(Formbuilder.options.mappings.INCLUDE_BLANK)) { %>\n    <option value=''></option>\n  <% } %>\n\n  <% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>\n    <option <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'selected' %>>\n      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>\n    </option>\n  <% } %>\n</select>",
        edit: "<%= Formbuilder.templates['edit/options']({ includeBlank: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Dropdowntext",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            attrs.field_options.attributes = [
                {
                    label: "",
                    value: "",
                }
            ];
            attrs.field_options.options = [
                {
                  label: "",
                  checked: false
                }, {
                  label: "",
                  checked: false
                }
            ];
          attrs.field_options.include_blank_option = false;
          return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('email', {
        order: 40,
        view: "<input type='text' class='rf-size-<%= rf.get(Formbuilder.options.mappings.SIZE) %>' />",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-envelope-o\"></span></span> Email",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        }
      });

    }).call(this);

    
    (function() {
      Formbuilder.registerField('landingpage', {
        order: 25,
        view: "<div class='input-line'>Landing page</div>",
        edit: "<%= Formbuilder.templates['edit/array']({rf:rf}) %> ",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-clock-o\"></span></span> Landing page",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
            attrs.field_options.attributes = [
                {label:'data-name',value:'label,value'},
                {label:'data-cl',value:''},
                {label:'data-placeholder',value:'label,value'},
                {label:'data-type',value:'text,text'},
                {label:'data-width',value:'405,405'},
            ];
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('replaceattribute', {
        order: 40,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-envelope-o\"></span></span> Replace text",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        },
      });

    }).call(this);
    
    
    (function() {
      Formbuilder.registerField('replacecontent', {
        order: 40,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-envelope-o\"></span></span> Replace content",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        },
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('content', {
        order: 102,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Content",
        defaultAttributes: function(attrs) {
          attrs.required = false;
		attrs.status = true;
          return attrs;
        }
      });

    }).call(this);
    
    (function() {
      Formbuilder.registerField('contentsmall', {
        order: 102,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Content small",
        defaultAttributes: function(attrs) {
          attrs.required = false;
		attrs.status = true;
          return attrs;
        }
      });

    }).call(this);
    
    (function() {
      Formbuilder.registerField('map', {
        order: 200,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Map",
        defaultAttributes: function(attrs) {
          attrs.required = false;
		attrs.status = true;
          return attrs;
        }
      });

    }).call(this);
    
    (function() {
      Formbuilder.registerField('tokeninput', {
        order: 9,
        view: "<% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>\n  <div>\n    <label class='fb-option'>\n      <input type='checkbox' <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'checked' %> onclick=\"javascript: return false;\" />\n      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>\n    </label>\n  </div>\n<% } %>\n\n<% if (rf.get(Formbuilder.options.mappings.INCLUDE_OTHER)) { %>\n  <div class='other-option'>\n    <label class='fb-option'>\n      <input type='checkbox' />\n      Other\n    </label>\n\n    <input type='text' />\n  </div>\n<% } %>",
        edit: "<%= Formbuilder.templates['edit/options']({ includeOther: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Tokeninput",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
          attrs.field_options.options = [
            {
              label: "",
              checked: false
            }, {
              label: "",
              checked: false
            }
          ];
          return attrs;
        }
      });

    }).call(this);

    
    (function() {
      Formbuilder.registerField('hidden', {
        order: 102,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Hidden",
        defaultAttributes: function(attrs) {
          attrs.required = false;
		attrs.status = true;
          return attrs;
        }
      });

    }).call(this);
    

    (function() {
      Formbuilder.registerField('oneimage', {
        order: 103,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> One Image",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        },
      });

    }).call(this);
    
    (function() {
      Formbuilder.registerField('oneswf', {
        order: 200,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> One Swf",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
            return attrs;
        },
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('manyimages', {
        order: 104,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Many images",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        },
      });

    }).call(this);
    
    (function() {
      Formbuilder.registerField('onefile', {
        order: 105,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> One file",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        },
      });

    }).call(this);
    
        (function() {
      Formbuilder.registerField('textsize', {
        order: 0,
        view: "<input type='text' class='rf-size-<%= rf.get(Formbuilder.options.mappings.SIZE) %>' />",
        edit: "<%= Formbuilder.templates['edit/attributes']({rf:rf}) %><%= Formbuilder.templates['edit/size']({rf:rf}) %>\n<%= Formbuilder.templates['edit/min_max_length']() %>",
        addButton: "<span class='symbol'><span class='fa fa-font'></span></span> Textsize",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
            attrs.field_options.size = 'large';
            return attrs;
        }
      });

    }).call(this);
    

    (function() {
      Formbuilder.registerField('manyfiles', {
        order: 106,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Many files",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        },
      });

    }).call(this);
    
    
    (function() {
      Formbuilder.registerField('multimenu', {
        order: 107,
        view: "",
        edit: "<%= Formbuilder.templates['edit/attributes']({rf:rf}) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Multimenu",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
            attrs.field_options.attributes = [
                {label: 'data-url1',value:'/settings/load/multimenu'},
                {label: 'data-url2',value:'/settings/load/menu'},
                {label: 'data-classcommon',value:''},
            ];
            return attrs;
        },
      });

    }).call(this); 
    
    (function() {
      Formbuilder.registerField('multiallmenu', {
        order: 107,
        view: "",
        edit: "<%= Formbuilder.templates['edit/mapping']({rf:rf}) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-square-o\"></span></span> Multiallmenu",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
            return attrs;
        },
      });

    }).call(this);
    
    
    (function() {
      Formbuilder.registerField('number', {
        order: 30,
        view: "<input type='text' />\n<% if (units = rf.get(Formbuilder.options.mappings.UNITS)) { %>\n  <%= units %>\n<% } %>",
        edit: "<%= Formbuilder.templates['edit/number']({rf:rf}) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-number\">123</span></span> Number",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        },
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('textarea', {
        order: 5,
        view: "<textarea class='rf-size-<%= rf.get(Formbuilder.options.mappings.SIZE) %>'></textarea>",
        edit: "<%= Formbuilder.templates['edit/attributes']({rf:rf}) %><%= Formbuilder.templates['edit/size']({rf:rf}) %>\n<%= Formbuilder.templates['edit/min_max_length']({rf:rf}) %>",
        addButton: "<span class=\"symbol\">&#182;</span> textarea",
        defaultAttributes: function(attrs) {
            attrs.field_options.size = 'small';
            attrs.required = false;
            attrs.status = true;
            attrs.field_options.size = 'large';
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('radio', {
        order: 15,
        view: "<% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>\n  <div>\n    <label class='fb-option'>\n      <input type='radio' <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'checked' %> onclick=\"javascript: return false;\" />\n      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>\n    </label>\n  </div>\n<% } %>\n\n<% if (rf.get(Formbuilder.options.mappings.INCLUDE_OTHER)) { %>\n  <div class='other-option'>\n    <label class='fb-option'>\n      <input type='radio' />\n      Other\n    </label>\n\n    <input type='text' />\n  </div>\n<% } %>",
        edit: "<%= Formbuilder.templates['edit/options']({ includeOther: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-circle-o\"></span></span> Radio",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
          attrs.field_options.options = [
            {
              label: "",
              checked: false
            }, {
              label: "",
              checked: false
            }
          ];
          return attrs;
        }
      });

    }).call(this);
    (function() {
      Formbuilder.registerField('onoff', {
        order: 15,
        view: "<% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>\n  <div>\n    <label class='fb-option'>\n      <input type='radio' <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'checked' %> onclick=\"javascript: return false;\" />\n      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>\n    </label>\n  </div>\n<% } %>\n\n<% if (rf.get(Formbuilder.options.mappings.INCLUDE_OTHER)) { %>\n  <div class='other-option'>\n    <label class='fb-option'>\n      <input type='radio' />\n      Other\n    </label>\n\n    <input type='text' />\n  </div>\n<% } %>",
        edit: "<%= Formbuilder.templates['edit/onoff']({ includeOther: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-circle-o\"></span></span> On/Off",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            attrs.field_options.options = [
              {
                label: "ON/OFF",
                checked: true
              }
            ];
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('text', {
        order: 0,
        view: "<input type='text' class='rf-size-<%= rf.get(Formbuilder.options.mappings.SIZE) %>' />",
        edit: "<%= Formbuilder.templates['edit/attributes']({rf:rf}) %><%= Formbuilder.templates['edit/size']({rf:rf}) %>\n<%= Formbuilder.templates['edit/min_max_length']() %>",
        addButton: "<span class='symbol'><span class='fa fa-font'></span></span> Text",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
            attrs.field_options.size = 'large';
            return attrs;
        }
      });

    }).call(this);
    
    
    (function() {
      Formbuilder.registerField('colorpicker', {
        order: 100,
        view: "<input type='text' class='rf-size-<%= rf.get(Formbuilder.options.mappings.SIZE) %>' />",
        edit: "<%= Formbuilder.templates['edit/size']({rf:rf}) %>",
        addButton: "<span class='symbol'><span class='fa fa-font'></span></span> Color Picker",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            attrs.field_options.size = 'small';
            return attrs;
        }
      });

    }).call(this);
    
    
    (function() {
      Formbuilder.registerField('customercolorpicker', {
        order: 101,
        view: "<select>\n  <% if (rf.get(Formbuilder.options.mappings.INCLUDE_BLANK)) { %>\n    <option value=''></option>\n  <% } %>\n\n  <% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>\n    <option <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'selected' %>>\n      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>\n    </option>\n  <% } %>\n</select>",
        edit: "<%= Formbuilder.templates['edit/options']({ includeBlank: true,rf:rf }) %>",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-font\"></span></span> C Colorpicker",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
          attrs.field_options.options = [
            {label: "#ac725e",value: "#ac725e",checked: false},
            {label: "#d06b64",value: "#d06b64",checked: false},
            {label: "#f83a22",value: "#f83a22",checked: false},
            {label: "#fa573c",value: "#fa573c",checked: false},
            {label: "#ff7537",value: "#ff7537",checked: false},
            {label: "#ffad46",value: "#ffad46",checked: false},
            {label: "#42d692",value: "#42d692",checked: false},
            {label: "#16a765",value: "#16a765",checked: false},
            {label: "#7bd148",value: "#7bd148",checked: false},
            {label: "#b3dc6c",value: "#b3dc6c",checked: false},
            {label: "#fbe983",value: "#fbe983",checked: false},
            {label: "#fad165",value: "#fad165",checked: false},
            {label: "#92e1c0",value: "#92e1c0",checked: false},
            {label: "#9fe1e7",value: "#9fe1e7",checked: false},
            {label: "#9fc6e7",value: "#9fc6e7",checked: false},
            {label: "#4986e7",value: "#4986e7",checked: false},
            {label: "#b99aff",value: "#b99aff",checked: false},
            {label: "#9a9cff",value: "#9a9cff",checked: false},
            {label: "#c2c2c2",value: "#c2c2c2",checked: false},
            {label: "#cabdbf",value: "#cabdbf",checked: false},
            {label: "#cca6ac",value: "#cca6ac",checked: false},
            {label: "#f691b2",value: "#f691b2",checked: false},
            {label: "#cd74e6",value: "#cd74e6",checked: false},
            {label: "#a47ae2",value: "#a47ae2",checked: false},
            {label: "#555",value: "#555",checked: false},
          ];
          attrs.field_options.include_blank_option = false;
          return attrs;
        }
      });

    }).call(this);
    
    
    (function() {
      Formbuilder.registerField('password', {
        order: 0,
        view: "<input type='text' class='rf-size-<%= rf.get(Formbuilder.options.mappings.SIZE) %>' />",
        edit: "<%= Formbuilder.templates['edit/size']({rf:rf}) %>\n<%= Formbuilder.templates['edit/min_max_length']({rf:rf}) %>",
        addButton: "<span class='symbol'><span class='fa fa-font'></span></span> Password",
        defaultAttributes: function(attrs) {
		attrs.required = true;
		attrs.status = true;
          attrs.field_options.size = 'large';
          return attrs;
        }
      });

    }).call(this);


    (function() {
      Formbuilder.registerField('icon', {
        order: 120,
        view: "",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-clock-o\"></span></span> Icon",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('time', {
        order: 25,
        view: "<div class='input-line'>h:i:s</div>",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-clock-o\"></span></span> Time",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('website', {
        order: 35,
        view: "<input type='text' placeholder='http://' />",
        edit: "",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-link\"></span></span> Website",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('array', {
        order: 25,
        view: "<div class='input-line'>Array</div>",
        edit: "<%= Formbuilder.templates['edit/array']({rf:rf}) %> ",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-clock-o\"></span></span> Array",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
            attrs.field_options.attributes = [
                {label:'data-count',value:'2'},
                {label:'data-placeholder',value:'label,value'},
            ];
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('arrayjson', {
        order: 25,
        view: "<div class='input-line'>Array json</div>",
        edit: "<%= Formbuilder.templates['edit/array']({rf:rf}) %> ",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-clock-o\"></span></span> Array json",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
            attrs.field_options.attributes = [
                {label:'data-name1',value:''},
                {label:'data-name2',value:''},
                {label:'data-placeholder',value:'label,value'},
            ];
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('arraymanyjson', {
        order: 25,
        view: "<div class='input-line'>Array many json</div>",
        edit: "<%= Formbuilder.templates['edit/array']({rf:rf}) %> ",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-clock-o\"></span></span> Array many json",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
            attrs.field_options.attributes = [
                {label:'data-name',value:'label,value'},
                {label:'data-cl',value:''},
                {label:'data-placeholder',value:'label,value'},
                {label:'data-type',value:'text,text'},
                {label:'data-width',value:'405,405'},
            ];
            return attrs;
        }
      });

    }).call(this);


    (function() {
      Formbuilder.registerField('json', {
        order: 25,
        view: "<div class='input-line'>Json</div>",
        edit: "<%= Formbuilder.templates['edit/array']({rf:rf}) %> ",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-clock-o\"></span></span> Json",
        defaultAttributes: function(attrs) {
            attrs.required = false;
            attrs.status = true;
            attrs.field_options.attributes = [
                {label:'data-placeholder',value:'name,value'},
            ];
            return attrs;
        }
      });

    }).call(this);

    (function() {
      Formbuilder.registerField('role', {
        order: 120,
        view: "",
        edit: "<%= Formbuilder.templates['edit/mapping']({rf:rf}) %> ",
        addButton: "<span class=\"symbol\"><span class=\"fa fa-clock-o\"></span></span> Role",
        defaultAttributes: function(attrs) {
            attrs.required = false;
		attrs.status = true;
            return attrs;
        }
      });

    }).call(this);




    this["Formbuilder"] = this["Formbuilder"] || {};
    this["Formbuilder"]["templates"] = this["Formbuilder"]["templates"] || {};

    this["Formbuilder"]["templates"]["edit/base"] = function(obj) {
        //console.log("Formbuilder edit/base");
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p +=
    ((__t = ( Formbuilder.templates['edit/base_header']({rf:rf}) )) == null ? '' : __t) +
    '\n' +
    ((__t = ( Formbuilder.templates['edit/common']({rf:rf}) )) == null ? '' : __t) +
    '\n' +
    ((__t = ( Formbuilder.fields[rf.get(Formbuilder.options.mappings.FIELD_TYPE)].edit({rf: rf}) )) == null ? '' : __t) +
    '\n';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["edit/base_header"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-field-label\'>\n  <span data-rv-text="model.' +
    ((__t = ( Formbuilder.options.mappings.LABEL )) == null ? '' : __t) +
    '"></span>\n  <code class=\'field-type\' data-rv-text=\'model.' +
    ((__t = ( Formbuilder.options.mappings.FIELD_TYPE )) == null ? '' : __t) +
    '\'></code>\n  <span class=\'fa fa-arrow-right pull-right\'></span>\n</div>';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["edit/base_non_input"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p +=
    ((__t = ( Formbuilder.templates['edit/base_header']() )) == null ? '' : __t) +
    '\n' +
    ((__t = ( Formbuilder.fields[rf.get(Formbuilder.options.mappings.FIELD_TYPE)].edit({rf: rf}) )) == null ? '' : __t) +
    '\n';

    }
    return __p
    };

this["Formbuilder"]["templates"]["edit/checkbox"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
    function print() { __p += __j.call(arguments, '') }
    with (obj) {
    __p += '<div class=\'fb-edit-section-header\'>Check</div>';
    __p += '\n<div class=\'option\' data-rv-each-option=\'model.' +
    ((__t = ( Formbuilder.options.mappings.OPTIONS )) == null ? '' : __t) +
    '\'>\n  <input type="checkbox" class=\'js-default-updated\' data-rv-checked="option:checked" />\n</div>\n';
    }
    return __p
    };



    this["Formbuilder"]["templates"]["edit/checkboxes"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<label>\n  <input type=\'checkbox\' data-rv-checked=\'model.' +
    ((__t = ( Formbuilder.options.mappings.REQUIRED )) == null ? '' : __t) +
    '\' />\n  Required\n</label>\n<!-- label>\n  <input type=\'checkbox\' data-rv-checked=\'model.' +
    ((__t = ( Formbuilder.options.mappings.ADMIN_ONLY )) == null ? '' : __t) +
    '\' />\n  Admin only\n</label -->';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["edit/common"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    
    with (obj) {
    __p += ((__t = ( Formbuilder.templates['edit/name']({rf:rf}) )) == null ? '' : __t)
        + '<div class=\'fb-edit-section-header\'>Label</div>\n\n' 
        + '<div class=\'fb-common-wrapper\'>\n  ' 
        + '<div class=\'fb-label-description\'>\n    ' +
    ((__t = ( Formbuilder.templates['edit/label_description']({rf:rf}) )) == null ? '' : __t) +
    '\n  </div>\n  <div class=\'fb-common-checkboxes\'>\n    ' +
    ((__t = ( Formbuilder.templates['edit/checkboxes']({rf:rf}) )) == null ? '' : __t) + '\n  </div>\n ' +
    ((__t = ( Formbuilder.templates['edit/status']() )) == null ? '' : __t) +
    ' <div class=\'fb-clear\'></div>\n</div>\n';
    __p += ((__t = ( Formbuilder.templates['edit/js']({rf:rf}) )) == null ? '' : __t);

    }
    return __p
    };
    
    
    this["Formbuilder"]["templates"]["edit/status"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
    function print() { __p += __j.call(arguments, '') }
    with (obj) {
        __p += '<div class=\'fb-edit-section-header\'>Status</div>\n<input type="checkbox" data-rv-checked="model.' +
            ((__t = ( Formbuilder.options.mappings.STATUS )) == null ? '' : __t) +
            '">\n';
            }
    return __p
    };

    this["Formbuilder"]["templates"]["edit/integer_only"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-edit-section-header\'>Integer only</div>\n<label>\n  <input type=\'checkbox\' data-rv-checked=\'model.' +
    ((__t = ( Formbuilder.options.mappings.INTEGER_ONLY )) == null ? '' : __t) +
    '\' />\n  Only accept integers\n</label>\n';

    }
    return __p
    };


    this["Formbuilder"]["templates"]["edit/js"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class="fb-common-wrapper"><div class="fb-edit-section-header">Js:</div> <div class="fb-label-description"><textarea data-rv-input=\'model.' +
    ((__t = ( Formbuilder.options.mappings.JS )) == null ? '' : __t) +
    '\'\n  placeholder=\'Js\'></textarea></div></div>';
    }
    return __p
    };
    
    this["Formbuilder"]["templates"]["edit/callfunction"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class="fb-common-wrapper"><div class="fb-edit-section-header">Call function:</div> <div class="fb-label-description"><textarea data-rv-input=\'model.' +
    ((__t = ( Formbuilder.options.mappings.CALLFUNCTION )) == null ? '' : __t) +
    '\'\n  placeholder=\'Call function\'></textarea></div></div>';
    }
    return __p
    };

    this["Formbuilder"]["templates"]["edit/label_description"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<input type=\'text\' data-rv-input=\'model.' +
    ((__t = ( Formbuilder.options.mappings.LABEL )) == null ? '' : __t) +
    '\' />\n<textarea style="margin-top:10px;" data-rv-input=\'model.' +
    ((__t = ( Formbuilder.options.mappings.DESCRIPTION )) == null ? '' : __t) +
    '\'\n  placeholder=\'Add a longer description to this field\'></textarea>';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["edit/min_max"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-edit-section-header\'>Minimum / Maximum</div>\n\nAbove\n<input type="text" data-rv-input="model.' +
    ((__t = ( Formbuilder.options.mappings.MIN )) == null ? '' : __t) +
    '" style="width: 30px" />\n\n&nbsp;&nbsp;\n\nBelow\n<input type="text" data-rv-input="model.' +
    ((__t = ( Formbuilder.options.mappings.MAX )) == null ? '' : __t) +
    '" style="width: 30px" />\n';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["edit/min_max_length"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-edit-section-header\'>Length Limit</div>\n\nMin\n<input type="text" data-rv-input="model.' +
    ((__t = ( Formbuilder.options.mappings.MINLENGTH )) == null ? '' : __t) +
    '" style="width: 30px" />\n\n&nbsp;&nbsp;\n\nMax\n<input type="text" data-rv-input="model.' +
    ((__t = ( Formbuilder.options.mappings.MAXLENGTH )) == null ? '' : __t) +
    '" style="width: 30px" />\n\n&nbsp;&nbsp;\n\n<select data-rv-value="model.' +
    ((__t = ( Formbuilder.options.mappings.LENGTH_UNITS )) == null ? '' : __t) +
    '" style="width: auto;">\n  <option value="characters">characters</option>\n  <option value="words">words</option>\n</select>\n';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["edit/array"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
    function print() { __p += __j.call(arguments, '') }
    with (obj) {
    __p += ((__t = ( Formbuilder.templates['edit/options']({rf:rf}) )) == null ? '' : __t);
    }
    return __p
    };
    
    this["Formbuilder"]["templates"]["edit/number"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
    function print() { __p += __j.call(arguments, '') }
    with (obj) {
    __p += ((__t = ( Formbuilder.templates['edit/attributes']({rf:rf}) )) == null ? '' : __t);
    }
    return __p
    };
    

    this["Formbuilder"]["templates"]["edit/options"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
    function print() { __p += __j.call(arguments, '') }
    with (obj) {
    __p += ((__t = ( Formbuilder.templates['edit/attributes']({rf:rf}) )) == null ? '' : __t);
    __p += ((__t = ( Formbuilder.templates['edit/mapping']() )) == null ? '' : __t);
    __p += ((__t = ( Formbuilder.templates['edit/callfunction']({rf:rf}) )) == null ? '' : __t);
    __p += '<div class=\'fb-edit-section-header\'>Options</div>\n\n';
     if (typeof includeBlank !== 'undefined'){ ;
    __p += '\n  <label>\n    <input type=\'checkbox\' data-rv-checked=\'model.' +
    ((__t = ( Formbuilder.options.mappings.INCLUDE_BLANK )) == null ? '' : __t) +
    '\' />\n    Include blank\n  </label>\n';
     } ;
    __p += '\n\n<div class=\'option\' data-rv-each-option=\'model.' +
    ((__t = ( Formbuilder.options.mappings.OPTIONS )) == null ? '' : __t) +
    '\'>\n  <input type="checkbox" class=\'js-default-updated\' data-rv-checked="option:checked" />\n '
    + '<input type="text" data-rv-input="option:value" style="width:155px;" class=\'option-label-input\' />\n'
    +' <input type="text" data-rv-input="option:label" style="width:355px;" class=\'option-label-input\' />\n  <a class="js-add-option ' +
    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
    '" title="Add Option"><i class=\'fa fa-plus-circle\'></i></a>\n  <a class="js-remove-option ' +
    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
    '" title="Remove Option"><i class=\'fa fa-minus-circle\'></i></a>\n</div>\n\n';
     if (typeof includeOther !== 'undefined'){ ;
    __p += '\n  <label>\n    <input type=\'checkbox\' data-rv-checked=\'model.' +
    ((__t = ( Formbuilder.options.mappings.INCLUDE_OTHER )) == null ? '' : __t) +
    '\' />\n    Include "other"\n  </label>\n';
     } ;
    __p += '\n\n<div class=\'fb-bottom-add\'>\n  <a class="js-add-option ' +
    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
    '">Add option</a>\n</div>\n';

    }
    return __p
    };


    this["Formbuilder"]["templates"]["edit/attributes"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
    function print() { __p += __j.call(arguments, '') }
    with (obj) {
    __p += '<div class=\'fb-edit-section-header\'>Attributes</div>\n\n';
    __p += '\n\n<div class=\'option_atrributes\' data-rv-each-attributes=\'model.' +
    ((__t = ( Formbuilder.options.mappings.ATTRIBUTES )) == null ? '' : __t) +
    '\'>\n  '
    + '<input type="text" data-rv-input="attributes:label" style="width:155px;" class=\'option-label-input\' />\n'
    +' <input type="text" data-rv-input="attributes:value" style="width:355px;" class=\'option-label-input\' />\n  <a class="js-add-option-attributes ' +
    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
    '" title="Add Option"><i class=\'fa fa-plus-circle\'></i></a>\n  <a class="js-remove-option-attributes ' +
    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
    '" title="Remove Option"><i class=\'fa fa-minus-circle\'></i></a>\n</div>\n\n';
    __p += '\n\n<div class=\'fb-bottom-add\'>\n  <a class="js-add-option-attributes ' +
    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
    '">Add option</a>\n</div>\n';

    }
    return __p
    };


    this["Formbuilder"]["templates"]["edit/onoff"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
    function print() { __p += __j.call(arguments, '') }
    with (obj) {
    __p += ((__t = ( Formbuilder.templates['edit/attributes']({rf:rf}) )) == null ? '' : __t);
    __p += '<div class=\'fb-edit-section-header\'>Options</div>\n\n';
     if (typeof includeBlank !== 'undefined'){ ;
    __p += '\n  <label>\n    <input type=\'checkbox\' data-rv-checked=\'model.' +
    ((__t = ( Formbuilder.options.mappings.INCLUDE_BLANK )) == null ? '' : __t) +
    '\' />\n    Include blank\n  </label>\n';
     } ;
    __p += '\n\n<div class=\'option\' data-rv-each-option=\'model.' +
    ((__t = ( Formbuilder.options.mappings.OPTIONS )) == null ? '' : __t) +
    '\'>\n  <input type="checkbox" class=\'js-default-updated\' data-rv-checked="option:checked" />\n '
    +'ON/OFF';
    }
    return __p
    };
    
    this["Formbuilder"]["templates"]["edit/name"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-edit-section-header\'>Name</div>\n<select id="builder_name" data-rv-value="model.' +
    ((__t = ( Formbuilder.options.mappings.NAME )) == null ? '' : __t) +
    '">\n'+optionName+'</select>\n <input style="width:177px;" id="input_attribute_name" class="form-control" /> <button id="button_add_attribute_name" class="btn btn-primary bnone">Add</button>';

    }
    return __p
    };
    
    this["Formbuilder"]["templates"]["edit/rangepicker"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-edit-section-header\'>Date 2</div>\n<select data-rv-value="model.' +
    ((__t = ( Formbuilder.options.mappings.NAME2 )) == null ? '' : __t) +
    '">\n'+optionName+'</select>\n';

    }
    return __p
    };
    

    
    this["Formbuilder"]["templates"]["edit/mapping"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-edit-section-header\'>Mapping</div>\n<select id="D_htmlmapping" data-href="/admin/buildform/loadlistmapping" data-rv-value="model.' +
    ((__t = ( Formbuilder.options.mappings.MAPPING_ID )) == null ? '' : __t) +
    '">\n'+mappingName+'</select>\n';
//    __p += '<button class="btn btn-primary" id="addmapping" data-href="/admin/mapping/create?code=203" style="border:0px;">Add Mapping</button>';
    }
    return __p
    };


    this["Formbuilder"]["templates"]["edit/size"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-edit-section-header\'>Size</div>\n<select data-rv-value="model.' +
    ((__t = ( Formbuilder.options.mappings.SIZE )) == null ? '' : __t) +
    '">\n  <option value="small">Small</option>\n  <option value="medium">Medium</option>\n  <option value="large">Large</option>\n</select>\n';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["edit/units"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-edit-section-header\'>Units</div>\n<input type="text" data-rv-input="model.' +
    ((__t = ( Formbuilder.options.mappings.UNITS )) == null ? '' : __t) +
    '" />\n';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["page"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p +=
    ((__t = ( Formbuilder.templates['partials/save_button']() )) == null ? '' : __t) +
    '\n' +
    ((__t = ( Formbuilder.templates['partials/left_side']() )) == null ? '' : __t) +
    '\n' +
    ((__t = ( Formbuilder.templates['partials/right_side']() )) == null ? '' : __t) +
    '\n<div class=\'fb-clear\'></div>';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["partials/add_field"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
    function print() { __p += __j.call(arguments, '') }
    with (obj) {
    __p += '<div class=\'fb-tab-pane active\' id=\'addField\'>\n  <div class=\'fb-add-field-types\'>\n    <div class=\'section\'>\n      ';
     _.each(_.sortBy(Formbuilder.inputFields, 'order'), function(f){ ;
    __p += '\n        <a data-field-type="' +
    ((__t = ( f.field_type )) == null ? '' : __t) +
    '" class="' +
    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
    '">\n          ' +
    ((__t = ( f.addButton )) == null ? '' : __t) +
    '\n        </a>\n      ';
     }); ;
    __p += '\n    </div>\n\n    <div class=\'section\'>\n      ';
     _.each(_.sortBy(Formbuilder.nonInputFields, 'order'), function(f){ ;
    __p += '\n        <a data-field-type="' +
    ((__t = ( f.field_type )) == null ? '' : __t) +
    '" class="' +
    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
    '">\n          ' +
    ((__t = ( f.addButton )) == null ? '' : __t) +
    '\n        </a>\n      ';
     }); ;
    __p += '\n    </div>\n  </div>\n</div>\n';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["partials/edit_field"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
//    __p += '<div class=\'fb-tab-pane\' id=\'editField\'>\n  <div class=\'fb-edit-field-wrapper\'></div>\n</div>\n';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["partials/left_side"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-left\'>\n  <ul class=\'fb-tabs\'>\n    <li><a data-target=\'#addField\'>Add new field</a></li>\n    </ul>\n\n  <div class=\'fb-tab-content\'>\n    ' +
    ((__t = ( Formbuilder.templates['partials/add_field']() )) == null ? '' : __t) +
    '\n    ' +
    ((__t = ( Formbuilder.templates['partials/edit_field']() )) == null ? '' : __t) +
    '\n  </div>\n</div>';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["partials/right_side"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'fb-right\'>\n  <div class=\'fb-no-response-fields\'>No response fields</div>\n  <div class=\'fb-response-fields\'></div>\n</div>\n';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["partials/save_button"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
//    __p += '<div class=\'fb-save-wrapper\'>\n  <button class=\'js-save-form ' +
//    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
//    '\'></button>\n</div>';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["view/base"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'subtemplate-wrapper\'>\n  <div class=\'cover\'></div>\n  ' +
    ((__t = ( Formbuilder.templates['view/label']({rf: rf}) )) == null ? '' : __t) +
    '\n\n  ' +
    ((__t = ( Formbuilder.fields[rf.get(Formbuilder.options.mappings.FIELD_TYPE)].view({rf: rf}) )) == null ? '' : __t) +
    '\n\n  ' +
    ((__t = ( Formbuilder.templates['view/description']({rf: rf}) )) == null ? '' : __t) +
    '\n  ' +
    ((__t = ( Formbuilder.templates['view/duplicate_remove']({rf: rf}) )) == null ? '' : __t) +
    '\n</div>\n';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["view/base_non_input"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["view/description"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<span class=\'help-block\'>\n  ' +
    ((__t = ( Formbuilder.helpers.simple_format(rf.get(Formbuilder.options.mappings.DESCRIPTION)) )) == null ? '' : __t) +
    '\n</span>\n';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["view/duplicate_remove"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape;
    with (obj) {
    __p += '<div class=\'actions-wrapper\'>\n  <a class="js-duplicate ' +
    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
    '" title="Duplicate Field"><i class=\'fa fa-plus-circle\'></i></a>\n  <a class="js-clear ' +
    ((__t = ( Formbuilder.options.BUTTON_CLASS )) == null ? '' : __t) +
    '" title="Remove Field"><i class=\'fa fa-minus-circle\'></i></a>\n</div>';

    }
    return __p
    };

    this["Formbuilder"]["templates"]["view/label"] = function(obj) {
    obj || (obj = {});
    var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
    function print() { __p += __j.call(arguments, '') }
    with (obj) {
    __p += '<label>\n  <span>' +
    ((__t = ( Formbuilder.helpers.simple_format(rf.get(Formbuilder.options.mappings.LABEL)) )) == null ? '' : __t) +
    '\n  ';
     if (rf.get(Formbuilder.options.mappings.REQUIRED)) { ;
    __p += '\n    <abbr title=\'required\'>*</abbr>\n  ';
     } ;
    __p += '\n</label>\n';

    }
    return __p
    };
    