<?php

function udinra_woo_shop_install(){
	udinra_woo_shop_create();
	udinra_wooshop_populate_db();
}

function udinra_woo_shop_refresh(){
	udinra_wooshop_delete_id();
	udinra_wooshop_check_db();
}

function udinra_woo_shop_uninstall(){
	udinra_woo_shop_delete();
}
function udinra_wooshop_call_init($udinra_wooshop_filter_sort){
	$udinra_woo_shop_begin = '<form id="udwooform" class="w3-container">' . '<div class="w3-row w3-padding">';
	$udinra_div_html = '<div class="w3-third w3-container">';
	$udinra_div_html_container = '<div class="w3-row w3-padding">';
	$udinra_div_html_button = '<div class="w3-half w3-container">';
	$udinra_div_loading_html = '<div id="loadingmessage" class="w3-container" style="display:none;">' . 
								'<img style="padding-left:40%;padding-top:5%;" src="' . plugins_url( 'image/loader.svg', dirname(__FILE__) ) . '" > ' .
								'</div>' ;
	$udinra_woo_shop_end = '<input name="udinradec" id="udinradec" type="hidden" />' .
						   '<input name="action" type="hidden" value="udinra_wooshop_hook" />&nbsp;' . '</form>';	
	$udinra_woo_shop_sort = '<select class="UdinraSelect" id="udwoosort" name="udwoosort" onchange="udwooajax();" style="width:100%">' . udinra_wooshop_get_sort() . '</select>';		

	$udinra_woo_shop_limit = get_option('udinra_wooshop_filter_count');
	$udinra_woo_shop_counter = 0;

	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
	$udinra_woo_shop_order = udinra_wooshop_set_order($udinra_wooshop_filter_sort);
	$udinra_woo_shop_limit = $udinra_woo_shop_limit + 1;
	$udinra_woo_shop_sql = "SELECT  product_id
							FROM $UdinraWooShop 
							WHERE loaded = 0
							$udinra_woo_shop_order LIMIT $udinra_woo_shop_limit";		
	$udinra_woo_shop_limit = $udinra_woo_shop_limit -1;
	$udinra_woo_shop_downloads = udinra_wooshop_fetch_downloads($udinra_woo_shop_sql,$udinra_woo_shop_counter,$udinra_woo_shop_limit);	

	if($udinra_woo_shop_counter <= $udinra_woo_shop_limit){
		$udinra_button_next_html = '<button class="w3-button w3-ripple w3-teal" style="align:right;width:50%" onclick="udwoonext();" disabled>Show More</button>';
		$udinra_button_prev_html = '<button class="w3-button w3-ripple w3-indigo" style="align:left;width:50%" onclick="udwooprev();" disabled>Show Previous</button>';
	}
	else {
		$udinra_button_next_html = '<button class="w3-button w3-ripple w3-teal" style="align:right;width:50%" onclick="udwoonext();">Show More</button>';
		$udinra_button_prev_html = '<button class="w3-button w3-ripple w3-indigo" style="align:left;width:50%" onclick="udwooprev();" disabled>Show Previous</button>';
	}

	$udinra_woo_shop_html = $udinra_woo_shop_begin . $udinra_div_html . $udinra_woo_shop_sort . 
							'</div>' . '</div>' . $udinra_woo_shop_end . $udinra_div_loading_html . 
							'<div id="udwoo_response" class="w3-container">' . $udinra_woo_shop_downloads . $udinra_div_html_container .  
							$udinra_div_html_button . $udinra_button_prev_html . '</div>' . $udinra_div_html_button . 
							$udinra_button_next_html . '</div></div></div>';
	return	$udinra_woo_shop_html;
}
function udinra_wooshop_call_refresh($udinra_wooshop_filter_sort,$udinra_wooshop_filter_category,$udinra_wooshop_filter_tag){
	$udinra_div_html_container = '<div class="w3-row">';
	$udinra_div_html_button = '<div class="w3-half w3-container">';
	$udinra_woo_shop_limit = get_option('udinra_wooshop_filter_count');
	$udinra_woo_shop_counter = 0;

	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
	$udinra_woo_shop_limit = $udinra_woo_shop_limit + 1;
	$udinra_woo_shop_order = udinra_wooshop_set_order($udinra_wooshop_filter_sort);
	$udinra_woo_shop_sql = "SELECT  product_id  
							FROM $UdinraWooShop 
							WHERE loaded = 0
							$udinra_woo_shop_order LIMIT $udinra_woo_shop_limit";		
	
	$udinra_woo_shop_limit = $udinra_woo_shop_limit - 1;
	$udinra_woo_shop_downloads = udinra_wooshop_fetch_downloads($udinra_woo_shop_sql,$udinra_woo_shop_counter,$udinra_woo_shop_limit);	
	if($udinra_woo_shop_counter <= $udinra_woo_shop_limit){
		$udinra_button_next_html = '<button class="w3-button w3-ripple w3-teal" style="align:right;width:50%" onclick="udwoonext();" disabled>Show More</button>';
		$udinra_button_prev_html = '<button class="w3-button w3-ripple w3-indigo" style="align:left;width:50%" onclick="udwooprev();" disabled>Show Previous</button>';
	}
	else {
		$udinra_button_next_html = '<button class="w3-button w3-ripple w3-teal" style="align:right;width:50%" onclick="udwoonext();">Show More</button>';
		$udinra_button_prev_html = '<button class="w3-button w3-ripple w3-indigo" style="align:left;width:50%" onclick="udwooprev();" disabled>Show Previous</button>';
	}

	$udinra_woo_shop_html = $udinra_woo_shop_downloads . '</div>' . $udinra_div_html_container .  
							$udinra_div_html_button . $udinra_button_prev_html . '</div>' . 
							$udinra_div_html_button . $udinra_button_next_html . '</div></div>';
	
	return	$udinra_woo_shop_html;
}
function udinra_wooshop_call_next($udinra_wooshop_filter_sort){
	$udinra_div_html_container = '<div class="w3-row">';
	$udinra_div_html_button = '<div class="w3-half w3-container">';
	$udinra_woo_shop_limit = get_option('udinra_wooshop_filter_count');
	$udinra_woo_shop_counter = 0;
	$udinra_wooshop_filter_sort = get_option('udinra_wooshop_filter_sort');
	$udinra_woo_shop_order = udinra_wooshop_set_order($udinra_wooshop_filter_sort);

	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
	$udinra_wooshop_prev_true = udinra_wooshop_check_prev();
	$udinra_woo_shop_limit = $udinra_woo_shop_limit + 1;
	$udinra_wooshop_filter_loaded = get_option('udinra_wooshop_filter_loaded');
	$udinra_wooshop_filter_loaded = $udinra_wooshop_filter_loaded + 1;
	update_option('udinra_wooshop_filter_loaded',$udinra_wooshop_filter_loaded);

	if($udinra_wooshop_prev_true == 1){
		$udinra_woo_shop_sql = "SELECT  product_id  
								FROM $UdinraWooShop 
								WHERE loaded = $udinra_wooshop_filter_loaded
								$udinra_woo_shop_order LIMIT $udinra_woo_shop_limit";		
	}
	else{
		$udinra_woo_shop_sql = "SELECT  product_id 
								FROM $UdinraWooShop 
								WHERE loaded = 0
								$udinra_woo_shop_order LIMIT $udinra_woo_shop_limit";		
	}
	$udinra_woo_shop_limit = $udinra_woo_shop_limit - 1;
	$udinra_woo_shop_downloads = udinra_wooshop_fetch_downloads($udinra_woo_shop_sql,$udinra_woo_shop_counter,$udinra_woo_shop_limit);	
	
	if($udinra_wooshop_prev_true == 0) {
		$udinra_woo_shop_sql = "SELECT  COUNT(product_id) AS remaincount
								FROM $UdinraWooShop 
								WHERE loaded = 0
								$udinra_woo_shop_order LIMIT $udinra_woo_shop_limit";
		$udinra_woo_shop_counter = $wpdb->get_var($udinra_woo_shop_sql);
		
		if($udinra_woo_shop_counter > 0){
			$udinra_button_next_html = '<button class="w3-button w3-ripple w3-teal" style="align:right;width:50%" onclick="udwoonext();" enabled>Show More</button>';
			$udinra_button_prev_html = '<button class="w3-button w3-ripple w3-indigo" style="align:left;width:50%" onclick="udwooprev();" enabled>Show Previous</button>';		
		}
		else {
			$udinra_button_next_html = '<button class="w3-button w3-ripple w3-teal" style="align:right;width:50%" onclick="udwoonext();" disabled>Show More</button>';
			$udinra_button_prev_html = '<button class="w3-button w3-ripple w3-indigo" style="align:left;width:50%" onclick="udwooprev();" enabled>Show Previous</button>';					
		}
	}
	if($udinra_wooshop_prev_true == 1){
		$udinra_woo_shop_sql = "SELECT  COUNT(product_id) AS remaincount
								FROM $UdinraWooShop 
								WHERE loaded = $udinra_wooshop_filter_loaded - 1
								$udinra_woo_shop_order LIMIT $udinra_woo_shop_limit";		
		$udinra_woo_shop_counter = $wpdb->get_var($udinra_woo_shop_sql);
		
		if($udinra_woo_shop_counter > 0){
			$udinra_button_prev_html = '<button class="w3-button w3-ripple w3-indigo" style="align:left;width:50%" onclick="udwooprev();" enabled>Show Previous</button>';
		}
		else {
			$udinra_button_prev_html = '<button class="w3-button w3-ripple w3-indigo" style="align:left;width:50%" onclick="udwooprev();" disabled>Show Previous</button>';
		}
		$udinra_woo_shop_sql = "SELECT  COUNT(product_id) AS remaincount
								FROM $UdinraWooShop 
								WHERE (loaded = 0 OR loaded = $udinra_wooshop_filter_loaded + 1)
								$udinra_woo_shop_order LIMIT $udinra_woo_shop_limit";		
		$udinra_woo_shop_counter = $wpdb->get_var($udinra_woo_shop_sql);
		
		if($udinra_woo_shop_counter > 0){
			$udinra_button_next_html = '<button class="w3-button w3-ripple w3-teal" style="align:right;width:50%" onclick="udwoonext();" enabled>Show More</button>';
		}
		else {
			$udinra_button_next_html = '<button class="w3-button w3-ripple w3-teal" style="align:right;width:50%" onclick="udwoonext();" disabled>Show More</button>';
		}
	}
	$udinra_woo_shop_html = $udinra_woo_shop_downloads . '</div>' . $udinra_div_html_container .  $udinra_div_html_button .
							$udinra_button_prev_html . '</div>' . $udinra_div_html_button . 
							$udinra_button_next_html . '</div></div>';
	return	$udinra_woo_shop_html;
}
function udinra_wooshop_call_prev($udinra_wooshop_filter_sort){
	$udinra_div_html_container = '<div class="w3-row">';
	$udinra_div_html_button = '<div class="w3-half w3-container">';
	$udinra_woo_shop_limit = get_option('udinra_wooshop_filter_count');
	$udinra_woo_shop_counter = 0;
	$udinra_wooshop_filter_sort = get_option('udinra_wooshop_filter_sort');	
	$udinra_woo_shop_order = udinra_wooshop_set_order($udinra_wooshop_filter_sort);

	global $wpdb;
	$UdinraWooShop = $wpdb->prefix . 'udwooshop';
	$udinra_woo_shop_limit = $udinra_woo_shop_limit + 1;
	$udinra_wooshop_filter_loaded = get_option('udinra_wooshop_filter_loaded');
	$udinra_wooshop_filter_loaded = $udinra_wooshop_filter_loaded - 1;
	update_option('udinra_wooshop_filter_loaded',$udinra_wooshop_filter_loaded);

	if($udinra_wooshop_filter_loaded == 1){
		$udinra_button_next_html = '<button class="w3-button w3-ripple w3-teal" style="align:right;width:50%" onclick="udwoonext();" enabled>Show More</button>';
		$udinra_button_prev_html = '<button class="w3-button w3-ripple w3-indigo" style="align:left;width:50%" onclick="udwooprev();" disabled>Show Previous</button>';
	}
	else {
		$udinra_button_next_html = '<button class="w3-button w3-ripple w3-teal" style="align:right;width:50%" onclick="udwoonext();" enabled>Show More</button>';
		$udinra_button_prev_html = '<button class="w3-button w3-ripple w3-indigo" style="align:left;width:50%" onclick="udwooprev();" enabled>Show Previous</button>';
	}
	
	$udinra_woo_shop_sql = "SELECT  product_id 
							FROM $UdinraWooShop 
							WHERE loaded = $udinra_wooshop_filter_loaded
							$udinra_woo_shop_order LIMIT $udinra_woo_shop_limit";		

	$udinra_woo_shop_limit = $udinra_woo_shop_limit - 1;
	$udinra_woo_shop_downloads = udinra_wooshop_fetch_downloads($udinra_woo_shop_sql,$udinra_woo_shop_counter,$udinra_woo_shop_limit);	

	$udinra_woo_shop_html = $udinra_woo_shop_downloads . '</div>' . $udinra_div_html_container .  $udinra_div_html_button .
							$udinra_button_prev_html . '</div>' . $udinra_div_html_button . 
							$udinra_button_next_html . '</div></div>';
	return	$udinra_woo_shop_html;
}
function udinra_wooshop_get_downloads($udinra_woo_shop_call,$udinra_wooshop_filter_sort){
	$udinra_woo_shop_html = '';
	if($udinra_woo_shop_call == 0){
		global $wpdb;
		$UdinraWooShop = $wpdb->prefix . 'udwooshop';
		$udinra_wooshop_update_query = "UPDATE $UdinraWooShop SET loaded = 0 WHERE loaded > 0";
		$udinra_wooshop_update_result = $wpdb->query($udinra_wooshop_update_query);
		$udinra_woo_shop_html = udinra_wooshop_call_init($udinra_wooshop_filter_sort);
		return $udinra_woo_shop_html;
	}
	if($udinra_woo_shop_call == 1){
		global $wpdb;
		$UdinraWooShop = $wpdb->prefix . 'udwooshop';
		$udinra_wooshop_update_query = "UPDATE $UdinraWooShop SET loaded = 0 WHERE loaded > 0";
		$udinra_wooshop_update_result = $wpdb->query($udinra_wooshop_update_query);
		$udinra_woo_shop_html = udinra_wooshop_call_refresh($udinra_wooshop_filter_sort);
		return $udinra_woo_shop_html;
	}
	if($udinra_woo_shop_call == 2){
		$udinra_woo_shop_html = udinra_wooshop_call_next($udinra_wooshop_filter_sort);
		return $udinra_woo_shop_html;
	}
	if($udinra_woo_shop_call == 3){
		$udinra_woo_shop_html = udinra_wooshop_call_prev($udinra_wooshop_filter_sort);
		return $udinra_woo_shop_html;
	}
}

