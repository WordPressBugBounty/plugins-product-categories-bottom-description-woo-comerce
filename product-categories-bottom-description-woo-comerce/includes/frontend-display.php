<?php

/**
 * Hook into WooCommerce and decide where to render the custom description.
 * If the category has products, use the user-selected hook.
 * If not, fall back to a hook that executes after the "no products found" message.
 */
add_action('woocommerce_before_main_content', function () {

    if (!is_tax('product_cat') || is_paged()) {
        return;
    }

    $term = get_queried_object();
    $display_position = get_term_meta($term->term_id, 'woo_bottom_description_display_position', true);

    if (have_posts()) {
        // Show description using the user-defined hook
        add_action($display_position, 'pcbdw_product_cat_display_details_meta');
    } else {
        // Show description only if no products found
        add_action('woocommerce_no_products_found', 'pcbdw_product_cat_display_details_meta');
    }

}, 5);


/**
 * Render the custom bottom description for product categories.
 * Only displays if the "display description" option is enabled and content exists.
 */
function pcbdw_product_cat_display_details_meta()
{
    $term = get_queried_object();
    $display_option = get_term_meta($term->term_id, 'woo_bottom_description_display_option', true);
    $checked = ($display_option === '1') ? 'checked' : '';

    if (!$checked) {
        $details = get_term_meta($term->term_id, 'details', true);
        $formatted_details = wpautop($details);

        if ('' !== $details) {
            echo '<div class="pcbdw-bottom-description-content">';
            echo apply_filters('the_content', wp_kses_post($formatted_details));
            echo '</div>';
        }
    }
}


/**
 * Output custom CSS styles in the frontend based on saved plugin options.
 */
add_action('wp_enqueue_scripts', function () {
    if (!is_tax('product_cat')) return;

    wp_enqueue_style('pcbdw-custom-style', plugins_url('../assets/css/style.css', __FILE__));

    $sides = ['top', 'right', 'bottom', 'left'];
    $css = '';

    foreach (['margin', 'padding'] as $type) {
        $values = [];
        foreach ($sides as $side) {
            $val = get_option("pcbdw_{$type}_{$side}_value", '');
            $unit = get_option("pcbdw_{$type}_{$side}_unit", 'px');
            $values[] = $val !== '' ? "{$val}{$unit}" : '0';
        }
        $css .= "{$type}: " . implode(' ', $values) . "; ";
    }

    $max_width_val = get_option('pcbdw_max_width_value', '');
    $max_width_unit = get_option('pcbdw_max_width_unit', 'px');
    if ($max_width_val !== '') {
        $css .= "max-width: {$max_width_val}{$max_width_unit}; ";
    }

    $bg_color = get_option('pcbdw_background_color', '#ffffff');
    $css .= "background-color: {$bg_color}; ";

    $border_width = get_option('pcbdw_border_width', '');
    $border_color = get_option('pcbdw_border_color', '#000000');
    if ($border_width !== '') {
        $css .= "border: {$border_width}px solid {$border_color}; ";
    }

    $radius_val = get_option('pcbdw_border_radius_value', '');
    $radius_unit = get_option('pcbdw_border_radius_unit', 'px');
    if ($radius_val !== '') {
        $css .= "border-radius: {$radius_val}{$radius_unit}; ";
    }

    $final_css = ".pcbdw-bottom-description-content { {$css} }";

    wp_add_inline_style('pcbdw-custom-style', $final_css);
});
