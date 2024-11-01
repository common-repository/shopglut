<?php
namespace Shopglut\layouts\templates;

if (!defined('ABSPATH')) {
    exit;
}

class template6
{

    public function layout_render($r)
    {

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
  <div class="product-design layout6">
  <div
    class="product-grid-item basel-hover-base product col-md-3 col-sm-4 col-xs-6 type-product post-19616 status-publish instock product_cat-shoes has-post-thumbnail featured shipping-taxable purchasable product-type-variable"
    data-loop="2"
    data-id="19616"
>
    <div class="product-element-top">
        <a href="https://demo.xtemos.com/basel/shop/shoes/basic-contrast-sneakers/">
            <div class="product-labels labels-rounded"><span class="new product-label">New</span></div>
            <img
                width="100%"
                height="348"
                src="https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12.jpg"
                class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                alt=""
                decoding="async"
                loading="lazy"
                srcset="
                    https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12.jpg          870w,
                    https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-235x300.jpg  235w,
                    https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-768x980.jpg  768w,
                    https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-803x1024.jpg 803w,
                    https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-266x340.jpg  266w,
                    https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-219x280.jpg  219w,
                    https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-263x336.jpg  263w,
                    https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-526x671.jpg  526w
                "
                sizes="(max-width: 100%px) 100vw, 100%px"
            />
        </a>
        <div class="hover-img">
            <a href="https://demo.xtemos.com/basel/shop/shoes/basic-contrast-sneakers/">
                <img
                    width="100%"
                    height="348"
                    src="https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-1.jpg"
                    class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                    alt=""
                    decoding="async"
                    loading="lazy"
                    srcset="
                        https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-1.jpg          870w,
                        https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-1-235x300.jpg  235w,
                        https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-1-768x980.jpg  768w,
                        https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-1-803x1024.jpg 803w,
                        https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-1-266x340.jpg  266w,
                        https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-1-219x280.jpg  219w,
                        https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-1-263x336.jpg  263w,
                        https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-1-526x671.jpg  526w
                    "
                    sizes="(max-width: 100%px) 100vw, 100%px"
                />
            </a>
        </div>
        <div class="hover-mask">
            <div class="basel-add-btn">
                <a
                    href="https://demo.xtemos.com/basel/shop/shoes/basic-contrast-sneakers/"
                    data-quantity="1"
                    class="button wp-element-button product_type_variable add_to_cart_button basel-tooltip basel-tooltip-inited"
                    data-product_id="19616"
                    data-product_sku=""
                    aria-label="Select options for “Basic contrast sneakers”"
                    rel="nofollow"
                >
                    <span class="basel-tooltip-label">Select options</span>Select options
                </a>
            </div>
            <div class="quick-view">
                <a href="https://demo.xtemos.com/basel/shop/shoes/basic-contrast-sneakers/" class="open-quick-view quick-view-button basel-tooltip basel-tooltip-inited" rel="nofollow" data-id="19616">
                    <span class="basel-tooltip-label">Quick View</span>Quick View
                </a>
            </div>
            <div class="basel-wishlist-btn">
                <a class="button basel-tooltip basel-tooltip-inited" rel="nofollow" href="https://demo.xtemos.com/basel/wishlist/" data-key="c16deb611f" data-product-id="19616" data-added-text="Browse Wishlist">
                    <span class="basel-tooltip-label">Add to wishlist</span>Add to wishlist
                </a>
            </div>
            <div class="basel-compare-btn product-compare-button">
                <a class="button basel-tooltip basel-tooltip-inited" rel="nofollow" href="https://demo.xtemos.com/basel/compare/" data-added-text="Compare products" data-id="19616"><span class="basel-tooltip-label">Compare</span>Compare</a>
            </div>
        </div>
    </div>
    <div class="swatches-on-grid">
        <div
            class="swatch-on-grid basel-tooltip swatch-has-image swatch-size-"
            style="background-color: #0c0c0c;"
            data-image-src="https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes-11.jpg"
            data-image-srcset="https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-11.jpg 870w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-11-235x300.jpg 235w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-11-768x980.jpg 768w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-11-803x1024.jpg 803w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-11-266x340.jpg 266w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-11-219x280.jpg 219w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-11-263x336.jpg 263w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-11-526x671.jpg 526w"
            data-image-sizes="(max-width: 870px) 100vw, 870px"
        >
            Black
        </div>
        <div
            class="swatch-on-grid basel-tooltip swatch-has-image swatch-size-"
            style="background-color: #aa6927;"
            data-image-src="https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes12-1.jpg"
            data-image-srcset="https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes12-1.jpg 870w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes12-1-235x300.jpg 235w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes12-1-768x980.jpg 768w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes12-1-803x1024.jpg 803w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes12-1-266x340.jpg 266w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes12-1-219x280.jpg 219w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes12-1-263x336.jpg 263w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes12-1-526x671.jpg 526w"
            data-image-sizes="(max-width: 870px) 100vw, 870px"
        >
            Brown
        </div>
        <div
            class="swatch-on-grid basel-tooltip swatch-has-image swatch-size- basel-tooltip-inited"
            style="background-color: #db4332;"
            data-image-src="https://basel-cec2.kxcdn.com/basel/wp-content/uploads/2015/10/shoes-20.jpg"
            data-image-srcset="https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-20.jpg 870w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-20-235x300.jpg 235w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-20-768x980.jpg 768w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-20-803x1024.jpg 803w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-20-266x340.jpg 266w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-20-219x280.jpg 219w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-20-263x336.jpg 263w, https://demo.xtemos.com/basel/wp-content/uploads/2015/10/shoes-20-526x671.jpg 526w"
            data-image-sizes="(max-width: 870px) 100vw, 870px"
        >
            <span class="basel-tooltip-label">Red</span>Red
        </div>
    </div>
    <h3 class="product-title"><a href="https://demo.xtemos.com/basel/shop/shoes/basic-contrast-sneakers/">Basic contrast sneakers</a></h3>

    <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
        <span style="width: 100%;">Rated <strong class="rating">5.00</strong> out of 5</span>
    </div>
    <span class="price">
        <span class="woocommerce-Price-amount amount">
            <bdi><span class="woocommerce-Price-currencySymbol">£</span>10.00</bdi>
        </span>
    </span>
</div>


  </div>

       <?php
}

}
