<?php
function udinra_wooshop_button() {
	$udinra_wooshop_cap = apply_filters( 'udinra_wooshop_button_cap', 'edit_posts' );
	if ( current_user_can( $udinra_wooshop_cap ) ) {
		add_filter( 'mce_external_plugins', 'udinra_woo_shop_plugin' );
		add_filter( 'mce_buttons', 'udinra_wooshop_register_button' );
	}
}
function udinra_woo_shop_plugin( $plugin_array ) {
	$plugin_array['udinra_wooshop_subscribe'] = plugins_url( 'js/udinra_wooshop_button.js',dirname( __FILE__ ));
	return $plugin_array;
}
function udinra_wooshop_register_button( $buttons ) {
	array_push( $buttons, "udinra_wooshop_subscribe" );
	return $buttons;
}
?>