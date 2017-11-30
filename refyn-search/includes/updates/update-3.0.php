<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
@set_time_limit(86400);
@ini_set("memory_limit","640M");
global $wpdb;
global $refyn_search;
$refyn_search->install_databases();
global $refyn_synch;
$refyn_synch->synch_full_database();
global $refyn_exclude_data;
$refyn_search_exclude_products = get_option( 'refyn_search_exclude_products', array() );
if ( is_array( $refyn_search_exclude_products ) && count( $refyn_search_exclude_products ) > 0 ) {
	foreach ( $refyn_search_exclude_products as $object_id ) {
		$refyn_exclude_data->insert_item( $object_id, 'product' );
	}
}
global $refyn_search_admin_init;
$refyn_search_admin_init->set_default_settings();
flush_rewrite_rules();
