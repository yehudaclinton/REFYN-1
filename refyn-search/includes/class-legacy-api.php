<?php



/* "Copyright 2012 REFYN Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */



/**



 * Refyn Search Legacy API Class



 *



 */



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



class Refyn_Search_Legacy_API {



	/** @var string $base the route base */



	protected $base = '/refyn_legacy_api';



	protected $base_tag = 'refyn_legacy_api';



	/**



	* Default contructor



	*/



	public function __construct() {



		add_action( 'refyn_api_' . $this->base_tag, array( $this, 'refyn_api_handler' ) );



	}



	public function get_legacy_api_url() {



		$legacy_api_url = WC()->api_request_url( $this->base_tag );



		$legacy_api_url = str_replace( array( 'https:', 'http:' ), '', $legacy_api_url );



		return apply_filters( 'refyn_legacy_api_url', $legacy_api_url );



	}



	public function refyn_api_handler() {



		if ( isset(  $_REQUEST['action'] ) )  {



			$action = addslashes( trim( sanitize_text_field( $_REQUEST['action'] ) ) );



			switch ( $action ) {



				case 'get_result_popup' :



					$this->get_result_popup();



				break;



				case 'get_results' :



					$this->get_all_results();



				break;



			}



		}



	}



	public function get_result_popup() {



		@ini_set('display_errors', false );



		global $refyn_search_page_id;



		global $refyn_search;



		$current_lang = '';



		if ( class_exists('SitePress') ) {



			$current_lang = sanitize_text_field( $_REQUEST['lang'] );



		}



		$rs_items = array();



		$row = 6;



		$text_lenght = 100;



		$show_price = 0;



		$search_keyword = '';



		$cat_in = 'all';



		$found_items = false;



		$total_product = $total_post = $total_page = 0;



		$items_search_default = Refyn_Search_Widgets::get_items_search();



		$search_in_default = array();



		foreach ( $items_search_default as $key => $data ) {



			if ( $data['number'] > 0 ) {



				$search_in_default[$key] = $data['number'];



			}



		}



		if ( isset( $_REQUEST['row']) && sanitize_text_field( $_REQUEST['row'] ) > 0) $row = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['row'] ) ) );



		if ( isset( $_REQUEST['text_lenght']) && sanitize_text_field( $_REQUEST['text_lenght']) >= 0) $text_lenght = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['text_lenght'] ) ) );



		if ( isset( $_REQUEST['show_price']) && trim(sanitize_text_field( $_REQUEST['show_price'])) != '') $show_price = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['show_price'] ) ) );



		if ( $show_price == 1 ) $show_price = true; else $show_price = false;



		if ( isset( $_REQUEST['q']) && trim(sanitize_text_field( $_REQUEST['q'])) != '') $search_keyword = stripslashes( strip_tags(sanitize_text_field(  $_REQUEST['q'] ) ) );



		if ( isset( $_REQUEST['cat_in']) && trim(sanitize_text_field( $_REQUEST['cat_in'])) != '') $cat_in = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['cat_in'] ) ) );



		if ( isset( $_REQUEST['search_in']) && trim(sanitize_text_field( $_REQUEST['search_in'])) != '') $search_in = json_decode( stripslashes( sanitize_text_field( $_REQUEST['search_in'] )), true );



		if ( ! is_array($search_in) || count($search_in) < 1 || array_sum($search_in) < 1) $search_in = $search_in_default;



		if ( $search_keyword != '' ) {



			$search_list = array();



			foreach ($search_in as $key => $number) {



				if ( ! isset( $items_search_default[$key] ) ) continue;



				if ($number > 0)



					$search_list[$key] = $key;



			}



			$refyn_search_focus_enable = false;



			$refyn_search_focus_plugin = false;



			$all_items = array();



			$product_list = array();



			$post_list = array();



			$page_list = array();



			$permalink_structure = get_option( 'permalink_structure' );



			$product_term_id = 0;



			$post_term_id = 0;



			if ( isset( $search_in['product'] ) && $search_in['product'] > 0 ) {



				$product_list = $refyn_search->get_product_results( $search_keyword, $search_in['product'], 0, $refyn_search_focus_enable, $refyn_search_focus_plugin, $product_term_id, $text_lenght, $current_lang, true, $show_price );



				$total_product = $product_list['total'];



				if ( $total_product > 0 ) {



					$found_items = true;



					$rs_items['product'] = $product_list['items'];



				}



			}



			if ( isset( $search_in['post'] ) && $search_in['post'] > 0 ) {



				$post_list = $refyn_search->get_post_results( $search_keyword, $search_in['post'], 0, $refyn_search_focus_enable, $refyn_search_focus_plugin, $post_term_id, $text_lenght, $current_lang, 'post' );



				$total_post = $post_list['total'];



				if ( $total_post > 0 ) {



					$found_items = true;



					$rs_items['post'] = $post_list['items'];



				}



			}



			if ( isset( $search_in['page'] ) && $search_in['page'] > 0 ) {



				$page_list = $refyn_search->get_post_results( $search_keyword, $search_in['page'], 0, $refyn_search_focus_enable, $refyn_search_focus_plugin, 0, $text_lenght, $current_lang, 'page' );



				$total_page = $page_list['total'];



				if ( $total_page > 0 ) {



					$found_items = true;



					$rs_items['page'] = $page_list['items'];



				}



			}



			if ( $found_items === false ) {



				$all_items[] = array(



					'title' 	=> refyn_ict_t__( 'Nothing found', __('Nothing found for that name. Try a different spelling or name.', 'refyn') ),



					'keyword'	=> $search_keyword,



					'type'		=> 'nothing'



				);



			} else {



				foreach ( $search_in as $key => $number ) {



					if ( $number > 0 ) {



						if ( isset( $rs_items[$key] ) ) $all_items = array_merge( $all_items, $rs_items[$key] );



					}



				}



				$search_other = $search_list;



				if ( $total_product < 1 )  { unset($search_list['product']); unset($search_other['product']);



				} elseif ($total_product <= $search_in['product']) { unset($search_list['product']); }



				if ( $total_post < 1 ) { unset($search_list['post']); unset($search_other['post']);



				} elseif ($total_post <= $search_in['post']) { unset($search_list['post']); }



				if ( $total_page < 1 ) { unset($search_list['page']); unset($search_other['page']);



				} elseif ($total_page <= $search_in['page']) { unset($search_list['page']); }



				if ( count( $search_list ) > 0 ) {



					$rs_footer_html = '';



					foreach ($search_list as $other_rs) {



						if ( $permalink_structure == '')



							$search_in_parameter = '&search_in='.$other_rs;



						else



							$search_in_parameter = '/search-in/'.$other_rs;



						if ( $permalink_structure == '')



							$link_search = get_permalink( $refyn_search_page_id ).'&rs='. urlencode($search_keyword) .$search_in_parameter.'&search_other='.implode(",", $search_other).'&cat_in='.$cat_in;



						else



							$link_search = rtrim( get_permalink( $refyn_search_page_id ), '/' ).'/keyword/'. urlencode($search_keyword) .$search_in_parameter.'/cat-in/'.$cat_in.'/search-other/'.implode(",", $search_other);



						$rs_item = '<a href="'.$link_search.'">'.$items_search_default[$other_rs]['name'].' <span class="see_more_arrow"></span></a>';



						$rs_footer_html .= "$rs_item";



					}



					$all_items[] = array(



						'title' 	=> $search_keyword,



						'keyword'	=> $search_keyword,



						'description'	=> $rs_footer_html,



						'type'		=> 'footer'



					);



				}



			}



			header( 'Content-Type: application/json', true, 200 );



			die( json_encode( $all_items ) );



		} else {



			header( 'Content-Type: application/json', true, 200 );



			die( json_encode( array() ) );



		}



	}



	public function get_all_results() {



		@ini_set('display_errors', false );



		global $refyn_search;



		$current_lang = '';



		if ( class_exists('SitePress') ) {



			$current_lang = sanitize_text_field( $_REQUEST['lang'] );



		}



		$psp = 1;



		$row = 10;



		$search_keyword = '';



		$cat_in = 'all';



		$search_in = 'product';



		if ( get_option('refyn_search_result_items') > 0  ) $row = get_option('refyn_search_result_items');



		if ( isset(  $_REQUEST['psp'] ) && sanitize_text_field( $_REQUEST['psp']) > 0 ) $psp = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['psp'] ) ) );



		if ( isset(  $_REQUEST['q'] ) && trim( sanitize_text_field( $_REQUEST['q']) ) != '' ) $search_keyword = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['q'] ) ) );



		if ( isset(  $_REQUEST['cat_in'] ) && trim( sanitize_text_field( $_REQUEST['cat_in']) ) != '' ) $cat_in = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['cat_in'] ) ) );



		if ( isset(  $_REQUEST['search_in'] ) && trim( sanitize_text_field( $_REQUEST['search_in'] ) ) != '' ) $search_in = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['search_in'] ) ) );



		$item_list = array( 'total' => 0, 'items' => array() );



		if ( $search_keyword != '' && $search_in != '') {



			$show_sku = false;



			$show_price = false;



			$show_addtocart = false;



			$show_categories = false;



			$show_tags = false;



			if ( get_option('refyn_search_sku_enable') == '' || get_option('refyn_search_sku_enable') == 'yes' ) $show_sku = true;



			if ( get_option('refyn_search_price_enable') == '' || get_option('refyn_search_price_enable') == 'yes' ) $show_price = true;



			if ( get_option('refyn_search_addtocart_enable') == '' || get_option('refyn_search_addtocart_enable') == 'yes' ) $show_addtocart = true;



			if ( get_option('refyn_search_categories_enable') == '' || get_option('refyn_search_categories_enable') == 'yes' ) $show_categories = true;



			if ( get_option('refyn_search_tags_enable') == '' || get_option('refyn_search_tags_enable') == 'yes' ) $show_tags = true;



			$text_lenght = get_option('refyn_search_text_lenght');



			$product_term_id = 0;



			$post_term_id = 0;



			$start = ( $psp - 1) * $row;



			$refyn_search_focus_enable = false;



			$refyn_search_focus_plugin = false;



			if ( $search_in == 'product' ) {



				$item_list = $refyn_search->get_product_results( $search_keyword, $row, $start, $refyn_search_focus_enable, $refyn_search_focus_plugin, $product_term_id, $text_lenght, $current_lang, false, $show_price, $show_sku, $show_addtocart, $show_categories, $show_tags );



			} elseif ( $search_in == 'post' ) {



				$item_list = $refyn_search->get_post_results( $search_keyword, $row, $start, $refyn_search_focus_enable, $refyn_search_focus_plugin, $post_term_id, $text_lenght, $current_lang, 'post', false , $show_categories, $show_tags );



			} elseif ( $search_in == 'page' ) {



				$item_list = $refyn_search->get_post_results( $search_keyword, $row, $start, $refyn_search_focus_enable, $refyn_search_focus_plugin, 0, $text_lenght, $current_lang, 'page', false );



			}



		}



		header( 'Content-Type: application/json', true, 200 );



		die( json_encode( $item_list ) );



	}



}



global $refyn_legacy_api;



$refyn_legacy_api = new Refyn_Search_Legacy_API();