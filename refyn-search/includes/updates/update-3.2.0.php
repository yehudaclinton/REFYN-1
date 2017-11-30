<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
@set_time_limit(86400);
@ini_set("memory_limit","1000M");
add_option( 'refyn_search_exclude_out_stock', 'yes' );
add_option( 'refyn_search_cache_timeout', 1 );
add_option( 'refyn_search_is_debug', 'no' );
global $refyn_search;
$refyn_search->install_databases();
global $refyn_synch;
$refyn_synch->migrate_products_out_of_stock();
