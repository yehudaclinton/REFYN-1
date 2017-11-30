<?php





/**





 * Refyn Search Hook Filter





 *





 * Hook anf Filter into refyn plugin





 *





 * Table Of Contents





 *





 * add_search_widget_icon()





 * add_search_widget_mce_popup()





 * parse_shortcode_search_result()





 * get_product_price()





 * get_product_price_dropdown()





 * get_product_addtocart()





 * get_product_categories()





 * get_product_tags()





 * display_search()





 * get_result_search_page()





 */





class Refyn_Search_Shortcodes





{





	public static function add_search_widget_icon($context){





		$image_btn = REFYN_IMAGES_URL . "/ps_icon.png";





		$out = '<a href="#TB_inline?width=670&height=500&modal=false&inlineId=woo_search_widget_shortcode" class="thickbox" title="'.__('Insert Refyn Search Shortcode', 'refyn').'"><img class="search_widget_shortcode_icon" src="'.$image_btn.'" alt="'.__('Insert Refyn Search Shortcode', 'refyn').'" /></a>';





		return $context . $out;





	}





	





	//Action target that displays the popup to insert a form to a post/page





	public static function add_search_widget_mce_popup(){





		$items_search_default = Refyn_Search_Widgets::get_items_search();





		?>





		<style type="text/css">





		#TB_ajaxContent{width:auto !important;}





		#TB_ajaxContent p {





			padding:2px 0;	





			margin:6px 0;





		}





		.field_content {





			padding:0 0 0 40px;





		}





		.field_content label{





			width:150px;





			float:left;





			text-align:left;





		}





		.refyn-view-docs-button {





			background-color: #FFFFE0 !important;





			border: 1px solid #E6DB55 !important;





			border-radius: 3px;





			-webkit-border-radius: 3px;





			-moz-border-radius: 3px;





			color: #21759B !important;





			outline: 0 none;





			text-shadow:none !important;





			font-weight:normal !important;





			font-family: sans-serif;





			font-size: 12px;





			text-decoration: none;





			padding: 3px 8px;





			position: relative;





			margin-left: 4px;





			white-space:nowrap;





		}





		.refyn-view-docs-button:hover {





			color: #D54E21 !important;





		}





