<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: section
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'AGSHOPGLUT_Field_section' ) ) {
  class AGSHOPGLUT_section extends AGSHOPGLUTP {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unallows = array( 'section' );

      echo esc_attr($this->field_before());

      foreach ( $this->field['sections'] as $key => $tab ) {

        echo '<div class="agl-section-content '. esc_attr( $tab['id'] ) .'">';

        foreach ( $tab['fields'] as $field ) {

          if ( in_array( $field['type'], $unallows ) ) { $field['_notice'] = true; }

          $field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
          $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
          $field_value   = ( isset( $this->value[$field_id] ) ) ? $this->value[$field_id] : $field_default;
          $unique_id     = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']' : $this->field['id'];

          AGSHOPGLUT::field( $field, $field_value, $unique_id, 'field/section' );

        }
        echo '</div>';

      }

      echo esc_attr($this->field_after());

    }

  }
}
