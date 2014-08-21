<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( is_admin() ) {
	
	$prefix = OFFER_CALC_META_PREFIX; 
	
	/* 
	 * configure your meta box
	 */
	$offer_calc_meta_config = array(
		'id' 			=> 'offer_calc_meta',						// meta box id, unique per meta box
		'title' 		=> __( 'Offer Calc Option', 'offercalc'),	// meta box title
		'pages' 		=> array( OFFER_CALC_POST_TYPE ),			// add meta box for post and pages. if you would like to add it for custom post type(s) make it 'all'.																			 
		'context' 		=> 'normal',								// where the meta box appear: normal (default), advanced, side; optional
		'priority' 		=> 'high',									// order of meta box: high (default), low; optional
		'fields' 		=> array(),									// list of meta fields (can be added by field arrays)
		'local_images' 	=> false,									// Use local or hosted images (meta box images for add/remove)
	);
	
	$ofc_meta =  new Offer_Calc_Meta_Box( $offer_calc_meta_config );
	
	$ofc_meta = apply_filters( 'offer_calc_add_meta_options_top',  $ofc_meta );
	
	// service	
	$service_fields[] = $ofc_meta->addText( 'name', array( 'validate_func' => 'escape_html', 'name'=> __( 'Service Name', 'offercalc' ), ), true );
	$service_fields[] = $ofc_meta->addText( 'price', array( 'validate_func' => 'escape_html', 'name'=> __( 'Service Price', 'offercalc' ), ), true );
	
	//Apply new fields/option in offer meta. 	
	
	$service_fields = apply_filters( 'offer_calc_add_image',  $service_fields, $ofc_meta );
		
	$sortable = apply_filters( 'offer_calc_sortable_services', false );
	
	$ofc_meta->addRepeaterBlock( $prefix. 'service_fields', array( 'validate_func' => 'escape_html', 'inline' => false, 'name' => __( 'Add Service', 'offercalc' ), 'fields' => $service_fields, 'sortable'=> $sortable ) );
	
	$ofc_meta = apply_filters( 'offer_calc_add_meta_options',  $ofc_meta );	
	
	//Finish Meta Box Decleration
	$ofc_meta->Finish();
}
?>