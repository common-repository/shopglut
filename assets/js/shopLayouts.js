jQuery(document).ready(function ($) {
  // Use jQuery to select all the elements and apply inline style with !important
  $(".shopglut-admin .updated, .shopglut-admin .success, .shopglut-admin .notice").each(function () {
    $(this).attr("style", "display: none !important;");
  });

  var ajaxRequestMade = false;

  jQuery(window).on("load", function () {
    jQuery(".loader-container, .loader-overlay").fadeOut("slow", function () {
      // jQuery(".loader-overlay").remove();
    });
  });

  $(window).on("click", function (e) {
    if ($(e.target).is("#shop-layouts-quick-view-modal")) {
      $("#shop-layouts-quick-view-modal").hide();
    }
  });

  function loadComparisonTable(productId) {
    $(".shop-layouts-compare-modal").hide();
    $(".comparison-data").hide(); // Hide the comparison data
    $(".loader-container, .loader-overlay").show(); // Show the loading icon

    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "load_comparison_table",
        product_id: productId,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          $(".shop-layouts-compare-modal").show();
          $(".comparison-data")
            .html(response.data)
            .show(0, function () {
              // This callback function runs after .html() has finished
              $(".loader-container, .loader-overlay").hide();
            });
        } else {
          $(".loader-container, .loader-overlay").hide(); // Hide the loading icon
          alert("Failed to load comparison data: " + response.data);
        }
      },
      error: function () {
        $(".loader-container, .loader-overlay").hide(); // Hide the loading icon
        alert("An error occurred while loading the comparison data.");
      },
    });
  }

  function loadAfterRemoveCompareTable(productId) {
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "load_comparison_table",
        product_id: productId,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          $(".shop-layouts-compare-modal").show();
          $(".comparison-data")
            .html(response.data)
            .show(0, function () {});
        } else {
          alert("Failed to load comparison data: " + response.data);
        }
      },
      error: function () {
        $(".loader-container, .loader-overlay").hide(); // Hide the loading icon
        alert("An error occurred while loading the comparison data.");
      },
    });
  }

  function removeProductFromComparison(product_id) {
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "remove_from_comparison",
        product_id: product_id,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          loadAfterRemoveCompareTable();
        } else {
          alert("Failed to remove product from comparison: " + response.data);
        }
      },
    });
  }

  function filterProducts() {
    // Retrieve selected and excluded filters
    var selectedProductIds = $('[data-depend-id="shopg-layouts-include-products"]').val() || [];
    var excludedProductIds = $('[data-depend-id="shopg-layouts-exclude-products"]').val() || [];
    var selectedCategoryIds = $('[data-depend-id="shopg-layouts-include-categories"]').val() || [];
    var excludeCategoryIds = $('[data-depend-id="shopg-layouts-exclude-categories"]').val() || [];
    var includeTagIds = $('[data-depend-id="shopg-layouts-include-tags"]').val() || [];
    var excludeTagIds = $('[data-depend-id="shopg-layouts-exclude-tags"]').val() || [];
    var includeTypeIds = $('[data-depend-id="shopg-layouts-product-type"]').val() || [];

    // Convert selected filters to sets for efficient lookup
    var selectedProductIdsSet = new Set(selectedProductIds);
    var excludedProductIdsSet = new Set(excludedProductIds);
    var selectedCategoryIdsSet = new Set(selectedCategoryIds);
    var excludeCategoryIdsSet = new Set(excludeCategoryIds);
    var includeTagIdsSet = new Set(includeTagIds);
    var excludeTagIdsSet = new Set(excludeTagIds);
    var includeTypeIdsSet = new Set(includeTypeIds);

    // Track whether any product is shown
    var anyProductShown = false;

    $(".product-design").each(function () {
      var productId = $(this).attr("data-product-id");
      var productCategories = $(this).attr("data-product-category")
        ? $(this).attr("data-product-category").split(",")
        : [];
      var productTags = $(this).attr("data-product-tags") ? $(this).attr("data-product-tags").split(",") : [];
      var productType = $(this).attr("data-product-type") || "";

      // Check if product matches selected criteria
      var matchesProduct =
        (selectedProductIdsSet.size === 0 || selectedProductIdsSet.has(productId)) &&
        !excludedProductIdsSet.has(productId);
      var matchesCategory =
        (selectedCategoryIdsSet.size === 0 ||
          productCategories.some((category) => selectedCategoryIdsSet.has(category))) &&
        !productCategories.some((category) => excludeCategoryIdsSet.has(category));
      var matchesTag =
        (includeTagIdsSet.size === 0 || productTags.some((tag) => includeTagIdsSet.has(tag))) &&
        !productTags.some((tag) => excludeTagIdsSet.has(tag));
      var matchesType = includeTypeIdsSet.size === 0 || includeTypeIdsSet.has(productType);

      // Show or hide the product based on the combined filter criteria
      if (matchesProduct && matchesCategory && matchesTag && matchesType) {
        $(this).show();
        anyProductShown = true;
      } else {
        $(this).hide();
      }
    });

    // Show "No Product Found" message if no product is shown
    if (!anyProductShown) {
      $("#no-product-found").show();
    } else {
      $("#no-product-found").hide();
    }
  }

  function updateShopLayoutCSS(componentId, selector, newRules) {
    var styleId = componentId + "-dynamic-style";
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

    var css = "";
    Object.keys(rulesObj).forEach((sel) => {
      css += sel + " { ";
      Object.keys(rulesObj[sel]).forEach((prop) => {
        css += prop + ": " + rulesObj[sel][prop] + "; ";
      });
      css += "} ";
    });

    style.html(css);
  }

  function showNotification(message, type) {
    var $notification = $('<div class="notification"></div>').addClass(type);
    $notification.html(message);

    $("#shopg-notification-container").append($notification);

    $notification.show().animate({right: "20px"}, 500);

    // Optionally remove after a delay
    setTimeout(function () {
      $notification.animate({right: "-300px"}, 500, function () {
        $(this).remove();
      });
    }, 3000);
  }

  $("body").on("click", ".shopg_shop_layouts .wishlist", function (e) {
    e.preventDefault();

    var $button = $(this);
    var product_id = $button.data("product-id");
    var is_wishlisted = $button.find("i").hasClass("fa-solid");

    if (is_wishlisted) {
      $.ajax({
        type: "POST",
        url: ajax_data.ajax_url,
        data: {
          action: "shopglut_remove_from_wishlist",
          product_id: product_id,
          nonce: ajax_data.nonce,
        },
        success: function (response) {
          if (response.success) {
            $button.find("i").removeClass("fa-solid fa-heart").addClass("fa-regular fa-heart");
            $button.attr("data-original-title", "Add to Wishlist");
            $removeHtml =
              "<div class='wishlist-removed'><i class='fa-solid fa-circle-xmark'></i> Product Removed from Wishlist</div>";
            showNotification($removeHtml, "wishlist-removed");
          } else {
            alert("Failed to remove from wishlist: " + response.data);
          }
        },
      });
    } else {
      $.ajax({
        type: "POST",
        url: ajax_data.ajax_url,
        data: {
          action: "shopglut_add_to_wishlist",
          product_id: product_id,
          nonce: ajax_data.nonce,
        },
        success: function (response) {
          console.log("Response:", response);
          if (response.success) {
            $button.find("i").removeClass("fa-regular fa-heart").addClass("fa-solid fa-heart");
            $button.attr("data-original-title", "Added to Wishlist");
            $sucsessHtml =
              "<div class='wishlist-added'><i class='fa fa-check-circle'></i> Product Added to Wishlist</div>";
            showNotification($sucsessHtml, "wishlist-added");
          } else {
            alert("Failed to add to wishlist: " + response.data);
          }
        },
      });
    }
  });
  $("body").on("click", ".shopg_shop_layouts  .compare", function (e) {
    e.preventDefault();
    var productId = $(this).data("product-id");
    loadComparisonTable(productId);
  });
  $("body").on("click", ".shopg_shop_layouts .quick-view", function (e) {
    e.preventDefault();

    var product_id = $(this).data("product-id");

    $(".loader-container, .loader-overlay").show();
    $(".product-overview").hide().html("");

    // Fetch product data via AJAX
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "quick_view_product",
        product_id: product_id,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          $("#shop-layouts-quick-view-modal").show().fadeIn("slow");
          $(".product-overview").html(response.data).show().fadeIn("slow");
          $(".loader-container, .loader-overlay").hide();
        } else {
          alert("Failed to load product data: " + response.data);
        }
      },
      error: function () {
        $(".loader-container, .loader-overlay").hide();
        alert("An error occurred while loading the product data.");
      },
    });
  });
  $("body").on("click", "button.shopg-shop-remove-compare", function () {
    var productId = $(this).data("product-id");
    removeProductFromComparison(productId);
  });

  $("body").on("click", ".shopg_shop_layouts .product-cart-action .ajax-spin-cart", function (e) {
    var $button = $(this);

    if ($button.find(".cart-added").is(":visible")) {
      return true;
    }

    var product_id = $button.data("product-id");

    e.preventDefault();

    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "add_to_cart",
        product_id: product_id,
        nonce: ajax_data.nonce,
      },
      beforeSend: function () {
        $button.find(".cart-contents").hide();
        $button.find(".cart-loading").show();
        $button.find(".cart-added").hide();
        $button.find(".cart-unavailable").hide();
      },
      success: function (response) {
        if (response.success) {
          $button.find(".cart-loading").hide();
          $button.find(".cart-added").show();
          $button.attr("href", ajax_data.cart_url);
          $button.attr("target", "_blank"); // Ensure the link opens in a new tab
        } else {
          $button.find(".cart-loading").hide();
          $button.find(".cart-unavailable").show();
        }
      },
      error: function () {
        $button.find(".cart-loading").hide();
        $button.find(".cart-unavailable").show();
      },
    });
  });

  $(".shop-layouts-compare-modal .shop-layouts-compare-modal-close").on("click", function () {
    $(".shop-layouts-compare-modal").hide();
  });

  $(".shop-layouts-quick-view-modal-close").on("click", function () {
    $("#shop-layouts-quick-view-modal").hide();
  });

  $("#shopg-shoplayout-settings").on("input change", "input, select", function () {
    var newRules = {};
    var $selector;

    var componentId = "shopg-shoplayout-container";

    const dataDependId = $(this).attr("data-depend-id");

    if (
      [
        "shopg-layouts-include-products",
        "shopg-layouts-exclude-products",
        "shopg-layouts-include-categories",
        "shopg-layouts-exclude-categories",
        "shopg-layouts-include-tags",
        "shopg-layouts-exclude-tags",
        "shopg-layouts-product-type",
      ].includes($(this).attr("data-depend-id"))
    ) {
      if (!ajaxRequestMade) {
        var postid = $("#shopg_shop_layoutid").val();
        $(".shopg-inside-loader, .shopg-inside-loader-container, .shopg-inside-loader-overlay").show();
        $.ajax({
          type: "POST",
          url: ajax_data.ajax_url,
          data: {
            action: "retrive_shopg_shopdata",
            shopg_shop_layoutid: postid,
            nonce: ajax_data.nonce,
          },
          success: function (response) {
            if (response.success) {
              $(".shopg-inside-loader .shopg-inside-loader-container, .shopg-inside-loader-overlay").hide();
              $(".agl-field-preview").html(response.data.html);
              filterProducts();
              ajaxRequestMade = true;
            } else {
              console.log("Failed to save data: " + response.data);
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            $(".loader-container, .loader-overlay").hide();
            console.log("AJAX request failed:");
            console.log("Status: " + textStatus);
            console.log("Error Thrown: " + errorThrown);
            console.log("Response Text: " + jqXHR.responseText);
            alert("Failed to save data. Check console for details.");
          },
        });
      } else {
        filterProducts();
      }
    }

    const displayRules = {
      "show-add-to-cart": ".product-cart-action",
      "show-add-to-cart-text": ".product-cart-action .cart-title",
      "show-wishlist-cart": ".shopg-wishlist-section",
      "shopg-show-compare": ".shopg-compare-section",
      "show-quickview": ".quickview-section",
      "shopg-show-title": ".shopg_shop_layouts .product-title",
      "shopg-show-price": ".shopg_shop_layouts .price-box",
      "shopg-show-image": ".shopg_shop_layouts .product-thumb",
      // "shopg-show-category": ".shopg_shop_layouts .product-category",
      "shopg-show-review": ".shopg_shop_layouts .ratings",
      "shopg-show-badge": ".shopg_shop_layouts .product-badge",
    };

    const iconClasses = {
      "shopg-change-cart-icon": ".cart-contents i",
      "shopg-change-added-cart-icon": ".cart-added i",
      "change-wishlist-icon": ".shopg-wishlist-section .not-added i",
      "wishlist-added-icon": ".shopg-wishlist-section .added i",
      "change-compare-icon": ".shopg-compare-section i",
      "quickview-icon": ".quickview-section .quick-view i",
    };

    const tooltipTitles = {
      "show-wishlist-tooltip": ".shopg-wishlist-section .wishlist",
      "show-wishlist-tooltip-text": ".shopg-wishlist-section .wishlist",
      "show-compare-tooltip": ".shopg-compare-section .compare",
      "show-compare-tooltip-text": ".shopg-compare-section .compare",
      "show-quickview-tooltip": ".quickview-section .quick-view",
      "show-quickview-tooltip-text": ".quickview-section .quick-view",
    };

    // Handle display rules
    if (displayRules[dataDependId]) {
      $selector = displayRules[dataDependId];
      newRules["display"] = $(this).val() === "1" ? "inline-block" : "none";
    }

    // Handle icon class changes
    if (iconClasses[dataDependId]) {
      $(iconClasses[dataDependId]).attr("class", $(this).val());
    }

    // Handle tooltip titles
    if (tooltipTitles[dataDependId]) {
      const $tooltipElement = $(tooltipTitles[dataDependId]);
      if (dataDependId.includes("text")) {
        const tooltipText = $(`[data-depend-id="${dataDependId}"]`).val();
        $tooltipElement.attr("title", tooltipText);
      } else {
        const tooltipText = $(`[data-depend-id="${dataDependId}-text"]`).val();
        $tooltipElement.attr("title", $(this).val() === "1" ? tooltipText : null);
      }
    }

    // Handle text change for add to cart
    if (dataDependId === "shopg-change-cart-text") {
      $(".cart-contents .cart-title").text($(this).val());
    }

    if (
      ["shopg-layouts-product-options", "shopg-layouts-product-sorting", "shopg-layouts-product-order"].includes(
        $(this).attr("data-depend-id")
      )
    ) {
      $(".shopg-inside-loader, .shopg-inside-loader-container, .shopg-inside-loader-overlay").show();

      var postid = $("#shopg_shop_layoutid").val();
      var layoutName = $("#layout_name").val();
      var layoutTemplate = $("#layout_template").val();

      var shopgOptionsSettings = {};

      function setNestedProperty(obj, keys, value) {
        var lastKey = keys.pop();
        var nestedObj = keys.reduce((o, key) => (o[key] = o[key] || {}), obj);
        nestedObj[lastKey] = value;
      }
      $("#shopg-shoplayout-settings :input").each(function () {
        var input = $(this);
        var name = input.attr("name");
        var value = input.val();

        if (name) {
          var keys = name.split("[").map((k) => k.replace("]", ""));
          setNestedProperty(shopgOptionsSettings, keys, value);
        }
      });

      $.ajax({
        type: "POST",
        url: ajax_data.ajax_url,
        data: {
          action: "save_shopg_shopdata",
          shopg_shop_layoutid: postid,
          layout_name: layoutName,
          layout_template: layoutTemplate,
          shopg_options_settings: shopgOptionsSettings,
          nonce: ajax_data.nonce,
        },
        success: function (response) {
          if (response.success) {
            $(".shopg-inside-loader, .shopg-inside-loader-container, .shopg-inside-loader-overlay").hide();
            $(".agl-field-preview").html(response.data.html);
            ajaxRequestMade = false;
          } else {
            console.log("Failed to save data: " + response.data);
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $(".shopg-inside-loader, .shopg-inside-loader-container, .shopg-inside-loader-overlay").hide(); // Hide the loading icon
          console.log("AJAX request failed...");
          console.log("Status: " + textStatus);
          console.log("Error Thrown: " + errorThrown);
          console.log("Response Text: " + jqXHR.responseText);
          alert("Failed to save data. Check console for details.");
          ajaxRequestMade = false;
        },
      });
    }

    //Style Section
    if ($(this).attr("data-depend-id") === "shopg-column-grid-desktop") {
      $selector = "#shopg_shop_layout_contents .shopg_shop_layouts";
      let columns = $(this).val().replace("col-", "");
      newRules["grid-template-columns"] = "repeat(" + columns + ", 1fr)";
    } else if ($(this).attr("data-depend-id") === "shopg-column-grid-tablet") {
      $selector = "#shopg_shop_layout_contents.width-50 .shopg_shop_layouts";
      let columns = $(this).val().replace("col-", "");
      newRules["grid-template-columns"] = "repeat(" + columns + ", 1fr)";
    } else if ($(this).attr("data-depend-id") === "shopg-column-grid-mobile") {
      $selector = "#shopg_shop_layout_contents.width-30 .shopg_shop_layouts";
      let columns = $(this).val().replace("col-", "");
      newRules["grid-template-columns"] = "repeat(" + columns + ", 1fr)";
    } else if ($(this).attr("data-depend-id") === "shopg-column-gap-desktop") {
      $selector = "#shopg_shop_layout_contents.width-100 .shopg_shop_layouts";
      var unitValue = $('[data-depend-id="shopg-column-gap-desktop-unit"]:checked').val() || "px";
      newRules["grid-column-gap"] = $(this).val() + unitValue;
    } else if ($(this).attr("data-depend-id") === "shopg-column-gap-desktop-unit") {
      $selector = "#shopg_shop_layout_contents.width-100 .shopg_shop_layouts";
      var gapValue = $('[data-depend-id="shopg-column-gap-desktop"]').val();
      newRules["grid-column-gap"] = gapValue + $(this).val();
    } else if ($(this).attr("data-depend-id") === "shopg-column-gap-tablet") {
      $selector = "#shopg_shop_layout_contents.width-50 .shopg_shop_layouts";
      var unitValue = $('[data-depend-id="shopg-column-gap-tablet-unit"]:checked').val() || "px";
      newRules["grid-column-gap"] = $(this).val() + unitValue;
    } else if ($(this).attr("data-depend-id") === "shopg-column-gap-tablet-unit") {
      $selector = "#shopg_shop_layout_contents.width-50 .shopg_shop_layouts";
      var gapValue = $('[data-depend-id="shopg-column-gap-tablet"]').val();
      newRules["grid-column-gap"] = gapValue + $(this).val();
    } else if ($(this).attr("data-depend-id") === "shopg-column-gap-mobile") {
      $selector = "#shopg_shop_layout_contents.width-30 .shopg_shop_layouts";
      var unitValue = $('[data-depend-id="shopg-column-gap-mobile-unit"]:checked').val() || "px";
      newRules["grid-column-gap"] = $(this).val() + unitValue;
    } else if ($(this).attr("data-depend-id") === "shopg-column-gap-mobile-unit") {
      $selector = "#shopg_shop_layout_contents.width-30 .shopg_shop_layouts";
      var gapValue = $('[data-depend-id="shopg-column-gap-mobile"]').val();
      newRules["grid-column-gap"] = gapValue + $(this).val();
    } else if ($(this).attr("data-depend-id") === "shopg-row-gap-desktop") {
      $selector = "#shopg_shop_layout_contents.width-100 .shopg_shop_layouts";
      var unitValue = $('[data-depend-id="shopg-column-row-desktop-unit"]:checked').val() || "px";
      newRules["grid-row-gap"] = $(this).val() + unitValue;
    } else if ($(this).attr("data-depend-id") === "shopg-row-gap-desktop-unit") {
      $selector = "#shopg_shop_layout_contents.width-100 .shopg_shop_layouts";
      var gapValue = $('[data-depend-id="shopg-row-gap-desktop"]').val();
      newRules["grid-row-gap"] = gapValue + $(this).val();
    } else if ($(this).attr("data-depend-id") === "shopg-row-gap-tablet") {
      $selector = "#shopg_shop_layout_contents.width-50 .shopg_shop_layouts";
      var unitValue = $('[data-depend-id="shopg-row-gap-tablet-unit"]:checked').val() || "px";
      newRules["grid-row-gap"] = $(this).val() + unitValue;
    } else if ($(this).attr("data-depend-id") === "shopg-row-gap-tablet-unit") {
      $selector = "#shopg_shop_layout_contents.width-50 .shopg_shop_layouts";
      var gapValue = $('[data-depend-id="shopg-row-gap-tablet"]').val();
      newRules["grid-row-gap"] = gapValue + $(this).val();
    } else if ($(this).attr("data-depend-id") === "shopg-row-gap-mobile") {
      $selector = "#shopg_shop_layout_contents.width-30 .shopg_shop_layouts";
      var unitValue = $('[data-depend-id="shopg-row-gap-mobile-unit"]:checked').val() || "px";
      newRules["grid-row-gap"] = $(this).val() + unitValue;
    } else if ($(this).attr("data-depend-id") === "shopg-row-gap-mobile-unit") {
      $selector = "#shopg_shop_layout_contents.width-30 .shopg_shop_layouts";
      var gapValue = $('[data-depend-id="shopg-row-gap-mobile"]').val();
      newRules["grid-row-gap"] = gapValue + $(this).val();
    }

    const sizeMap = {
      desktop: "100",
      tablet: "50",
      mobile: "30",
    };

    // Function to update margin or padding rules for Shop Body
    function updateShopBodyBoxModelRule(size, property) {
      $selector = `#shopg_shop_layout_contents.width-${sizeMap[size]} .shopg_shop_layouts`;
      const top = $(`[data-depend-id="shopbody-${property}-${size}-top"]`).val() || "0";
      const right = $(`[data-depend-id="shopbody-${property}-${size}-right"]`).val() || "0";
      const bottom = $(`[data-depend-id="shopbody-${property}-${size}-bottom"]`).val() || "0";
      const left = $(`[data-depend-id="shopbody-${property}-${size}-left"]`).val() || "0";
      const unit = $(`[data-depend-id="shopbody-${property}-${size}-unit"]:checked`).val() || "px";
      newRules[property] = `${top}${unit} ${right}${unit} ${bottom}${unit} ${left}${unit}`;
    }

    if (dataDependId && dataDependId.startsWith("shopbody-margin-")) {
      const size = dataDependId.split("-")[2]; // Extract size (desktop, tablet, mobile)
      updateShopBodyBoxModelRule(size, "margin");
    } else if (dataDependId && dataDependId.startsWith("shopbody-padding-")) {
      const size = dataDependId.split("-")[2]; // Extract size (desktop, tablet, mobile)
      updateShopBodyBoxModelRule(size, "padding");
    } else if (dataDependId && dataDependId.startsWith("shopbody-margin-") && dataDependId.endsWith("-unit")) {
      const size = dataDependId.split("-")[2]; // Extract size (desktop, tablet, mobile)
      updateShopBodyBoxModelRule(size, "margin");
    } else if (dataDependId && dataDependId.startsWith("shopbody-padding-") && dataDependId.endsWith("-unit")) {
      const size = dataDependId.split("-")[2]; // Extract size (desktop, tablet, mobile)
      updateShopBodyBoxModelRule(size, "padding");
    }

    // Function to update typography rules for Shop Body
    function updateShopBodyTypographyRule() {
      $selector = "#shopg_shop_layout_contents .shopg_shop_layouts";
      const fontFamily = $('[data-depend-id="shopbody-typography-font-family"]').val();
      const fontWeight = $('[data-depend-id="shopbody-typography-font-weights"]').val();
      const fontStyle = $('[data-depend-id="shopbody-typography-font-styles"]').val();
      const textTransform = $('[data-depend-id="shopbody-typography-text-transform"]').val();
      const textDecoration = $('[data-depend-id="shopbody-typography-text-decoration"]').val();

      newRules["font-family"] = fontFamily + " !important" || "inherit";
      newRules["font-weight"] = fontWeight + " !important" || "normal";
      newRules["font-style"] = fontStyle + " !important" || "normal";
      newRules["text-transform"] = textTransform + " !important" || "none";
      newRules["text-decoration"] = textDecoration + " !important" || "none";
    }

    if (dataDependId && dataDependId.startsWith("shopbody-typography")) {
      updateShopBodyTypographyRule();
    }

    if (dataDependId && dataDependId === "shopbody-normal-background") {
      $selector = "#shopg_shop_layout_contents .shopg_shop_layouts";
      const color = $(this).val();
      newRules["background-color"] = color || "transparent";
    } else if (dataDependId && dataDependId === "shopbody-hover-background") {
      $selector = "#shopg_shop_layout_contents .shopg_shop_layouts:hover";
      const color = $(this).val();
      newRules["background-color"] = color || "transparent";
    }

    // Function to update border rules for Shop Body
    function updateShopBodyBorderRule() {
      $selector = "#shopg_shop_layout_contents .shopg_shop_layouts";
      const borderStyle = $('[data-depend-id="shopbody-border-border-type"]').val();
      const borderColor = $('[data-depend-id="shopbody-border-color-value"]').val() || "#000";
      const borderTop = $('[data-depend-id="shopbody-border-top"]').val() || "0";
      const borderRight = $('[data-depend-id="shopbody-border-right"]').val() || "0";
      const borderBottom = $('[data-depend-id="shopbody-border-bottom"]').val() || "0";
      const borderLeft = $('[data-depend-id="shopbody-border-left"]').val() || "0";

      newRules["border-style"] = borderStyle || "none";
      newRules["border-color"] = borderColor;
      newRules["border-width"] = `${borderTop}px ${borderRight}px ${borderBottom}px ${borderLeft}px`;
    }

    if (dataDependId && dataDependId.startsWith("shopbody-border")) {
      updateShopBodyBorderRule();
    }

    // Function to update margin or padding rules for Product Design
    function updateProductDesignBoxModelRule(size, property) {
      $selector = `#shopg_shop_layout_contents.width-${sizeMap[size]} .shopg_shop_layouts .product-design`;
      const top = $(`[data-depend-id="product-${property}-${size}-top"]`).val() || "0";
      const right = $(`[data-depend-id="product-${property}-${size}-right"]`).val() || "0";
      const bottom = $(`[data-depend-id="product-${property}-${size}-bottom"]`).val() || "0";
      const left = $(`[data-depend-id="product-${property}-${size}-left"]`).val() || "0";
      const unit = $(`[data-depend-id="product-${property}-${size}-unit"]:checked`).val() || "px";
      newRules[property] = `${top}${unit} ${right}${unit} ${bottom}${unit} ${left}${unit}`;
    }

    if (dataDependId && dataDependId.startsWith("product-margin-")) {
      const size = dataDependId.split("-")[2]; // Extract size (desktop, tablet, mobile)
      updateProductDesignBoxModelRule(size, "margin");
    } else if (dataDependId && dataDependId.startsWith("product-padding-")) {
      const size = dataDependId.split("-")[2]; // Extract size (desktop, tablet, mobile)
      updateProductDesignBoxModelRule(size, "padding");
    } else if (dataDependId && dataDependId.startsWith("product-margin-") && dataDependId.endsWith("-unit")) {
      const size = dataDependId.split("-")[2]; // Extract size (desktop, tablet, mobile)
      updateProductDesignBoxModelRule(size, "margin");
    } else if (dataDependId && dataDependId.startsWith("product-padding-") && dataDependId.endsWith("-unit")) {
      const size = dataDependId.split("-")[2]; // Extract size (desktop, tablet, mobile)
      updateProductDesignBoxModelRule(size, "padding");
    }

    // Function to update border rules for Product Design
    function updateProductDesignBorderRule() {
      $selector = "#shopg_shop_layout_contents .shopg_shop_layouts .product-design";
      const borderStyle = $('[data-depend-id="product-border-border-type"]').val() || "none";
      const borderColor = $('[data-depend-id="product-border-color-value"]').val() || "#000";
      const borderTop = $('[data-depend-id="product-border-top"]').val() || "0";
      const borderRight = $('[data-depend-id="product-border-right"]').val() || "0";
      const borderBottom = $('[data-depend-id="product-border-bottom"]').val() || "0";
      const borderLeft = $('[data-depend-id="product-border-left"]').val() || "0";

      newRules["border-style"] = borderStyle;
      newRules["border-color"] = borderColor;
      newRules["border-width"] = `${borderTop}px ${borderRight}px ${borderBottom}px ${borderLeft}px`;
    }

    if (dataDependId && dataDependId.startsWith("product-border")) {
      updateProductDesignBorderRule();
    }

    // Function to update border radius rules for Product Design
    function updateProductDesignBorderRadiusRule(size) {
      $selector = `#shopg_shop_layout_contents.width-${sizeMap[size]} .shopg_shop_layouts .product-design`;
      const borderRadiusTop = $(`[data-depend-id="product-radius-${size}-top"]`).val() || "0";
      const borderRadiusRight = $(`[data-depend-id="product-radius-${size}-right"]`).val() || "0";
      const borderRadiusBottom = $(`[data-depend-id="product-radius-${size}-bottom"]`).val() || "0";
      const borderRadiusLeft = $(`[data-depend-id="product-radius-${size}-left"]`).val() || "0";
      const borderRadiusUnit = $(`[data-depend-id="product-radius-${size}-unit"]:checked`).val() || "px";

      newRules[
        "border-radius"
      ] = `${borderRadiusTop}${borderRadiusUnit} ${borderRadiusRight}${borderRadiusUnit} ${borderRadiusBottom}${borderRadiusUnit} ${borderRadiusLeft}${borderRadiusUnit}`;
    }

    if (dataDependId && dataDependId.startsWith("product-radius")) {
      const size = dataDependId.split("-")[2]; // Extract size (desktop, tablet, mobile)
      updateProductDesignBorderRadiusRule(size);
    }

    if (dataDependId && dataDependId.startsWith("product-normal-background")) {
      $selector = "#shopg_shop_layout_contents .shopg_shop_layouts .product-design";
      const color = $(this).val();
      newRules["background-color"] = color || "transparent";
    } else if (dataDependId && dataDependId.startsWith("product-hover-background")) {
      $selector = "#shopg_shop_layout_contents .shopg_shop_layouts .product-design:hover";
      const color = $(this).val();
      newRules["background-color"] = color || "transparent";
    }

    if (dataDependId === `product-caption-background`) {
      $selector = `.product-design .product-caption`;
      newRules["background-color"] = $(this).val();
    }
    if (dataDependId === `product-caption-hover-background`) {
      $selector = `.product-design .product-caption:hover`;
      newRules["background-color"] = $(this).val();
    }
    const ProductContentTypes = ["title", "category", "price", "review"];

    ProductContentTypes.forEach((contentType) => {
      // Handle Font Color
      if (dataDependId && dataDependId.startsWith(`product-${contentType}-`)) {
        if (contentType === "price") {
          $selector = `.product-caption .product-${contentType}`;
        } else if (contentType === "review") {
          $selector = `.product-caption .ratings`;
        } else {
          $selector = `.product-caption .product-${contentType} a`;
        }

        const fontFamily = $(`[data-depend-id="product-${contentType}-font-family"]`).val() || "inherit";
        const fontWeight = $(`[data-depend-id="product-${contentType}-font-weights"]`).val() || "normal";
        const fontStyle = $(`[data-depend-id="product-${contentType}-font-styles"]`).val() || "normal";
        const textTransform = $(`[data-depend-id="product-${contentType}-text-transform"]`).val() || "none";
        const textDecoration = $(`[data-depend-id="product-${contentType}-text-decoration"]`).val() || "none";

        const fontSize = $(`[data-depend-id="product-${contentType}-font-size"]`).val() || "inherit";
        const fontSizeUnit = $(`[data-depend-id="product-${contentType}-font-size-unit"]:checked`).val() || "px";
        const lineHeight = $(`[data-depend-id="product-${contentType}-line-height"]`).val() || "inherit";
        const lineHeightUnit = $(`[data-depend-id="product-${contentType}-line-height-unit"]:checked`).val() || "px";
        const letterSpacing = $(`[data-depend-id="product-${contentType}-letter-spacing"]`).val() || "inherit";
        const letterSpacingUnit =
          $(`[data-depend-id="product-${contentType}-letter-spacing-unit"]:checked`).val() || "px";
        const wordSpacing = $(`[data-depend-id="product-${contentType}-word-spacing"]`).val() || "inherit";
        const wordSpacingUnit = $(`[data-depend-id="product-${contentType}-word-spacing-unit"]:checked`).val() || "px";

        const fontColor = $(`[data-depend-id="product-${contentType}-font-color"]`).val() || "#000";
        const fontHoverColor = $(`[data-depend-id="product-${contentType}-font-hover-color"]`).val() || "#000";
        const ratingsColor = $(`[data-depend-id="product-review-color"]`).val() || "##f9bd22";

        if (dataDependId === `product-${contentType}-font-hover-color`) {
          if (contentType === "price") {
            $selector = `.product-caption .product-${contentType}:hover`;
          } else {
            $selector = `.product-caption .product-${contentType} a:hover`;
          }

          newRules["color"] = fontHoverColor;
        } else if (dataDependId && dataDependId.startsWith(`product-${contentType}-`) && contentType !== "review") {
          newRules["font-family"] = fontFamily;
          newRules["font-weight"] = fontWeight;
          newRules["font-style"] = fontStyle;
          newRules["text-transform"] = textTransform;
          newRules["text-decoration"] = textDecoration;
          newRules["font-size"] = `${fontSize}${fontSizeUnit}`;
          newRules["line-height"] = `${lineHeight}${lineHeightUnit}`;
          newRules["letter-spacing"] = `${letterSpacing}${letterSpacingUnit}`;
          newRules["word-spacing"] = `${wordSpacing}${wordSpacingUnit}`;
          newRules["color"] = fontColor;
        } else if (dataDependId === `product-review-color`) {
          newRules["color"] = ratingsColor + " !important";
        }
      }
    });

    // Function to update image padding rules
    function updateImagePaddingRule(size) {
      $selector = `.product-thumb .pri-img`; // Assuming the image class in your product structure
      const top = $(`[data-depend-id="image-padding-${size}-top"]`).val() || "0";
      const right = $(`[data-depend-id="image-padding-${size}-right"]`).val() || "0";
      const bottom = $(`[data-depend-id="image-padding-${size}-bottom"]`).val() || "0";
      const left = $(`[data-depend-id="image-padding-${size}-left"]`).val() || "0";
      const unit = $(`[data-depend-id="image-padding-${size}-unit"]:checked`).val() || "px";
      newRules["padding"] = `${top}${unit} ${right}${unit} ${bottom}${unit} ${left}${unit}`;
    }

    if (dataDependId && dataDependId.startsWith("image-padding-")) {
      const size = dataDependId.split("-")[2]; // Extract size (desktop, tablet, mobile)
      updateImagePaddingRule(size);
    }

    if (dataDependId && dataDependId === "image-normal-background") {
      $selector = `.product-thumb img`;
      const color = $(this).val();
      newRules["background-color"] = color + " !important" || "transparent";
    } else if (dataDependId && dataDependId === "image-hover-background") {
      $selector = `.product-thumb img:hover`;
      const color = $(this).val();
      newRules["background-color"] = color + " !important" || "transparent";
    }

    // Function to update image link options
    function updateImageLinkOptions() {
      $(".product-thumb").each(function () {
        const productLink = $(this).find("a").first();

        if (!productLink.data("original-href")) {
          productLink.data("original-href", productLink.attr("href"));
        }

        const linkOption = $('[data-depend-id="image-link-option"]:checked').val();
        const openInNewTab = $('[data-depend-id="image-product-open-newtab"]').val();
        const originalHref = productLink.data("original-href");

        if (linkOption === "enable") {
          productLink.attr("href", originalHref);
          if (openInNewTab === "1") {
            productLink.attr("target", "_blank");
          } else {
            productLink.removeAttr("target");
          }
        } else if (linkOption === "disable") {
          productLink.removeAttr("href").removeAttr("target");
        }
      });
    }

    if (
      (dataDependId && dataDependId === "image-link-option") ||
      (dataDependId && dataDependId === "image-product-open-newtab")
    ) {
      updateImageLinkOptions();
    }

    // Function to update box model rules for Badges
    function updateBadgeBoxModelRule(badgeType, device, property) {
      $selector = `.product-badge .${badgeType}`;
      const top = $(`[data-depend-id="${badgeType}-${property}-${device}-top"]`).val() || "0";
      const right = $(`[data-depend-id="${badgeType}-${property}-${device}-right"]`).val() || "0";
      const bottom = $(`[data-depend-id="${badgeType}-${property}-${device}-bottom"]`).val() || "0";
      const left = $(`[data-depend-id="${badgeType}-${property}-${device}-left"]`).val() || "0";
      const unit = $(`[data-depend-id="${badgeType}-${property}-${device}-unit"]:checked`).val() || "px";
      newRules[property] = `${top}${unit} ${right}${unit} ${bottom}${unit} ${left}${unit}`;
    }

    // Function to update dimension rules for Badges
    function updateBadgeDimensionRule(badgeType, device, property) {
      $selector = `.product-badge .${badgeType}`;
      const value = $(`[data-depend-id="${badgeType}-${property}-${device}"]`).val() || "auto";
      const unit = $(`[data-depend-id="${badgeType}-${property}-${device}-unit"]:checked`).val() || "px";
      newRules[property] = `${value}${unit}`;
    }

    const badgeTypes = ["new-badge", "outofstock-badge", "featured-badge", "discount-badge"];
    const devices = ["desktop", "tablet", "mobile"];

    badgeTypes.forEach((badgeType) => {
      devices.forEach((device) => {
        // Handle Margin
        if (dataDependId && dataDependId.startsWith(`${badgeType}-margin-`) && dataDependId.includes(device)) {
          updateBadgeBoxModelRule(badgeType, device, "margin");
        }

        // Handle Padding
        else if (dataDependId && dataDependId.startsWith(`${badgeType}-padding-`) && dataDependId.includes(device)) {
          updateBadgeBoxModelRule(badgeType, device, "padding");
        }

        // Handle Width
        else if (dataDependId && dataDependId.startsWith(`${badgeType}-width-`) && dataDependId.includes(device)) {
          updateBadgeDimensionRule(badgeType, device, "width");
        }

        // Handle Height
        else if (dataDependId && dataDependId.startsWith(`${badgeType}-height-`) && dataDependId.includes(device)) {
          updateBadgeDimensionRule(badgeType, device, "height");
        }

        // Handle Font Color
        else if (dataDependId && dataDependId === `${badgeType}-color`) {
          $selector = `.product-badge .${badgeType}`;
          const fontColor = $(this).val() || "#000000";
          newRules["color"] = fontColor;
        }

        // Handle Background Color
        else if (dataDependId && dataDependId === `${badgeType}-bgcolor`) {
          $selector = `.product-badge .${badgeType}`;
          const bgColor = $(this).val() || "transparent";
          newRules["background-color"] = bgColor;
        }

        // Handle Border
        else if (
          dataDependId &&
          dataDependId.startsWith(`${badgeType}-border-`) &&
          !dataDependId.startsWith(`${badgeType}-border-radius-`)
        ) {
          $selector = `.product-badge .${badgeType}`;

          const borderStyle = $(`[data-depend-id="${badgeType}-border-border-type"]`).val() || "solid";
          const borderTop = $(`[data-depend-id="${badgeType}-border-top"]`).val() || "1";
          const borderRight = $(`[data-depend-id="${badgeType}-border-right"]`).val() || "1";
          const borderBottom = $(`[data-depend-id="${badgeType}-border-bottom"]`).val() || "1";
          const borderLeft = $(`[data-depend-id="${badgeType}-border-left"]`).val() || "1";

          const borderColor = $(`[data-depend-id="${badgeType}-border-color-value"]`).val() || "#000";
          const borderUnit = "px";

          newRules["border-top"] = `${borderTop}${borderUnit} ${borderStyle} ${borderColor}`;
          newRules["border-right"] = `${borderRight}${borderUnit} ${borderStyle} ${borderColor}`;
          newRules["border-bottom"] = `${borderBottom}${borderUnit} ${borderStyle} ${borderColor}`;
          newRules["border-left"] = `${borderLeft}${borderUnit} ${borderStyle} ${borderColor}`;
        } else if (
          dataDependId &&
          dataDependId.startsWith(`${badgeType}-border-radius-`) &&
          dataDependId.includes(device)
        ) {
          $selector = `.product-badge .${badgeType}`;
          const borderRadiusTop = $(`[data-depend-id="${badgeType}-border-radius-${device}-top"]`).val() || "0";
          const borderRadiusRight = $(`[data-depend-id="${badgeType}-border-radius-${device}-right"]`).val() || "0";
          const borderRadiusBottom = $(`[data-depend-id="${badgeType}-border-radius-${device}-bottom"]`).val() || "0";
          const borderRadiusLeft = $(`[data-depend-id="${badgeType}-border-radius-${device}-left"]`).val() || "0";
          const borderRadiusUnit =
            $(`[data-depend-id="${badgeType}-border-radius-${device}-unit"]:checked`).val() || "px";
          newRules[
            "border-radius"
          ] = `${borderRadiusTop}${borderRadiusUnit} ${borderRadiusRight}${borderRadiusUnit} ${borderRadiusBottom}${borderRadiusUnit} ${borderRadiusLeft}${borderRadiusUnit}`;
        }

        // Handle Position
        else if (dataDependId && dataDependId.startsWith(`${badgeType}-position`)) {
          $selector = `.product-badge`;
          newRules["text-align"] = $(this).val();
        }

        // Handle Typography
        else if (dataDependId && dataDependId.startsWith(`${badgeType}-typography-`)) {
          $selector = `.product-badge .${badgeType}`;
          const fontFamily = $(`[data-depend-id="${badgeType}-typography-font-family"]`).val() || "inherit";
          const fontWeight = $(`[data-depend-id="${badgeType}-typography-font-weights"]`).val() || "normal";
          const fontStyle = $(`[data-depend-id="${badgeType}-typography-font-styles"]`).val() || "normal";
          const textTransform = $(`[data-depend-id="${badgeType}-typography-text-transform"]`).val() || "none";
          const textDecoration = $(`[data-depend-id="${badgeType}-typography-text-decoration"]`).val() || "none";
          newRules["font-family"] = fontFamily;
          newRules["font-weight"] = fontWeight;
          newRules["font-style"] = fontStyle;
          newRules["text-transform"] = textTransform;
          newRules["text-decoration"] = textDecoration;
        }
      });
    });

    // Function to update box model properties (margin, padding) for Buttons
    function updateButtonBoxModelRule(buttonType, device, property) {
      $selector = `.product-design .${buttonType}`;
      const top = $(`[data-depend-id="${buttonType}-${property}-${device}-top"]`).val() || "0";
      const right = $(`[data-depend-id="${buttonType}-${property}-${device}-right"]`).val() || "0";
      const bottom = $(`[data-depend-id="${buttonType}-${property}-${device}-bottom"]`).val() || "0";
      const left = $(`[data-depend-id="${buttonType}-${property}-${device}-left"]`).val() || "0";
      const unit = $(`[data-depend-id="${buttonType}-${property}-${device}-unit"]:checked`).val() || "px";
      newRules[property] = `${top}${unit} ${right}${unit} ${bottom}${unit} ${left}${unit}`;
    }

    const IconbuttonTypes = ["add-to-cart", "wishlist", "compare", "quick-view"];

    IconbuttonTypes.forEach((buttonType) => {
      // Handle Font Color
      if (dataDependId && dataDependId === `${buttonType}-font-color`) {
        $selector = `.product-design .${buttonType}`;
        const colorValue = $(this).val() || "#000";
        newRules["color"] = colorValue;
      }

      if (dataDependId && dataDependId === `${buttonType}-hover-font-color`) {
        $selector = `.product-design .${buttonType}:hover`;
        const colorValue = $(this).val() || "#000";
        newRules["color"] = colorValue;
      }

      if (dataDependId && dataDependId === `${buttonType}-icon-color`) {
        $selector = `.product-design .${buttonType} i`;
        const colorValue = $(this).val() || "#000";
        newRules["color"] = colorValue;
      }

      if (dataDependId && dataDependId === `${buttonType}-hover-icon-color`) {
        $selector = `.product-design .${buttonType} i:hover`;
        const colorValue = $(this).val() || "#000";
        newRules["color"] = colorValue;
      }

      if (dataDependId && dataDependId === `${buttonType}-bg-color`) {
        $selector = `.product-design .${buttonType}`;
        const colorValue = $(this).val() || "#fff";
        newRules["background-color"] = colorValue;
      }
      if (dataDependId && dataDependId === `${buttonType}-hover-bg-color`) {
        $selector = `.product-design .${buttonType}:hover`;
        const colorValue = $(this).val() || "#fff";
        newRules["background-color"] = colorValue;
      }

      devices.forEach((device) => {
        // Handle Margin
        if (dataDependId && dataDependId.startsWith(`${buttonType}-margin-${device}`)) {
          updateButtonBoxModelRule(buttonType, device, "margin");
        }
        // Handle Padding
        if (dataDependId && dataDependId.startsWith(`${buttonType}-padding-${device}`)) {
          updateButtonBoxModelRule(buttonType, device, "padding");
        }
        // Handle Width
        if (dataDependId && dataDependId.startsWith(`${buttonType}-width-${device}`)) {
          //updateButtonDimensionRule(buttonType, device, "width");
          $selector = `.product-design .${buttonType}`;
          const width = $(`[data-depend-id="${buttonType}-width-${device}"]`).val() || "145px";
          const unit = $(`[data-depend-id="${buttonType}-width-${device}-unit"]:checked`).val() || "px";
          newRules["width"] = `${width}${unit}`;
        }
      });
    });

    if ($selector !== undefined) {
      updateShopLayoutCSS(componentId, $selector, newRules);
    }
  });

  $("#shopglayout-publishing-action").on("click", function (e) {
    e.preventDefault();
    $(".loader-container, .loader-overlay").show();

    var postid = $("#shopg_shop_layoutid").val();
    var layoutName = $("#layout_name").val();
    var layoutTemplate = $("#layout_template").val();

    var shopgOptionsSettings = {};

    function setNestedProperty(obj, keys, value) {
      var lastKey = keys.pop();
      var nestedObj = keys.reduce((o, key) => (o[key] = o[key] || {}), obj);
      nestedObj[lastKey] = value;
    }

    $("#shopg-shoplayout-settings :input").each(function () {
      var input = $(this);
      var name = input.attr("name");
      var value;

      // Handle radio buttons
      if (input.is(":radio")) {
        if (input.is(":checked")) {
          value = input.val();
        } else {
          return; // Skip unchecked radio buttons
        }
      } else {
        value = input.val();
      }

      if (name) {
        var keys = name.split("[").map((k) => k.replace("]", ""));
        setNestedProperty(shopgOptionsSettings, keys, value);
      }
    });

    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "save_shopg_shopdata",
        shopg_shop_layoutid: postid,
        layout_name: layoutName,
        layout_template: layoutTemplate,
        shopg_options_settings: shopgOptionsSettings,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          $(".loader-container, .loader-overlay").hide();
          $(".agl-field-preview").html(response.data.html);
        } else {
          console.log("Failed to save data: " + response.data);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        $(".loader-container, .loader-overlay").hide(); // Hide the loading icon
        console.log("AJAX request failed...");
        console.log("Status: " + textStatus);
        console.log("Error Thrown: " + errorThrown);
        console.log("Response Text: " + jqXHR.responseText);
        alert("Failed to save data. Check console for details.");
      },
    });
  });

  $(".shopglut_shop_reset #resetButton").on("click", function (e) {
    e.preventDefault();

    // Confirmation before reset
    if (confirm("Are you sure you want to reset the layout? This action cannot be undone.")) {
      $.ajax({
        url: ajax_data.ajax_url, // WordPress AJAX URL
        type: "POST",
        data: {
          action: "reset_shopglut_layouts", // Custom action name
          nonce: ajax_data.nonce, // Security nonce
        },
        success: function (response) {
          if (response.success) {
            alert("Settings have been reset successfully.");
            location.reload(); // Reload the page after reset
          } else {
            alert("Error: " + response.data);
          }
        },
        error: function () {
          alert("An error occurred during the reset process.");
        },
      });
    }
  });
});
