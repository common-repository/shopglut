jQuery(document).ready(function (jQuery) {
  function toggleFullscreen(isFullscreen) {
    // Toggle visibility of the WordPress admin menu
    jQuery("#adminmenuwrap, #adminmenuback").toggle(!isFullscreen);

    // Set margin-left of #wpcontent to 0px when the admin menu is hidden
    if (isFullscreen) {
      jQuery("#wpcontent").css({
        "margin-left": "0px",
        transition: "margin-left 0.3s ease",
      });
      // Change the text content to "Exit Fullscreen"
      jQuery("a#layout-switch-fullscreen").html('<i class="fa fa-compress"></i> Exit Fullscreen');
    } else {
      // Set margin-left back to its original value (160px by default)
      jQuery("#wpcontent").css({
        "margin-left": "160px",
        transition: "margin-left 0.05s ease",
      });
      jQuery("a#layout-switch-fullscreen").html('<i class="fa fa-expand"></i> Fullscreen Mode');
    }
  }

  // Check if body has the class 'shopglut-admin-shopglut-shop-editor' and trigger fullscreen mode
  if (jQuery("body").hasClass("shopglut-admin-shopglut-shop-editor")) {
    //toggleFullscreen(true);
  }

  jQuery("a#layout-switch-fullscreen").click(function (e) {
    e.preventDefault();
    let isFullscreen = jQuery("#adminmenuwrap").is(":hidden");
    toggleFullscreen(!isFullscreen);
  });
});
