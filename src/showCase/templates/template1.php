<?php
namespace Shopglut\showcase\templates;

if (!defined('ABSPATH')) {
	exit;
}
//https://debaco.myshopify.com/
class template1 {

	public function layout_render($template_data) {
		$product_id = get_the_ID();
		$product = wc_get_product($product_id);

		// New Product Badge
		$newness_days = 30;
		$product_created_date = $product->get_date_created()->getTimestamp();
		$current_date = current_time('timestamp');
		$is_new = ($product_created_date > strtotime('-' . $newness_days . ' days'));

		$is_out_of_stock = !$product->is_in_stock();

		$is_featured = $product->is_featured();

		$regular_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();
		$discount_percentage = 0;

		if ($regular_price && $sale_price) {
			$discount_percentage = round(((floatval($regular_price) - floatval($sale_price)) / floatval($regular_price)) * 100);
		}

		$currency_symbol = get_woocommerce_currency_symbol();

		$product_link = get_permalink($product_id);
		$product_name = get_the_title($product_id);
		$product_image = get_the_post_thumbnail_url($product_id, 'full');

		$second_image_id = null;

		$product_gallery_ids = $product->get_gallery_image_ids();

		if ($product_gallery_ids) {
			$second_image_id = $product_gallery_ids[0];
		}

		$second_image_url = wp_get_attachment_url($second_image_id);

		$color_variants = array(
			array('blue', '4_icon_crop_center.jpg', '4_270x270_crop_center.jpg'),
			array('green', '5_icon_crop_center.jpg', '5_270x270_crop_center.jpg'),
			array('gray', '1_icon_crop_center.jpg', '1_270x270_crop_center.jpg'),
			array('pink', '3_icon_crop_center.jpg', '3_270x270_crop_center.jpg'),
		);

		?>

<div class="product-design product-template1">

    <div class="product-item <?php echo esc_attr($product_id); ?>">
        <div class="product-thumb">
            <a href="<?php echo esc_url($product_link); ?>" >
                <img class="popup_cart_image pri-img" src="<?php echo esc_url($product_image); ?>" alt="">
                <?php if ($second_image_url): ?> <img class="popup_cart_image sec-img"
                    src="<?php echo esc_url($second_image_url); ?>" alt="Second Image"> <?php endif;?>
            </a>
            <div class="product-badge">
                <?php if ($is_new): ?>
                <span class="new-title product-label">New</span>
                <?php endif;?>

                <?php if ($is_out_of_stock): ?>
                <span class="out-of-stock-title product-label">Out of Stock</span>
                <?php endif;?>

                <?php if ($is_featured): ?>
                <span class="featured-title product-label">Featured</span>
                <?php endif;?>

                <?php if ($discount_percentage > 0): ?>
                <span class="discount product-label">-<?php echo esc_html($discount_percentage); ?>%</span>
                <?php endif;?>
            </div>
            <div class="button-group">
                <a title="" data-original-title="Add to Wishlist" class="wishlist"
                    href="<?php echo esc_url('/wishlist-url'); ?>" >
                    <i class="fa-regular fa-heart"></i>
                </a>
                <a href="javascript:void(0);" class="compare" data-pid="<?php echo esc_attr($product_id); ?>" title=""
                    data-original-title="Compare" ><i class="fa fa-refresh"></i></a>
                <a class="action-plus" title="Quick View" data-toggle="modal" data-target="#quickViewModal"
                    href="javascript:void(0);" onclick="quiqview('<?php echo esc_js($product_id); ?>')" ><i
                        class="fa fa-search"></i></a>
            </div>
            <div class="box-cart">
                <div class="product-cart-action">
                    <a href="javascript:void(0);"
                        onclick="Shopify.addItem(<?php echo esc_js($product_id); ?>, 1); return false;"
                        class="ajax-spin-cart btn btn-cart" >
                        <span>
                            <span class="cart-title"><i class="fa fa-shopping-cart"></i> Add to cart</span>
                            <span class="cart-loading"><i class="fa fa-spinner animated rotateIn infinite"></i>
                                Wait..</span>
                            <span class="cart-added"><i class="fa fa-check-square"></i> Added</span>
                            <span class="cart-unavailable"><i class="fa fa-shopping-cart"></i> Uavailable</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="product-caption">
            <div class="product-identity">
                <p class="manufacturer-name"><a href="<?php echo esc_url($product_link); ?>" >Nirob</a></p>
                <div class="ratings">
                    <span class="spr-badge" id="spr_badge_<?php echo esc_attr($product_id); ?>" data-rating="0.0"><span
                            class="spr-starrating spr-badge-starrating"><i class="spr-icon spr-icon-star-empty"
                                aria-hidden="true"></i><i class="spr-icon spr-icon-star-empty" aria-hidden="true"></i><i
                                class="spr-icon spr-icon-star-empty" aria-hidden="true"></i><i
                                class="spr-icon spr-icon-star-empty" aria-hidden="true"></i><i
                                class="spr-icon spr-icon-star-empty" aria-hidden="true"></i></span><span
                            class="spr-badge-caption">No reviews</span></span>
                </div>
            </div>

            <p class="product-title popup_cart_title">
                <a href="<?php echo esc_url($product_link); ?>" ><?php echo esc_html($product_name); ?></a>
            </p>
            <div class="price-box">

                <span class="product-price">
                    <span class="money" data-currency-usd="<?php echo esc_attr($regular_price); ?>">
                        <?php if ($regular_price): ?>
                        <?php echo esc_html($currency_symbol . $regular_price); ?><?php endif;?>
                    </span>
                </span>

                <?php if ($sale_price): ?>
                <span class="price-old">
                    <del>
                        <span class="money" data-currency-usd="<?php echo esc_attr($sale_price); ?>">
                            <?php echo esc_html($currency_symbol . $sale_price); ?>
                        </span>
                    </del>
                </span>
                <?php endif;?>
            </div>
        </div>
    </div>

</div>
<?php
}

}