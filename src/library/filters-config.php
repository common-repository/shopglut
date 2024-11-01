<?php

if (!defined('ABSPATH')) {
	die;
}

// Create the live preview metabox
AGSHOPGLUT::createMetabox(
	'shopg_filter_live_preview',
	array(
		'title' => __('Live Preview', 'shopglut'),
		'post_type' => 'shopglut_showcase',
		'context' => 'normal',
	)
);

// Create the live preview section
AGSHOPGLUT::createSection(
	'shopg_filter_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

$SHOPG_OPTIONS_SETTINGS = "shopg_filter_options_settings";

// Create the layout settings metabox
AGSHOPGLUT::createMetabox(
	$SHOPG_OPTIONS_SETTINGS,
	array(
		'title' => esc_html__('Layout Settings', 'shopglut'),
		'post_type' => 'shopglut_showcase',
		'context' => 'side',
	)
);

// Fetch all WooCommerce product attributes
$attribute_taxonomies = wc_get_attribute_taxonomies();

// Build options array dynamically for filter types
$options = array(
	'product-categories' => esc_html__('Product Categories', 'shopglut'),
	'product-tags' => esc_html__('Product Tags', 'shopglut'),
	'product-price' => esc_html__('Product Price', 'shopglut'),
	'product-rating' => esc_html__('Product Rating', 'shopglut'),
	'product-author' => esc_html__('Author', 'shopglut'),
	'product-stock' => esc_html__('Product Stock', 'shopglut'),
	'product-type' => esc_html__('Product Type', 'shopglut'),
	'product-keyword' => esc_html__('Product Keyword', 'shopglut'),
);

// Loop through attributes and add them to filter options
if (!empty($attribute_taxonomies)) {
	foreach ($attribute_taxonomies as $attribute) {
		if (isset($attribute->attribute_name)) {
			$attribute_name = wc_attribute_taxonomy_name($attribute->attribute_name);
			$options[$attribute_name] = esc_html__(ucfirst(str_replace('-', ' ', 'Product ' . ucfirst($attribute->attribute_label))), 'shopglut');
		}
	}
}

// Main fields array
$fields = array(
	array(
		'id' => 'shopglut-filter-settings-main-tab',
		'type' => 'tabbed',
		'tabs' => array(
			array(
				'class' => "shopglut-filter-settings-main-tab-11",
				'title' => __('Filter', 'shopglut'),
				'icon' => 'fa-solid fa-filter',
				'fields' => array(
					array(
						'id' => 'shopg-filter-add-new',
						'type' => 'repeater',
						'button_title' => __("Add New", 'shopglut'),
						'fields' => array(

							array(
								'id' => 'shopg-filter-accordion-11',
								'type' => 'accordion',
								'accordions' => array(
									array(
										'title' => __('Title', 'shopglut'),
										'fields' => array(
											array(
												'id' => 'shopg-filter-sub-tabbed',
												'type' => 'tabbed',
												'tabs' => array(
													array(
														'class' => 'general-tab',
														'title' => __('General', 'shopglut'),
														'icon' => 'fa-solid fa-gears',
														'fields' => array(
															array(
																'id' => 'accordion-title',
																'type' => 'text',
																'title' => __('Filter Title', 'shopglut'),
															),
															array(
																'id' => 'filter-type',
																'type' => 'select',
																'title' => __('Filter Type', 'shopglut'),
																'options' => $options,
																'default' => 'product-categories',
															),

															array(
																'id' => 'filter-orderby',
																'type' => 'select',
																'title' => __('Order By', 'shopglut'),
																'options' => array(
																	'date' => __('Date', 'shopglut'),
																	'id' => __('ID', 'shopglut'),
																	'name' => __('Name', 'shopglut'),
																),
																'default' => 'product-categories',
															),

															array(
																'id' => 'filter-cat-exclude-include-button',
																'type' => 'button_set',
																'title' => __('Choose Option', 'shopglut'),
																'options' => array(
																	'all-cat' => __('All Categories', 'shopglut'),
																	'exclude' => __('Exclude', 'shopglut'),
																	'include' => __('Include', 'shopglut'),
																),
																'default' => 'all-cat',
																'dependency' => array('filter-type', '==', "product-categories"),
															),
															array(
																'id' => 'shopg-layouts-include-categories',
																'type' => 'select',
																'title' => esc_html__('Include Categories', 'shopglut'),
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__('Choose Category', 'shopglut'),
																'options' => 'categories',
																'query_args' => array(
																	'taxonomy' => 'product_cat',
																),
																'dependency' => array('filter-cat-exclude-include-button|filter-type', '==|==', "include|product-categories"),
															),
															array(
																'id' => 'shopg-layouts-exclude-categories',
																'type' => 'select',
																'title' => esc_html__('Exclude Categories', 'shopglut'),
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__('Choose Category', 'shopglut'),
																'options' => 'categories',
																'query_args' => array(
																	'taxonomy' => 'product_cat',
																),
																'dependency' => array('filter-cat-exclude-include-button|filter-type', '==|==', "exclude|product-categories"),
															),
															array(
																'id' => 'filter-tag-exclude-include-button',
																'type' => 'button_set',
																'title' => __('Choose Option', 'shopglut'),
																'options' => array(
																	'all-tags' => __('All Tags', 'shopglut'),
																	'exclude' => __('Exclude', 'shopglut'),
																	'include' => __('Include', 'shopglut'),
																),
																'default' => 'all-tags',
																'dependency' => array('filter-type', '==', "product-tags"),
															),
															array(
																'id' => 'shopg-layouts-include-tags',
																'type' => 'select',
																'title' => esc_html__('Include Tags', 'shopglut'),
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__('Choose Tags', 'shopglut'),
																'options' => 'categories',
																'query_args' => array(
																	'taxonomy' => 'product_tag',
																),
																'dependency' => array('filter-tag-exclude-include-button|filter-type', '==|==', "include|product-tags"),
															),
															array(
																'id' => 'shopg-layouts-exclude-tags',
																'type' => 'select',
																'title' => esc_html__('Exclude Tags', 'shopglut'),
																'chosen' => true,
																'multiple' => true,
																'placeholder' => esc_html__('Choose Tags', 'shopglut'),
																'options' => 'categories',
																'query_args' => array(
																	'taxonomy' => 'product_tag',
																),
																'dependency' => array('filter-tag-exclude-include-button|filter-type', '==|==', "exclude|product-tags"),
															),

														),
													),
													array(
														'class' => 'appearance-tab',
														'title' => __('Appearance', 'shopglut'),
														'icon' => 'fa-solid fa-vest',
														'fields' => array(

															array(
																'id' => 'filter-display',
																'type' => 'select',
																'title' => __('Display', 'shopglut'),
																'options' => array(
																	'checkbox' => __('CheckBox', 'shopglut'),
																	'radio' => __('Radio', 'shopglut'),
																	'label' => __('Label', 'shopglut'),
																	'select' => __('Select', 'shopglut'),
																	'multiselect' => __('MultiSelect', 'shopglut'),
																),
																'default' => 'checkbox',
															),

															array(
																'id' => 'filter-layout',
																'type' => 'select',
																'title' => __('Layout', 'shopglut'),
																'options' => array(
																	'inline' => __('Inline', 'shopglut'),
																	'list' => __('List', 'shopglut'),
																	'grid' => __('Grid', 'shopglut'),
																),
																'default' => 'inline',
															),

															array(
																'id' => 'show-count',
																'type' => 'switcher',
																'title' => __("Show Count", 'shopglut'),
																'text_on' => __('Yes', 'shopglut'),
																'text_off' => __('No', 'shopglut'),
																'default' => false,
															),

															array(
																'id' => 'filter-enable-tooltip',
																'type' => 'switcher',
																'title' => __("Enable Tooltip", 'shopglut'),
																'text_on' => __('Yes', 'shopglut'),
																'text_off' => __('No', 'shopglut'),
																'default' => false,
															),

															array(
																'id' => 'tooltip-position',
																'type' => 'button_set',
																'title' => __('Tooltip Position', 'shopglut'),
																'options' => array(
																	'left' => __('Left', 'shopglut'),
																	'top' => __('Top', 'shopglut'),
																	'right' => __('Right', 'shopglut'),
																	'bottom' => __('Bottom', 'shopglut'),
																),
																'default' => 'right',
																'dependency' => array('filter-enable-tooltip', '==', "1"),
															),

														),
													),

												),
											),
										),
									),
								),
							),
						),
					),
				),
			),
			array(
				'class' => "shog-filter-settings-maintab",
				'title' => __('Settings', 'shopglut'),
				'icon' => 'fa fa-gear',
				'fields' => array(
					array(
						'id' => 'filter-option',
						'type' => 'select',
						'title' => __('Filter Option', 'shopglut'),
						'options' => array(
							'submit-filter' => esc_html__('Submit Filter', 'shopglut'),
							'ajax-filter' => esc_html__('Ajax Filter', 'shopglut'),
						),
						'default' => 'ajax-filter',
					),

					array(
						'id' => 'filter-show-title',
						'type' => 'checkbox',
						'title' => __('Filter Title Show', 'shopglut'),
						'label' => __('Yes', 'shopglut'),
						'default' => true, // or false
					),
				),
			),
		),
	),

	array(
		'id' => 'save-filter-settings',
		'button_text' => __('Save Filter', 'shopglut'),
		'type' => 'publish',
	),

);

// Loop through attributes and add include/exclude fields for each attribute
if (!empty($attribute_taxonomies)) {
	foreach ($attribute_taxonomies as $attribute) {
		if (isset($attribute->attribute_name)) {
			$attribute_name = wc_attribute_taxonomy_name($attribute->attribute_name);
			$attribute_label = ucfirst(str_replace('-', ' ', $attribute->attribute_label));

			// Add include/exclude button sets and select fields
			$fields[0]['tabs'][0]['fields'][0]['fields'][0]['accordions'][0]['fields'][0]['tabs'][0]['fields'][] = array(
				'id' => 'filter-' . $attribute_name . '-exclude-include-button',
				'type' => 'button_set',
				'title' => __('Choose Options', 'shopglut'),
				'options' => array(
					'all' => __('All ' . $attribute_label, 'shopglut'),
					'exclude' => __('Exclude', 'shopglut'),
					'include' => __('Include', 'shopglut'),
				),
				'default' => 'all',
				'dependency' => array('filter-type', '==', $attribute_name),
			);

			$fields[0]['tabs'][0]['fields'][0]['fields'][0]['accordions'][0]['fields'][0]['tabs'][0]['fields'][] = array(
				'id' => 'shopg-layouts-include-' . $attribute_name,
				'type' => 'select',
				'title' => esc_html__('Include ' . $attribute_label, 'shopglut'),
				'chosen' => true,
				'multiple' => true,
				'placeholder' => esc_html__('Choose ' . $attribute_label, 'shopglut'),
				'options' => 'categories',
				'query_args' => array(
					'taxonomy' => $attribute_name,
				),
				'dependency' => array('filter-' . $attribute_name . '-exclude-include-button|filter-type', '==|==', "include|$attribute_name"),
			);

			$fields[0]['tabs'][0]['fields'][0]['fields'][0]['accordions'][0]['fields'][0]['tabs'][0]['fields'][] = array(
				'id' => 'shopg-layouts-exclude-' . $attribute_name,
				'type' => 'select',
				'title' => esc_html__('Exclude ' . $attribute_label, 'shopglut'),
				'chosen' => true,
				'multiple' => true,
				'placeholder' => esc_html__('Choose ' . $attribute_label, 'shopglut'),
				'options' => 'categories',
				'query_args' => array(
					'taxonomy' => $attribute_name,
				),
				'dependency' => array('filter-' . $attribute_name . '-exclude-include-button|filter-type', '==|==', "exclude|$attribute_name"),
			);

		}
	}
}

// Final section creation
AGSHOPGLUT::createSection(
	$SHOPG_OPTIONS_SETTINGS,
	array(
		'fields' => $fields, // Use the $fields array here
	)
);