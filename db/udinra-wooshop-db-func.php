<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

function udinra_woo_shop_create() {
   global $wpdb;
   $UdinraWooShop = $wpdb->prefix . 'udwooshop';
   $udinra_charset_collate = $wpdb->get_charset_collate();
   update_option( "udinra_wooshop_db_version", '1.0.0' );	
   $udinra_woo_sql = "CREATE TABLE IF NOT EXISTS $UdinraWooShop (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			product_id bigint(20) NOT NULL,
			price decimal(5,2),
			sales int(11),
			rating decimal(3,2),
			count int(11),
			review int(11),
			loaded int(11),
			PRIMARY KEY  (id)
			) $udinra_charset_collate;";
	dbDelta( $udinra_woo_sql );
}

function udinra_woo_shop_delete() {
	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
	delete_option( "udinra_wooshop_db_version");	
	delete_option('udinra_wooshop_filter_purchase');
	delete_option('udinra_wooshop_filter_loaded');
	delete_option('udinra_wooshop_filter_count');
	delete_option('udinra_wooshop_filter_show');
	delete_option('udinra_wooshop_filter_rating');
	delete_option('udinra_wooshop_filter_review');	
	delete_option('udinra_wooshop_filter_sort');
	delete_option('udinra_wooshop_filter_image');
	$udinra_woo_sql = "DROP TABLE IF EXISTS $UdinraWooShop;";
    $wpdb->query($udinra_woo_sql);
}

function udinra_wooshop_populate_db() {
	global $wpdb;
   $udinra_woo_sql = "SELECT id FROM $wpdb->posts 
						WHERE post_type = 'product'
						AND post_status = 'publish'";
   $udinra_wooshop_id_lists = $wpdb->get_results($udinra_woo_sql);
   foreach ($udinra_wooshop_id_lists as $udinra_wooshop_id_list) { 
		$udinra_wooshop_id            = $udinra_wooshop_id_list->id;
		$udinra_wooshop_price         = udinra_wooshop_price_func($udinra_wooshop_id);
		$udinra_wooshop_rating        = udinra_wooshop_rating_func($udinra_wooshop_id);
		$udinra_wooshop_count         = udinra_wooshop_count_func($udinra_wooshop_id);		
		$udinra_wooshop_review        = udinra_wooshop_review_func($udinra_wooshop_id);
		$udinra_wooshop_sales         = udinra_wooshop_sale_func($udinra_wooshop_id);
		udinra_woo_shop_insert($udinra_wooshop_id,$udinra_wooshop_count,$udinra_wooshop_price,$udinra_wooshop_rating,$udinra_wooshop_review,$udinra_wooshop_sales);
   }
}

function udinra_wooshop_price_func($udinra_wooshop_id) {
	global $wpdb;
	$udinra_woo_sql = "SELECT pm.meta_value
						FROM $wpdb->posts p 
						INNER JOIN $wpdb->postmeta pm 
						ON p.id = pm.post_id 
						WHERE p.post_type = 'product'
						AND meta_key = '_regular_price'
						AND p.id = $udinra_wooshop_id
						LIMIT 1";
	return $wpdb->get_var($udinra_woo_sql);	
}

function udinra_wooshop_rating_func($udinra_wooshop_id) {
	global $wpdb;
	$udinra_woo_sql = "SELECT pm.meta_value
						FROM $wpdb->posts p 
						INNER JOIN $wpdb->postmeta pm 
						ON p.id = pm.post_id 
						WHERE p.post_type = 'product'
						AND meta_key = '_wc_average_rating'
						AND p.id = $udinra_wooshop_id
						LIMIT 1";
	if($wpdb->get_var($udinra_woo_sql) == NULL) {
		return 0;
	}
	else {
		return $wpdb->get_var($udinra_woo_sql);
	}
}

