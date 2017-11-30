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
	public static

	function plugins_loaded()
		{
		global $refyn_id_excludes;
		Refyn_Search::get_id_excludes();
		}

	public static

	function pre_get_posts($query)
		{
		$q = $query->query_vars;
		if (isset($q['ps_post_type']))
			{
			$query->set('post_type', $q['ps_post_type']);
			}

		return $query;
		}

	/**
	 * to display product in ajax call.
	 */
	public static

	function show_row($search_products, $search_keyword, $extra_parameter, $end_row, $row)
		{
		$text_lenght = 70;
		global $setting_options;
		$avatar_enable = $setting_options['refyn_show_images_option'];
		$refyn_enable = $setting_options['refyn_results_option'];
		if (!empty($refyn_enable) && $refyn_enable == 'no')
			{
			foreach($search_products as $postt)
				{
				$avatar = false;
				$link_detail = get_permalink($postt->ID);

				// $avatar = Refyn_Search::refyn_get_product_thumbnail($postt->ID, array(64,64), 64,64);

				if (has_post_thumbnail($postt->ID))
					{
					$avatar = get_the_post_thumbnail($postt->ID, array(
						64,
						64
					));
					}
				  else
					{

					// Elchanan 22-NOV-2016  Fix to find inner image or feature

					$args = array(
						'post_parent' => $postt->ID,
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'offset' => '0',
						'numberposts' => 1
					);
					$images = get_posts($args);
					$thumb_url = wp_get_attachment_thumb_url($images[0]->ID);
					if ($thumb_url)
						{
						if (count($images) > 0) $avatar = '<img src="' . $thumb_url . '"  width="64" height="64" />';
						}

					if ($avatar == false || $avatar == '' || is_null($avatar))
						{
						if (!empty($setting_options['refyn_default_image'])) $avatar = '<img src="' . $setting_options['refyn_default_image'] . '" alt="Placeholder" width="64" height="64" />';
						  else $avatar = '<img src="' . REFYN_IMAGES_URL . '/placeholder.png" alt="Placeholder" width="64" height="64" />';
						}
					}

				$product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $postt->post_content)))) , $text_lenght, '...');
				if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $postt->post_excerpt)))) , $text_lenght, '...');
				if (!empty($avatar_enable) && $avatar_enable == 'yes')
					{

					// $post_image = '<span class="rs_avatar">' . $avatar . '</span>';

					$post_image = $avatar;
					}
				  else
					{
					$post_image = '';
					}

				$item = '<a href="' . $link_detail . '"><div class="search_product_img vvx">' . $post_image . '</div><div class="search_product_info"><h4 class="search_product_heading">' . stripslashes($postt->post_title) . '</h4><p>' . $product_description . '</p></div></a><script type="text/javascript">spiralcatalist();</script>';
				if (!is_null($_SERVER["HTTP_REFERER"])) echo $item . '[|]' . $link_detail . '[|]' . stripslashes($postt->post_title) . "\n"; // for popup winodw
				  else echo $item . "\n"; // for search page /?=
				$end_row--;
				if ($end_row < 1) break;
				}
			}
		  else
			{
			
			$woo_product = '';
			$pages_posts = '';	
			foreach($search_products as $product)
				{
				$link_detail = get_permalink($product->ID);
				$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID, 'shop_catalog', 64, 64);
				$product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $product->post_content)))) , $text_lenght, '...');
				if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $product->post_excerpt)))) , $text_lenght, '...');
				$currency = get_woocommerce_currency_symbol();
				$regular_price = get_post_meta($product->ID, '_regular_price', true);
				$regularprice = explode('.', $regular_price);
				$count = count($regularprice);
				$sale_price = get_post_meta($product->ID, '_sale_price', true);
				$saleprice = explode('.', $sale_price);
				$count1 = count($saleprice);
				$price_html = '<span class="rs_price">';
				if ($sale_price)
					{
					if ($count1 == 2)
						{
						$sale_price = $sale_price;
						}
					  else
						{
						$sale_price = $sale_price . '.00';
						}

					$price_html.= '<span style="display:block;"><span class="amount">' . $currency . $sale_price . '</span></span>';
					if ($regular_price)
						{
						if ($count == 2)
							{
							$regular_price = $regular_price;
							}
						  else
							{
							$regular_price = $regular_price . '.00';
							}

						$price_html.= '<del style="display:block;padding-top:2px;"><span class="amount" style="color: #777777">' . $currency . $regular_price . '</span></del> </span>';
						$price_html.= '';
						}
					}
				elseif ($regular_price)
					{
					if ($count == 2)
						{
						$regular_price = $regular_price;
						}
					  else
						{
						$regular_price = $regular_price . '.00';
						}

					$price_html.= '<span class="amount">' . $currency . $regular_price . '</span></span>';
					}

				// $price_html = Refyn_Search_Shortcodes::get_product_price_dropdown($product->ID);

				$price_html = str_replace('<del><span class="amount">', '<del><span style="color: #777777" class="amount">', $price_html);
				if (!empty($setting_options['refyn_predict_win_price_option']) && $setting_options['refyn_predict_win_price_option'] == 'yes') $price_html = '<div style="float: right; font-weight: bolder;">' . str_replace("Price:", "", $price_html) . '</div>';
				  else $price_html = '';
				if (!empty($avatar_enable) && $avatar_enable == 'yes')
					{
					$product_image = '' . $avatar . '';
					}
				  else
					{
					$product_image = '';
					}

				if (get_post_type($product->ID) == 'product')
					{
						$item = '<a href="' . $link_detail . '"><div class="search_product_img vv">' . $product_image . '</div><div class="search_product_info"><h4 class="search_product_heading">' . stripslashes($product->post_title) . '</h4><p>' . $product_description . '</p></div><div class="search_product_cost">' . $price_html . '</div></a>';
						$frt = $item . '[|]' . $link_detail . '[|]' . stripslashes($product->post_title) . "\n";

						$item = str_replace("'", "\'", $item);
						$woo_product .= '<li>'.$item.'</li>';					
					}
				  else
					{

					// /Find the post Thumb Image

					$avatar_post = false;
					if (has_post_thumbnail($product->ID))
						{
						$avatar_post = get_the_post_thumbnail($product->ID, array(
							64,
							64
						));
						}
					  else
						{

						// Elchanan 22-NOV-2016  Fix to find inner image or feature

						$args = array(
							'post_parent' => $product->ID,
							'post_type' => 'attachment',
							'post_mime_type' => 'image',
							'orderby' => 'menu_order',
							'order' => 'ASC',
							'offset' => '0',
							'numberposts' => 1
						);
						$images = get_posts($args);
						$thumb_url = wp_get_attachment_thumb_url($images[0]->ID);
						if ($thumb_url)
							{
							if (count($images) > 0) $avatar_post = '<img src="' . $thumb_url . '"  width="64" height="64" />';
							}

						if ($avatar_post == false || $avatar_post == '' || is_null($avatar_post))
							{
							if (!empty($setting_options['refyn_default_image'])) $avatar_post = '<img src="' . $setting_options['refyn_default_image'] . '" alt="Placeholder" width="64" height="64" />';
							  else $avatar_post = '<img src="' . REFYN_IMAGES_URL . '/placeholder.png" alt="Placeholder" width="64" height="64" />';
							}
						}

					// /Find the post Thumb Image

					$item = '<a href="' . $link_detail . '"><div class="search_product_img vv' . get_post_type($product->ID) . '">' . $avatar_post . '</div><div class="search_product_info"><h4 class="search_product_heading">' . stripslashes($product->post_title) . '</h4><p>' . $product_description . '</p></div><div class="search_product_cost">' . $price_html . '</div></a>';
					echo $item . '[|]' . $link_detail . '[|]' . stripslashes($product->post_title) . "\n";
					}

				$end_row--;
				if ($end_row < 1) break;
				}
			}

		$rs_item = '';
		if (count($search_products) > $row && 1 == 4) // 1==4 to turn this off
			{
			if (get_option('permalink_structure') == '') $link_search = get_permalink(get_option('refyn_search_page_id')) . '&rs=' . urlencode($search_keyword) . $extra_parameter;
			else $link_search = rtrim(get_permalink(get_option('refyn_search_page_id')) , '/') . '/keyword/' . urlencode($search_keyword) . $extra_parameter;
			$rs_item.= '<div class="more_result" rel="more_result"><a href="' . $link_search . '"><h4 class="search_product_heading">' . __('See more results for', 'refyn') . ' ' . $search_keyword . ' <span class="see_more_arrow"></span></h4></a><span>' . __('Displaying top', 'refyn') . ' ' . $row . ' ' . __('results', 'refyn') . '</span></div><script type="text/javascript">spiralcatalist();</script>';
			echo $rs_item . '[|]' . $link_search . '[|]' . $search_keyword . "\n";
			}
			
		if($woo_product!=''){	
		?><script type="text/javascript">spiralcatalist();jQuery('#reltaprddcoral').html(''); js_item_woo_product = <?php echo json_encode($woo_product); ?>;jQuery('#reltaprddcoral').html(js_item_woo_product);jQuery('#rpp').show();</script><?php
		}	
	}

	public static function show_row_modified($search_products, $search_keyword, $extra_parameter, $end_row, $row)
		{
		$text_lenght = 70;
		global $setting_options;
		$avatar_enable = $setting_options['refyn_show_images_option'];
		$refyn_enable = $setting_options['refyn_results_option'];
		if (!empty($refyn_enable) && $refyn_enable == 'no')
			{
			foreach($search_products as $postt)
				{
				$avatar = false;
				$link_detail = get_permalink($postt->ID);
				if (has_post_thumbnail($postt->ID))
					{
					$avatar = get_the_post_thumbnail($postt->ID, array(
						64,
						64
					));
					}
				  else
					{

					// Elchanan 22-NOV-2016  Fix to find inner image or feature

					$args = array(
						'post_parent' => $postt->ID,
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'offset' => '0',
						'numberposts' => 1
					);
					$images = get_posts($args);
					$thumb_url = wp_get_attachment_thumb_url($images[0]->ID);
					if ($thumb_url)
						{
						if (count($images) > 0) $avatar = '<img src="' . $thumb_url . '"  width="64" height="64" />';
						}

					if ($avatar == false || $avatar == '' || is_null($avatar))
						{
						if (!empty($setting_options['refyn_default_image'])) $avatar = '<img src="' . $setting_options['refyn_default_image'] . '" alt="Placeholder" width="64" height="64" />';
						  else $avatar = '<img src="' . REFYN_IMAGES_URL . '/placeholder.png" alt="Placeholder" width="64" height="64" />';
						}
					}

				$product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $postt->post_content)))) , $text_lenght, '...');
				if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $postt->post_excerpt)))) , $text_lenght, '...');
				if (!empty($avatar_enable) && $avatar_enable == 'yes')
					{
					$post_image = '<span class="rs_avatar">' . $avatar . '</span>';
					}
				  else
					{
					$post_image = '';
					}

				$item = '<a href="' . $link_detail . '"><div class="search_product_img ll">' . $avatar . '</div><div class="search_product_info"><h4 class="search_product_heading">' . stripslashes($postt->post_title) . '</h4><p>' . $product_description . '</p></div></a><script type="text/javascript">spiralcatalist();</script>';
				if (!is_null($_SERVER["HTTP_REFERER"])) echo $item . '[|]' . $link_detail . '[|]' . stripslashes($postt->post_title) . "\n"; // for popup winodw
				  else return $item . "\n"; // for search page /?=
				$end_row--;
				if ($end_row < 1) break;
				}
			}
		  else
			{
			foreach($search_products as $product)
				{
				$link_detail = get_permalink($product->ID);
				$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID, 'shop_catalog', 64, 64);
				$product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $product->post_content)))) , $text_lenght, '...');
				if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $product->post_excerpt)))) , $text_lenght, '...');
				$currency = get_woocommerce_currency_symbol();
				$regular_price = get_post_meta($product->ID, '_regular_price', true);
				$regularprice = explode('.', $regular_price);
				$count = count($regularprice);
				$sale_price = get_post_meta($product->ID, '_sale_price', true);
				$saleprice = explode('.', $sale_price);
				$count1 = count($saleprice);
				$price_html = '<span class="rs_price">';
				if ($sale_price)
					{
					if ($count1 == 2)
						{
						$sale_price = $sale_price;
						}
					  else
						{
						$sale_price = $sale_price . '.00';
						}

					$price_html.= '<span style="display:block;"><span class="amount">' . $currency . $sale_price . '</span></span>';
					if ($regular_price)
						{
						if ($count == 2)
							{
							$regular_price = $regular_price;
							}
						  else
							{
							$regular_price = $regular_price . '.00';
							}

						$price_html.= '<del style="display:block;padding-top:2px;"><span class="amount" style="color: #777777">' . $currency . $regular_price . '</span></del> </span>';
						}
					}
				elseif ($regular_price)
					{
					if ($count == 2)
						{
						$regular_price = $regular_price;
						}
					  else
						{
						$regular_price = $regular_price . '.00';
						}

					$price_html.= '<span class="amount">' . $currency . $regular_price . '</span></span>';
					}

				// $price_html = Refyn_Search_Shortcodes::get_product_price_dropdown($product->ID);

				$price_html = str_replace('<del><span class="amount">', '<del><span style="color: #777777" class="amount">', $price_html);
				if (!empty($setting_options['refyn_predict_win_price_option']) && $setting_options['refyn_predict_win_price_option'] == 'yes') $price_html = '<div style="float: right; font-weight: bolder;">' . str_replace("Price:", "", $price_html) . '</div>';
				  else $price_html = '';
				if (!empty($avatar_enable) && $avatar_enable == 'yes')
					{
					$product_image = '' . $avatar . '';
					}
				  else
					{
					$product_image = '';
					}

				$item = '<a href="' . $link_detail . '"><div class="search_product_img nn">' . $product_image . '</div><div class="search_product_info"><h4 class="search_product_heading">' . stripslashes($product->post_title) . '</h4><p>' . $product_description . '</p></div><div class="search_product_cost">' . $price_html . '</div></a>';
				return $item . '[|]' . $link_detail . '[|]' . stripslashes($product->post_title) . "\n";
				$end_row--;
				if ($end_row < 1) break;
				}
			}

		$rs_item = '';
		if (count($search_products) > $row && 1 == 4) // 1==4 to turn this off
			{
			if (get_option('permalink_structure') == '') $link_search = get_permalink(get_option('refyn_search_page_id')) . '&rs=' . urlencode($search_keyword) . $extra_parameter;
			  else $link_search = rtrim(get_permalink(get_option('refyn_search_page_id')) , '/') . '/keyword/' . urlencode($search_keyword) . $extra_parameter;
			$rs_item.= '<div class="more_result" rel="more_result"><a href="' . $link_search . '"><h4 class="search_product_heading">' . __('See more results for', 'refyn') . ' ' . $search_keyword . ' <span class="see_more_arrow"></span></h4></a><span>' . __('Displaying top', 'refyn') . ' ' . $row . ' ' . __('results', 'refyn') . '</span></div><script type="text/javascript">spiralcatalist();</script>';
			return $rs_item . '[|]' . $link_search . '[|]' . $search_keyword . "\n";
			}
		}

	public static

	function show_col($search_products, $search_keyword, $extra_parameter, $end_row, $row)
		{
		$text_lenght = 70;
		global $setting_options;
		$avatar_enable = $setting_options['refyn_show_images_option'];
		$refyn_enable = $setting_options['refyn_results_option'];
		if (!empty($refyn_enable) && $refyn_enable == 'no')
			{
			foreach($search_products as $postt)
				{
				$avatar = false;
				$link_detail = get_permalink($postt->ID);
				if (has_post_thumbnail($postt->ID))
					{
					$avatar = get_the_post_thumbnail($postt->ID, array(
						64,
						64
					));
					}
				  else
					{

					// Elchanan 22-NOV-2016  Fix to find inner image or feature

					$args = array(
						'post_parent' => $postt->ID,
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'offset' => '0',
						'numberposts' => 1
					);
					$images = get_posts($args);
					$thumb_url = wp_get_attachment_thumb_url($images[0]->ID);
					if ($thumb_url)
						{
						if (count($images) > 0) $avatar = '<img src="' . $thumb_url . '"  width="64" height="64" />';
						}
					  else
						{
						preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $product->post_content, $first_image);
						if ($first_image)
							{
							$avatar = '<img src="' . $first_image['src'] . '"  width="64" height="64" />';
							}
						}

					if ($avatar == false || $avatar == '' || is_null($avatar))
						{
						if (!empty($setting_options['refyn_default_image'])) $avatar = '<img src="' . $setting_options['refyn_default_image'] . '" alt="Placeholder" width="64" height="64" />';
						  else $avatar = '<img src="' . REFYN_IMAGES_URL . '/placeholder.png" alt="Placeholder" width="64" height="64" />';
						}
					}

				$desc = "";

				// Find the best $product_description

				if (trim($postt->post_content) != '') $desc = $postt->post_content;
				elseif (trim($product->post_content) != '') $desc = $product->post_content;
				elseif (trim($postt->post_excerpt) != '') $desc = $postt->post_excerpt;
				elseif (trim($product->post_excerpt) != '') $desc = $product->post_excerpt;
				if (trim($desc) != '') $product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $desc)))) , $text_lenght, '...');
				$item = '<div class="rs_result_row"><div class="product-item"><a class="product-image row" href="' . $link_detail . '">' . $avatar . '</a><h3>' . stripslashes($postt->post_title) . '</h3>';
				if (trim($product_description) != '') $item.= '<p class="row"><span class="price">' . $product_description . '</span></p>';
				$item.= '</div><a class="row-c-s-button" href="' . $link_detail . '">View</a></div>';
				echo $item . "\n"; // for search page /?=
				$end_row--;
				if ($end_row < 1) break;
				}
			}
		  else
			{
			foreach($search_products as $product)
				{
				$link_detail = get_permalink($product->ID);
				$avatar = Refyn_Search::refyn_get_product_thumbnail($product->ID, 'shop_catalog', 64, 64);
				$product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $product->post_content)))) , $text_lenght, '...');
				if (trim($product_description) == '') $product_description = Refyn_Search::refyn_limit_words(strip_tags(Refyn_Search::strip_shortcodes(strip_shortcodes(str_replace("\n", "", $product->post_excerpt)))) , $text_lenght, '...');
				$currency = get_woocommerce_currency_symbol();
				$regular_price = get_post_meta($product->ID, '_regular_price', true);
				$regularprice = explode('.', $regular_price);
				$count = count($regularprice);
				$sale_price = get_post_meta($product->ID, '_sale_price', true);
				$saleprice = explode('.', $sale_price);
				$count1 = count($saleprice);
				$price_html = '<span class="rs_price">';
				if ($sale_price)
					{
					if ($count1 == 2)
						{
						$sale_price = $sale_price;
						}
					  else
						{
						$sale_price = $sale_price . '.00';
						}

					$price_html.= '<span style="display:block;"><span class="amount">' . $currency . $sale_price . '</span></span>';
					if ($regular_price)
						{
						if ($count == 2)
							{
							$regular_price = $regular_price;
							}
						  else
							{
							$regular_price = $regular_price . '.00';
							}

						$price_html.= '<del style="display:block;padding-top:2px;"><span class="amount" style="color: #777777">' . $currency . $regular_price . '</span></del> </span>';
						}
					}
				elseif ($regular_price)
					{
					if ($count == 2)
						{
						$regular_price = $regular_price;
						}
					  else
						{
						$regular_price = $regular_price . '.00';
						}

					$price_html.= '<span class="amount">' . $currency . $regular_price . '</span></span>';
					}

				// $price_html = Refyn_Search_Shortcodes::get_product_price_dropdown($product->ID);

				$price_html = str_replace('<del><span class="amount">', '<del><span style="color: #777777" class="amount">', $price_html);
				if (!empty($setting_options['refyn_predict_win_price_option']) && $setting_options['refyn_predict_win_price_option'] == 'yes') $price_html = '<div style="float: right; font-weight: bolder;">' . str_replace("Price:", "", $price_html) . '</div>';
				  else $price_html = '';
				if (!empty($avatar_enable) && $avatar_enable == 'yes')
					{
					$product_image = '' . $avatar . '';
					}
				  else
					{
					$product_image = '';
					}

				$item = '<div class="ajax_search_content">MM<div class="result_row"><a href="' . $link_detail . '">' . $product_image . '<div class="rs_content_popup"><div class="rs_product_dt"><span class="rs_name">' . stripslashes($product->post_title) . '</span><span class="rs_description">' . $product_description . '</span></div>' . $price_html . '</div></a></div></div>';
				echo $item . '[|]' . $link_detail . '[|]' . stripslashes($product->post_title) . "\n";
				$end_row--;
				if ($end_row < 1) break;
				}
			}

		$rs_item = '';
		if (count($search_products) > $row && 1 == 4) // 1==4 to turn this off
			{
			if (get_option('permalink_structure') == '') $link_search = get_permalink(get_option('refyn_search_page_id')) . '&rs=' . urlencode($search_keyword) . $extra_parameter;
			  else $link_search = rtrim(get_permalink(get_option('refyn_search_page_id')) , '/') . '/keyword/' . urlencode($search_keyword) . $extra_parameter;
			$rs_item.= '<div class="more_result" rel="more_result"><a href="' . $link_search . '"><h4 class="search_product_heading">' . __('See more results for', 'refyn') . ' ' . $search_keyword . ' <span class="see_more_arrow"></span></h4></a><span>' . __('Displaying top', 'refyn') . ' ' . $row . ' ' . __('results', 'refyn') . '</span></div><script type="text/javascript">spiralcatalist();</script>';
			echo $rs_item . '[|]' . $link_search . '[|]' . $search_keyword . "\n";
			}
		}

	public static function get_result_popup()
		{
		?><script type="text/javascript">var js_item_woo_product='',js_item_tryto='';</script><?php	
		// add_filter( 'posts_search', array('Refyn_Hook_Filter', 'search_by_title_only'), 500, 2 );

		add_filter('posts_orderby', array(
			'Refyn_Hook_Filter',
			'refyn_posts_orderby'
		) , 500, 2);
		add_filter('posts_request', array(
			'Refyn_Hook_Filter',
			'posts_request_unconflict_role_scoper_plugin'
		) , 500, 2);
		global $refyn_id_excludes, $setting_options;

		// display items limit.

		global $remote_API_server, $home_url, $api_key;
		$remote_API_server = REMOTE_API_SERVER . 'obs_v1/refyn_api.php';
		$home_url = home_url();
		$api_key = $setting_options['refyn_api_key_text'];
		if (empty($api_key))
			{
			echo '<h4><div class="ajax_no_result" style="font-weight:bold;">' . __('Could not found API key in admin.', 'refyn') . '</div></h4>';
			?><script type="text/javascript">spiralcatalist();jQuery('#wooprd').hide();jQuery('.search_logo_sec').css('display','none');</script><?php
			exit;
			}
		  else
			{
			//$check_api = file_get_contents($remote_API_server . '?siteurl=https://comfyplane.com&apikey=391f6cd4c101');
			$check_api = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key);
			$check_api1 = json_decode($check_api, true);
			if ($check_api1['result'])
				{
				echo '<h4><div class="ajax_no_result" style="font-weight:bold;">' . __('Invalid API key.', 'refyn') . '</div></h4>';
				?><script type="text/javascript">spiralcatalist();jQuery('#wooprd').hide();jQuery('.search_logo_sec').css('display','none');</script><?php
				exit;
				}
			}

		$row = 6;
		$text_lenght = 100;
		$show_price = 1;
		$search_keyword = '';
		$cat_slug = sanitize_text_field( $_REQUEST['scat']);
		$tag_slug = '';
		$extra_parameter = '';
		$line_of_cats = '';
		$outofstock_option = $setting_options['refyn_include_outofstock_option'];
		$sku_search_enable = $setting_options['refyn_product_sku_search_option'];
		$no_of_word = $setting_options['refyn_no_of_character_obs_search_box_text'];
		if (!empty($no_of_word)) $keyword_character_count = $no_of_word;
		  else $keyword_character_count = 3;
		$extra_post_type = false;
		$searchin = $setting_options['refyn_search_inside_blog_option'];
		if (!empty($searchin) && $searchin == 'yes')
			{
			$extra_post_type = true;
			}
		  else
			{
			$extra_post_type = false;
			}

		$no_of_row = $setting_options['refyn_no_of_results_search_box_text'];
		if (!empty($no_of_row) && $no_of_row > 0) $row = $no_of_row;
		  else
		if (isset( $_REQUEST['row']) && sanitize_text_field( $_REQUEST['row']) > 0) $row = stripslashes(strip_tags(sanitize_text_field( $_REQUEST['row'])));
		if (isset( $_REQUEST['text_lenght']) && sanitize_text_field( $_REQUEST['text_lenght']) >= 0) stripslashes(strip_tags($text_lenght = sanitize_text_field( $_REQUEST['text_lenght'])));
		if (isset( $_REQUEST['show_price']) && trim(sanitize_text_field( $_REQUEST['show_price'])) != '') $show_price = stripslashes(strip_tags(sanitize_text_field( $_REQUEST['show_price'])));
		if (isset( $_REQUEST['q']) && trim(sanitize_text_field( $_REQUEST['q'])) != '') $search_keyword = stripslashes(strip_tags(sanitize_text_field( $_REQUEST['q'])));
		$end_row = $row;
		$total_products = 0;
		$refyn_enable = $setting_options['refyn_results_option'];
		$link_search = rtrim(get_permalink(get_option('refyn_search_page_id')) , '/') . '/keyword/';

		// Machine learning: Elchanan 13-NOV
		//					If none of the rows selected
		//					We will wait to the next KW to be typed and save it for future use
		//					Here we create a MEMORY SQL TABLE to save the 1st KW state

		global $wpdb;

		// before I start the process, if the cp_refyn_keywords exists --> I don't need all this process!
		// $query = $wpdb->get_results("SELECT kw FROM cp_refyn_keywords WHERE kw = '$search_keyword' OR seo = '$search_keyword' OR the_kw_user_selected = '$search_keyword' LIMIT 0, 1", ARRAY_N );

		$ip = getenv("REMOTE_ADDR");
		$len = strlen($search_keyword);

		// Elchanan 2-DEC-2015    Is the kw = page (department)?	or kw = post (blog)?

		if (strlen($search_keyword) >= $keyword_character_count && $extra_post_type)
			{
			$query = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status='publish' and post_title like '%" . $search_keyword . "%' LIMIT 0, 1", ARRAY_N);

			// is the new lack WHERE going to work on comfyplane?
			// this was after the WHERE -->    (post_type='page' OR post_type='post' OR post_type='vehicle') and

			if (!empty($query))
				{

				// $query = '<a href="' . get_permalink($query[0][0]) . '"><font color=blue>' . ucwords($query[0][1]) . '</font></a> ';
				// $fortyy="<div class='ajax_search_content_title xaolin'>" . __('Navigate to ' . $query . ' Page &gt;&gt;', 'refyn') . " </div>\n";

				$fortyy = '<a href="' . get_permalink($query[0][0]) . '">' . the_contentsmall(ucwords($query[0][1]) , '', '..', false, 20) . '</a>';
?>
<script type="text/javascript">spiralcatalist();var js_navigae_to = <?php echo json_encode($fortyy); ?>;jQuery('#carpoll').html('<h3 class="search_item_heading">NAVIGATE TO</h3>');jQuery('#recattix').html('<li>'+js_navigae_to+'</li>');</script>
<?php //
				$query = null;
				}
			}

		// end kw = page OR ke = post

		/* - end machine learn - */
		$trytosearch = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=insert_new_keyword_interval&q='.urlencode($search_keyword).'&ip='.$ip );
		
		$history_kw_table ="";
