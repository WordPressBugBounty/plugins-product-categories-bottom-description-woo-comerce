<?php

// Save all plugin fields
add_action('create_product_cat', 'save_woo_bottom_description_display_option');
add_action('edit_product_cat', 'save_woo_bottom_description_display_option');
add_action('create_product_tag', 'save_woo_bottom_description_display_option');
add_action('edit_product_tag', 'save_woo_bottom_description_display_option');
function save_woo_bottom_description_display_option($term_id) {
    $old_position = get_term_meta( $term_id, PCBDW_META_PREFIX . 'display_position', true );
    $new_position = isset( $_POST['woo_bottom_description_display_position'] ) ? pcbdw_sanitize_details( $_POST['woo_bottom_description_display_position'] ) : '';
    if ( $old_position !== $new_position ) {
        update_term_meta( $term_id, PCBDW_META_PREFIX . 'display_position', $new_position );
    }

    if ( isset( $_POST['pcbdw-product-cat-bottom-description'] ) ) {
        $new_details = pcbdw_sanitize_details( $_POST['pcbdw-product-cat-bottom-description'] );
    } elseif ( isset( $_POST['pcbdw-bottom-description-content'] ) ) {
        $new_details = pcbdw_sanitize_details( $_POST['pcbdw-bottom-description-content'] );
    } else {
        $new_details = '';
    }
    update_term_meta( $term_id, 'details', $new_details );

    $old_option = get_term_meta( $term_id, PCBDW_META_PREFIX . 'display_option', true );
    $new_option = isset( $_POST['woo_bottom_description_display_option'] ) ? pcbdw_sanitize_details( $_POST['woo_bottom_description_display_option'] ) : '';
    if ( $old_option !== $new_option ) {
        update_term_meta( $term_id, PCBDW_META_PREFIX . 'display_option', $new_option );
    }
}
