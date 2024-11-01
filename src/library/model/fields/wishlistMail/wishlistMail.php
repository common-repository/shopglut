<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'AGSHOPGLUT_wishlistMail' ) ) {
	class AGSHOPGLUT_wishlistMail extends AGSHOPGLUTP {

		/**
		 * Value
		 *
		 * @var array
		 */
		public $value = array();

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			$this->cron_token = get_option( 'shopglut_wishlist_cron_token', '' );
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		// Render the form and table
		public function render() {
			echo $this->field_before();

			$args = wp_parse_args(
				$this->field,
				array(
					'from_name' => '',
					'from_email' => '',
					'email_body' => '',
					'send_email' => '',
					'time_value' => '',
					'time_unit' => '',
				)
			);

			$default_value = array(
				'from_name' => '',
				'from_email' => '',
				'email_body' => '',
				'send_email' => '',
				'time_value' => '',
				'time_unit' => '',
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;
			$this->value = wp_parse_args( $this->value, $default_value );

			// Check if 'pro' is active
			$is_pro = ! empty( $this->field['pro'] ) ? true : false;
			$pro_text = __( 'Unlock the Pro version', 'shopglut' );
			?>

<div class="agl-fieldset-content">


    <span class="agl--label"><label
            for="send_email"><?php esc_html_e( 'Send Email Option', 'shopglut' ); ?></label></span>
    <select id="agl-wishmail-email-option" name="<?php echo esc_attr( $this->field_name( '[send_email]' ) ); ?>">
        <option value="no" <?php selected( $this->value['send_email'], 'no' ); ?>>
            <?php esc_html_e( 'Do Not Send Email Automatically', 'shopglut' ); ?>
        </option>
        <option value="yes" <?php selected( $this->value['send_email'], 'yes' ); ?>>
            <?php esc_html_e( 'Send Email Automatically', 'shopglut' ); ?>
        </option>
    </select>

    <div class="agl-wishmail-send-email-conditions"
        style="display: <?php echo ( $this->value['send_email'] == 'yes' ) ? 'block' : 'none'; ?>;">
        <span class="agl--label"><label
                for="time_value"><?php esc_html_e( 'Send Email Time after Added to Wishlist', 'shopglut' ); ?></label></span>
        <?php if ( $is_pro ) : ?>
        <a href="<?php echo esc_url( $this->field['pro'] ); ?>" target="_blank"
            class="agl--pro-link"><?php echo esc_html( $pro_text ); ?></a>
        <?php endif; ?>
        <!-- Disable Time fields when Pro version is active -->
        <input type="number" name="<?php echo esc_attr( $this->field_name( '[time_value]' ) ); ?>"
            value="<?php echo esc_attr( $this->value['time_value'] ); ?>"
            <?php echo ( $is_pro ) ? 'disabled' : ''; ?> />
        <select name="<?php echo esc_attr( $this->field_name( '[time_unit]' ) ); ?>"
            <?php echo ( $is_pro ) ? 'disabled' : ''; ?>>
            <option value="minute" <?php selected( $this->value['time_unit'], 'minute' ); ?>>
                <?php esc_html_e( 'Minutes', 'shopglut' ); ?>
            </option>
            <option value="hour" <?php selected( $this->value['time_unit'], 'hour' ); ?>>
                <?php esc_html_e( 'Hours', 'shopglut' ); ?>
            </option>
            <option value="day" <?php selected( $this->value['time_unit'], 'day' ); ?>>
                <?php esc_html_e( 'Days', 'shopglut' ); ?>
            </option>
        </select>

        <!-- Display empty cron URL if Pro version is active -->
        <?php $cron_url = ( $is_pro ) ? '' : site_url( '/send-shopglut-wishlist-emails/?cronkey=' . $this->cron_token ); ?>
        <div class="shopglut-wishlist-mail-cron-url">
            <label for="cron-url"><?php esc_html_e( 'Cron URL', 'shopglut' ); ?></label>
            <input type="text" id="shopglut-wishlist-cron-url" value="<?php echo esc_url( $cron_url ); ?>" readonly>
            <div id="copy-cron-url">
                <i class="fa fa-copy"></i>
            </div>
            <p><?php esc_html_e( 'You can configure a cron job from your hosting control panel using the URL above for reliable cron execution.', 'shopglut' ); ?>
            </p>
            <?php if ( $is_pro ) : ?>
            <a href="<?php echo esc_url( $this->field['pro'] ); ?>" target="_blank"
                class="agl--pro-link"><?php echo esc_html( $pro_text ); ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="agl-user-wishlist-table">
    <h3><?php esc_html_e( 'Wishlist Users', 'shopglut' ); ?></h3>
    <table class="widefat">
        <thead>
            <tr>
                <th><?php esc_html_e( 'User Name', 'shopglut' ); ?></th>
                <th><?php esc_html_e( 'User Email', 'shopglut' ); ?></th>
                <th><?php esc_html_e( 'Products Added to Wishlist', 'shopglut' ); ?></th>
                <th><?php esc_html_e( 'Latest Added Product Time', 'shopglut' ); ?></th>
                <th><?php esc_html_e( 'Send Email', 'shopglut' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
						global $wpdb;
						$table_name = $wpdb->prefix . 'shopglut_wishlist';
						$wishlist_users = $wpdb->get_results( "SELECT * FROM $table_name" );

						if ( ! empty( $wishlist_users ) ) {
							foreach ( $wishlist_users as $user ) {
								$username = esc_html( $user->username );
								$useremail = esc_html( $user->useremail );
								$product_ids = array_filter( explode( ',', $user->product_ids ) ); // Extract product IDs
								$wishlist_sublist = json_decode( $user->wishlist_sublist, true );

								if ( is_array( $wishlist_sublist ) ) {
									foreach ( $wishlist_sublist as $sublist => $sublist_ids ) {
										$product_ids = array_merge( $product_ids, $sublist_ids );
									}
								}

								$product_ids = array_unique( $product_ids );
								$product_titles = [];
								foreach ( $product_ids as $product_id ) {
									$product_title = get_the_title( $product_id );
									if ( $product_title ) {
										$product_titles[] = $product_title;
									}
								}

								$product_list = ! empty( $product_titles ) ? implode( ', ', $product_titles ) : __( 'No Products', 'shopglut' );
								$product_added_time = esc_html( $user->product_added_time );
								?>
            <tr>
                <td><?php echo $username; ?></td>
                <td><?php echo $useremail; ?></td>
                <td><?php echo $product_list; ?></td>
                <td><?php echo $product_added_time; ?></td>
                <td>
                    <!-- Disable send email button if Pro version is active -->
                    <?php if ( $is_pro ) : ?>
                    <a href="<?php echo esc_url( $this->field['pro'] ); ?>" target="_blank" class="agl--pro-link">

                        <?php echo esc_html( $pro_text ); ?>

                    </a>
                    <?php else : ?>
                    <button class="agl-send-email-button" data-email="<?php echo esc_attr( $user->useremail ); ?>">
                        <?php esc_html_e( 'Send Email', 'shopglut' ); ?>
                    </button>
                    <?php endif; ?>


                </td>
            </tr>
            <?php
							}
						} else {
							?>
            <tr>
                <td colspan="5"><?php esc_html_e( 'No data available', 'shopglut' ); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
		}
	}
}