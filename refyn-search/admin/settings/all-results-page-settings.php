<?php
/*
Copyright 2016 REFYN 
This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
/*-----------------------------------------------------------------------------------
Refyn Search All Results Page Settings
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
class REFYN_All_Results_Page_Settings extends Refyn_Search_Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'all-results-page';
	
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
	public $form_key = 'refyn_all_results_pages_settings';
	
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
				'success_message'	=> __( 'All Results Pages successfully saved.', 'refyn' ),
				'error_message'		=> __( 'Error: All Results Pages can not save.', 'refyn' ),
				'reset_message'		=> __( 'All Results Pages successfully reseted.', 'refyn' ),
			);
		
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'reset_default_settings' ) );
		
		//add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
		
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
			'name'				=> 'all-results-page',
			'label'				=> __( 'All Results Pages', 'refyn' ),
			'callback_function'	=> 'refyn_all_results_page_settings_form',
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
            	'name' 		=> __( 'Search results page settings', 'refyn' ),
                'type' 		=> 'heading',
				'class'		=> 'pro_feature_fields',
           	),
			array(  
				'name' 		=> __( 'Results', 'refyn' ),
				'desc' 		=> __('The number of results to show before endless scroll click to see more results.', 'refyn'),
				'id' 		=> 'refyn_search_result_items',
				'default'	=> 10,
				'type' 		=> 'slider',
				'min'	=> '0',
				'max'	=> '20',
				'free_version'		=> true,
			),
			array(  
				'name' 		=> __( 'Description character count', 'refyn' ),
				'desc' 		=> __('The number of characters from product descriptions that shows with each search result.', 'refyn'),
				'id' 		=> 'refyn_search_text_lenght',
				'default'	=> 100,
				'type' 		=> 'slider',
				'min'	=> '100',
				'max'	=> '1000',
				'free_version'		=> true,
			),
			array(  
				'name' 		=> __( 'SKU', 'refyn' ),
				'desc' 		=> __('ON to show product SKU with search results', 'refyn'),
				'id' 		=> 'refyn_search_sku_enable',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			array(  
				'name' 		=> __( 'Price', 'refyn' ),
				'desc' 		=> __('ON to show product price with search results', 'refyn'),
				'id' 		=> 'refyn_search_price_enable',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			array(  
				'name' 		=> __( 'Add to cart', 'refyn' ),
				'desc' 		=> __('On to show Add to cart button with search results', 'refyn'),
				'id' 		=> 'refyn_search_addtocart_enable',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			array(  
				'name' 		=> __( 'Product Categories', 'refyn' ),
				'desc' 		=> __('On to show categories with search results', 'refyn'),
				'id' 		=> 'refyn_search_categories_enable',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
			array(  
				'name' 		=> __( 'Product Tags', 'refyn' ),
				'desc' 		=> __('On to show tags with search results', 'refyn'),
				'id' 		=> 'refyn_search_tags_enable',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
				'free_version'		=> true,
			),
		
        ));
	}
	
}
global $refyn_all_results_page_settings;
$refyn_all_results_page_settings = new REFYN_All_Results_Page_Settings();
/** 
 * refyn_all_results_page_settings_form()
 * Define the callback function to show subtab content
 */
function refyn_all_results_page_settings_form() {
	global $refyn_all_results_page_settings;
	$refyn_all_results_page_settings->settings_form();
}
