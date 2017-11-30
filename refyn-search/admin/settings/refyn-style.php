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

class Refyn_Search_Style extends Refyn_Search_Admin_UI
{

	/**
	 * @var string
	 */
	private $parent_tab = 'style';

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
	public $form_key = 'refyn_search_style';

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
				'success_message'	=> __( 'Options successfully saved.', 'refyn' ),
				'error_message'		=> __( 'Error: Options can not save.', 'refyn' ),
				'reset_message'		=> __( 'Options successfully reseted.', 'refyn' ),
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
			'name'				=> 'style',
			'label'				=> __( 'Style', 'refyn' ),
			'callback_function'	=> 'refyn_search_style_form',
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

			  		// Define settings
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
			array(
            	'name' 		=> __( 'CSS OPTIONS', 'refyn' ),
                'type' 		=> 'heading',
				'id'		=> 'refyn_search_background_text',
           	),
			array(
            	'name' 		=> __( 'List background color', 'refyn' ),
                'type' 		=> 'color',
				'id'		=> 'refyn_search_list_background_color',
				'default'   => '#808080',
				'free_version'		=> true,
           	),
			
			array(
            	'name' 		=> __( 'List border color', 'refyn' ),
                'type' 		=> 'color',
				'id'		=> 'refyn_search_list_border_color',
				'default'   => '#cccccc',
				'free_version'		=> true,
           	),
			array(
            	'name' 		=> __( 'Alternate row first color', 'refyn' ),
                'type' 		=> 'color',
				'id'		=> 'refyn_search_first_color',
				'default'   => '#ffffff',
				'free_version'		=> true,
           	),
			
			array(
            	'name' 		=> __( 'Alternate row second color', 'refyn' ),
                'type' 		=> 'color',
				'id'		=> 'refyn_search_second_color',
				'default'   => '#ffffff',
				'free_version'		=> true,
           	),
			
			array(
            	'name' 		=> __( 'Text color', 'refyn' ),
                'type' 		=> 'color',
				'id'		=> 'refyn_search_text_color',
				'default'   => '#050b16',
				'free_version'		=> true,
           	),
			
			array(
            	'name' 		=> __( 'Background hover color', 'refyn' ),
                'type' 		=> 'color',
				'id'		=> 'refyn_search_background_hover_color',
				'default'   => '#727272',
				'free_version'		=> true,
           	),
			array(
            	'name' 		=> __( 'Text hover color', 'refyn' ),
                'type' 		=> 'color',
				'id'		=> 'refyn_search_text_hover_color',
				'default'   => '#ffffff',
				'free_version'		=> true,
           	),
			array(
            	'name' 		=> __( 'Search box pop-up width', 'refyn' ),
                'type' 		=> 'text',
				'id'		=> 'refyn_search_searchbox_popup_width',
				'default'   => '320',
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

global $refyn_search_style;
$refyn_search_style = new Refyn_Search_Style();

/**
 * refyn_search_global_settings_form()
 * Define the callback function to show subtab content
 */
function refyn_search_style_form() {
	global $refyn_search_style;
	$refyn_search_style->settings_form();
}
