<?php

/**

 * Refyn Search Widget

 *

 * Table Of Contents

 *

 * get_items_search()

 * __construct()

 * widget()

 * refyn_results_search_form()

 * update()

 * form()

 */

class Refyn_Search_Widgets extends WP_Widget 

{

	

	public static function get_items_search() {

		$items_search = array(

				'product'				=> array( 'number' => 6, 'name' => __('Product Name', 'refyn') ),

				'p_sku'					=> array( 'number' => 0, 'name' => __('Product SKU', 'refyn') ),

				'p_cat'					=> array( 'number' => 0, 'name' => __('Product Categories', 'refyn') ),

				'p_tag'					=> array( 'number' => 0, 'name' => __('Product Tags', 'refyn') ),

				'post'					=> array( 'number' => 0, 'name' => __('Posts', 'refyn') ),

				'page'					=> array( 'number' => 0, 'name' => __('Pages', 'refyn') )

			);

			

		return $items_search;

	}

	function __construct() {

		$widget_ops = array('classname' => 'widget_products_refyn_search', 'description' => __( "User sees search results as they type in a dropdown - links through to 'All Search Results Page' that features endless scroll.", 'refyn') );

		parent::__construct('products_refyn_search', __('Refyn Search', 'refyn'), $widget_ops);

	}

	function widget( $args, $instance ) {

		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		if(empty($instance['number_items']) || is_array($instance['number_items']) || $instance['number_items'] <= 0) $number_items = 6; 

		else $number_items = $instance['number_items'];

		if(empty($instance['text_lenght']) || $instance['text_lenght'] < 0) $text_lenght = 100; 

		else $text_lenght = $instance['text_lenght'];

		$search_global = empty($instance['search_global']) ? 0 : $instance['search_global'];

		$show_price = empty($instance['show_price']) ? 0 : $instance['show_price'];

		$search_box_text = ( isset($instance['search_box_text']) ? $instance['search_box_text'] : '' );

		if (trim($search_box_text) == '') $search_box_text = get_option('refyn_search_box_text');

		echo $before_widget;

		if ( $title )

			echo $before_title . $title . $after_title;

		echo $this->refyn_results_search_form($widget_id, $number_items, $text_lenght, '',$search_global, $search_box_text, $show_price);

		echo $after_widget;

	}

	