// ** START 'try also'
		$trytosearch = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&link_search='.$link_search.'&r=trytosearch&q='.urlencode($search_keyword).'&ip='.$ip );
		$trytosearch_result = json_decode($trytosearch, true);
		//file_put_contents(ABSPATH.'/log.txt', print_r($trytosearch_result, true), FILE_APPEND);
		if (!empty($trytosearch_result))
			{
			if( isset($trytosearch_result['selected']) && !empty($trytosearch_result['selected']) ){
				$selected_key = trim($trytosearch_result['selected']);
				$try_to_case_1 = '<a href="'.site_url().'/refyn-search/keyword/'.$selected_key.'/">'.ucwords($selected_key).'</a>';
			}else{
				$try_to_case_1 = $trytosearch_result['trytosearch'];
			}
				
			$history_kw_table['seo'] = $trytosearch_result['seo'];
			$history_kw_table['selected'] = $trytosearch_result['selected'];

			//			echo "<div class='ajax_search_content_title'>".__('@@TRY FETCH ='.$search_keyword, 'refyn')."</div>[|]#[|]$spellResult\n";

			if (trim($search_keyword) != trim($trytosearch_result['selected']))
				{
				$args['s'] = $trytosearch_result['selected'];

				// echo "try		=".$trytosearch_result['selected'];

				$search_products = get_posts($args);
				Refyn_Hook_Filter::show_row($search_products, $trytosearch_result['selected'], $extra_parameter, $end_row, $row);
				$row_showed = true;
				}
			} // ** ENDS 'try also'

		// Remove stop words from the search input

		$search_keyword = remove_stop_words($search_keyword);

		// Search the default??

		if (stripos($search_keyword, "search ") !== false)
			{
			$search_keyword = "comfyplane";
			}

		// Elchanan get the sub category if typed by user
		// The list is too long for partial KW, so I need 3 chars or more

		$product_category_enable = '';
		$product_category_enable = $setting_options['refyn_product_categories_option'];
		if (!empty($product_category_enable) && $product_category_enable == 'yes' && !empty($refyn_enable) && $refyn_enable == 'yes')
			{
			if (strlen($search_keyword) >= 3)
				{
				$list_of_cats = all_categories();
				$found_cats = "";
				$used_list = array();

				// $i = array_search($search_keyword, $list_of_cats);

				foreach($list_of_cats as $sub_category)
					{
					if (stripos($sub_category, $search_keyword) !== false) $found_cats[] = $sub_category;
					}

				// echo "<br />C132=".count($found_cats);

				if ($found_cats != "")
					{
					#Disable by Ron as for this next script was not working properly.	
					//echo "<div class='ajax_search_content_title mamrix'>" . __('Categories', 'refyn') . "</div>\n"; 
					
					foreach($found_cats as $sub_category)
						{

						// prune duplicates

						if (in_array($sub_category, $used_list)) continue;
						$sub_category_url = sanitize_title($sub_category);
						$line_of_cats.= '<li><a href="' . home_url() . '/product-category/' . $sub_category_url . '/">' . ucwords($sub_category) . '</a></li>';
						}

					$line_of_cats = substr($line_of_cats, 0, -2); // delete the last |
					$used_list[] = $sub_category;

					// print_r($line_of_cats);

					$parambulator = '<h3 class="search_item_heading">RELETED CATEGORIES</h3><div class="search_item_list" ><ul class="link_type_pill list_unstyled list_inline">' . $line_of_cats . '</ul></div>';
?><script type="text/javascript">spiralcatalist();var js_parambulator = <?php echo json_encode($parambulator); ?>;jQuery('#havingcati').html(js_parambulator);</script><?php
					}
				}
			}

		$try_also = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=tryalso&link_search='.urlencode($link_search).'&q='.urlencode($search_keyword));
		$res1 = json_decode($try_also, true);
			
		if (!empty($res1['tryalso']) || isset($try_to_case_1) ){
			$try_to_final = '<h3 class="search_item_heading">TRY ALSO</h3><div class="search_item_list" ><ul class="link_type_pill list_unstyled list_inline" id="rectimatha">';
			
			
			if (!empty($res1['tryalso'])){
				$try_to_final .= '<li class="cdx-1">'. str_replace(', ', '</li><li>', $res1['tryalso']).'</li>';
			}
			if(isset($try_to_case_1)){
				$try_to_final .= '<li class="cdx-2">'.$try_to_case_1.'</li>';
			}
			
			$try_to_final .= '</ul></div>';
			
			?><script type="text/javascript">spiralcatalist();jQuery('#modiserchx').html('');jQuery('#modiserchxpayrol').html('');var js_try_to_final = <?php echo json_encode($try_to_final); ?>;jQuery('#modiserchx').html(js_try_to_final);jQuery('#modiserchxpayrol').html(js_try_to_final);</script><?php
		}
		

		

		if (!empty($refyn_enable) && $refyn_enable == 'no')
			{
			add_filter('posts_where', 'pages_filter_by_fields', 10, 2);
			$post_types = $setting_options['refyn_seach_in'] ? $setting_options['refyn_seach_in'] : 'any';

			// if( is_array( $post_types ) ) $post_types = implode(",", $post_types);

			$page_post = array(
				'numberposts' => $row + 1,
				'offset' => 0,
				'orderby' => "post_type",
				'order' => "DESC",
				'post_type' => $post_types,
				'post_status' => 'publish',
				'suppress_filters' => FALSE
			);
			$search_pages = get_posts($page_post);
			if ($search_pages && count($search_pages) > 0)
				{
				echo "<div class='ajax_search_content_title xiolx'>" . __('Pages or posts', 'refyn') . "</div>[|]#[|]$search_keyword\n";
				Refyn_Hook_Filter::show_row($search_pages, $search_keyword, $extra_parameter, $end_row, $row);
				$row_showed = true;
				}
			  else
				{

				// Elchanan 20-NOV-2016 Nothing found, not even "try also"
				// Let's try spell check 1st

				$spell_suggestions = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=spellcheck&q='.urlencode($search_keyword));
				$all_suggestion = json_decode($spell_suggestions, true);
				$spellResult = $all_suggestion['result'][$search_keyword][0];
				if (strlen($spellResult) > 2)
					{
					$args['s'] = $spellResult;
					$search_products = get_posts($args);

					//				echo "  @SK=$spellResult @@ "; print_r($search_products);

					if (stripos($spellResult, "trial") === true) $spellResult = "oops";
					echo "<div class='ajax_search_content_title capri'>" . __('DID YOU MEAN ' . $spellResult . '?', 'refyn') . "</div>[|]#[|]$spellResult\n";
					Refyn_Hook_Filter::show_row($search_products, $spellResult, $extra_parameter, $end_row, $row);
					$row_showed = true;
					if (strlen($spellResult) >= 3 && strtolower($search_keyword) != strtolower($spellResult))
						{
						$data = array(
							'kw' => $search_keyword,
							'times' => 1,
							'seo' => '',
							'the_kw_user_selected' => $spellResult,
							'source' => 'ML',
							'ip' => $ip,
							'soundex' => soundex($spellResult)
						);
						$insertkeyword = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=insertkeyword&data='.serialize($data));
						}
					}

				// ------- nothing yet?? ------
				// Before I cut the string and leave 2 chars, let's to yahoo suggestion

				$yahoo_result = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate&q='.urlencode($search_keyword));
				$yahoo_result = json_decode($yahoo_result, true);
				$answer = "";
				$line_of_cats = "";


				if (!empty($yahoo_result))
					{
					$i = 0;
					foreach($yahoo_result as $word)
						{
						if (trim(strtolower($search_keyword)) == trim(strtolower($word))) continue;
						$word = str_ireplace($search_keyword, '', $word);
						$word = preg_replace('/\b\w\b(\s|.\s)?/', '', $word);
						$word = remove_stop_words($word);
						if (strlen($word) < 2) continue;
						$line_of_cats.= " " . $word;

						// one result is enough to know what is all about -- and do it to the 1st occurance

						if (!$i) $answer = $word;
						$i++;
						}

					$alt_seo = ltrim($answer);
					$query = '<a href="' . $link_search . $alt_seo . '/"><font color=blue>' . ucwords($alt_seo) . '</font></a> ';

					// Show highlight of what I found
					// 25auggg
					
					 echo "<div style='font-size:12px;margin:0;border-top:none;' class='ajax_search_content_title xoanthis'>You might be interested: " . $query . "&nbsp;<img src='" . plugin_dir_path( __FILE__ ) .  "classes/question-mark.png' height='13' width='13' title='Maybe it related to: " . $line_of_cats . "?'> </div>";

$alt_seo_uc = ucwords($alt_seo);					
?>
<script type="text/javascript">var js_alt_seo=<?php echo json_encode($alt_seo); ?>;var js_alt_seo_uc=<?php echo json_encode($alt_seo_uc); ?>;js_item_tryto +='<li class="cdx-2"><a href="<?php echo $link_search . json_encode($alt_seo);?>/"><?php echo json_encode($alt_seo_uc); ?></a></li>';</script>
<?php

					$search_keyword = $answer;
					}

				// leave the query with 2 letters only

				$spellResult = substr($search_keyword, 0, 2);
				$args['s'] = $spellResult;
				$search_products = get_posts($args);

				// not show 2 words - its funny  -- echo "<div class='ajax_search_content_title'>".__('DID YOU MEAN '.$spellResult.'?', 'refyn')."</div>[|]#[|]$spellResult\n";

				Refyn_Hook_Filter::show_row($search_products, $spellResult, $extra_parameter, $end_row, $row);
				$row_showed = true;
				if (!$row_showed) echo '<div class="ajax_no_result">' . $setting_options['refyn_notfound_error_search_box_text'] . '</div>';
				}

			exit;
			}

		add_filter('posts_where', 'product_filter_by_fields', 10, 2);

		// End of get the sub category ----

		if ($search_keyword != '')
			{
			if (!empty($setting_options['refyn_ranking_formula']) && $setting_options['refyn_ranking_formula'] == '1')
				{
				$post_order = 'DESC';
				$post_orderby = "type";
				}
			  else
			if (!empty($setting_options['refyn_ranking_formula']) && $setting_options['refyn_ranking_formula'] == '2')
				{
				$post_order = 'DESC';
				$post_orderby = "meta_value_num";
				}
			  else
			if (!empty($setting_options['refyn_ranking_formula']) && $setting_options['refyn_ranking_formula'] == '3')
				{
				$post_order = 'ASC';
				$post_orderby = "meta_value_num";
				}
			  else
				{
				$post_order = 'ASC';
				$post_orderby = "refyn";
				}

			$args = array(
				'numberposts' => $row + 1,
				'offset' => 0,
				'orderby' => "$post_orderby",
				'order' => "$post_order",
				'post_type' => 'product',
				'post_status' => 'publish',
				'exclude' => $refyn_id_excludes['exclude_products'],
				'suppress_filters' => FALSE,
				'ps_post_type' => 'product',
				'meta_key' => '_price'
			);

			// check product sku first.
			// check product in stock.

			if (!empty($outofstock_option) && $outofstock_option == 'yes') $args1['meta_query'][] = array(
				'key' => '_stock_status',
				'value' => array(
					'instock',
					'outofstock'
				) ,
				'compare' => 'IN'
			);
			  else $args['meta_query'][] = array(
				'key' => '_stock_status',
				'value' => 'instock',
				'compare' => '='
			);
			if ($cat_slug != '')
				{
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' => $cat_slug
					)
				);
				if (get_option('permalink_structure') == '') $extra_parameter.= '&scat=' . $cat_slug;
				  else $extra_parameter.= '/scat/' . $cat_slug;
				}
			elseif ($tag_slug != '')
				{
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_tag',
						'field' => 'slug',
						'terms' => $tag_slug
					)
				);
				if (get_option('permalink_structure') == '') $extra_parameter.= '&stag=' . $tag_slug;
				  else $extra_parameter.= '/stag/' . $tag_slug;
				}

			$total_args = $args;
			$total_args['numberposts'] = - 1;
			$search_products = get_posts($args);
			if (empty($search_products))
				{

				// unset previous meta.

				unset($args['meta_query']);

				// set new meta to check product title & description

				$args['s'] = $search_keyword;
				if (!empty($outofstock_option) && $outofstock_option == 'yes') $args1['meta_query'][] = array(
					'key' => '_stock_status',
					'value' => array(
						'instock',
						'outofstock'
					) ,
					'compare' => 'IN'
				);
				  else $args['meta_query'][] = array(
					'key' => '_stock_status',
					'value' => 'instock',
					'compare' => '='
				);
				$search_products = get_posts($args);
				}

			/**
			 * display product in drop-down list.
			 */
			if ($search_products && count($search_products) > 0)
				{
				//				echo "<div class='ajax_search_content_title marka'>" . __('Products', 'refyn') . "</div>[|]#[|]$search_keyword\n";
				echo '<div style="display:none" class="pluxdiv">';
				$prdxtt = Refyn_Hook_Filter::show_row($search_products, $search_keyword, $extra_parameter, $end_row, $row);;
				$row_showed = false;
				echo '</div>';
				// echo $prdxtt;
				//				echo '<div style="display:none" id="pluxdiv">';
				//				Refyn_Hook_Filter::show_row($search_products, $search_keyword, $extra_parameter, $end_row, $row);
				//				$row_showed = true;
				//				echo '</div>';
				}
			  else
				{
				$spell_suggestions = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=spellcheck&q='.urlencode($search_keyword));
				$all_suggestion = json_decode($spell_suggestions, true);
				$spellResult = $all_suggestion['result'];
				$total_search_products = array();

				// Elchanan 23-NOV-2015 Try to Translate!

				if (strlen($search_keyword) > 4 && count($spellResult) < 2)
					{
				$google_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=google_translate&q='.urlencode($search_keyword));
					$google_result = json_decode($google_suggestion, true);
					if ($google_result['error']) echo '<img src="' . REFYN_IMAGES_URL . '/api_error.png" style="width:10%;"/><div>' . $google_result['error'] . "</div>";
					if (!empty($google_result['result']))
						{
						$translate = strtolower($google_result['result']);
						if (!$translate || $translate != $search_keyword) // if FALSE translate was failed
						$search_keyword = $translate;
						  else $translate = ""; // set if off for no use in Yahoo
						}
					}

				// @@if (str_word_count($search_keyword) > 1) echo "IM HERE **";

				if ($spellResult)
					{
					$checked_soundas = 0;
					/**
					 * loop to find products by suggested key.
					 */
					foreach($spellResult as $rows)
						{
						if (count($rows) < 2)
							{
							global $wpdb;
							$query = $wpdb->get_results("SELECT p.post_title FROM {$wpdb->posts} p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N);
							$soundex_match = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=soundex_match&q='.urlencode($search_keyword).'&localDB='.serialize($query) );
							$res1 = json_decode($soundex_match, true);
							if (!empty($res1['soundex']))
								{
								foreach($res1['soundex'] as $soundex)
									{
									$rows[] = $soundex;
									}
								}

							if (!empty($res1['checked_soundas'])) $checked_soundas = $res1['checked_soundas'];
							}

						// Elchanan 28-OCT-2015		Check if kw has been used before and in the LOG?

						global $wpdb;
						$lev = soundex($search_keyword);
						$logs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=logs&q='.urlencode($search_keyword));
						$query = json_decode($logs, true);

						// $query = $wpdb->get_results("SELECT post_id FROM cp_refyn_log WHERE kw = '$search_keyword' LIMIT 0, 1", ARRAY_N );

						if (!empty($query))
							{
							$postid = $query['postid'];
							$search_products = get_post($postid);

							// echo "postid=$postid | tit=".$search_products->post_title;

							$rows[] = $search_products->post_title;
							}
						  else
							{

							// try OR soundex = '$lev'
							// $lev = substr($lev,0,3);

							$revlogs = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=reverselogs&q='.$lev);
							$query = json_decode($revlogs, true);

							// $query = $wpdb->get_results("SELECT post_id FROM cp_refyn_log WHERE soundex = '$lev' LIMIT 0, 1", ARRAY_N );

							if (!empty($query))
								{
								$postid = $query['postid'];
								$search_products = get_post($postid);

								// echo "lev=$lev | tit=".$search_products->post_title;

								$rows[] = $search_products->post_title;
								}
							}

						// ELchnanan 30-NOV    Get yahoo suggestion start =====
						// if ($translate == "") echo "is null - tran";
						// else echo "NOT n=$translate";

					$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate&q='.urlencode($search_keyword));

						// echo "Y!="; print_r ($yahoo_suggestion);

						$yahoo_result = json_decode($yahoo_suggestion, true);
						if (!empty($yahoo_result['result']))
							{
							if ($yahoo_result['result']['bossresponse']['related']['count'] == 0)
								{
							$yahoo_suggestion = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=yahoo_translate_extra&q='.urlencode($search_keyword));
								$yahoo_result = json_decode($yahoo_suggestion, true);
								$answer = remove_stop_words($yahoo_result['result']['bossresponse']['web']['results'][0]['title']); // one result is enough to know what is all about

								// echo "Possible="; print_r ($json->bossresponse->web->results);
								// try to get better answer

								$seo = "";
								$wordsCount = 999;
								foreach($yahoo_result['result']['bossresponse']['web']['results'] as $word)
									{
									$tmp = remove_stop_words($word['title']);

									// echo $tmp."*L=".str_word_count ($tmp);

									if (str_word_count($tmp) < $wordsCount) $seo = $tmp;
									$wordsCount = str_word_count($tmp);
									}

								if (strlen($answer) < 6) $answer = $yahoo_result['result']['bossresponse']['web']['results'][0]['abstract'];
								}
							}

						$answer.= " " . $yahoo_result['result']['bossresponse']['related']['results'][0]['suggestion'];
						$answer = str_ireplace($search_keyword, '', $answer); //	remove same words

						// echo "B:".$answer;

						$answer = remove_stop_words($answer);

						//  remove stop words
						// echo " | A:".$answer;

						$rows[] = $answer;

						// Insert what I found immediatly to cp_refyn_keywords table

						if (strlen($answer) >= 3)
							{
							$data = array(
								'kw' => $search_keyword,
								'times' => 1,
								'seo' => $seo,
								'the_kw_user_selected' => $answer,
								'source' => 'ML',
								'ip' => $ip,
								'soundex' => soundex($answer)
							);

							// $query = $wpdb->insert( "cp_refyn_keywords", $data );

						   $insertkeyword = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=insertkeyword&data='.serialize($data));
							$query = json_decode($insertkeyword, true);
							}

						// ====== Get yahoo suggestion stop =====
						// Elchanan 1-OCT-2015 : Two or more words in a query

						/*	start two words */
						if (str_word_count($search_keyword) > 1)
							{
							$array = explode(" ", $search_keyword);
							$list = "";
							$toEnd = count($array);
							foreach($array as $word)
								{
								if (0 === --$toEnd)
									{

									// last value

									if (empty($word)) continue;
									$list = $list . " like '%" . $word . "%' ";
									}
								  else
									{
									if (empty($word)) continue;
									$list = $list . " like '%" . $word . "%' AND post_content ";
									}
								}

							// echo "SELECT * FROM cp_posts WHERE post_content " . $list;

							global $wpdb;
							$query = $wpdb->get_results("SELECT post_title FROM {$wpdb->posts} WHERE post_type='product' and post_status='publish' and post_content " . $list, ARRAY_N);
							if (!empty($query))
								{
								foreach($query as $key => $value)
									{
									$rows[] = $value[0];
									}
								}
							}

						//	Elchanan 15-OCT-2015 try these History rows that I kept
						// if (!isset($rows)) $rows = array();

						if (strlen(@$history_kw_table['seo']) > 2) $rows[] = @$history_kw_table['seo'];
						  else $rows[] = @$history_kw_table['selected'];

						// Elchanan 13 OCT 2015 - check if exists in post_excerpt

						global $wpdb;
						$query = $wpdb->get_results("SELECT post_title FROM {$wpdb->posts} WHERE post_type='product' and post_status='publish' and post_excerpt like '%" . $search_keyword . "%' LIMIT 0, 10", ARRAY_N);
						if (!empty($query))
							{
							foreach($query as $key => $value)
								{
								$rows[] = $value[0];

								// echo "245->".$value[0];

								}
							}

						/* end two or more	  */

						// echo "<pre>";print_r($rows);echo "<pre>";exit;

						foreach($rows as $q2)
							{

							// $q2 is an empty array = nothing really found!

							if (empty($q2))
								{

								// ------- nothing yet?? ------
								// leave the query with 2 letters only

								$q2 = preg_replace("/[^A-Za-z0-9 ]/", '', $q2); // just ABCD

								// echo "|";  print_r($q2); echo "|";

								$q2 = substr($search_keyword, 0, 2);

								// continue;

								}

							remove_filter('posts_where', 'product_filter_by_fields', 10, 2);
							$args1 = array(
								'orderby' => 'refyn',
								'order' => 'ASC',
								'post_type' => 'product',
								'post_status' => 'publish',
								'exclude' => $refyn_id_excludes['exclude_products'],
								'suppress_filters' => FALSE,
								'ps_post_type' => 'product'
							);

							// modified
							// check product sku first.
							// if(!empty($sku_search_enable) && $sku_search_enable == 'yes'){

							$args1['meta_query'][] = array(
								'key' => '_sku',
								'value' => $q2,
								'compare' => 'LIKE'
							);

							// }
							// else
							// $args1['s'] = $q2;
							// check product in stock.

							if (!empty($outofstock_option) && $outofstock_option == 'yes') $args1['meta_query'][] = array(
								'key' => '_stock_status',
								'value' => array(
									'instock',
									'outofstock'
								) ,
								'compare' => 'IN'
							);
							  else $args1['meta_query'][] = array(
								'key' => '_stock_status',
								'value' => 'instock',
								'compare' => '='
							);
							if ($cat_slug != '')
								{
								$args1['tax_query'] = array(
									array(
										'taxonomy' => 'product_cat',
										'field' => 'slug',
										'terms' => $cat_slug
									)
								);
								if (get_option('permalink_structure') == '') $extra_parameter.= '&scat=' . $cat_slug;
								  else $extra_parameter.= '/scat/' . $cat_slug;
								}
							elseif ($tag_slug != '')
								{
								$args1['tax_query'] = array(
									array(
										'taxonomy' => 'product_tag',
										'field' => 'slug',
										'terms' => $tag_slug
									)
								);
								if (get_option('permalink_structure') == '') $extra_parameter.= '&stag=' . $tag_slug;
								  else $extra_parameter.= '/stag/' . $tag_slug;
								}

							$total_args1 = $args1;
							$total_args1['numberposts'] = - 1;
							$search_products1 = get_posts($args1);
							if (empty($search_products1))
								{

								// unset previous meta.

								unset($args1['meta_query']);

								// set new meta to check product title & description

								$args1['s'] = $q2;
								if (!empty($outofstock_option) && $outofstock_option == 'yes') $args1['meta_query'][] = array(
									'key' => '_stock_status',
									'value' => array(
										'instock',
										'outofstock'
									) ,
									'compare' => 'IN'
								);
								  else $args1['meta_query'][] = array(
									'key' => '_stock_status',
									'value' => 'instock',
									'compare' => '='
								);
								$search_products1 = get_posts($args1);
								}

							/**
							 * set suggested products in an array.
							 */
							if ($search_products1 && count($search_products1) > 0)
								{
								foreach($search_products1 as $search_products_single)
									{

									// check duplicate product.

									if (!in_array($search_products_single, $total_search_products)) array_push($total_search_products, $search_products_single);

									// break if product increase to limit (row=6 ).

									if (count($total_search_products) > $row) break;
									}
								}
							}

						/**
						 * display suggested products.
						 */
						//echo "<div class='ajax_search_content_title zpata' style='border-bottom:none;margin:0; padding:0;width:100%!important;'></div>\n";
						if (count($total_search_products) > 0)
							{
							Refyn_Hook_Filter::show_row($total_search_products, $search_keyword, $extra_parameter, $end_row, $row);
							$row_showed = true;
							}
						  else
							{

							// check if soundas checked before.

							if ($checked_soundas == 0)
								{
								$rows = array();
								global $wpdb;
								$query = $wpdb->get_results("SELECT p.post_title FROM {$wpdb->posts} p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N);
								$soundex_match = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=soundex_match&q='.$search_keyword.'&localDB='.serialize($query) );
								$res1 = json_decode($soundex_match, true);
								$checked_soundas = $res1['checked_soundas'];
								if (!empty($res1['soundex']))
									{
									foreach($res1['soundex'] as $soundex)
										{
										$rows[] = $soundex;
										}
									}

								/*if (!empty($query))
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

								// echo " spelt| ".$result->spelt;

								$q2 = str_ireplace ($left3, $result->spelt, $search_keyword);
								}
								  else
								{

								// echo " soundas| ".$result->soundas;

								$q2 = str_ireplace ($left3, $result->soundas, $search_keyword);
								}

								$rows[]=$q2;
								}*/

								// echo "<pre>";print_r($rows);echo "<pre>";exit;

								foreach($rows as $q2)
									{

									// $q2 is an empty array = nothing really found!

									if (empty($q2))
										{

										// ------- nothing yet?? ------
										// leave the query with 2 letters only

										$q2 = preg_replace("/[^A-Za-z0-9 ]/", '', $q2); // just ABCD

										// echo "|";  print_r($q2); echo "|";

										$q2 = substr($search_keyword, 0, 2);

										// continue;

										}

									$args1 = array(
										'orderby' => 'refyn',
										'order' => 'ASC',
										'post_type' => 'product',
										'post_status' => 'publish',
										'exclude' => $refyn_id_excludes['exclude_products'],
										'suppress_filters' => FALSE,
										'ps_post_type' => 'product'
									);

									// check product sku first.

									if (!empty($sku_search_enable) && $sku_search_enable == 'yes')
										{
										$args1['meta_query'][] = array(
											'key' => '_sku',
											'value' => $q2,
											'compare' => 'LIKE'
										);
										}
									  else $args['s'] = $q2;

									// check product in stock.

									if (!empty($outofstock_option) && $outofstock_option == 'yes') $args1['meta_query'][] = array(
										'key' => '_stock_status',
										'value' => array(
											'instock',
											'outofstock'
										) ,
										'compare' => 'IN'
									);
									  else $args1['meta_query'][] = array(
										'key' => '_stock_status',
										'value' => 'instock',
										'compare' => '='
									);
									if ($cat_slug != '')
										{
										$args1['tax_query'] = array(
											array(
												'taxonomy' => 'product_cat',
												'field' => 'slug',
												'terms' => $cat_slug
											)
										);
										if (get_option('permalink_structure') == '') $extra_parameter.= '&scat=' . $cat_slug;
										  else $extra_parameter.= '/scat/' . $cat_slug;
										}
									elseif ($tag_slug != '')
										{
										$args1['tax_query'] = array(
											array(
												'taxonomy' => 'product_tag',
												'field' => 'slug',
												'terms' => $tag_slug
											)
										);
										if (get_option('permalink_structure') == '') $extra_parameter.= '&stag=' . $tag_slug;
										  else $extra_parameter.= '/stag/' . $tag_slug;
										}

									$total_args1 = $args1;
									$total_args1['numberposts'] = - 1;
									$search_products1 = get_posts($args1);
									if (empty($search_products1))
										{

										// unset previous meta.

										unset($args1['meta_query']);

										// set new meta to check product title & description

										$args1['s'] = $q2;
										if (!empty($outofstock_option) && $outofstock_option == 'yes') $args1['meta_query'][] = array(
											'key' => '_stock_status',
											'value' => array(
												'instock',
												'outofstock'
											) ,
											'compare' => 'IN'
										);
										  else $args1['meta_query'][] = array(
											'key' => '_stock_status',
											'value' => 'instock',
											'compare' => '='
										);
										$search_products1 = get_posts($args1);
										}

									/**
									 * set soundas products in an array.
									 */
									if ($search_products1 && count($search_products1) > 0)
										{
										foreach($search_products1 as $search_products_single)
											{

											// check duplicate product.

											if (!in_array($search_products_single, $total_search_products)) array_push($total_search_products, $search_products_single);

											// break if product increase to limit (row=6 ).

											if (count($total_search_products) > $row) break;
											}
										}
									}

								if (count($total_search_products) > 0)
									{
									Refyn_Hook_Filter::show_row($total_search_products, $search_keyword, $extra_parameter, $end_row, $row);
									$row_showed = true;
									}
								  else
									{
									$not_found_msg = $setting_options['refyn_notfound_error_search_box_text'];
									if (!empty($not_found_msg)) $not_found_error_msg = $not_found_msg;
									  else $not_found_error_msg = 'Ouch! ' . __(strtoupper($search_keyword) . ' is not found in the store. May we suggest:', 'refyn');

									// display not found product.

									echo '<div class="ajax_no_result">' . $not_found_error_msg . '</div>';

									// Elchanan 1-OCT-2015 show something instead on nothing:

									$args['s'] = substr($search_keyword, 0, 4);
									remove_filter('posts_where', 'product_filter_by_fields', 10, 2);
									if (!empty($outofstock_option) && $outofstock_option == 'yes') $args['meta_query'] = array(
										'relation' => 'OR',
										array(
											'key' => '_stock_status',
											'value' => 'instock',
											'compare' => '='
										) ,
										array(
											'key' => '_stock_status',
											'value' => 'outofstock',
											'compare' => '='
										)
									);
									  else $args['meta_query'][] = array(
										'key' => '_stock_status',
										'value' => 'instock',
										'compare' => '='
									);
									$search_products = get_posts($args);
									Refyn_Hook_Filter::show_row($search_products, $search_keyword, $extra_parameter, $end_row, $row);
									$row_showed = true;
									}
								}
							  else
								{

								// display not found product.

								echo '<div class="ajax_no_result">' . __(strtoupper($search_keyword) . ' is not found in the store. Try these:', 'refyn') . '</div>';

								// Elchanan 1-OCT-2015 show something instead on nothing:

								remove_filter('posts_where', 'product_filter_by_fields', 10, 2);
								$left_chars = $setting_options['refyn_no_of_letter_search_box_text'];
								$fuzzy_rows = $setting_options['refyn_unrelated_result_search_box_text'];
								$end_roww = $fuzzy_rows;
								if (!empty($left_chars) && $left_chars > 0) $left_character = $left_chars;
								  else $left_character = 2;
								$args['s'] = substr($search_keyword, 0, $left_character);
								if (!empty($outofstock_option) && $outofstock_option == 'yes') $args1['meta_query'][] = array(
									'key' => '_stock_status',
									'value' => array(
										'instock',
										'outofstock'
									) ,
									'compare' => 'IN'
								);
								  else $args['meta_query'][] = array(
									'key' => '_stock_status',
									'value' => 'instock',
									'compare' => 'LIKE'
								);
								$search_products = get_posts($args);
								Refyn_Hook_Filter::show_row($search_products, $search_keyword, $extra_parameter, $end_roww, $fuzzy_rows);
								$row_showed = true;
								}
							}
						}
					} // end of 				if($spellResult)
				  else
					{

					// Not found in spellcheck dictionary

					$rows = array();
					global $wpdb;
					$query = $wpdb->get_results("SELECT p.post_title FROM {$wpdb->posts} p WHERE post_type='product' and post_status='publish' and soundex_match('$search_keyword', p.post_title, ' ')", ARRAY_N);
					$soundex_match = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=soundex_match&q='.$search_keyword.'&localDB='.serialize($query) );
					$res1 = json_decode($soundex_match, true);
					$checked_soundas = $res1['checked_soundas'];
					if (!empty($res1['soundex']))
						{
						foreach($res1['soundex'] as $soundex)
							{
							$rows[] = $soundex;
							}
						}

					/*if (!empty($query))
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

					// echo " spelt| ".$result->spelt;

					$q2 = str_ireplace ($left3, $result->spelt, $search_keyword);
					}
					  else
					{

					// echo " soundas| ".$result->soundas;

					$q2 = str_ireplace ($left3, $result->soundas, $search_keyword);
					}

					$rows[]=$q2;
					}*/

					// echo "<pre>";print_r($rows);echo "<pre>";exit;

					foreach($rows as $q2)
						{

						// $q2 is an empty array = nothing really found!

						if (empty($q2))
							{

							// ------- nothing yet?? ------
							// leave the query with 2 letters only

							$q2 = preg_replace("/[^A-Za-z0-9 ]/", '', $q2); // just ABCD

							// echo "|";  print_r($q2); echo "|";

							$q2 = substr($search_keyword, 0, 2);

							// continue;

							}

						remove_filter('posts_where', 'product_filter_by_fields', 10, 2);
						$args1 = array(
							'orderby' => 'refyn',
							'order' => 'ASC',
							'post_type' => 'product',
							'post_status' => 'publish',
							'exclude' => $refyn_id_excludes['exclude_products'],
							'suppress_filters' => FALSE,
							'ps_post_type' => 'product'
						);

						// check product sku first.

						if (!empty($sku_search_enable) && $sku_search_enable == 'yes')
							{
							$args1['meta_query'][] = array(
								'key' => '_sku',
								'value' => $q2,
								'compare' => 'LIKE'
							);
							}
						  else $args['s'] = $q2;

						// check product in stock.

						if (!empty($outofstock_option) && $outofstock_option == 'yes') $args1['meta_query'][] = array(
							'key' => '_stock_status',
							'value' => array(
								'instock',
								'outofstock'
							) ,
							'compare' => 'IN'
						);
						  else $args1['meta_query'][] = array(
							'key' => '_stock_status',
							'value' => 'instock',
							'compare' => '='
						);
						if ($cat_slug != '')
							{
							$args1['tax_query'] = array(
								array(
									'taxonomy' => 'product_cat',
									'field' => 'slug',
									'terms' => $cat_slug
								)
							);
							if (get_option('permalink_structure') == '') $extra_parameter.= '&scat=' . $cat_slug;
							  else $extra_parameter.= '/scat/' . $cat_slug;
							}
						elseif ($tag_slug != '')
							{
							$args1['tax_query'] = array(
								array(
									'taxonomy' => 'product_tag',
									'field' => 'slug',
									'terms' => $tag_slug
								)
							);
							if (get_option('permalink_structure') == '') $extra_parameter.= '&stag=' . $tag_slug;
							  else $extra_parameter.= '/stag/' . $tag_slug;
							}

						$total_args1 = $args1;
						$total_args1['numberposts'] = - 1;
						$search_products1 = get_posts($args1);
						if (empty($search_products1))
							{

							// unset previous meta.

							unset($args1['meta_query']);

							// set new meta to check product title & description

							$args1['s'] = $q2;
							if (!empty($outofstock_option) && $outofstock_option == 'yes') $args1['meta_query'][] = array(
								'key' => '_stock_status',
								'value' => array(
									'instock',
									'outofstock'
								) ,
								'compare' => 'IN'
							);
							  else $args1['meta_query'][] = array(
								'key' => '_stock_status',
								'value' => 'instock',
								'compare' => '='
							);
							$search_products1 = get_posts($args1);
							}

						/**
						 * set soundas products in an array.
						 */
						if ($search_products1 && count($search_products1) > 0)
							{
							foreach($search_products1 as $search_products_single)
								{

								// check duplicate product.

								if (!in_array($search_products_single, $total_search_products)) array_push($total_search_products, $search_products_single);

								// break if product increase to limit (row=6 ).

								if (count($total_search_products) > $row) break;
								}
							}
						}

					/**
					 * display soundas products.
					 */
					if (count($total_search_products) > 0)
						{
						Refyn_Hook_Filter::show_row($total_search_products, $search_keyword, $extra_parameter, $end_row, $row);
						$row_showed = true;
						}
					  else
						{

						// display not found product.

						echo '<div class="ajax_no_result">' . __(strtoupper($search_keyword) . ' is not found in the store.', 'refyn') . '</div>';
						}
					}
				}
			}

		die();
		}

	/*
	* Include the script for widget search and Search page
	*/
	public static function add_frontend_script()
		{
		wp_enqueue_script('jquery');
		wp_enqueue_script('ajax-woo-autocomplete-script', REFYN_JS_URL . '/ajax-autocomplete/jquery.autocomplete.js', array() , false, true);
		}

	public static

	function add_frontend_style()
		{
		wp_enqueue_style('ajax-woo-autocomplete-style', REFYN_JS_URL . '/ajax-autocomplete/jquery.autocomplete.css');
		}

	public static

	function add_query_vars($aVars)
		{
		$aVars[] = "keyword"; // represents the name of the product category as shown in the URL
		$aVars[] = "scat";
		$aVars[] = "stag";
		return $aVars;
		}

	public static

	function add_rewrite_rules($aRules)
		{

		$refyn_search_page_id = get_option('refyn_search_page_id');
		$search_page = get_page($refyn_search_page_id);
		if (!empty($search_page))
			{
			$search_page_slug = $search_page->post_name;
			if (stristr($_SERVER['REQUEST_URI'], $search_page_slug) !== FALSE)
				{

				// $url_text = stristr($_SERVER['REQUEST_URI'], $search_page_slug);

				$position = strpos($_SERVER['REQUEST_URI'], $search_page_slug);
				$new_url = substr($_SERVER['REQUEST_URI'], ($position + strlen($search_page_slug . '/')));
				$parameters_array = explode("/", $new_url);
				if (is_array($parameters_array) && count($parameters_array) > 1)
					{
					$array_key = array();
					$array_value = array();
					$number = 0;
					foreach($parameters_array as $parameter)
						{
						$number++;
						if (trim($parameter) == '') continue;
						if ($number % 2 == 0) $array_value[] = $parameter;
						  else $array_key[] = $parameter;
						}

					if (count($array_key) > 0 && count($array_value) > 0)
						{
						$rewrite_rule = '';
						$original_url = '';
						$number_matches = 0;
						foreach($array_key as $key)
							{
							$number_matches++;
							$rewrite_rule.= $key . '/([^/]*)/';
							$original_url.= '&' . $key . '=$matches[' . $number_matches . ']';
							}

						$aNewRules = array(
							$search_page_slug . '/' . $rewrite_rule . '?$' => 'index.php?pagename=' . $search_page_slug . $original_url
						);
						$aRules = $aNewRules + $aRules;
						}
					}
				}
			}

		return $aRules;
		}

	public static

	function custom_rewrite_rule()
		{

		// BEGIN rewrite
		// hook add_query_vars function into query_vars

		add_filter('query_vars', array(
			'Refyn_Hook_Filter',
			'add_query_vars'
		));
		add_filter('rewrite_rules_array', array(
			'Refyn_Hook_Filter',
			'add_rewrite_rules'
		));
		$refyn_search_page_id = get_option('refyn_search_page_id');
		$search_page = get_page($refyn_search_page_id);
		if (!empty($search_page))
			{
			$search_page_slug = $search_page->post_name;
			if (stristr($_SERVER['REQUEST_URI'], $search_page_slug) !== FALSE)
				{
				global $wp_rewrite;
				$wp_rewrite->flush_rules();
				}
			}

		// END rewrite

		}

	public static

	function remove_special_characters_in_mysql($field_name)
		{
		if (trim($field_name) == '') return '';
		$field_name = 'REPLACE( ' . $field_name . ', "(", "")';
		$field_name = 'REPLACE( ' . $field_name . ', ")", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "{", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "}", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "<", "")';
		$field_name = 'REPLACE( ' . $field_name . ', ">", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "Â©", "")'; // copyright
		$field_name = 'REPLACE( ' . $field_name . ', "Â®", "")'; // registered
		$field_name = 'REPLACE( ' . $field_name . ', "â"¢", "")'; // trademark
		$field_name = 'REPLACE( ' . $field_name . ', "Â£", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "Â¥", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "Â§", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "Â¢", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "Âµ", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "Â¶", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "â€"", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "Â¿", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "Â«", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "Â»", "")';
		$field_name = 'REPLACE( ' . $field_name . ', "&lsquo;", "")'; // left single curly quote
		$field_name = 'REPLACE( ' . $field_name . ', "&rsquo;", "")'; // right single curly quote
		$field_name = 'REPLACE( ' . $field_name . ', "&ldquo;", "")'; // left double curly quote
		$field_name = 'REPLACE( ' . $field_name . ', "&rdquo;", "")'; // right double curly quote
		$field_name = 'REPLACE( ' . $field_name . ', "&quot;", "")'; // quotation mark
		$field_name = 'REPLACE( ' . $field_name . ', "&ndash;", "")'; // en dash
		$field_name = 'REPLACE( ' . $field_name . ', "&mdash;", "")'; // em dash
		$field_name = 'REPLACE( ' . $field_name . ', "&iexcl;", "")'; // inverted exclamation
		$field_name = 'REPLACE( ' . $field_name . ', "&iquest;", "")'; // inverted question mark
		$field_name = 'REPLACE( ' . $field_name . ', "&laquo;", "")'; // guillemets
		$field_name = 'REPLACE( ' . $field_name . ', "&raquo;", "")'; // guillemets
		$field_name = 'REPLACE( ' . $field_name . ', "&gt;", "")'; // greater than
		$field_name = 'REPLACE( ' . $field_name . ', "&lt;", "")'; // less than
		return $field_name;
		}

	// search products from db by title & description.

	public static

	function search_by_title_only($search, &$wp_query)
		{
		global $wpdb;
		global $wp_version;
		$q = $wp_query->query_vars;
		if (empty($search) || !isset($q['s'])) return $search; // skip processing - no search term in query
		$search = '';
		if (version_compare($wp_version, '4.0', '<'))
			{
			$term = esc_sql(like_escape(trim($q['s'])));
			}
		  else
			{
			$term = esc_sql($wpdb->esc_like(trim($q['s'])));
			}

		$term_nospecial = preg_replace("/[^a-zA-Z0-9_.\s]/", "", $term);
		$search_nospecial = false;
		if ($term != $term_nospecial) $search_nospecial = true;
		$search.= "( $wpdb->posts.post_title LIKE '%{$term}%' OR $wpdb->posts.post_content LIKE '%{$term}%')";
		if ($search_nospecial) $search.= " OR ( $wpdb->posts.post_title LIKE '%{$term_nospecial}%' OR $wpdb->posts.post_content LIKE '%{$term_nospecial}%')";
		if (!empty($search))
			{
			$search = " AND ({$search}) ";
			}

		return $search;
		}

	public static

	function refyn_posts_orderby($orderby, &$wp_query)
		{
		global $wpdb;
		global $wp_version;
		$q = $wp_query->query_vars;
		if (isset($q['orderby']) && $q['orderby'] == 'refyn' && isset($q['s']))
			{
			if (version_compare($wp_version, '4.0', '<'))
				{
				$term = esc_sql(like_escape(trim($q['s'])));
				}
			  else
				{
				$term = esc_sql($wpdb->esc_like(trim($q['s'])));
				}

			$orderby = "$wpdb->posts.post_title NOT LIKE '{$term}%' ASC, $wpdb->posts.post_title ASC";
			}

		return $orderby;
		}

	public static

	function posts_request_unconflict_role_scoper_plugin($posts_request, &$wp_query)
		{
		$posts_request = str_replace('1=2', '2=2', $posts_request);
		return $posts_request;
		}

	public static

	function refyn_wp_admin()
		{
		wp_enqueue_style('refynrev-wp-admin-style', REFYN_CSS_URL . '/refyn_wp_admin.css');
		}

	public static

	function plugin_extra_links($links, $plugin_name)
		{
		if ($plugin_name != REFYN_NAME)
			{
			return $links;
			}

		$links[] = '<a href="' . WOO_REFYN_SEARCH_DOCS_URI . '" target="_blank">' . __('Documentation', 'refyn') . '</a>';
		$links[] = '<a href="https://refyn.org/contact/" target="_blank">' . __('Support', 'refyn') . '</a>';
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
	$all_categories = get_categories($args);
	foreach($all_categories as $sub_category)
		{
		$list_of_cats[] = strtolower($sub_category->cat_name);
		}

	return $list_of_cats;
	}

