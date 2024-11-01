<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_switcher' ) ) {
	class AGSHOPGLUT_switcher extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$active = ( ! empty( $this->value ) ) ? ' agl--active' : '';
			$text_on = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'shopglut' );
			$text_off = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'shopglut' );
			$text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: ' . wp_kses_post( $this->field['text_width'] ) . 'px;"' : '';

			echo esc_attr( $this->field_before() );

			// Check if 'pro' is set and has a value
			$is_pro = ! empty( $this->field['pro'] ) ? true : false;
			$pro_text = __( 'Unlock the Pro version', 'shopglut' );

			// If 'pro' is set, disable the switcher and show pro version text
			if ( $is_pro ) {
				echo '<div class="agl--switcher agl--pro agl--disabled"' . wp_kses_post( $text_width ) . '>';
				echo '<span class="agl--on">' . esc_attr( $text_on ) . '</span>';
				echo '<span class="agl--off">' . esc_attr( $text_off ) . '</span>';
				echo '<span class="agl--ball"></span>';
				echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" value="0" disabled />';
				echo '</div>';
				echo '<a href="' . esc_url( $this->field['pro'] ) . '" target="_blank" class="agl--pro-link">' . esc_html( $pro_text ) . '</a>';
			} else {
				// Normal switcher
				echo '<div class="agl--switcher' . esc_attr( $active ) . '"' . wp_kses_post( $text_width ) . '>';
				echo '<span class="agl--on">' . esc_attr( $text_on ) . '</span>';
				echo '<span class="agl--off">' . esc_attr( $text_off ) . '</span>';
				echo '<span class="agl--ball"></span>';
				$value = ( $this->value == 1 ) ? $this->value : 0;
				echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $value ) . '"' . wp_kses_post( $this->field_attributes() ) . ' />';
				echo '</div>';
			}

			echo ( ! empty( $this->field['label'] ) ) ? '<span class="agl--label">' . esc_attr( $this->field['label'] ) . '</span>' : '';

			echo wp_kses_post( $this->field_after() );

		}
	}
}