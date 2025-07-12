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