function remove_stop_words($search_keyword)
	{

	// echo "| SK=$search_keyword |";

	/*$array = explode (" ", $search_keyword);
	foreach ($array as $word)
	{
	global $wpdb;
	$query = $wpdb->get_results("SELECT idstopwords FROM cp_refyn_stopwords WHERE stopword = '$word' LIMIT 0, 10", ARRAY_N );
	if (!empty($query))
	{
	$search_keyword = preg_replace('/'.$word.'/', ' ', $search_keyword, 1);
	}
	}

	// remove special characters

	$search_keyword = preg_replace("/[^a-zA-Z0-9]+/", " ", $search_keyword);

	// I dont knwo where is the 'b' from

	$search_keyword = str_ireplace ('b', "", $search_keyword);

	// remove special characters*/

	global $remote_API_server, $home_url, $api_key;
	$removestopwords = file_get_contents($remote_API_server.'?siteurl='.$home_url.'&apikey='.$api_key.'&r=removestopwords&q='.urlencode($search_keyword));
	$removed_stopword = json_decode($removestopwords, true);
	if (!empty($removed_stopword))
		{
		$search_keyword = $removed_stopword['removestopwords'];
		}

	return $search_keyword;
	}

function product_filter_by_fields($where, &$wp_query)
	{
	global $wpdb, $setting_options;
	$searchAlphabet = stripslashes(strip_tags(sanitize_text_field( $_REQUEST['q'])));
	$content_search = '';
	if ($setting_options['refyn_search_inside_postcontent_option'] == 'yes') $content_search = 'OR ' . $wpdb->posts . '.post_content LIKE \'%' . $searchAlphabet . '%\' ';
	  else $content_search = '';
	$excerpt_search = '';
	if ($setting_options['refyn_search_inside_excerpt_option'] == 'yes') $excerpt_search = 'OR ' . $wpdb->posts . '.post_excerpt LIKE \'%' . $searchAlphabet . '%\' ';
	  else $excerpt_search = '';
	$sku_search = '';
	if ($setting_options['refyn_product_sku_search_option'] == 'yes') $sku_search = " OR (" . $wpdb->postmeta . ".meta_key = '_sku' AND " . $wpdb->postmeta . ".meta_value LIKE '%" . $searchAlphabet . "%')";
	  else $sku_search = '';
	$where.= ' AND ( ' . $wpdb->posts . '.post_title LIKE \'%' . $searchAlphabet . '%\' ' . $content_search . $excerpt_search . ')' . $sku_search;
	return $where;
	}

function pages_filter_by_fields($where, &$wp_query)
	{
	global $wpdb, $setting_options;
	$searchAlphabet = stripslashes(strip_tags(sanitize_text_field( $_REQUEST['q'])));
	$content_search = '';
	if ($setting_options['refyn_search_inside_postcontent_option'] == 'yes') $content_search = 'OR ' . $wpdb->posts . '.post_content LIKE \'%' . $searchAlphabet . '%\' ';
	  else $content_search = '';
	$excerpt_search = '';
	if ($setting_options['refyn_search_inside_excerpt_option'] == 'yes') $excerpt_search = 'OR ' . $wpdb->posts . '.post_excerpt LIKE \'%' . $searchAlphabet . '%\' ';
	  else $excerpt_search = '';
	$where.= ' AND ( ' . $wpdb->posts . '.post_title LIKE \'%' . $searchAlphabet . '%\' ' . $content_search . $excerpt_search . ')';
	return $where;
	}