<?php

namespace Shopglut\wishlist;

class dynamicStyle {

	public function dynamicCss() {
		// Retrieve the saved options for the wishlist notifications
		$options = get_option( 'agshopglut_wishlist_options' );

		$notificationType = isset( $options['wishlist-general-notification'] ) ? $options['wishlist-general-notification'] : 'notification-off';
		$notificationAddedBGColor = isset( $options['wishlist-notification-added-bg-color'] ) ? $options['wishlist-notification-added-bg-color'] : 'rgba(45,206,24,0.68)';
		$notificationRemovedBGColor = isset( $options['wishlist-notification-removed-bg-color'] ) ? $options['wishlist-notification-removed-bg-color'] : 'rgba(221,8,8,0.68)';
		$notificationFontColor = isset( $options['wishlist-notification-font-color'] ) ? $options['wishlist-notification-font-color'] : '#fff';
		$sidePosition = isset( $options['wishlist-side-notification-appear'] ) ? $options['wishlist-side-notification-appear'] : 'bottom-right';
		$sideEffect = isset( $options['wishlist-side-notification-effect'] ) ? $options['wishlist-side-notification-effect'] : 'fade-in-out';
		$popupEffect = isset( $options['wishlist-popup-notification-effect'] ) ? $options['wishlist-popup-notification-effect'] : 'fade-in-out';

		// Button styles for Wishlist
		$wishlistButtonWidth = isset( $options['wishlist-product-wishlist-button-width'] ) && is_array( $options['wishlist-product-wishlist-button-width'] ) ? $options['wishlist-product-wishlist-button-width'] : array( 'width' => '125', 'unit' => 'px' );
		$wishlistButtonColor = isset( $options['wishlist-product-button-color'] ) ? $options['wishlist-product-button-color'] : '#0073aa';
		$wishlistButtonFontColor = isset( $options['wishlist-product-button-font-color'] ) ? $options['wishlist-product-button-font-color'] : '#fff';
		$wishlistButtonPadding = isset( $options['wishlist-product-button-padding'] ) && is_array( $options['wishlist-product-button-padding'] ) ? $options['wishlist-product-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$wishlistButtonMargin = isset( $options['wishlist-product-button-margin'] ) && is_array( $options['wishlist-product-button-margin'] ) ? $options['wishlist-product-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );
		$wishlistIconColor = isset( $options['wishlist-product-icon-color'] ) ? $options['wishlist-product-icon-color'] : '#fff';

		// Button styles for Move to List
		$moveButtonColor = isset( $options['wishlist-product-move-button-color'] ) ? $options['wishlist-product-move-button-color'] : '#0073aa';
		$moveButtonFontColor = isset( $options['wishlist-product-move-button-font-color'] ) ? $options['wishlist-product-move-button-font-color'] : '#fff';
		$moveButtonPadding = isset( $options['wishlist-product-move-button-padding'] ) && is_array( $options['wishlist-product-move-button-padding'] ) ? $options['wishlist-product-move-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$moveButtonMargin = isset( $options['wishlist-product-move-button-margin'] ) && is_array( $options['wishlist-product-move-button-margin'] ) ? $options['wishlist-product-move-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );

		$wishlistLockedButtonColor = isset( $options['wishlist-locked-background'] ) ? $options['wishlist-locked-background'] : '#dd3333';
		$wishlistLockedButtonFontColor = isset( $options['wishlist-locked-font-color'] ) ? $options['wishlist-locked-font-color'] : '#fff';
		$wishlistLockedIconColor = isset( $options['wishlist-locked-icon-color'] ) ? $options['wishlist-locked-icon-color'] : '#fff';

		$tabColor = isset( $options['wishlist-page-list-tab-color'] ) ? $options['wishlist-page-list-tab-color'] : '#a3a3a3';
		$tabFontColor = isset( $options['wishlist-page-list-tab-font-color'] ) ? $options['wishlist-page-list-tab-font-color'] : '#fff';
		$activeTabColor = isset( $options['wishlist-page-list-active-tab-color'] ) ? $options['wishlist-page-list-active-tab-color'] : '#fff';
		$activeTabFontColor = isset( $options['wishlist-page-list-active-tab-font-color'] ) ? $options['wishlist-page-list-active-tab-font-color'] : '#000';

		// Table header colors
		$tableHeaderColor = isset( $options['wishlist-page-table-header-color'] ) ? $options['wishlist-page-table-header-color'] : '#a3a3a3';
		$tableHeaderFontColor = isset( $options['wishlist-page-table-head-font-color'] ) ? $options['wishlist-page-table-head-font-color'] : '#fff';

		$subscribeButtonColor = isset( $options['wishlist-page-subscription-btn-color'] ) ? $options['wishlist-page-subscription-btn-color'] : '#0073aa';
		$subscribeButtonFontColor = isset( $options['wishlist-page-subscription-btn-font-color'] ) ? $options['wishlist-page-subscription-btn-font-color'] : '#fff';
		// Table body colors
		$bodyColorChoice = isset( $options['wishlist-page-body-color-choice'] ) ? $options['wishlist-page-body-color-choice'] : 'body-same-color';
		$tableBodyColor = isset( $options['wishlist-page-body-color'] ) ? $options['wishlist-page-body-color'] : '#fff';
		$oddRowColor = isset( $options['wishlist-page-body-odd-color'] ) ? $options['wishlist-page-body-odd-color'] : '#fff';
		$evenRowColor = isset( $options['wishlist-page-body-even-color'] ) ? $options['wishlist-page-body-even-color'] : '#f1f1f1';
		$hoverColor = isset( $options['wishlist-page-body-hover-color'] ) ? $options['wishlist-page-body-hover-color'] : '#f1f1f1';
		$bodyFontColor = isset( $options['wishlist-page-table-body-font-color'] ) ? $options['wishlist-page-table-body-font-color'] : '#000';

		// Button styles
		$addToCartButtonColor = isset( $options['wishlist-page-addtocart-button-color'] ) ? $options['wishlist-page-addtocart-button-color'] : '#0073aa';
		$addToCartButtonFontColor = isset( $options['wishlist-page-addtocart-button-font-color'] ) ? $options['wishlist-page-addtocart-button-font-color'] : '#fff';
		$checkoutButtonColor = isset( $options['wishlist-page-checkout-button-color'] ) ? $options['wishlist-page-checkout-button-color'] : '#0073aa';
		$checkoutButtonFontColor = isset( $options['wishlist-page-checkout-button-font-color'] ) ? $options['wishlist-page-checkout-button-font-color'] : '#fff';

		$ShopButtonWidth = isset( $options['wishlist-shop-wishlist-button-width'] ) && is_array( $options['wishlist-shop-wishlist-button-width'] ) ? $options['wishlist-shop-wishlist-button-width'] : array( 'width' => '125', 'unit' => 'px' );
		$shopButtonColor = isset( $options['wishlist-shop-button-color'] ) ? $options['wishlist-shop-button-color'] : '#0073aa';
		$shopButtonFontColor = isset( $options['wishlist-shop-button-text-color'] ) ? $options['wishlist-shop-button-text-color'] : '#000';
		$shopButtonPadding = isset( $options['wishlist-shop-button-padding'] ) && is_array( $options['wishlist-shop-button-padding'] ) ? $options['wishlist-shop-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$shopButtonMargin = isset( $options['wishlist-shop-button-margin'] ) && is_array( $options['wishlist-shop-button-margin'] ) ? $options['wishlist-shop-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );
		$shopIconColor = isset( $options['wishlist-shop-icon-color'] ) ? $options['wishlist-shop-icon-color'] : '#000';

		$shopMoveButtonColor = isset( $options['wishlist-shop-move-button-color'] ) ? $options['wishlist-shop-move-button-color'] : '#0073aa';
		$shopMoveButtonFontColor = isset( $options['wishlist-shop-move-button-font-color'] ) ? $options['wishlist-shop-move-button-font-color'] : '#000';
		$shopMoveButtonPadding = isset( $options['wishlist-shop-move-button-padding'] ) && is_array( $options['wishlist-shop-move-button-padding'] ) ? $options['wishlist-shop-move-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$shopMoveButtonMargin = isset( $options['wishlist-shop-move-button-margin'] ) && is_array( $options['wishlist-shop-move-button-margin'] ) ? $options['wishlist-shop-move-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );

		// Archive Page Styles
		$archiveButtonWidth = isset( $options['wishlist-archive-wishlist-button-width'] ) && is_array( $options['wishlist-archive-wishlist-button-width'] ) ? $options['wishlist-archive-wishlist-button-width'] : array( 'width' => '125', 'unit' => 'px' );
		$archiveButtonColor = isset( $options['wishlist-archive-button-color'] ) ? $options['wishlist-archive-button-color'] : '#0073aa';
		$archiveButtonFontColor = isset( $options['wishlist-archive-button-text-color'] ) ? $options['wishlist-archive-button-text-color'] : '#000';
		$archiveButtonPadding = isset( $options['wishlist-archive-button-padding'] ) && is_array( $options['wishlist-archive-button-padding'] ) ? $options['wishlist-archive-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$archiveButtonMargin = isset( $options['wishlist-archive-button-margin'] ) && is_array( $options['wishlist-archive-button-margin'] ) ? $options['wishlist-archive-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );
		$archiveIconColor = isset( $options['wishlist-archive-icon-color'] ) ? $options['wishlist-archive-icon-color'] : '#000';

		$archiveMoveButtonColor = isset( $options['wishlist-archive-move-button-color'] ) ? $options['wishlist-archive-move-button-color'] : '#0073aa';
		$archiveMoveButtonFontColor = isset( $options['wishlist-archive-move-button-font-color'] ) ? $options['wishlist-archive-move-button-font-color'] : '#000';
		$archiveMoveButtonPadding = isset( $options['wishlist-archive-move-button-padding'] ) && is_array( $options['wishlist-archive-move-button-padding'] ) ? $options['wishlist-archive-move-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$archiveMoveButtonMargin = isset( $options['wishlist-archive-move-button-margin'] ) && is_array( $options['wishlist-archive-move-button-margin'] ) ? $options['wishlist-archive-move-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );

		$dynamic_css = "";

		$dynamic_css = "
        .shoglut-wishlist-tabs .tab-title {
            background-color: {$tabColor};
            color: {$tabFontColor};
        }
        .shoglut-wishlist-tabs .tab-title.active {
            background-color: {$activeTabColor};
            color: {$activeTabFontColor};
        }
        .shopglut-wishlist-table thead tr {
            background-color: {$tableHeaderColor};
            color: {$tableHeaderFontColor};
        }
        .shopglut-wishlist-table tbody tr {
            background-color: {$tableBodyColor};
            color: {$bodyFontColor};
        }
        .shopglut-wishlist-table tbody tr:hover {
            background-color: {$hoverColor};
        }
    ";

		// Conditional styling for odd/even rows
		if ( $bodyColorChoice === 'body-oddeven-color' ) {
			$dynamic_css .= "
            .shopglut-wishlist-table tbody tr:nth-child(odd) {
                background-color: {$oddRowColor};
            }
            .shopglut-wishlist-table tbody tr:nth-child(even) {
                background-color: {$evenRowColor};
            }
        ";
		}

		// Button styles for Add to Cart and Checkout
		$dynamic_css .= "
        .shopglut-wishlist-table .add-to-cart-btn {
            background-color: {$addToCartButtonColor};
            color: {$addToCartButtonFontColor};
        }
        .shopglut-wishlist-table .checkout-link {
            background-color: {$checkoutButtonColor};
            color: {$checkoutButtonFontColor};
             background-color: #0073aa;
			 transition: background-color 0.3s;
             font-weight: 400;
             padding: 10px 15px;
             border-radius: 4px;
			 text-decoration: none !important;
        }
    ";

		$dynamic_css .= "
    .shopglutw-subscribe-notification-btn {
        background-color: {$subscribeButtonColor};
        color: {$subscribeButtonFontColor};
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

	 .shopglutw-subscribe-notification-btn:hover {
        background-color: {$subscribeButtonColor};
        color: {$subscribeButtonFontColor};
    }
";

		$dynamic_css .= "
            .shopglut_wishlist.single-product .button {
                background-color: {$wishlistButtonColor};
                color: {$wishlistButtonFontColor};
                padding: {$wishlistButtonPadding['top']}{$wishlistButtonPadding['unit']} {$wishlistButtonPadding['right']}{$wishlistButtonPadding['unit']} {$wishlistButtonPadding['bottom']}{$wishlistButtonPadding['unit']} {$wishlistButtonPadding['left']}{$wishlistButtonPadding['unit']};
                margin: {$wishlistButtonMargin['top']}{$wishlistButtonMargin['unit']} {$wishlistButtonMargin['right']}{$wishlistButtonMargin['unit']} {$wishlistButtonMargin['bottom']}{$wishlistButtonMargin['unit']} {$wishlistButtonMargin['left']}{$wishlistButtonMargin['unit']};
                border: none;
                border-radius: 5px;
				width: {$wishlistButtonWidth['width']}{$wishlistButtonWidth['unit']};
            }

           .shopglut_wishlist.single-product .button i {
                color: {$wishlistIconColor};
            }
        ";

		$dynamic_css .= "
            .shopglut_wishlist.single-product .button.login-required {
                background-color: {$wishlistLockedButtonColor};
                color: {$wishlistLockedButtonFontColor};
				border: none;
                border-radius: 5px;
            }

           .shopglut_wishlist.single-product .button.login-required i {

                color: {$wishlistLockedIconColor};
            }
        ";

		$dynamic_css .= "
            .shopglut_wishlist_movelist.single-product .button.move_to_list {
                background-color: {$moveButtonColor};
                color: {$moveButtonFontColor};
                padding: {$moveButtonPadding['top']}{$moveButtonPadding['unit']} {$moveButtonPadding['right']}{$moveButtonPadding['unit']} {$moveButtonPadding['bottom']}{$moveButtonPadding['unit']} {$moveButtonPadding['left']}{$moveButtonPadding['unit']};
                margin: {$moveButtonMargin['top']}{$moveButtonMargin['unit']} {$moveButtonMargin['right']}{$moveButtonMargin['unit']} {$moveButtonMargin['bottom']}{$moveButtonMargin['unit']} {$moveButtonMargin['left']}{$moveButtonMargin['unit']};
                border: none;
                border-radius: 5px;
            }
        ";

		$dynamic_css .= "
    .shopglut_wishlist.shop-page .button {
        background-color: {$shopButtonColor};
        color: {$shopButtonFontColor};
        padding: {$shopButtonPadding['top']}{$shopButtonPadding['unit']} {$shopButtonPadding['right']}{$shopButtonPadding['unit']} {$shopButtonPadding['bottom']}{$shopButtonPadding['unit']} {$shopButtonPadding['left']}{$shopButtonPadding['unit']};
        margin: {$shopButtonMargin['top']}{$shopButtonMargin['unit']} {$shopButtonMargin['right']}{$shopButtonMargin['unit']} {$shopButtonMargin['bottom']}{$shopButtonMargin['unit']} {$shopButtonMargin['left']}{$shopButtonMargin['unit']};
        border: none;
        border-radius: 5px;
		width: {$ShopButtonWidth['width']}{$ShopButtonWidth['unit']};
    }
    .shopglut_wishlist.shop-page .button i {
        color: {$shopIconColor};
    }
    .shopglut_wishlist_movelist.shop-page .button.move_to_list {
        background-color: {$shopMoveButtonColor};
        color: {$shopMoveButtonFontColor};
        padding: {$shopMoveButtonPadding['top']}{$shopMoveButtonPadding['unit']} {$shopMoveButtonPadding['right']}{$shopMoveButtonPadding['unit']} {$shopMoveButtonPadding['bottom']}{$shopMoveButtonPadding['unit']} {$shopMoveButtonPadding['left']}{$shopMoveButtonPadding['unit']};
        margin: {$shopMoveButtonMargin['top']}{$shopMoveButtonMargin['unit']} {$shopMoveButtonMargin['right']}{$shopMoveButtonMargin['unit']} {$shopMoveButtonMargin['bottom']}{$shopMoveButtonMargin['unit']} {$shopMoveButtonMargin['left']}{$shopMoveButtonMargin['unit']};
        border: none;
        border-radius: 5px;
    }
";

		$dynamic_css .= "
    .shopglut_wishlist.archive-page .button {
        background-color: {$archiveButtonColor};
        color: {$archiveButtonFontColor};
        padding: {$archiveButtonPadding['top']}{$archiveButtonPadding['unit']} {$archiveButtonPadding['right']}{$archiveButtonPadding['unit']} {$archiveButtonPadding['bottom']}{$archiveButtonPadding['unit']} {$archiveButtonPadding['left']}{$archiveButtonPadding['unit']};
        margin: {$archiveButtonMargin['top']}{$archiveButtonMargin['unit']} {$archiveButtonMargin['right']}{$archiveButtonMargin['unit']} {$archiveButtonMargin['bottom']}{$archiveButtonMargin['unit']} {$archiveButtonMargin['left']}{$archiveButtonMargin['unit']};
        border: none;
        border-radius: 5px;
	    width: {$archiveButtonWidth['width']}{$archiveButtonWidth['unit']};

    }
    .shopglut_wishlist.archive-page .button i {
        color: {$archiveIconColor};
    }
    .shopglut_wishlist_movelist.archive-page .button.move_to_list {
        background-color: {$archiveMoveButtonColor};
        color: {$archiveMoveButtonFontColor};
        padding: {$archiveMoveButtonPadding['top']}{$archiveMoveButtonPadding['unit']} {$archiveMoveButtonPadding['right']}{$archiveMoveButtonPadding['unit']} {$archiveMoveButtonPadding['bottom']}{$archiveMoveButtonPadding['unit']} {$archiveMoveButtonPadding['left']}{$archiveMoveButtonPadding['unit']};
        margin: {$archiveMoveButtonMargin['top']}{$archiveMoveButtonMargin['unit']} {$archiveMoveButtonMargin['right']}{$archiveMoveButtonMargin['unit']} {$archiveMoveButtonMargin['bottom']}{$archiveMoveButtonMargin['unit']} {$archiveMoveButtonMargin['left']}{$archiveMoveButtonMargin['unit']};
        border: none;
        border-radius: 5px;
    }
";

		// Generate CSS for side notifications based on the selected effect
		if ( $notificationType === 'side-notification' ) {
			// Common CSS for the notification container
			$dynamic_css .= "

			.shog-wishlist-notification.side-notification{
			        position: fixed;
                    z-index: 1000;
					display: none;
		   }
				.shog-wishlist-notification.side-notification.added {

					padding: 15px;
					background: {$notificationAddedBGColor};
					color: {$notificationFontColor};
					border-radius: 4px;

				}
				.shog-wishlist-notification.side-notification.removed {
					padding: 15px;
					background: {$notificationRemovedBGColor};
					color: {$notificationFontColor};
					border-radius: 4px;

				}
			";

			// Set the position of the notification
			switch ( $sidePosition ) {
				case 'top-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 10px; left: 10px; }";
					break;
				case 'top-middle':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 10px; left: 50%; transform: translateX(-50%); }";
					break;
				case 'top-right':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 10px; right: 10px; }";
					break;
				case 'middle-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 50%; left: 10px; transform: translateY(-50%); }";
					break;
				case 'middle-right':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 50%; right: 10px; transform: translateY(-50%); }";
					break;
				case 'bottom-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { bottom: 10px; left: 10px; }";
					break;
				case 'bottom-middle':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { bottom: 10px; left: 50%; transform: translateX(-50%); }";
					break;
				case 'bottom-right':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { bottom: 10px; right: 10px; }";
					break;
			}

