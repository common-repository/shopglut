<?php
namespace Shopglut\layouts\templates;

if (!defined('ABSPATH')) {
    exit;
}

class template5
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
  <div class="product-design product-layout5 woocommerce">
    <ul class="products">
  <li class="prodpage-style3 product type-product post-84 status-publish instock product_cat-medicines-treatments has-post-thumbnail sale shipping-taxable purchasable product-type-simple">
<div class="product-list-item text-custom-parent-hov prod-layout-style2">
<a href="https://demo.kallyas.net/medical/product/bayer-back-body-extra-strength-coated-caplets/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"> <div class="zn_badge_container">
<span class="zonsale zn_badge_sale">Sale! -23%</span> </div>
<span class="kw-prodimage"><img width="652" height="652" src="https://demo.kallyas.net/medical/wp-content/uploads/sites/18/2016/11/prod07.jpg" class="kw-prodimage-img" alt="" decoding="async" loading="lazy" srcset="https://demo.kallyas.net/medical/wp-content/uploads/sites/18/2016/11/prod07.jpg 652w, https://demo.kallyas.net/medical/wp-content/uploads/sites/18/2016/11/prod07-150x150.jpg 150w, https://demo.kallyas.net/medical/wp-content/uploads/sites/18/2016/11/prod07-300x300.jpg 300w, https://demo.kallyas.net/medical/wp-content/uploads/sites/18/2016/11/prod07-187x187.jpg 187w" sizes="(max-width: 652px) 100vw, 652px"></span> <div class="kw-details clearfix">
<h3 class="kw-details-title text-custom-child" itemprop="headline">Bayer Back &amp; Body Extra Strength Coated Caplets</h3>
<div class="kw-details-desc"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In interdum non orci in rhoncus.</p>
</div>
<span class="price"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>19.50</bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>14.99</bdi></span></ins></span>
<div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5</span></div> </div> 
</a><span class="kw-actions"><a href="?add-to-cart=84" data-quantity="1" class="actions-addtocart  product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="84" data-product_sku="" aria-label="Add “Bayer Back &amp; Body Extra Strength Coated Caplets” to your cart" aria-describedby="" rel="nofollow"><svg width="24px" height="27px" viewBox="0 0 24 27" class="svg-addCartIcon"> <path d="M3.0518948,6.073 L0.623,6.073 C0.4443913,6.073064 0.2744004,6.1497833 0.1561911,6.2836773 C0.0379818,6.4175713 -0.0170752,6.5957608 0.005,6.773 L1.264,16.567 L0.006,26.079 C-0.0180763,26.2562394 0.0363321,26.4351665 0.155,26.569 C0.2731623,26.703804 0.4437392,26.7810739 0.623,26.781 L17.984,26.781 C18.1637357,26.7812017 18.3347719,26.7036446 18.4530474,26.5683084 C18.5713228,26.4329722 18.6252731,26.2530893 18.601,26.075 L18.489,25.233 C18.4652742,25.0082534 18.3215123,24.814059 18.1134843,24.7257511 C17.9054562,24.6374431 17.6658978,24.6689179 17.4877412,24.8079655 C17.3095847,24.947013 17.2208653,25.1717524 17.256,25.395 L17.274,25.534 L1.332,25.534 L2.509,16.646 C2.5159976,16.5925614 2.5159976,16.5384386 2.509,16.485 L1.33,7.312 L2.853102,7.312 C2.818066,7.6633881 2.8,8.0215244 2.8,8.385 C2.8,8.7285211 3.0784789,9.007 3.422,9.007 C3.7655211,9.007 4.044,8.7285211 4.044,8.385 C4.044,8.0203636 4.0642631,7.6620439 4.103343,7.312 L14.5126059,7.312 C14.5517192,7.6620679 14.572,8.02039 14.572,8.385 C14.571734,8.5500461 14.6371805,8.7084088 14.7538859,8.8251141 C14.8705912,8.9418195 15.0289539,9.007266 15.194,9.007 C15.3590461,9.007266 15.5174088,8.9418195 15.6341141,8.8251141 C15.7508195,8.7084088 15.816266,8.5500461 15.816,8.385 C15.816,8.0215244 15.797934,7.6633881 15.762898,7.312 L17.273,7.312 L16.264,15.148 C16.2418906,15.3122742 16.2862643,15.4785783 16.3872727,15.6100018 C16.4882811,15.7414254 16.6375681,15.8270962 16.802,15.848 C16.9668262,15.8735529 17.1349267,15.8304976 17.2671747,15.7288556 C17.3994227,15.6272135 17.4842817,15.4758514 17.502,15.31 L18.602,6.773 C18.6234087,6.5958949 18.5681158,6.4180821 18.4500484,6.2843487 C18.3319809,6.1506154 18.1623929,6.0737087 17.984,6.073 L15.5641052,6.073 C14.7827358,2.5731843 12.2735317,0.006 9.308,0.006 C6.3424683,0.006 3.8332642,2.5731843 3.0518948,6.073 Z M4.3273522,6.073 L14.2884507,6.073 C13.5783375,3.269785 11.6141971,1.249 9.308,1.249 C7.0015895,1.249 5.0372989,3.2688966 4.3273522,6.073 Z" class="addtocart_bag" fill="#141414" fill-rule="evenodd"></path> <path d="M17.6892,25.874 C14.6135355,25.8713496 12.1220552,23.3764679 12.1236008,20.3008027 C12.1251465,17.2251374 14.6191332,14.7327611 17.6947988,14.7332021 C20.7704644,14.7336431 23.2637363,17.2267344 23.2644,20.3024 C23.2604263,23.3816113 20.7624135,25.8753272 17.6832,25.874 L17.6892,25.874 Z M17.6892,16.2248 C15.4358782,16.2248 13.6092,18.0514782 13.6092,20.3048 C13.6092,22.5581218 15.4358782,24.3848 17.6892,24.3848 C19.9425218,24.3848 21.7692,22.5581218 21.7692,20.3048 C21.7692012,19.2216763 21.3385217,18.1830021 20.5720751,17.4176809 C19.8056285,16.6523598 18.7663225,16.2232072 17.6832,16.2248 L17.6892,16.2248 Z" class="addtocart_circle" fill="#141414"></path> <path d="M18.4356,21.0488 L19.6356,21.0488 L19.632,21.0488 C20.0442253,21.0497941 20.3792059,20.7164253 20.3802,20.3042 C20.3811941,19.8919747 20.0478253,19.5569941 19.6356,19.556 L18.4356,19.556 L18.4356,18.356 C18.419528,17.9550837 18.0898383,17.6383459 17.6886,17.6383459 C17.2873617,17.6383459 16.957672,17.9550837 16.9416,18.356 L16.9416,19.556 L15.7392,19.556 C15.3269747,19.556 14.9928,19.8901747 14.9928,20.3024 C14.9928,20.7146253 15.3269747,21.0488 15.7392,21.0488 L16.9416,21.0488 L16.9416,22.2488 C16.9415997,22.4469657 17.0204028,22.6369975 17.1606396,22.7770092 C17.3008764,22.9170209 17.4910346,22.9955186 17.6892,22.9952 L17.6856,22.9952 C17.8842778,22.99648 18.0752408,22.9183686 18.2160678,22.7782176 C18.3568947,22.6380666 18.4359241,22.4474817 18.4356,22.2488 L18.4356,21.0488 Z" class="addtocart_plus" fill="#141414"></path> </svg></a><a class="actions-moreinfo" href="https://demo.kallyas.net/medical/product/bayer-back-body-extra-strength-coated-caplets/" title="MORE INFO"><svg width="50px" height="24px" class="svg-moreIcon"><circle cx="12" cy="12" r="2"></circle><circle cx="20" cy="12" r="2"></circle><circle cx="28" cy="12" r="2"></circle></svg></a></span> </div> 
</li>
</ul>


  </div>

       <?php
}

}