	public static function refyn_results_search_form($widget_id, $number_items=6, $text_lenght=100, $style='', $search_global = 0, $search_box_text = '', $show_price = 1) {

		



		// Add ajax search box script and style at footer

		add_action('wp_footer',array('Refyn_Hook_Filter','add_frontend_script'));

		

		$id = str_replace('products_refyn_search-','',$widget_id);

		$refyn_get_result_popup = wp_create_nonce("refyn-get-result-popup");

		

        $cat_slug = ''; 		

		$tag_slug = '';

		$row = 6;

		if ( $number_items > 0  ) $row = $number_items;

		

		ob_start();

		?>

    

<script type="text/javascript">

jQuery(document).ready(function() {

	

	// category width control & get category name

	window.cat_name ='';	

	jQuery(document).on('change', '#scat', function() {

		window.cat_name = jQuery( "#scat" ).val();

		//jQuery("#scat").width(170);		

		jQuery("#pp_course_<?php echo $id;?>").width(139);

		jQuery("#pp_course_<?php echo $id;?>").val('');

				

	});

	

	jQuery(document).on('click', '#default_category', function() {

		//jQuery("#scat").width(40);		

		jQuery("#pp_course_<?php echo $id;?>").width(269);

				

	});



	jQuery(document).on("click", "#bt_pp_search_<?php echo $id;?>", function(){

		if (jQuery("#pp_course_<?php echo $id;?>").val() != '' && jQuery("#pp_course_<?php echo $id;?>").val() != '<?php echo esc_js( $search_box_text ); ?>') {

			<?php if (get_option('permalink_structure') == '') { ?>jQuery("#fr_pp_search_widget_<?php echo $id;?>").submit();<?php } else { ?>var pp_search_url_<?php echo $id;?> = '<?php echo rtrim( get_permalink(get_option('refyn_search_page_id')), '/' );?>/keyword/'+ jQuery("#pp_course_<?php echo $id;?>").val().replace('(', '%28').replace(')', '%29');

			if (window.cat_name != '') { pp_search_url_<?php echo $id;?> += '/scat/'+window.cat_name;}

			<?php if ($tag_slug != '') { ?> pp_search_url_<?php echo $id;?> += '/stag/<?php echo $tag_slug; ?>'; <?php } ?>

			window.location = pp_search_url_<?php echo $id;?>;

		<?php } ?>

		}

	});

	jQuery("#fr_pp_search_widget_<?php echo $id;?>").bind("keypress", function(e) {

		if (e.keyCode == 13) {

			if (jQuery("#pp_course_<?php echo $id;?>").val() != '' && jQuery("#pp_course_<?php echo $id;?>").val() != '<?php echo esc_js( $search_box_text ); ?>') {

				<?php if (get_option('permalink_structure') == '') { ?>jQuery("#fr_pp_search_widget_<?php echo $id;?>").submit();<?php } else { ?>var pp_search_url_<?php echo $id;?> = '<?php echo rtrim( get_permalink(get_option('refyn_search_page_id')), '/' );?>/keyword/'+ jQuery("#pp_course_<?php echo $id;?>").val().replace('(', '%28').replace(')', '%29');

				if (window.cat_name != '') {  pp_search_url_<?php echo $id;?> += '/scat/'+window.cat_name;}

				<?php if ($tag_slug != '') { ?> pp_search_url_<?php echo $id;?> += '/stag/<?php echo $tag_slug; ?>'; <?php } ?>

				window.location = pp_search_url_<?php echo $id;?>;

				<?php } ?>

				return false;

			} else {

				return false;

			}

		}

		

		

		

	});

	var ul_width = jQuery("#pp_search_container_<?php echo $id;?>").find('.ctr_search').innerWidth();

	var ul_height = jQuery("#pp_search_container_<?php echo $id;?>").height();

	var urls = '<?php echo admin_url( 'admin-ajax.php', 'relative' ) ;?>'+'?action=refyn_get_result_popup';

	

		<?php 

		global $setting_options;

		$min_char=$setting_options['refyn_no_of_character_obs_search_box_text'];

		 if( !empty($min_char) && $min_char > 0 )

		$min_char = $min_char;

		else

		$min_char=3;

		

		?>

		jQuery("#pp_course_<?php echo $id;?>").autocomplete(urls, {

			/*width: ul_width,*/

			scrollHeight: 2000,

			max: <?php echo ($row + 2); ?>,

			extraParams: {'row':'<?php echo $row; ?>', 'text_lenght':'<?php echo $text_lenght;?>', 'security':'<?php echo $refyn_get_result_popup;?>' <?php if($tag_slug != ''){ ?>, 'stag':'<?php echo $tag_slug ?>' <?php } ?>, 'show_price':'<?php echo $show_price; ?>' },

			inputClass: "ac_input_<?php echo $id; ?>",

			resultsClass: "ac_results_<?php echo $id; ?>",

			loadingClass: "refyn_loading",

			minChars: "<?php echo $min_char; ?>",

			highlight : false

		});

	

	

	

	jQuery("#pp_course_<?php echo $id;?>").result(function(event, data, formatted) {

		if(data[2] != ''){

			jQuery("#pp_course_<?php echo $id;?>").val(data[2]);

		}

		window.location.href(data[1]);

	});

});

</script>



<style>

.refyn-product-search, .fr_search_widget {

    float: left;

    width: 370px;

}

.ctr_search {

    background: transparent none repeat scroll 0 0;

    border: 1 none;

    padding: 0 !important;

    width: 100%;

}

 .ctr_search select {

    -moz-appearance: none;

    align-content: center;

    background: #00aeef url("<?php echo REFYN_IMAGES_URL ?>/top_search_cat_bg.png") no-repeat scroll right 10px center;

    border-radius: 5px 0 0 5px;

    border-right: 1px solid #1481d2;

    border-style: none solid none none;

    color: #000;

    cursor: pointer;

    float: left;

    height: 35px;

    padding: 5px;

    width: 50px;

}

.ctr_search .txt_livesearch, #wrap .ctr_search .txt_livesearch {

    border: 0 none;

    box-sizing: border-box !important;

    float: left;

    font-size: 14px;

    height: 35px !important;

    padding: 10px !important;

    width: 190px;

}

.refyn-product-search input[type="submit"], .bt_search {

    align-content: center;

    background: #00aeef url("<?php echo REFYN_IMAGES_URL ?>/searchbutton.png") no-repeat scroll center center !important;

    border-radius: 0 5px 5px 0 !important;

    border-style: none;

    color: transparent !important;

    float: left;

    height: 35px;

    max-width: 35px;

    width: 35px;

}

.bt_search {

    background: rgba(0, 0, 0, 0) url("<?php echo REFYN_IMAGES_URL ?>/bg-search.png") no-repeat scroll 0 center;

    cursor: pointer;

    float: left;

    height: 100%;

    max-width: 30px;

    position: absolute;

    top: 0;

    width: 16%;

}

</style>



