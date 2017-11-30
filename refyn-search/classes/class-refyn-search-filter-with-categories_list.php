<?php







/**







 * Refyn Search Hook Filter







 *







 * Hook anf Filter into refyn plugin







 *







 * Table Of Contents







 *







 * plugins_loaded()







 * get_result_popup()







 * add_frontend_script()







 * add_frontend_style()







 * add_query_vars()







 * add_rewrite_rules()







 * custom_rewrite_rule()







 * search_by_title_only()







 * posts_request_unconflict_role_scoper_plugin()







 * refyn_wp_admin()







 * plugin_extra_links()







 * show_row()







 */







class Refyn_Hook_Filter







{







	public static function plugins_loaded() {







		global $refyn_id_excludes;







		Refyn_Search::get_id_excludes();







	}







	public static function pre_get_posts( $query ) {







		$q = $query->query_vars;







		if ( isset( $q['ps_post_type'] ) ) {







	        $query->set( 'post_type', $q['ps_post_type'] );







	    }







	    return $query;







	}







	







	/**







	 * to display product in ajax call.







	 */







	public static function show_row($search_products,$search_keyword,$extra_parameter,$end_row,$row) {







	







	







		foreach ( $search_products as $product ) {







			$link_detail = get_permalink($product->ID);







			$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID,'shop_catalog',64,64);







			$product_description = Refyn_Search::refyn_limit_words(strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( str_replace("\n", "", $product->post_content) ) ) ),$text_lenght,'...');







			if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words(strip_tags( Refyn_Search::strip_shortcodes( strip_shortcodes( str_replace("\n", "", $product->post_excerpt) ) ) ),$text_lenght,'...');







	







			$price_html = '';







			if ( $show_price == 1)







				$price_html = Refyn_Search_Shortcodes::get_product_price_dropdown($product->ID);







	







			$item = '<div class="ajax_search_content">XX<div class="result_row"><a href="'.$link_detail.'"><span class="rs_avatar">'.$avatar.'</span><div class="rs_content_popup"><span class="rs_name">'.stripslashes( $product->post_title).'</span>'.$price_html.'<span class="rs_description">'.$product_description.'</span></div></a></div></div>';







			echo $item.'[|]'.$link_detail.'[|]'.stripslashes( $product->post_title)."\n";







			$end_row--;







			if ($end_row < 1) break;







		}







		$rs_item = '';







		if ( count($search_products) > $row ) {







			if (get_option('permalink_structure') == '')







				$link_search = get_permalink(get_option('refyn_search_page_id')).'&rs='. urlencode($search_keyword) .$extra_parameter;







			else







				$link_search = rtrim( get_permalink(get_option('refyn_search_page_id')), '/' ).'/keyword/'. urlencode($search_keyword) .$extra_parameter;







			$rs_item .= '<div class="more_result" rel="more_result"><a href="'.$link_search.'"><h4 class="search_product_heading">'.__('See more results for', 'refyn').' '.$search_keyword.' <span class="see_more_arrow"></span></h4></a><span>'.__('Displaying top', 'refyn').' '.$row.' '.__('results', 'refyn').'</span></div><script type="text/javascript">spiralcatalist();</script>';







			echo $rs_item.'[|]'.$link_search.'[|]'.$search_keyword."\n";







		}







		







	}







	public static function get_result_popup() {







		







	







		add_filter( 'posts_search', array('Refyn_Hook_Filter', 'search_by_title_only'), 500, 2 );







		add_filter( 'posts_orderby', array('Refyn_Hook_Filter', 'refyn_posts_orderby'), 500, 2 );







		add_filter( 'posts_request', array('Refyn_Hook_Filter', 'posts_request_unconflict_role_scoper_plugin'), 500, 2);







		global $refyn_id_excludes;







		







		// display items limit.







		$row = 6;







		







		$text_lenght = 100;







		$show_price = 1;







		$search_keyword = '';







		$cat_slug = sanitize_text_field( $_REQUEST['scat'] );







		$tag_slug = '';







		$extra_parameter = '';







		if (isset(  $_REQUEST['row'] )  && 
		sanitize_text_field( $_REQUEST['row'] ) > 0) $row = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['row'] ) ) );







		if (isset(  $_REQUEST['text_lenght'] ) ) && sanitize_text_field( $_REQUEST['text_lenght'] ) >= 0) stripslashes( strip_tags( $text_lenght = sanitize_text_field( $_REQUEST['text_lenght'] ) ) );







		if (isset(  $_REQUEST['show_price'] )  && trim(sanitize_text_field( $_REQUEST['show_price'] ) ) != '') $show_price = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['show_price'] ) ) );







		if (isset(  $_REQUEST['q'] )  && trim(sanitize_text_field( $_REQUEST['q'] ) ) != '') $search_keyword = stripslashes( strip_tags( sanitize_text_field( $_REQUEST['q'] ) ) );







		$end_row = $row;







		$total_products = 0;







		// Remove stop words from the search input







		$array = explode (" ", $search_keyword);







		foreach ($array as $word)







		{







			global $wpdb;







			$query = $wpdb->get_results("SELECT idstopwords FROM cp_refyn_stopwords WHERE stopword = '$word'", ARRAY_N );







			if (!empty($query)) 







			{







				$search_keyword = preg_replace('/'.$word.'/', ' ', $search_keyword, 1);







			}







		}







		







		// Search the default??







		if (stripos($search_keyword, "search ") !==false ) {







			$search_keyword = "comfyplane";







		}







		







		// Elchanan DEMO mode







		// Comment these code line before production: 







		// Format  "OBS suggest" => 'user input'







		$demo = array(	"breast" => 'maternity', "jeep" => '4x4', "jeep" => 'crossover', 







						"selfie" => 'iphone self pics', "airplane belt" => 'extend belt',







						"compass" => 'north',







						"survival" => 'sos');







		if ( in_array (	$search_keyword , $demo ))					$search_keyword = array_search($search_keyword, $demo); 







	







		// end DEMO ---







		







		// Elchanan get the sub category if typed by user







		$list_of_cats = all_categories();







		$found_cats = "";







		//$i = array_search($search_keyword, $list_of_cats); 







		foreach ($list_of_cats as $sub_category)







		{







			if (stripos ($sub_category, $search_keyword) !== false)  $found_cats [] = $sub_category ;







		}







		







		// The list is too long for partial KW, so I need 4 chars or more 







		if ($found_cats != "" && strlen ($search_keyword) >3)







		{







			echo "<div class='ajax_search_content_title xolori'>".__('Categories', 'refyn')."</div>\n";







			foreach ($found_cats as $sub_category)







			{			







					$sub_category_url = sanitize_title ($sub_category);







					echo '<a style="text-decoration: underline;" href="'.home_url().'/product-category/'.$sub_category_url.'/">'.ucwords($sub_category).'</a> | ';







			}







		}















		if ($search_keyword != '') {







			$args = array( 'numberposts' => $row+1, 'offset'=> 0, 'orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');







			







			// check product sku first.







			$args['meta_query'][] = array('key' => '_sku','value' => $search_keyword,'compare' => 'LIKE');







			







			// check product in stock.







			$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');







			







			if ($cat_slug != '') {







				$args['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );







				if (get_option('permalink_structure') == '')







					$extra_parameter .= '&scat='.$cat_slug;







				else







					$extra_parameter .= '/scat/'.$cat_slug;







			} elseif($tag_slug != '') {







				$args['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );







				if (get_option('permalink_structure') == '')







					$extra_parameter .= '&stag='.$tag_slug;







				else







					$extra_parameter .= '/stag/'.$tag_slug;







			}







			$total_args = $args;







			$total_args['numberposts'] = -1;







			$search_products = get_posts($args);







			if(empty($search_products))







			{







				// unset previous meta.







				unset($args['meta_query']);







				







				//set new meta to check product title & description







				$args['s'] = $search_keyword;				







				$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');				







				$search_products = get_posts($args);







			}







			







			/**







			 * display product in drop-down list.







			 */







			if ( $search_products && count($search_products) > 0 ) {







								







				echo "<div class='ajax_search_content_title matirol'>".__('Products', 'refyn')."</div>[|]#[|]$search_keyword\n";







				Refyn_Hook_Filter::show_row($search_products,$search_keyword,$extra_parameter,$end_row,$row);







			







			} 







			else {















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







					//echo "SELECT * FROM cp_posts WHERE post_content " . $list; 







				







					global $wpdb;







					$query = $wpdb->get_results("SELECT post_title FROM cp_posts WHERE post_type='product' and post_status='publish' and post_content ". $list, ARRAY_N );







					if (!empty($query))







					{







							foreach( $query as $key => $value)







							{







								$rows[] = $value[0];







							}







					}







			







				}







			







				/* end two or more	  */ 







				















				







				require $_SERVER['DOCUMENT_ROOT']."/phpspellcheck/core/php/engine.php";







				$spellcheckObject = new PHPSpellCheck();







				$spellcheckObject -> LoadDictionary("English (International)");







				$spellResult = array($search_keyword=>$spellcheckObject->Suggestions($search_keyword)); 







				//echo "<pre>";print_r($spellResult);echo "</pre>";exit;







				















				







				$total_search_products = array();







				/**







				 * is suggestion key found.







				 */







				if($spellResult)







				{







					$checked_soundas = 0;







					







					/**







					 * loop to find products by suggested key.







					 */







					foreach ($spellResult as $rows)







					{







					







						// ignore a word that is on OBS database and has times > 2







						// then select a suggestion word that related to my theme







						







						// what is the suggestion word that most soundex like?







						//if ($debug) { print_r($row);  exit; }







						







						// count suggestion word







						if(count($rows)<2)







						{







							global $wpdb;







							$query = $wpdb->get_results("SELECT p.post_title FROM cp_posts p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N );







							if (!empty($query))







							{







								foreach( $query as $key => $value)







								{







									// each column in your row will be accessible like this







									$rows[] = $value[0];







								







								}







							}					







							$left3 = substr($search_keyword,0,3);







							// this will give me few possible words:			   







							$col = "";







							$results = $wpdb->get_results( "SELECT soundas, spelt FROM cp_refyn_soundex WHERE spelt LIKE '$left3%'") ;







							if (empty($results))  







							{







								// reverse







								$results = $wpdb->get_results( "SELECT soundas, spelt FROM cp_refyn_soundex WHERE soundas LIKE '$left3%'") ;







								$col = "spelt";







							}						







							foreach( $results as $result )







							{







								if ($col == "spelt")







								{







									//echo " spelt| ".$result->spelt;







									$q2 = str_ireplace ($left3, $result->spelt, $search_keyword);







								}







								else







								{	







									//echo " soundas| ".$result->soundas;







									$q2 = str_ireplace ($left3, $result->soundas, $search_keyword);







								}







								$rows[]=$q2;					  







				







							}







							$checked_soundas = 1;







						}







						







						







						







						//echo "<pre>";print_r($rows);echo "<pre>";exit;







						foreach ($rows as $q2)







						{







							// $q2 is an empty array = nothing really found!







							if (empty($q2))







							{ 							







								// ------- nothing yet?? ------







								// leave the query with 2 letters only







								$q2 = preg_replace("/[^A-Za-z0-9 ]/", '', $q2); // just ABCD







								//echo "|";  print_r($q2); echo "|";







								$q2 = substr($search_keyword,0,2);







								//continue;







							}







							







							$args1 = array( 'orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');







			







							// check product sku first.







							$args1['meta_query'][] = array('key' => '_sku','value' => $q2,'compare' => 'LIKE');







							







							// check product in stock.







							$args1['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');







							







							if ($cat_slug != '') {







								$args1['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );







								if (get_option('permalink_structure') == '')







									$extra_parameter .= '&scat='.$cat_slug;







								else







									$extra_parameter .= '/scat/'.$cat_slug;







							} elseif($tag_slug != '') {







								$args1['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );







								if (get_option('permalink_structure') == '')







									$extra_parameter .= '&stag='.$tag_slug;







								else







									$extra_parameter .= '/stag/'.$tag_slug;







							}







							$total_args1 = $args1;







							$total_args1['numberposts'] = -1;







				







							







							$search_products1 = get_posts($args1);







							if(empty($search_products1))







							{







								// unset previous meta.







								unset($args1['meta_query']);







								







								//set new meta to check product title & description







								$args1['s'] = $q2;







								$args1['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');







								$search_products1 = get_posts($args1);







							}	







							







							/**







							 * set suggested products in an array.







							 */







							if ( $search_products1 && count($search_products1) > 0 ) {







															







								foreach($search_products1 as $search_products_single)







								{







									// check duplicate product.







									if(!in_array($search_products_single, $total_search_products))







									array_push($total_search_products, $search_products_single);







									







									// break if product increase to limit (row=6 ).







									if(count($total_search_products)>$row)







									break;







									







									







								}







							}







							







						}	







						







						/**







						 * display suggested products.







						 */







						if(count($total_search_products)>0)







						{







							Refyn_Hook_Filter::show_row($total_search_products,$search_keyword,$extra_parameter,$end_row,$row);







						}







						else







						{







							// check if soundas checked before.







							if($checked_soundas==0)







							{







								$rows = array();







								global $wpdb;







								$query = $wpdb->get_results("SELECT p.post_title FROM cp_posts p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N );







								if (!empty($query))







								{







									foreach( $query as $key => $value)







									{







										// each column in your row will be accessible like this







										$rows[] = $value[0];







									







									}







								}					







								$left3 = substr($search_keyword,0,3);







								// this will give me few possible words:			   







								$col = "";







								$results = $wpdb->get_results( "SELECT soundas, spelt FROM cp_refyn_soundex WHERE spelt LIKE '$left3%'") ;







								if (empty($results))  







								{







									// reverse







									$results = $wpdb->get_results( "SELECT soundas, spelt FROM cp_refyn_soundex WHERE soundas LIKE '$left3%'") ;







									$col = "spelt";







								}						







								foreach( $results as $result )







								{







									if ($col == "spelt")







									{







										//echo " spelt| ".$result->spelt;







										$q2 = str_ireplace ($left3, $result->spelt, $search_keyword);







									}







									else







									{	







										//echo " soundas| ".$result->soundas;







										$q2 = str_ireplace ($left3, $result->soundas, $search_keyword);







									}







									$rows[]=$q2;					  







					







								}







								







								//echo "<pre>";print_r($rows);echo "<pre>";exit;







								foreach ($rows as $q2)







								{







									// $q2 is an empty array = nothing really found!







									if (empty($q2))







									{ 							







										// ------- nothing yet?? ------







										// leave the query with 2 letters only







										$q2 = preg_replace("/[^A-Za-z0-9 ]/", '', $q2); // just ABCD







										//echo "|";  print_r($q2); echo "|";







										$q2 = substr($search_keyword,0,2);







										//continue;







									}







									







									$args1 = array( 'orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');







					







									// check product sku first.







									$args1['meta_query'][] = array('key' => '_sku','value' => $q2,'compare' => 'LIKE');







									







									// check product in stock.







									$args1['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');







									







									if ($cat_slug != '') {







										$args1['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );







										if (get_option('permalink_structure') == '')







											$extra_parameter .= '&scat='.$cat_slug;







										else







											$extra_parameter .= '/scat/'.$cat_slug;







									} elseif($tag_slug != '') {







										$args1['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );







										if (get_option('permalink_structure') == '')







											$extra_parameter .= '&stag='.$tag_slug;







										else







											$extra_parameter .= '/stag/'.$tag_slug;







									}







									$total_args1 = $args1;







									$total_args1['numberposts'] = -1;







						







									







									$search_products1 = get_posts($args1);







									if(empty($search_products1))







									{







										// unset previous meta.







										unset($args1['meta_query']);







										







										//set new meta to check product title & description







										$args1['s'] = $q2;







										$args1['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');







										$search_products1 = get_posts($args1);







									}	







									







									/**







									 * set soundas products in an array.







									 */







									if ( $search_products1 && count($search_products1) > 0 ) {







																	







										foreach($search_products1 as $search_products_single)







										{







											// check duplicate product.







											if(!in_array($search_products_single, $total_search_products))







											array_push($total_search_products, $search_products_single);







											







											// break if product increase to limit (row=6 ).







											if(count($total_search_products)>$row)







											break;







											







											







										}







									}







									







								}







								







								if(count($total_search_products)>0)







								{







									Refyn_Hook_Filter::show_row($total_search_products,$search_keyword,$extra_parameter,$end_row,$row);







								}







								else







								{







									// display not found product.







									echo '<div class="ajax_no_result">Ouch! '.__(strtoupper($search_keyword). ' is not found in the store. May we suggest:', 'refyn').'</div>';







									







									// Elchanan 1-OCT-2015 show something instead on nothing:







									$args['s'] = substr($search_keyword,0,4);				







									$args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');				







									$search_products = get_posts($args);







									Refyn_Hook_Filter::show_row($search_products,$search_keyword,$extra_parameter,$end_row,$row);







								}







								







								







							}







							else







							{







								// display not found product.







								echo '<div class="ajax_no_result">'.__(strtoupper($search_keyword). ' is not found in the store. Try these:', 'refyn').'</div>';







								







							   // Elchanan 1-OCT-2015 show something instead on nothing:







							   $args['s'] = substr($search_keyword,0,2);				







							   $args['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');				







							   $search_products = get_posts($args);







							   Refyn_Hook_Filter::show_row($search_products,$search_keyword,$extra_parameter,$end_row,$row);







							}







							







						}







								







						







					}







				







				}







				else







				{







					$rows = array();







					global $wpdb;







					$query = $wpdb->get_results("SELECT p.post_title FROM cp_posts p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N );







					if (!empty($query))







					{







						foreach( $query as $key => $value)







						{







							// each column in your row will be accessible like this







							$rows[] = $value[0];







						







						}







					}					







					$left3 = substr($search_keyword,0,3);







					// this will give me few possible words:			   







					$col = "";







					$results = $wpdb->get_results( "SELECT soundas, spelt FROM cp_refyn_soundex WHERE spelt LIKE '$left3%'") ;







					if (empty($results))  







					{







						// reverse







						$results = $wpdb->get_results( "SELECT soundas, spelt FROM cp_refyn_soundex WHERE soundas LIKE '$left3%'") ;







						$col = "spelt";







					}						







					foreach( $results as $result )







					{







						if ($col == "spelt")







						{







							//echo " spelt| ".$result->spelt;







							$q2 = str_ireplace ($left3, $result->spelt, $search_keyword);







						}







						else







						{	







							//echo " soundas| ".$result->soundas;







							$q2 = str_ireplace ($left3, $result->soundas, $search_keyword);







						}







						$rows[]=$q2;					  







		







					}







					







					//echo "<pre>";print_r($rows);echo "<pre>";exit;







					foreach ($rows as $q2)







					{







						// $q2 is an empty array = nothing really found!







						if (empty($q2))







						{ 							







							// ------- nothing yet?? ------







							// leave the query with 2 letters only







							$q2 = preg_replace("/[^A-Za-z0-9 ]/", '', $q2); // just ABCD







							//echo "|";  print_r($q2); echo "|";







							$q2 = substr($search_keyword,0,2);







							//continue;







						}







						







						$args1 = array( 'orderby' => 'refyn', 'order' => 'ASC', 'post_type' => 'product', 'post_status' => 'publish', 'exclude' => $refyn_id_excludes['exclude_products'], 'suppress_filters' => FALSE, 'ps_post_type' => 'product');







		







						// check product sku first.







						$args1['meta_query'][] = array('key' => '_sku','value' => $q2,'compare' => 'LIKE');







						







						// check product in stock.







						$args1['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');







						







						if ($cat_slug != '') {







							$args1['tax_query'] = array( array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat_slug) );







							if (get_option('permalink_structure') == '')







								$extra_parameter .= '&scat='.$cat_slug;







							else







								$extra_parameter .= '/scat/'.$cat_slug;







						} elseif($tag_slug != '') {







							$args1['tax_query'] = array( array('taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $tag_slug) );







							if (get_option('permalink_structure') == '')







								$extra_parameter .= '&stag='.$tag_slug;







							else







								$extra_parameter .= '/stag/'.$tag_slug;







						}







						$total_args1 = $args1;







						$total_args1['numberposts'] = -1;







			







						







						$search_products1 = get_posts($args1);







						if(empty($search_products1))







						{







							// unset previous meta.







							unset($args1['meta_query']);







							







							//set new meta to check product title & description







							$args1['s'] = $q2;







							$args1['meta_query'][] = array('key' => '_stock_status','value' => 'instock','compare' => '=');







							$search_products1 = get_posts($args1);







						}	







						







						/**







						 * set soundas products in an array.







						 */







						if ( $search_products1 && count($search_products1) > 0 ) {







														







							foreach($search_products1 as $search_products_single)







							{







								// check duplicate product.







								if(!in_array($search_products_single, $total_search_products))







								array_push($total_search_products, $search_products_single);







								







								// break if product increase to limit (row=6 ).







								if(count($total_search_products)>$row)







								break;







								







								







							}







						}







						







					}	







					







					/**







					 * display soundas products.







					 */







					if(count($total_search_products)>0)







					{







						Refyn_Hook_Filter::show_row($total_search_products,$search_keyword,$extra_parameter,$end_row,$row);







					}







					else







					{







						// display not found product.







						echo '<div class="ajax_no_result">'.__(strtoupper($search_keyword). ' is not found in the store.', 'refyn').'</div>';







					}







				}				







			}







		}







		







		







		







		die();







	}







	/*







	* Include the script for widget search and Search page







	*/







	public static function add_frontend_script() {







		wp_enqueue_script('jquery');







		wp_enqueue_script( 'ajax-woo-autocomplete-script', REFYN_JS_URL . '/ajax-autocomplete/jquery.autocomplete.js', array(), false, true );







	}







	public static function add_frontend_style() {







		wp_enqueue_style( 'ajax-woo-autocomplete-style', REFYN_JS_URL . '/ajax-autocomplete/jquery.autocomplete.css' );







	}







	public static function add_query_vars($aVars) {







		$aVars[] = "keyword";    // represents the name of the product category as shown in the URL







		$aVars[] = "scat";







		$aVars[] = "stag";







		return $aVars;







	}







	public static function add_rewrite_rules($aRules) {














		$refyn_search_page_id = get_option('refyn_search_page_id');







		$search_page = get_page($refyn_search_page_id);







		if (!empty($search_page)) {







			$search_page_slug = $search_page->post_name;







			if (stristr($_SERVER['REQUEST_URI'], $search_page_slug) !== FALSE) {







				//$url_text = stristr($_SERVER['REQUEST_URI'], $search_page_slug);







				$position = strpos($_SERVER['REQUEST_URI'], $search_page_slug);







				$new_url = substr($_SERVER['REQUEST_URI'], ($position + strlen($search_page_slug.'/') ) );







				$parameters_array = explode("/", $new_url);







				if (is_array($parameters_array) && count($parameters_array) > 1) {







					$array_key = array();







					$array_value = array();







					$number = 0;







					foreach ($parameters_array as $parameter) {







						$number++;







						if (trim($parameter) == '') continue;







						if ($number%2 == 0) $array_value[] = $parameter;







						else $array_key[] = $parameter;







					}







					if (count($array_key) > 0 && count($array_value) > 0 ) {







						$rewrite_rule = '';







						$original_url = '';







						$number_matches = 0;







						foreach ($array_key as $key) {







							$number_matches++;







							$rewrite_rule .= $key.'/([^/]*)/';







							$original_url .= '&'.$key.'=$matches['.$number_matches.']';







						}







						$aNewRules = array($search_page_slug.'/'.$rewrite_rule.'?$' => 'index.php?pagename='.$search_page_slug.$original_url);







						$aRules = $aNewRules + $aRules;







					}







				}







			}







		}







		return $aRules;







	}







	public static function custom_rewrite_rule() {







		// BEGIN rewrite







		// hook add_query_vars function into query_vars







		add_filter('query_vars', array('Refyn_Hook_Filter', 'add_query_vars') );







		add_filter('rewrite_rules_array', array('Refyn_Hook_Filter', 'add_rewrite_rules') );







		$refyn_search_page_id = get_option('refyn_search_page_id');







		$search_page = get_page($refyn_search_page_id);







		if (!empty($search_page)) {







			$search_page_slug = $search_page->post_name;







			if (stristr($_SERVER['REQUEST_URI'], $search_page_slug) !== FALSE) {







				global $wp_rewrite;







				$wp_rewrite->flush_rules();







			}







		}







		// END rewrite







	}







	public static function remove_special_characters_in_mysql( $field_name ) {







		if ( trim( $field_name ) == '' ) return '';







		$field_name = 'REPLACE( '.$field_name.', "(", "")';







		$field_name = 'REPLACE( '.$field_name.', ")", "")';







		$field_name = 'REPLACE( '.$field_name.', "{", "")';







		$field_name = 'REPLACE( '.$field_name.', "}", "")';







		$field_name = 'REPLACE( '.$field_name.', "<", "")';







		$field_name = 'REPLACE( '.$field_name.', ">", "")';







		$field_name = 'REPLACE( '.$field_name.', "©", "")'; 	// copyright







		$field_name = 'REPLACE( '.$field_name.', "®", "")'; 	// registered







		$field_name = 'REPLACE( '.$field_name.', "™", "")'; 	// trademark







		$field_name = 'REPLACE( '.$field_name.', "£", "")';







		$field_name = 'REPLACE( '.$field_name.', "¥", "")';







		$field_name = 'REPLACE( '.$field_name.', "§", "")';







		$field_name = 'REPLACE( '.$field_name.', "¢", "")';







		$field_name = 'REPLACE( '.$field_name.', "µ", "")';







		$field_name = 'REPLACE( '.$field_name.', "¶", "")';







		$field_name = 'REPLACE( '.$field_name.', "–", "")';







		$field_name = 'REPLACE( '.$field_name.', "¿", "")';







		$field_name = 'REPLACE( '.$field_name.', "«", "")';







		$field_name = 'REPLACE( '.$field_name.', "»", "")';















		$field_name = 'REPLACE( '.$field_name.', "&lsquo;", "")'; 	// left single curly quote







		$field_name = 'REPLACE( '.$field_name.', "&rsquo;", "")'; 	// right single curly quote







		$field_name = 'REPLACE( '.$field_name.', "&ldquo;", "")'; 	// left double curly quote







		$field_name = 'REPLACE( '.$field_name.', "&rdquo;", "")'; 	// right double curly quote







		$field_name = 'REPLACE( '.$field_name.', "&quot;", "")'; 	// quotation mark







		$field_name = 'REPLACE( '.$field_name.', "&ndash;", "")'; 	// en dash







		$field_name = 'REPLACE( '.$field_name.', "&mdash;", "")'; 	// em dash







		$field_name = 'REPLACE( '.$field_name.', "&iexcl;", "")'; 	// inverted exclamation







		$field_name = 'REPLACE( '.$field_name.', "&iquest;", "")'; 	// inverted question mark







		$field_name = 'REPLACE( '.$field_name.', "&laquo;", "")'; 	// guillemets







		$field_name = 'REPLACE( '.$field_name.', "&raquo;", "")'; 	// guillemets







		$field_name = 'REPLACE( '.$field_name.', "&gt;", "")'; 		// greater than







		$field_name = 'REPLACE( '.$field_name.', "&lt;", "")'; 		// less than







		return $field_name;







	}







	







	// search products from db by title & description.







	public static function search_by_title_only( $search, &$wp_query ) {







		global $wpdb;







		global $wp_version;







		$q = $wp_query->query_vars;







		if ( empty( $search) || !isset($q['s']) )







			return $search; // skip processing - no search term in query







		$search = '';







		if ( version_compare( $wp_version, '4.0', '<' ) ) {







			$term = esc_sql( like_escape( trim($q['s'] ) ) );







		} else {







			$term = esc_sql( $wpdb->esc_like( trim($q['s'] ) ) );







		}







		$term_nospecial = preg_replace( "/[^a-zA-Z0-9_.\s]/", "", $term );







		$search_nospecial = false;







		if ( $term != $term_nospecial ) $search_nospecial = true;







		$search .= "( $wpdb->posts.post_title LIKE '%{$term}%' OR $wpdb->posts.post_content LIKE '%{$term}%')";







		if ( $search_nospecial ) $search .= " OR ( $wpdb->posts.post_title LIKE '%{$term_nospecial}%' OR $wpdb->posts.post_content LIKE '%{$term_nospecial}%')";







		if ( ! empty( $search ) ) {







			$search = " AND ({$search}) ";







		}







		return $search;







	}







	public static function refyn_posts_orderby( $orderby, &$wp_query ) {







		global $wpdb;







		global $wp_version;







		$q = $wp_query->query_vars;







		if (isset($q['orderby']) && $q['orderby'] == 'refyn' && isset($q['s']) ) {







			if ( version_compare( $wp_version, '4.0', '<' ) ) {







				$term = esc_sql( like_escape( trim($q['s'] ) ) );







			} else {







				$term = esc_sql( $wpdb->esc_like( trim($q['s'] ) ) );







			}







			$orderby = "$wpdb->posts.post_title NOT LIKE '{$term}%' ASC, $wpdb->posts.post_title ASC";







		}







		return $orderby;







	}







	public static function posts_request_unconflict_role_scoper_plugin( $posts_request, &$wp_query ) {







		$posts_request = str_replace('1=2', '2=2', $posts_request);







		return $posts_request;







	}







	public static function refyn_wp_admin() {







		wp_enqueue_style( 'refynrev-wp-admin-style', REFYN_CSS_URL . '/refyn_wp_admin.css' );







	}







	public static function plugin_extra_links($links, $plugin_name) {







		if ( $plugin_name != REFYN_NAME) {







			return $links;







		}







		$links[] = '<a href="'.WOO_REFYN_SEARCH_DOCS_URI.'" target="_blank">'.__('Documentation', 'refyn').'</a>';







		$links[] = '<a href="https://refyn.org/support/" target="_blank">'.__('Support', 'refyn').'</a>';







		return $links;







	}







}







// Elchanan 12-OCT-2015







function all_categories()







{







	$taxonomy = 'product_cat';







	$orderby = 'name';







	$show_count = 0; // 1 for yes, 0 for no







	$pad_counts = 0; // 1 for yes, 0 for no







	$hierarchical = 1; // 1 for yes, 0 for no







	$title = '';







	$empty = 0;







	$args = array(







	'taxonomy' => $taxonomy,







	'orderby' => $orderby,







	'show_count' => $show_count,







	'pad_counts' => $pad_counts,







	'hierarchical' => $hierarchical,







	'title_li' => $title,







	'hide_empty' => $empty







	);







	$all_categories = get_categories( $args );







	foreach($all_categories as $sub_category)







 	{







	   $list_of_cats[] = strtolower ( $sub_category->cat_name );







 	}







 	







 	return $list_of_cats; 







}







