/**
 *
 * -----------------------------------------------------------
 *
 * AppGlut Framework
 *
 * -----------------------------------------------------------
 *
 */
(function ($, window, document, undefined) {
  ("use strict");

  //
  // Constants
  //
  var AGSHOPGLUT = AGSHOPGLUT || {};

  AGSHOPGLUT.funcs = {};

  AGSHOPGLUT.vars = {
    onloaded: false,
    $body: $("body"),
    $window: $(window),
    $document: $(document),
    $form_warning: null,
    is_confirm: false,
    form_modified: false,
    code_themes: [],
    is_rtl: $("body").hasClass("rtl"),
  };

  //
  // Helper Functions
  //
  AGSHOPGLUT.helper = {
    //
    // Generate UID
    //
    uid: function (prefix) {
      return (prefix || "") + Math.random().toString(36).substr(2, 9);
    },

    // Quote regular expression characters
    //
    preg_quote: function (str) {
      return (str + "").replace(/(\[|\])/g, "\\$1");
    },

    //
    // Reneme input names
    //
    name_nested_replace: function ($selector, field_id) {
      var checks = [];
      var regex = new RegExp(AGSHOPGLUT.helper.preg_quote(field_id + "[\\d+]"), "g");

      $selector.find(":radio").each(function () {
        if (this.checked || this.orginal_checked) {
          this.orginal_checked = true;
        }
      });

      $selector.each(function (index) {
        $(this)
          .find(":input")
          .each(function () {
            this.name = this.name.replace(regex, field_id + "[" + index + "]");
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
        var context = this,
          args = arguments;
        var later = function () {
          timeout = null;
          if (!immediate) {
            callback.apply(context, args);
          }
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, threshold);
        if (callNow) {
          callback.apply(context, args);
        }
      };
    },
  };

  //
  // Custom clone for textarea and select clone() bug
  //
  $.fn.agl_clone = function () {
    var base = $.fn.clone.apply(this, arguments),
      clone = this.find("select").add(this.filter("select")),
      cloned = base.find("select").add(base.filter("select"));

    for (var i = 0; i < clone.length; ++i) {
      for (var j = 0; j < clone[i].options.length; ++j) {
        if (clone[i].options[j].selected === true) {
          cloned[i].options[j].selected = true;
        }
      }
    }

    this.find(":radio").each(function () {
      this.orginal_checked = this.checked;
    });

    return base;
  };

  //
  // Expand All Options
  //
  $.fn.agl_expand_all = function () {
    return this.each(function () {
      $(this).on("click", function (e) {
        e.preventDefault();
        $(".agl-wrapper").toggleClass("agl-show-all");
        $(".agl-section").agl_reload_script();
        $(this).find(".fa").toggleClass("fa-indent").toggleClass("fa-outdent");
      });
    });
  };

  //
  // Options Navigation
  //
  $.fn.agl_nav_options = function () {
    return this.each(function () {
      var $nav = $(this),
        $links = $nav.find("a"),
        $last;

      $(window)
        .on("hashchange agl.hashchange", function () {
          var hash = window.location.hash.replace("#tab=", "");
          var slug = hash ? hash : $links.first().attr("href").replace("#tab=", "");
          var $link = $('[data-tab-id="' + slug + '"]');

          if ($link.length) {
            $link.closest(".agl-tab-item").addClass("agl-tab-expanded").siblings().removeClass("agl-tab-expanded");

            if ($link.next().is("ul")) {
              $link = $link.next().find("li").first().find("a");
              slug = $link.data("tab-id");
            }

            $links.removeClass("agl-active");
            $link.addClass("agl-active");

            if ($last) {
              $last.addClass("hidden");
            }

            var $section = $('[data-section-id="' + slug + '"]');

            $section.removeClass("hidden");
            $section.agl_reload_script();

            $(".agl-section-id").val($section.index() + 1);

            $last = $section;
          }
        })
        .trigger("agl.hashchange");
    });
  };

  //
  // Metabox Tabs
  //
  $.fn.agl_nav_metabox = function () {
    return this.each(function () {
      var $nav = $(this),
        $links = $nav.find("a"),
        $sections = $nav.parent().find(".agl-section"),
        $last;

      $links.each(function (index) {
        $(this).on("click", function (e) {
          e.preventDefault();

          var $link = $(this);

          $links.removeClass("agl-active");
          $link.addClass("agl-active");

          if ($last !== undefined) {
            $last.addClass("hidden");
          }

          var $section = $sections.eq(index);

          $section.removeClass("hidden");
          $section.agl_reload_script();

          $last = $section;
        });
      });

      $links.first().trigger("click");
    });
  };

  //
  // Metabox Page Templates Listener
  //
  $.fn.agl_page_templates = function () {
    if (this.length) {
      $(document).on("change", ".editor-page-attributes__template select, #page_template", function () {
        var maybe_value = $(this).val() || "default";

        $(".agl-page-templates").removeClass("agl-metabox-show").addClass("agl-metabox-hide");
        $(".agl-page-" + maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g, "-"))
          .removeClass("agl-metabox-hide")
          .addClass("agl-metabox-show");
      });
    }
  };

  //
  // Metabox Post Formats Listener
  //
  $.fn.agl_post_formats = function () {
    if (this.length) {
      $(document).on("change", '.editor-post-format select, #formatdiv input[name="post_format"]', function () {
        var maybe_value = $(this).val() || "default";

        // Fallback for classic editor version
        maybe_value = maybe_value === "0" ? "default" : maybe_value;

        $(".agl-post-formats").removeClass("agl-metabox-show").addClass("agl-metabox-hide");
        $(".agl-post-format-" + maybe_value)
          .removeClass("agl-metabox-hide")
          .addClass("agl-metabox-show");
      });
    }
  };

  //
  // Search
  //
  $.fn.agl_search = function () {
    return this.each(function () {
      var $this = $(this),
        $input = $this.find("input");

      $input.on("change keyup", function () {
        var value = $(this).val(),
          $wrapper = $(".agl-wrapper"),
          $section = $wrapper.find(".agl-section"),
          $fields = $section.find("> .agl-field:not(.agl-depend-on)"),
          $titles = $fields.find("> .agl-title, .agl-search-tags");

        if (value.length > 3) {
          $fields.addClass("agl-metabox-hide");
          $wrapper.addClass("agl-search-all");

          $titles.each(function () {
            var $title = $(this);

            if ($title.text().match(new RegExp(".*?" + value + ".*?", "i"))) {
              var $field = $title.closest(".agl-field");

              $field.removeClass("agl-metabox-hide");
              $field.parent().agl_reload_script();
            }
          });
        } else {
          $fields.removeClass("agl-metabox-hide");
          $wrapper.removeClass("agl-search-all");
        }
      });
    });
  };

  //
  // Sticky Header
  //
  $.fn.agl_sticky = function () {
    return this.each(function () {
      var $this = $(this),
        $window = $(window),
        $inner = $this.find(".agl-header-inner"),
        padding = parseInt($inner.css("padding-left")) + parseInt($inner.css("padding-right")),
        offset = 32,
        scrollTop = 0,
        lastTop = 0,
        ticking = false,
        stickyUpdate = function () {
          var offsetTop = $this.offset().top,
            stickyTop = Math.max(offset, offsetTop - scrollTop),
            winWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

          if (stickyTop <= offset && winWidth > 782) {
            $inner.css({width: $this.outerWidth() - padding});
            $this.css({height: $this.outerHeight()}).addClass("agl-sticky");
          } else {
            $inner.removeAttr("style");
            $this.removeAttr("style").removeClass("agl-sticky");
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

      $window.on("scroll resize", onSticky);

      onSticky();
    });
  };

  //
  // Dependency System
  //
  $.fn.agl_dependency = function () {
    return this.each(function () {
      var $this = $(this),
        $fields = $this.children("[data-controller]");

      if ($fields.length) {
        var normal_ruleset = $.agl_deps.createRuleset(),
          global_ruleset = $.agl_deps.createRuleset(),
          normal_depends = [],
          global_depends = [];

        $fields.each(function () {
          var $field = $(this),
            controllers = $field.data("controller").split("|"),
            conditions = $field.data("condition").split("|"),
            values = $field.data("value").toString().split("|"),
            is_global = $field.data("depend-global") ? true : false,
            ruleset = is_global ? global_ruleset : normal_ruleset;

          $.each(controllers, function (index, depend_id) {
            var value = values[index] || "",
              condition = conditions[index] || conditions[0];

            ruleset = ruleset.createRule('[data-depend-id="' + depend_id + '"]', condition, value);

            ruleset.include($field);

            if (is_global) {
              global_depends.push(depend_id);
            } else {
              normal_depends.push(depend_id);
            }
          });
        });

        if (normal_depends.length) {
          $.agl_deps.enable($this, normal_ruleset, normal_depends);
        }

        if (global_depends.length) {
          $.agl_deps.enable(AGSHOPGLUT.vars.$body, global_ruleset, global_depends);
        }
      }
    });
  };

  //
  // Field: accordion
  //
  $.fn.AGSHOPGLUT_accordion = function () {
    return this.each(function () {
      var $titles = $(this).find(".agl-accordion-title");

      // Open the first accordion item by default
      var $firstTitle = $titles.first();
      var $firstIcon = $firstTitle.find(".agl-accordion-icon");
      var $firstContent = $firstTitle.next();

      // Toggle the icon and content for the first item
      $firstIcon.removeClass("fa-angle-right").addClass("fa-angle-down");
      $firstContent.addClass("agl-accordion-open");
      $firstContent.agl_reload_script();
      $firstContent.data("opened", true);

      $titles.on("click", function () {
        var $title = $(this),
          $icon = $title.find(".agl-accordion-icon"),
          $content = $title.next();

        if ($icon.hasClass("fa-angle-right")) {
          $icon.removeClass("fa-angle-right").addClass("fa-angle-down");
        } else {
          $icon.removeClass("fa-angle-down").addClass("fa-angle-right");
        }

        if (!$content.data("opened")) {
          $content.agl_reload_script();
          $content.data("opened", true);
        }

        $content.toggleClass("agl-accordion-open");
      });
    });
  };

  //
  // Field: backup
  //
  $.fn.AGSHOPGLUT_backup = function () {
    return this.each(function () {
      if (window.wp.customize === undefined) {
        return;
      }

      var base = this,
        $this = $(this),
        $body = $("body"),
        $import = $this.find(".agl-import"),
        $reset = $this.find(".agl-reset");

      base.notificationOverlay = function () {
        if (wp.customize.notifications && wp.customize.OverlayNotification) {
          // clear if there is any saved data.
          if (!wp.customize.state("saved").get()) {
            wp.customize.state("changesetStatus").set("trash");
            wp.customize.each(function (setting) {
              setting._dirty = false;
            });
            wp.customize.state("saved").set(true);
          }

          // then show a notification overlay
          wp.customize.notifications.add(
            new wp.customize.OverlayNotification("AGSHOPGLUT_backup_notification", {
              type: "default",
              message: "&nbsp;",
              loading: true,
            })
          );
        }
      };

      $reset.on("click", function (e) {
        e.preventDefault();

        if (AGSHOPGLUT.vars.is_confirm) {
          base.notificationOverlay();

          window.wp.ajax
            .post("agl-reset", {
              unique: $reset.data("unique"),
              nonce: $reset.data("nonce"),
            })
            .done(function (response) {
              window.location.reload(true);
            })
            .fail(function (response) {
              alert(response.error);
              wp.customize.notifications.remove("AGSHOPGLUT_backup_notification");
            });
        }
      });

      $import.on("click", function (e) {
        e.preventDefault();

        if (AGSHOPGLUT.vars.is_confirm) {
          base.notificationOverlay();

          window.wp.ajax
            .post("agl-import", {
              unique: $import.data("unique"),
              nonce: $import.data("nonce"),
              data: $this.find(".agl-import-data").val(),
            })
            .done(function (response) {
              window.location.reload(true);
            })
            .fail(function (response) {
              alert(response.error);
              wp.customize.notifications.remove("AGSHOPGLUT_backup_notification");
            });
        }
      });
    });
  };

  //
  // Field: background
  //
  $.fn.AGSHOPGLUT_background = function () {
    return this.each(function () {
      $(this).find(".agl--background-image").agl_reload_script();
    });
  };

  //
  // Field: code_editor
  //
  $.fn.AGSHOPGLUT_code_editor = function () {
    return this.each(function () {
      if (typeof CodeMirror !== "function") {
        return;
      }

      var $this = $(this),
        $textarea = $this.find("textarea"),
        $inited = $this.find(".CodeMirror"),
        data_editor = $textarea.data("editor");

      if ($inited.length) {
        $inited.remove();
      }

      var interval = setInterval(function () {
        if ($this.is(":visible")) {
          var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor);

          // load code-mirror theme css.
          if (data_editor.theme !== "default" && AGSHOPGLUT.vars.code_themes.indexOf(data_editor.theme) === -1) {
            var $cssLink = $("<link>");

            $("#agl-codemirror-css").after($cssLink);

            $cssLink.attr({
              rel: "stylesheet",
              id: "agl-codemirror-" + data_editor.theme + "-css",
              href: data_editor.cdnURL + "/theme/" + data_editor.theme + ".min.css",
              type: "text/css",
              media: "all",
            });

            AGSHOPGLUT.vars.code_themes.push(data_editor.theme);
          }

          CodeMirror.modeURL = data_editor.cdnURL + "/mode/%N/%N.min.js";
          CodeMirror.autoLoadMode(code_editor, data_editor.mode);

          code_editor.on("change", function (editor, event) {
            $textarea.val(code_editor.getValue()).trigger("change");
          });

          clearInterval(interval);
        }
      });
    });
  };

  //
  // Field: date
  //
  $.fn.AGSHOPGLUT_date = function () {
    return this.each(function () {
      var $this = $(this),
        $inputs = $this.find("input"),
        settings = $this.find(".agl-date-settings").data("settings"),
        wrapper = '<div class="agl-datepicker-wrapper"></div>',
        $datepicker;

      var defaults = {
        showAnim: "",
        beforeShow: function (input, inst) {
          $(inst.dpDiv).addClass("agl-datepicker-wrapper");
        },
        onClose: function (input, inst) {
          $(inst.dpDiv).removeClass("agl-datepicker-wrapper");
        },
      };

      settings = $.extend({}, settings, defaults);

      if ($inputs.length === 2) {
        settings = $.extend({}, settings, {
          onSelect: function (selectedDate) {
            var $this = $(this),
              $from = $inputs.first(),
              option = $inputs.first().attr("id") === $(this).attr("id") ? "minDate" : "maxDate",
              date = $.datepicker.parseDate(settings.dateFormat, selectedDate);

            $inputs.not(this).datepicker("option", option, date);
          },
        });
      }

      $inputs.each(function () {
        var $input = $(this);

        if ($input.hasClass("hasDatepicker")) {
          $input.removeAttr("id").removeClass("hasDatepicker");
        }

        $input.datepicker(settings);
      });
    });
  };

  //
  // Field: fieldset
  //
  $.fn.AGSHOPGLUT_fieldset = function () {
    return this.each(function () {
      $(this).find(".agl-fieldset-content").agl_reload_script();
    });
  };

  //
  // Field: WishlistMail
  //

  $.fn.AGSHOPGLUT_wishlistMail = function () {
    return this.each(function () {
      $(this).find(".agl-fieldset-content").agl_reload_script();
    });
  };

  //
  // Field: gallery
  //
  $.fn.AGSHOPGLUT_gallery = function () {
    return this.each(function () {
      var $this = $(this),
        $edit = $this.find(".agl-edit-gallery"),
        $clear = $this.find(".agl-clear-gallery"),
        $list = $this.find("ul"),
        $input = $this.find("input"),
        $img = $this.find("img"),
        wp_media_frame;

      $this.on("click", ".agl-button, .agl-edit-gallery", function (e) {
        var $el = $(this),
          ids = $input.val(),
          what = $el.hasClass("agl-edit-gallery") ? "edit" : "add",
          state = what === "add" && !ids.length ? "gallery" : "gallery-edit";

        e.preventDefault();

        if (typeof window.wp === "undefined" || !window.wp.media || !window.wp.media.gallery) {
          return;
        }

        // Open media with state
        if (state === "gallery") {
          wp_media_frame = window.wp.media({
            library: {
              type: "image",
            },
            frame: "post",
            state: "gallery",
            multiple: true,
          });

          wp_media_frame.open();
        } else {
          wp_media_frame = window.wp.media.gallery.edit('[gallery ids="' + ids + '"]');

          if (what === "add") {
            wp_media_frame.setState("gallery-library");
          }
        }

        // Media Update
        wp_media_frame.on("update", function (selection) {
          $list.empty();

          var selectedIds = selection.models.map(function (attachment) {
            var item = attachment.toJSON();
            var thumb =
              item.sizes && item.sizes.thumbnail && item.sizes.thumbnail.url ? item.sizes.thumbnail.url : item.url;

            $list.append('<li><img src="' + thumb + '"></li>');

            return item.id;
          });

          $input.val(selectedIds.join(",")).trigger("change");
          $clear.removeClass("hidden");
          $edit.removeClass("hidden");
        });
      });

      $clear.on("click", function (e) {
        e.preventDefault();
        $list.empty();
        $input.val("").trigger("change");
        $clear.addClass("hidden");
        $edit.addClass("hidden");
      });
    });
  };

  //
  // Field: group
  //
  $.fn.AGSHOPGLUT_group = function () {
    return this.each(function () {
      var $this = $(this),
        $fieldset = $this.children(".agl-fieldset"),
        $group = $fieldset.length ? $fieldset : $this,
        $wrapper = $group.children(".agl-cloneable-wrapper"),
        $hidden = $group.children(".agl-cloneable-hidden"),
        $max = $group.children(".agl-cloneable-max"),
        $min = $group.children(".agl-cloneable-min"),
        field_id = $wrapper.data("field-id"),
        is_number = Boolean(Number($wrapper.data("title-number"))),
        max = parseInt($wrapper.data("max")),
        min = parseInt($wrapper.data("min"));

      // clear accordion arrows if multi-instance
      if ($wrapper.hasClass("ui-accordion")) {
        $wrapper.find(".ui-accordion-header-icon").remove();
      }

      var update_title_numbers = function ($selector) {
        $selector.find(".agl-cloneable-title-number").each(function (index) {
          $(this).html($(this).closest(".agl-cloneable-item").index() + 1 + ".");
        });
      };

      $wrapper.accordion({
        header: "> .agl-cloneable-item > .agl-cloneable-title",
        collapsible: true,
        active: false,
        animate: false,
        heightStyle: "content",
        icons: {
          header: "agl-cloneable-header-icon fas fa-angle-right",
          activeHeader: "agl-cloneable-header-icon fas fa-angle-down",
        },
        activate: function (event, ui) {
          var $panel = ui.newPanel;
          var $header = ui.newHeader;

          if ($panel.length && !$panel.data("opened")) {
            var $fields = $panel.children();
            var $first = $fields.first().find(":input").first();
            var $title = $header.find(".agl-cloneable-value");

            $first.on("change keyup", function (event) {
              $title.text($first.val());
            });

            $panel.agl_reload_script();
            $panel.data("opened", true);
            $panel.data("retry", false);
          } else if ($panel.data("retry")) {
            $panel.agl_reload_script_retry();
            $panel.data("retry", false);
          }
        },
      });

      $wrapper.sortable({
        axis: "y",
        handle: ".agl-cloneable-title,.agl-cloneable-sort",
        helper: "original",
        cursor: "move",
        placeholder: "widget-placeholder",
        start: function (event, ui) {
          $wrapper.accordion({active: false});
          $wrapper.sortable("refreshPositions");
          ui.item.children(".agl-cloneable-content").data("retry", true);
        },
        update: function (event, ui) {
          AGSHOPGLUT.helper.name_nested_replace($wrapper.children(".agl-cloneable-item"), field_id);
          $wrapper.agl_customizer_refresh();

          if (is_number) {
            update_title_numbers($wrapper);
          }
        },
      });

      $group.children(".agl-cloneable-add").on("click", function (e) {
        e.preventDefault();

        var count = $wrapper.children(".agl-cloneable-item").length;

        $min.hide();

        if (max && count + 1 > max) {
          $max.show();
          return;
        }

        var $cloned_item = $hidden.agl_clone(true);

        $cloned_item.removeClass("agl-cloneable-hidden");

        $cloned_item.find(':input[name!="_pseudo"]').each(function () {
          this.name = this.name.replace("___", "").replace(field_id + "[0]", field_id + "[" + count + "]");
        });

        $wrapper.append($cloned_item);
        $wrapper.accordion("refresh");
        $wrapper.accordion({active: count});
        $wrapper.agl_customizer_refresh();
        $wrapper.agl_customizer_listen({closest: true});

        if (is_number) {
          update_title_numbers($wrapper);
        }
      });

      var event_clone = function (e) {
        e.preventDefault();

        var count = $wrapper.children(".agl-cloneable-item").length;

        $min.hide();

        if (max && count + 1 > max) {
          $max.show();
          return;
        }

        var $this = $(this),
          $parent = $this.parent().parent(),
          $cloned_helper = $parent.children(".agl-cloneable-helper").agl_clone(true),
          $cloned_title = $parent.children(".agl-cloneable-title").agl_clone(),
          $cloned_content = $parent.children(".agl-cloneable-content").agl_clone(),
          $cloned_item = $('<div class="agl-cloneable-item" />');

        $cloned_item.append($cloned_helper);
        $cloned_item.append($cloned_title);
        $cloned_item.append($cloned_content);

        $wrapper.children().eq($parent.index()).after($cloned_item);

        AGSHOPGLUT.helper.name_nested_replace($wrapper.children(".agl-cloneable-item"), field_id);

        $wrapper.accordion("refresh");
        $wrapper.agl_customizer_refresh();
        $wrapper.agl_customizer_listen({closest: true});

        if (is_number) {
          update_title_numbers($wrapper);
        }
      };

      $wrapper
        .children(".agl-cloneable-item")
        .children(".agl-cloneable-helper")
        .on("click", ".agl-cloneable-clone", event_clone);
      $group
        .children(".agl-cloneable-hidden")
        .children(".agl-cloneable-helper")
        .on("click", ".agl-cloneable-clone", event_clone);

      var event_remove = function (e) {
        e.preventDefault();

        var count = $wrapper.children(".agl-cloneable-item").length;

        $max.hide();
        $min.hide();

        if (min && count - 1 < min) {
          $min.show();
          return;
        }

        $(this).closest(".agl-cloneable-item").remove();

        AGSHOPGLUT.helper.name_nested_replace($wrapper.children(".agl-cloneable-item"), field_id);

        $wrapper.agl_customizer_refresh();

        if (is_number) {
          update_title_numbers($wrapper);
        }
      };

      $wrapper
        .children(".agl-cloneable-item")
        .children(".agl-cloneable-helper")
        .on("click", ".agl-cloneable-remove", event_remove);
      $group
        .children(".agl-cloneable-hidden")
        .children(".agl-cloneable-helper")
        .on("click", ".agl-cloneable-remove", event_remove);
    });
  };

  //
  // Field: icon
  //
  $.fn.AGSHOPGLUT_icon = function () {
    return this.each(function () {
      var $this = $(this);

      $this.on("click", ".agl-icon-add", function (e) {
        e.preventDefault();

        var $button = $(this);
        var $modal = $("#agl-modal-icon");

        $modal.removeClass("hidden");

        AGSHOPGLUT.vars.$icon_target = $this;

        if (!AGSHOPGLUT.vars.icon_modal_loaded) {
          $modal.find(".agl-modal-loading").show();

          window.wp.ajax
            .post("agl-get-icons", {
              nonce: $button.data("nonce"),
            })
            .done(function (response) {
              $modal.find(".agl-modal-loading").hide();

              AGSHOPGLUT.vars.icon_modal_loaded = true;

              var $load = $modal.find(".agl-modal-load").html(response.content);

              $load.on("click", "i", function (e) {
                e.preventDefault();

                var icon = $(this).attr("title");

                AGSHOPGLUT.vars.$icon_target.find(".agl-icon-preview i").removeAttr("class").addClass(icon);
                AGSHOPGLUT.vars.$icon_target.find(".agl-icon-preview").removeClass("hidden");
                AGSHOPGLUT.vars.$icon_target.find(".agl-icon-remove").removeClass("hidden");
                AGSHOPGLUT.vars.$icon_target.find("input").val(icon).trigger("change");

                $modal.addClass("hidden");
              });

              $modal.on("change keyup", ".agl-icon-search", function () {
                var value = $(this).val(),
                  $icons = $load.find("i");

                $icons.each(function () {
                  var $elem = $(this);

                  if ($elem.attr("title").search(new RegExp(value, "i")) < 0) {
                    $elem.hide();
                  } else {
                    $elem.show();
                  }
                });
              });

              $modal.on("click", ".agl-modal-close, .agl-modal-overlay", function () {
                $modal.addClass("hidden");
              });
            })
            .fail(function (response) {
              $modal.find(".agl-modal-loading").hide();
              $modal.find(".agl-modal-load").html(response.error);
              $modal.on("click", function () {
                $modal.addClass("hidden");
              });
            });
        }
      });

      $this.on("click", ".agl-icon-remove", function (e) {
        e.preventDefault();
        $this.find(".agl-icon-preview").addClass("hidden");
        $this.find("input").val("").trigger("change");
        $(this).addClass("hidden");
      });
    });
  };

  //
  // Field: map
  //
  $.fn.AGSHOPGLUT_map = function () {
    return this.each(function () {
      if (typeof L === "undefined") {
        return;
      }

      var $this = $(this),
        $map = $this.find(".agl--map-osm"),
        $search_input = $this.find(".agl--map-search input"),
        $latitude = $this.find(".agl--latitude"),
        $longitude = $this.find(".agl--longitude"),
        $zoom = $this.find(".agl--zoom"),
        map_data = $map.data("map");

      var mapInit = L.map($map.get(0), map_data);

      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      }).addTo(mapInit);

      var mapMarker = L.marker(map_data.center, {draggable: true}).addTo(mapInit);

      var update_latlng = function (data) {
        $latitude.val(data.lat);
        $longitude.val(data.lng);
        $zoom.val(mapInit.getZoom());
      };

      mapInit.on("click", function (data) {
        mapMarker.setLatLng(data.latlng);
        update_latlng(data.latlng);
      });

      mapInit.on("zoom", function () {
        update_latlng(mapMarker.getLatLng());
      });

      mapMarker.on("drag", function () {
        update_latlng(mapMarker.getLatLng());
      });

      if (!$search_input.length) {
        $search_input = $('[data-depend-id="' + $this.find(".agl--address-field").data("address-field") + '"]');
      }

      var cache = {};

      $search_input.autocomplete({
        source: function (request, response) {
          var term = request.term;

          if (term in cache) {
            response(cache[term]);
            return;
          }

          $.get(
            "https://nominatim.openstreetmap.org/search",
            {
              format: "json",
              q: term,
            },
            function (results) {
              var data;

              if (results.length) {
                data = results.map(function (item) {
                  return {
                    value: item.display_name,
                    label: item.display_name,
                    lat: item.lat,
                    lon: item.lon,
                  };
                }, "json");
              } else {
                data = [
                  {
                    value: "no-data",
                    label: "No Results.",
                  },
                ];
              }

              cache[term] = data;
              response(data);
            }
          );
        },
        select: function (event, ui) {
          if (ui.item.value === "no-data") {
            return false;
          }

          var latLng = L.latLng(ui.item.lat, ui.item.lon);

          mapInit.panTo(latLng);
          mapMarker.setLatLng(latLng);
          update_latlng(latLng);
        },
        create: function (event, ui) {
          $(this).autocomplete("widget").addClass("agl-map-ui-autocomplate");
        },
      });

      var input_update_latlng = function () {
        var latLng = L.latLng($latitude.val(), $longitude.val());

        mapInit.panTo(latLng);
        mapMarker.setLatLng(latLng);
      };

      $latitude.on("change", input_update_latlng);
      $longitude.on("change", input_update_latlng);
    });
  };

  //
  // Field: link
  //
  $.fn.AGSHOPGLUT_link = function () {
    return this.each(function () {
      var $this = $(this),
        $link = $this.find(".agl--link"),
        $add = $this.find(".agl--add"),
        $edit = $this.find(".agl--edit"),
        $remove = $this.find(".agl--remove"),
        $result = $this.find(".agl--result"),
        uniqid = AGSHOPGLUT.helper.uid("agl-wplink-textarea-");

      $add.on("click", function (e) {
        e.preventDefault();

        window.wpLink.open(uniqid);
      });

      $edit.on("click", function (e) {
        e.preventDefault();

        $add.trigger("click");

        $("#wp-link-url").val($this.find(".agl--url").val());
        $("#wp-link-text").val($this.find(".agl--text").val());
        $("#wp-link-target").prop("checked", $this.find(".agl--target").val() === "_blank");
      });

      $remove.on("click", function (e) {
        e.preventDefault();

        $this.find(".agl--url").val("").trigger("change");
        $this.find(".agl--text").val("");
        $this.find(".agl--target").val("");

        $add.removeClass("hidden");
        $edit.addClass("hidden");
        $remove.addClass("hidden");
        $result.parent().addClass("hidden");
      });

      $link.attr("id", uniqid).on("change", function () {
        var atts = window.wpLink.getAttrs(),
          href = atts.href,
          text = $("#wp-link-text").val(),
          target = atts.target ? atts.target : "";

        $this.find(".agl--url").val(href).trigger("change");
        $this.find(".agl--text").val(text);
        $this.find(".agl--target").val(target);

        $result.html('{url:"' + href + '", text:"' + text + '", target:"' + target + '"}');

        $add.addClass("hidden");
        $edit.removeClass("hidden");
        $remove.removeClass("hidden");
        $result.parent().removeClass("hidden");
      });
    });
  };

  //
  // Field: media
  //
  $.fn.AGSHOPGLUT_media = function () {
    return this.each(function () {
      var $this = $(this),
        $upload_button = $this.find(".agl--button"),
        $remove_button = $this.find(".agl--remove"),
        $library = ($upload_button.data("library") && $upload_button.data("library").split(",")) || "",
        $auto_attributes = $this.hasClass("agl-assign-field-background")
          ? $this.closest(".agl-field-background").find(".agl--auto-attributes")
          : false,
        wp_media_frame;

      $upload_button.on("click", function (e) {
        e.preventDefault();

        if (typeof window.wp === "undefined" || !window.wp.media || !window.wp.media.gallery) {
          return;
        }

        if (wp_media_frame) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library,
          },
        });

        wp_media_frame.on("select", function () {
          var thumbnail;
          var attributes = wp_media_frame.state().get("selection").first().attributes;
          var preview_size = $upload_button.data("preview-size") || "thumbnail";

          if (
            $library.length &&
            $library.indexOf(attributes.subtype) === -1 &&
            $library.indexOf(attributes.type) === -1
          ) {
            return;
          }

          $this.find(".agl--id").val(attributes.id);
          $this.find(".agl--width").val(attributes.width);
          $this.find(".agl--height").val(attributes.height);
          $this.find(".agl--alt").val(attributes.alt);
          $this.find(".agl--title").val(attributes.title);
          $this.find(".agl--description").val(attributes.description);

          if (
            typeof attributes.sizes !== "undefined" &&
            typeof attributes.sizes.thumbnail !== "undefined" &&
            preview_size === "thumbnail"
          ) {
            thumbnail = attributes.sizes.thumbnail.url;
          } else if (typeof attributes.sizes !== "undefined" && typeof attributes.sizes.full !== "undefined") {
            thumbnail = attributes.sizes.full.url;
          } else {
            thumbnail = attributes.icon;
          }

          if ($auto_attributes) {
            $auto_attributes.removeClass("agl--attributes-hidden");
          }

          $remove_button.removeClass("hidden");

          $this.find(".agl--preview").removeClass("hidden");
          $this.find(".agl--src").attr("src", thumbnail);
          $this.find(".agl--thumbnail").val(thumbnail);
          $this.find(".agl--url").val(attributes.url).trigger("change");
        });

        wp_media_frame.open();
      });

      $remove_button.on("click", function (e) {
        e.preventDefault();

        if ($auto_attributes) {
          $auto_attributes.addClass("agl--attributes-hidden");
        }

        $remove_button.addClass("hidden");
        $this.find("input").val("");
        $this.find(".agl--preview").addClass("hidden");
        $this.find(".agl--url").trigger("change");
      });
    });
  };

  //
  // Field: repeater
  //
  $.fn.AGSHOPGLUT_repeater = function () {
    return this.each(function () {
      var $this = $(this),
        $fieldset = $this.children(".agl-fieldset"),
        $repeater = $fieldset.length ? $fieldset : $this,
        $wrapper = $repeater.children(".agl-repeater-wrapper"),
        $hidden = $repeater.children(".agl-repeater-hidden"),
        $max = $repeater.children(".agl-repeater-max"),
        $min = $repeater.children(".agl-repeater-min"),
        field_id = $wrapper.data("field-id"),
        max = parseInt($wrapper.data("max")),
        min = parseInt($wrapper.data("min"));

      $wrapper.children(".agl-repeater-item").children(".agl-repeater-content").agl_reload_script();

      $wrapper.sortable({
        axis: "y",
        handle: ".agl-repeater-sort",
        helper: "original",
        cursor: "move",
        placeholder: "widget-placeholder",
        update: function (event, ui) {
          AGSHOPGLUT.helper.name_nested_replace($wrapper.children(".agl-repeater-item"), field_id);
          $wrapper.agl_customizer_refresh();
          ui.item.agl_reload_script_retry();
        },
      });

      $repeater.children(".agl-repeater-add").on("click", function (e) {
        e.preventDefault();

        var count = $wrapper.children(".agl-repeater-item").length;

        $min.hide();

        if (max && count + 1 > max) {
          $max.show();
          return;
        }

        var $cloned_item = $hidden.agl_clone(true);

        $cloned_item.removeClass("agl-repeater-hidden");

        $cloned_item.find(':input[name!="_pseudo"]').each(function () {
          this.name = this.name.replace("___", "").replace(field_id + "[0]", field_id + "[" + count + "]");
        });

        $wrapper.append($cloned_item);
        $cloned_item.children(".agl-repeater-content").agl_reload_script();
        $wrapper.agl_customizer_refresh();
        $wrapper.agl_customizer_listen({closest: true});
      });

      var event_clone = function (e) {
        e.preventDefault();

        var count = $wrapper.children(".agl-repeater-item").length;

        $min.hide();

        if (max && count + 1 > max) {
          $max.show();
          return;
        }

        var $this = $(this),
          $parent = $this.parent().parent().parent(),
          $cloned_content = $parent.children(".agl-repeater-content").agl_clone(),
          $cloned_helper = $parent.children(".agl-repeater-helper").agl_clone(true),
          $cloned_item = $('<div class="agl-repeater-item" />');

        $cloned_item.append($cloned_content);
        $cloned_item.append($cloned_helper);

        $wrapper.children().eq($parent.index()).after($cloned_item);

        $cloned_item.children(".agl-repeater-content").agl_reload_script();

        AGSHOPGLUT.helper.name_nested_replace($wrapper.children(".agl-repeater-item"), field_id);

        $wrapper.agl_customizer_refresh();
        $wrapper.agl_customizer_listen({closest: true});
      };

      $wrapper
        .children(".agl-repeater-item")
        .children(".agl-repeater-helper")
        .on("click", ".agl-repeater-clone", event_clone);
      $repeater
        .children(".agl-repeater-hidden")
        .children(".agl-repeater-helper")
        .on("click", ".agl-repeater-clone", event_clone);

      var event_remove = function (e) {
        e.preventDefault();

        var count = $wrapper.children(".agl-repeater-item").length;

        $max.hide();
        $min.hide();

        if (min && count - 1 < min) {
          $min.show();
          return;
        }

        $(this).closest(".agl-repeater-item").remove();

        AGSHOPGLUT.helper.name_nested_replace($wrapper.children(".agl-repeater-item"), field_id);

        $wrapper.agl_customizer_refresh();
      };

      $wrapper
        .children(".agl-repeater-item")
        .children(".agl-repeater-helper")
        .on("click", ".agl-repeater-remove", event_remove);
      $repeater
        .children(".agl-repeater-hidden")
        .children(".agl-repeater-helper")
        .on("click", ".agl-repeater-remove", event_remove);
    });
  };

  //
  // Field: slider
  //

  $.fn.AGSHOPGLUT_slider = function () {
    var columnStyle = "";
    var rowStyle = "";
    return this.each(function () {
      var $field = $(this);
      $field.find(".field-slider-wrap").each(function () {
        var fieldId = $(this).attr("id");
        $field.find(".agl--wrap").each(function () {
          var $wrap = $(this);
          // Iterate over all elements with classes starting with 'agl-slider-ui_'
          $wrap.find('[class^="agl-slider-ui_' + fieldId + '"]').each(function () {
            var classes = $(this).attr("class").split(" ");
            var deviceType = "";
            classes.forEach(function (className) {
              if (className.startsWith("agl-slider-ui_" + fieldId)) {
                deviceType = className.replace("agl-slider-ui_" + fieldId + "_", "");
              }
            });

            var $slider = $wrap.find(".agl-slider-ui_" + fieldId + "_" + deviceType);
            var $input = $wrap.find("#slider_value_" + fieldId + "_" + deviceType);
            var data = $input.data();
            var value = $input.val() || 0;

            if ($slider.hasClass("ui-slider")) {
              $slider.empty();
            }

            $slider.slider({
              range: "min",
              value: value,
              min: data.min || 0,
              max: data.max || 100,
              step: data.step || 1,
              slide: function (e, o) {
                $input.val(o.value).trigger("change");
              },
            });

            $input.on("keyup", function () {
              $slider.slider("value", $input.val());
            });
          });
        });
      });
    });
  };

  //
  // Field: sortable
  //
  $.fn.AGSHOPGLUT_sortable = function () {
    return this.each(function () {
      var $sortable = $(this).find(".agl-sortable");

      $sortable.sortable({
        axis: "y",
        helper: "original",
        cursor: "move",
        placeholder: "widget-placeholder",
        update: function (event, ui) {
          $sortable.agl_customizer_refresh();
        },
      });

      $sortable.find(".agl-sortable-content").agl_reload_script();
    });
  };

  //
  // Field: sorter
  //
  $.fn.AGSHOPGLUT_sorter = function () {
    return this.each(function () {
      var $this = $(this),
        $enabled = $this.find(".agl-enabled"),
        $has_disabled = $this.find(".agl-disabled"),
        $disabled = $has_disabled.length ? $has_disabled : false;

      $enabled.sortable({
        connectWith: $disabled,
        placeholder: "ui-sortable-placeholder",
        update: function (event, ui) {
          var $el = ui.item.find("input");

          if (ui.item.parent().hasClass("agl-enabled")) {
            $el.attr("name", $el.attr("name").replace("disabled", "enabled"));
          } else {
            $el.attr("name", $el.attr("name").replace("enabled", "disabled"));
          }

          $this.agl_customizer_refresh();
        },
      });

      if ($disabled) {
        $disabled.sortable({
          connectWith: $enabled,
          placeholder: "ui-sortable-placeholder",
          update: function (event, ui) {
            $this.agl_customizer_refresh();
          },
        });
      }
    });
  };

  //
  // Field: spinner
  //
  $.fn.AGSHOPGLUT_spinner = function () {
    return this.each(function () {
      var $this = $(this),
        $input = $this.find("input"),
        $inited = $this.find(".ui-spinner-button"),
        data = $input.data();

      if ($inited.length) {
        $inited.remove();
      }

      $input.spinner({
        min: data.min || 0,
        max: data.max || 100,
        step: data.step || 1,
        create: function (event, ui) {
          if (data.unit) {
            $input.after('<span class="ui-button agl--unit">' + data.unit + "</span>");
          }
        },
        spin: function (event, ui) {
          $input.val(ui.value).trigger("change");
        },
      });
    });
  };

  //
  // Field: switcher
  //
  $.fn.AGSHOPGLUT_switcher = function () {
    return this.each(function () {
      var $switcher = $(this).find(".agl--switcher");

      $switcher.on("click", function () {
        var value = 0;
        var $input = $switcher.find("input");

        if ($switcher.hasClass("agl--active")) {
          $switcher.removeClass("agl--active");
          value = 0;
        } else {
          value = 1;
          $switcher.addClass("agl--active");
        }

        $input.val(value).trigger("change");
      });
    });
  };

  //
  // Field: tabbed
  //

  $.fn.AGSHOPGLUT_tabbed = function () {
    return this.each(function () {
      var $this = $(this),
        $contents = $this.find(".agl-tabbed-content");

      $contents.eq(0).agl_reload_script();

      // Find all tab navigation containers
      var $navContainers = $this.find(".agl-tabbed-nav");

      $navContainers.each(function () {
        var $navContainer = $(this);

        // Find all tab links within this container
        var $navLinks = $navContainer.find("a");

        // Click event for tab navigation
        $navLinks.on("click", function (e) {
          e.preventDefault();

          var $clickedTab = $(this),
            clickedTabClass = $clickedTab.attr("class").split(" ")[0]; // Get the first class (e.g., 'tab-11', 'tab-12')

          // Activate the clicked tab and deactivate the others
          $navLinks.removeClass("agl-tabbed-active");
          $clickedTab.addClass("agl-tabbed-active");

          // Find the corresponding content based on the tab's unique class
          var $contents = $this.find(".agl-tabbed-content." + clickedTabClass);

          // Show the corresponding content and hide the others
          $contents.removeClass("hidden").siblings(".agl-tabbed-content").addClass("hidden");

          // Reinitialize any dynamic content if needed (like color picker)
          $contents.agl_reload_script();
        });
      });
    });
  };

  //
  // Field: section
  //
  $.fn.AGSHOPGLUT_section = function () {
    return this.each(function () {
      var $this = $(this),
        $contents = $this.find(".agl-section-content");

      $contents.eq(0).agl_reload_script();
    });
  };

  //
  // Field: typography
  //
  $.fn.AGSHOPGLUT_typography = function () {
    return this.each(function () {
      var base = this;
      var $this = $(this);
      var loaded_fonts = [];
      var webfonts = agl_typography_json.webfonts;
      var googlestyles = agl_typography_json.googlestyles;
      var defaultstyles = agl_typography_json.defaultstyles;

      //
      //
      // Sanitize google font subset
      base.sanitize_subset = function (subset) {
        subset = subset.replace("-ext", " Extended");
        subset = subset.charAt(0).toUpperCase() + subset.slice(1);
        return subset;
      };

      //
      //
      // Sanitize google font styles (weight and style)
      base.sanitize_style = function (style) {
        return googlestyles[style] ? googlestyles[style] : style;
      };

      //
      //
      // Load google font
      base.load_google_font = function (font_family, weight, style) {
        if (font_family && typeof WebFont === "object") {
          weight = weight ? weight.replace("normal", "") : "";
          style = style ? style.replace("normal", "") : "";

          if (weight || style) {
            font_family = font_family + ":" + weight + style;
          }

          if (loaded_fonts.indexOf(font_family) === -1) {
            WebFont.load({google: {families: [font_family]}});
          }

          loaded_fonts.push(font_family);
        }
      };

      //
      //
      // Append select options
      base.append_select_options = function ($select, options, condition, type, is_multi) {
        $select.find("option").not(":first").remove();

        var opts = "";

        $.each(options, function (key, value) {
          var selected;
          var name = value;

          // is_multi
          if (is_multi) {
            selected = condition && condition.indexOf(value) !== -1 ? " selected" : "";
          } else {
            selected = condition && condition === value ? " selected" : "";
          }

          if (type === "subset") {
            name = base.sanitize_subset(value);
          } else if (type === "style") {
            name = base.sanitize_style(value);
          }

          opts += '<option value="' + value + '"' + selected + ">" + name + "</option>";
        });

        $select.append(opts).trigger("agl.change").trigger("chosen:updated");
      };

      base.init = function () {
        //
        //
        // Constants
        var selected_styles = [];
        var $typography = $this.find(".agl--typography");
        var $type = $this.find(".agl--type");
        var $styles = $this.find(".agl--block-font-style");
        var unit = $typography.data("unit");
        var line_height_unit = $typography.data("line-height-unit");
        var exclude_fonts = $typography.data("exclude") ? $typography.data("exclude").split(",") : [];

        //
        //
        // Chosen init
        if ($this.find(".agl--chosen").length) {
          var $chosen_selects = $this.find("select");

          $chosen_selects.each(function () {
            var $chosen_select = $(this),
              $chosen_inited = $chosen_select.parent().find(".chosen-container");

            if ($chosen_inited.length) {
              $chosen_inited.remove();
            }

            $chosen_select.chosen({
              allow_single_deselect: true,
              disable_search_threshold: 15,
              width: "100%",
            });
          });
        }

        //
        //
        // Font family select
        var $font_family_select = $this.find(".agl--font-family");
        var first_font_family = $font_family_select.val();

        // Clear default font family select options
        $font_family_select.find("option").not(":first-child").remove();

        var opts = "";

        $.each(webfonts, function (type, group) {
          // Check for exclude fonts
          if (exclude_fonts && exclude_fonts.indexOf(type) !== -1) {
            return;
          }

          opts += '<optgroup label="' + group.label + '">';

          $.each(group.fonts, function (key, value) {
            // use key if value is object
            value = typeof value === "object" ? key : value;
            var selected = value === first_font_family ? " selected" : "";
            opts += '<option value="' + value + '" data-type="' + type + '"' + selected + ">" + value + "</option>";
          });

          opts += "</optgroup>";
        });

        // Append google font select options
        $font_family_select.append(opts).trigger("chosen:updated");

        //
        //
        // Font style select
        var $font_style_block = $this.find(".agl--block-font-style");

        if ($font_style_block.length) {
          var $font_style_select = $this.find(".agl--font-style-select");
          var first_style_value = $font_style_select.val() ? $font_style_select.val().replace(/normal/g, "") : "";

          //
          // Font Style on on change listener
          $font_style_select.on("change agl.change", function (event) {
            var style_value = $font_style_select.val();

            // set a default value
            if (!style_value && selected_styles && selected_styles.indexOf("normal") === -1) {
              style_value = selected_styles[0];
            }

            // set font weight, for eg. replacing 800italic to 800
            var font_normal = style_value && style_value !== "italic" && style_value === "normal" ? "normal" : "";
            var font_weight =
              style_value && style_value !== "italic" && style_value !== "normal"
                ? style_value.replace("italic", "")
                : font_normal;
            var font_style = style_value && style_value.substr(-6) === "italic" ? "italic" : "";

            $this.find(".agl--font-weight").val(font_weight);
            $this.find(".agl--font-style").val(font_style);
          });

          //
          //
          // Extra font style select
          var $extra_font_style_block = $this.find(".agl--block-extra-styles");

          if ($extra_font_style_block.length) {
            var $extra_font_style_select = $this.find(".agl--extra-styles");
            var first_extra_style_value = $extra_font_style_select.val();
          }
        }

        //
        //
        // Subsets select
        var $subset_block = $this.find(".agl--block-subset");
        if ($subset_block.length) {
          var $subset_select = $this.find(".agl--subset");
          var first_subset_select_value = $subset_select.val();
          var subset_multi_select = $subset_select.data("multiple") || false;
        }

        //
        //
        // Backup font family
        var $backup_font_family_block = $this.find(".agl--block-backup-font-family");

        //
        //
        // Font Family on Change Listener
        $font_family_select
          .on("change agl.change", function (event) {
            // Hide subsets on change
            if ($subset_block.length) {
              $subset_block.addClass("hidden");
            }

            // Hide extra font style on change
            if ($extra_font_style_block.length) {
              $extra_font_style_block.addClass("hidden");
            }

            // Hide backup font family on change
            if ($backup_font_family_block.length) {
              $backup_font_family_block.addClass("hidden");
            }

            var $selected = $font_family_select.find(":selected");
            var value = $selected.val();
            var type = $selected.data("type");

            if (type && value) {
              // Show backup fonts if font type google or custom
              if ((type === "google" || type === "custom") && $backup_font_family_block.length) {
                $backup_font_family_block.removeClass("hidden");
              }

              // Appending font style select options
              if ($font_style_block.length) {
                // set styles for multi and normal style selectors
                var styles = defaultstyles;

                // Custom or gogle font styles
                if (type === "google" && webfonts[type].fonts[value][0]) {
                  styles = webfonts[type].fonts[value][0];
                } else if (type === "custom" && webfonts[type].fonts[value]) {
                  styles = webfonts[type].fonts[value];
                }

                selected_styles = styles;

                // Set selected style value for avoid load errors
                var set_auto_style = styles.indexOf("normal") !== -1 ? "normal" : styles[0];
                var set_style_value =
                  first_style_value && styles.indexOf(first_style_value) !== -1 ? first_style_value : set_auto_style;

                // Append style select options
                base.append_select_options($font_style_select, styles, set_style_value, "style");

                // Clear first value
                first_style_value = false;

                // Show style select after appended
                $font_style_block.removeClass("hidden");

                // Appending extra font style select options
                if (type === "google" && $extra_font_style_block.length && styles.length > 1) {
                  // Append extra-style select options
                  base.append_select_options($extra_font_style_select, styles, first_extra_style_value, "style", true);

                  // Clear first value
                  first_extra_style_value = false;

                  // Show style select after appended
                  $extra_font_style_block.removeClass("hidden");
                }
              }

              // Appending google fonts subsets select options
              if (type === "google" && $subset_block.length && webfonts[type].fonts[value][1]) {
                var subsets = webfonts[type].fonts[value][1];
                var set_auto_subset = subsets.length < 2 && subsets[0] !== "latin" ? subsets[0] : "";
                var set_subset_value =
                  first_subset_select_value && subsets.indexOf(first_subset_select_value) !== -1
                    ? first_subset_select_value
                    : set_auto_subset;

                // check for multiple subset select
                set_subset_value =
                  subset_multi_select && first_subset_select_value ? first_subset_select_value : set_subset_value;

                base.append_select_options($subset_select, subsets, set_subset_value, "subset", subset_multi_select);

                first_subset_select_value = false;

                $subset_block.removeClass("hidden");
              }
            } else {
              // Clear Styles
              $styles.find(":input").val("");

              // Clear subsets options if type and value empty
              if ($subset_block.length) {
                $subset_select.find("option").not(":first-child").remove();
                $subset_select.trigger("chosen:updated");
              }

              // Clear font styles options if type and value empty
              if ($font_style_block.length) {
                $font_style_select.find("option").not(":first-child").remove();
                $font_style_select.trigger("chosen:updated");
              }
            }

            // Update font type input value
            $type.val(type);
          })
          .trigger("agl.change");

        //
        //
        // Preview
        var $preview_block = $this.find(".agl--block-preview");

        if ($preview_block.length) {
          var $preview = $this.find(".agl--preview");

          // Set preview styles on change
          $this.on(
            "change",
            AGSHOPGLUT.helper.debounce(function (event) {
              $preview_block.removeClass("hidden");

              var font_family = $font_family_select.val(),
                font_weight = $this.find(".agl--font-weight").val(),
                font_style = $this.find(".agl--font-style").val(),
                font_size = $this.find(".agl--font-size").val(),
                font_variant = $this.find(".agl--font-variant").val(),
                line_height = $this.find(".agl--line-height").val(),
                text_align = $this.find(".agl--text-align").val(),
                text_transform = $this.find(".agl--text-transform").val(),
                text_decoration = $this.find(".agl--text-decoration").val(),
                text_color = $this.find(".agl--color").val(),
                word_spacing = $this.find(".agl--word-spacing").val(),
                letter_spacing = $this.find(".agl--letter-spacing").val(),
                custom_style = $this.find(".agl--custom-style").val(),
                type = $this.find(".agl--type").val();

              if (type === "google") {
                base.load_google_font(font_family, font_weight, font_style);
              }

              var properties = {};

              if (font_family) {
                properties.fontFamily = font_family;
              }
              if (font_weight) {
                properties.fontWeight = font_weight;
              }
              if (font_style) {
                properties.fontStyle = font_style;
              }
              if (font_variant) {
                properties.fontVariant = font_variant;
              }
              if (font_size) {
                properties.fontSize = font_size + unit;
              }
              if (line_height) {
                properties.lineHeight = line_height + line_height_unit;
              }
              if (letter_spacing) {
                properties.letterSpacing = letter_spacing + unit;
              }
              if (word_spacing) {
                properties.wordSpacing = word_spacing + unit;
              }
              if (text_align) {
                properties.textAlign = text_align;
              }
              if (text_transform) {
                properties.textTransform = text_transform;
              }
              if (text_decoration) {
                properties.textDecoration = text_decoration;
              }
              if (text_color) {
                properties.color = text_color;
              }

              $preview.removeAttr("style");

              // Customs style attribute
              if (custom_style) {
                $preview.attr("style", custom_style);
              }

              $preview.css(properties);
            }, 100)
          );

          // Preview black and white backgrounds trigger
          $preview_block.on("click", function () {
            $preview.toggleClass("agl--black-background");

            var $toggle = $preview_block.find(".agl--toggle");

            if ($toggle.hasClass("fa-toggle-off")) {
              $toggle.removeClass("fa-toggle-off").addClass("fa-toggle-on");
            } else {
              $toggle.removeClass("fa-toggle-on").addClass("fa-toggle-off");
            }
          });

          if (!$preview_block.hasClass("hidden")) {
            $this.trigger("change");
          }
        }
      };

      base.init();
    });
  };

  //
  // Field: upload
  //
  $.fn.AGSHOPGLUT_upload = function () {
    return this.each(function () {
      var $this = $(this),
        $input = $this.find("input"),
        $upload_button = $this.find(".agl--button"),
        $remove_button = $this.find(".agl--remove"),
        $library = ($upload_button.data("library") && $upload_button.data("library").split(",")) || "",
        wp_media_frame;

      $input.on("change", function (e) {
        if ($input.val()) {
          $remove_button.removeClass("hidden");
        } else {
          $remove_button.addClass("hidden");
        }
      });

      $upload_button.on("click", function (e) {
        e.preventDefault();

        if (typeof window.wp === "undefined" || !window.wp.media || !window.wp.media.gallery) {
          return;
        }

        if (wp_media_frame) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library,
          },
        });

        wp_media_frame.on("select", function () {
          var attributes = wp_media_frame.state().get("selection").first().attributes;

          if (
            $library.length &&
            $library.indexOf(attributes.subtype) === -1 &&
            $library.indexOf(attributes.type) === -1
          ) {
            return;
          }

          $input.val(attributes.url).trigger("change");
        });

        wp_media_frame.open();
      });

      $remove_button.on("click", function (e) {
        e.preventDefault();
        $input.val("").trigger("change");
      });
    });
  };

  //
  // Field: wp_editor
  //
  $.fn.AGSHOPGLUT_wp_editor = function () {
    return this.each(function () {
      if (
        typeof window.wp.editor === "undefined" ||
        typeof window.tinyMCEPreInit === "undefined" ||
        typeof window.tinyMCEPreInit.mceInit.agl_wp_editor === "undefined"
      ) {
        return;
      }

      var $this = $(this),
        $editor = $this.find(".agl-wp-editor"),
        $textarea = $this.find("textarea");

      // If there is wp-editor remove it for avoid dupliated wp-editor conflicts.
      var $has_wp_editor = $this.find(".wp-editor-wrap").length || $this.find(".mce-container").length;

      if ($has_wp_editor) {
        $editor.empty();
        $editor.append($textarea);
        $textarea.css("display", "");
      }

      // Generate a unique id
      var uid = AGSHOPGLUT.helper.uid("agl-editor-");

      $textarea.attr("id", uid);

      // Get default editor settings
      var default_editor_settings = {
        tinymce: window.tinyMCEPreInit.mceInit.agl_wp_editor,
        quicktags: window.tinyMCEPreInit.qtInit.agl_wp_editor,
      };

      // Get default editor settings
      var field_editor_settings = $editor.data("editor-settings");

      // Callback for old wp editor
      var wpEditor = wp.oldEditor ? wp.oldEditor : wp.editor;

      if (wpEditor && wpEditor.hasOwnProperty("autop")) {
        wp.editor.autop = wpEditor.autop;
        wp.editor.removep = wpEditor.removep;
        wp.editor.initialize = wpEditor.initialize;
      }

      // Add on change event handle
      var editor_on_change = function (editor) {
        editor.on("change keyup", function () {
          var value = field_editor_settings.wpautop ? editor.getContent() : wp.editor.removep(editor.getContent());
          $textarea.val(value).trigger("change");
        });
      };

      // Extend editor selector and on change event handler
      default_editor_settings.tinymce = $.extend({}, default_editor_settings.tinymce, {
        selector: "#" + uid,
        setup: editor_on_change,
      });

      // Override editor tinymce settings
      if (field_editor_settings.tinymce === false) {
        default_editor_settings.tinymce = false;
        $editor.addClass("agl-no-tinymce");
      }

      // Override editor quicktags settings
      if (field_editor_settings.quicktags === false) {
        default_editor_settings.quicktags = false;
        $editor.addClass("agl-no-quicktags");
      }

      // Wait until :visible
      var interval = setInterval(function () {
        if ($this.is(":visible")) {
          window.wp.editor.initialize(uid, default_editor_settings);
          clearInterval(interval);
        }
      });

      // Add Media buttons
      if (field_editor_settings.media_buttons && window.agl_media_buttons) {
        var $editor_buttons = $editor.find(".wp-media-buttons");

        if ($editor_buttons.length) {
          $editor_buttons.find(".agl-shortcode-button").data("editor-id", uid);
        } else {
          var $media_buttons = $(window.agl_media_buttons);

          $media_buttons.find(".agl-shortcode-button").data("editor-id", uid);

          $editor.prepend($media_buttons);
        }
      }
    });
  };

  //
  // Confirm
  //
  $.fn.agl_confirm = function () {
    return this.each(function () {
      $(this).on("click", function (e) {
        var confirm_text = $(this).data("confirm") || window.agl_vars.i18n.confirm;
        var confirm_answer = confirm(confirm_text);

        if (confirm_answer) {
          AGSHOPGLUT.vars.is_confirm = true;
          AGSHOPGLUT.vars.form_modified = false;
        } else {
          e.preventDefault();
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

      obj[n] = obj[n] === undefined ? v : $.isArray(obj[n]) ? obj[n].concat(v) : [obj[n], v];
    });

    return obj;
  };

  //
  // Options Save
  //
  $.fn.agl_save = function () {
    return this.each(function () {
      var $this = $(this),
        $buttons = $(".agl-save"),
        $panel = $(".agl-options"),
        flooding = false,
        timeout;

      $this.on("click", function (e) {
        if (!flooding) {
          var $text = $this.data("save"),
            $value = $this.val();

          $buttons.attr("value", $text);

          if ($this.hasClass("agl-save-ajax")) {
            e.preventDefault();

            $panel.addClass("agl-saving");
            $buttons.prop("disabled", true);

            window.wp.ajax
              .post("agl_" + $panel.data("unique") + "_ajax_save", {
                data: $("#agl-form").serializeJSONAGSHOPGLUT(),
              })
              .done(function (response) {
                // clear errors
                $(".agl-error").remove();

                if (Object.keys(response.errors).length) {
                  var error_icon = '<i class="agl-label-error agl-error">!</i>';

                  $.each(response.errors, function (key, error_message) {
                    var $field = $('[data-depend-id="' + key + '"]'),
                      $link = $("#agl-tab-link-" + ($field.closest(".agl-section").index() + 1)),
                      $tab = $link.closest(".agl-tab-depth-0");

                    $field
                      .closest(".agl-fieldset")
                      .append('<p class="agl-error agl-error-text">' + error_message + "</p>");

                    if (!$link.find(".agl-error").length) {
                      $link.append(error_icon);
                    }

                    if (!$tab.find(".agl-arrow .agl-error").length) {
                      $tab.find(".agl-arrow").append(error_icon);
                    }
                  });
                }

                $panel.removeClass("agl-saving");
                $buttons.prop("disabled", false).attr("value", $value);
                flooding = false;

                AGSHOPGLUT.vars.form_modified = false;
                AGSHOPGLUT.vars.$form_warning.hide();

                clearTimeout(timeout);

                var $result_success = $(".agl-form-success");
                $result_success
                  .empty()
                  .append(response.notice)
                  .fadeIn("fast", function () {
                    timeout = setTimeout(function () {
                      $result_success.fadeOut("fast");
                    }, 1000);
                  });
              })
              .fail(function (response) {
                alert(response.error);
              });
          } else {
            AGSHOPGLUT.vars.form_modified = false;
          }
        }

        flooding = true;
      });
    });
  };

  //
  // Option Framework
  //
  $.fn.agl_options = function () {
    return this.each(function () {
      var $this = $(this),
        $content = $this.find(".agl-content"),
        $form_success = $this.find(".agl-form-success"),
        $form_warning = $this.find(".agl-form-warning"),
        $save_button = $this.find(".agl-header .agl-save");

      AGSHOPGLUT.vars.$form_warning = $form_warning;

      // Shows a message white leaving theme options without saving
      if ($form_warning.length) {
        window.onbeforeunload = function () {
          return AGSHOPGLUT.vars.form_modified ? true : undefined;
        };

        $content.on("change keypress", ":input", function () {
          if (!AGSHOPGLUT.vars.form_modified) {
            $form_success.hide();
            $form_warning.fadeIn("fast");
            AGSHOPGLUT.vars.form_modified = true;
          }
        });
      }

      if ($form_success.hasClass("agl-form-show")) {
        setTimeout(function () {
          $form_success.fadeOut("fast");
        }, 1000);
      }

      $(document).keydown(function (event) {
        if ((event.ctrlKey || event.metaKey) && event.which === 83) {
          $save_button.trigger("click");
          event.preventDefault();
          return false;
        }
      });
    });
  };

  //
  // Taxonomy Framework
  //
  $.fn.agl_taxonomy = function () {
    return this.each(function () {
      var $this = $(this),
        $form = $this.parents("form");

      if ($form.attr("id") === "addtag") {
        var $submit = $form.find("#submit"),
          $cloned = $this.find(".agl-field").agl_clone();

        $submit.on("click", function () {
          if (!$form.find(".form-required").hasClass("form-invalid")) {
            $this.data("inited", false);

            $this.empty();

            $this.html($cloned);

            $cloned = $cloned.agl_clone();

            $this.agl_reload_script();
          }
        });
      }
    });
  };

  //
  // Shortcode Framework
  //
  $.fn.agl_shortcode = function () {
    var base = this;

    base.shortcode_parse = function (serialize, key) {
      var shortcode = "";

      $.each(serialize, function (shortcode_key, shortcode_values) {
        key = key ? key : shortcode_key;

        shortcode += "[" + key;

        $.each(shortcode_values, function (shortcode_tag, shortcode_value) {
          if (shortcode_tag === "content") {
            shortcode += "]";
            shortcode += shortcode_value;
            shortcode += "[/" + key + "";
          } else {
            shortcode += base.shortcode_tags(shortcode_tag, shortcode_value);
          }
        });

        shortcode += "]";
      });

      return shortcode;
    };

    base.shortcode_tags = function (shortcode_tag, shortcode_value) {
      var shortcode = "";

      if (shortcode_value !== "") {
        if (typeof shortcode_value === "object" && !$.isArray(shortcode_value)) {
          $.each(shortcode_value, function (sub_shortcode_tag, sub_shortcode_value) {
            // sanitize spesific key/value
            switch (sub_shortcode_tag) {
              case "background-image":
                sub_shortcode_value = sub_shortcode_value.url ? sub_shortcode_value.url : "";
                break;
            }

            if (sub_shortcode_value !== "") {
              shortcode += " " + sub_shortcode_tag.replace("-", "_") + '="' + sub_shortcode_value.toString() + '"';
            }
          });
        } else {
          shortcode += " " + shortcode_tag.replace("-", "_") + '="' + shortcode_value.toString() + '"';
        }
      }

      return shortcode;
    };

    base.insertAtChars = function (_this, currentValue) {
      var obj = typeof _this[0].name !== "undefined" ? _this[0] : _this;

      if (obj.value.length && typeof obj.selectionStart !== "undefined") {
        obj.focus();
        return (
          obj.value.substring(0, obj.selectionStart) +
          currentValue +
          obj.value.substring(obj.selectionEnd, obj.value.length)
        );
      } else {
        obj.focus();
        return currentValue;
      }
    };

    base.send_to_editor = function (html, editor_id) {
      var tinymce_editor;

      if (typeof tinymce !== "undefined") {
        tinymce_editor = tinymce.get(editor_id);
      }

      if (tinymce_editor && !tinymce_editor.isHidden()) {
        tinymce_editor.execCommand("mceInsertContent", false, html);
      } else {
        var $editor = $("#" + editor_id);
        $editor.val(base.insertAtChars($editor, html)).trigger("change");
      }
    };

    return this.each(function () {
      var $modal = $(this),
        $load = $modal.find(".agl-modal-load"),
        $content = $modal.find(".agl-modal-content"),
        $insert = $modal.find(".agl-modal-insert"),
        $loading = $modal.find(".agl-modal-loading"),
        $select = $modal.find("select"),
        modal_id = $modal.data("modal-id"),
        nonce = $modal.data("nonce"),
        editor_id,
        target_id,
        gutenberg_id,
        sc_key,
        sc_name,
        sc_view,
        sc_group,
        $cloned,
        $button;

      $(document).on("click", '.agl-shortcode-button[data-modal-id="' + modal_id + '"]', function (e) {
        e.preventDefault();

        $button = $(this);
        editor_id = $button.data("editor-id") || false;
        target_id = $button.data("target-id") || false;
        gutenberg_id = $button.data("gutenberg-id") || false;

        $modal.removeClass("hidden");

        // single usage trigger first shortcode
        if ($modal.hasClass("agl-shortcode-single") && sc_name === undefined) {
          $select.trigger("change");
        }
      });

      $select.on("change", function () {
        var $option = $(this);
        var $selected = $option.find(":selected");

        sc_key = $option.val();
        sc_name = $selected.data("shortcode");
        sc_view = $selected.data("view") || "normal";
        sc_group = $selected.data("group") || sc_name;

        $load.empty();

        if (sc_key) {
          $loading.show();

          window.wp.ajax
            .post("agl-get-shortcode-" + modal_id, {
              shortcode_key: sc_key,
              nonce: nonce,
            })
            .done(function (response) {
              $loading.hide();

              var $appended = $(response.content).appendTo($load);

              $insert.parent().removeClass("hidden");

              $cloned = $appended.find(".agl--repeat-shortcode").agl_clone();

              $appended.agl_reload_script();
              $appended.find(".agl-fields").agl_reload_script();
            });
        } else {
          $insert.parent().addClass("hidden");
        }
      });

      $insert.on("click", function (e) {
        e.preventDefault();

        if ($insert.prop("disabled") || $insert.attr("disabled")) {
          return;
        }

        var shortcode = "";
        var serialize = $modal
          .find(".agl-field:not(.agl-depend-on)")
          .find(":input:not(.ignore)")
          .serializeObjectAGSHOPGLUT();

        switch (sc_view) {
          case "contents":
            var contentsObj = sc_name ? serialize[sc_name] : serialize;
            $.each(contentsObj, function (sc_key, sc_value) {
              var sc_tag = sc_name ? sc_name : sc_key;
              shortcode += "[" + sc_tag + "]" + sc_value + "[/" + sc_tag + "]";
            });
            break;

          case "group":
            shortcode += "[" + sc_name;
            $.each(serialize[sc_name], function (sc_key, sc_value) {
              shortcode += base.shortcode_tags(sc_key, sc_value);
            });
            shortcode += "]";
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            shortcode += "[/" + sc_name + "]";

            break;

          case "repeater":
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            break;

          default:
            shortcode += base.shortcode_parse(serialize);
            break;
        }

        shortcode = shortcode === "" ? "[" + sc_name + "]" : shortcode;

        if (gutenberg_id) {
          var content = window.agl_gutenberg_props.attributes.hasOwnProperty("shortcode")
            ? window.agl_gutenberg_props.attributes.shortcode
            : "";
          window.agl_gutenberg_props.setAttributes({shortcode: content + shortcode});
        } else if (editor_id) {
          base.send_to_editor(shortcode, editor_id);
        } else {
          var $textarea = target_id ? $(target_id) : $button.parent().find("textarea");
          $textarea.val(base.insertAtChars($textarea, shortcode)).trigger("change");
        }

        $modal.addClass("hidden");
      });

      $modal.on("click", ".agl--repeat-button", function (e) {
        e.preventDefault();

        var $repeatable = $modal.find(".agl--repeatable");
        var $new_clone = $cloned.agl_clone();
        var $remove_btn = $new_clone.find(".agl-repeat-remove");

        var $appended = $new_clone.appendTo($repeatable);

        $new_clone.find(".agl-fields").agl_reload_script();

        AGSHOPGLUT.helper.name_nested_replace($modal.find(".agl--repeat-shortcode"), sc_group);

        $remove_btn.on("click", function () {
          $new_clone.remove();

          AGSHOPGLUT.helper.name_nested_replace($modal.find(".agl--repeat-shortcode"), sc_group);
        });
      });

      $modal.on("click", ".agl-modal-close, .agl-modal-overlay", function () {
        $modal.addClass("hidden");
      });
    });
  };

  //
  // WP Color Picker
  //
  if (typeof Color === "function") {
    Color.prototype.toString = function () {
      if (this._alpha < 1) {
        return this.toCSS("rgba", this._alpha).replace(/\s+/g, "");
      }

      var hex = parseInt(this._color, 10).toString(16);

      if (this.error) {
        return "";
      }

      if (hex.length < 6) {
        for (var i = 6 - hex.length - 1; i >= 0; i--) {
          hex = "0" + hex;
        }
      }

      return "#" + hex;
    };
  }

  AGSHOPGLUT.funcs.parse_color = function (color) {
    var value = color.replace(/\s+/g, ""),
      trans = value.indexOf("rgba") !== -1 ? parseFloat(value.replace(/^.*,(.+)\)/, "$1") * 100) : 100,
      rgba = trans < 100 ? true : false;

    return {value: value, transparent: trans, rgba: rgba};
  };

  $.fn.agl_color = function () {
    return this.each(function () {
      var $input = $(this),
        picker_color = AGSHOPGLUT.funcs.parse_color($input.val()),
        palette_color = window.agl_vars.color_palette.length ? window.agl_vars.color_palette : true,
        $container;

      // Destroy and Reinit
      if ($input.hasClass("wp-color-picker")) {
        $input.closest(".wp-picker-container").after($input).remove();
      }

      $input.wpColorPicker({
        palettes: palette_color,
        change: function (event, ui) {
          var ui_color_value = ui.color.toString();

          $container.removeClass("agl--transparent-active");
          $container.find(".agl--transparent-offset").css("background-color", ui_color_value);
          $input.val(ui_color_value).trigger("change");
        },
        create: function () {
          $container = $input.closest(".wp-picker-container");

          var a8cIris = $input.data("a8cIris"),
            $transparent_wrap = $(
              '<div class="agl--transparent-wrap">' +
                '<div class="agl--transparent-slider"></div>' +
                '<div class="agl--transparent-offset"></div>' +
                '<div class="agl--transparent-text"></div>' +
                '<div class="agl--transparent-button">transparent <i class="fas fa-toggle-off"></i></div>' +
                "</div>"
            ).appendTo($container.find(".wp-picker-holder")),
            $transparent_slider = $transparent_wrap.find(".agl--transparent-slider"),
            $transparent_text = $transparent_wrap.find(".agl--transparent-text"),
            $transparent_offset = $transparent_wrap.find(".agl--transparent-offset"),
            $transparent_button = $transparent_wrap.find(".agl--transparent-button");

          if ($input.val() === "transparent") {
            $container.addClass("agl--transparent-active");
          }

          $transparent_button.on("click", function () {
            if ($input.val() !== "transparent") {
              $input.val("transparent").trigger("change").removeClass("iris-error");
              $container.addClass("agl--transparent-active");
            } else {
              $input.val(a8cIris._color.toString()).trigger("change");
              $container.removeClass("agl--transparent-active");
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
              $input.wpColorPicker("color", a8cIris._color.toString());
              $transparent_text.text(slide_value === 1 || slide_value === 0 ? "" : slide_value);
            },
            create: function () {
              var slide_value = parseFloat(picker_color.transparent / 100),
                text_value = slide_value < 1 ? slide_value : "";

              $transparent_text.text(text_value);
              $transparent_offset.css("background-color", picker_color.value);

              $container.on("click", ".wp-picker-clear", function () {
                a8cIris._color._alpha = 1;
                $transparent_text.text("");
                $transparent_slider.slider("option", "value", 100);
                $container.removeClass("agl--transparent-active");
                $input.trigger("change");
              });

              $container.on("click", ".wp-picker-default", function () {
                var default_color = AGSHOPGLUT.funcs.parse_color($input.data("default-color")),
                  default_value = parseFloat(default_color.transparent / 100),
                  default_text = default_value < 1 ? default_value : "";

                a8cIris._color._alpha = default_value;
                $transparent_text.text(default_text);
                $transparent_slider.slider("option", "value", default_color.transparent);

                if (default_color.value === "transparent") {
                  $input.removeClass("iris-error");
                  $container.addClass("agl--transparent-active");
                }
              });
            },
          });
        },
      });
    });
  };

  //
  // ChosenJS
  //
  $.fn.agl_chosen = function () {
    return this.each(function () {
      var $this = $(this),
        $inited = $this.parent().find(".chosen-container"),
        is_sortable = $this.hasClass("agl-chosen-sortable") || false,
        is_ajax = $this.hasClass("agl-chosen-ajax") || false,
        is_multiple = $this.attr("multiple") || false,
        set_width = is_multiple ? "100%" : "auto",
        set_options = $.extend(
          {
            allow_single_deselect: true,
            disable_search_threshold: 10,
            width: set_width,
            no_results_text: window.agl_vars.i18n.no_results_text,
          },
          $this.data("chosen-settings")
        );

      if ($inited.length) {
        $inited.remove();
      }

      // Chosen ajax
      if (is_ajax) {
        var set_ajax_options = $.extend(
          {
            data: {
              type: "post",
              nonce: "",
            },
            allow_single_deselect: true,
            disable_search_threshold: -1,
            width: "100%",
            min_length: 3,
            type_delay: 500,
            typing_text: window.agl_vars.i18n.typing_text,
            searching_text: window.agl_vars.i18n.searching_text,
            no_results_text: window.agl_vars.i18n.no_results_text,
          },
          $this.data("chosen-settings")
        );

        $this.AGSHOPGLUTAjaxChosen(set_ajax_options);
      } else {
        $this.chosen(set_options);
      }

      // Chosen keep options order
      if (is_multiple) {
        var $hidden_select = $this.parent().find(".agl-hide-select");
        var $hidden_value = $hidden_select.val() || [];

        $this.on("change", function (obj, result) {
          if (result && result.selected) {
            $hidden_select.append(
              '<option value="' + result.selected + '" selected="selected">' + result.selected + "</option>"
            );
          } else if (result && result.deselected) {
            $hidden_select.find('option[value="' + result.deselected + '"]').remove();
          }

          // Force customize refresh
          if (
            window.wp.customize !== undefined &&
            $hidden_select.children().length === 0 &&
            $hidden_select.data("customize-setting-link")
          ) {
            window.wp.customize.control($hidden_select.data("customize-setting-link")).setting.set("");
          }

          $hidden_select.trigger("change");
        });

        // Chosen order abstract
        $this.AGSHOPGLUTChosenOrder($hidden_value, true);
      }

      // Chosen sortable
      if (is_sortable) {
        var $chosen_container = $this.parent().find(".chosen-container");
        var $chosen_choices = $chosen_container.find(".chosen-choices");

        $chosen_choices.bind("mousedown", function (event) {
          if ($(event.target).is("span")) {
            event.stopPropagation();
          }
        });

        $chosen_choices.sortable({
          items: "li:not(.search-field)",
          helper: "orginal",
          cursor: "move",
          placeholder: "search-choice-placeholder",
          start: function (e, ui) {
            ui.placeholder.width(ui.item.innerWidth());
            ui.placeholder.height(ui.item.innerHeight());
          },
          update: function (e, ui) {
            var select_options = "";
            var chosen_object = $this.data("chosen");
            var $prev_select = $this.parent().find(".agl-hide-select");

            $chosen_choices.find(".search-choice-close").each(function () {
              var option_array_index = $(this).data("option-array-index");
              $.each(chosen_object.results_data, function (index, data) {
                if (data.array_index === option_array_index) {
                  select_options += '<option value="' + data.value + '" selected>' + data.value + "</option>";
                }
              });
            });

            $prev_select.children().remove();
            $prev_select.append(select_options);
            $prev_select.trigger("change");
          },
        });
      }
    });
  };

  //
  // Helper Checkbox Checker
  //
  $.fn.agl_checkbox = function () {
    return this.each(function () {
      var $this = $(this),
        $input = $this.find(".agl--input"),
        $checkbox = $this.find(".agl--checkbox");

      $checkbox.on("click", function () {
        $input.val(Number($checkbox.prop("checked"))).trigger("change");
      });
    });
  };

  //
  // Siblings
  //
  $.fn.agl_siblings = function () {
    return this.each(function () {
      var $this = $(this),
        $siblings = $this.find(".agl--sibling"),
        multiple = $this.data("multiple") || false;

      $siblings.on("click", function () {
        var $sibling = $(this);

        if (multiple) {
          if ($sibling.hasClass("agl--active")) {
            $sibling.removeClass("agl--active");
            $sibling.find("input").prop("checked", false).trigger("change");
          } else {
            $sibling.addClass("agl--active");
            $sibling.find("input").prop("checked", true).trigger("change");
          }
        } else {
          $this.find("input").prop("checked", false);
          $sibling.find("input").prop("checked", true).trigger("change");
          $sibling.addClass("agl--active").siblings().removeClass("agl--active");
        }
      });
    });
  };

  //
  // Help Tooltip
  //
  $.fn.agl_help = function () {
    return this.each(function () {
      var $this = $(this),
        $tooltip,
        offset_left;

      $this.on({
        mouseenter: function () {
          $tooltip = $('<div class="agl-tooltip"></div>').html($this.find(".agl-help-text").html()).appendTo("body");
          offset_left = AGSHOPGLUT.vars.is_rtl ? $this.offset().left + 24 : $this.offset().left - $tooltip.outerWidth();

          $tooltip.css({
            top: $this.offset().top - ($tooltip.outerHeight() / 2 - 14),
            left: offset_left,
          });
        },
        mouseleave: function () {
          if ($tooltip !== undefined) {
            $tooltip.remove();
          }
        },
      });
    });
  };

  //
  // Customize Refresh
  //
  $.fn.agl_customizer_refresh = function () {
    return this.each(function () {
      var $this = $(this),
        $complex = $this.closest(".agl-customize-complex");

      if ($complex.length) {
        var unique_id = $complex.data("unique-id");

        if (unique_id === undefined) {
          return;
        }

        var $input = $complex.find(":input"),
          option_id = $complex.data("option-id"),
          obj = $input.serializeObjectAGSHOPGLUT(),
          data = !$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id] ? obj[unique_id][option_id] : "",
          control = window.wp.customize.control(unique_id + "[" + option_id + "]");

        // clear the value to force refresh.
        control.setting._value = null;

        control.setting.set(data);
      } else {
        $this.find(":input").first().trigger("change");
      }

      $(document).trigger("agl-customizer-refresh", $this);
    });
  };

  //
  // Customize Listen Form Elements
  //
  $.fn.agl_customizer_listen = function (options) {
    var settings = $.extend(
      {
        closest: false,
      },
      options
    );

    return this.each(function () {
      if (window.wp.customize === undefined) {
        return;
      }

      var $this = settings.closest ? $(this).closest(".agl-customize-complex") : $(this),
        $input = $this.find(":input"),
        unique_id = $this.data("unique-id"),
        option_id = $this.data("option-id");

      if (unique_id === undefined) {
        return;
      }

      $input.on("change keyup", function () {
        var obj = $this.find(":input").serializeObjectAGSHOPGLUT();
        var val = !$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id] ? obj[unique_id][option_id] : "";

        window.wp.customize.control(unique_id + "[" + option_id + "]").setting.set(val);
      });
    });
  };

  //
  // Customizer Listener for Reload JS
  //
  $(document).on("expanded", ".control-section", function () {
    var $this = $(this);

    if ($this.hasClass("open") && !$this.data("inited")) {
      var $fields = $this.find(".agl-customize-field");
      var $complex = $this.find(".agl-customize-complex");

      if ($fields.length) {
        $this.agl_dependency();
        $fields.agl_reload_script({dependency: false});
        $complex.agl_customizer_listen();
      }

      $this.data("inited", true);
    }
  });

  //
  // Window on resize
  //
  AGSHOPGLUT.vars.$window
    .on(
      "resize agl.resize",
      AGSHOPGLUT.helper.debounce(function (event) {
        var window_width =
          navigator.userAgent.indexOf("AppleWebKit/") > -1 ? AGSHOPGLUT.vars.$window.width() : window.innerWidth;

        if (window_width <= 782 && !AGSHOPGLUT.vars.onloaded) {
          $(".agl-section").agl_reload_script();
          AGSHOPGLUT.vars.onloaded = true;
        }
      }, 200)
    )
    .trigger("agl.resize");

  //
  // Widgets Framework
  //
  $.fn.agl_widgets = function () {
    if (this.length) {
      $(document).on("widget-added widget-updated", function (event, $widget) {
        $widget.find(".agl-fields").agl_reload_script();
      });

      $(".widgets-sortables, .control-section-sidebar").on("sortstop", function (event, ui) {
        ui.item.find(".agl-fields").agl_reload_script_retry();
      });

      $(document).on("click", ".widget-top", function (event) {
        $(this).parent().find(".agl-fields").agl_reload_script();
      });
    }
  };

  //
  // Nav Menu Options Framework
  //
  $.fn.agl_nav_menu = function () {
    return this.each(function () {
      var $navmenu = $(this);

      $navmenu.on("click", "a.item-edit", function () {
        $(this).closest("li.menu-item").find(".agl-fields").agl_reload_script();
      });

      $navmenu.on("sortstop", function (event, ui) {
        ui.item.find(".agl-fields").agl_reload_script_retry();
      });
    });
  };

  //
  // Retry Plugins
  //
  $.fn.agl_reload_script_retry = function () {
    return this.each(function () {
      var $this = $(this);

      if ($this.data("inited")) {
        $this.children(".agl-field-wp_editor").AGSHOPGLUT_wp_editor();
      }
    });
  };

  //
  // Reload Plugins
  //
  $.fn.agl_reload_script = function (options) {
    var settings = $.extend(
      {
        dependency: true,
      },
      options
    );

    return this.each(function () {
      var $this = $(this);

      // Avoid for conflicts
      if (!$this.data("inited")) {
        // Field plugins
        $this.children(".agl-field-accordion").AGSHOPGLUT_accordion();
        $this.children(".agl-field-backup").AGSHOPGLUT_backup();
        $this.children(".agl-field-background").AGSHOPGLUT_background();
        $this.children(".agl-field-code_editor").AGSHOPGLUT_code_editor();
        $this.children(".agl-field-date").AGSHOPGLUT_date();
        $this.children(".agl-field-fieldset").AGSHOPGLUT_fieldset();
        $this.children(".agl-field-gallery").AGSHOPGLUT_gallery();
        $this.children(".agl-field-group").AGSHOPGLUT_group();
        $this.children(".agl-field-icon").AGSHOPGLUT_icon();
        $this.children(".agl-field-link").AGSHOPGLUT_link();
        $this.children(".agl-field-media").AGSHOPGLUT_media();
        $this.children(".agl-field-map").AGSHOPGLUT_map();
        $this.children(".agl-field-repeater").AGSHOPGLUT_repeater();
        $this.children(".agl-field-slider").AGSHOPGLUT_slider();
        $(".agl-field-slider").AGSHOPGLUT_slider();
        $this.children(".agl-field-sortable").AGSHOPGLUT_sortable();
        $this.children(".agl-field-sorter").AGSHOPGLUT_sorter();
        $this.children(".agl-field-spinner").AGSHOPGLUT_spinner();
        $this.children(".agl-field-switcher").AGSHOPGLUT_switcher();
        $this.children(".agl-field-tabbed").AGSHOPGLUT_tabbed();
        $this.children(".agl-field-section").AGSHOPGLUT_section();
        $this.children(".agl-field-typography").AGSHOPGLUT_typography();
        $this.children(".agl-field-upload").AGSHOPGLUT_upload();
        $this.children(".agl-field-wp_editor").AGSHOPGLUT_wp_editor();
        $this.children(".agl-field-wishlistMail").AGSHOPGLUT_wishlistMail();

        // Field colors
        $this.children(".agl-field-border").find(".agl-color").agl_color();
        $this.children(".agl-field-background").find(".agl-color").agl_color();
        $this.children(".agl-field-color").find(".agl-color").agl_color();
        $this.children(".agl-field-color_group").find(".agl-color").agl_color();
        $this.children(".agl-field-link_color").find(".agl-color").agl_color();
        $this.children(".agl-field-typography").find(".agl-color").agl_color();

        // Field chosenjs
        $this.children(".agl-field-select").find(".agl-chosen").agl_chosen();
        $this.children(".agl-field-taxonomy").find(".agl-chosen").agl_chosen();

        // Field Checkbox
        $this.children(".agl-field-checkbox").find(".agl-checkbox").agl_checkbox();

        // Field Siblings
        $this.children(".agl-field-button_set").find(".agl-siblings").agl_siblings();
        $this.children(".agl-field-image_select").find(".agl-siblings").agl_siblings();
        $this.children(".agl-field-palette").find(".agl-siblings").agl_siblings();

        // Help Tooptip
        $this.children(".agl-field").find(".agl-help").agl_help();

        // Trigger dependency check on change/input for various input types
        $this
          .find("select[data-depend-id], input[data-depend-id], textarea[data-depend-id]")
          .on("change input", function () {
            $this.agl_dependency();
          });

        if (settings.dependency) {
          $this.agl_dependency();
        }

        $this.data("inited", true);

        $(document).trigger("agl-reload-script", $this);
      }
    });
  };

  //
  // Document ready and run scripts
  //
  $(document).ready(function () {
    $(".agl-save").agl_save();
    $(".agl-options").agl_options();
    $(".agl-sticky-header").agl_sticky();
    $(".agl-nav-options").agl_nav_options();
    $(".agl-nav-metabox").agl_nav_metabox();
    $(".agl-taxonomy").agl_taxonomy();
    $(".agl-page-templates").agl_page_templates();
    $(".agl-post-formats").agl_post_formats();
    $(".agl-shortcode").agl_shortcode();
    $(".agl-search").agl_search();
    $(".agl-confirm").agl_confirm();
    $(".agl-expand-all").agl_expand_all();
    $(".agl-onload").agl_reload_script();
    $(".widget").agl_widgets();
    $("#menu-to-edit").agl_nav_menu();
  });
})(jQuery, window, document);
