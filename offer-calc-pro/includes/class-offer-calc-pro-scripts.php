<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Script Class
 * 
 * Handles include style and script functinalities
 *
 * @package Offer Calc Pro 
 * @since 1.0.0
 */
class Offer_Calc_Pro_Scripts{

	function __construct(){
		
	}
	
	/**
	 * Css style for front end.
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_public_styles(  ) {
			
		// Registring and enquing admin side css
		wp_register_style( 'offercalc_pro_public_style', OFFER_CALC_PRO_URL . 'includes/css/offer-calc-pro-wp.css' );
		wp_enqueue_style( 'offercalc_pro_public_style' );	
	}
	
	/**
	 * Css style for front end.
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_admin_scripts( $hook_suffix ) {
		
		$pages_hook_suffix = array( 'post.php', 'edit.php', 'post-new.php', 'offer-calc_page_offer-calc-global-settings' );
	
		wp_enqueue_script( 'postbox' );
		
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {
			
			wp_enqueue_script( 'jquery' );
						
			// load custom copy to clipboard script				
						
			wp_register_script( 'offercalc_pro_admin_script_copy', OFFER_CALC_PRO_URL .'includes/js/jquery.zclip.js' , array('jquery'), '1.0', true );			
			wp_enqueue_script( 'offercalc_pro_admin_script_copy' );
			
			wp_register_script( 'offercalc_pro_admin_script', OFFER_CALC_PRO_URL .'includes/js/copy-to-clipboard.js' , array('jquery'), '1.0', true );
						
			$flash_file = OFFER_CALC_PRO_URL .'includes/js/ZeroClipboard.swf' ;	
			$param_array = array( 'flash_path' => $flash_file );
				
			wp_localize_script( 'offercalc_pro_admin_script', 'OfferPro', $param_array );			
			wp_enqueue_script( 'offercalc_pro_admin_script' );
						
		}
	}
	
	/**
	 * Loading Additional Java Script
	 *
	 * Loads the JavaScript required for toggling the meta boxes add coupon page.
	 *
	 * @package WP Settings & Widget Page
	 * @since 1.0.0
	 */

	 public function offer_calc_settings_scripts() { 

		echo '<script type="text/javascript">

				//<![CDATA[

				jQuery(document).ready( function($) {

					$(".if-js-closed").removeClass("if-js-closed").addClass("closed");
					
					postboxes.add_postbox_toggles( "admin_page_offer-calc-settings" );
					
				});

				//]]>

			</script>';

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
		
		// Registring and enquing admin side css
		wp_register_style( 'offercalc_pro_admin_style', OFFER_CALC_PRO_URL . 'includes/css/offer-calc-pro-admin.css' );
		wp_enqueue_style( 'offercalc_pro_admin_style' );	
	}
	
	/**
	 * Enqueue Scripts
	 * 
	 * Loads custom copy to clipboard script
	 *
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	public function offer_calc_pro_public_scripts(  ) {		
		
		wp_enqueue_script( 'jquery' );			
		
		wp_register_script( 'jquery-validation-plugin', OFFER_CALC_PRO_URL .'includes/js/jquery.validate.js', array('jquery') );
		wp_enqueue_script( 'jquery-validation-plugin' );
	
		wp_register_script( 'frontend-jquery', OFFER_CALC_PRO_URL .'includes/js/offer-calc-pro-wp.js', array('jquery-validation-plugin') );
		wp_enqueue_script( 'frontend-jquery' );
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */	
	public function add_hooks() {
		
		add_action( 'wp_enqueue_scripts', array( $this, 'offer_calc_pro_public_styles' ) );
		
		// Action to add js at frontend side
		add_action( 'wp_enqueue_scripts', array( $this, 'offer_calc_pro_public_scripts' ) );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'offer_calc_pro_admin_scripts' ) );
		
		// Action to add css at admin side
		add_action( 'admin_enqueue_scripts', array( $this, 'offer_calc_pro_admin_styles' ) );
	}
}