<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

/**
 * Field: dimensions with pro appearance
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'AGSHOPGLUT_dimensions' ) ) {
	class AGSHOPGLUT_dimensions extends AGSHOPGLUTP {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'width_icon' => '<i class="fas fa-arrows-alt-h"></i>',
				'height_icon' => '<i class="fas fa-arrows-alt-v"></i>',
				'width_placeholder' => esc_html__( 'width', 'agl' ),
				'height_placeholder' => esc_html__( 'height', 'agl' ),
				'width' => true,
				'height' => true,
				'unit' => true,
				'show_units' => true,
				'units' => array( 'px', '%', 'em' ),
			) );

			$default_values = array(
				'width' => '',
				'height' => '',
				'unit' => 'px',
			);

			$value = wp_parse_args( $this->value, $default_values );
			$unit = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
			$is_unit = ( ! empty( $unit ) ) ? ' agl--is-unit' : '';

			// Check if the field is pro-only
			$is_pro = ! empty( $this->field['pro'] ) ? true : false;
			$pro_text = __( 'Unlock in Pro version', 'shopglut' );

			echo $this->field_before();
			echo '<div class="agl--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			if ( $is_pro ) {
				// Pro-only appearance with disabled fields
				echo '<div class="agl--input agl--pro agl--disabled">';
				echo '<input type="text" disabled="disabled" class="agl-input-number" placeholder="' . esc_attr( $pro_text ) . '"/>';
				echo '</div>';
				echo '<a href="' . esc_url( $this->field['pro'] ) . '" target="_blank" class="agl--pro-link">' . esc_html( $pro_text ) . '</a>';
			} else {
				// Normal Dimensions Controls
				if ( ! empty( $args['width'] ) ) {
					$placeholder = ( ! empty( $args['width_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['width_placeholder'] ) . '"' : '';
					echo '<div class="agl--input">';
					echo ( ! empty( $args['width_icon'] ) ) ? '<span class="agl--label agl--icon">' . $args['width_icon'] . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[width]' ) ) . '" value="' . esc_attr( $value['width'] ) . '"' . $placeholder . ' class="agl-input-number' . esc_attr( $is_unit ) . '" step="any" />';
					echo ( ! empty( $unit ) ) ? '<span class="agl--label agl--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
					echo '</div>';
				}

				if ( ! empty( $args['height'] ) ) {
					$placeholder = ( ! empty( $args['height_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['height_placeholder'] ) . '"' : '';
					echo '<div class="agl--input">';
					echo ( ! empty( $args['height_icon'] ) ) ? '<span class="agl--label agl--icon">' . $args['height_icon'] . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[height]' ) ) . '" value="' . esc_attr( $value['height'] ) . '"' . $placeholder . ' class="agl-input-number' . esc_attr( $is_unit ) . '" step="any" />';
					echo ( ! empty( $unit ) ) ? '<span class="agl--label agl--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
					echo '</div>';
				}

				if ( ! empty( $args['unit'] ) && ! empty( $args['show_units'] ) && count( $args['units'] ) > 1 ) {
					echo '<div class="agl--input">';
					echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
					foreach ( $args['units'] as $unit ) {
						$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
						echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $unit ) . '</option>';
					}
					echo '</select>';
					echo '</div>';
				}
			}

			echo '</div>';
			echo $this->field_after();
		}

		public function output() {

			$output = '';
			$element = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];
			$prefix = ( ! empty( $this->field['output_prefix'] ) ) ? $this->field['output_prefix'] . '-' : '';
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$unit = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';

			$width = ( isset( $this->value['width'] ) && $this->value['width'] !== '' ) ? $prefix . 'width:' . $this->value['width'] . $unit . $important . ';' : '';
			$height = ( isset( $this->value['height'] ) && $this->value['height'] !== '' ) ? $prefix . 'height:' . $this->value['height'] . $unit . $important . ';' : '';

			if ( $width !== '' || $height !== '' ) {
				$output = $element . '{' . $width . $height . '}';
			}

			$this->parent->output_css .= $output;

			return $output;
		}
	}
}