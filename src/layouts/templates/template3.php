<?php
namespace Shopglut\layouts\templates;

if (!defined('ABSPATH')) {
    exit;
}

class template3
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
  <div class="product-design product-layout3">
   <div class="product mb-0">
      <div class="product-thumb-info border-0 mb-3">
         <div class="product-thumb-info-badges-wrapper"><span class="badge badge-ecommerce text-bg-success">NEW</span><span class="badge badge-ecommerce text-bg-danger">27% OFF</span>
         </div>
         <div class="addtocart-btn-wrapper">
            <a href="shop-cart.html" class="text-decoration-none addtocart-btn">
            <i class="fa fa-shopping-bag"></i>
            </a>
         </div>
         <a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
         QUICK VIEW
         </a>
         <a href="shop-product-sidebar-left.html">
            <div class="product-thumb-info-image product-thumb-info-image-effect">
               <img alt="" class="img-fluid" src="<?php echo esc_url($product_image); ?>">
               <img alt="" class="img-fluid" src="">
            </div>
         </a>
      </div>
      <div class="d-flex justify-content-between">
         <div>
            <a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">accessories</a>
            <h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary">Porto Headphone</a></h3>
         </div>
         <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
      </div>
      <div title="Rated 5 out of 5">
         <input type="text" class="d-none" value="5" title="" data-plugin-star-rating="" data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
      </div>
      <p class="price text-5 mb-3">
         <span class="sale text-color-dark font-weight-semi-bold">$199,00</span>
         <span class="amount">$99,00</span>
      </p>
   </div>
</div>

       <?php
}

}
