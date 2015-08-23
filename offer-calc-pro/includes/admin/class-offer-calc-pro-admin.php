<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 * 
 * Handles all admin functinalities
 *
 * @package Offer Calc Pro 
 * @since 1.0.0
 */
class Offer_Calc_Pro_Admin_Pages{
	
	public $model, $scripts;
	
	function __construct(){
		
		global $offer_calc_model, $offer_calc_pro_scripts;
		
		$this->model	= $offer_calc_model;
		$this->scripts	= $offer_calc_pro_scripts;
	}
	
	/**
	 * Offer service shortable
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_add_shortable_meta_fields( $state ) {
		
		return apply_filters( 'offer_calc_pro_add_shortable_meta_fields', true );
	}	
	
	/**
	 * Register Shortcode Button
	 *
	 * Add Shortcode button for the editor
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_shortcode_editor_register_button( $buttons ) {	
		
		global $post;
		
		//if( $post->post_type != 'offer-calc' ){
	 		array_push( $buttons, "|", "offercalcshortcodes" );
		//}
	 	return $buttons;	 	
	}	
		
	/**
	 * Editor Pop Up Script
	 *
	 * Adding the needed script for the add shortcode pop up on the editor
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_shortcode_editor_button_script( $plugin_array ) {
	
		wp_enqueue_script( 'tinymce' );
		
	   $plugin_array['offercalcshortcodes'] = OFFER_CALC_PRO_URL . 'includes/js/offer-calc-pro-shortcode.js';	 
	   return $plugin_array;   
	   
	}
	
	/**
	 * Editor Pop Up Styles at admin side
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_admin_styles( $hook_suffix ) {		
		
		$pages_hook_suffix = array( 'post.php', 'post-new.php' );		
		
		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {
			
			// Registring and enquing admin side css
			wp_register_style( 'offercalc_pro_admin_style', OFFER_CALC_PRO_URL . 'includes/css/offer-calc-shortcode-popup.css' );
			wp_enqueue_style( 'offercalc_pro_admin_style' );
		}
		
		
		if( in_array( $hook_suffix, array( 'post.php', 'edit.php', 'post-new.php' ) ) ) {
			
			// Registring and enquing admin side css
			wp_register_style( 'offercalc_pro_admin_style', OFFER_CALC_PRO_URL . 'includes/css/copy-to-clipboard.css' );
			wp_enqueue_style( 'offercalc_pro_admin_style' );
		}
	}
	/**
	 * Shortcode Button
	 *
	 * Adds the shortcode button above the WordPress editor.
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */	
	
