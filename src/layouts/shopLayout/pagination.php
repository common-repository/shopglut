<?php

namespace Shopglut\layouts\shopLayout;

class pagination {

	public function __construct() {
		// Constructor logic if needed
	}

	public function render_pagination($option_to_show, $current_page, $total_pages, $page_id) {
		?>
<div class="shopglut-pagination-container">
    <?php if ($option_to_show === 'pagination-number') {?>
    <div id="numbering-pagination">
        <?php
// Get the permalink of the page where the shortcode is placed
			$current_page_permalink = get_permalink($page_id); // Use the page ID passed from AJAX

			// Ensure the pagination links include the page name dynamically
			echo paginate_links(array(
				'base' => trailingslashit($current_page_permalink) . 'page/%#%/', // Append page number to current page URL
				'format' => 'page/%#%/', // Ensure correct format
				'current' => max(1, $current_page),
				'total' => $total_pages,
				'prev_text' => __('« Previous', 'woocommerce-custom-ajax-pagination'),
				'next_text' => __('Next »', 'woocommerce-custom-ajax-pagination'),
				'type' => 'plain',
			));
			?>
    </div>
    <?php } elseif ($option_to_show === 'pagination-loadmore') {?>
    <div class="shopglut-pagination shopglut-pagination-load-more">
        <!-- Load More Button -->
        <button id="load-more-products" data-page="<?php echo $current_page; ?>">
            <?php _e('Load More', 'woocommerce-custom-ajax-pagination');?>
        </button>
    </div>
    <?php } elseif ($option_to_show === 'pagination-nextprev') {?>
    <div class="shopglut-pagination shopglut-pagination-prev-next">
        <!-- Next/Previous Links -->
        <div id="next-prev-pagination">
            <?php if ($current_page > 1): ?>
            <a href="#" id="prev-page"
                data-page="<?php echo $current_page - 1; ?>"><?php _e('« Previous', 'woocommerce-custom-ajax-pagination');?></a>
            <?php endif;?>
            <?php if ($current_page < $total_pages): ?>
            <a href="#" id="next-page"
                data-page="<?php echo $current_page + 1; ?>"><?php _e('Next »', 'woocommerce-custom-ajax-pagination');?></a>
            <?php endif;?>
        </div>
    </div>
    <?php }?>
</div>
<?php
}

}