function udinra_wooshop_get_sort(){
	$udinra_wooshop_sort =	'<option value="default">Order By</option>' . 
							'<option value="sales">Popularity</option>' .
							'<option value="rated">Highest Rated</option>' .
							'<option value="review">Most Reviewed</option>' .
							'<option value="highprice">Price High</option>' .
							'<option value="lowprice">Price Low</option>' .
							'<option value="newest">Newest</option>' .
							'<option value="oldest">Oldest</option>' ;
	return $udinra_wooshop_sort;
}

function udinra_wooshop_set_order($udinra_wooshop_filter_sort){
	$udinra_woo_sql_order = '';
	if($udinra_wooshop_filter_sort == 1){
		$udinra_woo_sql_order = ' ORDER BY product_id DESC';
	}
	if($udinra_wooshop_filter_sort == 2){
		$udinra_woo_sql_order = ' ORDER BY product_id ASC';
	}
	if($udinra_wooshop_filter_sort == 3){
		$udinra_woo_sql_order = ' ORDER BY price ASC';
	}
	if($udinra_wooshop_filter_sort == 4){
		$udinra_woo_sql_order = ' ORDER BY price DESC';
	}
	if($udinra_wooshop_filter_sort == 5){
		$udinra_woo_sql_order = ' ORDER BY rating DESC';
	}
	if($udinra_wooshop_filter_sort == 6){
		$udinra_woo_sql_order = ' ORDER BY sales DESC';
	}
	if($udinra_wooshop_filter_sort == 7){
		$udinra_woo_sql_order = ' ORDER BY review DESC';
	}
	return $udinra_woo_sql_order;
}

?>