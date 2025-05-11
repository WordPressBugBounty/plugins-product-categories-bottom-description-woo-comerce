<?php

add_action( 'product_cat_add_form_fields', 'pcbdw_product_cat_add_details_meta' );
add_action( 'product_tag_add_form_fields', 'pcbdw_product_cat_add_details_meta' );
function pcbdw_product_cat_add_details_meta() {
    wp_nonce_field( 'pcbdw_product_category_bottom_description', 'pcbdw_product_category_bottom_description_nonce' );
    ?>
    <div class="form-field">
        <label for="pcbdw-bottom-description-content"><?php esc_html_e( 'Bottom description', 'pcbdw' ); ?></label>
        <textarea name="pcbdw-bottom-description-content" id="pcbdw-bottom-description-content" rows="5" cols="40"></textarea>
        <p class="description"><?php esc_html_e( 'The content in this field will be shown after the products within the product category/tag.', 'pcbdw' ); ?></p>
    </div>
    <?php
}




// Add custom fields to the Edit Product Category page
add_action( 'product_cat_edit_form_fields', 'pcbdw_product_cat_custom_fields', 10, 2 );
add_action( 'product_tag_edit_form_fields', 'pcbdw_product_cat_custom_fields', 10, 2 );
function pcbdw_product_cat_custom_fields( $term, $taxonomy ) {
    if ( ! pcbdw_is_supported_taxonomy( $taxonomy ) ) return;
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"></th>
        <td>
            <hr style="margin-bottom: 25px;">
            <h3>Product categories bottom description for WooCommerce</h3>
        </td>
    </tr>
    <?php

    $display_position = get_term_meta($term->term_id, PCBDW_META_PREFIX . 'display_position', true);
    if ( !$display_position ) {
        $display_position = 'woocommerce_after_shop_loop';
    }
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="woo_bottom_description_display_position">Where would you like to display the new product category description?</label></th>
        <td>
            <select name="woo_bottom_description_display_position" id="woo_bottom_description_display_position">
                <option value="woocommerce_before_main_content"<?php if($display_position=='woocommerce_before_main_content'){echo ' selected';} ?>>Before Product Category Title</option>
                <option value="woocommerce_archive_description"<?php if($display_position=='woocommerce_archive_description'){echo ' selected';} ?>>After WooCommerce Product Description</option>
                <option value="woocommerce_before_shop_loop"<?php if($display_position=='woocommerce_before_shop_loop'){echo ' selected';} ?>>Before products</option>
                <option value="woocommerce_after_shop_loop"<?php if($display_position=='woocommerce_after_shop_loop'){echo ' selected';} ?>>After products (default)</option>
                <option value="woocommerce_after_main_content"<?php if($display_position=='woocommerce_after_main_content'){echo ' selected';} ?>>After the main content</option>
            </select>
        </td>
    </tr>
    <?php

    $product_category_bottom_description = get_term_meta( $term->term_id, 'details', true );
    if ( ! $product_category_bottom_description ) {
        $product_category_bottom_description = '';
    }
    $settings = array( 'textarea_name' => 'pcbdw-product-cat-bottom-description' );
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="pcbdw-product-cat-bottom-description"><?php esc_html_e( 'Bottom description', 'pcbdw' ); ?></label></th>
        <td>
            <?php wp_nonce_field( 'pcbdw_save_details', 'pcbdw_details_nonce' ); ?>
            <?php wp_editor( wp_kses_post( $product_category_bottom_description ), 'pcbdw-product-cat-bottom-description', $settings ); ?>
        </td>
    </tr>
    <?php

    $display_option = get_term_meta($term->term_id, PCBDW_META_PREFIX . 'display_option', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="display_option">Hide bottom description</label></th>
        <td>
            <input type="checkbox" name="woo_bottom_description_display_option" id="display_option" value="1" <?php checked($display_option, 1); ?> />
            <label for="display_option">Check this option if you want to <u>hide</u> the bottom description on the category page.</label>
            <br><hr style="margin-top: 25px;">
        </td>
    </tr>
    <?php
}




// Add fields to attribute terms (pa_xxx)
add_action( 'init', function() {
    $attribute_taxonomies = get_object_taxonomies( 'product' );
    foreach ( $attribute_taxonomies as $taxonomy ) {
        if ( strpos( $taxonomy, 'pa_' ) === 0 ) {
            add_action( "{$taxonomy}_add_form_fields", 'pcbdw_product_cat_add_details_meta' );
            add_action( "{$taxonomy}_edit_form_fields", 'pcbdw_product_cat_custom_fields', 10, 2 );
            add_action( "created_{$taxonomy}", 'save_woo_bottom_description_display_option', 10, 2 );
            add_action( "edited_{$taxonomy}", 'save_woo_bottom_description_display_option', 10, 2 );
        }
    }
});
