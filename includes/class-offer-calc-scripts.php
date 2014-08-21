<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Offer Calc Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package Offer Calc
 * @since 1.0.0
 */
class Offer_Calc_Scripts {
	
	function __construct() {}
		
	/**
	 * Enqueue Scripts
	 * 
	 * Loads Javascript file for managing functionality in forntend
	 *
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	function offer_calc_admin_scripts( $hook_suffix ) {
		
		global $wp_version;
		
		$pages_hook_suffix = array( 'post-new.php','post.php' );
	
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {
			wp_enqueue_script( 'jquery' );
			wp_register_script( 'offercalc_admin_script', OFFER_CALC_URL . 'includes/js/offer-calc-admin.js' , array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'offercalc_admin_script' );		
		}	
	}

	function offer_calc_public_scripts() {
		
		global $wp_version;
			
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'offercalc_public_script', OFFER_CALC_URL . 'includes/js/offercalc.js' , array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'offercalc_public_script' );		
	}
	
	/**
	 * Enqueue Styles
	 * 
	 * Loads CSS file for functionality in frontend
	 *
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	function offer_calc_public_styles() {
		wp_register_style( 'offercalc_public_style',  OFFER_CALC_URL . 'includes/css/offercalc.css' );
		wp_enqueue_style( 'offercalc_public_style' );
	}
	
	/**
	 * Enqueue Styles in admin
	 * 
	 * Loads CSS file for functionality in backend
	 *
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	function offer_calc_admin_styles( $hook_suffix ) {
		wp_register_style( 'offercalc_admin_style',  OFFER_CALC_URL .'includes/css/offer-calc-admin.css' );
		wp_enqueue_style( 'offercalc_admin_style' );
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding hooks for the styles and scripts.
	 *
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//add style for setting & widget
		add_action( 'wp_enqueue_scripts', array( $this, 'offer_calc_public_styles' ) );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'offer_calc_admin_styles' ) );
		
		add_action( 'wp_enqueue_scripts', array( $this, 'offer_calc_public_scripts' ) );
		
		//add js for setting & widget
		add_action( 'admin_enqueue_scripts', array( $this, 'offer_calc_admin_scripts' ) );		
		
	}
}
?>