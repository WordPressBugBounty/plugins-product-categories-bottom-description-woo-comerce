<?php

// Display details for supported taxonomies at configured position
add_action('wp', function(){

	if ( ! is_tax( 'product_cat' ) ) {
		return;
	}

	$term = get_queried_object();
	$display_position = get_term_meta($term->term_id, 'woo_bottom_description_display_position', true);

	add_action( $display_position, 'pcbdw_product_cat_display_details_meta' );
	function pcbdw_product_cat_display_details_meta() {

		// Check if the "show description" option is selected to show/hide its content
		$term = get_queried_object();
		$display_option = get_term_meta($term->term_id, 'woo_bottom_description_display_option', true);
		$checked = ($display_option === '1') ? 'checked' : '';
		
		if ( !$checked ) {
			$t_id = get_queried_object()->term_id;
			$details = get_term_meta( $t_id, 'details', true );
			$formatted_details = wpautop($details);
			if ( '' !== $details ) {
				?>
				<div class="pcbdw-bottom-description-content">
					<?php echo apply_filters( 'the_content', wp_kses_post( $formatted_details ) ); ?>
				</div>
				<?php
			}
		}
	}
	
});