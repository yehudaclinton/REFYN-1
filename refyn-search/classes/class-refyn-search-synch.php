<?php
/* "Copyright 2012 REFYN.org" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
class Refyn_Search_Synch
{
	public function __construct() {
		// Synch for post
		add_action( 'save_post', array( $this, 'synch_save_post' ), 102, 2 );
		add_action( 'delete_post', array( $this, 'synch_delete_post' ) );
		/*
		 *
		 * Synch for custom mysql query from 3rd party plugin
		 * Call below code on 3rd party plugin when create post by mysql query
		 * do_action( 'mysql_inserted_post', $post_id );
		 */
		add_action( 'mysql_inserted_post', array( $this, 'synch_mysql_inserted_post' ) );
	}
	public function migrate_posts() {
		global $wpdb;
		global $refyn_posts_data;
		global $refyn_product_sku_data;
		// Empty all tables
		$refyn_posts_data->empty_table();
		$refyn_product_sku_data->empty_table();
		$post_types = apply_filters( 'refyn_search_post_types_support', array( 'post', 'page', 'product' ) );
		$all_posts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID, post_title, post_type FROM {$wpdb->posts} WHERE post_status = %s AND post_type IN ('". implode("','", $post_types ) ."')" , 'publish'
			)
		);
		if ( $all_posts ) {
			foreach ( $all_posts as $item ) {
				$post_id       = $item->ID;
				$refyn_posts_data->insert_item( $post_id, $item->post_title, $item->post_type );
				if ( 'product' == $item->post_type ) {
					$sku = get_post_meta( $post_id, '_sku', true );
					if ( ! empty( $sku ) && '' != trim( $sku ) ) {
						$refyn_product_sku_data->insert_item( $post_id, $sku );
					}
				}
			}
		}
	}
	public function synch_full_database() {
		$this->migrate_posts();
	}
	public function delete_post_data( $post_id ) {
		global $refyn_posts_data;
		global $refyn_product_sku_data;
		$refyn_posts_data->delete_item( $post_id );
		$refyn_product_sku_data->delete_item( $post_id );
	}
	public function synch_save_post( $post_id, $post ) {
		global $wpdb;
		global $refyn_posts_data;
		global $refyn_product_sku_data;
		$this->delete_post_data( $post_id );
		if ( 'publish' == $post->post_status ) {
			$refyn_posts_data->update_item( $post_id, $post->post_title, $post->post_type );
			if ( 'page' == $post->post_type ) {
				global $refyn_search_page_id;
				// flush rewrite rules if page is editing is Refyn Search Result page
				if ( $post_id == $refyn_search_page_id ) {
					flush_rewrite_rules();
				}
			}
		}
	}
	public function synch_delete_post( $post_id ) {
		global $refyn_exclude_data;
		$this->delete_post_data( $post_id );
		$post_type = get_post_type( $post_id );
		$refyn_exclude_data->delete_item( $post_id, $post_type );
	}
	public function synch_mysql_inserted_post( $post_id = 0 ) {
		if ( $post_id < 1 ) return;
		global $wpdb;
		$post_types = apply_filters( 'refyn_search_post_types_support', array( 'post', 'page', 'product' ) );
		$item = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->posts} WHERE ID = %d AND post_status = %s AND post_type IN ('". implode("','", $post_types ) ."')" , $post_id, 'publish'
			)
		);
		if ( $item ) {
			global $refyn_posts_data;
			global $refyn_product_sku_data;
			$refyn_posts_data->insert_item( $post_id, $item->post_title, $item->post_type );
			if ( 'product' == $item->post_type ) {
				$sku = get_post_meta( $post_id, '_sku', true );
				if ( ! empty( $sku ) && '' != trim( $sku ) ) {
					$refyn_product_sku_data->insert_item( $post_id, $sku );
				}
			}
		}
	}
}
global $refyn_synch;
$refyn_synch = new Refyn_Search_Synch();
