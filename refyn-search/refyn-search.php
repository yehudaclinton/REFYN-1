<?php
/*
Plugin Name: REFYN Search
Plugin URI: https://refyn.org
Description: REFYN is Smarter than Google. Text & Product search with Artificial Intelligence and Never Zero Results or Not Found.
Version: 1.0
Author: REFYN
Author URI: https://www.refyn.org
Requires at least: 3.7
Tested up to: 4.2.2
License: GPLv2 or later

	Refyn Search. Plugin for the Refyn plugin.
	Copyright © 2016


*/



ini_set('display_errors','Off');
ini_set('error_reporting', 0);
error_reporting(0);


define('REFYN_FILE_PATH', dirname(__FILE__));

define('REFYN_DIR_NAME', basename(REFYN_FILE_PATH));



define('REFYN_FOLDER', dirname(plugin_basename(__FILE__)));







define('REFYN_NAME', plugin_basename(__FILE__));







define('REFYN_URL', untrailingslashit(plugins_url('/', __FILE__)));







define('REFYN_JS_URL', REFYN_URL . '/assets/js');







define('REFYN_CSS_URL', REFYN_URL . '/assets/css');







define('REFYN_IMAGES_URL', REFYN_URL . '/assets/images');







if (!defined("REFYN_AUTHOR_URI")) define("REFYN_AUTHOR_URI", "https://refyn.org/about/");







if (!defined("WOO_REFYN_SEARCH_DOCS_URI")) define("WOO_REFYN_SEARCH_DOCS_URI", "https://refyn.org/features/");







if (!defined("REMOTE_API_SERVER")) define("REMOTE_API_SERVER", "https://comfyplane.com/");









include ('admin/admin-ui.php');






include ('admin/admin-interface.php');







include ('admin/admin-pages/refyn-search-page.php');








include ('admin/admin-init.php');










include 'classes/class-refyn-search-filter.php';







include 'classes/class-refyn-search.php';







include 'classes/class-refyn-search-shortcodes.php';







include 'classes/class-refyn-search-metabox.php';







include 'widget/refyn-search-widgets.php';











// Editor







include 'tinymce3/tinymce.php';















include 'admin/refyn-search-init.php';















/**







 * Call when the plugin is activated







 */







register_activation_hook(__FILE__, 'refyn_install');















function refyn_uninstall()







{







    if (get_option('refyn_search_lite_clean_on_deletion') == 'yes') {







        delete_option('refyn_search_text_lenght');







        delete_option('refyn_search_result_items');







        delete_option('refyn_search_sku_enable');







        delete_option('refyn_search_price_enable');







        delete_option('refyn_search_addtocart_enable');







        delete_option('refyn_search_categories_enable');







        delete_option('refyn_search_tags_enable');







        delete_option('refyn_search_box_text');







        delete_option('refyn_search_page_id');







        delete_option('refyn_search_exclude_products');















        delete_option('refyn_search_exclude_p_categories');







        delete_option('refyn_search_exclude_p_tags');







        delete_option('refyn_search_exclude_posts');







        delete_option('refyn_search_exclude_pages');







        delete_option('refyn_search_focus_enable');







        delete_option('refyn_search_focus_plugin');







        delete_option('refyn_search_product_items');







        delete_option('refyn_search_p_sku_items');







        delete_option('refyn_search_p_cat_items');







        delete_option('refyn_search_p_tag_items');







        delete_option('refyn_search_post_items');







        delete_option('refyn_search_page_items');







        delete_option('refyn_search_character_max');







        delete_option('refyn_search_width');







        delete_option('refyn_search_padding_top');







        delete_option('refyn_search_padding_bottom');







        delete_option('refyn_search_padding_left');







        delete_option('refyn_search_padding_right');







        delete_option('refyn_search_custom_style');







        delete_option('refyn_search_global_search');















        delete_option('refyn_search_enable_google_analytic');







        delete_option('refyn_search_google_analytic_id');







        delete_option('refyn_search_google_analytic_query_parameter');















        delete_option('refyn_search_lite_clean_on_deletion');







// option tab







	delete_option('refyn_no_of_character_obs_search_box_text');







	delete_option('refyn_no_of_results_search_box_text');







	delete_option('refyn_unrelated_result_search_box_text');







	delete_option('refyn_no_of_letter_search_box_text');







	delete_option('refyn_ranking_formula');







	delete_option('refyn_results_option');







	delete_option('refyn_show_images_option');







	delete_option('refyn_predict_win_price_option');







	delete_option('refyn_search_inside_blog_option');







	delete_option('refyn_product_sku_search_option');







	delete_option('refyn_search_inside_postcontent_option');







	delete_option('refyn_search_inside_excerpt_option');







	delete_option('refyn_include_outofstock_option');







	delete_option('refyn_product_categories_option');







	







	// style tab







	







	delete_option('refyn_search_list_background_color');







	delete_option('refyn_search_list_border_color');







	delete_option('refyn_search_first_color');







	delete_option('refyn_search_second_color');







	delete_option('refyn_search_text_color');







	delete_option('refyn_search_background_hover_color');







	delete_option('refyn_search_text_hover_color');







		delete_option('refyn_all_settings');







	







        delete_post_meta_by_key('_refyn_search_focuskw');















        wp_delete_post(get_option('refyn_search_page_id'), true);







		







    }







}







if (get_option('refyn_search_lite_clean_on_deletion') == 'yes') {







    register_uninstall_hook(__FILE__, 'refyn_uninstall');







}



/**



 * to remove undate notification.



 */



//add_filter('site_transient_update_plugins', 'remove_update_notification');



function remove_update_notification($value) {



     unset($value->response[ plugin_basename(__FILE__) ]);



     return $value;



} 



/*



* Function to get Smaller Content



*/



function the_contentsmall($post_id,$before = '', $after = '', $echo = true, $length = false) 



	   {



		 $title = $post_id;



		if ( $length && is_numeric($length) ) {



			$title =substr($title, 0, $length+1 );



		}



	if ( strlen($title)> 0 ) {



		$title=utf8_encode( apply_filters(utf8_decode('the_contentsmall'), $before . $title . $after, $before, $after));



		if ( $echo )



		{



			return utf8_decode($title);



		}



		else



		{



			return utf8_decode($title);



		}



	 }



  }



