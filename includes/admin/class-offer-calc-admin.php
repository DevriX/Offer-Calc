<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Offer Calc Admin Pages Class
 * 
 * Handles all admin functinalities
 *
 * @package Offer Calc 
 * @since 1.0.0
 */
class Offer_Calc_Admin_Pages{
	
	public $model, $scripts;
	
	function __construct(){		
		
		global $offer_calc_model, $offer_calc_scripts,$offer_calc_shortcodes;
		$this->model = $offer_calc_model;
		$this->scripts = $offer_calc_scripts;
		$this->ofr_shortcode = $offer_calc_shortcodes;
	}
	
	/**
	 * Add Top Level Menu Page
	 *
	 * Runs when the admin_menu hook fires and adds a new
	 * top level admin page and menu item
	 * 
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	function offer_calc_admin_menu() {
		
		if( get_option('ofc_version') < 1 ){
			
			//main menu page	 
			add_menu_page( __( 'Offer Calc','offercalc' ), __( 'Offer Calc','offercalc' ), 'manage_options', 'offer-calc-settings', array( $this, 'offer_calc_settings' ) );
							
		} else {
			if( get_option('ofc_migration_done') == false ) {
				// add setting page
				$settings_page = add_submenu_page( 'edit.php?post_type='.OFFER_CALC_POST_TYPE, __( 'Migrate Offer','offercalc' ), __( 'Migrate Offer','offercalc'), 'manage_options', 'offer-calc-settings', array( $this, 'offer_calc_settings' ) );
			}
			
			// help page
			add_submenu_page( 'edit.php?post_type='.OFFER_CALC_POST_TYPE, __( 'Offer Calc Help','offercalc' ), __( 'Offer Calc Help','offercalc'), 'manage_options', 'offer-calc-help', array( $this, 'offer_calc_help_page' ) );
		}
	}
		
	/**
	 * Custom column
	 *
	 * Handles the custom columns to offer listing page
	 * 
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	
	public function offer_calc_manage_custom_column_slug( $column_name,$post_id ) {
		
		global $wpdb,$post,$offer_calc_shortcodes;		
		$this->ofc_shortcode = $offer_calc_shortcodes;
		$prefix = OFFER_CALC_META_PREFIX;
		
		switch ($column_name) {
				
				case 'post_name' :
								$post_name = get_post_field('post_name', $post->ID, true );
								if( !empty( $post_name ) ) {
									echo $post_name;
								}								
								break;
				case 'shortcode' :
								$post_name = get_post_field('post_name', $post->ID, true );
								if( !empty( $post_name ) ) {
									$shortcode = sprintf( __( '[ofc_shortcode offer_slug="%s"]', 'offercalc' ), $post_name );
									echo apply_filters( 'offer_calc_copy_short_code' , $shortcode, $post_name);
									//echo sprintf( __( '[ofc_shortcode offer_slug="%s"]', 'offercalc' ), $post_name );									
								}								
								break;									
				case 'service_fields' :
								$service_fields = get_post_meta( $post->ID, $prefix . 'service_fields', true );
								
								if( !empty( $service_fields ) ) {																	
										echo count($service_fields);									
									}								
								break;					
			}
	}	
	
	/**
	 * Add New Column to offer calc listing page
	 *
	 * @package Offer calc
	 * @since 1.0.0
	 */
	function add_new_offer_slug_columns($new_columns) {
 		
		if(isset($new_columns['date'])){
			$post_date = $new_columns['date'];
			unset($new_columns['date']);
		}
 		
		$new_columns['post_name'] 		= __('Slug','offercalc');
		$new_columns['shortcode'] 		= __('Shortcode','offercalc');
		$new_columns['service_fields'] 	= __('Offer Services','offercalc');
		$new_columns['date']			= $post_date;
		return $new_columns;
	}
	
	
	/**
	 * Add New Column to offer calc listing page
	 *
	 * @package Offer calc
	 * @since 1.0.0
	 */
	function offer_calc_settings() {		
		include_once( OFFER_CALC_ADMIN . '/forms/offer-calc-settings.php' );
	}
	
	/**
	 * Add Help Page
	 *
	 * @package Offer calc
	 * @since 1.0.0
	 */
	function offer_calc_help_page() {		
		include_once( OFFER_CALC_ADMIN . '/offer-calc-help-page.php' );
	}
	
