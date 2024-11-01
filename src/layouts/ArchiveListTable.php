<?php

namespace Shopglut\layouts;

use Shopglut\tables\LayoutEntity;


class ArchiveListTable extends \WP_List_Table
{

    public function get_layouts($per_page, $current_page = 1)
    {
        return LayoutEntity::retrieveAll($per_page, $current_page);
    }

    public function get_totals()
    {
        return LayoutEntity::retrieveAllCount();
    }


    // Define table columns
    function get_columns()
    {
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'layout_name' => esc_html__('Name', 'shopglut'),
            'layout_template' => esc_html__('Template', 'shopglut'),
            'shortcode'    => esc_html__('Shortcode', 'shopglut'),
        );
        return $columns;
    }

    public function no_items()
    {
        esc_html_e('No layout found.', 'shopglut');
    }

    public function column_layout_name($item)
    {

        $layout_id = absint($item['id']);

        $edit_link   = add_query_arg(['layout_id' => $layout_id], admin_url('admin.php?page=shopglut_layouts'));

        $actions = [
            // translators: %d is a placeholder
            'id'        => sprintf(__('ID: %d', 'shopglut'), $layout_id),
            'edit'      => sprintf('<a href="%s">%s</a>', $edit_link, esc_html__('Edit', 'shopglut')),
        ];

        $a = '<a href="' . esc_url($edit_link) . '">' . esc_html($item['layout_name']) . '</a>';

        return '<strong>' . wp_kses_post($a) . '</strong>' . wp_kses_post($this->row_actions($actions));

    }

    public function column_layout_template($item)
    {
        $name =  esc_html($item['layout_template']);

        return '<strong>' . esc_html($name) . '</strong>' ;
    }

    public function column_shortcode($item)
    {
        $shortcode_html = '<span class="ag_shopglut__shortcode-selectable">[shopg_layout id="' . esc_attr($item['id']) . '"]</span></div> <div class="ag_shopglut-after-copy-text"><i class="fa fa-check-circle"></i> ' . esc_html__("Shortcode  Copied!", "shopglut") . '</div>';

        return $shortcode_html ;
    }

   
    function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        $this->process_bulk_action();
        
        /* pagination */
        $per_page = $this->get_items_per_page('shopglut_layouts_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_items = $this->get_totals();


        $this->set_pagination_args(array(
            'total_items' => $total_items, // total number of items
            'per_page'    => $per_page // items to show on a page
        ));


        $this->items = $this->get_layouts($per_page, $current_page);
    }


    // To show checkbox with each row
    function column_cb($item)
    {  // translators: %s is a placeholder
        return sprintf(
            '<input type="checkbox" name="user[]" value="%s" />',
            $item['id']
        );
    }

    // Bulk actions
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => esc_html__('Delete', 'shopglut')
        );
        return $actions;
    }
}
