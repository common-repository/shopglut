<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: textarea
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_textarea' ) ) {
  class AGSHOPGLUT_textarea extends AGSHOPGLUTP {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      echo esc_attr($this->field_before());
      echo esc_attr($this->shortcoder());
      echo '<textarea name="'. esc_attr( $this->field_name() ) .'"'. wp_kses_post($this->field_attributes()) .'>'. wp_kses_post($this->value) .'</textarea>';
      echo esc_attr($this->field_after());

    }

    public function shortcoder() {

      if ( ! empty( $this->field['shortcoder'] ) ) {

        $instances = ( is_array( $this->field['shortcoder'] ) ) ? $this->field['shortcoder'] : array_filter( (array) $this->field['shortcoder'] );

        foreach ( $instances as $instance_key ) {

          if ( isset( AGSHOPGLUT::$shortcode_instances[$instance_key] ) ) {

            $button_title = AGSHOPGLUT::$shortcode_instances[$instance_key]['button_title'];

            echo '<a href="#" class="button button-primary agl-shortcode-button" data-modal-id="'. esc_attr( $instance_key ) .'">'. esc_html($button_title) .'</a>';

          }

        }

      }

    }
  }
}
