jQuery(document).ready(function ($) {
  // Get the shortcode ID and current page ID
  var shortcode_id = $("#shopg_shop_layout_contents").data("shortcode-id");
  var current_page_id = $("#shopg_shop_layout_contents").data("page-id");

  // Function to load products via AJAX
  function loadProducts(page) {
    $.ajax({
      url: shopglut_ajax_object.ajax_url, // AJAX URL should still point to admin-ajax.php
      type: "POST",
      dataType: "json",
      data: {
        action: "retrive_shopg_shopdata",
        page: page,
        shopg_shop_layoutid: shortcode_id, // Pass the layout_id here
        page_id: current_page_id, // Pass the current page ID here
      },
      beforeSend: function () {
        $("#load-more-products").text("Loading..."); // Show loading state
      },
      success: function (response) {
        if (response.success) {
          // Replace existing content with new products
          $("#shopg_shop_layout_contents").html(response.data.html);

          // Replace the pagination section with updated pagination
          $("#numbering-pagination").html(response.pagination); // Update pagination links

          // Hide Load More if there are no more pages
          if (page >= response.data.max_pages) {
            $("#load-more-products").hide();
          } else {
            $("#load-more-products").show();
          }
        } else {
          $("#shopg_shop_layout_contents").html(response.data.html); // Display no products found
        }
      },
    });
  }

  // Handle numbered pagination clicks
  $(document).on("click", "#numbering-pagination a", function (e) {
    e.preventDefault();
    var href = $(this).attr("href");
    var page = parseInt(href.match(/\/page\/(\d+)/)[1]); // Extract the page number from the URL
    loadProducts(page);
  });

  // Handle load more button
  $("#load-more-products").on("click", function (e) {
    e.preventDefault();
    var page = $(this).data("page") + 1;
    loadProducts(page);
  });

  // Handle next/previous links
  $("#next-prev-pagination").on("click", "a", function (e) {
    e.preventDefault();
    var page = $(this).data("page");
    loadProducts(page);
  });
});