function udinra_wooshop_count_func($udinra_wooshop_id) {
	global $wpdb;
	$udinra_woo_sql = "SELECT COUNT(cm.comment_id)
						FROM $wpdb->comments c
						INNER JOIN $wpdb->commentmeta cm 
						ON c.comment_ID = cm.comment_id
						INNER JOIN $wpdb->posts p
						ON p.ID = c.comment_post_ID
						WHERE p.post_type = 'product' 
						AND cm.meta_key = 'rating'
						AND p.id = $udinra_wooshop_id
						";
	if($wpdb->get_var($udinra_woo_sql) == NULL) {
		return 0;
	}
	else {
		return $wpdb->get_var($udinra_woo_sql);
	}
}

function udinra_wooshop_review_func($udinra_wooshop_id) {
	global $wpdb;
	$udinra_woo_sql = "SELECT COUNT(pm.comment_ID)
						FROM $wpdb->posts p 
						INNER JOIN $wpdb->comments pm 
						ON p.id = pm.comment_post_ID
						WHERE p.post_type = 'product'
						AND p.id = $udinra_wooshop_id
						LIMIT 1";
	if($wpdb->get_var($udinra_woo_sql) == NULL) {
		return 0;
	}
	else {
		return $wpdb->get_var($udinra_woo_sql);
	}
}

function udinra_wooshop_sale_func($udinra_wooshop_id) {
	global $wpdb;
	$udinra_woo_sql = "SELECT pm.meta_value
						FROM $wpdb->posts p 
						INNER JOIN $wpdb->postmeta pm 
						ON p.id = pm.post_id 
						WHERE p.post_type = 'product'
						AND meta_key = 'total_sales'
						AND p.id = $udinra_wooshop_id
						LIMIT 1";
	if($wpdb->get_var($udinra_woo_sql) == NULL) {
		return 0;
	}
	else {
		return $wpdb->get_var($udinra_woo_sql);
	}
}

function udinra_woo_shop_insert($product_id,$count,$price,$rating,$review,$sales) {
	global $wpdb;
   $UdinraWooShop = $wpdb->prefix . 'udwooshop';
   $wpdb->insert( 
		$UdinraWooShop, 
		array( 
			'product_id' => $product_id, 
			'price' => $price,
			'rating' => $rating, 
			'review' => $review, 
			'count' => $count,
			'sales' => $sales,
			'loaded' => 0	
		) 
	);
}

function udinra_wooshop_check_db() {
	global $wpdb;
   $udinra_woo_sql = "SELECT id FROM $wpdb->posts 
						WHERE post_type = 'product' 
						AND post_status = 'publish'";
   $udinra_wooshop_id_lists = $wpdb->get_results($udinra_woo_sql);
   foreach ($udinra_wooshop_id_lists as $udinra_wooshop_id_list) { 
		$udinra_wooshop_id = $udinra_wooshop_id_list->id;
		udinra_wooshop_check_id($udinra_wooshop_id);
   }
}

function udinra_wooshop_check_id($udinra_wooshop_id) {
	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
	$udinra_woo_sql = "SELECT product_id
						FROM $UdinraWooShop eshop 
						WHERE eshop.product_id = $udinra_wooshop_id
						LIMIT 1";
	$udinra_wooshop_product_id = $wpdb->get_var($udinra_woo_sql);

	if ($udinra_wooshop_product_id > 0) {
		$udinra_wooshop_price         = udinra_wooshop_price_func($udinra_wooshop_id);		
		$udinra_wooshop_rating        = udinra_wooshop_rating_func($udinra_wooshop_id);
		$udinra_wooshop_count         = udinra_wooshop_count_func($udinra_wooshop_id);
		$udinra_wooshop_review        = udinra_wooshop_review_func($udinra_wooshop_id);
		$udinra_wooshop_sales         = udinra_wooshop_sale_func($udinra_wooshop_id);
		udinra_woo_shop_update($udinra_wooshop_id,$udinra_wooshop_count,$udinra_wooshop_price,$udinra_wooshop_rating,$udinra_wooshop_review,$udinra_wooshop_sales);
	}
	else {
		$udinra_wooshop_price         = udinra_wooshop_price_func($udinra_wooshop_id);		
		$udinra_wooshop_rating        = udinra_wooshop_rating_func($udinra_wooshop_id);
		$udinra_wooshop_count         = udinra_wooshop_count_func($udinra_wooshop_id);		
		$udinra_wooshop_review        = udinra_wooshop_review_func($udinra_wooshop_id);
		$udinra_wooshop_sales         = udinra_wooshop_sale_func($udinra_wooshop_id);
		udinra_woo_shop_insert($udinra_wooshop_id,$udinra_wooshop_count,$udinra_wooshop_price,$udinra_wooshop_rating,$udinra_wooshop_review,$udinra_wooshop_sales);
	}
}