        <div class="pp_search_container" id="pp_search_container_<?php echo $id;?>" style=" <?php echo $style; ?> ">

        <div style="display:none" class="chrome_xp"></div>zzzzz

		<form autocomplete="off" action="<?php echo str_replace(array('http:','https:'), '', get_permalink(get_option('refyn_search_page_id')) ); ?>" method="get" class="fr_search_widget" id="fr_pp_search_widget_<?php echo $id;?>">

        	<?php

			if (get_option('permalink_structure') == '') {

			?>

            <input type="hidden" name="page_id" value="<?php echo get_option('refyn_search_page_id'); ?>"  />

            <?php } ?>

   			<div class="ctr_search">

            

            <!--category listing-->

            

		<?php if($setting_options['refyn_front_dropdown_option'] == 'yes'){ ?>



            <select id="scat" name="scat2">

            	<option value="" id="default_category">All</option>

                <option value="on-board">ON-BOARD</option>

                <option value="travel-accessories">TRAVEL-ACCESSORIES</option>

                <option value="apparel">APPAREL</option>

                <option value="emergency">EMERGENCY</option>

                <option value="women-accessories">WOMEN-ACCESSORIES</option>

                <option value="toys">TOYS</option>

                <option value="outdoor-living">OUTDOOR</option>

            </select>



		<?php } ?>

            

			<input type="text" id="pp_course_<?php echo $id;?>" onblur="if (this.value == '') {this.value = '<?php echo esc_js( $search_box_text ); ?>';}" onfocus="if (this.value == '<?php echo esc_js( $search_box_text ); ?>') {this.value = '';}" value="<?php echo esc_attr( $search_box_text ); ?>" name="rs" class="txt_livesearch" /><span class="bt_search" id="bt_pp_search_<?php echo $id;?>"></span>

            </div>

            

            <?php if ($tag_slug != '') { ?>

            	<input type="hidden" name="stag" value="<?php echo $tag_slug; ?>"  />

            <?php

			}?>

		</form>

        </div>

        <?php if (trim($style) == '') { ?>

        <div style="clear:both;"></div>

		<?php } ?>

    	<?php

		$search_form = ob_get_clean();

		return $search_form;

	}

	

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		$instance['number_items'] = $new_instance['number_items'];

		$instance['text_lenght'] = strip_tags($new_instance['text_lenght']);

		$instance['show_price'] = $new_instance['show_price'];

		$instance['search_global'] = 1;

		$instance['search_box_text'] = strip_tags($new_instance['search_box_text']);

		return $instance;

	}

	function form( $instance ) {

		$global_search_box_text = get_option('refyn_search_box_text');

		



		$items_search_default = Refyn_Search_Widgets::get_items_search();

		

		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'number_items' => 6, 'text_lenght' => 100, 'show_price' => 1, 'search_global' => 0, 'search_box_text' => $global_search_box_text) );

		$title = strip_tags($instance['title']);

		$number_items = $instance['number_items'];

		if (empty($number_items) || is_array($number_items) ) $number_items = 6;

		else $number_items = strip_tags($instance['number_items']);

		$text_lenght = strip_tags($instance['text_lenght']);

		$show_price = $instance['show_price'];

		$search_global = $instance['search_global'];

		$search_box_text = $instance['search_box_text'];

		

