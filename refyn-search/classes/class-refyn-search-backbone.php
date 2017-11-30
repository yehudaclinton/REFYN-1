<?php



/* "Copyright 2012 REFYN.org" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */



/**



 * Refyn Search Hook Backbone



 *



 * Table Of Contents



 *



 * register_admin_screen()



 */



class Refyn_Search_Hook_Backbone



{



	public function __construct() {



		



		// Add script into footer to hanlde the event from widget, popup



		//add_action( 'wp_footer', array( $this, 'register_plugin_scripts' ) );



		add_action( 'wp_head', array( $this, 'register_plugin_scripts' ) );



		add_action( 'wp_head', array( $this, 'include_result_shortcode_script' ) );



	}



	



	public function register_plugin_scripts() {



		global $refyn_search_page_id;



		



		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';



	?>



    <!-- Refyn Search Widget Template -->



    <script type="text/template" id="refynearch_itemTpl">



		<div class="ajax_search_content">



			<div class="result_row">



				<a href="{{= url }}">TT



					<div class="search_product_img"><img src="{{= image_url }}" /></div> 



					<div class="search_product_info">



					<h4 class="search_product_heading">{{= title }}</h4>



					{{ if ( description != null && description != '' ) { }}<p>{{= description }}</p>{{ } }}



					</div>



					{{ if ( price != null && price != '' ) { }}<div class="search_product_cost"><?php refyn_ict_t_e( 'Price', __('Price', 'refyn') ); ?>: {{= price }}</div>{{ } }}







					<span class="rs_avatar"><img src="{{= image_url }}" /></span>



					<div class="rs_content_popup">



						{{ if ( type == 'p_sku' ) { }}<span class="rs_name">{{= sku }}</span>{{ } }}



						<span class="rs_name">{{= title }}</span>



						{{ if ( price != null && price != '' ) { }}<span class="rs_price"><?php refyn_ict_t_e( 'Price', __('Price', 'refyn') ); ?>: {{= price }}</span>{{ } }}



						{{ if ( description != null && description != '' ) { }}<span class="rs_description">{{= description }}</span>{{ } }}



					</div>



				</a>



			</div>



		</div>



	</script>



    



    <script type="text/template" id="refynearch_footerTpl">



		<div rel="more_result" class="more_result">



			<span><?php refyn_ict_t_e( 'More result Text', __('See more search results for', 'refyn') ); ?> '{{= title }}' <?php refyn_ict_t_e( 'in', __('in', 'refyn') ); ?>:</span>



			{{ if ( description != null && description != '' ) { }}{{= description }}{{ } }}



		</div>



	</script>



    



    



    <?php



		wp_register_script( 'ajax-woo-autocomplete-script', REFYN_JS_URL . '/ajax-autocomplete/jquery.autocomplete.js', array(), '3.0', true );



		wp_register_script( 'backbone.localStorage', REFYN_JS_URL . '/backbone.localStorage.js', array() , '1.1.9', true );



		wp_register_script( 'refyn-search-backbone', REFYN_JS_URL . '/refyn-search.backbone.js', array(), '1.0.0', true );



		wp_enqueue_script( 'jquery' );



		wp_enqueue_script( 'underscore' );



		wp_enqueue_script( 'backbone' );



		wp_enqueue_script( 'backbone.localStorage' );



		wp_enqueue_script( 'refyn-search-popup-backbone', REFYN_JS_URL . '/refyn-search-popup.backbone.min.js', array( 'ajax-woo-autocomplete-script', 'refyn-search-backbone' ), '3.0', true );



		



		global $refyn_legacy_api;



		$legacy_api_url = $refyn_legacy_api->get_legacy_api_url();



		$legacy_api_url = add_query_arg( 'action', 'get_result_popup', $legacy_api_url );



		if ( class_exists( 'SitePress' ) ) {



			$legacy_api_url = add_query_arg( 'lang', ICL_LANGUAGE_CODE, $legacy_api_url );



		}



		$min_characters = 1;



		$delay_time = 600;



		wp_localize_script( 'refyn-search-popup-backbone', 'refyn_vars', apply_filters( 'refyn_vars', array( 'minChars' => $min_characters, 'delay' => $delay_time, 'legacy_api_url' => $legacy_api_url, 'search_page_url' => get_permalink( $refyn_search_page_id ), 'permalink_structure' => get_option('permalink_structure' ) ) ) );



	}



	