		@media screen and ( max-width: 782px ) {





			#woo_search_box_text {





				width:100% !important;	





			}





		}





		@media screen and ( max-width: 480px ) {





			.refyn_refyn_search_exclude_item {





				float:none !important;





				display:block;





			}





		}





		#woo_refyn_upgrade_area { border:2px solid #E6DB55;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; padding:0; position:relative}





	  	#woo_refyn_upgrade_area h3{ margin-left:10px;}





		.refyn-rev-logo-extensions { position:absolute; left:10px; top:0px; z-index:10; color:#46719D; }





		.refyn-rev-logo-extensions:before {





		  font-family: "refyn-sidebar-menu" !important;





		  font-style: normal !important;





		  font-weight: normal !important;





		  font-variant: normal !important;





		  text-transform: none !important;





		  speak: none;





		  line-height: 1;





		  -webkit-font-smoothing: antialiased;





		  -moz-osx-font-smoothing: grayscale;





			display:inline-block;





			font-size:25px !important;





			font-weight:400;





			height: 36px;





			padding: 8px 0;





			transition: all 0.1s ease-in-out 0s;





		  





		  content: "\refyn" !important;





		}





	   	#woo_refyn_extensions { background:#FFFBCC; -webkit-border-radius:10px 10px 0 0;-moz-border-radius:10px 10px 0 0;-o-border-radius:10px 10px 0 0; border-radius: 10px 10px 0 0; color: #555555; margin: 0px; padding: 4px 8px 4px 40px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); position:relative;}





		</style>





		<div id="woo_search_widget_shortcode" style="display:none;">





		  <div>





			<h3><?php _e('Customize the Refyn Search Shortcode', 'refyn'); ?> <a class="add-new-h2 refyn-view-docs-button" target="_blank" href="<?php echo WOO_REFYN_SEARCH_DOCS_URI; ?>#section-16" ><?php _e('View Docs', 'refyn'); ?></a></h3>





			<div style="clear:both"></div>





            <div id="woo_refyn_upgrade_area"><div class="refyn-rev-logo-extensions"></div><?php echo Refyn_Search::refyn_extension_shortcode(); ?>





			<div class="field_content">





            	<?php foreach ($items_search_default as $key => $data) { ?>





                <p><label for="woo_search_<?php echo $key ?>_items"><?php echo $data['name']; ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="woo_search_<?php echo $key ?>_items" name="woo_search_<?php echo $key ?>_items" type="text" value="<?php echo $data['number'] ?>" /> <span class="description"><?php _e('Number of', 'refyn'); echo ' '.$data['name'].' '; _e('results to show in dropdown', 'refyn'); ?></span></p> 





                <?php } ?>





                <p><label for="woo_search_show_price"><?php _e('Price', 'refyn'); ?>:</label> <input disabled="disabled" type="checkbox" checked="checked" id="woo_search_show_price" name="woo_search_show_price" value="1" /> <span class="description"><?php _e('Show Product prices', 'refyn'); ?></span></p>





            	<p><label for="woo_search_text_lenght"><?php _e('Characters', 'refyn'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="woo_search_text_lenght" name="woo_search_text_lenght" type="text" value="100" /> <span class="description"><?php _e('Number of product description characters', 'refyn'); ?></span></p>





                <p><label for="woo_search_align"><?php _e('Alignment', 'refyn'); ?>:</label> <select disabled="disabled" style="width:100px" id="woo_search_align" name="woo_search_align"><option value="none" selected="selected"><?php _e('None', 'refyn'); ?></option><option value="left-wrap"><?php _e('Left - wrap', 'refyn'); ?></option><option value="left"><?php _e('Left - no wrap', 'refyn'); ?></option><option value="center"><?php _e('Center', 'refyn'); ?></option><option value="right-wrap"><?php _e('Right - wrap', 'refyn'); ?></option><option value="right"><?php _e('Right - no wrap', 'refyn'); ?></option></select> <span class="description"><?php _e('Horizontal aliginment of search box', 'refyn'); ?></span></p>





                <p><label for="woo_search_width"><?php _e('Search box width', 'refyn'); ?>:</label> <input disabled="disabled" style="width:100px;" size="10" id="woo_search_width" name="woo_search_width" type="text" value="200" />px</p>





                <p><label for="woo_search_box_text"><?php _e('Search box text message', 'refyn'); ?>:</label> <input disabled="disabled" style="width:300px;" size="10" id="woo_search_box_text" name="woo_search_box_text" type="text" value="<?php echo $setting_options['refyn_search_box_text']; ?>" /></p>





                <p><label for="woo_search_padding"><strong><?php _e('Padding', 'refyn'); ?></strong>:</label><br /> 





				<label for="woo_search_padding_top" style="width:auto; float:none"><?php _e('Above', 'refyn'); ?>:</label><input disabled="disabled" style="width:50px;" size="10" id="woo_search_padding_top" name="woo_search_padding_top" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;





                <label for="woo_search_padding_bottom" style="width:auto; float:none"><?php _e('Below', 'refyn'); ?>:</label> <input disabled="disabled" style="width:50px;" size="10" id="woo_search_padding_bottom" name="woo_search_padding_bottom" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;





                <label for="woo_search_padding_left" style="width:auto; float:none"><?php _e('Left', 'refyn'); ?>:</label> <input disabled="disabled" style="width:50px;" size="10" id="woo_search_padding_left" name="woo_search_padding_left" type="text" value="0" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;





                <label for="woo_search_padding_right" style="width:auto; float:none"><?php _e('Right', 'refyn'); ?>:</label> <input disabled="disabled" style="width:50px;" size="10" id="woo_search_padding_right" name="woo_search_padding_right" type="text" value="0" />px





                </p>





			</div>





            <p>&nbsp;&nbsp;<input disabled="disabled" type="button" class="button-primary" value="<?php _e('Insert Shortcode', 'refyn'); ?>" />&nbsp;&nbsp;&nbsp;





            <a class="button" style="" href="#" onclick="tb_remove(); return false;"><?php _e('Cancel', 'refyn'); ?></a>





			</p>





            <div style="clear:both;"></div>





           	</div>





		  </div>





          <div style="clear:both;"></div>





		</div>





<?php





	}





	





	public static function parse_shortcode_search_result($attributes) {





    	return Refyn_Search_Shortcodes::display_search();	





    }





public static function get_product_sku($product_id, $show_sku=true) {





		$product_sku_output = '';





		if ($show_sku) {





				$current_db_version = get_option( 'woocommerce_db_version', null );





			if ( version_compare( $current_db_version, '2.0', '<' ) && null !== $current_db_version ) {





				$current_product = new WC_Product($product_id);





			} elseif ( version_compare( WC()->version, '2.2.0', '<' ) ) {





				$current_product = get_product( $product_id );





			} else {





				$current_product = wc_get_product( $product_id );





			}





				$sku=$current_product->get_sku();





				if(!empty($sku))





				$product_sku_output = '<div class="posted_in">'.__('SKU', 'refyn').': '. $current_product->get_sku() .'</div>';





		}





		





		return $product_sku_output;





	}





	





	public static function get_product_price($product_id, $show_price=true) {





		$product_price_output = '';





		if ($show_price) {





			$current_db_version = get_option( 'woocommerce_db_version', null );





			if ( version_compare( $current_db_version, '2.0', '<' ) && null !== $current_db_version ) {





				$current_product = new WC_Product($product_id);





			} elseif ( version_compare( WC()->version, '2.2.0', '<' ) ) {





				$current_product = get_product( $product_id );





			} else {





				$current_product = wc_get_product( $product_id );





			}





			if ($current_product->is_type('grouped')) {





				$product_price_output = $current_product->get_price_html();





			} elseif ($current_product->is_type('variable')) {





				$product_price_output = $current_product->get_price_html();





			} else {





				$product_price_output = $current_product->get_price_html();





			}





		}





		





		return $product_price_output;





	}





	





	public static function get_product_price_dropdown($product_id) {





		$product_price_output = '';





		$current_db_version = get_option( 'woocommerce_db_version', null );





		if ( version_compare( $current_db_version, '2.0', '<' ) && null !== $current_db_version ) {





			$current_product = new WC_Product($product_id);





		} elseif ( version_compare( WC()->version, '2.2.0', '<' ) ) {





			$current_product = get_product( $product_id );





		} else {





			$current_product = wc_get_product( $product_id );





		}





		if ($current_product->is_type('grouped')) {





			$product_price_output = '<span class="rs_price">'.__('Price', 'refyn').': '. $current_product->get_price_html(). '</span>';





		} elseif ($current_product->is_type('variable')) {





			$product_price_output = '<span class="rs_price">'.__('Price', 'refyn').': '. $current_product->get_price_html(). '</span>';





		} else {





			$product_price_output = '<span class="rs_price">'.__('Price', 'refyn').': '. $current_product->get_price_html(). '</span>';





		}





		





		return $product_price_output;





	}





	





	public static function get_product_addtocart($product_id, $show_addtocart=true) {





		$product_addtocart_output = '';





		global $product;





		if ($show_addtocart) {





			$current_db_version = get_option( 'woocommerce_db_version', null );





			if ( version_compare( $current_db_version, '2.0', '<' ) && null !== $current_db_version ) {





				$current_product = new WC_Product($product_id);





			} elseif ( version_compare( WC()->version, '2.2.0', '<' ) ) {





				$current_product = get_product( $product_id );





			} else {





				$current_product = wc_get_product( $product_id );





			}





			$product = $current_product;





			ob_start();





			if (function_exists('refyn_template_loop_add_to_cart') )





				refyn_template_loop_add_to_cart();





			$product_addtocart_html = ob_get_clean();





			$product_addtocart_output = '<div class="rs_rs_addtocart">'. $product_addtocart_html. '</div>';





		}





		





		return $product_addtocart_output;





	}





	





	public static function get_product_categories($product_id, $show_categories=true) {





		$product_cats_output = '';





		if ($show_categories) {





			





			$product_cats = get_the_terms( $product_id, 'product_cat' );





						





			if ( $product_cats && ! is_wp_error( $product_cats ) ) {





				$product_cat_links = array();





				foreach ( $product_cats as $product_cat ) {





					$product_cat_links[] = '<a href="' .get_term_link($product_cat->slug, 'product_cat') .'">'.$product_cat->name.'</a>';





				}





				if (count($product_cat_links) > 0)





					$product_cats_output = '<div class="rs_rs_cat posted_in">'.__('Category', 'refyn').': '.join( ", ", $product_cat_links ).'</div>';





			}





		}





		





		return $product_cats_output;





	}





	





	public static function get_product_tags($product_id, $show_tags=true) {





		$product_tags_output = '';





		if ($show_tags) {





			$product_tags = get_the_terms( $product_id, 'product_tag' );





						





			if ( $product_tags && ! is_wp_error( $product_tags ) ) {





				$product_tag_links = array();





				foreach ( $product_tags as $product_tag ) {





					$product_tag_links[] = '<a href="' .get_term_link($product_tag->slug, 'product_tag') .'">'.$product_tag->name.'</a>';





				}





				if (count($product_tag_links) > 0)





					$product_tags_output = '<div class="rs_rs_tag tagged_as">'.__('Tags', 'refyn').': '.join( ", ", $product_tag_links ).'</div>';





			}





		}





		





		return $product_tags_output;





	}





	





	public static function display_search() {





		global $setting_options;





		if( !empty($setting_options['refyn_search_enable_google_analytic']) && $setting_options['refyn_search_enable_google_analytic'] == 'yes' && !empty($setting_options['refyn_search_google_analytic_query_parameter']) && !empty($setting_options['refyn_search_google_analytic_id']) ){





			?><script>





		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){





		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),





		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)





		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');





		  ga('create', '<?php echo $setting_options['refyn_search_google_analytic_id']; ?>', 'auto');





		  ga('send', 'pageview');





		</script>





	<?php





		}





		global $wp_query;





		global $wpdb;





		global $setting_options;





		





		global $refyn_id_excludes;





		$psp = 0;





		global $remote_API_server, $home_url, $api_key;





		$remote_API_server=REMOTE_API_SERVER.'obs_v1/refyn_api.php';





		$home_url=home_url();





		$api_key=$setting_options['refyn_api_key_text'];





		





		// display product set.





		





		$no_of_products=$setting_options['refyn_search_result_items'];





		if(!empty($no_of_products) && $no_of_products > 0)





		$row=$no_of_products;





		else





		$row = 12;





		





		$search_keyword = '';





		





		$cat_slug = '';





		$sales = '';





		$images = '';





		$prices = '';	





		$line_of_cats='';





		// get category name / filter(sale/image/price) name.	





		$_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);		





		$segments_key = explode('keyword', $_SERVER['REQUEST_URI_PATH']);





		$segments = explode('/', $segments_key[1]);				





		if (in_array("scat", $segments)) {





			if($segments[2]=='scat')





			$cat_slug = $segments[3];		





			if($segments[4]=='sale')





			$sales = $segments[5];			





			if($segments[4]=='images')





			$images = $segments[5];			





			if($segments[4]=='price')





			{





				$price_label = $segments[4];





				$prices = $segments[5];





				$prices_part = explode('-', $prices);	





				$start_prices = $prices_part[0];





				$end_prices = $prices_part[1];





			}		





		}





		else





		{		





			if (in_array("sale", $segments) || in_array("images", $segments) || in_array("price", $segments)) {		





				if($segments[2]=='sale')





				$sales = $segments[3];			





				if($segments[2]=='images')





				$images = $segments[3];			





				if($segments[2]=='price')





				{





					$price_label = $segments[2];





					$prices = $segments[3];





					$prices_part = explode('-', $prices);	





					$start_prices = $prices_part[0];





					$end_prices = $prices_part[1];





				}		





			}





		}





		





		





		// get product ids by image filter.





		if($images=='3' || $images=='4')





		{				





			$product_images_gallery_3 = array();





			$product_images_gallery_4 = array();





			$product_images = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_product_image_gallery' && meta_value != ''", '') );





			for($i=0;$i<count($product_images);$i++)





			{





				$meta_values = explode(',', $product_images[$i]->meta_value);





				if( count($meta_values)>0 && count($meta_values)<3 )





					array_push($product_images_gallery_3,$product_images[$i]->meta_value);





				if( count($meta_values)>2 )





					array_push($product_images_gallery_4,$product_images[$i]->meta_value);





			}





		}





				





		





		$tag_slug = '';





		$extra_parameter = '';





		$show_sku = false;





		$show_price = false;





		$show_categories = false;





		$show_tags = false;





		$show_addtocart = false;





		$extra_parameter_admin = '';





		





		$sku_enable = $setting_options['refyn_search_sku_enable'];





				





				if(!empty($sku_enable) && $sku_enable == 'yes')





				$show_sku=true;





				$price_enable = $setting_options['refyn_search_price_enable'];





				if(!empty($price_enable) && $price_enable == 'yes')





				$show_price=true;





				





				$category_enable = $setting_options['refyn_search_categories_enable'];





				if(!empty($category_enable) && $category_enable == 'yes')





				$show_categories=true;





				





				$protag_enable = $setting_options['refyn_search_tags_enable'];





				if(!empty($protag_enable) && $protag_enable == 'yes')





				$show_tags=true;





				





				$addtocart_enable = $setting_options['refyn_search_addtocart_enable'];





				if(!empty($addtocart_enable) && $addtocart_enable == 'yes')





				$show_addtocart=true;





		





		if (isset($wp_query->query_vars['keyword'])) $search_keyword = stripslashes( strip_tags( urldecode( $wp_query->query_vars['keyword'] ) ) );





		else if (isset( $_REQUEST['rs']) && trim(sanitize_text_field( $_REQUEST['rs'])) != '') $search_keyword = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['rs'] ) ) );





		





		$start = $psp * $row;





		$end_row = $row;





		$end_row1 = $row;





		?>





        





        





		<script type="text/javascript">





		jQuery(document).ready(function() {				





			jQuery(document).ready(function() {				





				// set search keyword to page title.





				jQuery( "#page-title h1" ).append("<?php echo $search_keyword;?>");	





				// set logo alt to logo image on search page.





				jQuery('.logo a img').attr('alt', '<?php echo $search_keyword;?> logo');					





			});			





		});





		</script>





		





        <?php





		if ($search_keyword != '') {





			





			// Update History





			/*





			$refn_search_history = is_array( get_option('refyn_search_history') ) ? get_option('refyn_search_history') : array();





			if( isset( $refn_search_history[$search_keyword] ) ) {





				$refn_search_history[$search_keyword] += 1;





			}





			else {





				$refn_search_history[$search_keyword] = 1;





			}





			update_option( 'refyn_search_history', $refn_search_history  );





			*/





			// End Update History





			





			$refyn_enable=$setting_options['refyn_results_option'];





			$link_search = rtrim( get_permalink(get_option('refyn_search_page_id')), '/' ).'/keyword/';





			





			$try_also = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&link_search='.$link_search.'&r=tryalso&q='.urlencode($search_keyword) );





  		$res1 = json_decode($try_also, true);





		if(!empty($res1['tryalso'])){





		echo "<div class='ajax_search_content_title markopolo'>".__('Try Also', 'refyn')."&nbsp;<img style='display:inline;' src='".plugin_dir_path( __FILE__ )."classes/question-mark.png' height='13' width='13' title='Did you mean " . $res1['definition']	."?'> \n";	





		echo "&nbsp;".$res1['tryalso']."</div>";





		





		}





		if(!empty($refyn_enable) && $refyn_enable == 'no'){





			$html ='';





				//add_filter( 'posts_where', 'pages_filter_by_fields', 10, 2 );





			//$page_post = array( 'numberposts' => $row+1, 'offset'=> 0, 'orderby' => "title", 's' => $search_keyword, 'order' => "ASC", 'post_type' => array('page', 'post'), 'post_status' => 'publish',  'suppress_filters' => FALSE);





			// $page_post = array( 'numberposts' => $row+1, 'offset'=> 0, 'orderby' => "title", 's' => $search_keyword, 'order' => "ASC", 'post_type' => 'any', 'post_status' => 'publish',  'suppress_filters' => FALSE);





			





			$post_types = $setting_options['refyn_seach_in'] ? $setting_options['refyn_seach_in'] : 'any';





			// if( is_array( $post_types ) ) $post_types = implode(",", $post_types);





			$page_post = array( 'numberposts' => $row+1, 'offset'=> 0, 'orderby' => "title", 's' => $search_keyword, 'order' => "ASC", 'post_type' => $post_types, 'post_status' => 'publish',  'suppress_filters' => FALSE);





			





			$search_pages=get_posts($page_post);





			if ( $search_pages && count($search_pages) > 0 ) {





				$html = '<div class="refyn">';			





				$html .= '<p class="rs_result_heading">'.__('Showing all results for your search', 'refyn').' | '.$search_keyword.'</p>';





				$html .= '<style type="text/css">





.row-c-s-button{





        





         width: 100%;





        





         margin-top: 10px;





         background: #c51402;





         border-radius: 4px;





         text-align: center;





         color: #fff;





         font-size: 22px;





         box-sizing: border-box;





         padding-top: 6px;





         transition: all 0.4s;





	float:left;





font-family: Arial;





height:40px;





         }





         .row-c-s-button:hover{





         background: #ED4E56;





         }











         		.product-item{height:auto;min-height:250px}





				.rs_result_heading{margin:15px 0;}





				.ajax-wait{display: none; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; background:url("'.REFYN_IMAGES_URL.'/ajax-loader.gif") no-repeat center center #EDEFF4; opacity: 1;text-align:center;}





				.ajax-wait img{margin-top:14px;}





				





				.p_data,.r_data,.q_data{display:none;}





				.rs_date{color:#777;font-size:small;}





				.rs_result_row{font-family: Arial;height:auto;min-height:250px;width:100%;display: inline-block;margin:0px 0 10px;padding :0px 0 10px; 6px;border-bottom:1px solid #c2c2c2;}





				.rs_result_row:hover{opacity:1;}





				.rs_rs_avatar{width:64px;margin-right:10px;overflow: hidden;float:left; text-align:center;}





				.rs_rs_avatar img{width:100%;height:auto; padding:0 !important; margin:0 !important; border: none !important;}





				.rs_rs_name{margin-left:0px;}





				.rs_content{margin-left:74px;}





				.rs_more_result{display:none;width:240px;text-align:center;position:fixed;bottom:50%;left:50%;margin-left:-125px;background-color: black;opacity: .75;color: white;padding: 10px;border-radius:10px;-webkit-border-radius: 10px;-moz-border-radius: 10px}





				.rs_rs_price .oldprice{text-decoration:line-through; font-size:80%;}





				#page .rs_result_row .price_sale {color: #77a464; }





				</style>';





				$html .= '<div class="rs_ajax_search_content">';





				$text_lenght = $setting_options['refyn_search_text_lenght'];





				foreach($search_pages as $product){





					$link_detail = get_permalink($product->ID);





					if (has_post_thumbnail($product->ID)) {





						$avatar =get_the_post_thumbnail($product->ID, array(64,64));





					}





					else{





						// Elchanan 22-NOV-2016  Fix to find inner image or feature 			





						$args = array(  'post_parent' => $product->ID,        'post_type' => 'attachment',        'post_mime_type' => 'image',        'orderby' => 'menu_order',        'order' => 'ASC',        'offset' => '0',        'numberposts' => 1     );





						$images = get_posts($args);





						$thumb_url = wp_get_attachment_thumb_url( $images[0]->ID);





						if($thumb_url) {





							if ( count( $images ) > 0 )  $avatar = '<img src="'.$thumb_url.'"  width="64" height="64" />';





						}





						else {





							preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $product->post_content, $first_image);





							if( $first_image ) {





								$avatar = '<img src="'.$first_image['src'].'"  width="64" height="64" />';





							}





						}





					





						if (is_null($avatar)) {		





							if(!empty($setting_options['refyn_default_image']))





							$avatar ='<img src="'.$setting_options['refyn_default_image'].'" alt="Placeholder" width="64" height="64" />';





							else





							$avatar ='<img src="'.REFYN_IMAGES_URL.'/placeholder.png" alt="Placeholder" width="64" height="64" />';





						}





					}





						





				   // Elchanan 3-JAN-2017 make order with TITLE and DESC for each result





				   $title = get_the_title($product->ID);





				   $desc = "";





				   // Find the best $product_description





				   if (trim($postt->post_content) != '') 





					   $desc = $postt->post_content;





				   elseif (trim($product->post_content) != '') 





					   $desc = $product->post_content;





				   elseif (trim($postt->post_excerpt) != '') 





					   $desc = $postt->post_excerpt;





				   elseif (trim($product->post_excerpt) != '') 





					   $desc = $product->post_excerpt;





				   if (trim($desc) != '') 	





					   $product_description = Refyn_Search::refyn_limit_words(strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( str_replace("\n", "", $desc) ) ) ),$text_lenght,'...');











					// Show one search result			





					$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><h3>'.$title.'</h3>';





					if(trim($product_description) != '')				





						$html .='<p class="row"><span class="price">'.$product_description.'</span></p>';





							





							





					$html .='</div><a class="row-c-s-button" href="'.$link_detail.'">View</a></div>';





				





				}





				





				$html .= '</div>';





				return $html;





			





			} 





			else{





				





				echo $html .= '<style type="text/css">





				.row-c-s-button{





						





						 width: 100%;





						





						 margin-top: 10px;





						 background: #c51402;





						 border-radius: 4px;





						 text-align: center;





						 color: #fff;





						 font-size: 22px;





						 box-sizing: border-box;





						 padding-top: 6px;





						 transition: all 0.4s;





					float:left;





				font-family: Arial;





				height:40px;





						 }





						 .row-c-s-button:hover{





						 background: #ED4E56;





						 }





         		.product-item{height:auto;min-height:250px}





				.rs_result_heading{margin:15px 0;}





				.ajax-wait{display: none; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; background:url("'.REFYN_IMAGES_URL.'/ajax-loader.gif") no-repeat center center #EDEFF4; opacity: 1;text-align:center;}





				.ajax-wait img{margin-top:14px;}





				





				.p_data,.r_data,.q_data{display:none;}





				.rs_date{color:#777;font-size:small;}





				.rs_result_row{font-family: Arial;height:auto;min-height:250px;width:100%;display: inline-block;margin:0px 0 10px;padding :0px 0 10px; 6px;border-bottom:1px solid #c2c2c2;}





				.rs_result_row:hover{opacity:1;}





				.rs_rs_avatar{width:64px;margin-right:10px;overflow: hidden;float:left; text-align:center;}





				.rs_rs_avatar img{width:100%;height:auto; padding:0 !important; margin:0 !important; border: none !important;}





				.rs_rs_name{margin-left:0px;}





				.rs_content{margin-left:74px;}





				.rs_more_result{display:none;width:240px;text-align:center;position:fixed;bottom:50%;left:50%;margin-left:-125px;background-color: black;opacity: .75;color: white;padding: 10px;border-radius:10px;-webkit-border-radius: 10px;-moz-border-radius: 10px}





				.rs_rs_price .oldprice{text-decoration:line-through; font-size:80%;}





				#page .rs_result_row .price_sale {color: #77a464; }





				</style>';





				





				// Elchanan 22-NOV-2016 TRAY ALSO





			   $trytosearch = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&link_search='.$link_search.'&r=trytosearch&q='.urlencode($search_keyword).'&ip='.$ip );





			   $trytosearch_result = json_decode($trytosearch, true);





			   if(!empty($trytosearch_result)){





				   echo $trytosearch_result['trytosearch'];





		





					if (trim($search_keyword) != trim($trytosearch_result['selected']) )





					{		





						$args['s'] = $trytosearch_result['selected'];





						$search_products = get_posts($args);





							Refyn_Hook_Filter::show_col($search_products,$trytosearch_result['selected'],$extra_parameter,$end_row,$row);





							$row_showed = true;





					}





 





				}





		





				// Elchanan 20-NOV-2016 Nothing found, not even "try also"





				// Let's try spell check 1st





				$spell_suggestions = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=spellcheck&q='.urlencode($search_keyword));





				$all_suggestion = json_decode($spell_suggestions, true);





				$spellResult=$all_suggestion['result'][$search_keyword][0];				





				if (strlen($spellResult) > 2)





				{





					$args['s'] = $spellResult;				





					$search_products = get_posts($args);





					echo "<div class='ajax_search_content_title marci'>".__('DID YOU MEAN '.$spellResult.'?', 'refyn')."</div>\n";





					Refyn_Hook_Filter::show_col($search_products,$spellResult,$extra_parameter,$end_row,$row);





					$row_showed = true;





					$search_keyword=$spellResult;











				}





				if (				!$row_showed          )





					return '<div class="ajax_no_result">'.$setting_options['refyn_notfound_error_search_box_text'].' &nbsp;&nbsp; <b>'.$search_keyword.'</b></div>';





				else





					return;





			}





			exit;





			}	





		





			$args = array( 'numberposts' => $row+1, 'offset'=> $start, 'orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');





			





			// check product sku first.





			$args['meta_query'][] = array('key'=>'_sku','value'=>$search_keyword,'compare'=>'LIKE');





			// check product in stock.





			$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





			





			// set meta to filter by sale/image/price.





			if($sales=='yes')





			$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





			elseif($sales=='no')





			$args['meta_query'][] = array('key' => '_sale_price','value' => '');





			elseif($images=='1')





			$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





			elseif($images=='3')





			$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





			elseif($images=='4')





			$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





			elseif($images=='video')





			$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');





			elseif($price_label=='price')





			$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





			





			





				





			if ($cat_slug != '') {





				$args['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );





				$extra_parameter_admin .= '&scat='.$cat_slug;





				if (get_option('permalink_structure') == '') 





					$extra_parameter .= '&scat='.$cat_slug;





				else





					$extra_parameter .= '/scat/'.$cat_slug;





			} elseif($tag_slug != '') {





				$args['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );





				$extra_parameter_admin .= '&stag='.$tag_slug;





				if (get_option('permalink_structure') == '') 





					$extra_parameter .= '&stag='.$tag_slug;





				else





					$extra_parameter .= '/stag/'.$tag_slug;





			}





			





			$total_args = $args;





			$total_args['numberposts'] = -1;





			$total_args['offset'] = 0;





			





									





			$search_products = get_posts($args);





			if(empty($search_products))





			{





				// unset previous meta.





				unset($args['meta_query']);





				//set new meta to check product title & description





				$args['s'] = $search_keyword;								





				$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





				





				// set meta to filter by sale/image/price.			





				if($sales=='yes')





				$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





				elseif($sales=='no')





				$args['meta_query'][] = array('key' => '_sale_price','value' => '');





				elseif($images=='1')





				$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





				elseif($images=='3')





				$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





				elseif($images=='4')





				$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





				elseif($images=='video')





				$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');				





				elseif($price_label=='price')





				$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





				





				$search_products = get_posts($args);





			}





			





			$html = '<div class="refyn">';			





			$html .= '<p class="rs_result_heading">'.__('Showing all results for your search', 'refyn').' | '.$search_keyword.'</p>';





			





			





			/**





			 * display product list.





			 */





			if ( $search_products && count($search_products) > 0 )





			{					





				$html .= '<style type="text/css">





				.rs_result_heading{margin:15px 0;}





				.ajax-wait{display: none; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; background:url("'.REFYN_IMAGES_URL.'/ajax-loader.gif") no-repeat center center #EDEFF4; opacity: 1;text-align:center;}





				.ajax-wait img{margin-top:14px;}





				.p_data,.r_data,.q_data{display:none;}





				.rs_date{color:#777;font-size:small;}





				.rs_result_row{width:100%;float:left;margin:0px 0 10px;padding :0px 0 10px; 6px;border-bottom:1px solid #c2c2c2;}





				.rs_result_row:hover{opacity:1;}





				.rs_rs_avatar{width:64px;margin-right:10px;overflow: hidden;float:left; text-align:center;}





				.rs_rs_avatar img{width:100%;height:auto; padding:0 !important; margin:0 !important; border: none !important;}





				.rs_rs_name{margin-left:0px;}





				.rs_content{margin-left:74px;}





				.rs_more_result{display:none;width:240px;text-align:center;position:fixed;bottom:50%;left:50%;margin-left:-125px;background-color: black;opacity: .75;color: white;padding: 10px;border-radius:10px;-webkit-border-radius: 10px;-moz-border-radius: 10px}





				.rs_rs_price .oldprice{text-decoration:line-through; font-size:80%;}





				#page .rs_result_row .price_sale {color: #77a464;}





				</style>';





				$html .= '<div class="rs_ajax_search_content">';





				$text_lenght = $setting_options['refyn_search_text_lenght'];





				





				$sku_enable = $setting_options['refyn_search_sku_enable'];





				





				if(!empty($sku_enable) && $sku_enable == 'yes')





				$show_sku=true;





				$price_enable = $setting_options['refyn_search_price_enable'];





				if(!empty($price_enable) && $price_enable == 'yes')





				$show_price=true;





				





				$category_enable = $setting_options['refyn_search_categories_enable'];





				if(!empty($category_enable) && $category_enable == 'yes')





				$show_categories=true;





				





				$protag_enable = $setting_options['refyn_search_tags_enable'];





				if(!empty($protag_enable) && $protag_enable == 'yes')





				$show_tags=true;





				





				$addtocart_enable = $setting_options['refyn_search_addtocart_enable'];





				if(!empty($addtocart_enable) && $addtocart_enable == 'yes')





				$show_addtocart=true;





				





				foreach ( $search_products as $product ) {





					$link_detail = get_permalink($product->ID);





					





					$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID,'shop_catalog',64,64);





					





					$product_sku_output = Refyn_Search_Shortcodes::get_product_sku($product->ID, $show_sku);





					$product_price_output = Refyn_Search_Shortcodes::get_product_price($product->ID, $show_price);





						





					$product_cats_output = Refyn_Search_Shortcodes::get_product_categories($product->ID, $show_categories);





					





					$product_tags_output = Refyn_Search_Shortcodes::get_product_tags($product->ID, $show_tags);





					$addtocart_output = Refyn_Search_Shortcodes::get_product_addtocart($product->ID, $show_addtocart);











					$product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_content ) ) ),$text_lenght,'...');





					if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_excerpt ) ) ),$text_lenght,'...');





					





					/**





					 *  check product on sale.





					 */





					global $wpdb;





					$is_sales_price = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sale_price' && meta_value != '' && post_id = ".$product->ID, '') );





					





					$product_title = (strlen(stripslashes( $product->post_title)) > 65) ? substr(stripslashes( $product->post_title),0,65).'...' : stripslashes( $product->post_title);





					





					





					// display product.





					if($is_sales_price==1){





						$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .= '<p class="row"><span class="price">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}else{





						$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price_sale">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .='<p class="row"><span class="price_sale">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}





					$end_row--;





					if ($end_row < 1) break;





				}





				





				/**





				 * check products increase limit row (default 12).





				 */





				if ( count($search_products) > $row ) 





				{





					$refyn_get_result_search_page = wp_create_nonce("refyn-get-result-search-page");





					





					$html .= '<div id="search_more_rs"></div><div style="clear:both"></div><div id="rs_more_check"></div><div class="rs_more_result"><span class="p_data">'.($psp + 1).'</span><img src="'.REFYN_IMAGES_URL.'/more-results-loader.gif" /><div><em>'.__('Loading More Results...', 'refyn').'</em></div></div>';





					$html .= "<script>jQuery(document).ready(function() {





						var search_rs_obj = jQuery('#rs_more_check');





						var is_loading = false;





						





						function auto_click_more() {





							if (is_loading == false) {





								var visibleAtTop = search_rs_obj.offset().top + search_rs_obj.height() >= jQuery(window).scrollTop();





								var visibleAtBottom = search_rs_obj.offset().top <= jQuery(window).scrollTop() + jQuery(window).height();





								if (visibleAtTop && visibleAtBottom) {





									is_loading = true;





									jQuery('.rs_more_result').fadeIn('normal');





									var p_data_obj = jQuery('.rs_more_result .p_data');





									var p_data = p_data_obj.html();





									p_data_obj.html('');





									var urls = '&psp='+p_data+'&row=".$row."&q=".$search_keyword.$extra_parameter_admin."&action=refyn_get_result_search_page&security=".$refyn_get_result_search_page."';





									jQuery.post('".admin_url( 'admin-ajax.php', 'relative' )."', urls, function(theResponse){





										if(theResponse != ''){





											var num = parseInt(p_data)+1;





											p_data_obj.html(num);





											jQuery('#search_more_rs').append(theResponse);





											is_loading = false;





											jQuery('.rs_more_result').fadeOut('normal');





										}else{





											jQuery('.rs_more_result').html('<em>".__('No More Results to Show', 'refyn')."</em>').fadeOut(2000);





										}





									});





									return false;





								}





							}





						}





						jQuery(window).scroll(function(){





							auto_click_more();





						});





						auto_click_more();						





						});</script>";





				}





			}





			





			else 





			{





				





				$html .= '<div class="rs_ajax_search_content">';





				





				/*--- PhpSpellChecker ---*/





				





				$spell_suggestions = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=spellcheck&q='.urlencode($search_keyword));





  				$all_suggestion = json_decode($spell_suggestions, true);





				$spellResult=$all_suggestion['result'];





				$search_products1 = array();





				$checked_soundas = 0;





				





				





				





				// Elchanan 23-NOV-2015 Try to Translate!





				//$translate = "";





				if (strlen($search_keyword) > 4 && count($spellResult) < 2)





				{





				$google_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=google_translate&q='.urlencode($search_keyword));





  				$google_result = json_decode($google_suggestion, true);





				if($google_result['error'])





				echo $google_result['error'];





				





				if(!empty($google_result['result'])){





					$translate = strtolower($google_result['result']);





					if (!$translate || $translate != $search_keyword ) // if FALSE translate was failed





						$search_keyword = $translate;	





					else





						$translate = ""; // set if off for no use in Yahoo	





					}





				}





				





				





				/**





				 * is suggestion key found.





				 */





				





				if( $spellResult )





				{





					/**





					 * loop to find products by suggested key.





					 */





					





					foreach ($spellResult as $rows)





					{





						// count suggestion word





						if(count($rows)<2)





						{





							global $wpdb;











							//echo count($query);





							//echo "|->";   print_r ($query); exit;





							$soundex_match = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=soundex_match&q='.urlencode($search_keyword).'&localDB='.serialize($query) );





  							$res1 = json_decode($soundex_match, true);





							





							$checked_soundas=$res1['checked_soundas'];





							if(!empty($res1['soundex'])){





							foreach($res1['soundex'] as $soundex){





								$rows[]=$soundex;	





							}





							}





							if(!empty($res1['checked_soundas']))





							$checked_soundas=$res1['checked_soundas'];





							





						





						}





						





						// Elchanan 28-OCT-2015		Check if kw has been used before and in the LOG?





						 global $wpdb;





				





						$lev = soundex ($search_keyword);





						$logs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=logs&q='.urlencode($search_keyword));





  						$query = json_decode($logs, true);





						 if (!empty($query)) 





						 {





							 $postid = $query['postid'];





							 $search_products = get_post($postid );





							 //echo "postid=$postid | tit=".$search_products->post_title;





							 $rows[] = $search_products->post_title;





						 }





						 else 





						 {





							 // try OR soundex = '$lev'





							 //$lev = substr($lev,0,3); 





							$revlogs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=reverselogs&q='.$lev);





  							$query = json_decode($revlogs, true);





							 if (!empty($query)) 





							 {





								 $postid = $query['postid'];





								 $search_products = get_post($postid );





								 //echo "lev=$lev | tit=".$search_products->post_title;





								 $rows[] = $search_products->post_title;





							 }





						 }





						





						// ELchnanan 30-NOV    Get yahoo suggestion start =====									





						





						$yahoo_result = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate&q='.urlencode($search_keyword));





						$yahoo_result = json_decode($yahoo_result, true);





						$answer = "";





						$line_of_cats = "";





						//echo "Y="; var_dump ($yahoo_result);





						if(!empty($yahoo_result))





						{





						   $i=0;						





						   foreach ($yahoo_result as $word)





						   {





						   		if (trim(strtolower($search_keyword)) == trim(strtolower($word)) ) continue;





							   $word = str_ireplace($search_keyword, '', $word);	





							   $word = preg_replace('/\b\w\b(\s|.\s)?/', '', $word);





							   $word = remove_stop_words ($word);





							   if (strlen($word) < 2) continue;





							   $line_of_cats .= " ". $word;





							   // one result is enough to know what is all about -- and do it to the 1st occurance





							   if (!$i) $answer = $word;





							   $i++;





						   }





				





						$alt_seo = 	ltrim($answer);	





						$query = '<a href="'.$link_search.$alt_seo.'/"><font color=blue>' .ucwords($alt_seo) . '</font></a> ';





							// Show highlight of what I found





						echo "<div style='font-size:12px;margin:0;border-top:none;' class='ajax_search_content_title mika'>You might be interested: ".$query."&nbsp;<img src='".plugin_dir_path( __FILE__ )."classes/question-mark.png' height='13' width='13' title='Maybe it related to: " . $line_of_cats	."?'> </div>\n";	





						$rows[] = $answer;





						$search_keyword = $answer;





						





						}





	











					





						// Insert what I found immediatly to cp_refyn_keywords table





						if (strlen($answer) >= 3)





						{





						   $data = array ( 'kw' => $search_keyword, 





										   'times' => 1, 





										   'seo' => $seo, 





										   'the_kw_user_selected' => $answer,





										   'source' => 'ML',





                                           'ip' => $ip ,





										   'soundex' => soundex($answer) );			





						   //$query = $wpdb->insert( "cp_refyn_keywords", $data );





						   $insertkeyword = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=insertkeyword&data='.serialize($data));





  							$query = json_decode($insertkeyword, true);





							





						}





						





						// ====== Get yahoo suggestion stop =====





						





						// Elchanan 1-OCT-2015 : Two or more words in a query		





						/*	start two words */





						if (str_word_count($search_keyword) > 1)





						{





							$array = explode (" ", $search_keyword);





							$list  = "";





							$toEnd = count($array);





							foreach ($array as $word)





							{





							  if (0 === --$toEnd)





							  {





								 // last value





							if (empty($word)) continue;





							$list = $list . " like '%". $word ."%' ";





								}





							  else





							  {





							if (empty($word)) continue;  





							$list = $list . " like '%". $word ."%' AND post_content ";





							  }





							 }











				





							global $wpdb;





							$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_content ". $list, ARRAY_N );





							if (!empty($query))





							{





									foreach( $query as $key => $value)





									{





										$rows[] = $value[0];





									}





							}





			





						}





			





						//	Elchanan 15-OCT-2015 try these History rows that I kept





						$rows[] = $history_kw_table['seo'];





			





						// Elchanan 13 OCT 2015 - check if exists in post_excerpt  





						global $wpdb;





						$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_excerpt like '%". $search_keyword ."%' LIMIT 0, 10", ARRAY_N );





						if (!empty($query))





						{





								foreach( $query as $key => $value)





								{





									$rows[] = $value[0];





									//echo "245->".$value[0];





								}





						}





			





						/* end two or more	  */





						





						 





						





						foreach ($rows as $q2)





						{





							$args = array( 'orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');





							





							// check product sku first.





							$args['meta_query'][] = array('key'=>'_sku','value'=>$q2,'compare'=>'LIKE');





							// check product in stock.





							$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





							





							// set meta to filter by sale/image/price.			





							if($sales=='yes')





							$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





							elseif($sales=='no')





							$args['meta_query'][] = array('key' => '_sale_price','value' => '');





							elseif($images=='1')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





							elseif($images=='3')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





							elseif($images=='4')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





							elseif($images=='video')





							$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');





							





							elseif($price_label=='price')





							$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





							





							if ($cat_slug != '') {





								$args['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );





								$extra_parameter_admin .= '&scat='.$cat_slug;





								if (get_option('permalink_structure') == '') 





									$extra_parameter .= '&scat='.$cat_slug;





								else





									$extra_parameter .= '/scat/'.$cat_slug;





							} elseif($tag_slug != '') {





								$args['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );





								$extra_parameter_admin .= '&stag='.$tag_slug;





								if (get_option('permalink_structure') == '') 





									$extra_parameter .= '&stag='.$tag_slug;





								else





									$extra_parameter .= '/stag/'.$tag_slug;





							}





							





							$total_args = $args;





							$total_args['numberposts'] = -1;





							$total_args['offset'] = 0;





							





							$search_products = get_posts($args);





							if(empty($search_products))





							{





								// unset previous meta.





								unset($args['meta_query']);





								//set new meta to check product title & description





								$args['s'] = $q2;								





								$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');				





				





								// set meta to filter by sale/image/price.			





								if($sales=='yes')





								$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





								elseif($sales=='no')





								$args['meta_query'][] = array('key' => '_sale_price','value' => '');





								elseif($images=='1')





								$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





								elseif($images=='3')





								$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





								elseif($images=='4')





								$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





								elseif($images=='video')





								$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');								





								elseif($price_label=='price')





								$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





								





								$search_products = get_posts($args);





							}





							





							





							





							





							/**





							 * set suggested products in an array.





							 */





							if ( $search_products && count($search_products) > 0 ) {





												





									foreach($search_products as $search_products_single)





									{





										// check duplicate product.





										if(!in_array($search_products_single, $search_products1))





										array_push($search_products1, $search_products_single);





									}





									





									





							}





							





						}





							





							





							/**





							 * display suggested products.





							 */





							if ( $search_products1 && count($search_products1) > 0 ) 





							{





								$html .= '<style type="text/css">





								.rs_result_heading{margin:15px 0;}





								.ajax-wait{display: none; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; background:url("'.REFYN_IMAGES_URL.'/ajax-loader.gif") no-repeat center center #EDEFF4; opacity: 1;text-align:center;}





								.ajax-wait img{margin-top:14px;}





								.p_data,.r_data,.q_data{display:none;}





								.rs_date{color:#777;font-size:small;}





								.rs_result_row{width:100%;float:left;margin:0px 0 10px;padding :0px 0 10px; 6px;border-bottom:1px solid #c2c2c2;}





								.rs_result_row:hover{opacity:1;}





								.rs_rs_avatar{width:64px;margin-right:10px;overflow: hidden;float:left; text-align:center;}





								.rs_rs_avatar img{width:100%;height:auto; padding:0 !important; margin:0 !important; border: none !important;}





								.rs_rs_name{margin-left:0px;}





								.rs_content{margin-left:74px;}





								.rs_more_result{display:none;width:240px;text-align:center;position:fixed;bottom:50%;left:50%;margin-left:-125px;background-color: black;opacity: .75;color: white;padding: 10px;border-radius:10px;-webkit-border-radius: 10px;-moz-border-radius: 10px}





								.rs_rs_price .oldprice{text-decoration:line-through; font-size:80%;}





								</style>';





								$text_lenght = $setting_options['refyn_search_text_lenght'];





								foreach ( $search_products1 as $product ) 





								{





									$link_detail = get_permalink($product->ID);





									





									$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID,'shop_catalog',64,64);





									





									$product_sku_output = Refyn_Search_Shortcodes::get_product_sku($product->ID, $show_sku);





									$product_price_output = Refyn_Search_Shortcodes::get_product_price($product->ID, $show_price);





										





									$product_cats_output = Refyn_Search_Shortcodes::get_product_categories($product->ID, $show_categories);





									





									$product_tags_output = Refyn_Search_Shortcodes::get_product_tags($product->ID, $show_tags);





									





									$product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_content ) ) ),$text_lenght,'...');





									if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_excerpt ) ) ),$text_lenght,'...');





									





									global $wpdb;





									$is_sales_price = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sale_price' && meta_value != '' && post_id = ".$product->ID, '') );





									$product_title = (strlen(stripslashes( $product->post_title)) > 65) ? substr(stripslashes( $product->post_title),0,65).'...' : stripslashes( $product->post_title);





									if($is_sales_price==1){





										/*$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





									else





										$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price_sale">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';*/





										





										$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .= '<p class="row"><span class="price">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}else{





						$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price_sale">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .='<p class="row"><span class="price_sale">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}





									





									$end_row1--;





									if ($end_row1 < 1) break;





								}





								





								/**





								 * check products increase limit row (default 12).





								 */





								if ( count($search_products1) > $row ) 





								{





									$refyn_get_result_search_page = wp_create_nonce("refyn-get-result-search-page");





									





									$html .= '<div id="search_more_rs"></div><div style="clear:both"></div><div id="rs_more_check"></div><div class="rs_more_result"><span class="p_data">'.($psp + 1).'</span><img src="'.REFYN_IMAGES_URL.'/more-results-loader.gif" /><div><em>'.__('Loading More Results...', 'refyn').'</em></div></div>';





									$html .= "<script>jQuery(document).ready(function() {





										var search_rs_obj = jQuery('#rs_more_check');





										var is_loading = false;





										





										function auto_click_more() {





											if (is_loading == false) {





												var visibleAtTop = search_rs_obj.offset().top + search_rs_obj.height() >= jQuery(window).scrollTop();





												var visibleAtBottom = search_rs_obj.offset().top <= jQuery(window).scrollTop() + jQuery(window).height();





												if (visibleAtTop && visibleAtBottom) {





													is_loading = true;





													jQuery('.rs_more_result').fadeIn('normal');





													var p_data_obj = jQuery('.rs_more_result .p_data');





													var p_data = p_data_obj.html();





													p_data_obj.html('');





													var urls = '&psp='+p_data+'&row=".$row."&q=".$search_keyword.$extra_parameter_admin."&action=refyn_get_result_search_page&security=".$refyn_get_result_search_page."';





													jQuery.post('".admin_url( 'admin-ajax.php', 'relative' )."', urls, function(theResponse){





														if(theResponse != ''){





															var num = parseInt(p_data)+1;





															p_data_obj.html(num);





															jQuery('#search_more_rs').append(theResponse);





															is_loading = false;





															jQuery('.rs_more_result').fadeOut('normal');





														}else{





															jQuery('.rs_more_result').html('<em>".__('No More Results to Show', 'refyn')."</em>').fadeOut(2000);





														}





													});





													return false;





												}





											}





										}





										jQuery(window).scroll(function(){





											auto_click_more();





										});





										auto_click_more();						





										});</script>";





								}





				





								





							





							}





							else





							{								





								// check if soundas checked before.





								if($checked_soundas==0)





								{





									$rows = array();





									global $wpdb;





									$query = $wpdb->get_results("SELECT p.post_title FROM $wpdb->posts p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N );





									//echo count($query);





									//echo "|->";   print_r ($query); exit;





									





									$soundex_match = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=soundex_match&q='.urlencode($search_keyword).'&localDB='.serialize($query));





  							$res1 = json_decode($soundex_match, true);





							





							$checked_soundas=$res1['checked_soundas'];





							if(!empty($res1['soundex'])){





							foreach($res1['soundex'] as $soundex){





								$rows[]=$soundex;	





							}





							}





									





									





									// Elchanan 28-OCT-2015		Check if kw has been used before and in the LOG?





						 global $wpdb;





				





						$lev = soundex ($search_keyword);





						$logs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=logs&q='.urlencode($search_keyword));





  						$query = json_decode($logs, true);





						 if (!empty($query)) 





						 {





							 $postid = $query['postid'];





							 $search_products = get_post($postid );





							 //echo "postid=$postid | tit=".$search_products->post_title;





							 $rows[] = $search_products->post_title;





						 }





						 else 





						 {





							 // try OR soundex = '$lev'





							 //$lev = substr($lev,0,3); 





							 $revlogs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=reverselogs&q='.$lev);





  							 $query = json_decode($revlogs, true);





							 if (!empty($query)) 





							 {





								 $postid = $query['postid'];





								 $search_products = get_post($postid );





								 //echo "lev=$lev | tit=".$search_products->post_title;





								 $rows[] = $search_products->post_title;





							 }





						 }





						





						// ELchnanan 30-NOV    Get yahoo suggestion start =====									





						





									//if ($translate == "") echo "is null - tran";





									//else echo "NOT n=$translate";





									$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate&q='.urlencode($search_keyword));





  						$yahoo_result = json_decode($yahoo_suggestion, true);





						if(!empty($yahoo_result['result'])){





						if ($yahoo_result['result']['bossresponse']['related']['count'] == 0)





						{





							





							$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate_extra&q='.urlencode($search_keyword));





  						$yahoo_result = json_decode($yahoo_suggestion, true);





							





							





							$answer = remove_stop_words ($yahoo_result['result']['bossresponse']['web']['results'][0]['title']); 		// one result is enough to know what is all about





							//echo "Possible="; print_r ($json->bossresponse->web->results);





							





							// try to get better answer





							$seo = "";





							$wordsCount = 999;





							foreach ($yahoo_result['result']['bossresponse']['web']['results'] as $word)





							{





								$tmp = remove_stop_words ($word['title']);





								//echo $tmp."*L=".str_word_count ($tmp);





								if (str_word_count ($tmp) < $wordsCount)	$seo = $tmp;





								$wordsCount = str_word_count ($tmp);





							}











							if (strlen($answer) < 6) 		





								$answer = $yahoo_result['result']['bossresponse']['web']['results'][0]['abstract'];





								





						}





						}





						$answer .= " ". $yahoo_result['result']['bossresponse']['related']['results'][0]['suggestion'];





									/*require $_SERVER['DOCUMENT_ROOT']."/yahoo-api/OAuth.php";





									require $_SERVER['DOCUMENT_ROOT']."/yahoo-api/yahoo_api.php";





			





									//$json 	= call_yahoo_api ($search_keyword, 'spelling');	





									//$answer = $json->bossresponse->spelling->results[0];





									//print_r ($answer);





			





				





									$json 	= call_yahoo_api ($search_keyword, 'related');	





									$answer = "";





									if ($json->bossresponse->related->count == 0)





									{





										$url	= "https://yboss.yahooapis.com/ysearch/web";							// Try web search instead "related"





										$json	= call_yahoo_api ($search_keyword, 'web');





										





										$answer = remove_stop_words ($json->bossresponse->web->results[0]->title); 		// one result is enough to know what is all about





										//echo "Possible="; print_r ($json->bossresponse->web->results);





										





										// try to get better answer





										$seo = "";





										$wordsCount = 999;





										foreach ($json->bossresponse->web->results as $word)





										{





											$tmp = remove_stop_words ($word->title);





											//echo $tmp."*L=".str_word_count ($tmp);





											if (str_word_count ($tmp) < $wordsCount)	$seo = $tmp;





											$wordsCount = str_word_count ($tmp);





										}





			





			





										if (strlen($answer) < 6) 		





											$answer = $json->bossresponse->web->results[0]->abstract;





									}





			





									$answer .= " ". $json->bossresponse->related->results[0]->suggestion;	*/			// one result is enough to know what is all about





									$answer = str_ireplace($search_keyword, '', $answer);								//	remove same words						





									//echo "B:".$answer;





									$answer = remove_stop_words ($answer);												//  remove stop words





									//echo " | A:".$answer;





									$rows[] = $answer;





									





									// Insert what I found immediatly to cp_refyn_keywords table





									if (strlen($answer) >= 3)





									{





									   $data = array ( 'kw' => $search_keyword, 





													   'times' => 1, 





													   'seo' => $seo, 





													   'the_kw_user_selected' => $answer,





													   'source' => 'ML',





													   'soundex' => soundex($answer) );			





									  // $query = $wpdb->insert( "cp_refyn_keywords", $data );





									  $insertkeyword = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=insertkeyword&data='.serialize($data));





  							$query = json_decode($insertkeyword, true);





									}





			





									





									// ====== Get yahoo suggestion stop =====





									





									// Elchanan 1-OCT-2015 : Two or more words in a query		





									/*	start two words */





									if (str_word_count($search_keyword) > 1)





									{





										$array = explode (" ", $search_keyword);





										$list  = "";





										$toEnd = count($array);





										foreach ($array as $word)





										{





										  if (0 === --$toEnd)





										  {





											 // last value





										if (empty($word)) continue;





										$list = $list . " like '%". $word ."%' ";





											}





										  else





										  {





										if (empty($word)) continue;  





										$list = $list . " like '%". $word ."%' AND post_content ";





										  }





										 }











							





										global $wpdb;





										$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_content ". $list, ARRAY_N );





										if (!empty($query))





										{





												foreach( $query as $key => $value)





												{





													$rows[] = $value[0];





												}





										}





						





									}





						





									//	Elchanan 15-OCT-2015 try these History rows that I kept





									$rows[] = $history_kw_table['seo'];





			





						





									// Elchanan 13 OCT 2015 - check if exists in post_excerpt  





									global $wpdb;





									$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_excerpt like '%". $search_keyword ."%' LIMIT 0, 10", ARRAY_N );





									if (!empty($query))





									{





											foreach( $query as $key => $value)





											{





												$rows[] = $value[0];





												//echo "245->".$value[0];





											}





									}





						





									/* end two or more	  */





									





									//echo "<pre>";print_r($rows);echo "<pre>";exit;





									foreach ($rows as $q2)





									{





										$args = array( 'orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');





										





										// check product sku first.





										$args['meta_query'][] = array('key'=>'_sku','value'=>$q2,'compare'=>'LIKE');





										// check product in stock.





										$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





										





										// set meta to filter by sale/image/price.			





										if($sales=='yes')





										$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





										elseif($sales=='no')





										$args['meta_query'][] = array('key' => '_sale_price','value' => '');





										elseif($images=='1')





										$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





										elseif($images=='3')





										$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





										elseif($images=='4')





										$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





										elseif($images=='video')





										$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');





										





										elseif($price_label=='price')





										$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





										





										if ($cat_slug != '') {





											$args['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );





											$extra_parameter_admin .= '&scat='.$cat_slug;





											if (get_option('permalink_structure') == '') 





												$extra_parameter .= '&scat='.$cat_slug;





											else





												$extra_parameter .= '/scat/'.$cat_slug;





										} elseif($tag_slug != '') {





											$args['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );





											$extra_parameter_admin .= '&stag='.$tag_slug;





											if (get_option('permalink_structure') == '') 





												$extra_parameter .= '&stag='.$tag_slug;





											else





												$extra_parameter .= '/stag/'.$tag_slug;





										}





										





										$total_args = $args;





										$total_args['numberposts'] = -1;





										$total_args['offset'] = 0;





										





										$search_products = get_posts($args);





										if(empty($search_products))





										{





											// unset previous meta.





											unset($args['meta_query']);





											//set new meta to check product title & description





											$args['s'] = $q2;								





											$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');				





							





											// set meta to filter by sale/image/price.			





											if($sales=='yes')





											$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





											elseif($sales=='no')





											$args['meta_query'][] = array('key' => '_sale_price','value' => '');





											elseif($images=='1')





											$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





											elseif($images=='3')





											$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





											elseif($images=='4')





											$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





											elseif($images=='video')





											$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');								





											elseif($price_label=='price')





											$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





											





											$search_products = get_posts($args);





										}





										





										





										





										





										/**





										 * set suggested products in an array.





										 */





										if ( $search_products && count($search_products) > 0 ) {





															





												foreach($search_products as $search_products_single)





												{





													// check duplicate product.





													if(!in_array($search_products_single, $search_products1))





													array_push($search_products1, $search_products_single);





												}





												





												





										}





										





									}





									





									/**





									 * display suggested products.





									 */





									if ( $search_products1 && count($search_products1) > 0 ) 





									{





										$html .= '<style type="text/css">





										.rs_result_heading{margin:15px 0;}





										.ajax-wait{display: none; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; background:url("'.REFYN_IMAGES_URL.'/ajax-loader.gif") no-repeat center center #EDEFF4; opacity: 1;text-align:center;}





										.ajax-wait img{margin-top:14px;}





										.p_data,.r_data,.q_data{display:none;}





										.rs_date{color:#777;font-size:small;}





										.rs_result_row{width:100%;float:left;margin:0px 0 10px;padding :0px 0 10px; 6px;border-bottom:1px solid #c2c2c2;}





										.rs_result_row:hover{opacity:1;}





										.rs_rs_avatar{width:64px;margin-right:10px;overflow: hidden;float:left; text-align:center;}





										.rs_rs_avatar img{width:100%;height:auto; padding:0 !important; margin:0 !important; border: none !important;}





										.rs_rs_name{margin-left:0px;}





										.rs_content{margin-left:74px;}





										.rs_more_result{display:none;width:240px;text-align:center;position:fixed;bottom:50%;left:50%;margin-left:-125px;background-color: black;opacity: .75;color: white;padding: 10px;border-radius:10px;-webkit-border-radius: 10px;-moz-border-radius: 10px}





										.rs_rs_price .oldprice{text-decoration:line-through; font-size:80%;}





										</style>';





										$text_lenght = $setting_options['refyn_search_text_lenght'];





										foreach ( $search_products1 as $product ) 





										{





											$link_detail = get_permalink($product->ID);





											





											$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID,'shop_catalog',64,64);





											





											$product_sku_output = Refyn_Search_Shortcodes::get_product_sku($product->ID, $show_sku);





											$product_price_output = Refyn_Search_Shortcodes::get_product_price($product->ID, $show_price);





												





											$product_cats_output = Refyn_Search_Shortcodes::get_product_categories($product->ID, $show_categories);





											





											$product_tags_output = Refyn_Search_Shortcodes::get_product_tags($product->ID, $show_tags);





											





											$product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_content ) ) ),$text_lenght,'...');





											if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_excerpt ) ) ),$text_lenght,'...');





											





											global $wpdb;





											$is_sales_price = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sale_price' && meta_value != '' && post_id = ".$product->ID, '') );





											$product_title = (strlen(stripslashes( $product->post_title)) > 65) ? substr(stripslashes( $product->post_title),0,65).'...' : stripslashes( $product->post_title);





											if($is_sales_price==1){





												/*$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





											else





												$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price_sale">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';*/





	$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .= '<p class="row"><span class="price">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}else{





						$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price_sale">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .='<p class="row"><span class="price_sale">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





											





											}





											$end_row1--;





											if ($end_row1 < 1) break;





										}





										





										/**





										 * check products increase limit row (default 12).





										 */





										if ( count($search_products1) > $row ) 





										{





											$refyn_get_result_search_page = wp_create_nonce("refyn-get-result-search-page");





											





											$html .= '<div id="search_more_rs"></div><div style="clear:both"></div><div id="rs_more_check"></div><div class="rs_more_result"><span class="p_data">'.($psp + 1).'</span><img src="'.REFYN_IMAGES_URL.'/more-results-loader.gif" /><div><em>'.__('Loading More Results...', 'refyn').'</em></div></div>';





											$html .= "<script>jQuery(document).ready(function() {





												var search_rs_obj = jQuery('#rs_more_check');





												var is_loading = false;





												





												function auto_click_more() {





													if (is_loading == false) {





														var visibleAtTop = search_rs_obj.offset().top + search_rs_obj.height() >= jQuery(window).scrollTop();





														var visibleAtBottom = search_rs_obj.offset().top <= jQuery(window).scrollTop() + jQuery(window).height();





														if (visibleAtTop && visibleAtBottom) {





															is_loading = true;





															jQuery('.rs_more_result').fadeIn('normal');





															var p_data_obj = jQuery('.rs_more_result .p_data');





															var p_data = p_data_obj.html();





															p_data_obj.html('');





															var urls = '&psp='+p_data+'&row=".$row."&q=".$search_keyword.$extra_parameter_admin."&action=refyn_get_result_search_page&security=".$refyn_get_result_search_page."';





															jQuery.post('".admin_url( 'admin-ajax.php', 'relative' )."', urls, function(theResponse){





																if(theResponse != ''){





																	var num = parseInt(p_data)+1;





																	p_data_obj.html(num);





																	jQuery('#search_more_rs').append(theResponse);





																	is_loading = false;





																	jQuery('.rs_more_result').fadeOut('normal');





																}else{





																	jQuery('.rs_more_result').html('<em>".__('No More Results to Show', 'refyn')."</em>').fadeOut(2000);





																}





															});





															return false;





														}





													}





												}





												jQuery(window).scroll(function(){





													auto_click_more();





												});





												auto_click_more();						





												});</script>";





										}





						





										





									





									}





									else





									{





										$html .= '<p style="text-align:left; margin-top:20px;">'.__('Nothing Found for '.$search_keyword.'! Please refine your search and try again.', 'refyn').'</p>';





									}





									





									





									





																		





									





								}





								else





								{





									$html .= '<p style="text-align:left; margin-top:20px;">'.__('Nothing Found for '.$search_keyword.'! Please refine your search and try again.', 'refyn').'</p>';





								}								





							





							}





								





						





					}





	





					//$spellCheck->clearWarnings(); // clear all previous warnings 





					//$spellCheck->clearErrors(); // clear all previous errors 





					





					





				}





				else





				{	





					$rows = array();





					global $wpdb;





					$query = $wpdb->get_results("SELECT p.post_title FROM $wpdb->posts p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N );





					//echo "count=".count($query);





					//echo "|->";   print_r ($query); exit;





					





					$soundex_match = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=soundex_match&q='.urlencode($search_keyword).'&localDB='.serialize($query) );





  							$res1 = json_decode($soundex_match, true);





							





							$checked_soundas=$res1['checked_soundas'];





							if(!empty($res1['soundex'])){





							foreach($res1['soundex'] as $soundex){





								$rows[]=$soundex;	





							}





							}





			





					// Elchanan 28-OCT-2015		Check if kw has been used before and in the LOG?





						 global $wpdb;





				





						$lev = soundex ($search_keyword);





						$logs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=logs&q='.urlencode($search_keyword));





  						$query = json_decode($logs, true);





						 if (!empty($query)) 





						 {





							 $postid = $query['postid'];





							 $search_products = get_post($postid );





							 //echo "postid=$postid | tit=".$search_products->post_title;





							 $rows[] = $search_products->post_title;





						 }





						 else 





						 {





							 // try OR soundex = '$lev'





							 //$lev = substr($lev,0,3); 





							 $revlogs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=reverselogs&q='.$lev);





  							 $query = json_decode($revlogs, true);





							 if (!empty($query)) 





							 {





								 $postid = $query['postid'];





								 $search_products = get_post($postid );





								 //echo "lev=$lev | tit=".$search_products->post_title;





								 $rows[] = $search_products->post_title;





							 }





						 }





						





						// ELchnanan 30-NOV    Get yahoo suggestion start =====									





						





					//if ($translate == "") echo "is null - tran";





					//else echo "NOT n=$translate";





					





					$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate&q='.urlencode($search_keyword));





  						$yahoo_result = json_decode($yahoo_suggestion, true);





						if(!empty($yahoo_result['result'])){





						if ($yahoo_result['result']['bossresponse']['related']['count'] == 0)





						{





							





							$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate_extra&q='.urlencode($search_keyword));





  						$yahoo_result = json_decode($yahoo_suggestion, true);





							





							





							$answer = remove_stop_words ($yahoo_result['result']['bossresponse']['web']['results'][0]['title']); 		// one result is enough to know what is all about





							//echo "Possible="; print_r ($json->bossresponse->web->results);





							





							// try to get better answer





							$seo = "";





							$wordsCount = 999;





							foreach ($yahoo_result['result']['bossresponse']['web']['results'] as $word)





							{





								$tmp = remove_stop_words ($word['title']);





								//echo $tmp."*L=".str_word_count ($tmp);





								if (str_word_count ($tmp) < $wordsCount)	$seo = $tmp;





								$wordsCount = str_word_count ($tmp);





							}











							if (strlen($answer) < 6) 		





								$answer = $yahoo_result['result']['bossresponse']['web']['results'][0]['abstract'];





								





						}





						}





						$answer .= " ". $yahoo_result['result']['bossresponse']['related']['results'][0]['suggestion'];





					/*require $_SERVER['DOCUMENT_ROOT']."/yahoo-api/OAuth.php";





					require $_SERVER['DOCUMENT_ROOT']."/yahoo-api/yahoo_api.php";





					//$json 	= call_yahoo_api ($search_keyword, 'spelling');	





					//$answer = $json->bossresponse->spelling->results[0];





					//print_r ($answer);





					$json 	= call_yahoo_api ($search_keyword, 'related');	





					$answer = "";





					if ($json->bossresponse->related->count == 0)





					{





						$url	= "https://yboss.yahooapis.com/ysearch/web";							// Try web search instead "related"





						$json	= call_yahoo_api ($search_keyword, 'web');





						





						$answer = remove_stop_words ($json->bossresponse->web->results[0]->title); 		// one result is enough to know what is all about





						//echo "Possible="; print_r ($json->bossresponse->web->results);





						





						// try to get better answer





						$seo = "";





						$wordsCount = 999;





						foreach ($json->bossresponse->web->results as $word)





						{





							$tmp = remove_stop_words ($word->title);





							//echo $tmp."*L=".str_word_count ($tmp);





							if (str_word_count ($tmp) < $wordsCount)	$seo = $tmp;





							$wordsCount = str_word_count ($tmp);





						}





						if (strlen($answer) < 6) 		





							$answer = $json->bossresponse->web->results[0]->abstract;





					}





					$answer .= " ". $json->bossresponse->related->results[0]->suggestion;*/				// one result is enough to know what is all about





					$answer = str_ireplace($search_keyword, '', $answer);								//	remove same words						





					//echo "B:".$answer;





					$answer = remove_stop_words ($answer);												//  remove stop words





					//echo " | A:".$answer;





					$rows[] = $answer;





					





					// Insert what I found immediatly to cp_refyn_keywords table





					if (strlen($answer) >= 3)





					{





					   $data = array ( 'kw' => $search_keyword, 





									   'times' => 1, 





									   'seo' => $seo, 





									   'the_kw_user_selected' => $answer,





									   'source' => 'ML',





									   'soundex' => soundex($answer) );			





					   //$query = $wpdb->insert( "cp_refyn_keywords", $data );





					   $insertkeyword = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=insertkeyword&data='.serialize($data));





  							$query = json_decode($insertkeyword, true);





					}





					





					// ====== Get yahoo suggestion stop =====





					





					// Elchanan 1-OCT-2015 : Two or more words in a query		





					/*	start two words */





					if (str_word_count($search_keyword) > 1)





					{





						$array = explode (" ", $search_keyword);





						$list  = "";





						$toEnd = count($array);





						foreach ($array as $word)





						{





						  if (0 === --$toEnd)





						  {





							 // last value





						if (empty($word)) continue;





						$list = $list . " like '%". $word ."%' ";





							}





						  else





						  {





						if (empty($word)) continue;  





						$list = $list . " like '%". $word ."%' AND post_content ";





						  }





						 }











			





						global $wpdb;





						$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_content ". $list, ARRAY_N );





						if (!empty($query))





						{





								foreach( $query as $key => $value)





								{





									$rows[] = $value[0];





								}





						}





		





					}





		





					//	Elchanan 15-OCT-2015 try these History rows that I kept





					$rows[] = $history_kw_table['seo'];





		





					// Elchanan 13 OCT 2015 - check if exists in post_excerpt  





					global $wpdb;





					$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_excerpt like '%". $search_keyword ."%' LIMIT 0, 10", ARRAY_N );





					if (!empty($query))





					{





							foreach( $query as $key => $value)





							{





								$rows[] = $value[0];





								//echo "245->".$value[0];





							}





					}





		





					/* end two or more	  */





					





					//echo "<pre>";print_r($rows);echo "<pre>";exit;





					foreach ($rows as $q2)





					{





						$args = array( 'orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');





						





						// check product sku first.





						$args['meta_query'][] = array('key'=>'_sku','value'=>$q2,'compare'=>'LIKE');





						// check product in stock.





						$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





						





						// set meta to filter by sale/image/price.			





						if($sales=='yes')





						$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





						elseif($sales=='no')





						$args['meta_query'][] = array('key' => '_sale_price','value' => '');





						elseif($images=='1')





						$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





						elseif($images=='3')





						$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





						elseif($images=='4')





						$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





						elseif($images=='video')





						$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');





						





						elseif($price_label=='price')





						$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





						





						if ($cat_slug != '') {





							$args['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );





							$extra_parameter_admin .= '&scat='.$cat_slug;





							if (get_option('permalink_structure') == '') 





								$extra_parameter .= '&scat='.$cat_slug;





							else





								$extra_parameter .= '/scat/'.$cat_slug;





						} elseif($tag_slug != '') {





							$args['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );





							$extra_parameter_admin .= '&stag='.$tag_slug;





							if (get_option('permalink_structure') == '') 





								$extra_parameter .= '&stag='.$tag_slug;





							else





								$extra_parameter .= '/stag/'.$tag_slug;





						}





						





						$total_args = $args;





						$total_args['numberposts'] = -1;





						$total_args['offset'] = 0;





						





						$search_products = get_posts($args);





						if(empty($search_products))





						{





							// unset previous meta.





							unset($args['meta_query']);





							//set new meta to check product title & description





							$args['s'] = $q2;								





							$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');				





			





							// set meta to filter by sale/image/price.			





							if($sales=='yes')





							$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





							elseif($sales=='no')





							$args['meta_query'][] = array('key' => '_sale_price','value' => '');





							elseif($images=='1')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





							elseif($images=='3')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





							elseif($images=='4')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





							elseif($images=='video')





							$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');								





							elseif($price_label=='price')





							$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





							





							$search_products = get_posts($args);





						}





						





						





						





						





						/**





						 * set soundas products in an array.





						 */





						if ( $search_products && count($search_products) > 0 ) {





											





								foreach($search_products as $search_products_single)





								{





									// check duplicate product.





									if(!in_array($search_products_single, $search_products1))





									array_push($search_products1, $search_products_single);





								}





								





								





						}





						





					}





					





					/**





					 * display soundas products.





					 */





					if ( $search_products1 && count($search_products1) > 0 ) 





					{





						$html .= '<style type="text/css">





						.rs_result_heading{margin:15px 0;}





						.ajax-wait{display: none; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; background:url("'.REFYN_IMAGES_URL.'/ajax-loader.gif") no-repeat center center #EDEFF4; opacity: 1;text-align:center;}





						.ajax-wait img{margin-top:14px;}





						.p_data,.r_data,.q_data{display:none;}





						.rs_date{color:#777;font-size:small;}





						.rs_result_row{width:100%;float:left;margin:0px 0 10px;padding :0px 0 10px; 6px;border-bottom:1px solid #c2c2c2;}





						.rs_result_row:hover{opacity:1;}





						.rs_rs_avatar{width:64px;margin-right:10px;overflow: hidden;float:left; text-align:center;}





						.rs_rs_avatar img{width:100%;height:auto; padding:0 !important; margin:0 !important; border: none !important;}





						.rs_rs_name{margin-left:0px;}





						.rs_content{margin-left:74px;}





						.rs_more_result{display:none;width:240px;text-align:center;position:fixed;bottom:50%;left:50%;margin-left:-125px;background-color: black;opacity: .75;color: white;padding: 10px;border-radius:10px;-webkit-border-radius: 10px;-moz-border-radius: 10px}





						.rs_rs_price .oldprice{text-decoration:line-through; font-size:80%;}





						</style>';





						$text_lenght = $setting_options['refyn_search_text_lenght'];





						foreach ( $search_products1 as $product ) 





						{





							$link_detail = get_permalink($product->ID);





							





							$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID,'shop_catalog',64,64);





							





							$product_sku_output = Refyn_Search_Shortcodes::get_product_sku($product->ID, $show_sku);





							$product_price_output = Refyn_Search_Shortcodes::get_product_price($product->ID, $show_price);





								





							$product_cats_output = Refyn_Search_Shortcodes::get_product_categories($product->ID, $show_categories);





							





							$product_tags_output = Refyn_Search_Shortcodes::get_product_tags($product->ID, $show_tags);





							





							$product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_content ) ) ),$text_lenght,'...');





							if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_excerpt ) ) ),$text_lenght,'...');





							





							global $wpdb;





							$is_sales_price = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sale_price' && meta_value != '' && post_id = ".$product->ID, '') );





							$product_title = (strlen(stripslashes( $product->post_title)) > 65) ? substr(stripslashes( $product->post_title),0,65).'...' : stripslashes( $product->post_title);





							if($is_sales_price==1){





								/*$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





							else





								$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price_sale">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';*/





	$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .= '<p class="row"><span class="price">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}else{





						$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price_sale">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .='<p class="row"><span class="price_sale">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}





							





							$end_row1--;





							if ($end_row1 < 1) break;





						}





						





						/**





						 * check products increase limit row (default 12).





						 */





						if ( count($search_products1) > $row ) 





						{





							$refyn_get_result_search_page = wp_create_nonce("refyn-get-result-search-page");





							





							$html .= '<div id="search_more_rs"></div><div style="clear:both"></div><div id="rs_more_check"></div><div class="rs_more_result"><span class="p_data">'.($psp + 1).'</span><img src="'.REFYN_IMAGES_URL.'/more-results-loader.gif" /><div><em>'.__('Loading More Results...', 'refyn').'</em></div></div>';





							$html .= "<script>jQuery(document).ready(function() {





								var search_rs_obj = jQuery('#rs_more_check');





								var is_loading = false;





								





								function auto_click_more() {





									if (is_loading == false) {





										var visibleAtTop = search_rs_obj.offset().top + search_rs_obj.height() >= jQuery(window).scrollTop();





										var visibleAtBottom = search_rs_obj.offset().top <= jQuery(window).scrollTop() + jQuery(window).height();





										if (visibleAtTop && visibleAtBottom) {





											is_loading = true;





											jQuery('.rs_more_result').fadeIn('normal');





											var p_data_obj = jQuery('.rs_more_result .p_data');





											var p_data = p_data_obj.html();





											p_data_obj.html('');





											var urls = '&psp='+p_data+'&row=".$row."&q=".$search_keyword.$extra_parameter_admin."&action=refyn_get_result_search_page&security=".$refyn_get_result_search_page."';





											jQuery.post('".admin_url( 'admin-ajax.php', 'relative' )."', urls, function(theResponse){





												if(theResponse != ''){





													var num = parseInt(p_data)+1;





													p_data_obj.html(num);





													jQuery('#search_more_rs').append(theResponse);





													is_loading = false;





													jQuery('.rs_more_result').fadeOut('normal');





												}else{





													jQuery('.rs_more_result').html('<em>".__('No More Results to Show', 'refyn')."</em>').fadeOut(2000);





												}





											});





											return false;





										}





									}





								}





								jQuery(window).scroll(function(){





									auto_click_more();





								});





								auto_click_more();						





								});</script>";





						}





		





						





					





					}





					else





					{





						$html .= '<p style="text-align:left; margin-top:20px;">'.__('Nothing Found for '.$search_keyword.'! Please refine your search and try again.', 'refyn').'</p>';





					}								





					





				





				}





			}





			$html .= '</div>'; 





			





			return $html;





		}





		





		// Show History





		else {





			global $remote_API_server, $home_url, $api_key;





			$refn_search_history = file_get_contents( $remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=history' );





        	$refn_search_history = json_decode($refn_search_history, true); 





        	$refn_search_history = isset($refn_search_history['history']) ? $refn_search_history['history'] : array();





			if( $refn_search_history ) {





				$link_search = rtrim( get_permalink(get_option('refyn_search_page_id')), '/' ).'/keyword/';





				$html = '<table class="gradienttable">';





				$index = -1;





				$columns = 3;





				foreach( $refn_search_history as $seach_key ) {





					$index++;





					if( $index != 0 && $index % $columns == 0 ) $html .= '</tr>';





					if( $index % $columns == 0 ) $html .= '<tr>';





					$html .= '<td><a target="_top" href="'. esc_url( $link_search ) . $seach_key .'">'. $seach_key .'</a></td>';





				}





				$html .= '</table>';





			}





			else {





				$html = __('Search data history is empty','refyn');





			}





			return force_balance_tags($html);





		}





	}





	





	public static function get_result_search_page() {





		add_filter( 'posts_search', array('Refyn_Hook_Filter', 'search_by_title_only'), 500, 2 );





		add_filter( 'posts_orderby', array('Refyn_Hook_Filter', 'refyn_posts_orderby'), 500, 2 );





		add_filter( 'posts_request', array('Refyn_Hook_Filter', 'posts_request_unconflict_role_scoper_plugin'), 500, 2);





		global $refyn_id_excludes,$wpdb;





		global $setting_options;





		$psp = 1;





		





		// display items limit.





		$row = 12;





		





		$search_keyword = '';





		$starting_1 = 1;





		$cat_slug = sanitize_text_field( $_REQUEST['scat'] );





		$sales = '';





		$images = '';





		$prices = '';	





		global $remote_API_server, $home_url, $api_key;





		$remote_API_server='https://comfyplane.com/obs_v1/refyn_api.php';





		$home_url=home_url();





		$api_key=$setting_options['refyn_api_key_text'];





		





		// get category name / filter(sale/image/price) name.	





		$segments_key = explode('keyword', $_SERVER['HTTP_REFERER']);





		$segments = explode('/', $segments_key[1]);				





		if (in_array("scat", $segments)) {





			if($segments[2]=='scat')





			$cat_slug = $segments[3];		





			if($segments[4]=='sale')





			$sales = $segments[5];			





			if($segments[4]=='images')





			$images = $segments[5];





			if($segments[4]=='price')





			{





				$price_label = $segments[4];





				$prices = $segments[5];





				$prices_part = explode('-', $prices);	





				$start_prices = $prices_part[0];





				$end_prices = $prices_part[1];





			}		





		}





		else





		{		





			if (in_array("sale", $segments) || in_array("images", $segments) || in_array("price", $segments)) {		





				if($segments[2]=='sale')





				$sales = $segments[3];			





				if($segments[2]=='images')





				$images = $segments[3];





				if($segments[2]=='price')





				{





					$price_label = $segments[2];





					$prices = $segments[3];





					$prices_part = explode('-', $prices);	





					$start_prices = $prices_part[0];





					$end_prices = $prices_part[1];





				}		





			}





		}





		





		





		// get product ids by image filter.





		if($images=='3' || $images=='4')





		{





			$product_images_gallery_3 = array();





			$product_images_gallery_4 = array();





			$product_images = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_product_image_gallery' && meta_value != ''", '') );





			for($i=0;$i<count($product_images);$i++)





			{





				$meta_values = explode(',', $product_images[$i]->meta_value);





				if( count($meta_values)>0 && count($meta_values)<3 )





					array_push($product_images_gallery_3,$product_images[$i]->meta_value);





				if( count($meta_values)>2 )





					array_push($product_images_gallery_4,$product_images[$i]->meta_value);





			}





		}





		





		





		$tag_slug = '';





		$extra_parameter = '';





		$show_price = false;





		$show_categories = false;





		$show_tags = false;





		if (isset( $_REQUEST['psp']) && sanitize_text_field( $_REQUEST['psp']) > 0) $psp = sanitize_text_field( $_REQUEST['psp']);





		if (isset( $_REQUEST['row']) && sanitize_text_field( $_REQUEST['row']) > 0) $row = sanitize_text_field( $_REQUEST['row']);





		if (isset( $_REQUEST['q']) && trim(sanitize_text_field( $_REQUEST['q'])) != '') $search_keyword = sanitize_text_field( $_REQUEST['q']);





		





		$start = $psp * $row;





		$end = $start + $row;





		$end_row = $row;





		





		





		if ($search_keyword != '') {





			$args = array( 's' => $search_keyword, 'numberposts' => $row+1, 'offset'=> $start, 'orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');			





			





			// check product in stock.





			$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





			





			// set meta to filter by sale/image/price.





			if($sales=='yes')





			$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





			elseif($sales=='no')





			$args['meta_query'][] = array('key' => '_sale_price','value' => '');





			elseif($images=='1')





			$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





			elseif($images=='3')





			$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





			elseif($images=='4')





			$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





			elseif($images=='video')





			$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');			





			elseif($price_label=='price')





			$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





				





			if ($cat_slug != '') {





				$args['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );





				$extra_parameter .= '&scat='.$cat_slug;





			} elseif($tag_slug != '') {





				$args['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );





				$extra_parameter .= '&stag='.$tag_slug;





			}





			





			$total_args = $args;





			$total_args['numberposts'] = -1;





			$total_args['offset'] = 0;





			





			$search_products = get_posts($args);





						





			$html = '';





			





			/**





			 * display product in drop-down list.





			 */





			if ( $search_products && count($search_products) > 0 )





			{





				$text_lenght = $setting_options['refyn_search_text_lenght'];





				





				$sku_enable = $setting_options['refyn_search_sku_enable'];





				if(!empty($sku_enable) && $sku_enable == 'yes')





				$show_sku=true;





				$price_enable = $setting_options['refyn_search_price_enable'];





				if(!empty($price_enable) && $price_enable == 'yes')





				$show_price=true;





				





				$category_enable = $setting_options['refyn_search_categories_enable'];





				if(!empty($category_enable) && $category_enable == 'yes')





				$show_categories=true;





				





				$protag_enable = $setting_options['refyn_search_tags_enable'];





				if(!empty($protag_enable) && $protag_enable == 'yes')





				$show_tags=true;





				





				$addtocart_enable = $setting_options['refyn_search_addtocart_enable'];





				if(!empty($addtocart_enable) && $addtocart_enable == 'yes')





				$show_addtocart=true;





				





				foreach ( $search_products as $product ) {





					$link_detail = get_permalink($product->ID);





					





					$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID,'shop_catalog',64,64);





					





					$product_sku_output = Refyn_Search_Shortcodes::get_product_sku($product->ID, $show_sku);





					$product_price_output = Refyn_Search_Shortcodes::get_product_price($product->ID, $show_price);





						





					$product_cats_output = Refyn_Search_Shortcodes::get_product_categories($product->ID, $show_categories);





					





					$product_tags_output = Refyn_Search_Shortcodes::get_product_tags($product->ID, $show_tags);





					$addtocart_output = Refyn_Search_Shortcodes::get_product_addtocart($product->ID, $show_addtocart);





					$product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_content ) ) ),$text_lenght,'...');





					if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_excerpt ) ) ),$text_lenght,'...');





										





					global $wpdb;





					$is_sales_price = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sale_price' && meta_value != '' && post_id = ".$product->ID, '') );





					$product_title = (strlen(stripslashes( $product->post_title)) > 65) ? substr(stripslashes( $product->post_title),0,65).'...' : stripslashes( $product->post_title);





					if($is_sales_price==1){





						$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price">'.$product_price_output.'</span></p>';





						





						





						if(!empty($product_sku_output))





						$html .='<p class="row"><span class="price">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="price">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="price">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="price">'.$addtocart_output.'</span></p>';





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}else{





						$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price_sale">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .='<p class="row"><span class="price_sale">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="price_sale">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="price_sale">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="price">'.$addtocart_output.'</span></p>';





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					





					}





					$end_row--;





					if ($end_row < 1) break;





				}





				





				if ( count($search_products) <= $row ) {





					





					$html .= '';





				}





				





			}





			else 





			{





				





				/*--- PhpSpellChecker ---*/





				$spell_suggestions1 = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=spellcheck&q='.urlencode($search_keyword));





  				$all_suggestion1 = json_decode($spell_suggestions1, true);





				$spellResult=$all_suggestion1['result'];





				/*require_once($_SERVER['DOCUMENT_ROOT']."/phpspellchecker/PHPSpellChecker.class.php");





				$spellCheck = new PHPSpellChecker(); 





				$spellResult = $spellCheck->checkSpelling($search_keyword, "en-US");*/





				





				/*require $_SERVER['DOCUMENT_ROOT']."/phpspellcheck/core/php/engine.php";





				$spellcheckObject = new PHPSpellCheck();





				$spellcheckObject -> LoadDictionary("English (International)") ;





				$spellResult = array($search_keyword=>$spellcheckObject->Suggestions($search_keyword));*/





				





				/*require_once($_SERVER['DOCUMENT_ROOT']."/phpspellchecker/HunSpellChecker.class.php");





				$spellCheck = new HunSpellChecker(); 





//				$spellCheck->setHunspellPath(get_home_path()."phpspellchecker/dictionaries/hunspell/");





				$spellResult = $spellCheck->checkSpelling($search_keyword, "en-US"); */





				





				$search_products_new = array();





				$checked_soundas = 0;





				





				// Elchanan 23-NOV-2015 Try to Translate!





				if (strlen($search_keyword) > 4 && count($spellResult) < 2)





				{





				$google_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=google_translate&q='.urlencode($search_keyword));





  				$google_result = json_decode($google_suggestion, true);





				if($google_result['error'])





				echo $google_result['error'];





				





				if(!empty($google_result['result'])){





					$translate = strtolower($google_result['result']);





					if (!$translate || $translate != $search_keyword ) // if FALSE translate was failed





						$search_keyword = $translate;	





					else





						$translate = ""; // set if off for no use in Yahoo	





					}





				}





				/*$translate = "";





				





				if (strlen($search_keyword) > 4 && count($spellResult) < 2)





				{





					require_once $_SERVER['DOCUMENT_ROOT']."/Google_api/autoload.php";





					$translate = strtolower(google_translate($search_keyword));





					if (!$translate || $translate != $search_keyword ) // if FALSE translate was failed





						$search_keyword = $translate;	





					else





						$translate = ""; // set if off for no use in Yahoo	





				}*/





				/**





				 * is suggestion key found.





				 */





				if($spellResult)





				{





					/**





					 * loop to find products by suggested key.





					 */





					foreach ($spellResult as $rows)





					{





						// count suggestion word





						if(count($rows)<2)





						{





							global $wpdb;





							$query = $wpdb->get_results("SELECT p.post_title FROM $wpdb->posts p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N );





							//echo count($query);





							//echo "|->";   print_r ($query); exit;





							





							$soundex_match = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=soundex_match&q='.urlencode($search_keyword).'&localDB='.serialize($query) );





  							$res1 = json_decode($soundex_match, true);





							





							$checked_soundas=$res1['checked_soundas'];





							if(!empty($res1['soundex'])){





							foreach($res1['soundex'] as $soundex){





								$rows[]=$soundex;	





							}





							}





							if(!empty($res1['checked_soundas']))





							$checked_soundas=$res1['checked_soundas'];











						}





						





						// Elchanan 28-OCT-2015		Check if kw has been used before and in the LOG?





						 global $wpdb;





				





						





						$lev = soundex ($search_keyword);





						$logs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=logs&q='.urlencode($search_keyword));





  						$query = json_decode($logs, true);





						 if (!empty($query)) 





						 {





							 $postid = $query['postid'];





							 $search_products = get_post($postid );





							 //echo "postid=$postid | tit=".$search_products->post_title;





							 $rows[] = $search_products->post_title;





						 }





						 else 





						 {





							 // try OR soundex = '$lev'





							 //$lev = substr($lev,0,3); 





							 $revlogs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=reverselogs&q='.$lev);





  							 $query = json_decode($revlogs, true);





							 if (!empty($query)) 





							 {





								 $postid = $query['postid'];





								 $search_products = get_post($postid );





								 //echo "lev=$lev | tit=".$search_products->post_title;





								 $rows[] = $search_products->post_title;





							 }





						 }





						





						// ELchnanan 30-NOV    Get yahoo suggestion start =====									





						





						//if ($translate == "") echo "is null - tran";





						//else echo "NOT n=$translate";





						$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate&q='.urlencode($search_keyword));





  						$yahoo_result = json_decode($yahoo_suggestion, true);





						if(!empty($yahoo_result['result'])){





						if ($yahoo_result['result']['bossresponse']['related']['count'] == 0)





						{





							





							$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate_extra&q='.urlencode($search_keyword));





  						$yahoo_result = json_decode($yahoo_suggestion, true);





							





							





							$answer = remove_stop_words ($yahoo_result['result']['bossresponse']['web']['results'][0]['title']); 		// one result is enough to know what is all about





							//echo "Possible="; print_r ($json->bossresponse->web->results);





							





							// try to get better answer





							$seo = "";





							$wordsCount = 999;





							foreach ($yahoo_result['result']['bossresponse']['web']['results'] as $word)





							{





								$tmp = remove_stop_words ($word['title']);





								//echo $tmp."*L=".str_word_count ($tmp);





								if (str_word_count ($tmp) < $wordsCount)	$seo = $tmp;





								$wordsCount = str_word_count ($tmp);





							}











							if (strlen($answer) < 6) 		





								$answer = $yahoo_result['result']['bossresponse']['web']['results'][0]['abstract'];





								





						}





						}





						$answer .= " ". $yahoo_result['result']['bossresponse']['related']['results'][0]['suggestion'];





						/*require $_SERVER['DOCUMENT_ROOT']."/yahoo-api/OAuth.php";





						require $_SERVER['DOCUMENT_ROOT']."/yahoo-api/yahoo_api.php";





						//$json 	= call_yahoo_api ($search_keyword, 'spelling');	





						//$answer = $json->bossresponse->spelling->results[0];





						//print_r ($answer);





	





						$json 	= call_yahoo_api ($search_keyword, 'related');	





						$answer = "";





						if ($json->bossresponse->related->count == 0)





						{





							$url	= "https://yboss.yahooapis.com/ysearch/web";							// Try web search instead "related"





							$json	= call_yahoo_api ($search_keyword, 'web');





							





							$answer = remove_stop_words ($json->bossresponse->web->results[0]->title); 		// one result is enough to know what is all about





							//echo "Possible="; print_r ($json->bossresponse->web->results);





							





							// try to get better answer





							$seo = "";





							$wordsCount = 999;





							foreach ($json->bossresponse->web->results as $word)





							{





								$tmp = remove_stop_words ($word->title);





								//echo $tmp."*L=".str_word_count ($tmp);





								if (str_word_count ($tmp) < $wordsCount)	$seo = $tmp;





								$wordsCount = str_word_count ($tmp);





							}





							if (strlen($answer) < 6) 		





								$answer = $json->bossresponse->web->results[0]->abstract;





						}





						$answer .= " ". $json->bossresponse->related->results[0]->suggestion;*/				// one result is enough to know what is all about





						$answer = str_ireplace($search_keyword, '', $answer);								//	remove same words						





						//echo "B:".$answer;





						$answer = remove_stop_words ($answer);												//  remove stop words





						//echo " | A:".$answer;





						$rows[] = $answer;





						





						// Insert what I found immediatly to cp_refyn_keywords table





						if (strlen($answer) >= 3)





						{





						   $data = array ( 'kw' => $search_keyword, 





										   'times' => 1, 





										   'seo' => $seo, 





										   'the_kw_user_selected' => $answer,





										   'source' => 'ML',





										   'soundex' => soundex($answer) );			





						   //$query = $wpdb->insert( "cp_refyn_keywords", $data );





						   $insertkeyword = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=insertkeyword&data='.serialize($data));





  							$query = json_decode($insertkeyword, true);





						}





						





						// ====== Get yahoo suggestion stop =====





						





						// Elchanan 1-OCT-2015 : Two or more words in a query		





						/*	start two words */





						if (str_word_count($search_keyword) > 1)





						{





							$array = explode (" ", $search_keyword);





							$list  = "";





							$toEnd = count($array);





							foreach ($array as $word)





							{





							  if (0 === --$toEnd)





							  {





								 // last value





							if (empty($word)) continue;





							$list = $list . " like '%". $word ."%' ";





								}





							  else





							  {





							if (empty($word)) continue;  





							$list = $list . " like '%". $word ."%' AND post_content ";





							  }





							 }











				





							global $wpdb;





							$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_content ". $list, ARRAY_N );





							if (!empty($query))





							{





									foreach( $query as $key => $value)





									{





										$rows[] = $value[0];





									}





							}





			





						}





			





						//	Elchanan 15-OCT-2015 try these History rows that I kept





						$rows[] = $history_kw_table['seo'];





			





						// Elchanan 13 OCT 2015 - check if exists in post_excerpt  





						global $wpdb;





						$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_excerpt like '%". $search_keyword ."%' LIMIT 0, 10", ARRAY_N );





						if (!empty($query))





						{





								foreach( $query as $key => $value)





								{





									$rows[] = $value[0];





									//echo "245->".$value[0];





								}





						}





			





						/* end two or more	  */





		





					





						foreach ($rows as $q2)





						{





							$args = array('orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');





							





							// check product sku first.





							$args['meta_query'][] = array('key'=>'_sku','value'=>$q2,'compare'=>'LIKE');





							// check product in stock.





							$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





			





							// set meta to filter by sale/image/price.			





							if($sales=='yes')





							$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





							elseif($sales=='no')





							$args['meta_query'][] = array('key' => '_sale_price','value' => '');





							elseif($images=='1')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





							elseif($images=='3')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





							elseif($images=='4')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





							elseif($images=='video')





							$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');							





							elseif($price_label=='price')





							$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





							





							if ($cat_slug != '') {





								$args['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );





								$extra_parameter_admin .= '&scat='.$cat_slug;





								if (get_option('permalink_structure') == '') 





									$extra_parameter .= '&scat='.$cat_slug;





								else





									$extra_parameter .= '/scat/'.$cat_slug;





							} elseif($tag_slug != '') {





								$args['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );





								$extra_parameter_admin .= '&stag='.$tag_slug;





								if (get_option('permalink_structure') == '') 





									$extra_parameter .= '&stag='.$tag_slug;





								else





									$extra_parameter .= '/stag/'.$tag_slug;





							}





							





							$total_args = $args;





							$total_args['numberposts'] = -1;





							$total_args['offset'] = 0;





							





							$search_products = get_posts($args);





							if(empty($search_products))





							{





								// unset previous meta.





								unset($args['meta_query']);





								//set new meta to check product title & description





								$args['s'] = $q2;								





								$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





			





								// set meta to filter by sale/image/price.			





								if($sales=='yes')





								$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





								elseif($sales=='no')





								$args['meta_query'][] = array('key' => '_sale_price','value' => '');





								elseif($images=='1')





								$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





								elseif($images=='3')





								$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





								elseif($images=='4')





								$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





								elseif($images=='video')





								$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');								





								elseif($price_label=='price')





								$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





								





								$search_products = get_posts($args);





							}





							





							/**





							 * set suggested products in an array.





							 */





							if ( $search_products && count($search_products) > 0 ) {





															





								foreach($search_products as $search_products_single)





								{





									





											// check duplicate product.





											if(!in_array($search_products_single, $search_products_new))





												array_push($search_products_new, $search_products_single);





										





									





								}





							}





							





						}





						





						$search_products1 = array();





						/**





						 * set suggested item in an array to display newer item.





						 */





						foreach($search_products_new as $search_products_single)





						{





							if(($starting_1 > ($psp*$row)) && ($starting_1 < ((($psp*$row)+($row+1)))))





								{





											





								





									array_push($search_products1, $search_products_single);





										





								





							}





							$starting_1++;





						}





							





							





						/**





						 * display suggested products.





						 */





						if ( $search_products1 && count($search_products1) > 0 ) 





						{





							$text_lenght = $setting_options['refyn_search_text_lenght'];





							foreach ( $search_products1 as $product ) {





								$link_detail = get_permalink($product->ID);





								





								$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID,'shop_catalog',64,64);





								





								$product_sku_output = Refyn_Search_Shortcodes::get_product_sku($product->ID, $show_sku);





								$product_price_output = Refyn_Search_Shortcodes::get_product_price($product->ID, $show_price);





									





								$product_cats_output = Refyn_Search_Shortcodes::get_product_categories($product->ID, $show_categories);





								





								$product_tags_output = Refyn_Search_Shortcodes::get_product_tags($product->ID, $show_tags);





								





								$product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_content ) ) ),$text_lenght,'...');





								if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_excerpt ) ) ),$text_lenght,'...');





													





								global $wpdb;





								$is_sales_price = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sale_price' && meta_value != '' && post_id = ".$product->ID, '') );





								$product_title = (strlen(stripslashes( $product->post_title)) > 65) ? substr(stripslashes( $product->post_title),0,65).'...' : stripslashes( $product->post_title);





								if($is_sales_price==1){





									/*$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





								else





									$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price_sale">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';*/





									





									$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .= '<p class="row"><span class="price">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}else{





						$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price_sale">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .='<p class="row"><span class="price_sale">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}





								





								$end_row--;





								if ($end_row < 1) break;





							}





							





							if ( count($search_products1) <= $row ) {





								





								$html .= '';





							}





							





						}





						else





						{





							// check if soundas checked before.





							if($checked_soundas==0)





							{





								$rows = array();





								global $wpdb;





								$query = $wpdb->get_results("SELECT p.post_title FROM $wpdb->posts p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N );





								//echo count($query);





								//echo "|->";   print_r ($query); exit;





								





								$soundex_match = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=soundex_match&q='.urlencode($search_keyword).'&localDB='.serialize($query) );





  							$res1 = json_decode($soundex_match, true);





							





							$checked_soundas=$res1['checked_soundas'];





							if(!empty($res1['soundex'])){





							foreach($res1['soundex'] as $soundex){





								$rows[]=$soundex;	





							}





							}





								





								// Elchanan 28-OCT-2015		Check if kw has been used before and in the LOG?





						 global $wpdb;





				





						$lev = soundex ($search_keyword);





						$logs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=logs&q='.urlencode($search_keyword));





  						$query = json_decode($logs, true);





						 if (!empty($query)) 





						 {





							 $postid = $query['postid'];





							 $search_products = get_post($postid );





							 //echo "postid=$postid | tit=".$search_products->post_title;





							 $rows[] = $search_products->post_title;





						 }





						 else 





						 {





							 // try OR soundex = '$lev'





							 //$lev = substr($lev,0,3); 





							 $revlogs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=reverselogs&q='.$lev);





  							 $query = json_decode($revlogs, true);





							 if (!empty($query)) 





							 {





								 $postid = $query['postid'];





								 $search_products = get_post($postid );





								 //echo "lev=$lev | tit=".$search_products->post_title;





								 $rows[] = $search_products->post_title;





							 }





						 }





						





						// ELchnanan 30-NOV    Get yahoo suggestion start =====									





						





								//if ($translate == "") echo "is null - tran";





								//else echo "NOT n=$translate";





								$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate&q='.urlencode($search_keyword));





  						$yahoo_result = json_decode($yahoo_suggestion, true);





						if(!empty($yahoo_result['result'])){





						if ($yahoo_result['result']['bossresponse']['related']['count'] == 0)





						{





							





							$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate_extra&q='.urlencode($search_keyword));





  						$yahoo_result = json_decode($yahoo_suggestion, true);





							





							





							$answer = remove_stop_words ($yahoo_result['result']['bossresponse']['web']['results'][0]['title']); 		// one result is enough to know what is all about





							//echo "Possible="; print_r ($json->bossresponse->web->results);





							





							// try to get better answer





							$seo = "";





							$wordsCount = 999;





							foreach ($yahoo_result['result']['bossresponse']['web']['results'] as $word)





							{





								$tmp = remove_stop_words ($word['title']);





								//echo $tmp."*L=".str_word_count ($tmp);





								if (str_word_count ($tmp) < $wordsCount)	$seo = $tmp;





								$wordsCount = str_word_count ($tmp);





							}











							if (strlen($answer) < 6) 		





								$answer = $yahoo_result['result']['bossresponse']['web']['results'][0]['abstract'];





								





						}





						}





						$answer .= " ". $yahoo_result['result']['bossresponse']['related']['results'][0]['suggestion'];





								/*require $_SERVER['DOCUMENT_ROOT']."/yahoo-api/OAuth.php";





								require $_SERVER['DOCUMENT_ROOT']."/yahoo-api/yahoo_api.php";





		





								//$json 	= call_yahoo_api ($search_keyword, 'spelling');	





								//$answer = $json->bossresponse->spelling->results[0];





								//print_r ($answer);





		





			





								$json 	= call_yahoo_api ($search_keyword, 'related');	





								$answer = "";





								if ($json->bossresponse->related->count == 0)





								{





									$url	= "https://yboss.yahooapis.com/ysearch/web";							// Try web search instead "related"





									$json	= call_yahoo_api ($search_keyword, 'web');





									





									$answer = remove_stop_words ($json->bossresponse->web->results[0]->title); 		// one result is enough to know what is all about





									//echo "Possible="; print_r ($json->bossresponse->web->results);





									





									// try to get better answer





									$seo = "";





									$wordsCount = 999;





									foreach ($json->bossresponse->web->results as $word)





									{





										$tmp = remove_stop_words ($word->title);





										//echo $tmp."*L=".str_word_count ($tmp);





										if (str_word_count ($tmp) < $wordsCount)	$seo = $tmp;





										$wordsCount = str_word_count ($tmp);





									}





		





		





									if (strlen($answer) < 6) 		





										$answer = $json->bossresponse->web->results[0]->abstract;





								}





		





								$answer .= " ". $json->bossresponse->related->results[0]->suggestion;*/				// one result is enough to know what is all about





								$answer = str_ireplace($search_keyword, '', $answer);								//	remove same words						





								//echo "B:".$answer;





								$answer = remove_stop_words ($answer);												//  remove stop words





								//echo " | A:".$answer;





								$rows[] = $answer;





								





								// Insert what I found immediatly to cp_refyn_keywords table





								if (strlen($answer) >= 3)





								{





								   $data = array ( 'kw' => $search_keyword, 





												   'times' => 1, 





												   'seo' => $seo, 





												   'the_kw_user_selected' => $answer,





												   'source' => 'ML',





												   'soundex' => soundex($answer) );			





								  // $query = $wpdb->insert( "cp_refyn_keywords", $data );





								   $insertkeyword = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=insertkeyword&data='.serialize($data));





  							$query = json_decode($insertkeyword, true);





								}





		





								





								// ====== Get yahoo suggestion stop =====





								





								// Elchanan 1-OCT-2015 : Two or more words in a query		





								/*	start two words */





								if (str_word_count($search_keyword) > 1)





								{





									$array = explode (" ", $search_keyword);





									$list  = "";





									$toEnd = count($array);





									foreach ($array as $word)





									{





									  if (0 === --$toEnd)





									  {





										 // last value





									if (empty($word)) continue;





									$list = $list . " like '%". $word ."%' ";





										}





									  else





									  {





									if (empty($word)) continue;  





									$list = $list . " like '%". $word ."%' AND post_content ";





									  }





									 }











						





									global $wpdb;





									$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_content ". $list, ARRAY_N );





									if (!empty($query))





									{





											foreach( $query as $key => $value)





											{





												$rows[] = $value[0];





											}





									}





					





								}





					





								//	Elchanan 15-OCT-2015 try these History rows that I kept





								$rows[] = $history_kw_table['seo'];





		





					





								// Elchanan 13 OCT 2015 - check if exists in post_excerpt  





								global $wpdb;





								$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_excerpt like '%". $search_keyword ."%' LIMIT 0, 10", ARRAY_N );





								if (!empty($query))





								{





										foreach( $query as $key => $value)





										{





											$rows[] = $value[0];





											//echo "245->".$value[0];





										}





								}





					





								/* end two or more	  */





								





								foreach ($rows as $q2)





								{





									$args = array('orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');





									





									// check product sku first.





									$args['meta_query'][] = array('key'=>'_sku','value'=>$q2,'compare'=>'LIKE');





									// check product in stock.





									$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





					





									// set meta to filter by sale/image/price.			





									if($sales=='yes')





									$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





									elseif($sales=='no')





									$args['meta_query'][] = array('key' => '_sale_price','value' => '');





									elseif($images=='1')





									$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





									elseif($images=='3')





									$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





									elseif($images=='4')





									$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





									elseif($images=='video')





									$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');							





									elseif($price_label=='price')





									$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





									





									if ($cat_slug != '') {





										$args['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );





										$extra_parameter_admin .= '&scat='.$cat_slug;





										if (get_option('permalink_structure') == '') 





											$extra_parameter .= '&scat='.$cat_slug;





										else





											$extra_parameter .= '/scat/'.$cat_slug;





									} elseif($tag_slug != '') {





										$args['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );





										$extra_parameter_admin .= '&stag='.$tag_slug;





										if (get_option('permalink_structure') == '') 





											$extra_parameter .= '&stag='.$tag_slug;





										else





											$extra_parameter .= '/stag/'.$tag_slug;





									}





									





									$total_args = $args;





									$total_args['numberposts'] = -1;





									$total_args['offset'] = 0;





									





									$search_products = get_posts($args);





									if(empty($search_products))





									{





										// unset previous meta.





										unset($args['meta_query']);





										//set new meta to check product title & description





										$args['s'] = $q2;								





										$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





					





										// set meta to filter by sale/image/price.			





										if($sales=='yes')





										$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





										elseif($sales=='no')





										$args['meta_query'][] = array('key' => '_sale_price','value' => '');





										elseif($images=='1')





										$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





										elseif($images=='3')





										$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





										elseif($images=='4')





										$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





										elseif($images=='video')





										$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');								





										elseif($price_label=='price')





										$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





										





										$search_products = get_posts($args);





									}





									





									/**





									 * set suggested products in an array.





									 */





									if ( $search_products && count($search_products) > 0 ) {





																	





										foreach($search_products as $search_products_single)





										{





											





													// check duplicate product.





													if(!in_array($search_products_single, $search_products_new))





														array_push($search_products_new, $search_products_single);





												





											





										}





									}





									





								}





								$search_products1 = array();





								/**





								 * set suggested item in an array to display newer item.





								 */





								foreach($search_products_new as $search_products_single)





								{





									if(($starting_1 > ($psp*$row)) && ($starting_1 < ((($psp*$row)+($row+1)))))





										{





													





										





											array_push($search_products1, $search_products_single);





												





										





									}





									$starting_1++;





								}





									





									





								/**





								 * display suggested products.





								 */





								if ( $search_products1 && count($search_products1) > 0 ) 





								{





									$text_lenght = $setting_options['refyn_search_text_lenght'];





									foreach ( $search_products1 as $product ) {





										$link_detail = get_permalink($product->ID);





										





										$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID,'shop_catalog',64,64);





										$product_sku_output = Refyn_Search_Shortcodes::get_product_sku($product->ID, $show_sku);





										$product_price_output = Refyn_Search_Shortcodes::get_product_price($product->ID, $show_price);





											





										$product_cats_output = Refyn_Search_Shortcodes::get_product_categories($product->ID, $show_categories);





										





										$product_tags_output = Refyn_Search_Shortcodes::get_product_tags($product->ID, $show_tags);





										





										$product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_content ) ) ),$text_lenght,'...');





										if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_excerpt ) ) ),$text_lenght,'...');





															





										global $wpdb;





										$is_sales_price = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sale_price' && meta_value != '' && post_id = ".$product->ID, '') );





										$product_title = (strlen(stripslashes( $product->post_title)) > 65) ? substr(stripslashes( $product->post_title),0,65).'...' : stripslashes( $product->post_title);





										if($is_sales_price==1){





											/*$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





											





										else





											$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price_sale">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';*/





											$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .= '<p class="row"><span class="price">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}else{





						$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price_sale">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .='<p class="row"><span class="price_sale">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}





										





										$end_row--;





										if ($end_row < 1) break;





									}





									





									if ( count($search_products1) <= $row ) {





										





										$html .= '';





									}





								}





									





									





									





							}





						}





								





						





					}





	





					//$spellCheck->clearWarnings(); // clear all previous warnings 





					//$spellCheck->clearErrors(); // clear all previous errors 





				}





				else





				{





					global $wpdb;





					$query = $wpdb->get_results("SELECT p.post_title FROM $wpdb->posts p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N );





					//echo count($query);





					//echo "|->";   print_r ($query); exit;





					$soundex_match = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=soundex_match&q='.urlencode($search_keyword).'&localDB='.serialize($query) );





  							$res1 = json_decode($soundex_match, true);





							





							$checked_soundas=$res1['checked_soundas'];





							if(!empty($res1['soundex'])){





							foreach($res1['soundex'] as $soundex){





								$rows[]=$soundex;	





							}





							}





					





					// Elchanan 28-OCT-2015		Check if kw has been used before and in the LOG?





						 global $wpdb;





				





						$lev = soundex ($search_keyword);





						$logs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=logs&q='.urlencode($search_keyword));





  						$query = json_decode($logs, true);





						 if (!empty($query)) 





						 {





							 $postid = $query['postid'];





							 $search_products = get_post($postid );





							 //echo "postid=$postid | tit=".$search_products->post_title;





							 $rows[] = $search_products->post_title;





						 }





						 else 





						 {





							 // try OR soundex = '$lev'





							 //$lev = substr($lev,0,3); 





							 $revlogs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=reverselogs&q='.$lev);





  						$query = json_decode($revlogs, true);





							 if (!empty($query)) 





							 {





								 $postid = $query['postid'];





								 $search_products = get_post($postid );





								 //echo "lev=$lev | tit=".$search_products->post_title;





								 $rows[] = $search_products->post_title;





							 }





						 }





						





						// ELchnanan 30-NOV    Get yahoo suggestion start =====									





						





					//if ($translate == "") echo "is null - tran";





					//else echo "NOT n=$translate";





					$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate&q='.urlencode($search_keyword));





  						$yahoo_result = json_decode($yahoo_suggestion, true);





						if(!empty($yahoo_result['result'])){





						if ($yahoo_result['result']['bossresponse']['related']['count'] == 0)





						{





							





							$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate_extra&q='.urlencode($search_keyword));





  						$yahoo_result = json_decode($yahoo_suggestion, true);





							





							





							$answer = remove_stop_words ($yahoo_result['result']['bossresponse']['web']['results'][0]['title']); 		// one result is enough to know what is all about





							//echo "Possible="; print_r ($json->bossresponse->web->results);





							





							// try to get better answer





							$seo = "";





							$wordsCount = 999;





							foreach ($yahoo_result['result']['bossresponse']['web']['results'] as $word)





							{





								$tmp = remove_stop_words ($word['title']);





								//echo $tmp."*L=".str_word_count ($tmp);





								if (str_word_count ($tmp) < $wordsCount)	$seo = $tmp;





								$wordsCount = str_word_count ($tmp);





							}











							if (strlen($answer) < 6) 		





								$answer = $yahoo_result['result']['bossresponse']['web']['results'][0]['abstract'];





								





						}





						}





						$answer .= " ". $yahoo_result['result']['bossresponse']['related']['results'][0]['suggestion'];





					/*require $_SERVER['DOCUMENT_ROOT']."/yahoo-api/OAuth.php";





					require $_SERVER['DOCUMENT_ROOT']."/yahoo-api/yahoo_api.php";





					//$json 	= call_yahoo_api ($search_keyword, 'spelling');	





					//$answer = $json->bossresponse->spelling->results[0];





					//print_r ($answer);





					$json 	= call_yahoo_api ($search_keyword, 'related');	





					$answer = "";





					if ($json->bossresponse->related->count == 0)





					{





						$url	= "https://yboss.yahooapis.com/ysearch/web";							// Try web search instead "related"





						$json	= call_yahoo_api ($search_keyword, 'web');





						





						$answer = remove_stop_words ($json->bossresponse->web->results[0]->title); 		// one result is enough to know what is all about





						//echo "Possible="; print_r ($json->bossresponse->web->results);





						





						// try to get better answer





						$seo = "";





						$wordsCount = 999;





						foreach ($json->bossresponse->web->results as $word)





						{





							$tmp = remove_stop_words ($word->title);





							//echo $tmp."*L=".str_word_count ($tmp);





							if (str_word_count ($tmp) < $wordsCount)	$seo = $tmp;





							$wordsCount = str_word_count ($tmp);





						}





						if (strlen($answer) < 6) 		





							$answer = $json->bossresponse->web->results[0]->abstract;





					}





					$answer .= " ". $json->bossresponse->related->results[0]->suggestion;*/				// one result is enough to know what is all about





					$answer = str_ireplace($search_keyword, '', $answer);								//	remove same words						





					//echo "B:".$answer;





					$answer = remove_stop_words ($answer);												//  remove stop words





					//echo " | A:".$answer;





					$rows[] = $answer;





					





					// Insert what I found immediatly to cp_refyn_keywords table





					if (strlen($answer) >= 3)





					{





					   $data = array ( 'kw' => $search_keyword, 





									   'times' => 1, 





									   'seo' => $seo, 





									   'the_kw_user_selected' => $answer,





									   'source' => 'ML',





									   'soundex' => soundex($answer) );			





					  // $query = $wpdb->insert( "cp_refyn_keywords", $data );





					  $insertkeyword = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=insertkeyword&data='.serialize($data));





  							$query = json_decode($insertkeyword, true);





					}





					





					// ====== Get yahoo suggestion stop =====





					





					// Elchanan 1-OCT-2015 : Two or more words in a query		





					/*	start two words */





					if (str_word_count($search_keyword) > 1)





					{





						$array = explode (" ", $search_keyword);





						$list  = "";





						$toEnd = count($array);





						foreach ($array as $word)





						{





						  if (0 === --$toEnd)





						  {





							 // last value





						if (empty($word)) continue;





						$list = $list . " like '%". $word ."%' ";





							}





						  else





						  {





						if (empty($word)) continue;  





						$list = $list . " like '%". $word ."%' AND post_content ";





						  }





						 }











			





						global $wpdb;





						$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_content ". $list, ARRAY_N );





						if (!empty($query))





						{





								foreach( $query as $key => $value)





								{





									$rows[] = $value[0];





								}





						}





		





					}





		





					//	Elchanan 15-OCT-2015 try these History rows that I kept





					$rows[] = $history_kw_table['seo'];





		





					// Elchanan 13 OCT 2015 - check if exists in post_excerpt  





					global $wpdb;





					$query = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type='product' and post_status='publish' and post_excerpt like '%". $search_keyword ."%' LIMIT 0, 10", ARRAY_N );





					if (!empty($query))





					{





							foreach( $query as $key => $value)





							{





								$rows[] = $value[0];





								//echo "245->".$value[0];





							}





					}





		





					/* end two or more	  */





					





					foreach ($rows as $q2)





					{





						$args = array('orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');





						





						// check product sku first.





						$args['meta_query'][] = array('key'=>'_sku','value'=>$q2,'compare'=>'LIKE');





						// check product in stock.





						$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





		





						// set meta to filter by sale/image/price.			





						if($sales=='yes')





						$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





						elseif($sales=='no')





						$args['meta_query'][] = array('key' => '_sale_price','value' => '');





						elseif($images=='1')





						$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





						elseif($images=='3')





						$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





						elseif($images=='4')





						$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





						elseif($images=='video')





						$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');							





						elseif($price_label=='price')





						$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





						





						if ($cat_slug != '') {





							$args['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );





							$extra_parameter_admin .= '&scat='.$cat_slug;





							if (get_option('permalink_structure') == '') 





								$extra_parameter .= '&scat='.$cat_slug;





							else





								$extra_parameter .= '/scat/'.$cat_slug;





						} elseif($tag_slug != '') {





							$args['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );





							$extra_parameter_admin .= '&stag='.$tag_slug;





							if (get_option('permalink_structure') == '') 





								$extra_parameter .= '&stag='.$tag_slug;





							else





								$extra_parameter .= '/stag/'.$tag_slug;





						}





						





						$total_args = $args;





						$total_args['numberposts'] = -1;





						$total_args['offset'] = 0;





						





						$search_products = get_posts($args);





						if(empty($search_products))





						{





							// unset previous meta.





							unset($args['meta_query']);





							//set new meta to check product title & description





							$args['s'] = $q2;								





							$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');





		





							// set meta to filter by sale/image/price.			





							if($sales=='yes')





							$args['meta_query'][] = array('key' => '_sale_price','value' => 0,'compare' => '>');





							elseif($sales=='no')





							$args['meta_query'][] = array('key' => '_sale_price','value' => '');





							elseif($images=='1')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => '','compare' => '=');





							elseif($images=='3')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_3,'compare' => 'IN');





							elseif($images=='4')





							$args['meta_query'][] = array('key' => '_product_image_gallery','value' => $product_images_gallery_4,'compare' => 'IN');





							elseif($images=='video')





							$args['meta_query'][] = array('value' => 'embed','compare' => 'LIKE');								





							elseif($price_label=='price')





							$args['meta_query'][] = array('key' => '_price', 'value' => array($start_prices,$end_prices),'compare' => 'BETWEEN','type' => 'NUMERIC');





							





							$search_products = get_posts($args);





						}





						





						/**





						 * set soundas products in an array.





						 */





						if ( $search_products && count($search_products) > 0 ) {





														





							foreach($search_products as $search_products_single)





							{





								





										// check duplicate product.





										if(!in_array($search_products_single, $search_products_new))





											array_push($search_products_new, $search_products_single);





									





								





							}





						}





						





					}





					





					$search_products1 = array();





					/**





					 * set soundas item in an array to display newer item.





					 */





					foreach($search_products_new as $search_products_single)





					{





						if(($starting_1 > ($psp*$row)) && ($starting_1 < ((($psp*$row)+($row+1)))))





							{





										





							





								array_push($search_products1, $search_products_single);





									





							





						}





						$starting_1++;





					}





						





						





					/**





					 * display soundas products.





					 */





					if ( $search_products1 && count($search_products1) > 0 ) 





					{





						$text_lenght = $setting_options['refyn_search_text_lenght'];





						foreach ( $search_products1 as $product ) {





							$link_detail = get_permalink($product->ID);





							





							$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID,'shop_catalog',64,64);





							$product_sku_output = Refyn_Search_Shortcodes::get_product_sku($product->ID, $show_sku);





							$product_price_output = Refyn_Search_Shortcodes::get_product_price($product->ID, $show_price);





								





							$product_cats_output = Refyn_Search_Shortcodes::get_product_categories($product->ID, $show_categories);





							





							$product_tags_output = Refyn_Search_Shortcodes::get_product_tags($product->ID, $show_tags);





							





							$product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_content ) ) ),$text_lenght,'...');





							if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words( strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( $product->post_excerpt ) ) ),$text_lenght,'...');





												





							global $wpdb;





							$is_sales_price = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sale_price' && meta_value != '' && post_id = ".$product->ID, '') );





							$product_title = (strlen(stripslashes( $product->post_title)) > 65) ? substr(stripslashes( $product->post_title),0,65).'...' : stripslashes( $product->post_title);





							if($is_sales_price==1){





								/*$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





							else





								$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p><p class="row"><span class="price_sale">'.$product_price_output.'</span></p></div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';*/





								$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'"><span class="onsale">Sale!</span>'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .= '<p class="row"><span class="price">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}else{





						$html .= '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="'.$link_detail.'">'.$avatar.'</a><div class="product-details row"><p class="row">'.$product_title.'</p>';





						





						if(!empty($product_price_output))





						$html .='<p class="row"><span class="price_sale">'.$product_price_output.'</span></p>';





						





						if(!empty($product_sku_output))





						$html .='<p class="row"><span class="price_sale">'.$product_sku_output.'</span></p>';





						





						if(!empty($product_cats_output))





						$html .='<span class="categories">'.$product_cats_output.'</span>';





						





						if(!empty($product_tags_output))





						$html .='<p class="row"><span class="tags">'.$product_tags_output.'</span></p>';





						





						if(!empty($addtocart_output))





						$html .='<p class="row"><span class="addtocart">'.$addtocart_output.'</span></p>';





						$html .='</div><a class="view-item" href="'.$link_detail.'">VIEW ITEM</a></div></div>';





					}





							





							$end_row--;





							if ($end_row < 1) break;





						}





						





						if ( count($search_products1) <= $row ) {





							





							$html .= '';





						}





					}





					





				}





			}





			echo $html;





		}





		die();





	}





	





	





	





	





	function remove_stop_words ($search_keyword)





	{





			//echo "| SK=$search_keyword |";





			





			





			





			// remove special characters





			$search_keyword = preg_replace("/[^a-zA-Z0-9]+/", " ", $search_keyword);





	





			// I dont knwo where is the 'b' from





			$search_keyword = str_ireplace ('b', "", $search_keyword);





			





			global $remote_API_server, $home_url, $api_key;





		$removestopwords = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=removestopwords&q='.urlencode($search_keyword));





  		$removed_stopword = json_decode($removestopwords, true);





		if(!empty($removed_stopword)){





			$search_keyword= $removed_stopword['removestopwords'];	





		}





		return $search_keyword;





	}





}





