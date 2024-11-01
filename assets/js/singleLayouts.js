jQuery(document).ready(function ($) {
  var layoutId = 1;

  function generateUniqueId(componentId) {
    return componentId + "_" + Math.floor(Math.random() * 7872315);
  }

  // Function to initialize draggable and sortable components
  function initializeComponents() {
    $(".component").draggable({
      helper: "clone",
      connectToSortable: "#right-sidebar-editor",
    });

    $("#right-sidebar-editor").sortable({
      receive: function (event, ui) {
        var componentId = ui.item.attr("id");
        var newComponentId = generateUniqueId(componentId);

        var newComponentHtml;
        if (componentId === "shopg_single_component_heading") {
          newComponentHtml = $("#component-html-heading").html();
        } else if (componentId === "shopg_single_component_image") {
          newComponentHtml = $("#component-html-image").html();
        }

        var newComponent = $(newComponentHtml).attr("id", newComponentId);
        $("#right-sidebar-editor").append(newComponent);

        ui.helper.remove();
      },
    });

    $("#component-home").on("click", function () {
      $(".components-settings-head").hide();
      $(".shopglut-nav-content").show();
      $(".single-product-components").show();
    });

    $("#right-sidebar-editor").on("click", ".added-component", function () {
      var componentId = $(this).attr("id");

      var baseComponentId = componentId.split("_").slice(0, -1).join("_");

      $('[class^="agl-section-content shopg_single_component_"]').hide();

      var specificComponent = $("." + componentId);
      if (specificComponent.length > 0) {
        specificComponent.show();
      } else {
        var baseComponent = $("." + baseComponentId);
        if (baseComponent.length > 0) {
          var newComponentId = componentId;
          var newComponent = baseComponent
            .clone(true)
            .attr("class", "agl-section-content " + newComponentId)
            .appendTo(".agl-field-section");
          newComponent.show();
          reinitializeComponent(componentId, newComponent);
        }
      }
      $(".components-settings-head").show();
      $(".shopglut-nav-content").hide();
      $(".single-product-components").hide();

      // Attach event listeners to the inputs for dynamic CSS updates
      attachInputListeners(componentId, baseComponentId);
    });
  }

  function attachInputListeners(componentId, baseComponentId) {
    $("." + componentId)
      .find("input, select")
      .each(function () {
        $(this).on("input change", function () {
          var newRules = {};
          var $selector;

          if (baseComponentId == "shopg_single_component_heading") {
            if ($(this).attr("data-depend-id") === "heading_title") {
              var inputText = $(this).val();
              $("#" + componentId + " h1").text(inputText);
            } else if ($(this).attr("data-depend-id") === "heading_title_align") {
              $selector = "h1";
              newRules["text-align"] = $(this).val();
            } else if ($(this).attr("data-depend-id") === "heading_title_BGColor") {
              $selector = ".wrap-custom-heading";
              newRules["background-color"] = $(this).val();
            } else if ($(this).attr("data-depend-id").startsWith("heading_title_margin_desktop")) {
              $selector = "h1";
              var topMargin =
                $("." + componentId)
                  .find('[data-depend-id="heading_title_margin_desktop_top"]')
                  .val() || "0";
              var bottomMargin =
                $("." + componentId)
                  .find('[data-depend-id="heading_title_margin_desktop_bottom"]')
                  .val() || "0";
              var leftMargin =
                $("." + componentId)
                  .find('[data-depend-id="heading_title_margin_desktop_left"]')
                  .val() || "0";
              var rightMargin =
                $("." + componentId)
                  .find('[data-depend-id="heading_title_margin_desktop_right"]')
                  .val() || "0";
              newRules["margin"] = topMargin + "px " + rightMargin + "px " + bottomMargin + "px " + leftMargin + "px";
            } else if ($(this).attr("data-depend-id").startsWith("heading_title_padding_desktop")) {
              $selector = "h1";
              var topPadding =
                $("." + componentId)
                  .find('[data-depend-id="heading_title_padding_desktop_top"]')
                  .val() || "0";
              var bottomPadding =
                $("." + componentId)
                  .find('[data-depend-id="heading_title_padding_desktop_bottom"]')
                  .val() || "0";
              var leftPadding =
                $("." + componentId)
                  .find('[data-depend-id="heading_title_padding_desktop_left"]')
                  .val() || "0";
              var rightPadding =
                $("." + componentId)
                  .find('[data-depend-id="heading_title_padding_desktop_right"]')
                  .val() || "0";
              newRules["padding"] =
                topPadding + "px " + rightPadding + "px " + bottomPadding + "px " + leftPadding + "px";
            } else if ($(this).attr("data-depend-id").startsWith("heading_subtitle_margin_desktop")) {
              $selector = "p";
              var topMargin =
                $("." + componentId)
                  .find('[data-depend-id="heading_subtitle_margin_desktop_top"]')
                  .val() || "0";
              var bottomMargin =
                $("." + componentId)
                  .find('[data-depend-id="heading_subtitle_margin_desktop_bottom"]')
                  .val() || "0";
              var leftMargin =
                $("." + componentId)
                  .find('[data-depend-id="heading_subtitle_margin_desktop_left"]')
                  .val() || "0";
              var rightMargin =
                $("." + componentId)
                  .find('[data-depend-id="heading_subtitle_margin_desktop_right"]')
                  .val() || "0";
              newRules["margin"] = topMargin + "px " + rightMargin + "px " + bottomMargin + "px " + leftMargin + "px";
            } else if ($(this).attr("data-depend-id").startsWith("heading_subtitle_padding_desktop")) {
              $selector = "p";
              var topPadding =
                $("." + componentId)
                  .find('[data-depend-id="heading_subtitle_padding_desktop_top"]')
                  .val() || "0";
              var bottomPadding =
                $("." + componentId)
                  .find('[data-depend-id="heading_subtitle_padding_desktop_bottom"]')
                  .val() || "0";
              var leftPadding =
                $("." + componentId)
                  .find('[data-depend-id="heading_subtitle_padding_desktop_left"]')
                  .val() || "0";
              var rightPadding =
                $("." + componentId)
                  .find('[data-depend-id="heading_subtitle_padding_desktop_right"]')
                  .val() || "0";
              newRules["padding"] =
                topPadding + "px " + rightPadding + "px " + bottomPadding + "px " + leftPadding + "px";
            }
          }

          updateComponentCSS(componentId, $selector, newRules);
        });
      });
  }

  function updateComponentCSS(componentId, selector, newRules) {
    var styleId = "dynamic-style-" + componentId;
    var style = $("#" + styleId);

    if (style.length === 0) {
      style = $("<style>", {
        id: styleId,
      }).appendTo("head");
    }

    // Parse existing CSS rules
    var existingRules = style.html();
    var rulesObj = {};
    if (existingRules) {
      existingRules
        .split("}")
        .filter((rule) => rule.trim().length > 0)
        .forEach((rule) => {
          var parts = rule.split("{");
          if (parts.length === 2) {
            var existingSelector = parts[0].trim();
            var properties = parts[1]
              .trim()
              .split(";")
              .filter((prop) => prop.trim().length > 0);
            if (!rulesObj[existingSelector]) {
              rulesObj[existingSelector] = {};
            }
            properties.forEach((prop) => {
              var propParts = prop.split(":");
              if (propParts.length === 2) {
                rulesObj[existingSelector][propParts[0].trim()] = propParts[1].trim();
              }
            });
          }
        });
    }

    var fullSelector = "#" + componentId + " " + selector;
    if (!rulesObj[fullSelector]) {
      rulesObj[fullSelector] = {};
    }
    Object.keys(newRules).forEach((key) => {
      rulesObj[fullSelector][key] = newRules[key];
    });

    // Generate new CSS
    var css = "";
    Object.keys(rulesObj).forEach((sel) => {
      css += sel + " { ";
      Object.keys(rulesObj[sel]).forEach((prop) => {
        css += prop + ": " + rulesObj[sel][prop] + "; ";
      });
      css += "} ";
    });

    // Apply the new CSS
    style.html(css);
  }

  function reinitializeComponent(componentId, component) {
    // Reinitialize event handlers or other logic for the component
    component.find(".shopg_fake_click").on("click", function () {
      // Handle click event if needed
    });

    // Initialize wpColorPicker for heading_title_BGColor
    var colorPickerInput = component.find('[data-depend-id="heading_title_BGColor"]');
    colorPickerInput.wpColorPicker();

    // Additional method to listen to the wpColorPicker change event
    colorPickerInput.wpColorPicker({
      change: function (event, ui) {
        var newColor = ui.color.toString(); // Get the selected color value
        var $selector = ".wrap-custom-heading";
        var newRules = {
          "background-color": newColor,
        };

        // Update the component's CSS
        updateComponentCSS(componentId, $selector, newRules);
      },
    });
  }

  // Event handler for saving all component settings
  $("#save-button").on("click", function () {
    var componentsData = [];
    $("#right-sidebar-editor .added-component").each(function () {
      var componentId = $(this).attr("id");
      var settingsData = [];
      $("." + componentId)
        .find("input, select")
        .each(function () {
          settingsData.push({
            name: $(this).attr("name"),
            value: $(this).val(),
          });
        });
      componentsData.push({
        component_id: componentId,
        settings: settingsData,
      });
    });

    $.ajax({
      url: layouts_vars.ajaxurl,
      type: "POST",
      data: {
        action: "save_component_settings",
        layout_id: layoutId,
        components: componentsData,
      },
      success: function (response) {
        console.log(response.data);
      },
      error: function (error) {
        console.error("Error saving components:", error);
      },
    });
  });

  $("#save-now").on("click", function () {
    var $message = $("#message");

    $message.show().removeClass("slide-out").addClass("slide-in");

    setTimeout(function () {
      $message.removeClass("slide-in").addClass("slide-out");

      setTimeout(function () {
        $message.hide();
      }, 500);
    }, 3000);
  });

  initializeComponents();
});
