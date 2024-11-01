<?php

if (!defined('ABSPATH')) {
    die;
}

$taxonomy_options = get_option('agshopglut_settings_options');
$taxonomies = [];
if (!empty($taxonomy_options['agshopglut_tax_values_admin'])) {
    foreach ($taxonomy_options['agshopglut_tax_values_admin'] as $single_value) {
        $taxonomies[] = $single_value;
    }
}
// Set a unique slug-like ID
$AGSHOPGLUT_TAXONOMY_OPTIONS = 'agshopglut_taxonomy_options';

//
// Create taxonomy options
AGSHOPGLUT::createTaxonomyOptions($AGSHOPGLUT_TAXONOMY_OPTIONS, array(
    'taxonomy' => $taxonomies,
    'data_type' => 'serialize', // The type of the database save options. `serialize` or `unserialize`
));

//
// Create a section
AGSHOPGLUT::createSection($AGSHOPGLUT_TAXONOMY_OPTIONS, array(
    'fields' => array(

        array(
            'id' => 'taxonomy_shopglut_icon_switcher',
            'class' => 'taxonomy_shopglut_icon_switcher',
            'type' => 'switcher',
            'text_on' => esc_html__('Font Icon', 'shopglut'),
            'text_off' => esc_html__('Custom Icon', 'shopglut'),
            'default' => true,
            'text_width' => 110,
        ),

        array(
            'id' => 'taxonomy_product_shopglut_icon',
            'class' => 'taxonomy_product_shopglut_icon',
            'button_title' => esc_html__('Font Icon', 'shopglut'),
            'type' => 'icon',
            'library' => 'image',
            'url' => false,
            'dependency' => array('taxonomy_shopglut_icon_switcher', '==', true),
        ),

        array(
            'id' => 'taxonomy_shopglut_custom_icon',
            'class' => 'taxonomy_shopglut_custom_icon',
            'button_title' => esc_html__('Custom Icon', 'shopglut'),
            'type' => 'media',
            'library' => 'image',
            'url' => false,
            'dependency' => array('taxonomy_shopglut_icon_switcher', '==', false),
        ),

    ),
));
