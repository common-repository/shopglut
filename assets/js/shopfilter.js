jQuery(document).ready(function ($) {
  if ($("#shopg_filter_options_settings .agl-field-accordion .agl-accordion-content").length) {
    $("#shopg_filter_options_settings .agl-field-accordion .agl-accordion-content.agl-accordion-open").removeClass(
      "agl-accordion-open"
    );
  }

  $('#shopg_filter_options_settings .agl-field-accordion input[data-depend-id="accordion-title"]').on(
    "input",
    function () {
      // Get the new value from the input field
      var newValue = $(this).val();

      // Find the closest h4.agl-accordion-title relative to this input
      $(this)
        .closest(".agl-field-accordion")
        .find(".agl-accordion-title")
        .html('<i class="agl-accordion-icon fas fa-angle-right"></i>' + newValue);
    }
  );

  $("#save-filter-settings").on("click", function (e) {
    e.preventDefault();
    $(".loader-container, .loader-overlay").show();

    var postid = $("#shopg_shop_filter_id").val();
    var filterName = $("#filter_name").val();

    var shopgFilterOptions = {};

    function setNestedProperty(obj, keys, value) {
      var lastKey = keys.pop();
      var nestedObj = keys.reduce((o, key) => (o[key] = o[key] || {}), obj);
      nestedObj[lastKey] = value;
    }

    $("#shopg_filter_options_settings :input").each(function () {
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
        setNestedProperty(shopgFilterOptions, keys, value);
      }
    });

    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "save_shopg_filterdata",
        shopg_shop_filter_id: postid,
        filter_name: filterName,
        shopg_filter_settings: shopgFilterOptions,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          $(".loader-container, .loader-overlay").hide();
          console.log("Save Data Successfully " + response.data);
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
});
