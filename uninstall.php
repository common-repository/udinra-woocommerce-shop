<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

udinra_uninstall_wooshop_plugin();

function udinra_uninstall_wooshop_plugin () {
	udinra_delete_wooshop_options();
}

function udinra_delete_wooshop_options () {
	udinra_woo_shop_uninstall();
}

include 'db/udinra-wooshop-call-func.php';
include 'db/udinra-wooshop-db-func.php';

?>