	/**
	 * On migrate button click
	 *
	 * @package Offer calc
	 * @since 1.0.0
	 */
	function offer_calc_migrate_submit(){
					
		if(isset($_POST['offer_calc_form_migrate'])){
			$this->model->offer_calc_migrate_data();			
			// get redirect url
			$redirect_url = add_query_arg( array( 'post_type' => 'offer-calc', 'page' => 'offer-calc-settings','message' => '11' ), admin_url( 'edit.php' ) );
			wp_redirect( $redirect_url );	
		}
	}
	
	/**
	 * On delete button click
	 *
	 * @package Offer calc
	 * @since 1.0.0
	 */
	function offer_calc_migrate_delete(){
		
		if(isset($_POST['offer_calc_form_delete'])){
			$this->model->offer_calc_delete_data();
			// get redirect url
			$redirect_url = add_query_arg( array( 'post_type' => 'offer-calc', 'message' => '11' ), admin_url( 'edit.php' ) );
			wp_redirect( $redirect_url );	
		}
	}
	
	
	/**
	 * Message for migration success
	 *
	 * @package Offer calc
	 * @since 1.0.0
	 */
	function offer_calc_notices() {
		
		//migration notice
		if(isset($_GET['message'], $_GET['page']) && $_GET['page'] == 'offer-calc-settings' && $_GET['message'] == '11'){
		    echo '<div class="updated">
		        <p>' . __( 'Data migration done successfully!', 'offercalc' ).'</p>
		    </div>';
		}
		
		//old data delete notice
		if( isset($_GET['message'], $_GET['post_type']) && $_GET['post_type'] == 'offer-calc' && $_GET['message'] == '11' && !isset($_GET['page']) ){
		    echo '<div class="updated">
		        <p>' . __( 'Offer tables deletes successfully!', 'offercalc' ).'</p>
		    </div>';
		}
		
		//old data delete notice
		if( get_option( 'ofc_pro_notice' ) == 1 ){		    
		    ?>
		    <div id="message" class="updated offercalc-message wc-connect">
				<p>
					<?php _e( 'Thanks for downloading the <strong>ultimate Offer Calc engine</strong>! Check out our <a href="'. OFFER_CALC_ADMIN_HELP_PAGE_URL .'" title="Offer Calc guide" target="_blank">guide</a> for first starters or sign up for our <a href="' . OFFER_CALC_PRO_SITE_URL . '" title="Offer Calc Pro" target="_blank">Offer Calc Pro</a> service!', 'offercalc' ); ?>
				</p>
				<p class="submit"><a class="skip button-secondary" href="<?php echo add_query_arg( 'hide_pro_notice', 'true', admin_url( 'edit.php?post_type=offer-calc' ) ); ?>"><?php _e( 'Dismiss', 'offercalc' ); ?></a></p>
			</div>
		<?php
		}
	}
	
	/**
	 * Add shortcode metabox at sidebar
	 *
	 * @package Offer calc
	 * @since 1.0.0*/
	
	public function offer_calc_side_metabox_shortcode() {
		 if ( isset($_GET['action']) && $_GET['action'] == 'edit' ){
		  	add_meta_box( 'offer_calc_sidemeta_shortcode', __( 'Offer Shortcode', 'offercalc' ), array( $this, 'offer_calc_metabox_shortcode_content' ), OFFER_CALC_POST_TYPE, 'side', 'core' );	
		 }	 
	}	
	
	/**
	 * Add Pro info metabox at sidebar
	 *
	 * @package Offer calc
	 * @since 1.0.0*/
	
	public function offer_calc_side_metabox_pro_info() {		 
		  add_meta_box( 'offer_calc_sidemeta_pro_info', __( 'Offer Calc Pro', 'offercalc' ), array( $this, 'offer_calc_metabox_pro_content' ), OFFER_CALC_POST_TYPE, 'side', 'core' );			 
	}
	
	/**
	 * Render shortcode metabox at sidebar
	 *
	 * @package Offer calc
	 * @since 1.0.0
	 */
	public function offer_calc_metabox_shortcode_content() {		
			global $post;		
			
			$shortcode = sprintf( __( '[ofc_shortcode offer_slug="%s"]', 'offercalc' ), $post->post_name );
		    echo '<p>'.apply_filters( 'offer_calc_copy_short_code' , $shortcode, $post->post_name).'</p>';
	}	
	
