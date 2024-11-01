<?php

if (!defined('ABSPATH')) {
    die;
}

AGSHOPGLUT::createSettings(
    'agshop_single_settings_layout',
    array(
        'title' => esc_html__('Layout Settings', 'shopglut'),
        'post_type' => 'shopglut_layouts',
        'editor' => 'single_product',

    )
);

AGSHOPGLUT::createSection(
    'agshop_single_settings_layout',
    array(
        'fields' => array(

            array(
                'id' => 'shopg_settings_options',
                'type' => 'section',
                'sections' => array(
                    array(
                        'id' => 'shopg_single_component_heading',
                        'title' => esc_html__('Content', 'shopglut'),
                        'icon' => 'fa fa-up-down-left-right',
                        'fields' => array(

                            array(
                                'id' => 'heading_title',
                                'type' => 'text',
                                'title' => __('Title Text', 'shopglut'),
                                'default' => __('Custom Heading', 'shopglut'),
                            ),

                            array(
                                'id' => 'heading_title_align',
                                'type' => 'button_set',
                                'title' => __('Title Alignment', 'shopglut'),
                                'options' => array(
                                    'left' => __('Left', 'shopglut'),
                                    'center' => __('Middle', 'shopglut'),
                                    'right' => __('Right', 'shopglut'),
                                ),
                                'default' => 'center',
                            ),

                            array(
                                'id' => 'heading_title_BGColor',
                                'type' => 'text',
                                'title' => __('BackGround Color', 'shopglut'),
                            ),
                            

                            // array(
                            //     'id' => 'heading_title_margin',
                            //     'type' => 'space',
                            //     'title' => __('Title Margin', 'shopglut'),
                            //     'active_device' => true,
                            // ),

                            array(
                                'id' => 'heading_title_padding',
                                'type' => 'space',
                                'title' => __('Title Padding', 'shopglut'),
                                'active_device' => true,
                            ),
                            // array(
                            //     'id' => 'heading_subtitle_margin',
                            //     'type' => 'space',
                            //     'title' => __('SubTitle Margin', 'shopglut'),
                            //     'active_device' => true,
                            // ),

                            array(
                                'id' => 'heading_subtitle_margin',
                                'type' => 'space',
                                'title' => __('SubTitle Padding', 'shopglut'),
                                'active_device' => true,
                            ),
                        ),
                    ),
                    array(
                        'id' => 'shopg_single_component_image',
                        'title' => esc_html__('Style', 'shopglut'),
                        'icon' => 'fa fa-palette',
                        'fields' => array(

                            array(
                                'id' => 'shopg-spacing-margin',
                                'type' => 'space',
                                'title' => __('Product Margin', 'shopglut'),
                                'active_device' => true,
                            ),

                            array(
                                'id' => 'shopg-spacing-padding',
                                'type' => 'space',
                                'title' => __('Product Padding', 'shopglut'),
                                'active_device' => true,
                            ),
                        ),
                    ),
                ),
            ),

        ),
    )
);
