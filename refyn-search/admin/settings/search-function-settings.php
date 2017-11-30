<?php
/* 
Copyright 2016 REFYN
This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
/*-----------------------------------------------------------------------------------
Refyn Search Exclude Content Settings
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
class REFYN_Search_Function_Settings extends Refyn_Search_Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'search-function';
	
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
	public $form_key = 'refyn_search_function_settings';
	
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
				'success_message'	=> __( 'Search Function Settings successfully saved.', 'refyn' ),
				'error_message'		=> __( 'Error: Search Function Settings can not save.', 'refyn' ),
				'reset_message'		=> __( 'Search Function Settings successfully reseted.', 'refyn' ),
			);
			
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'reset_default_settings' ) );
		
		add_action( $this->plugin_name . '_settings_' . 'refyn_search_code' . '_start', array( $this, 'refyn_search_code_start' ) );
		
		//add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
		
		add_action( $this->plugin_name . '-'. $this->form_key.'_settings_start', array( $this, 'pro_fields_before' ) );
		add_action( $this->plugin_name . '-'. $this->form_key.'_settings_end', array( $this, 'pro_fields_after' ) );
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
			'name'				=> 'search-function',
			'label'				=> __( 'Search Function', 'refyn' ),
			'callback_function'	=> 'refyn_search_function_settings_form',
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
            	'name' 		=> __( 'Refyn Search Function', 'refyn' ),
                'type' 		=> 'heading',
          		'id' 		=> 'refyn_search_code'
           	),
			
			array(
            	'name' 		=> __( 'Customize Search Function values :', 'refyn' ),
				'desc'		=> __("The values you set here will be shown when you add the global search function to your header.php file. After adding the global function to your header.php file you can change the values here and 'Update' and they will be auto updated in the function.", "refyn"),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Product name', 'refyn' ),
				'desc' 		=> __('Number of Product Name to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'refyn'),
				'id' 		=> 'refyn_search_product_items',
				'type' 		=> 'text',
				'css' 		=> 'width:40px;',
			),
			array(  
				'name' 		=> __( 'Product SKU', 'refyn' ),
				'desc' 		=> __('Number of Product SKU to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'refyn'),
				'id' 		=> 'refyn_search_p_sku_items',
				'type' 		=> 'text',
				'css' 		=> 'width:40px;',
			),
			array(  
				'name' 		=> __( 'Product category', 'refyn' ),
				'desc' 		=> __('Number of Product Categories to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'refyn'),
				'id' 		=> 'refyn_search_p_cat_items',
				'type' 		=> 'text',
				'css' 		=> 'width:40px;',
			),
			array(  
				'name' 		=> __( 'Product tag', 'refyn' ),
				'desc' 		=> __('Number of Product Tags to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'refyn'),
				'id' 		=> 'refyn_search_p_tag_items',
				'type' 		=> 'text',
				'css' 		=> 'width:40px;',
			),
			array(  
				'name' 		=> __( 'Post', 'refyn' ),
				'desc' 		=> __('Number of Posts to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'refyn'),
				'id' 		=> 'refyn_search_post_items',
				'type' 		=> 'text',
				'css' 		=> 'width:40px;',
			),
			array(  
				'name' 		=> __( 'Page', 'refyn' ),
				'desc' 		=> __('Number of Pages to show in search field drop-down. Leave &lt;empty&gt; for not activated', 'refyn'),
				'id' 		=> 'refyn_search_page_items',
				'type' 		=> 'text',
				'css' 		=> 'width:40px;',
			),
			array(  
				'name' 		=> __( 'Price', 'refyn' ),
				'desc' 		=> __('On to show Product prices', 'refyn'),
				'id' 		=> 'refyn_search_show_price',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
			),
			array(  
				'name' 		=> __( 'Description Characters', 'refyn' ),
				'desc' 		=> __('Number of characters from product description to show in search field drop-down. Default value is "100".', 'refyn'),
				'id' 		=> 'refyn_search_character_max',
				'type' 		=> 'text',
				'css' 		=> 'width:40px;',
			),
			array(  
				'name' 		=> __( 'Width', 'refyn' ),
				'desc' 		=> 'px. '.__('Leave &lt;empty&gt; for 100% wide', 'refyn'),
				'id' 		=> 'refyn_search_width',
				'type' 		=> 'text',
				'css' 		=> 'width:40px;',
			),
			array(  
				'name' 		=> __( 'Padding', 'refyn' ),
				'id' 		=> 'refyn_search_padding',
				'type' 		=> 'array_textfields',
				'ids'		=> array( 
	 								array(  'id' 		=> 'refyn_search_padding_top',
	 										'name' 		=> __( 'Top', 'refyn' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 5 ),
	 
	 								array(  'id' 		=> 'refyn_search_padding_bottom',
	 										'name' 		=> __( 'Bottom', 'refyn' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 5 ),
											
									array(  'id' 		=> 'refyn_search_padding_left',
	 										'name' 		=> __( 'Left', 'refyn' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 5 ),
											
									array(  'id' 		=> 'refyn_search_padding_right',
	 										'name' 		=> __( 'Right', 'refyn' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 5 ),
	 							)
			),
			array(  
				'name' 		=> __( 'Custom style', 'refyn' ),
				'desc' 		=> __('Put other custom style for the Refyn search box', 'refyn'),
				'id' 		=> 'refyn_search_custom_style',
				'type' 		=> 'text',
			),
			array(  
				'name' 		=> __( 'Global search', 'refyn' ),
				'desc' 		=> __('On to set global search or search in current product category or current product tag. "Checked" to activate global search.', 'refyn'),
				'id' 		=> 'refyn_search_global_search',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'refyn' ),
				'unchecked_label' 	=> __( 'OFF', 'refyn' ),
			),
		
        ));
	}
	
	public function refyn_search_code_start() {
		echo '<tr valign="top"><td class="forminp" colspan="2">';
		?>
        <?php _e('Copy and paste this global function into your themes header.php file to replace any existing search function. (Be sure to delete the existing WordPress, Refyn or Theme search function)', 'refyn');?>
            <br /><code>&lt;?php<br />
            $ps_echo = true ; <br /> 
            if ( function_exists( 'woo_refyn_search_widget' ) ) woo_refyn_search_widget( $ps_echo ); <br /> 
            ?&gt;</code>
		<?php echo '</td></tr>';
	}
	
}
global $refyn_search_function_settings;
$refyn_search_function_settings = new REFYN_Search_Function_Settings();
/** 
 * refyn_search_function_settings_form()
 * Define the callback function to show subtab content
 */
function refyn_search_function_settings_form() {
	global $refyn_search_function_settings;
	$refyn_search_function_settings->settings_form();
}