			// Additional styling based on the side effect
			switch ( $sideEffect ) {
				case 'slide-down-up':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { display: block;  }";
					break;
				case 'slide-from-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { display: block; left: -200px; }";
					break;
				case 'slide-from-right':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { display:block; right: -200px; }";
					break;
				case 'bounce':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { display: block; animation: bounce 0.5s ease-in-out; }";
					break;
				default: // 'fade-in-out' or any unspecified effect
					// No specific CSS needed as fade is handled by jQuery
					break;
			}
		}

		// Generate CSS for popup notifications based on the selected effect
		if ( $notificationType === 'popup-notification' ) {
			$dynamic_css .= "

			.shog-wishlist-notification.popup-notification{
			        position: fixed;
					top: 50%;
					left: 50%;
					z-index: 1000;
					display: none;
		       }
				.shog-wishlist-notification.popup-notification.added {

					transform: translate(-50%, -50%);
					padding: 15px;
					background: {$notificationAddedBGColor};
					color: {$notificationFontColor};
					border-radius: 4px;

				}
				.shog-wishlist-notification.popup-notification.removed {
					transform: translate(-50%, -50%);
					padding: 15px;
					background: {$notificationRemovedBGColor};
					color: {$notificationFontColor};
					border-radius: 4px;

				}
			";

			// Additional styling based on the popup effect
			switch ( $popupEffect ) {
				case 'zoom-in':
					$dynamic_css .= ".shog-wishlist-notification.popup-notification {
                   transform: scale(0) translate(0%, 0%);
                   transition: transform 0.5s ease-in-out;
	               transform-origin: center center; /* Scale from the center */
                   display: block;
	               top:38%;
	               left:40%; } ";
					break;
				case 'drop-in':
					$dynamic_css .= ".shog-wishlist-notification.popup-notification { top: 100px; display: block; }";
					break;
				case 'bounce':
					$dynamic_css .= ".shog-wishlist-notification.popup-notification { display:block; animation: bounce 0.5s ease-in-out; }";
					break;
				case 'shake':
					$dynamic_css .= ".shog-wishlist-notification.popup-notification { animation: shake 0.3s ease-in-out; }";
					break;
				default: // 'fade-in-out' or any unspecified effect
					// No specific CSS needed as fade is handled by jQuery
					break;
			}
		}

		return $dynamic_css;
	}
}