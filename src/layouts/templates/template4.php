<?php
namespace Shopglut\layouts\templates;

if (!defined('ABSPATH')) {
    exit;
}

class template4
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
  <div class="product-design product-layout4">

  <div class="wd-product wd-with-labels wd-hover-alt wd-col product-grid-item product type-product post-1087 status-publish instock product_cat-furniture has-post-thumbnail sale featured shipping-taxable purchasable product-type-variable" data-loop="1" data-id="1087">
   <div class="product-wrapper">
      <div class="product-element-top wd-quick-shop">
         <a href="https://woodmart.xtemos.com/shop/furniture/eames-lounge-chair/" class="product-image-link">
            <div class="product-labels labels-rounded"><span class="onsale product-label">-50%</span><span class="featured product-label">Hot</span></div>
            <picture loading="lazy" decoding="async" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail">
               <source type="image/webp" srcset="https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8.jpg.webp 700w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-131x150.jpg.webp 131w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-263x300.jpg.webp 263w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-88x100.jpg.webp 88w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-430x490.jpg.webp 430w" sizes="(max-width: 430px) 100vw, 430px">
               <img loading="lazy" decoding="async" width="430" height="491" src="https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8.jpg" alt="" srcset="https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8.jpg 700w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-131x150.jpg 131w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-263x300.jpg 263w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-88x100.jpg 88w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-430x490.jpg 430w" sizes="(max-width: 430px) 100vw, 430px">
            </picture>
         </a>
         <div class="hover-img">
            <a href="https://woodmart.xtemos.com/shop/furniture/eames-lounge-chair/">
               <picture loading="lazy" decoding="async" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail">
                  <source type="image/webp" srcset="https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2.jpg.webp 700w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2-131x150.jpg.webp 131w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2-263x300.jpg.webp 263w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2-88x100.jpg.webp 88w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2-430x490.jpg.webp 430w" sizes="(max-width: 430px) 100vw, 430px">
                  <img loading="lazy" decoding="async" width="430" height="491" src="https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2.jpg" alt="" srcset="https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2.jpg 700w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2-131x150.jpg 131w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2-263x300.jpg 263w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2-88x100.jpg 88w, https://woodmart.b-cdn.net/wp-content/uploads/2016/09/product-furniture-8-2-430x490.jpg 430w" sizes="(max-width: 430px) 100vw, 430px">
               </picture>
            </a>
         </div>
         <div class="wd-buttons wd-pos-r-t">
            <div class="wd-compare-btn product-compare-button wd-action-btn wd-style-icon wd-compare-icon">
               <a href="https://woodmart.xtemos.com/compare/" data-id="1087" rel="nofollow" data-added-text="Compare products">
               <span>Compare</span>
               </a>
            </div>
            <div class="quick-view wd-action-btn wd-style-icon wd-quick-view-icon">
               <a href="https://woodmart.xtemos.com/shop/furniture/eames-lounge-chair/" class="open-quick-view quick-view-button" rel="nofollow" data-id="1087">Quick view</a>
            </div>
            <div class="wd-wishlist-btn wd-action-btn wd-style-icon wd-wishlist-icon">
               <a class="wd-tltp wd-tooltip-inited" href="https://woodmart.xtemos.com/wishlist/" data-key="ae5aae792c" data-product-id="1087" rel="nofollow" data-added-text="Browse Wishlist"><span class="wd-tooltip-label">
               Add to wishlist
               </span>
               <span>Add to wishlist</span>
               </a>
            </div>
         </div>
      </div>
      <div class="wd-product-header">
         <h3 class="wd-entities-title"><a href="https://woodmart.xtemos.com/shop/furniture/eames-lounge-chair/">Eames lounge chair</a></h3>
      </div>
      <div class="wd-product-cats">
         <a href="https://woodmart.xtemos.com/product-category/furniture/" rel="tag">Furniture</a>		
      </div>
      <div class="wrap-price">
         <div class="swap-wrapp">
            <div class="swap-elements">
               <span class="price"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>799.00</bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>399.00</bdi></span></ins></span>
               <div class="wd-add-btn">
                  <a href="https://woodmart.xtemos.com/shop/furniture/eames-lounge-chair/" data-quantity="1" class="button product_type_variable add_to_cart_button add-to-cart-loop" data-product_id="1087" data-product_sku="MNK-2045" aria-label="Select options for “Eames lounge chair”" aria-describedby="This product has multiple variants. The options may be chosen on the product page" rel="nofollow"><span>Add To Cart</span></a>					
               </div>
            </div>
         </div>
         <form class="variations_form cart wd-quick-shop-2 wd-clear-double wd-reset-side-lg wd-variations-inited" action="https://woodmart.xtemos.com/shop/furniture/eames-lounge-chair/" method="post" enctype="multipart/form-data" data-product_id="1087" data-product_variations="[{&quot;attributes&quot;:{&quot;attribute_pa_color&quot;:&quot;gray&quot;},&quot;availability_html&quot;:&quot;&quot;,&quot;backorders_allowed&quot;:false,&quot;dimensions&quot;:{&quot;length&quot;:&quot;&quot;,&quot;width&quot;:&quot;&quot;,&quot;height&quot;:&quot;&quot;},&quot;dimensions_html&quot;:&quot;N\/A&quot;,&quot;display_price&quot;:399,&quot;display_regular_price&quot;:799,&quot;image&quot;:{&quot;title&quot;:&quot;product-furniture-8-3&quot;,&quot;caption&quot;:&quot;&quot;,&quot;url&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3.jpg&quot;,&quot;alt&quot;:&quot;product-furniture-8-3&quot;,&quot;src&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3.jpg&quot;,&quot;srcset&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3.jpg 700w, https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3-131x150.jpg 131w, https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3-263x300.jpg 263w, https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3-88x100.jpg 88w, https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3-200x230.jpg 200w, https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3-430x490.jpg 430w&quot;,&quot;sizes&quot;:&quot;(max-width: 700px) 100vw, 700px&quot;,&quot;full_src&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3.jpg&quot;,&quot;full_src_w&quot;:700,&quot;full_src_h&quot;:800,&quot;gallery_thumbnail_src&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3-131x150.jpg&quot;,&quot;gallery_thumbnail_src_w&quot;:130,&quot;gallery_thumbnail_src_h&quot;:149,&quot;thumb_src&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-3.jpg&quot;,&quot;thumb_src_w&quot;:430,&quot;thumb_src_h&quot;:491,&quot;src_w&quot;:700,&quot;src_h&quot;:800},&quot;image_id&quot;:1093,&quot;is_downloadable&quot;:false,&quot;is_in_stock&quot;:true,&quot;is_purchasable&quot;:true,&quot;is_sold_individually&quot;:&quot;no&quot;,&quot;is_virtual&quot;:false,&quot;max_qty&quot;:&quot;&quot;,&quot;min_qty&quot;:1,&quot;price_html&quot;:&quot;&quot;,&quot;sku&quot;:&quot;MNK-2045&quot;,&quot;variation_description&quot;:&quot;&quot;,&quot;variation_id&quot;:1094,&quot;variation_is_active&quot;:true,&quot;variation_is_visible&quot;:true,&quot;weight&quot;:&quot;&quot;,&quot;weight_html&quot;:&quot;N\/A&quot;},{&quot;attributes&quot;:{&quot;attribute_pa_color&quot;:&quot;black&quot;},&quot;availability_html&quot;:&quot;&quot;,&quot;backorders_allowed&quot;:false,&quot;dimensions&quot;:{&quot;length&quot;:&quot;&quot;,&quot;width&quot;:&quot;&quot;,&quot;height&quot;:&quot;&quot;},&quot;dimensions_html&quot;:&quot;N\/A&quot;,&quot;display_price&quot;:399,&quot;display_regular_price&quot;:799,&quot;image&quot;:{&quot;title&quot;:&quot;product-furniture-8-2&quot;,&quot;caption&quot;:&quot;&quot;,&quot;url&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2.jpg&quot;,&quot;alt&quot;:&quot;product-furniture-8-2&quot;,&quot;src&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2.jpg&quot;,&quot;srcset&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2.jpg 700w, https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2-131x150.jpg 131w, https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2-263x300.jpg 263w, https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2-88x100.jpg 88w, https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2-200x230.jpg 200w, https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2-430x490.jpg 430w&quot;,&quot;sizes&quot;:&quot;(max-width: 700px) 100vw, 700px&quot;,&quot;full_src&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2.jpg&quot;,&quot;full_src_w&quot;:700,&quot;full_src_h&quot;:800,&quot;gallery_thumbnail_src&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2-131x150.jpg&quot;,&quot;gallery_thumbnail_src_w&quot;:130,&quot;gallery_thumbnail_src_h&quot;:149,&quot;thumb_src&quot;:&quot;https:\/\/woodmart.xtemos.com\/wp-content\/uploads\/2016\/09\/product-furniture-8-2.jpg&quot;,&quot;thumb_src_w&quot;:430,&quot;thumb_src_h&quot;:491,&quot;src_w&quot;:700,&quot;src_h&quot;:800},&quot;image_id&quot;:1092,&quot;is_downloadable&quot;:false,&quot;is_in_stock&quot;:true,&quot;is_purchasable&quot;:true,&quot;is_sold_individually&quot;:&quot;no&quot;,&quot;is_virtual&quot;:false,&quot;max_qty&quot;:&quot;&quot;,&quot;min_qty&quot;:1,&quot;price_html&quot;:&quot;&quot;,&quot;sku&quot;:&quot;MNK-2045&quot;,&quot;variation_description&quot;:&quot;&quot;,&quot;variation_id&quot;:1095,&quot;variation_is_active&quot;:true,&quot;variation_is_visible&quot;:true,&quot;weight&quot;:&quot;&quot;,&quot;weight_html&quot;:&quot;N\/A&quot;}]" current-image="">
            
            <div class="single_variation_wrap" style="display: block;">
               <div class="woocommerce-variation single_variation" style="display: none;"></div>
               <div class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-disabled">
                  <div class="quantity">
                     <input type="button" value="-" class="minus">
                     <label class="screen-reader-text" for="quantity_65aaa2e0acd66">Eames lounge chair quantity</label>
                     <input type="number" id="quantity_65aaa2e0acd66" class="input-text qty text" value="1" aria-label="Product quantity" min="1" max="" name="quantity" step="1" placeholder="" inputmode="numeric" autocomplete="off">
                     <input type="button" value="+" class="plus">
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
   
  </div>

       <?php
}

}
