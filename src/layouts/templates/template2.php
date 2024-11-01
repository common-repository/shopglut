<?php
namespace Shopglut\layouts\templates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class template2 {

    public function layout_render($r) {
        
       // print_r($r)
?>

<div class="product-design product-template1">
<div class="wpte-general-layout-row wpte-product-general-style-2">
    <div class="wpte-general-layout-image-area">
        <ul>
            <li class="featured product-label">Hot</li>
        </ul>
        <a href="http://shopglut.local/product/v-neck-t-shirt/" class="wpte-general-layout-product-img">
            <div class="wpte-general-layout-product-thumb">
                <img width="801" height="800" src="http://shopglut.local/wp-content/uploads/2024/01/vneck-tee-2.jpg" class="attachment- size-" alt="" loading="eager" />
            </div>
        </a>
        <div class="wpte-general-layout-product-cart-button-wrapper wpte-general-layout-icon-bottom">
            <div class="wpte-general-layout-cart-button wpte-general-layout-product-cart">
                <span class="wpte-product-cart-icon-render">
                    <span
                        class="wpte-product-add-cart-icon-text wpte-cart-icon-text"
                        id="wpte-cart-icons-text-2"
                        dataid="wpte-cart-icons-text-2"
                        view_cart="wpte-icon icon-cart-3"
                        add_cart_text="Add to Cart"
                        add_cart="wpte-icon icon-cart-2"
                        view_cart_text="View Cart"
                        groupde_icon="wpte-icon icon-setting-5"
                        groupde_text="Grouped"
                        external_icon="wpte-icon icon-external"
                        external_text="External"
                        variable_icon="wpte-icon icon-ok-5"
                        variable_text="Variable"
                    >
                        <a
                            href="http://shopglut.local/product/v-neck-t-shirt/"
                            data-quantity="1"
                            class="button product_type_variable add_to_cart_button"
                            data-product_id="120"
                            data-product_sku="woo-vneck-tee"
                            aria-label="Select options for “V-Neck T-Shirt”"
                            aria-describedby="This product has multiple variants. The options may be chosen on the product page"
                            rel="nofollow"
                        >
                            <i class="wpte-icon icon-ok-5"></i><span class="wpte-variable-text">Variable</span>
                        </a>
                    </span>
                </span>
            </div>
        </div>
    </div>
    <div class="wpte-general-layout-category-area">
        <a href="http://shopglut.local/product-category/clothing/tshirts/">Tshirts</a>
    </div>
    <div class="wpte-general-layout-title-area">
        <h2 class="wpte-general-layout-product-title">
            <a href="http://shopglut.local/product/v-neck-t-shirt/">V-Neck T-Shirt</a>
        </h2>
    </div>
    <div class="wpte-general-layout-rating-area">
        <i class="wpte-icons icon-star-0"></i><i class="wpte-icons icon-star-0"></i><i class="wpte-icons icon-star-0"></i><i class="wpte-icons icon-star-0"></i><i class="wpte-icons icon-star-0"></i>
    </div>
    <div class="wpte-general-layout-price-area">
        15.00$ – 20.00$
    </div>
</div>

</div>
<?php 
}

}