	/**
	 * Render pro metabox at sidebar
	 *
	 * @package Offer calc
	 * @since 1.0.0*/
	public function offer_calc_metabox_pro_content() {
		echo '<p>' . __( 'Thanks for downloading the <strong>ultimate Offer Calc engine</strong>! Check out our <a href="'. OFFER_CALC_ADMIN_HELP_PAGE_URL .'" title="Offer Calc guide" target="_blank">guide</a> for first starters or sign up for our <a href="' . OFFER_CALC_PRO_SITE_URL . '" title="Offer Calc Pro" target="_blank">Offer Calc Pro</a> service!', 'offercalc' ) . '</p>';
	}
	
	/**
	 * Dismiss notice
	 *
	 * @package Offer calc
	 * @since 1.0.0*/
	public function offer_calc_dismiss_notice() {
		if ( isset($_GET['hide_pro_notice']) && $_GET['hide_pro_notice'] == true ){
			delete_option( 'ofc_pro_notice' );
		}
	}
	
	/**
	 * Add dashboard widget
	 *
	 * @package Offer calc
	 * @since 1.0.0*/
	function offer_calc_dashboard_widget() {
	
		wp_add_dashboard_widget( __('offer_calc_dashboard_widget', 'offercalc'), __('Offer Calc', 'offercalc'),  array( $this, 'offer_calc_dashboard_widget_content' ));	
	}
	
	/**
	 * dashboard widget content
	 *
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	function offer_calc_dashboard_widget_content( ){

		$post_count = $this->model->offer_calc_dashboard_widget_count();		
		echo '<p>' . __( 'Thanks for downloading the <strong>ultimate Offer Calc engine</strong>! Check out our <a href="'. OFFER_CALC_ADMIN_HELP_PAGE_URL .'" title="Offer Calc guide" target="_blank">guide</a> for first starters or sign up for our <a href="' . OFFER_CALC_PRO_SITE_URL . '" title="Offer Calc Pro" target="_blank">Offer Calc Pro</a> service!', 'offercalc' ) . '</p>';
		echo '<h4>'.sprintf( __( 'Total Offer Calc: <b>%d</b>', 'offercalc' ), $post_count ).'</h4><br/>';
	}

	
	/**
	 * Show offer calc on post page
	 *
	 * @package Offer Calc
	 * @since 1.0.0
	 */  
	function offer_calc_view_post( $content ) {		
		
		global $post;			
				
		if($post->post_type == 'offer-calc' ){
			show_admin_bar( false );
			$attr['offer_slug'] = $post->post_name;		
			$content .= $this->ofr_shortcode->offer_calc_shortcode( $attr, $content='' );
		}
		return $content;
	}		
	
	/**
	 * Adding Hooks
	 *
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	function add_hooks() {				
		
		global $post;
		
		//add new admin menu page
		add_action( 'admin_menu', array( $this,'offer_calc_admin_menu' ) );
		
		add_action( 'admin_init', array( $this,'offer_calc_migrate_submit' ) );
		add_action( 'admin_init', array( $this,'offer_calc_migrate_delete' ) );
		add_action( 'admin_init', array( $this,'offer_calc_dismiss_notice' ) );					
				
		add_action( 'admin_notices', array($this,'offer_calc_notices') );		
		
		//add new field to offer listing page
		add_action( 'manage_'.OFFER_CALC_POST_TYPE.'_posts_custom_column', array($this,'offer_calc_manage_custom_column_slug'), 10, 2 );
		add_filter( 'manage_edit-'.OFFER_CALC_POST_TYPE.'_columns', array($this,'add_new_offer_slug_columns') );
		
		//add metabox to edit / add new offer page
 		add_action( 'add_meta_boxes', array( $this, 'offer_calc_side_metabox_shortcode') );
 		add_action( 'add_meta_boxes', array( $this, 'offer_calc_side_metabox_pro_info') );
 		
 		add_action( 'wp_dashboard_setup', array($this,'offer_calc_dashboard_widget') ); 	
 		
 		add_filter( 'the_content', array($this,'offer_calc_view_post'), 10 ,1); 
	}
}
?>