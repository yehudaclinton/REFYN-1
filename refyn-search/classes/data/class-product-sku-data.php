<?php
/* "Copyright 2012 REFYN.org" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
class REFYN_Product_SKU_Data
{
	public function install_database() {
		global $wpdb;
		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if( ! empty($wpdb->charset ) ) $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			if( ! empty($wpdb->collate ) ) $collate .= " COLLATE $wpdb->collate";
		}
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$table_ps_product_sku = $wpdb->prefix. "ps_product_sku";
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_ps_product_sku'") != $table_ps_product_sku) {
			$sql = "CREATE TABLE IF NOT EXISTS `{$table_ps_product_sku}` (
					post_id bigint(20) NOT NULL,
					sku text NULL,
					PRIMARY KEY  (post_id)
				) $collate; ";
			$wpdb->query($sql);
		}
	}
	/**
	 * Refyn Search Product SKU Table - set table name
	 *
	 * @return void
	 */
	public function set_table_wpdbfix() {
		global $wpdb;
		$meta_name = 'ps_product_sku';
		$wpdb->ps_product_sku = $wpdb->prefix . $meta_name;
		$wpdb->tables[] = 'ps_product_sku';
	}
	/**
	 * Insert Refyn Search Product SKU
	 */
	public function insert_item( $post_id, $sku = '' ) {
		global $wpdb;
		return $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->ps_product_sku} VALUES(%d, %s)", $post_id, stripslashes( $sku ) ) );
	}
	/**
	 * Update Refyn Search Product SKU
	 */
	public function update_item( $post_id, $sku = '' ) {
		global $wpdb;
		$value = $this->get_item( $post_id );
		if ( NULL == $value ) {
			return $this->insert_item( $post_id, $sku );
		} else {
			return $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->ps_product_sku} SET sku = %s WHERE post_id = %d ", stripslashes( $sku ), $post_id ) );
		}
	}
	/**
	 * Get Refyn Search Product SKU
	 */
	public function get_item( $post_id ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( "SELECT sku FROM {$wpdb->ps_product_sku} WHERE post_id = %d LIMIT 0,1 ", $post_id ) );
	}
	/**
	 * Delete Refyn Search Product SKU
	 */
	public function delete_item( $post_id ) {
		global $wpdb;
		return $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->ps_product_sku} WHERE post_id = %d ", $post_id ) );
	}
	/**
	 * Empty Refyn Search Product SKU
	 */
	public function empty_table() {
		global $wpdb;
		return $wpdb->query( "TRUNCATE {$wpdb->ps_product_sku}" );
	}
}
global $refyn_product_sku_data;
$refyn_product_sku_data = new REFYN_Product_SKU_Data();