?>

		<style>

			#woo_refyn_upgrade_area { border:2px solid #E6DB55;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; padding:5px; position:relative}

			#woo_refyn_upgrade_area legend {margin-left:4px; font-weight:bold;}

			.item_heading{ width:130px; display:inline-block;}

			ul.refyn_search_item li{padding-left:15px; background:url(<?php echo REFYN_IMAGES_URL; ?>/sortable.gif) no-repeat left center; cursor:pointer;}

			ul.refyn_search_item li.ui-sortable-placeholder{border:1px dotted #111; visibility:visible !important; background:none;}

			ul.refyn_search_item li.ui-sortable-helper{background-color:#DDD;}

		</style>

			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'refyn'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <p><label for="<?php echo $this->get_field_id('search_box_text'); ?>"><?php _e('Search box text message:', 'refyn'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('search_box_text'); ?>" name="<?php echo $this->get_field_name('search_box_text'); ?>" type="text" value="<?php echo esc_attr($search_box_text); ?>" /></p>

            <?php

			if ( class_exists('SitePress') ) {

				global $sitepress;

				$active_languages = $sitepress->get_active_languages();

				if ( is_array($active_languages)  && count($active_languages) > 0 ) {

			?>

			<fieldset id="woo_refyn_upgrade_area"><legend><?php _e('Upgrade to','refyn'); ?> <a href="<?php echo REFYN_AUTHOR_URI; ?>" target="_blank"><?php _e('Pro Version', 'refyn'); ?></a> <?php _e('to activate', 'refyn'); ?></legend>

            <?php

					foreach ( $active_languages as $language ) {

			?>

				<p><label for="search_box_text_<?php echo $language['code']; ?>"><?php _e('Search box text message', 'refyn'); ?> (<?php echo $language['display_name']; ?>)</label> <input disabled="disabled" class="widefat" id="search_box_text_<?php echo $language['code']; ?>" name="search_box_text_language[<?php echo $language['code']; ?>]" type="text" value="" /></p>

			<?php

					}

			?>

            </fieldset>

            <?php

				}

			}

			?>

            <p><label for="<?php echo $this->get_field_id('number_items'); ?>"><?php _e('Number of results to show:', 'refyn'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('number_items'); ?>" name="<?php echo $this->get_field_name('number_items'); ?>" type="text" value="<?php echo esc_attr($number_items); ?>" /></p>

            <p><label><input type="checkbox" name="<?php echo $this->get_field_name('show_price'); ?>" value="1" <?php checked( $show_price, 1 ); ?>  /> <?php _e('Show Product prices', 'refyn'); ?></label>

            </p>

            <p><label for="<?php echo $this->get_field_id('text_lenght'); ?>"><?php _e(' Results description character count:', 'refyn'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('text_lenght'); ?>" name="<?php echo $this->get_field_name('text_lenght'); ?>" type="text" value="<?php echo esc_attr($text_lenght); ?>" /></p>



<?php /*?>

            <fieldset id="woo_refyn_upgrade_area"><legend><?php _e('Upgrade to','refyn'); ?> <a href="<?php echo REFYN_AUTHOR_URI; ?>" target="_blank"><?php _e('Pro Version', 'refyn'); ?></a> <?php _e('to activate', 'refyn'); ?></legend>

            <p><?php _e("Activate search 'types' for this widget by entering the number of results to show in the widget dropdown. &lt;empty&gt; = not activated. Sort order by drag and drop", 'refyn'); ?></p>

            <ul class="ui-sortable refyn_search_item">

            <?php foreach ($items_search_default as $key => $data) { ?>

            	<li><span class="item_heading"><label><?php echo $data['name']; ?></label></span> <input disabled="disabled" id="" name="" type="text" value="<?php echo esc_attr($data['number']); ?>" style="width:50px;" /></li>

            <?php } ?>

            </ul>

            <p><label><?php _e(' Results description character count:', 'refyn'); ?></label> <input disabled="disabled" class="widefat" id="" name="" type="text" value="100" /></p>

            <p><input disabled="disabled" type="radio" value="1" checked="checked"  /> <label><?php _e('Search All Products', 'refyn'); ?></label><br />

            <input disabled="disabled" type="radio" value="0"  /> <label><?php _e('Smart Search', 'refyn'); ?></label>

            </p>

            </fieldset><?php */?>

<?php

	}

}

function woo_refyn_search_widget($ps_echo){

	if($ps_echo)

	the_widget('Refyn_Search_Widgets');

}