	public function offer_calc_pro_shortcode_button() {		
		
		global $post;
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
			return;
		}
	 
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', array( $this, 'offer_calc_pro_shortcode_editor_button_script' ) );
			add_filter( 'mce_buttons', array( $this, 'offer_calc_pro_shortcode_editor_register_button' ) );	     
		}
	}
	
	/**
	 * Pop Up On Editor
	 *
	 * Includes the pop up on the WordPress editor
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_shortcode_popup_markup() {
		global $wpdb;				
		include_once( OFFER_CALC_PRO_ADMIN . '/forms/offer-calc-pro-popup.php' );
	}
	
	/**
	 * Additional meta fields
	 *
	 * Include currency and email metafield
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0	
	 */
	public function offer_calc_pro_meta_options( $ofc_meta){
		
		$prefix = OFFER_CALC_META_PREFIX; 
			
		// Enable Email Offer
		$ofc_meta->addCheckbox( $prefix. 'enable_email_offer', array( 'validate_func' => 'escape_html', 'name'=> __( 'Enable Email Offer', 'offercalc' ), 'desc' => __( 'Enable/Disable Email Offer', 'offercalc' )) )	;
		
		// Email
		$ofc_meta->addText( $prefix. 'email', array( 'validate_func' => 'escape_html', 'name'=> __( 'Email Address', 'offercalc' ), 'desc' => __( 'Enter email address', 'offercalc' )) );	
		
		// Minumum Submission offer value
		$ofc_meta->addText( $prefix. 'submission_offer_value', array( 'validate_func' => 'escape_html', 'name'=> __( 'Minimum Offer Submission Value', 'offercalc' ), 'desc' => __( 'Enter Minimum offer submission value to submit the offer.', 'offercalc' ), 'class' => 'check_offer_value' ) );	
				
		return $ofc_meta;	
	}
	
	/**
	 * Custom field title in calc
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0	
	 */
	public function offer_calc_pro_editable_header( $ofc_meta ){
		
		$prefix = OFFER_CALC_META_PREFIX;
		
		$ofc_meta->addCheckbox( $prefix. 'edit_header', array( 'validate_func' => 'escape_html', 'name'=> __( 'Edit Header Labels', 'offercalc' ), 'desc' => __( 'Edit calc header', 'offercalc' ), 'class' => 'show-edit-header') )	;		
		// Image
		$ofc_meta->addText( $prefix. 'image_title', array( 'validate_func' => 'escape_html', 'name'=> __( 'Image Title', 'offercalc' ), 'desc' => __( 'Image field header text', 'offercalc' )) );
		//service	
		$ofc_meta->addText( $prefix. 'service_title', array( 'validate_func' => 'escape_html', 'name'=> __( 'Service Title', 'offercalc' ), 'desc' => __( 'Service field header text', 'offercalc' )) );	
		//Price
		$ofc_meta->addText( $prefix. 'price_title', array( 'validate_func' => 'escape_html', 'name'=> __( 'Price Title', 'offercalc' ), 'desc' => __( 'Price field header text', 'offercalc' )) );	
		//Count
		$ofc_meta->addText( $prefix. 'count_title', array( 'validate_func' => 'escape_html', 'name'=> __( 'Count Title', 'offercalc' ), 'desc' => __( 'Count field header text', 'offercalc' )) );	
		//Total
		$ofc_meta->addText( $prefix. 'total_title', array( 'validate_func' => 'escape_html', 'name'=> __( 'Total Title', 'offercalc' ), 'desc' => __( 'Total field header text', 'offercalc' )) );	
		return $ofc_meta;
	}

	/**
	 * Custom field image in calc
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0	
	 */
	public function offer_calc_pro_editable_image( $ofc_meta ){
		
		$prefix = OFFER_CALC_META_PREFIX;
		
		$ofc_meta->addCheckbox( $prefix. 'edit_image', array( 'validate_func' => 'escape_html', 'name'=> __( 'Custom Service Images ?', 'offercalc' ), 'desc' => __( 'Enable service image', 'offercalc' ), 'class' => 'show-edit-image') );
			
		return $ofc_meta;
	}
	
	/**
	 * Show image in calc
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0	
	 */
	public function offer_calc_pro_add_image( $service_fields, $ofc_meta ){				
						
		// Add Image
		$service_fields[] = $ofc_meta->addImage('offer_image', array( 'name'=> __( 'Image', 'offercalc' ), 'desc' => __( 'Here you can upload a image which will be used to this page.', 'offercalc' ), 'class' => 'edit_image_upload' ), true );
		
		// Add replace dropdown checkbox
		$service_fields[] = $ofc_meta->addCheckbox('replace_count_dropdown', array( 'name'=> __( 'Replace service dropdown?:', 'offercalc' ), 'desc' => __( 'Replace service count dropdown with textbox.', 'offercalc' ), 'class' => 'replace_dropdown_check'), true );	
		
		//Add dropdown limit
		//$options = array('from'=>'From:', 'to'=>'To:');
		//$service_fields[] = $ofc_meta->addMultipleTextbox( 'dropdown_limit', $options, array( 'validate_func' => 'escape_html', 'name'=> __( 'Set Dropdown Limit', 'offercalc' ), ), true ) ;
	
		$replace_options = array( '1'=>'Radio', '2'=>'Checkbox', '3'=>'Input field', '4'=>'Textarea', '5'=>'Select' , '6'=>'Multi Select' ,'7'=>'Number Field');
		$service_fields[] = $ofc_meta->addRadio( 'count_field_type', $replace_options, array( 'name'=> __( 'Select Count Type', 'offercalc' ) ), true );
		
		$service_fields[] = $ofc_meta->addCustomRepeater( 'count_field_type_opt', $replace_options, array( 'inline' => true, 'name'=> __( 'Display Options', 'offercalc' ) ), true );
		
		return $service_fields;
	}
	
	/**
	 * Editor Pop Up Styles at admin side
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_copy_short_code( $content , $post_name ) {		

		//$content = '< input type="text" value="'.$content.'" class="shotrtcode_text" />"';		
		$content = '<p id="p_'.$post_name.'">'.$content.'</p><a class="skip button-secondary copy" href="javascript:void(0);" id="'.$post_name.'" >Copy to clipboard</a>';
		return  $content;
		
	}
	
	/**
	 * Add Autoresponder metabox
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_meta_autoresponder() {
		
		add_meta_box( 'offer_calc_pro_autoresponder', __( 'Autoresponder Settings', 'offercalc' ), array( $this, 'offer_calc_pro_metabox' ), OFFER_CALC_PRO_POST_TYPE, 'normal', 'default' );
	}
	
	public function offer_calc_pro_metabox () {
		
		include_once( OFFER_CALC_PRO_ADMIN . '/offer-calc-pro-metabox.php' );
	}
	
	/**	 
	 * Save Custom Meta
	 * 
	 * Handles to save custom meta
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_save_meta( $post_id ) {	
		
		//$this->model->offer_calc_escape_slashes_deep()
		
		global $post_type;				
		
		$post_type_object = get_post_type_object( $post_type );
		
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                // Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] ) )       // Check Revision		
		{
			return $post_id;
		}
		
		if (isset($post_type) && $post_type == OFFER_CALC_PRO_POST_TYPE) {	
						
			// Update Radio button value
			$offer_calc_meta_value = isset( $_POST[ '_offer_calc_autoresponder' ] ) ? $_POST[ '_offer_calc_autoresponder' ] : '';
			update_post_meta( $post_id, '_offer_calc_autoresponder', $offer_calc_meta_value );
			
			if(isset($offer_calc_meta_value) && !empty($offer_calc_meta_value) && $offer_calc_meta_value == 'aweber'){
				
				$offercalc_aw_list_meta = isset( $_POST['_offercalc_aw_auth_code'] ) ? trim($this->model->offer_calc_escape_slashes_deep($_POST['_offercalc_aw_auth_code'])) : '';
				$aweber_list_data_meta = isset( $_POST['_offercalc_aw_list'] ) ? $_POST['_offercalc_aw_list'] : '';
					
				update_post_meta( $post_id, '_offercalc_aw_auth_code', $offercalc_aw_list_meta );
				update_post_meta( $post_id, '_offercalc_aw_list', $aweber_list_data_meta );
			}
						
			if(isset($offer_calc_meta_value) && !empty($offer_calc_meta_value) && $offer_calc_meta_value == 'gr'){
				
				$offercalc_gr_list_meta = isset( $_POST['_offercalc_gr_api'] ) ? trim($this->model->offer_calc_escape_slashes_deep($_POST['_offercalc_gr_api'])) : '';
				$gr_list_data_meta = isset( $_POST['_offercalc_gr_list'] ) ? $_POST['_offercalc_gr_list'] : '';
					
				update_post_meta( $post_id, '_offercalc_gr_api', $offercalc_gr_list_meta );
				update_post_meta( $post_id, '_offercalc_gr_list', $gr_list_data_meta );
			}
			
			if(isset($offer_calc_meta_value) && !empty($offer_calc_meta_value) && $offer_calc_meta_value == 'mc'){
				
				$offercalc_mc_list_meta = isset( $_POST['_offercalc_mc_api'] ) ? trim($this->model->offer_calc_escape_slashes_deep($_POST['_offercalc_mc_api'])) : '';
				$mc_list_data_meta = isset( $_POST['_offercalc_mc_list'] ) ? $_POST['_offercalc_mc_list'] : '';
					
				update_post_meta( $post_id, '_offercalc_mc_api', $offercalc_mc_list_meta );
				update_post_meta( $post_id, '_offercalc_mc_list', $mc_list_data_meta );
			}
			
		}	
	}
	
	/**
	 * Function for autoresponder initialize
	 * 
	 * Note: This `offer_calc_pro_metabox_option` filter should be in admin init to work it proper
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	function offer_calc_pro_admin_initialize() {
		
		$all_autoresponders = array();
		$all_autoresponders = apply_filters( 'offer_calc_pro_metabox_option', $all_autoresponders );
		
		if (!empty($all_autoresponders)) {						
			
			// Action to add meta box
			add_action( 'add_meta_boxes', array( $this, 'offer_calc_pro_meta_autoresponder') );	
		}
	}
	
	/**
	 * Add Global setting sub-menu
	 *
	 * Runs when the admin_menu hook fires and adds a new
	 * top level admin page and menu item
	 * 
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_global_setting_page() {
		
		$settings_page = add_submenu_page( 'edit.php?post_type='.OFFER_CALC_POST_TYPE, __( 'Email Global Settings','offercalc' ), __( 'Settings','offercalc' ), 'manage_options', 'offer-calc-global-settings', array( $this, 'offer_calc_global_settings' ) ); // add setting page
		
		add_action( "admin_head-$settings_page", array( $this->scripts, 'offer_calc_settings_scripts' ) );
	}	
	
	/**
	 * Admin Email Global Setting page
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */	
	public function offer_calc_global_settings() {		
						
		include_once( OFFER_CALC_PRO_ADMIN . '/forms/offer-calc-global-setting.php');	
	}
	
	/**	 
	 * Save setting
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_save_global_setting() {		
		
		register_setting( 'offer_calc_global_options', 'offer_calc_options', array($this, 'offer_calc_validate_options') );
	}
	
	public function offer_calc_validate_options( $input ) {
		
		$input['global_enable_offer']	= !empty($input['global_enable_offer']) ? $this->model->offer_calc_escape_slashes_deep( trim( $input['global_enable_offer'] ) ) : '';
		$input['global_email_addr']		= $this->model->offer_calc_escape_slashes_deep( trim( $input['global_email_addr'] ) );
		$input['global_offer_value']	= $this->model->offer_calc_escape_slashes_deep( trim( $input['global_offer_value'] ) );
		
		$input['admin_email_addr']		= $this->model->offer_calc_escape_slashes_deep( trim( $input['admin_email_addr'] ) );
		//$input['sender_name']			= $input['sender_name'];
		$input['admin_email_subject']	= $this->model->offer_calc_escape_slashes_deep( trim( $input['admin_email_subject'] ) );
		$input['admin_email_body']		= $this->model->offer_calc_escape_slashes_deep( trim( $input['admin_email_body'] ) );
		$input['user_email_subject']	= $this->model->offer_calc_escape_slashes_deep( trim( $input['user_email_subject'] ) );
		$input['user_email_body']		= $this->model->offer_calc_escape_slashes_deep( trim( $input['user_email_body'] ) );
		
		$input=apply_filters('offer_calc_validate_option',$input);
		
		return $input;
	}
	
	
		
	/**
	 * Adding Hooks
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */	
	public function add_hooks() {
		
		// For Email Global setting page
		add_action( 'admin_menu', array( $this, 'offer_calc_global_setting_page' ) );	
		add_action( 'admin_init', array( $this, 'offer_calc_save_global_setting' ) );
		
		// shortcode button
		add_action( 'init', array( $this, 'offer_calc_pro_shortcode_button' ) );
				
		// shortcode mark up for popup		
		add_action( 'admin_footer-post.php', array( $this,'offer_calc_pro_shortcode_popup_markup' ) );
		add_action( 'admin_footer-post-new.php', array( $this,'offer_calc_pro_shortcode_popup_markup' ) );
		
		//custom action / filter 		
		add_filter( 'offer_calc_sortable_services', array( $this, 'offer_calc_pro_add_shortable_meta_fields' ), 10, 2 );
		add_filter( 'offer_calc_add_meta_options', array( $this, 'offer_calc_pro_meta_options' ), 10, 1 );
		add_filter( 'offer_calc_add_image', array( $this, 'offer_calc_pro_add_image' ), 10, 2 );	
		add_filter( 'offer_calc_add_meta_options_top', array( $this, 'offer_calc_pro_editable_header' ), 10, 1 );
		add_filter( 'offer_calc_add_meta_options_top', array( $this, 'offer_calc_pro_editable_image' ), 10, 2 );
		add_filter( 'offer_calc_copy_short_code', array( $this, 'offer_calc_pro_copy_short_code' ), 10, 2 );
		
		// add action to save custom meta
		add_action( 'save_post', array( $this, 'offer_calc_pro_save_meta' ) );
		
		// Add action for autoresponder initialize
		add_action( 'admin_init', array( $this, 'offer_calc_pro_admin_initialize' ) );
	}
}
?>