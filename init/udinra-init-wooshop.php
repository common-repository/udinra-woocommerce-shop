<?php


function udinra_wooshop_shortcode( $udinra_wooshop_atts ) {

    $udinra_wooshop_parameters = shortcode_atts( array(
									'sort' => 'newest', 
									'show' => 'seventh',
									'purchase' => 'true',
									'downcount' => '6',
									'image' => 'medium'
									), $udinra_wooshop_atts );

	$udinra_woo_shop_html = '';
	$udinra_wooshop_filter_sort = 0;
	
	if($udinra_wooshop_parameters["sort"] == 'newest'){
		$udinra_wooshop_filter_sort = 1;
	}
	if($udinra_wooshop_parameters["sort"] == 'oldest'){
		$udinra_wooshop_filter_sort = 2;
	}
	if($udinra_wooshop_parameters["sort"] == 'lowprice'){
		$udinra_wooshop_filter_sort = 3;
	}
	if($udinra_wooshop_parameters["sort"] == 'highprice'){
		$udinra_wooshop_filter_sort = 4;
	}
	if($udinra_wooshop_parameters["sort"] == 'rated'){
		$udinra_wooshop_filter_sort = 5;
	}
	if($udinra_wooshop_parameters["sort"] == 'sales'){
		$udinra_wooshop_filter_sort = 6;
	}
	if($udinra_wooshop_parameters["sort"] == 'review'){
		$udinra_wooshop_filter_sort = 7;
	}
	if($udinra_wooshop_parameters["show"] == 'first'){
		$udinra_wooshop_filter_show = 1;
	}
	if($udinra_wooshop_parameters["show"] == 'second'){
		$udinra_wooshop_filter_show = 2;
	}
	if($udinra_wooshop_parameters["show"] == 'third'){
		$udinra_wooshop_filter_show = 3;
	}
	if($udinra_wooshop_parameters["show"] == 'fourth'){
		$udinra_wooshop_filter_show = 4;
	}
	if($udinra_wooshop_parameters["show"] == 'fifth'){
		$udinra_wooshop_filter_show = 5;
	}
	if($udinra_wooshop_parameters["show"] == 'sixth'){
		$udinra_wooshop_filter_show = 6;
	}
	if($udinra_wooshop_parameters["show"] == 'seventh'){
		$udinra_wooshop_filter_show = 7;
	}
	
	$udinra_wooshop_filter_count = filter_var($udinra_wooshop_parameters["downcount"], FILTER_SANITIZE_NUMBER_INT); 
	update_option('udinra_wooshop_filter_purchase',$udinra_wooshop_parameters["purchase"]);
	update_option('udinra_wooshop_filter_show',$udinra_wooshop_filter_show);
	update_option('udinra_wooshop_filter_image',$udinra_wooshop_parameters["image"]);
	update_option('udinra_wooshop_filter_loaded',1);
	update_option('udinra_wooshop_filter_count',$udinra_wooshop_filter_count);
	update_option('udinra_wooshop_filter_sort',$udinra_wooshop_filter_sort);
	
	$udinra_woo_shop_call = 0;
	
	$udinra_woo_shop_html = udinra_wooshop_get_downloads($udinra_woo_shop_call,$udinra_wooshop_filter_sort);
	
	udinra_wooshop_script();
	udinra_wooshop_css();
	return $udinra_woo_shop_html;
	
}

function udinra_wooshop_script() {
	wp_enqueue_script( 'udinra-wooshop-handle', plugins_url( 'js/udinra_wooshop_ajax.js',dirname( __FILE__ )), array( 'jquery' ) );
	wp_localize_script( 'udinra-wooshop-handle', 'udinra_wooshop_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

function udinra_wooshop_css() {
		wp_enqueue_style( 'udinra-wooshop-css', plugins_url( 'css/udstyle.css',dirname( __FILE__ )));
		wp_enqueue_style( 'udinra-wooshop-rating', plugins_url( 'css/rating.css',dirname( __FILE__ )));
}
	
add_shortcode( 'udinra_wooshop', 'udinra_wooshop_shortcode' );

?>