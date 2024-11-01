<?php
/*
Plugin Name: Udinra WooCommerce Shop
Plugin URI: https://udinra.com/downloads/woocommerce-shop-pro
Description: Professional Shop for your WooCommerce Store.
Author: Udinra
Version: 1.1
Author URI: https://udinra.com
*/

function Udinra_WooShop() {
	$udinra_woo_shop = '';
	if(isset($_POST['save_option'])) {
		udinra_woo_shop_refresh();
		$udinra_woo_shop =  'The Shop got refreshed successfully.';
	}	
	include 'lib/udinra_html_wooshop.php';
}

function udinra_woo_shop_admin() {
	if (function_exists('add_options_page')) {
		add_options_page('Udinra WooCommerce Shop', 'Udinra WooCommerce Shop', 'manage_options', basename(__FILE__), 'Udinra_WooShop');
	}
}

function udinra_wooshop_admin_notice() {
	global $current_user ;
	$user_id = $current_user->ID;
	if ( ! get_user_meta($user_id, 'udinra_wooshop_admin_notice') ) {
		echo '<div class="notice notice-info"><p>'; 
		printf(__('Increase conversion & Sales with Woo Shop Pro plugin <a href="%1$s"><b>Read More</b></a> | <a href="%2$s">Hide Notice</a>'),'https://udinra.com/downloads/woocommerce-shop-pro' ,'?udinra_wooshop_admin_ignore=0');
		echo "</p></div>";
	}
}

function udinra_wooshop_admin_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	if ( isset($_GET['udinra_wooshop_admin_ignore']) && '0' == $_GET['udinra_wooshop_admin_ignore'] ) {
		add_user_meta($user_id, 'udinra_wooshop_admin_notice', 'true', true);
	}
}
 
function udinra_wooshop_act() {
	wp_schedule_event( current_time( 'timestamp' ), 'daily', 'udinra_wooshop_event');
	udinra_woo_shop_install();
}

function udinra_wooshop_init_start() {
	udinra_wooshop_button();
}

function udinra_wooshop_event() {
	udinra_woo_shop_refresh();
}
function udinra_wooshop_deact() {
	wp_clear_scheduled_hook('udinra_wooshop_event');
	remove_action('admin_menu','udinra_woo_shop_admin');	
	remove_action('admin_notices', 'udinra_wooshop_admin_notice');
	remove_action('admin_init', 'udinra_wooshop_admin_ignore');
	remove_action( 'init', 'udinra_wooshop_update' );
	remove_action( 'wp_ajax_udinra_wooshop_hook', 'udinra_wooshop_function' );
	remove_action( 'wp_ajax_nopriv_udinra_wooshop_hook', 'udinra_wooshop_function' ); 
	remove_action( 'admin_enqueue_scripts', 'udinra_wooshop_admin_style' );
	remove_filter( 'plugin_action_links', 'udinra_wooshop_settings_plugin_link');
	udinra_woo_shop_uninstall();
}
function udinra_wooshop_function(){
	if($_POST['udinradec'] == 'Next'){
		$udinra_woo_shop_call = 2;
		udinra_wooshop_common($udinra_woo_shop_call);
	}
	elseif($_POST['udinradec'] == 'Prev'){
		$udinra_woo_shop_call = 3;
		udinra_wooshop_common($udinra_woo_shop_call);
	}
	else{
		update_option('udinra_wooshop_filter_loaded',1);
		$udinra_woo_shop_call = 1;
		udinra_wooshop_common($udinra_woo_shop_call);	
	}
}

function udinra_wooshop_common($udinra_woo_shop_call){
	$udinra_wooshop_filter_sort = '';
	$udinra_woo_shop_html = '';
	
	switch ($_POST['udwoosort']) {
		case "newest":
			$udinra_wooshop_filter_sort = 1;
			break;
		case "oldest":
			$udinra_wooshop_filter_sort = 2;
			break;
		case "lowprice":
			$udinra_wooshop_filter_sort = 3;
			break;
		case "highprice":
			$udinra_wooshop_filter_sort = 4;
			break;
		case "rated":
			$udinra_wooshop_filter_sort = 5;
			break;
		case "sales":
			$udinra_wooshop_filter_sort = 6;
			break;
		case "review":
			$udinra_wooshop_filter_sort = 7;
			break;
		default:
			$udinra_wooshop_filter_sort = get_option('udinra_wooshop_filter_sort');
	}
	
	$udinra_woo_shop_html = udinra_wooshop_get_downloads($udinra_woo_shop_call,$udinra_wooshop_filter_sort);
	echo $udinra_woo_shop_html;
	die();
}

function udinra_wooshop_admin_style($hook) {
	
	if($hook == 'settings_page_udinra-woocommerce-shop') {
		wp_enqueue_style( 'udinra_wooshop_pure_style', plugins_url('css/udstyle.css', __FILE__) );	
		wp_enqueue_script( 'udinra_image_pure_js', plugins_url('js/udinra_slideshow.js', __FILE__),array(), '1.0.0', true );
    }
}

function udinra_wooshop_settings_plugin_link( $links, $file ) 
{
    if ( $file == plugin_basename(dirname(__FILE__) . '/udinra-woocommerce-shop.php') ) 
    {
        $in = '<a href="options-general.php?page=udinra-woocommerce-shop">' . __('Settings','udwooshop') . '</a>';
        array_unshift($links, $in);
   }
    return $links;
}

include 'init/udinra-init-wooshop.php';
include 'lib/udinra-wooshop-visual-editor.php';
include 'db/udinra-wooshop-call-func.php';
include 'db/udinra-wooshop-db-func.php';

global $wpdb;	

register_activation_hook(__FILE__, 'udinra_wooshop_act');
register_deactivation_hook(__FILE__, 'udinra_wooshop_deact');

add_action('admin_menu','udinra_woo_shop_admin');	
add_action('admin_notices', 'udinra_wooshop_admin_notice');
add_action('admin_init', 'udinra_wooshop_admin_ignore');
add_action('init','udinra_wooshop_init_start');

add_action( 'wp_ajax_udinra_wooshop_hook', 'udinra_wooshop_function' );
add_action( 'wp_ajax_nopriv_udinra_wooshop_hook', 'udinra_wooshop_function' ); 
add_action( 'admin_enqueue_scripts', 'udinra_wooshop_admin_style' );
add_filter( 'plugin_action_links', 'udinra_wooshop_settings_plugin_link', 10, 2 );

?>
