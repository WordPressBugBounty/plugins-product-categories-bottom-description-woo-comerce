<?php

// Display details for supported taxonomies at configured position
add_action('template_redirect', function() {
    if ( ! is_tax() ) return;

    $term = get_queried_object();
    if ( empty( $term->term_id ) ) return;
    if ( ! pcbdw_is_supported_taxonomy( $term->taxonomy ) ) return;

    $display_position = get_term_meta($term->term_id, PCBDW_META_PREFIX . 'display_position', true);
    if ( ! $display_position ) $display_position = 'woocommerce_after_main_content';

    add_action( $display_position, function() {
        $term = get_queried_object();
        $display_option = get_term_meta($term->term_id, PCBDW_META_PREFIX . 'display_option', true);
        if ( $display_option == '1' ) return;
        $details = get_term_meta( $term->term_id, 'details', true );
        if ( $details ) echo '<div class="pcbdw-bottom-description">' . wp_kses_post( wpautop( $details ) ) . '</div>';
    });
});
