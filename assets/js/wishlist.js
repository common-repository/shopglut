jQuery(document).ready(function ($) {
  mergeGuestWishlist();
  initializeShopGluttabs();

  // Function to get or create the guest user ID
  function getOrCreateGuestId() {
    // Retrieve the guest_user_id from cookies
    const guestIdCookie = document.cookie.split("; ").find((row) => row.startsWith("shopglutw_guest_user_id="));

    if (guestIdCookie) {
      return guestIdCookie.split("=")[1];
    } else {
      return "";
    }
  }

  // Use event delegation for dynamically loaded elements
  $("body").on("click", ".shopglut-wishlist-table .add-to-cart-btn", function () {
    const productId = $(this).data("product-id");
    const quantity = $(this).closest("tr").find(".quantity").val() || 1; // Capture quantity
    var wishlist_type = $(this).data("wishlist-type");
    var list_name = $(this).data("list-name") || null;
    const shouldRemove = $(this).hasClass("remove-after-add");

    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "wishlist_add_to_cart",
        product_id: productId,
        quantity: quantity, // Pass quantity to server
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          showNotification(
            "<div class='success-added'><i class='fa fa-check-circle'></i> <span>Product added to Cart<span/></div>"
          );
          if (shouldRemove) {
            removeItemFromWishlist(productId, wishlist_type, list_name);
          }
        } else {
          alert(response.data || "Failed to add product to cart.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Add to Cart request failed:", xhr.responseText || error);
        alert("Failed to add product to cart. Please try again.");
      },
    });
  });

  // Use event delegation for dynamically loaded elements
  $("body").on("click", ".shopglut-wishlist-table .remove-btn", function () {
    var product_id = $(this).data("product-id");
    var wishlist_type = $(this).data("wishlist-type");
    var list_name = $(this).data("list-name") || null;

    // Show a confirmation dialog
    var confirmed = confirm("Are you sure you want to delete this item from your wishlist?");
    if (!confirmed) {
      return; // Exit if the user cancels the deletion
    }

    // Proceed with the AJAX request if confirmed
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "shopglut_remove_from_wishlist",
        product_id: product_id,
        wishlist_type: wishlist_type,
        list_name: list_name,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          // Show notification on successful removal
          showNotification(
            "<div class='wishlist-removed'><i class='fa fa-times-circle'></i> Product removed from Wishlist</div>"
          );

          // Reload the wishlist content after the item is removed
          loadWishlistContent();
        } else {
          // Display error message if the request failed
          alert(response.data || "Failed to remove product from wishlist.");
        }
      },
      error: function () {
        // Handle request failure
        alert("An error occurred. Please try again.");
      },
    });
  });

  $("body").on("click", ".shopglut-wishlist-table .remove-btn-account", function () {
    var product_id = $(this).data("product-id");
    var wishlist_type = $(this).data("wishlist-type");
    var list_name = $(this).data("list-name") || null;

    // Show a confirmation dialog
    var confirmed = confirm("Are you sure you want to delete this item from your wishlist?");
    if (!confirmed) {
      return; // Exit if the user cancels the deletion
    }

    // Proceed with the AJAX request if confirmed
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "shopglut_remove_from_wishlist",
        product_id: product_id,
        wishlist_type: wishlist_type,
        list_name: list_name,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          // Show notification on successful removal
          showNotification(
            "<div class='wishlist-removed'><i class='fa fa-times-circle'></i> Product removed from Wishlist</div>"
          );

          // Reload the wishlist content after the item is removed
          loadAccountWishlistContent();
        } else {
          // Display error message if the request failed
          alert(response.data || "Failed to remove product from wishlist.");
        }
      },
      error: function () {
        // Handle request failure
        alert("An error occurred. Please try again.");
      },
    });
  });

  // Function to remove item from wishlist
  function removeItemFromWishlist(productId, wishlist_type, list_name) {
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "shopglut_remove_from_wishlist",
        product_id: productId,
        wishlist_type: wishlist_type,
        list_name: list_name,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          showNotification(
            "<div class='wishlist-removed'><i class='fa fa-times-circle'></i> Product removed from wishlist</div>"
          );
          loadWishlistContent();
        } else {
          alert(response.data || "Failed to remove product from wishlist.");
        }
      },
      error: function () {
        alert("An error occurred. Please try again.");
      },
    });
  }

  // Function to reload the wishlist content
  function loadWishlistContent() {
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "load_wishlist_content",
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          // Replace the wishlist content with the updated content
          $(".shoglut-wishlist-tabs").html(response.data.content);
          initializeShopGluttabs();
        } else {
          // Display error message if loading wishlist content failed
          alert("Failed to reload wishlist content.");
        }
      },
      error: function () {
        alert("An error occurred while reloading the wishlist.");
      },
    });
  }

  function loadAccountWishlistContent() {
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "load_account_wishlist_content",
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          // Replace the wishlist content with the updated content
          $(".shoglut-wishlist-tabs").html(response.data);
          initializeShopGluttabs();
        } else {
          // Display error message if loading wishlist content failed
          alert("Failed to reload wishlist content.");
        }
      },
      error: function () {
        alert("An error occurred while reloading the wishlist.");
      },
    });
  }

  // Notification function to show removal success
  function showNotification(message) {
    $("#shopglut-wishlist-notification")
      .html(message) // Set the message content
      .fadeIn(300) // Show the container with fade in
      .delay(2000) // Keep it visible for 2 seconds
      .fadeOut(300); // Fade out the container
  }

  $(".agl-field-wishlistMail select#agl-wishmail-email-option").on("change", function () {
    if ($(this).val() === "yes") {
      $(".agl-wishmail-send-email-conditions").show();
    } else {
      $(".agl-wishmail-send-email-conditions").hide();
    }
  });

  function mergeGuestWishlist() {
    let guestId = getOrCreateGuestId();

    if (guestId && ajax_data.should_merge_wishlist) {
      $.ajax({
        url: ajax_data.ajax_url,
        type: "POST",
        data: {
          action: "merge_guest_wishlist",
          guest_id: guestId,
          nonce: ajax_data.nonce,
        },
        success: function (response) {
          console.log("Wishlist merged successfully ");
        },
        error: function (xhr, status, error) {
          console.error("Failed to merge wishlist: ", error);
        },
      });
    }
  }

  $(document).on(
    "click",
    ".shopglut_wishlist .not-shopgw-added, .shopglut_wishlist .shopgw-added , .shopglut_wishlist .already-added",
    function (e) {
      const $button = $(this);
      const isAdded = $button.hasClass("shopgw-added");
      let guestId = getOrCreateGuestId();

      e.preventDefault();

      $.ajax({
        url: ajax_data.ajax_url,
        type: "POST",
        data: {
          action: "toggle_wishlist",
          product_id: $button.data("product-id"),
          is_added: isAdded ? 1 : 0,
          shog_wishlist_guest_id: guestId,
          post_type: ajax_data.post_type, // send post type
        },
        success: function (response) {
          if (response.success) {
            const performToggle = response.data.perform_toggle;

            if (performToggle === true) {
              if (isAdded) {
                $button.removeClass("shopgw-added").addClass("not-shopgw-added");
                $button.find("i").attr("class", response.data.button_icon);
                $button.find(".button-text").text(response.data.button_text);
              } else {
                $button.removeClass("not-shopgw-added").addClass("shopgw-added");
                $button.find("i").attr("class", response.data.button_icon);
                $button.find(".button-text").text(response.data.button_text);
              }
            } else {
              $button.attr("href", response.data.href);
              $button.addClass(response.data.class ? response.data.class : "");
              $button.removeClass("not-shopgw-added");
              $button.find("i").attr("class", response.data.button_icon);
              $button.find(".button-text").text(response.data.button_text);
              $button.off("click");
            }

            // Show Notification with selected effect
            if (ajax_data.notification_type === "side-notification") {
              showSideNotification(
                response.data.notification_text,
                ajax_data.notification_position,
                ajax_data.side_notification_effect,
                isAdded
              );
            } else if (ajax_data.notification_type === "popup-notification") {
              showPopupNotification(response.data.notification_text, ajax_data.popup_notification_effect, isAdded);
            }
          }
        },
      });
    }
  );

  function showSideNotification(message, position, effect, isAdded) {
    const statusClass = isAdded ? "removed" : "added";

    const $notification = $("<div class='shog-wishlist-notification side-notification'></div>").text(message);
    $notification.addClass(position);
    $notification.addClass(statusClass);
    $("body").append($notification);

    // Apply the selected effect
    switch (effect) {
      case "slide-down-up":
        $notification
          .hide() // Ensure it's hidden before sliding down
          .slideDown()
          .delay(3000)
          .slideUp(function () {
            $(this).remove();
          });
        break;

      case "slide-from-left":
        $notification
          .css({left: "-100px", right: "auto"})
          .animate({left: "7px"}, 500)
          .delay(3000)
          .animate({left: "-210px"}, 500, function () {
            // Slide out to the left
            $(this).remove();
          });
        break;

      case "slide-from-right":
        $notification
          .css({right: "-100px", left: "auto"})
          .animate({right: "7px"}, 500)
          .delay(3000)
          .animate({right: "-210px"}, 500, function () {
            // Slide out to the right
            $(this).remove();
          });
        break;

      case "bounce":
        $notification
          .css({top: "-=30px", opacity: 0}) // Start above and hidden
          .animate({top: "+=30px", opacity: 1}, 300) // Bounce in effect
          .delay(3000)
          .animate({top: "-=10px"}, 100)
          .animate({top: "+=20px"}, 100)
          .animate({top: "-=10px"}, 100, function () {
            $(this).fadeOut().remove();
          });
        break;

      default:
        $notification
          .fadeIn()
          .delay(3000)
          .fadeOut(function () {
            $(this).remove();
          });
    }
  }

  function showPopupNotification(message, effect, isAdded) {
    const statusClass = isAdded ? "removed" : "added";

    const $popup = $("<div class='shog-wishlist-notification popup-notification'></div>").text(message);

    $popup.addClass(statusClass);

    $("body").append($popup);

    // Apply the selected effect
    switch (effect) {
      case "zoom-in":
        $popup
          .css({transform: "scale(0) translate(0%, 0%)", opacity: 1}) // Start small, centered and visible
          .appendTo("body") // Append to body if needed
          .delay(50) // Allow initial CSS effect to take place
          .queue(function (next) {
            $(this).css({transform: "scale(1) translate(0%, 0%)"}); // Scale to full size, keeping centered
            next();
          })
          .delay(3000)
          .queue(function (next) {
            $(this).css({transform: "scale(0) translate(0%, 0%)"}); // Scale down to center before fading out
            next();
          })
          .delay(500) // Allow time for scale down effect
          .fadeOut(function () {
            $(this).remove();
          });
        break;

      case "bounce":
        $popup
          .css({top: "-=50%", opacity: 0}) // Start above and hidden
          .animate({top: "+=50%", opacity: 1}, 300) // Bounce in effect
          .delay(3000)
          .animate({top: "-=10px"}, 100)
          .animate({top: "+=20px"}, 100)
          .animate({top: "-=10px"}, 100, function () {
            $(this).fadeOut().remove();
          });
        break;

      case "shake":
        $popup
          .css({display: "block", opacity: 1}) // Ensure it's visible
          // Initial shake effect on appearance
          .animate({left: "-=10px"}, 50)
          .animate({left: "+=20px"}, 50)
          .animate({left: "+=20px"}, 50)
          .animate({left: "-=10px"}, 50)
          .delay(3000)
          // Secondary shake effect before removal
          .animate({left: "-=10px"}, 100)
          .animate({left: "+=20px"}, 100)
          .animate({left: "-=10px"}, 100, function () {
            $(this).fadeOut().remove();
          });
        break;

      case "drop-in":
        $popup
          .css({top: "-100px"})
          .animate({top: "50%"}, 500)
          .delay(3000)
          .animate({top: "-50%"}, 500)
          .fadeOut(function () {
            $(this).remove();
          });
        break;
      default:
        $popup
          .fadeIn()
          .delay(3000)
          .fadeOut(function () {
            $(this).remove();
          });
    }
  }

  // Open modal with zoom effect
  $(".shopglut_wishlist_movelist .move_to_list").on("click", function (e) {
    e.preventDefault();

    const productId = $(this).data("product-id");

    // Store the product ID and show the modal with zoom-in effect
    $("#shopgMovetoListModal").data("product-id", productId).fadeIn();

    // Apply zoom-in animation
    setTimeout(() => {
      $("#shopgMovetoListModal").css({opacity: 1});
    }, 50);

    // Load the existing lists for the current user
    loadWishlistCheckboxes();
  });

  // Create new list and add it instantly to the list display without reloading
  $("#shopgMovetoListContainer #createListBtn").on("click", function () {
    const listName = $("#listName").val().trim();
    if (listName) {
      $.ajax({
        url: ajax_data.ajax_url,
        type: "POST",
        data: {
          action: "create_wishlist_sublist",
          list_name: listName,
          product_id: $("#shopgMovetoListModal").data("product-id"),
        },
        success: function (response) {
          if (response.success) {
            // Instantly add the new list to the checkbox area with the delete icon
            $("#checkboxList").append(`
            <label>
              <input type="checkbox" value="${listName}"> ${listName}
              <span class="delete-list" data-list-name="${listName}" style="cursor: pointer; color: red; background: white; border: 1px solid red; border-radius: 50%; padding: 2px 6px;">×</span>
            </label>
          `);
            $("#listName").val(""); // Clear input
          }
        },
      });
    }
  });

  // Load checkboxes dynamically with a delete icon and check those that contain the current product ID
  function loadWishlistCheckboxes() {
    const productId = $("#shopgMovetoListModal").data("product-id");

    $.ajax({
      url: ajax_data.ajax_url,
      type: "POST",
      data: {
        action: "get_wishlist_sublists",
      },
      success: function (response) {
        if (response.success) {
          const checkboxList = $("#checkboxList").empty();
          const sublists = response.data;

          // Iterate over each list to create checkboxes with a delete icon
          $.each(sublists, function (listName, products) {
            // Ensure products is an array; if not, try to parse it
            if (typeof products === "string") {
              try {
                products = JSON.parse(products);
              } catch (e) {
                console.error("Failed to parse products as JSON:", e);
                products = [];
              }
            }

            if (Array.isArray(products)) {
              // Check if the product is in the list
              const isChecked = products.includes(productId);
              const checkedAttr = isChecked ? "checked" : "";

              // Append checkbox with delete icon
              checkboxList.append(`
              <label>
                <input type="checkbox" value="${listName}" ${checkedAttr}> ${listName}
                <span class="delete-list" data-list-name="${listName}">×</span>
              </label>
            `);
            } else {
              console.warn(`Products for list "${listName}" is not an array.`);
            }
          });
        }
      },
    });
  }

  // Delete wishlist sublist with confirmation
  $("#shopgMovetoListContainer #checkboxList").on("click", ".delete-list", function () {
    const listName = $(this).data("list-name");

    if (confirm(`Are you sure you want to delete the list "${listName}"?`)) {
      $.ajax({
        url: ajax_data.ajax_url,
        type: "POST",
        data: {
          action: "delete_wishlist_sublist",
          list_name: listName,
        },
        success: function (response) {
          if (response.success) {
            // Remove the list item from the DOM
            $(`span.delete-list[data-list-name="${listName}"]`).closest("label").remove();
          } else {
            alert("An error occurred while deleting the list. Please try again.");
          }
        },
      });
    }
  });

  // Add product to selected wishlist sublists
  $("#shopgMovetoListContainer #addToListBtn").on("click", function () {
    const productId = $("#shopgMovetoListModal").data("product-id");
    const allLists = $("#checkboxList input")
      .map(function () {
        return $(this).val();
      })
      .get();

    const checkedLists = $("#checkboxList input:checked")
      .map(function () {
        return $(this).val();
      })
      .get();

    const uncheckedLists = allLists.filter((list) => !checkedLists.includes(list));

    $.ajax({
      url: ajax_data.ajax_url,
      type: "POST",
      data: {
        action: "add_product_to_wishlist_sublist",
        checked_lists: checkedLists,
        unchecked_lists: uncheckedLists,
        product_id: productId,
      },
      success: function (response) {
        if (response.success) {
          // Display success message
          $("#successMessage").fadeIn();

          // Optionally hide the message after a few seconds
          setTimeout(function () {
            $("#successMessage").fadeOut();
          }, 3000);
        }
      },
    });
  });

  // Close modal when overlay is clicked
  $("#shopgMovetoListModalOverlay").on("click", function () {
    $("#shopgMovetoListModal, #shopgMovetoListModalOverlay").hide();
  });

  function initializeShopGluttabs() {
    // Hide all tab content except the first one
    $(".shoglut-wishlist-tabs .tab-content").hide();
    $(".shoglut-wishlist-tabs .tab-content:first").show();
    $(".shoglut-wishlist-tabs .tab-titles li:first").addClass("active");

    // Tab click event
    $(".shoglut-wishlist-tabs .tab-titles li").click(function () {
      var tab_id = $(this).data("tab");

      // Remove active class from all tabs and hide all content
      $(".shoglut-wishlist-tabs .tab-titles li").removeClass("active");
      $(".shoglut-wishlist-tabs .tab-content").hide();

      // Add active class to the clicked tab and show corresponding content
      $(this).addClass("active");
      $("#" + tab_id).show();
    });
  }

  // Event listener for notification button clicks
  $("body").on("click", ".shopglutw-subscribe-notification-btn", function () {
    var wishlistType = $(this).data("wishlist-type");
    var listName = $(this).data("list-name") || ""; // Only for sublists

    // Set hidden fields for wishlist type and list name
    $("#shopglutw-subscribe-notification-modal").find("input[name='wishlist_type']").val(wishlistType);
    $("#shopglutw-subscribe-notification-modal").find("input[name='list_name']").val(listName);

    // Load saved notifications and check appropriate boxes
    loadNotifications(wishlistType, listName);

    // Show the modal with a fade-in effect
    $("#shopglutw-subscribe-notification-modal").fadeIn();
  });

  // Function to load notifications from the server
  function loadNotifications(wishlistType, listName) {
    var userId = $("#notification-form input[name='user_id']").val();
    var ajaxUrl = ajax_data.ajax_url;

    $.ajax({
      url: ajaxUrl,
      method: "POST",
      data: {
        action: "get_user_notifications",
        user_id: userId,
        wishlist_type: wishlistType,
        list_name: listName,
      },
      success: function (response) {
        // Clear previous selection
        $("#notification-form input[type='checkbox']").prop("checked", false);

        // Check if response is successful and notifications is an array
        if (response.success && Array.isArray(response.data.notifications)) {
          response.data.notifications.forEach(function (notification) {
            $("#notification-form input[value='" + notification + "']").prop("checked", true);
          });
        } else {
          console.error("Unexpected response format or no notifications found:", response);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX request failed:", textStatus, errorThrown);
      },
    });
  }

  // Hide modal on close button click or outside the modal content
  $("body").on("click", function (event) {
    if ($(event.target).is("#shopglutw-subscribe-notification-modal .close-modal")) {
      $("#shopglutw-subscribe-notification-modal").fadeOut(); // Hide the modal with a fade-out effect
    }
  });

  // Prevent click inside modal-content from closing the modal
  $("#shopglutw-subscribe-notification-modal .modal-content").on("click", function (event) {
    event.stopPropagation();
  });

  // Hide modal on close button click
  $("#shopglutw-subscribe-notification-modal .close-modal").on("click", function () {
    $("#shopglutw-subscribe-notification-modal").fadeOut();
  });

  // Handle form submission
  $("#shopglutw-subscribe-notification-modal #notification-form").on("submit", function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "save_list_notification_preferences",
        form_data: formData,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          alert("Notification preferences saved successfully!");
          $("#shopglutw-subscribe-notification-modal").hide();
        } else {
          alert("Failed to save preferences.");
        }
      },
    });
  });

  // Use event delegation on a static parent element
  $(document).on("click", ".shopglut-wishlist-table .checkout-link, .shopglut_wishlist .checkout-link", function (e) {
    e.preventDefault();
    var productID = $(this).data("product-id");
    const quantity = $(this).closest("tr").find(".quantity").val() || 1; // Capture quantity

    $.ajax({
      url: ajax_data.ajax_url, // WooCommerce AJAX URL
      type: "POST",
      data: {
        action: "shopglut_add_to_cart_and_checkout",
        product_id: productID,
        quantity: quantity,
      },
      success: function (response) {
        if (response.success) {
          // Redirect to the checkout page using the returned URL
          window.location.href = response.data.redirect_url;
        }
      },
    });
  });

  $(".agl-field-wishlistMail #copy-cron-url").on("click", function () {
    // Select the cron URL text
    var cronUrlInput = $("#shopglut-wishlist-cron-url");
    cronUrlInput.select();

    // Copy the text to clipboard
    document.execCommand("copy");
  });
});
