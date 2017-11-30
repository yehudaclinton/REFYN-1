<?php
/*
Copyright 2016 REFYN 

This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
/*-----------------------------------------------------------------------------------
Refyn Search Global Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class Refyn_Search_options extends Refyn_Search_Admin_UI
{

	/**
	 * @var string
	 */
	private $parent_tab = 'options';

	/**
	 * @var array
	 */
	private $subtab_data;

	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = '';

	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'refyn_search_options';

	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 1;

	/**
	 * @var array
	 */
	public $form_fields = array();

	/**
	 * @var array
	 */
	public $form_messages = array();

	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {

		$this->init_form_fields();
		$this->subtab_init();

		$this->form_messages = array(
				'success_message'	=> __( 'All Options successfully saved.', 'refyn' ),
				'error_message'		=> __( 'Error: Options can not save.', 'refyn' ),
				'reset_message'		=> __( 'All Options successfully reseted.', 'refyn' ),
			);

		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );

		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );

		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'reset_default_settings' ) );

		add_action( $this->plugin_name . '_settings_' . 'refyn_search_searchbox_text' . '_after', array( $this, 'refyn_search_searchbox_text' ) );

		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'after_save_settings' ) );
		//add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );

		// Add yellow border for pro fields
		add_action( $this->plugin_name . '_settings_pro_focus_keywords_before', array( $this, 'pro_fields_before' ) );
		add_action( $this->plugin_name . '_settings_pro_seo_focus_keywords_after', array( $this, 'pro_fields_after' ) );
	}

	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {

		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );

	}

	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {
		global $refyn_search_admin_interface;

		$refyn_search_admin_interface->reset_settings( $this->form_fields, $this->option_name, false );
	}

	/*-----------------------------------------------------------------------------------*/
	/* reset_default_settings()
	/* Reset default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function reset_default_settings() {
		global $refyn_search_admin_interface;

		$refyn_search_admin_interface->reset_settings( $this->form_fields, $this->option_name, true, true );
	}

	/*-----------------------------------------------------------------------------------*/
	/* after_save_settings()
	/* Process when clean on deletion option is un selected */
	/*-----------------------------------------------------------------------------------*/
	public function after_save_settings() {
		if ( ( isset( $_POST['bt_save_settings'] ) || isset( $_POST['bt_reset_settings'] ) ) && get_option( 'refyn_search_lite_clean_on_deletion' ) == 'no' )  {
			$uninstallable_plugins = (array) get_option('uninstall_plugins');
			unset($uninstallable_plugins[REFYN_NAME]);
			update_option('uninstall_plugins', $uninstallable_plugins);
		}
		if ( isset( $_REQUEST['refyn_search_box_text']) ) {
			update_option('refyn_search_box_text',  $_REQUEST['refyn_search_box_text'] );
		}
		
	}

	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
		global $refyn_search_admin_interface;

		$refyn_search_admin_interface->get_settings( $this->form_fields, $this->option_name );
	}

	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array (
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {

		$subtab_data = array(
			'name'				=> 'options',
			'label'				=> __( 'Options', 'refyn' ),
			'callback_function'	=> 'refyn_search_options_form',
		);

		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;

	}

	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {

		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();

		return $subtabs_array;
	}

	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {
		global $refyn_search_admin_interface;

		$output = '';
		$output .= $refyn_search_admin_interface->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );

		return $output;
	}

	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {
		$ranking_formula= array('Select ranking','Newest 1st', 'High price 1st', 'Low price 1st', 'Custom formula');
		
		$search_in = array();
		global $wp_post_types;
		foreach( $wp_post_types as $key=>$type ) {
			$search_in[$key] = $type->label;
		}
		
  		// Define settings
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(

			array(
            	'name' 		=> __( 'Global Search Box Text', 'refyn' ),
                'type' 		=> 'heading',
				'id'		=> 'refyn_search_searchbox_text',
           	),
			
				array(
				'name' 		=> __( 'Number of character', 'refyn' ),
				'desc'		=> __( 'Number of character to find results', 'refyn' ),
				'id' 		=> 'refyn_no_of_character_obs_search_box_text',
				'type' 		=> 'slider',
				'min'	=> '0',
				'max'	=> '10',

				'default'	=> 3,

				'free_version'		=> true,
			),
			array(
				'name' 		=> __( 'Number of rows', 'refyn' ),
				'desc'		=> __( 'Display no of search results in search box list', 'refyn' ),
				'id' 		=> 'refyn_no_of_results_search_box_text',
				'type' 		=> 'slider',
				'min'	=> '0',
				'max'	=> '10',
				'default'	=> 6,
				'free_version'		=> true,
			),
			
				array(
				'name' 		=> __( 'Fuzzy level', 'refyn' ),
				'desc'		=> __( 'No of rows for unrelated results', 'refyn' ),
				'id' 		=> 'refyn_unrelated_result_search_box_text',
				'type' 		=> 'slider',
				'min'	=> '0',
				'max'	=> '10',
				'default'	=> 6,
				'free_version'		=> true,
			),
			
			array(
				'name' 		=> __( 'Number of left letter', 'refyn' ),
				'desc'		=> __( 'Search according to left characters if result is null', 'refyn' ),
				'id' 		=> 'refyn_no_of_letter_search_box_text',
				'type' 		=> 'slider',
				'min'	=> '0',
				'max'	=> '10',
				'default'	=> 2,
				'free_version'		=> true,
			),
			
			array(
				'name' 		=> __( 'Not found error message', 'refyn' ),
				'desc'		=> __( 'Display not found error message', 'refyn' ),
				'id' 		=> 'refyn_notfound_error_search_box_text',
				'type' 		=> 'text',
				'default'	=> '',
				'free_version'		=> true,
			),

			array(
				'name' 		=> __( 'API key', 'refyn' ),
				//'desc'		=> __( '&lt;empty&gt; shows nothing', 'refyn' ),
				'id' 		=> 'refyn_api_key_text',
				'type' 		=> 'text',
				'default'	=> '',
				'free_version'		=> true,
			),
			
			array(  
				'name' 		=> __( 'Ranking Formula', 'refyn' ),
				'id' 		=> 'refyn_ranking_formula',
				'type' 		=> 'select',
				'default'	=> 1,
				'options'	=> $ranking_formula,
				'free_version'		=> true,
			),
			
			array(
				'name' 		=> __( 'Search in (hold the CTRL to select multiple)', 'refyn' ),
				'class'		=> 'refyn_seach_in',
				'id' 		=> 'refyn_seach_in',
				'type' 		=> 'multiselect',
				'default'	=> 'post',
				'options'	=> $search_in,
				'free_version'		=> true,
			),
			
			array(
				'name' 		=> __( 'Woocommerce results', 'refyn' ),
				'class'		=> 'refyn_results_enable',
				'id' 		=> 'refyn_results_option',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			
			array(
				'name' 		=> __( 'Show Images', 'refyn' ),
				'class'		=> 'refyn_show_images_enable',
				'id' 		=> 'refyn_show_images_option',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			array(
				'name' 		=> __( 'Display Price', 'refyn' ),
				'class'		=> 'refyn_predict_win_price_enable',
				'id' 		=> 'refyn_predict_win_price_option',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			array(
				'name' 		=> __( 'Search inside blog post', 'refyn' ),
				'class'		=> 'refyn_search_focus_enable',
				'id' 		=> 'refyn_search_inside_blog_option',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			
			array(
				'name' 		=> __( 'SKU Search', 'refyn' ),
				'class'		=> 'refyn_product_sku_search_enable',
				'id' 		=> 'refyn_product_sku_search_option',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			
			array(
				'name' 		=> __( 'Search in Post Content', 'refyn' ),
				'class'		=> 'refyn_search_in_postcontent_enable',
				'id' 		=> 'refyn_search_inside_postcontent_option',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			
			array(
				'name' 		=> __( 'Search in Post Excerpt', 'refyn' ),
				'class'		=> 'refyn_search_in_excerpt_enable',
				'id' 		=> 'refyn_search_inside_excerpt_option',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
		array(
				'name' 		=> __( 'Include Out-of-stock items', 'refyn' ),
				'class'		=> 'refyn_include_outofstock_enable',
				'id' 		=> 'refyn_include_outofstock_option',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			
			array(
				'name' 		=> __( 'Product Categories with suggestion', 'refyn' ),
				'class'		=> 'refyn_product_categories_enable',
				'id' 		=> 'refyn_product_categories_option',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
		array(
				'name' 		=> __( 'Search by Department Dropdown', 'refyn' ),
				'class'		=> 'refyn_front_dropdown_enable',
				'id' 		=> 'refyn_front_dropdown_option',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
		array(
				'name' 		=> __( 'Default image with result', 'refyn' ),
				'class'		=> 'refyn_default_image_option',
				'id' 		=> 'refyn_default_image',
				'type' 		=> 'upload',
				'default'	=> REFYN_IMAGES_URL.'/placeholder.png',
				'free_version'		=> true,
			),
			
			

        ));
	}

	function refyn_search_searchbox_text() {
		if ( class_exists('SitePress') ) {

			global $sitepress;
			$active_languages = $sitepress->get_active_languages();
			if ( is_array($active_languages)  && count($active_languages) > 0 ) {
	?>
    		<div class="pro_feature_fields">
    		<table class="form-table">
    <?php
				foreach ( $active_languages as $language ) {
	?>
    		<tr valign="top" class="">
				<th class="titledesc" scope="row"><label for="refyn_search_box_text_<?php echo $language['code']; ?>"><?php _e('Text to Show', 'refyn');?> (<?php echo $language['display_name']; ?>)</label></th>
				<td class="forminp">
                	<input type="text" class="" value="" style="min-width:300px;" id="refyn_search_box_text_<?php echo $language['code']; ?>" name="refyn_search_box_text_language[<?php echo $language['code']; ?>]" /> <span class="description"><?php _e('&lt;empty&gt; shows nothing', 'refyn'); ?></span>
				</td>
			</tr>
    <?php
				}
	?>
    		</table>
            </div>
    <?php
			}
    	}

	}

	public function include_script() {
	?>
<script>
(function($) {

	$(document).ready(function() {

		if ( $("input.refyn_search_focus_enable:checked").val() == 'yes') {
			$('.refyn_search_focus_plugin_container').css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		} else {
			$('.refyn_search_focus_plugin_container').css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
		}

		$(document).on( "refynrev-ui-onoff_checkbox-switch", '.refyn_search_focus_enable', function( event, value, status ) {
			$('.refyn_search_focus_plugin_container').hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
			if ( status == 'true' ) {
				$(".refyn_search_focus_plugin_container").slideDown();
			} else {
				$(".refyn_search_focus_plugin_container").slideUp();
			}
		});

	});

})(jQuery);
</script>
    <?php
	}
}

add_action('init', 'refyn_search_options_form_init', 9999);

function refyn_search_options_form_init() {
	global $refyn_search_options;
	$refyn_search_options = new Refyn_Search_options();

	/**
	 * refyn_search_global_settings_form()
	 * Define the callback function to show subtab content
	 */

	function refyn_search_options_form() {
		global $refyn_search_options;
		$refyn_search_options->settings_form();
		
	}
}