function udinra_woo_shop_update($product_id,$count,$price,$rating,$review,$sales) {
	global $wpdb;
   $UdinraWooShop = $wpdb->prefix . 'udwooshop';
   $wpdb->update( 
		$UdinraWooShop, 
		array( 
			'price' => $price,
			'rating' => $rating, 
			'count' => $count, 
			'review' => $review, 
			'sales' => $sales,
			'loaded' => 0	
		),
		array(
		'product_id' => $product_id	
		)	
	);
}

function udinra_wooshop_delete_id() {
	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
    $udinra_woo_sql = "SELECT product_id FROM $UdinraWooShop
						WHERE NOT EXISTS (
							SELECT 1 FROM $wpdb->posts
							WHERE id = product_id 
							AND post_type = 'product'
							AND post_status = 'publish'
							)";
   $udinra_wooshop_id_lists = $wpdb->get_results($udinra_woo_sql);
   foreach ($udinra_wooshop_id_lists as $udinra_wooshop_id_list) { 
		$udinra_wooshop_id = $udinra_wooshop_id_list->product_id;
		$wpdb->delete($UdinraWooShop,array('product_id' => $udinra_wooshop_id));
   }
}

function udinra_wooshop_check_prev() {
	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
	$udinra_wooshop_filter_loaded = get_option('udinra_wooshop_filter_loaded');
	$udinra_wooshop_filter_loaded = $udinra_wooshop_filter_loaded + 1;
    $udinra_woo_sql = "SELECT 1 AS maxloaded FROM $UdinraWooShop WHERE loaded = $udinra_wooshop_filter_loaded LIMIT 1";
	$udinra_wooshop_max_loaded = $wpdb->get_var($udinra_woo_sql);
	if($udinra_wooshop_max_loaded == 1){
		return 1;
	}
	else {
		return 0;
	}
}

function udinra_wooshop_get_rating($udinra_wooshop_id) {
	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
	$udinra_woo_sql = "SELECT rating
						FROM $UdinraWooShop 
						WHERE product_id = $udinra_wooshop_id
						AND rating IS NOT NULL";
	return $wpdb->get_var($udinra_woo_sql);
}

function udinra_wooshop_get_count($udinra_wooshop_id) {
	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
	$udinra_woo_sql = "SELECT count
						FROM $UdinraWooShop 
						WHERE product_id = $udinra_wooshop_id
						AND count IS NOT NULL";
	return $wpdb->get_var($udinra_woo_sql);
}

function udinra_wooshop_get_review($udinra_wooshop_id) {
	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
	$udinra_woo_sql = "SELECT review
						FROM $UdinraWooShop 
						WHERE product_id = $udinra_wooshop_id
						AND review IS NOT NULL";
	return $wpdb->get_var($udinra_woo_sql);
}