	public function include_result_shortcode_script() {



		global $wp_query;



		global $post;



		global $refyn_search_page_id;



		



		if ( $post && $post->ID != $refyn_search_page_id ) return '';



		$current_lang = '';



		if ( class_exists('SitePress') ) {



			$current_lang = ICL_LANGUAGE_CODE;



		}



		



		$search_keyword = '';



		$search_in = 'product';



		$search_other = '';



		$cat_in = 'all';



		



		if ( isset( $wp_query->query_vars['keyword'] ) ) $search_keyword = stripslashes( strip_tags( urldecode( $wp_query->query_vars['keyword'] ) ) );



		elseif ( isset(  $_REQUEST['rs']  ) && trim( sanitize_text_field( $_REQUEST['rs'] ) ) != '' ) $search_keyword = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['rs'] ) ) );



		if ( isset( $wp_query->query_vars['search-in'] ) ) $search_in = stripslashes( strip_tags( urldecode( $wp_query->query_vars['search-in'] ) ) );



		elseif ( isset(  $_REQUEST['search_in']  ) && trim( sanitize_text_field( $_REQUEST['search_in'] ) ) != '' ) $search_in = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['search_in'] ) ) );



		



		if ( isset( $wp_query->query_vars['search-other'] ) ) $search_other = stripslashes( strip_tags( urldecode( $wp_query->query_vars['search-other'] ) ) );



		elseif ( isset(  $_REQUEST['search_other']  ) && trim( sanitize_text_field( $_REQUEST['search_other'] ) ) != '' ) $search_other = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['search_other'] ) ) );



		if ( isset( $wp_query->query_vars['cat-in'] ) ) $cat_in = stripslashes( strip_tags( urldecode( $wp_query->query_vars['cat-in'] ) ) );



		elseif ( isset(  $_REQUEST['cat_in']  ) && trim( sanitize_text_field( $_REQUEST['cat_in'] ) ) != '' ) $cat_in = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['cat_in'] ) ) );



		



		$permalink_structure = get_option( 'permalink_structure' );



		if ( $search_keyword == '' || $search_in == '' ) return;



		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';



	?>



    <!-- Refyn Search Results Template -->



    <script type="text/template" id="refynearch_result_itemTpl">



		<span class="rs_rs_avatar"><a href="{{= url }}"><img src="{{= image_url }}" /></a></span>



		<div class="rs_content">



			<a href="{{= url }}">JJ<span class="rs_rs_name">{{ if ( type == 'p_sku' ) {  }}{{= keyword }}, {{ } }}{{= title }}</span></a>



			{{ if ( sku != null && sku != '' ) { }}<div class="rs_rs_sku"><?php refyn_ict_t_e( 'SKU', __('SKU', 'refyn') ); ?>: {{= sku }}</div>{{ } }}



			{{ if ( price != null && price != '' ) { }}<div class="rs_rs_price"><?php refyn_ict_t_e( 'Price', __('Price', 'refyn') ); ?>: {{= price }}</div>{{ } }}



			{{ if ( addtocart != null && addtocart != '' ) { }}<div class="rs_rs_addtocart">{{= addtocart }}</div>{{ } }}



			{{ if ( description != null && description != '' ) { }}<div class="rs_rs_description">{{= description }}</div>{{ } }}



			{{ if ( categories.length > 0 ) { }}



				<div class="rs_rs_cat posted_in">



					<?php refyn_ict_t_e( 'Category', __('Category', 'refyn') ); ?>: 



					{{ var number_cat = 0; }}



					{{ _.each( categories, function( cat_data ) { number_cat++; }}



						{{ if ( number_cat > 1 ) { }}, {{ } }}<a href="{{= cat_data.url }}">{{= cat_data.name }}</a>



					{{ }); }}



				</div>



			{{ } }}



			{{ if ( tags.length > 0 ) { }}



				<div class="rs_rs_tag tagged_as">



					<?php refyn_ict_t_e( 'Tags', __('Tags', 'refyn') ); ?>: 



					{{ var number_tag = 0; }}



					{{ _.each( tags, function( tag_data ) { number_tag++; }}



						{{ if ( number_tag > 1 ) { }}, {{ } }}<a href="{{= tag_data.url }}">{{= tag_data.name }}</a>



					{{ }); }}



				</div>



			{{ } }}



		</div>



	</script>



    



    <script type="text/template" id="refynearch_result_footerTpl">



		<div style="clear:both"></div>



		{{ if ( next_page_number > 1 ) { }}



		<div id="ps_more_check"></div>



		{{ } else if ( total_items == 0 && first_load ) { }}



		<p style="text-align:center"><?php refyn_ict_t_e( 'No Result Text', __('Nothing Found! Please refine your search and try again.', 'refyn') ); ?></p>



		{{ } }}



	</script>



    



    



    <?php



		wp_enqueue_script( 'jquery' );



		wp_enqueue_script( 'underscore' );



		wp_enqueue_script( 'backbone' );



		wp_enqueue_script( 'refyn-search-results-backbone', REFYN_JS_URL . '/refyn-search-results.backbone.min.js', array( 'refyn-search-backbone' ), '3.0', true );



		



		global $refyn_legacy_api;



		$legacy_api_url = $refyn_legacy_api->get_legacy_api_url();



		$legacy_api_url = add_query_arg( 'action', 'get_results', $legacy_api_url );



		if ( class_exists( 'SitePress' ) ) {



			$legacy_api_url = add_query_arg( 'lang', ICL_LANGUAGE_CODE, $legacy_api_url );



		}



		$legacy_api_url .= '&q=' . $search_keyword;



		if ( $cat_in != '' ) $legacy_api_url .= '&cat_in=' . $cat_in;



		else $legacy_api_url .= '&cat_in=all';



		$product_term_id = 0;



		$post_term_id = 0;



		$refyn_search_focus_enable = false;



		$refyn_search_focus_plugin = false;



		$search_in_have_items = false;



		global $refyn_search;



		$search_other_list = explode(",", $search_other);



		if ( ! is_array( $search_other_list ) ) {



			$search_other_list = array();



		}



		global $ps_search_list, $ps_current_search_in;



		$ps_search_list = $search_all_list = $search_other_list;



		$ps_current_search_in = $search_in;



		// Remove current search in on search other list first



		$search_all_list = array_diff( $search_all_list, (array) $search_in );



		// Add current search in as first element from search other list



		$search_all_list = array_merge( (array) $search_in, $search_all_list );



		if ( count( $search_all_list ) > 0 ) {



			foreach ( $search_all_list as $search_item ) {



				if ( 'product' == $search_item ) {



					$have_product = $refyn_search->check_product_exsited( $search_keyword, $refyn_search_focus_enable, $refyn_search_focus_plugin, 'product', $product_term_id, $current_lang );



					if ( $have_product ) {



						if ( ! $search_in_have_items ) {



							$search_in_have_items = true;



							$ps_current_search_in = $search_item;



						}



					} else {



						$ps_search_list = array_diff( $ps_search_list, (array) $search_item );



					}



				} elseif ( 'post' == $search_item ) {



					$have_post = $refyn_search->check_product_exsited( $search_keyword, $refyn_search_focus_enable, $refyn_search_focus_plugin, 'post', $post_term_id, $current_lang );



					if ( $have_post ) {



						if ( ! $search_in_have_items ) {



							$search_in_have_items = true;



							$ps_current_search_in = $search_item;



						}



					} else {



						$ps_search_list = array_diff( $ps_search_list, (array) $search_item );



					}



				} elseif ( 'page' == $search_item ) {



					$have_page = $refyn_search->check_product_exsited( $search_keyword, $refyn_search_focus_enable, $refyn_search_focus_plugin, 'page', 0, $current_lang );



					if ( $have_page ) {



						if ( ! $search_in_have_items ) {



							$search_in_have_items = true;



							$ps_current_search_in = $search_item;



						}



					} else {



						$ps_search_list = array_diff( $ps_search_list, (array) $search_item );



					}



				}



			}



		}



		$search_page_url = get_permalink( $refyn_search_page_id );



		$search_page_parsed = parse_url( $search_page_url );



		if ( $permalink_structure == '' ) {



			$search_page_path = $search_page_parsed['path'];



			$default_navigate = '?page_id='.$refyn_search_page_id.'&rs='.urlencode($search_keyword).'&search_in='.$ps_current_search_in.'&cat_in='.$cat_in.'&search_other='.$search_other;



		} else {



			$host_name = $search_page_parsed['host'];



			$search_page_exploded = explode( $host_name , $search_page_url );



			$search_page_path = $search_page_exploded[1];



			$default_navigate = 'keyword/'.urlencode($search_keyword).'/search-in/'.$ps_current_search_in.'/cat-in/'.$cat_in.'/search-other/'.$search_other;



		}



		wp_localize_script( 'refyn-search-results-backbone', 'refyn_results_vars', apply_filters( 'refyn_results_vars', array( 'default_navigate' => $default_navigate, 'search_in' => $ps_current_search_in, 'legacy_api_url' => $legacy_api_url, 'search_page_path' => $search_page_path, 'permalink_structure' => get_option('permalink_structure' ) ) ) );



	}



}



global $refyn_hook_backbone;



$refyn_hook_backbone = new Refyn_Search_Hook_Backbone();