function udinra_wooshop_fetch_downloads($udinra_woo_shop_sql,&$udinra_woo_shop_counter,$udinra_woo_shop_limit) {
	global $wpdb;
   $udinra_final_html = '';
   $udinra_download_html = '';
   $udinra_purchase_html = '';
   $udinra_wooshop_filter_image = get_option('udinra_wooshop_filter_image');
   $udinra_wooshop_filter_purchase = get_option('udinra_wooshop_filter_purchase');
   $udinra_wooshop_filter_show = get_option('udinra_wooshop_filter_show');
   $UdinraWooShop = $wpdb->prefix . 'udwooshop';
   $udinra_wooshop_download_lists = $wpdb->get_results($udinra_woo_shop_sql);
   $udinra_update_query = '';
   $udinra_img_container = '<div class="w3-card-4">';
   $udinra_other_container = '<div class="w3-container">';
   $udinra_row_container = '<div class="w3-row w3-padding">';
   $udinra_row_counter = 1;
   
   if($udinra_wooshop_filter_image == 'medium'){
		$udinra_div_html = '<div class="w3-third w3-container">';	   
		$udinra_wddshop_per_row = 3;
   }
   else {
		$udinra_div_html = '<div class="w3-quarter w3-container">';
		$udinra_wddshop_per_row = 4;
   }
   foreach ($udinra_wooshop_download_lists as $udinra_wooshop_download_list) { 
		
		if (has_post_thumbnail($udinra_wooshop_download_list->product_id)) {
			if($udinra_wooshop_filter_image == 'medium'){
				$udinra_download_html = $udinra_img_container . '<a href="' . get_permalink($udinra_wooshop_download_list->product_id) . 
										'" title="' . get_the_title($udinra_wooshop_download_list->product_id) . '">' .
										get_the_post_thumbnail($udinra_wooshop_download_list->product_id)  .
										'</a>' . $udinra_other_container;
			}
			else{
				$udinra_download_html = $udinra_img_container . '<a href="' . get_permalink($udinra_wooshop_download_list->product_id) . 
										'" title="' . get_the_title($udinra_wooshop_download_list->product_id) . '">' .
										get_the_post_thumbnail($udinra_wooshop_download_list->product_id)  .
										'</a>' . $udinra_other_container;	
			}
		}
		else {
			if($udinra_wooshop_filter_image == 'medium'){
				$udinra_download_html = $udinra_img_container . '<a href="' . get_permalink($udinra_wooshop_download_list->product_id) . 
										'" title="' . get_the_title($udinra_wooshop_download_list->product_id) . '">' .
										'<img src="' . plugins_url( 'image/udimage.jpg', dirname(__FILE__) ) . '" > '  .
										'</a>' . $udinra_other_container;				
			}
			else{
				$udinra_download_html = $udinra_img_container . '<a href="' . get_permalink($udinra_wooshop_download_list->product_id) . 
										'" title="' . get_the_title($udinra_wooshop_download_list->product_id) . '">' .
										'<img src="' . plugins_url( 'image/udimage.jpg', dirname(__FILE__) ) . '" > '  .
										'</a>' . $udinra_other_container;				
			}
		}
		if($udinra_wooshop_filter_show == 1){
			$udinra_download_html .= '<a href="' . get_permalink($udinra_wooshop_download_list->product_id) . '" title="' . 
									get_the_title($udinra_wooshop_download_list->product_id) . '"><b>' . 
									get_the_title($udinra_wooshop_download_list->product_id) . '</b></a>';	
		}
		if($udinra_wooshop_filter_show == 2){
			$udinra_download_html .= '<span class="rating-static rating-' . ( round(udinra_wooshop_get_rating($udinra_wooshop_download_list->product_id),1) * 10 ) .'"></span>';
			$udinra_download_html .=  udinra_wooshop_get_count($udinra_wooshop_download_list->product_id) . ' ratings ';
		}
		if($udinra_wooshop_filter_show == 3){
			$udinra_download_html .= udinra_wooshop_get_review($udinra_wooshop_download_list->product_id) . ' reviews ';				
		}
		if($udinra_wooshop_filter_show == 4){
			$udinra_download_html .= '<a href="' . get_permalink($udinra_wooshop_download_list->product_id) . '" title="' . 
									get_the_title($udinra_wooshop_download_list->product_id) . '">' . 
									get_the_title($udinra_wooshop_download_list->product_id) . '</a> ';	
			$udinra_download_html .= '<span class="rating-static rating-' . ( round(udinra_wooshop_get_rating($udinra_wooshop_download_list->product_id),1) * 10 ) .'"></span>';
			$udinra_download_html .=  udinra_wooshop_get_count($udinra_wooshop_download_list->product_id) . ' ratings ';
		}
		if($udinra_wooshop_filter_show == 5){
			$udinra_download_html .= '<a href="' . get_permalink($udinra_wooshop_download_list->product_id) . '" title="' . 
									get_the_title($udinra_wooshop_download_list->product_id) . '">' . 
									get_the_title($udinra_wooshop_download_list->product_id) . '</a> ';	
			$udinra_download_html .=  udinra_wooshop_get_review($udinra_wooshop_download_list->product_id) . ' reviews ';		
		}
		if($udinra_wooshop_filter_show == 6){
			$udinra_download_html .= '<span class="rating-static rating-' . ( round(udinra_wooshop_get_rating($udinra_wooshop_download_list->product_id),1) * 10 ) .'"></span>';
			$udinra_download_html .=  udinra_wooshop_get_count($udinra_wooshop_download_list->product_id) . ' ratings & ' . 
									  udinra_wooshop_get_review($udinra_wooshop_download_list->product_id) . ' reviews ';
		}
		if($udinra_wooshop_filter_show == 7){
			$udinra_download_html .= '<a href="' . get_permalink($udinra_wooshop_download_list->product_id) . '" title="' . 
									get_the_title($udinra_wooshop_download_list->product_id) . '"><b>' . 
									get_the_title($udinra_wooshop_download_list->product_id) . '</b></a> ';	
			$udinra_download_html .= '<span class="rating-static rating-' . ( round(udinra_wooshop_get_rating($udinra_wooshop_download_list->product_id),1) * 10 ) .'"></span>';
			$udinra_download_html .=  udinra_wooshop_get_count($udinra_wooshop_download_list->product_id) . ' ratings & ' . 
									  udinra_wooshop_get_review($udinra_wooshop_download_list->product_id) . ' reviews ';
		}
		if($udinra_wooshop_filter_purchase == 'true'){
			$udinra_purchase_html = '[add_to_cart id="' . $udinra_wooshop_download_list->product_id . '" ]';
			$udinra_download_html .= do_shortcode($udinra_purchase_html) . '</div>' . '</div>' ;						 
		}
		else{
			$udinra_download_html .= '</div>' . '</div>' ;
		}
		
		$udinra_woo_shop_counter = $udinra_woo_shop_counter + 1;
		if($udinra_woo_shop_counter <= $udinra_woo_shop_limit){
			if($udinra_row_counter == 1){
				$udinra_final_html .= $udinra_row_container . $udinra_div_html . $udinra_download_html . '</div>';
			}
			else {
				if($udinra_row_counter > 0 && ($udinra_row_counter % $udinra_wddshop_per_row == 0)) {
					$udinra_final_html .= $udinra_div_html . $udinra_download_html . '</div></div>' . $udinra_row_container;
				}
				if($udinra_row_counter > 0 && ($udinra_row_counter % $udinra_wddshop_per_row != 0)){
					$udinra_final_html .= $udinra_div_html . $udinra_download_html . '</div>';
				}	
			}
			$udinra_row_counter = $udinra_row_counter + 1;
			
			$udinra_wooshop_filter_loaded = get_option('udinra_wooshop_filter_loaded');
			$wpdb->update( 
				$UdinraWooShop, 
				array( 
					'loaded' => $udinra_wooshop_filter_loaded
				),
				array( 
					'product_id' => $udinra_wooshop_download_list->product_id
				)	
			);
		}
		$udinra_download_html = '';
   }
    return $udinra_final_html;
}

?>