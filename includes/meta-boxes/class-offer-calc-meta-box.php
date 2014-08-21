<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Custom Meta Box Class
 *
 * @package Offer Calc
 * @since 1.0.0
 */

require_once ( OFFER_CALC_METABOX_DIR . '/meta-box-validate-class.php' );
if ( ! class_exists( 'Offer_Calc_Meta_Box' ) ) :

/**
 * All Types Meta Box class.
 *
 * @package Offer Calc
 * @since 1.0.0
 */
class Offer_Calc_Meta_Box {
  
	/**
	 * Holds meta box object
	 *
	 * @var object
	 * @access protected
	 */
	protected $_meta_box;
  
	/**
	 * Holds meta box fields.
	 *
	 * @var array
	 * @access protected
	 */
	protected $_prefix;
  
	/**
	 * Holds Prefix for meta box fields.
	 *
	 * @var array
	 * @access protected
	 */
	protected $_fields;
  
	/**
	 * Use local images.
	 *
	 * @var bool
	 * @access protected
	 */
	protected $_Local_images;
  
	/**
	 * SelfPath to allow themes as well as plugins.
	 *
	 * @var string
	 * @access protected
	 * @since 1.0
	 */
	protected $SelfPath;
	
	/**
	 * $field_types  holds used field types
	 * @var array
	 * @access public
	 * @since 1.0.0
	 */
	public $field_types = array();

	/**
	 * $inGroup  holds groupping boolean
	 * @var boolean
	 * @access public
	 * @since 1.0.0
	 */
	public $inGroup = false;
  
	/**
	 * Constructor
	 *
	 * @package Offer Calc
	 * @since 1.0.0
	 * 
	 * @access public	 *
	 * @param array $meta_box 
	 */
	public function __construct ( $meta_box ) {
    
		// Exit if is not admin
		if ( ! is_admin() ) {
			return;
		}
      
		// Assign meta box values to local variables and add it's missed values.
		$this->_meta_box = $meta_box;
		$this->_prefix = ( isset( $meta_box['prefix'] ) ) ? $meta_box['prefix'] : ''; 
		$this->_fields = &$this->_meta_box['fields'];
		$this->_Local_images = ( isset( $meta_box['local_images'] ) ) ? true : false;
		$this->add_missed_values();
		$this->SelfPath = OFFER_CALC_METABOX_URL;
	
		// Add metaboxes
		add_action( 'add_meta_boxes', array( &$this, 'add' ) );
		//add_action( 'wp_insert_post', array( &$this, 'save' ) );
		add_action( 'save_post', array( &$this, 'save' ) );
		
		// Load common js, css files
		// Must enqueue for all pages as we need js for the media upload, too.
		add_action( 'admin_enqueue_scripts', array( &$this, 'load_scripts_styles' ) );
		// Delete file via Ajax
		//add_action( 'wp_ajax_at_delete_mupload', array( $this, 'wp_ajax_delete_image' ) );
		// Delete all attachments when delete custom post type.
		add_action( 'wp_ajax_atm_delete_file',     array( &$this, 'delete_file' ) );
		add_action( 'wp_ajax_atm_reorder_images',   array( &$this, 'reorder_images' ) );
	}
  
	/**
	 * Load all Javascript and CSS
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function load_scripts_styles() {
    
		// Get Plugin Path
		$plugin_path = $this->SelfPath;    
			
		// Check for which post type we need to load the styles and scripts	
		if( $this->_meta_box['pages'] == 'all' ) {
			$pages = get_post_types( array( 'public' => true ), 'names' );
		} else {
			$pages = $this->_meta_box['pages'];
		}
    
		/**
		 * only load styles and js when needed
		 * @since 1.0
		 */
		global $typenow, $wp_version;
    
		if ( in_array( $typenow, $pages ) && $this->is_edit_page() ) {
			
			// Enqueue Meta Box Style
			wp_enqueue_style( 'wpd-mb-meta-box', $plugin_path . '/css/meta-box.css' );
      
			// Enqueue Meta Box Scripts
			wp_enqueue_script( 'wpd-mb-meta-box', $plugin_path . '/js/meta-box.js', array( 'jquery' ), null, true );

			//localize script
			$newui = $wp_version >= '3.5' ? '1' : '0'; //check wp version for showing media uploader
			wp_localize_script( 'wpd-mb-meta-box', 'WpdMbSettings', array( 
																				'new_media_ui'		=>	$newui,
																			));
																					
			// Make upload feature work event when custom post type doesn't support 'editor'
			if ( $this->has_field( 'image' ) || $this->has_field( 'file' ) ){
				wp_enqueue_script( 'media-upload' );
				add_thickbox();
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-sortable' );
			}
      
			//this replaces the ugly check fields methods calls
			foreach( array( 'upload',  'select' ) as $type ) {
				call_user_func( array( &$this, 'check_field_' . $type ) );
			}
		}   
	}
	
	/**
	 * Check the Field select, Add needed Actions
	 *
	 * @package Offer Calc
     * @since 1.0.0
     * 
	 * @access public
	 */
	public function check_field_select() {
    
		// Check if the field is an image or file. If not, return.
		/*if( ! $this->has_field( 'select' ) )
			return;*/
      
		$plugin_path = $this->SelfPath;
		// Enqueu JQuery UI, use proper version.
      
		// Enqueu JQuery select2 library, use proper version.
		wp_enqueue_style( 'wps-multiselect-select2-css', $plugin_path . '/js/chosen/chosen.css', array(), null );	
		wp_enqueue_script( 'wps-multiselect-select2-js', $plugin_path . '/js/chosen/chosen.jquery.js', array( 'jquery' ), false, true );    
	}
  
	/**
	 * Check the Field Upload, Add needed Actions
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function check_field_upload() {
    
		// Check if the field is an image or file. If not, return.
		if ( ! $this->has_field( 'image' ) && ! $this->has_field( 'file' ) )
			return;
		
		// Add data encoding type for file uploading.  
		add_action( 'post_edit_form_tag', array( &$this, 'add_enctype' ) );
		
		// Add filters for media upload.
		add_filter( 'media_upload_gallery', array( &$this, 'insert_images' ) );
		add_filter( 'media_upload_library', array( &$this, 'insert_images' ) );
		add_filter( 'media_upload_image',   array( &$this, 'insert_images' ) );
	}
  
	/**
	 * Add data encoding type for file uploading
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function add_enctype () {
		printf( ' enctype="multipart/form-data" encoding="multipart/form-data" ' );
	}
  
	/**
	 * Process images added to meta field.
	 *
	 * Modified from Faster Image Insert plugin.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @return void	 
	 */
	public function insert_images() {
    
		// If post variables are empty, return.
		if ( ! isset( $_POST['wpd-mb-meta-insert'] ) || empty( $_POST['attachments'] ) )
		return;
    
		// Security Check
		check_admin_referer( 'media-form' );
    
		// Create Security Nonce
		$nonce = wp_create_nonce( 'at_ajax_delete' );
    
		// Get Post Id and Field Id
		$post_id = $_POST['post_id'];
		$id = $_POST['field_id'];
    
		// Modify the insertion string
		$html = '';
		
		foreach( $_POST['attachments'] as $attachment_id => $attachment ) {
      
			// Strip Slashes
			$attachment = stripslashes_deep( $attachment );
      
			// If not selected or url is empty, continue in loop.
			if ( empty( $attachment['selected'] ) || empty( $attachment['url'] ) )
				continue;
			
			$li    = "<li id='item_{$attachment_id}'>";
			$li   .= "<img src='{$attachment['url']}' alt='image_{$attachment_id}' />";
			$li   .= "<a title='" . __( 'Delete this image', 'wpdmb' ) . "' class='wpd-mb-meta-delete-file' href='#' rel='{$nonce}|{$post_id}|{$id}|{$attachment_id}'><img src='" . $this->SelfPath. "/images/delete-16.png' alt='" . __( 'Delete', 'wpdmb' ) . "' /></a>";
			$li   .= "<input type='hidden' name='{$id}[]' value='{$attachment_id}' />";
			$li   .= "</li>";
			$html .= $li;
		  
		} // End For Each
    
		return media_send_to_editor( $html );    
	}
  
	/**
	 * Delete attachments associated with the post.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public 
	 * @param string $post_id 
	 */
	public function delete_attachments( $post_id ) {
    
		// Get Attachments
		$attachments = get_posts( array( 'numberposts' => -1, 'post_type' => 'attachment', 'post_parent' => $post_id ) );
    
		// Loop through attachments, if not empty, delete it.
		if ( ! empty( $attachments ) ) {
			foreach ( $attachments as $att ) {
				wp_delete_attachment( $att->ID );
			}
		}
	}
  
	/**
	 * Ajax callback for deleting files.	
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public 
	 */
	public function delete_file() {
    
		// If data is not set, die.
		if ( ! isset( $_POST['data'] ) )
			die();
		  
		list($nonce, $post_id, $key, $attach_id) = explode('|', $_POST['data']);
		
		if ( ! wp_verify_nonce( $nonce, 'at_ajax_delete' ) )
			die( '1' );
			
		$saved = get_post_meta( $post_id,$key, true );

		$index = array_search( $attach_id, $saved );    
		foreach( $saved as $k => $value ) {
			if ( $value == $attach_id )
				unset( $saved[$k] );
		}
		
		if( count( $saved ) > 0 ){
			update_post_meta( $post_id, $key,$saved );
			die('0');
		}
		  
		delete_post_meta( $post_id, $key, $attach_id );
		
		die( '0' );  
	}
	
	/**
	 * Ajax callback for deleting files.
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function wp_ajax_delete_image() {
	
		$post_id = isset( $_GET['post_id'] ) ? intval( $_GET['post_id'] ) : 0;
		$field_id = isset( $_GET['field_id'] ) ? $_GET['field_id'] : 0;
		$attachment_id = isset( $_GET['attachment_id'] ) ? intval( $_GET['attachment_id'] ) : 0;
		$ok = false;
		if (strpos($field_id, '[') === false){
			check_admin_referer( "wpd-mb-meta-delete-mupload_".urldecode($field_id));
			$ok = delete_post_meta( $post_id, $field_id );
			$ok = $ok && wp_delete_attachment( $attachment_id );
		} else {
			$f = explode('[',urldecode($field_id));
			$f_fiexed = array();
		  
			foreach ($f as $k => $v){
				$f[$k] = str_replace(']','',$v);
			}
			
			$saved = get_post_meta($post_id,$f[0],true);
			
			if (isset($saved[$f[1]][$f[2]])){
				unset($saved[$f[1]][$f[2]]);
				$ok = update_post_meta($post_id,$f[0],$saved);
				$ok = $ok && wp_delete_attachment( $attachment_id );
			}
		}    
    
		if ( $ok ){
			echo json_encode( array( 'status' => 'success' ) );
			die();
		} else {
			echo json_encode( array( 'message' => __( 'Cannot delete file. Something\'s wrong.', 'wpdmb' ) ) );
			die();
		}
	}
  
	/**
	 * Ajax callback for reordering Images.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function reorder_images() {
    
		if ( ! isset( $_POST['data'] ) )
			die();
		  
		list( $order, $post_id, $key, $nonce ) = explode( '|', $_POST['data'] );
		
		if ( ! wp_verify_nonce( $nonce, 'at_ajax_reorder' ) )
			die( '1' );
		  
		parse_str( $order, $items );
		$items = $items['item'];
		$order = 1;
		
		foreach ( $items as $item ) {
			wp_update_post( array( 'ID' => $item, 'post_parent' => $post_id, 'menu_order' => $order ) );
			$order++;
		}
		
		die( '0' );  
	}  
	 
	/**
	 * Add Meta Box for multiple post types.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function add() {
    
		// Check for which post type we need to add the meta box
		if( $this->_meta_box['pages'] == 'all' ) {
			$pages = get_post_types( array( 'public' => true ), 'names' );
		} else {
			$pages = $this->_meta_box['pages'];
		}
		
		// Loop through array	
		foreach ( $pages as $page ) {
			add_meta_box( $this->_meta_box['id'], $this->_meta_box['title'], array( &$this, 'show' ), $page, $this->_meta_box['context'], $this->_meta_box['priority'] );
		}
	}
  
	/**
	 * Callback function to show fields in meta box.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public 
	 */
	public function show() {
    
		global $post;
		
		//var_dump($this->_fields);
		wp_nonce_field( basename(__FILE__), 'at_meta_box_nonce' );
		
		echo '<table class="form-table">';
		$edit_class_arr = array( '_offercalc_image_title', '_offercalc_service_title', '_offercalc_price_title', '_offercalc_count_title', '_offercalc_total_title' );
		$is_header = 0;
		
		foreach ( $this->_fields as $field ) {
			$field['multiple'] = isset( $field['multiple'] ) ? $field['multiple'] : false;
			$meta = get_post_meta( $post->ID, $field['id'], !$field['multiple'] );
			$meta = ( $meta !== '' ) ? $meta : $field['std'];
			
			if( $field['id'] == '_offercalc_edit_header' && $meta == 1 ) {
				$is_header = 1;
			}
			if( $field['id'] == '_offercalc_edit_header' && $meta == 1 ) {
				$is_header = 1;
			}
			
			if( !in_array( $field['type'], array( 'image', 'repeater', 'file','cond' ) ) ) {
				$meta = is_array( $meta ) ? array_map( 'esc_attr', $meta ) : esc_attr( $meta );
			}
			
			if( $this->inGroup !== true ) {		
				if( in_array( $field['id'], $edit_class_arr) ){
					$style = ( $is_header == 1 ) ? 'display:table-row' : 'display:none' ;
					echo '<tr valign="top" class="edit-header-opt" style="'.$style.'">';
				}
				else {	
					echo '<tr valign="top">';
				}
			}
			
			if( isset( $field['group'] ) && $field['group'] == 'start' ){
				$this->inGroup = true;
				echo '<td><table class="form-table"><tr valign="top">';
			}
			
			// Call Separated methods for displaying each type of field.
			call_user_func ( array( &$this, 'show_field_' . $field['type'] ), $field, $meta );

			if( $this->inGroup === true ) {
				if( isset($field['group'] ) && $field['group'] == 'end' ) {
					echo '</tr></table></td></tr>';
					$this->inGroup = false;
				}
			} else {
				echo '</tr>';
			}
		}
		
		echo '</table>';
	}
  
	/**
	 * Show Repeater Fields.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	public function show_field_repeater( $field, $meta ) {
		
		global $post;  
		
		// Get Plugin Path
		$plugin_path = $this->SelfPath;
		
		$this->show_field_begin( $field, $meta );
		
		$class = '';
		  
		if( $field['sortable'] )  
			$class = " repeater-sortable";
		echo "<div class='wpd-mb-meta-repeat".$class."' id='{$field['id']}'>";
		
		$c = 0;
		$meta = get_post_meta( $post->ID, $field['id'], true );
		
		if( count( $meta ) > 0 && is_array( $meta ) ) {
			 
			foreach( $meta as $me ) {				
				
				//for labling toggles
				//$mmm =  $me[$field['fields'][0]['id']];
				$mmm =  isset( $me[$field['fields'][0]['id']] )? $me[$field['fields'][0]['id']]: "";
				
				echo '<div class="wpd-mb-meta-repater-block">'.$mmm.'<br/><table  class="repeater-table form-table" style="display: none;">';
				if( $field['inline'] ) {
					echo '<tr class="wpd-mb-meta-inline" valign="top">';
				}
				
				$replace_dp = '';
				foreach ( $field['fields'] as $f ) {
					
					//reset var $id for repeater
					$id = '';
					$id = $field['id'].'['.$c.']['.$f['id'].']';
					
					$real_id = $f['id'];					
					
					$m = isset($me[$f['id']]) ? $me[$f['id']]: '';
					$m = ( $m !== '' ) ? $m : $f['std'];
					
					/*if ('image' != $f['type'] && $f['type'] != 'repeater')
						$m = is_array( $m) ? array_map( 'esc_attr', $m ) : esc_attr( $m);*/
					//set new id for field in array format
					$f['id'] = $id;
					
					$replace_dp = !empty($me['replace_count_dropdown']) ? $me['replace_count_dropdown'] : 0;						
								
					if ( !$field['inline'] ) {
						
						if( ($f['type'] == 'radio' && $real_id == 'count_field_type')){
							$style = ( !empty( $replace_dp ) ? "display:table-row" : 'display:none');
							echo '<tr class="cnt_type_options" style="'.$style.'">';	
						}
						elseif ( $f['type'] == 'multiple_textbox' ){								
							$style = ( empty( $replace_dp ) ) ? 'display:table-row' : 'display:none' ;
							echo '<tr style="'.$style.'">';
						}
						elseif ( isset($real_id) && $real_id == 'count_field_type_opt' ){
							
						}
						else {
							echo '<tr>';		
						}				
						
					} 						
								
					call_user_func( array( &$this, 'show_field_' . $f['type'] ), $f, $m );
					
					if( !$field['inline'] ){
						if ( isset($real_id) && $real_id == 'count_field_type_opt' ){
							
						}
						else {
							echo '</tr>';
						}
					} 
				}
				
				if( $field['inline'] ){  
					echo '</tr>';
				}
				
				echo '	</table>
				
				
				
				<img src="';
					
				if ($this->_Local_images){
					echo $plugin_path.'/images/remove.png';
				} else {
					echo 'http://i.imgur.com/g8Duj.png';
				}
				
				echo '" alt="'.__( 'Remove', 'wpdmb' ).'" title="'.__( 'Remove', 'wpdmb' ).'" id="remove-'.$field['id'].'">
				
				
				<span class="wpd-mb-meta-re-toggle"><img src="';
				   
				if ( $this->_Local_images ) {
					echo $plugin_path.'/images/edit.png';
				} else {
					echo 'http://i.imgur.com/ka0E2.png';
				}
						
				echo '" alt="Edit" title="Edit"/></span>
				
				</div>';
				
				$c = $c + 1;			
			}
		}

		echo '<img src="';
		
		if ( $this->_Local_images ) {
			echo $plugin_path.'/images/add.png';
		} else {
			echo 'http://i.imgur.com/w5Tuc.png';
		}
			
		echo '" alt="'.__( 'Add', 'wpdmb' ).'" title="'.__( 'Add', 'wpdmb' ).'" id="add-'.$field['id'].'"><br/></div>';
		
		//create all fields once more for js function and catch with object buffer
		ob_start();
		echo '<div class="wpd-mb-meta-repater-block"><table class="repeater-table form-table">';
		
		if ($field['inline']){
			echo '<tr class="wpd-mb-meta-inline" VALIGN="top">';
		} 
		
		foreach ($field['fields'] as $f){
			//reset var $id for repeater
			$id = '';
			$id = $field['id'].'[CurrentCounter]['.$f['id'].']';
			$f['id'] = $id; 
			
			if (!$field['inline']){
				
				if( ($f['type'] == 'radio' && $f['name'] == 'Select Count Type')){
					echo '<tr class="cnt_type_options" style="display:none">';	
				}
				elseif ( isset($real_id) && $real_id == 'count_field_type_opt' ){
					
				}
			}
				
			if ($f['type'] != 'wysiwyg') {
				call_user_func ( array( &$this, 'show_field_' . $f['type'] ), $f, '');
			} else {
				call_user_func ( array( &$this, 'show_field_' . $f['type'] ), $f, '',true);
			}
		  
			if (!$field['inline']){
				echo '</tr>';
			}  
		}
			
		if ($field['inline']){
			echo '</tr>';
		} 
			
		echo '</table><img src="';
			
		if ($this->_Local_images){
			echo $plugin_path.'/images/remove.png';
		} else {
			echo 'http://i.imgur.com/g8Duj.png';
		}
			
		echo '" alt="'.__( 'Remove', 'wpdmb' ).'" title="'.__( 'Remove', 'wpdmb' ).'" id="remove-'.$field['id'].'"></div>';
			
		$counter = 'countadd_'.$field['id'];
		$js_code = ob_get_clean ();
		$js_code = str_replace("\n","",$js_code);
		$js_code = str_replace("\r","",$js_code);
		$js_code = str_replace("'","\"",$js_code);
		$js_code = str_replace("CurrentCounter","' + ".$counter." + '",$js_code);
			
		echo '	<script>
					jQuery(document).ready(function() {
						var '.$counter.' = '.$c.';
						jQuery( document ).on( "click", "#add-'.$field['id'].'", function() {
							'.$counter.' = '.$counter.' + 1;
							jQuery(this).before(\''.$js_code.'\');            
							update_repeater_fields();
						});
						jQuery( document ).on( "click", "#remove-'.$field['id'].'", function() {
							jQuery(this).parent().remove();
						});
					});
				</script>';  
		
		$this->show_field_end( $field, $meta );
	}
  
	/**
	 * Begin Field.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	public function show_field_begin( $field, $meta ) {
		
		if( isset( $field['group'] ) ){
			if( $field['group'] == "start" ){
				echo "<th>";
			}
		} else {
			echo "<th>";
		}
		
		if ( $field['name'] != '' || $field['name'] != FALSE ) {
			echo "<label for='{$field['id']}'>{$field['name']}</label>";
		}
		
		echo '</th>';
		echo '<td>';
	}
  
	/**
	 * End Field.
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public 
	 */
	public function show_field_end( $field, $meta=NULL ,$group = false) {
	
		if (isset($field['group'])){
			if ($group == 'end'){
				if ( $field['desc'] != '' ) {
					echo "<div class='desc-field'>{$field['desc']}</div></td>";
				} else {
					echo "</td>";
				}
			} else {
				if ( $field['desc'] != '' ) {
					echo "<div class='desc-field'>{$field['desc']}</div><br/>";  
				} else {
					echo '<br/>';
				}  
			}    
		} else {
			if ( $field['desc'] != '' ) {
				echo "<div class='desc-field'>{$field['desc']}</div></td>";
			} else {
				echo "</td>";
			}
		}
	}
  
	/**
	 * Show Field Text.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	public function show_field_text( $field, $meta) {  	
		
		$this->show_field_begin( $field, $meta );
		echo "<input type='text' class='{$field['class']}' name='{$field['id']}' id='{$field['id']}' value='{$meta}' />";
		$this->show_field_end( $field, $meta );
	} 
  
	/**
	 * Show Field hidden.
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string|mixed $meta 
	 * @access public
	 */
	public function show_field_hidden( $field, $meta ) {  
	
		//$this->show_field_begin( $field, $meta );
		echo "<input type='hidden' class='regular-text' name='{$field['id']}' id='{$field['id']}' value='{$meta}'/>";
		//$this->show_field_end( $field, $meta );
	}
  
	/**
	 * Show Field Paragraph.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @access public
	 */
	public function show_field_paragraph( $field ) {  
	
		//$this->show_field_begin( $field, $meta );
		echo '<p>'.$field['value'].'</p>';
		//$this->show_field_end( $field, $meta );
	}
    
	/**
	 * Show Field Textarea.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	public function show_field_textarea( $field, $meta ) {

		$this->show_field_begin( $field, $meta );
		echo "<textarea class='wpd-mb-meta-textarea large-text' name='{$field['id']}' id='{$field['id']}' cols='60' rows='10'>{$meta}</textarea>";
		$this->show_field_end( $field, $meta );
	}
  
	/**
	 * Show Field Select.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	public function show_field_select( $field, $meta ) {
    
		if( ! is_array( $meta ) ) {
			$meta = (array) $meta;
		}
		  
		$this->show_field_begin( $field, $meta );		
		echo "<select class='wpd-mb-meta-select' name='{$field['id']}" . ( $field['multiple'] ? "[]' id='{$field['id']}' multiple='multiple'" : "'" ) . ">";
		
		foreach ( $field['options'] as $key => $value ) {
				echo "<option value='{$key}'" . selected( in_array( $key, $meta ), true, false ) . ">{$value}</option>";
		}
		
		echo "</select>";		
		$this->show_field_end( $field, $meta );    
	}
  
	/**
	 * Show Radio Field.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public 
	 */
	public function show_field_radio( $field, $meta ) {
    
		if ( ! is_array( $meta ) ) {
			$meta = (array) $meta;
		}
		  
		$this->show_field_begin( $field, $meta );
		
		foreach ( $field['options'] as $key => $value ) {
			echo "<input type='radio' class='wpd-mb-meta-radio' name='{$field['id']}' value='{$key}'" . checked( in_array( $key, $meta ), true, false ) . " /> <span class='wpd-mb-meta-radio-label'>{$value}</span>";
		}		
		
		$this->show_field_end( $field, $meta );
	}
  
	/**
	 * Show Checkbox Field.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	public function show_field_checkbox( $field, $meta ) {
  		
		if( isset($field['class']) ){
			$class = $field['class']; 
		}
		else {
			$class = '';
		}
		$this->show_field_begin($field, $meta);
		echo "<input type='checkbox' class='rw-checkbox {$class}' name='{$field['id']}' value='1' id='{$field['id']}'" . checked(!empty($meta), true, false) . " /><p class='wpd-mb-meta'>{$field['desc']}</p></td>";
      
	}
  
	/**
	 * Show Wysiwig Field.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	public function show_field_wysiwyg( $field, $meta,$in_repeater = false ) {
	
		$this->show_field_begin( $field, $meta );
    
		// Add TinyMCE script for WP version < 3.3
		global $wp_version;
    
		if ( version_compare( $wp_version, '3.2.1' ) < 1 || $in_repeater ) {
			echo "<textarea class='wpd-mb-meta-wysiwyg theEditor large-text' name='{$field['id']}' id='{$field['id']}' cols='60' rows='10'>{$meta}</textarea>";
		} else {
			// Use new wp_editor() since WP 3.3
			wp_editor( html_entity_decode($meta), $field['id'], array( 'editor_class' => 'wpd-mb-meta-wysiwyg' ) );
		}
   
		$this->show_field_end( $field, $meta );
	}
  
	/**
	 * Show File Field.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	public function show_field_file( $field, $meta ) {
    
		global $post;

		if ( ! is_array( $meta ) ) {
			$meta = (array) $meta;
		}

		$this->show_field_begin( $field, $meta );
		
		echo "{$field['desc']}<br />";

		if( ! empty( $meta ) ) {
			$nonce = wp_create_nonce( 'at_ajax_delete' );
			echo '<div style="margin-bottom: 10px"><strong>' . __( 'Uploaded files', 'wpdmb' ) . '</strong></div>';
			echo '<ol class="wpd-mb-meta-upload">';
			
				foreach( ( array )$meta[0] as $key => $att ) {
					// if (wp_attachment_is_image($att)) continue; // what's image uploader for?
					echo "<li>" . wp_get_attachment_url( $att) . " (<a class='wpd-mb-meta-delete-file' href='#' rel='{$nonce}|$key|{$field['id']}|{$att}'>" . __( 'Delete', 'wpdmb' ) . "</a>)</li>";
				}
			echo '</ol>';
		}

		// show form upload
		echo "<div class='wpd-mb-meta-file-upload-label'>";
		echo "<strong>" . __( 'Upload new files', 'wpdmb' ) . "</strong>";
		echo "</div>";
		echo "<div class='new-files'>";
		echo "<div class='file-input'>";
		echo "<input type='file' name='{$field['id']}[]' />";
		echo "</div><!-- End .file-input -->";
		echo "<a class='wpd-mb-meta-add-file button' href='#'>" . __( 'Add more files', 'wpdmb' ) . "</a>";
		echo "</div><!-- End .new-files -->";
		echo "</td>";
		$this->show_field_end( $field, $meta );
	}
  
	/**
	 * Show Image Field.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param array $field 
	 * @param array $meta 
	 * @access public
	 */
	public function show_field_image( $field, $meta ) {
	
		$this->show_field_begin( $field, $meta );
		$html = wp_nonce_field( "wpd-mb-meta-delete-mupload_{$field['id']}", "nonce-delete-mupload_".$field['id'], false, false );
	
		if( is_array( $meta ) ) {
			if( isset( $meta[0] ) && is_array( $meta[0] ) ) {
				$meta = $meta[0];
			}
		}
		
		if( is_array( $meta ) && isset( $meta['src'] ) && $meta['src'] != '' ) {
			$html .= "<span class='mupload_img_holder'><img src='".$meta['src']."' style='height: 150px;width: 150px;' /></span>";
			$html .= "<input type='hidden' name='".$field['id']."[id]' id='".$field['id']."[id]' value='".$meta['id']."' />";
			$html .= "<input type='hidden' name='".$field['id']."[src]' id='".$field['id']."[src]' value='".$meta['src']."' />";
			$html .= "<input class='wpd-mb-meta-delete_image_button button-secondary' type='button' rel='".$field['id']."' value='Delete Image' />";
		} else {
			$html .= "<span class='mupload_img_holder'></span>";
			$html .= "<input type='hidden' name='".$field['id']."[id]' id='".$field['id']."[id]' value='' />";
			$html .= "<input type='hidden' name='".$field['id']."[src]' id='".$field['id']."[src]' value='' />";
			$html .= "<input class='wpd-mb-meta-upload_image_button button-secondary' type='button' rel='".$field['id']."' value='" . __( 'Upload Image', 'wpdmb' ) . "' />";
		}
		echo $html;
	}
  
	/**
	 * Show Checkbox List Field
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	public function show_field_checkbox_list( $field, $meta ) {
    
		if ( ! is_array( $meta ) ) {
			$meta = (array) $meta;
		}
		  
		$this->show_field_begin($field, $meta);
		
		$html = array();
		
		foreach ($field['options'] as $key => $value) {
			$html[] = "<input type='checkbox' class='wpd-mb-meta-checkbox_list' name='{$field['id']}[]' value='{$key}'" . checked( in_array( $key, $meta ), true, false ) . " /> {$value}";
		}
		
		echo implode( '<br />' , $html );
		  
		$this->show_field_end($field, $meta);    
	}
  
	/**
	 * Show repeative box
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	
	
	public function show_field_custom_repeater( $field, $meta ) {
    	
		global $post;
		
		// Get Plugin Path
		$plugin_path = $this->SelfPath;
		
		if ( ! is_array( $meta ) ) {
			$meta = (array) $meta;
		}
		
		//$this->show_field_begin($field, $meta);	
		
		foreach ($field['options'] as $key => $value) {
			
			$id = ltrim ($field['id'],'_');
			$id = str_replace('[count_field_type]', '', $id);
			
			if( $key == 1 ) {
				
				$style = (!empty($m) ? "display:table-row" : 'display:none');
				
				$meta_val = isset($meta['radio']) ? $meta['radio'] : '';
				$meta_radio_prc = isset($meta['radio_price']) ? $meta['radio_price'] : '';
				
				$style = ( (!empty($meta['radio']) && $meta['radio'][0] != '') ? "display:table-row" : "display:none") ;
				echo '<tr class="cnt_options opt_radio" style="'.$style.'">';
				echo '<th></th><td class="type_opt">';
				
				echo '<img src="';
				
				if ( $this->_Local_images ) {
					echo $plugin_path.'/images/add.png';
				} else {
					echo 'http://i.imgur.com/w5Tuc.png';
				}
				
				echo '" alt="'.__( 'Add', 'wpdmb' ).'" title="'.__( 'Add', 'wpdmb' ).'" class="add-radio-reapeter">';
				
				if( count( $meta_val ) > 0 && is_array( $meta_val ) ) {
					
					foreach ( $meta_val as $m_k => $meta_value ) {
						
						echo "<div id='opt_radio' class='count-type-opt-radio'>";			
						
						echo "<input type='text' class='wpd-mb-meta-custom_repeater'  name='{$field['id']}[radio][]' value='{$meta_value}' placeholder='radio label'  />";
						echo "<input type='text' class='wpd-mb-meta-radio_price'  name='{$field['id']}[radio_price][]' value='{$meta_radio_prc[$m_k]}' placeholder='radio price'  />";
						
						echo '<img src="';
						
						if ($this->_Local_images){
							echo $plugin_path.'/images/delete-16.png';
						} else {
							echo 'http://i.imgur.com/g8Duj.png';
						}
						echo '" alt="'.__( 'Remove', 'wpdmb' ).'" title="'.__( 'Remove', 'wpdmb' ).'" class="remove-radio-pro">';
						
						echo "</div>";
					}
				} else {
					
					echo "<div id='opt_radio' class='count-type-opt-radio'>";			
					
					echo "<input type='text' class='wpd-mb-meta-custom_repeater'  name='{$field['id']}[radio][]' value='' placeholder='radio label' />";
					echo "<input type='text' class='wpd-mb-meta-radio_price'  name='{$field['id']}[radio_price][]' value='' placeholder='radio price'  />";
					
					echo '<img src="';
					
					if ($this->_Local_images){
						echo $plugin_path.'/images/delete-16.png';
					} else {
						echo 'http://i.imgur.com/g8Duj.png';
					}
					echo '" alt="'.__( 'Remove', 'wpdmb' ).'" title="'.__( 'Remove', 'wpdmb' ).'" class="remove-radio-pro">';
					
					echo "</div>";
				}
				echo '</td></tr>';
			}
			else if( $key == 2 ){				
				
				$meta_val = isset($meta['checkbox']) ? $meta['checkbox'] : '';
				$style = ( (!empty($meta['checkbox'])) ? "display:table-row" : "display:none") ;
				echo '<tr class="cnt_options opt_checkbox" style="'.$style.'">';
				echo '<th></th><td class="type_opt">';
				echo "<div id='{$id}_checkbox' class='count-type-opt-checkbox'>
				<input type='text' class='wpd-mb-meta-custom_repeater' name='{$field['id']}[checkbox]' value='{$meta_val}' placeholder='checkbox label' />
				</div>";
				echo '</td></tr>';
			}
			else if( $key == 3 ){	
					
				$meta_val = isset($meta['text']) ? $meta['text'] : '';
				$style = ( (!empty($meta['text'])) ? "display:table-row" : "display:none") ;
				echo '<tr class="cnt_options opt_text" style="'.$style.'">';
				echo '<th></th><td class="type_opt">';		
				echo "<div id='{$id}_text' class='count-type-opt-text'>
				<input type='text' class='wpd-mb-meta-custom_repeater' name='{$field['id']}[text]' value='{$meta_val}' placeholder='textbox label'/>
				</div>";
				echo '</td></tr>';
			}
			else if( $key == 4 ){	
					
				$meta_val = isset($meta['textarea']) ? $meta['textarea'] : '';
				$style = ( (!empty($meta['textarea'])) ? "display:table-row" : "display:none") ;
				echo '<tr class="cnt_options opt_textarea" style="'.$style.'">';
				echo '<th></th><td class="type_opt">';		
				echo "<div id='{$id}_text' class='count-type-opt-textarea'>
				<input type='text' class='wpd-mb-meta-custom_repeater' name='{$field['id']}[textarea]' value='{$meta_val}' placeholder='textarea label'/>
				</div>";
				echo '</td></tr>';
			}
			else if( $key == 5 ){	
					
				$meta_val = isset($meta['select']) ? $meta['select'] : '';
				$style = ( (!empty($meta['select'])) ? "display:table-row" : "display:none") ;
				echo '<tr class="cnt_options opt_select" style="'.$style.'">';
				echo '<th></th><td class="type_opt">';		
				echo "<div id='{$id}_text' class='count-type-opt-select'>
				<input type='text' class='wpd-mb-meta-custom_repeater' name='{$field['id']}[select]' value='{$meta_val}' placeholder='select label'/>
				</div>";
				echo '</td></tr>';
			}
			else if( $key == 6 ){	
					
				$style = (!empty($m) ? "display:table-row" : 'display:none');
				
				$meta_val = isset($meta['multi_select']) ? $meta['multi_select'] : '';
				$meta_radio_prc = isset($meta['multi_select_price']) ? $meta['multi_select_price'] : '';
				
				$style = ( (!empty($meta['multi_select']) && $meta['multi_select'][0] != '') ? "display:table-row" : "display:none") ;
				echo '<tr class="cnt_options opt_multi_select" style="'.$style.'">';
				echo '<th></th><td class="type_opt">';
				
				echo '<img src="';
				
				if ( $this->_Local_images ) {
					echo $plugin_path.'/images/add.png';
				} else {
					echo 'http://i.imgur.com/w5Tuc.png';
				}
				
				echo '" alt="'.__( 'Add', 'wpdmb' ).'" title="'.__( 'Add', 'wpdmb' ).'" class="add-multi-select-reapeter">';
				
				if( count( $meta_val ) > 0 && is_array( $meta_val ) ) {
					
					foreach ( $meta_val as $m_k => $meta_value ) {
						
						echo "<div id='opt_multi_select' class='count-type-opt-multi-select'>";			
						
						echo "<input type='text' class='wpd-mb-meta-select_repeater'  name='{$field['id']}[multi_select][]' value='{$meta_value}' placeholder='option label'  />";
						echo "<input type='text' class='wpd-mb-meta-select_price'  name='{$field['id']}[multi_select_price][]' value='{$meta_radio_prc[$m_k]}' placeholder='option price'  />";
						
						echo '<img src="';
						
						if ($this->_Local_images){
							echo $plugin_path.'/images/delete-16.png';
						} else {
							echo 'http://i.imgur.com/g8Duj.png';
						}
						echo '" alt="'.__( 'Remove', 'wpdmb' ).'" title="'.__( 'Remove', 'wpdmb' ).'" class="remove-multi-select-pro">';
						
						echo "</div>";
					}
				} else {
					
					echo "<div id='opt_multi_select' class='count-type-opt-multi_select'>";			
					
					echo "<input type='text' class='wpd-mb-meta-select_repeater'  name='{$field['id']}[multi_select][]' value='' placeholder='option label' />";
					echo "<input type='text' class='wpd-mb-meta-select_price'  name='{$field['id']}[multi_select_price][]' value='' placeholder='option price'  />";
					
					echo '<img src="';
					
					if ($this->_Local_images){
						echo $plugin_path.'/images/delete-16.png';
					} else {
						echo 'http://i.imgur.com/g8Duj.png';
					}
					echo '" alt="'.__( 'Remove', 'wpdmb' ).'" title="'.__( 'Remove', 'wpdmb' ).'" class="remove-multi-select-pro">';
					
					echo "</div>";
				}
				echo '</td></tr>';
			}
			else if( $key == 7 ){	
					
				$meta_val = isset($meta['number']) ? $meta['number'] : '';
				$style = ( (!empty($meta['number'])) ? "display:table-row" : "display:none") ;
				echo '<tr class="cnt_options opt_number" style="'.$style.'">';
				echo '<th></th><td class="type_opt">';		
				echo "<div id='{$id}_text' class='count-type-opt-number'>
				<input type='text' class='wpd-mb-meta-custom_repeater' name='{$field['id']}[number]' value='{$meta_val}' placeholder='number label'/>
				</div>";
				echo '</td></tr>';
			}
		}
		
		//$this->show_field_end($field, $meta);    
	}

	/**
	 * Show multiple textbox Field
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	 */
	public function show_field_multiple_textbox( $field, $meta ) {
    
		if ( ! is_array( $meta ) ) {
			$meta = (array) $meta;
		}
		  
		$this->show_field_begin($field, $meta);
		
		$html = array();

		$meta_inx =	0;
		foreach ($field['options'] as $key => $value) {
			
			$def_value = ( !empty($meta[$meta_inx]) ) ? $meta[$meta_inx] : '' ;
			
			$html[] = "{$value}<input type='text' class='wpd-mb-meta-multiple_textbox' name='{$field['id']}[]' value='{$def_value}' /> ";
			$meta_inx++;
		}
		
		echo implode( '&nbsp' , $html );
		  
		$this->show_field_end($field, $meta);    
	}
	
	/**
	 * Show Posts field.
	 * used creating a posts/pages/custom types checkboxlist or a select dropdown
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public 
 	 */
	public function show_field_posts( $field, $meta ) {
	
		global $post;
    
		if( !is_array( $meta ) ) {
			$meta = (array) $meta;
		}
		
		$this->show_field_begin( $field, $meta );
		$options = $field['options'];
		$posts = get_posts( $options['args'] );
    
		// checkbox_list
		if ('checkbox_list' == $options['type']) {
			foreach ($posts as $p) {
				echo "<input type='checkbox' name='{$field['id']}[]' value='$p->ID'" . checked(in_array($p->ID, $meta), true, false) . " /> $p->post_title<br/>";
			}
		}
		
		// select
		else {
			echo "<select name='{$field['id']}" . ( $field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'" ) . ">";
			
			foreach ($posts as $p) {
				echo "<option value='$p->ID'" . selected( in_array( $p->ID, $meta ), true, false ) . ">$p->post_title</option>";
			}
    
			echo "</select>";
		}
    
		$this->show_field_end( $field, $meta );
	}
  
	/**
	 * Show Taxonomy field.
	 * used creating a category/tags/custom taxonomy checkboxlist or a select dropdown
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public 
	 * 
	 * @uses get_terms()
	 */
	public function show_field_taxonomy( $field, $meta ) {
    
		global $post;
    
		if( !is_array( $meta ) ) {
			$meta = (array) $meta;
		}
		
		$this->show_field_begin( $field, $meta );
		$options = $field['options'];
		$terms = get_terms( $options['taxonomy'], $options['args'] );
    
		// checkbox_list
		if( 'checkbox_list' == $options['type'] ) {
			foreach( $terms as $term ) {
				echo "<input type='checkbox' name='{$field['id']}[]' value='$term->slug'" . checked( in_array( $term->slug, $meta ), true, false ) . " /> $term->name<br/>";
			}
		}
		
		// select
		else {
			echo "<select name='{$field['id']}" . ( $field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'" ) . ">";
			foreach ($terms as $term) {
				echo "<option value='$term->slug'" . selected( in_array( $term->slug, $meta ), true, false ) . ">$term->name</option>";
			}
			echo "</select>";
		}
    
		$this->show_field_end( $field, $meta );
	}
	
	/**
	 * Show conditinal Checkbox Field.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $field 
	 * @param string $meta 
	 * @access public
	*/
	public function show_field_cond( $field, $meta ) {
  
		$this->show_field_begin( $field, $meta );
		$checked = false;
		
		if( is_array( $meta ) && isset( $meta['enabled'] ) && $meta['enabled'] == 'on' ) {
		  $checked = true;
		}
		
		echo "<input type='checkbox' class='conditinal_control' name='{$field['id']}[enabled]' id='{$field['id']}'" . checked( $checked, true, false ) . " />";
		//start showing the fields
		$display = ( $checked )? '' :  ' style="display: none;"';
		
		echo '<div class="conditinal_container"' . $display . '><table>';
		
		foreach( ( array )$field['fields'] as $f ) {
			//reset var $id for cond
			$id = '';
			$id = $field['id'].'['.$f['id'].']';
			$m = '';
			$m = ( isset( $meta[$f['id']] ) ) ? $meta[$f['id']]: '';
			$m = ( $m !== '' ) ? $m : ( isset( $f['std'] )? $f['std'] : '' );
		
			if( 'image' != $f['type'] && $f['type'] != 'repeater' )
				$m = is_array( $m) ? array_map( 'esc_attr', $m ) : esc_attr( $m );
			
			//set new id for field in array format
			$f['id'] = $id;
			echo '<tr>';
			call_user_func ( array( &$this, 'show_field_' . $f['type'] ), $f, $m );
			echo '</tr>';
		}
		
		echo '</table></div>';
		
		$this->show_field_end( $field, $meta );
	}
  
	/**
	 * Save Data from Metabox
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $post_id 
	 * @access public 
	 */
	public function save( $post_id ) {
    
		global $post_type;
		
		$post_type_object = get_post_type_object( $post_type );
		
		// Check for which post type we need to add the meta box
		if( $this->_meta_box['pages'] == 'all' ) {
			$pages = get_post_types( array( 'public' => true ), 'names' );
		} else {
			$pages = $this->_meta_box['pages'];
		}

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                // Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )        // Check Revision
		|| ( ! in_array( $post_type, $pages ) )              // Check if current post type is supported.
		|| ( ! check_admin_referer( basename( __FILE__ ), 'at_meta_box_nonce') )      // Check nonce - Security
		|| ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) )       // Check permission
		{
		  return $post_id;
		}
		
		foreach ( $this->_fields as $field ) {	
		
			$name = $field['id'];
			$type = $field['type'];
			$old = get_post_meta( $post_id, $name, ! $field['multiple'] );
			$new = ( isset( $_POST[$name] ) ) ? $_POST[$name] : ( ( $field['multiple'] ) ? array() : '' );	

			
			// Validate meta value
			if ( class_exists( 'Offer_Calc_Meta_Box_Validate' ) && method_exists( 'Offer_Calc_Meta_Box_Validate', $field['validate_func'] ) ) {
				$wpd_meta_validate = new Offer_Calc_Meta_Box_Validate();
				$new = call_user_func( array( $wpd_meta_validate, $field['validate_func'] ), $new );
			}
			
			//skip on Paragraph field
			if( $type != "paragraph" ) {
				// Call defined method to save meta value, if there's no methods, call common one.
				$save_func = 'save_field_' . $type;
				
				if( method_exists( $this, $save_func ) ) {
					call_user_func( array( &$this, 'save_field_' . $type ), $post_id, $field, $old, $new );
				} else {
					$this->save_field( $post_id, $field, $old, $new );
				}
			}		  
		} // End foreach			
	}
  
	/**
	 * Common function for saving fields.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $post_id 
	 * @param string $field 
	 * @param string $old 
	 * @param string|mixed $new 
	 * @access public
	 */
	public function save_field( $post_id, $field, $old, $new ) {
		
		$name = $field['id'];
		delete_post_meta( $post_id, $name );
		
		if ( $new === '' || $new === array() ) {
			return;
		}
		
		if ( $field['multiple'] ) {
			foreach ( $new as $add_new ) {
				add_post_meta( $post_id, $name, $add_new, false );
			}
		} else {
		  update_post_meta( $post_id, $name, $new );
		}
	}  
  
	/**
	 * function for saving image field.
	 *	 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $post_id 
	 * @param string $field 
	 * @param string $old 
	 * @param string|mixed $new 
	 * @access public
	 */
	public function save_field_image( $post_id, $field, $old, $new ) {
	
		$name = $field['id'];
		delete_post_meta( $post_id, $name );
	
		if ( $new === '' || $new === array() || $new['id'] == '' || $new['src'] == '') {
			return;
		}
		
		update_post_meta( $post_id, $name, $new );
	}
  
	/*
	 * Save Wysiwyg Field.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @param string $post_id 
	 * @param string $field 
	 * @param string $old 
	 * @param string $new 
	 * @access public 
	 */
	public function save_field_wysiwyg( $post_id, $field, $old, $new ) {
	
		$this->save_field( $post_id, $field, $old, $new );
	}
  
	/**
	 * Save repeater Fields.
	 *
	 * @param string $post_id 
	 * @param string $field 
	 * @param string|mixed $old 
	 * @param string|mixed $new 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public 
	 */
	public function save_field_repeater( $post_id, $field, $old, $new ) {
    	
		if( is_array( $new ) && count( $new ) > 0 ) {
			
			foreach( $new as $n ) {
				foreach ( $field['fields'] as $f ) {
					$type = $f['type'];
					switch( $type ) {
						case 'wysiwyg':
							$n[$f['id']] = wpautop( $n[$f['id']] ); 
							break;
						case 'file':
							$n[$f['id']] = $this->save_field_file_repeater($post_id,$f,'',$n[$f['id']]);
							break;
						default:
							break;
					}
				}
				
				if(!$this->is_array_empty($n)) {
					$temp[] = $n;
				}
			}
			
			if( isset( $temp ) && count( $temp ) > 0 && !$this->is_array_empty( $temp ) ) {
				update_post_meta( $post_id, $field['id'], $temp );
			} else {
				//  remove old meta if exists
				delete_post_meta( $post_id, $field['id'] );
			}
    
		} else {
			//  remove old meta if exists
			delete_post_meta( $post_id, $field['id'] );
		}
	}
  
	/**
	 * Save File Field.
	 *
	 * @param string $post_id 
	 * @param string $field 
	 * @param string $old 
	 * @param string $new 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function save_field_file( $post_id, $field, $old, $new ) {
  
		$name = $field['id'];

		if( empty( $_FILES[$name] ) && !is_array( $old ) ) {
			delete_post_meta( $post_id, $name );
			return;
		}
		
		$temp = get_post_meta( $post_id, $name, true );
		$temp = is_array( $temp ) ? $temp : array();
		$files = $this->fix_file_array( $_FILES[$name] );
		
		foreach ( $files as $fileitem ) {
		
			$file = wp_handle_upload( $fileitem, array( 'test_form' => false ) );
			if ( empty( $file['file'] ) ) {
				continue;
			}
			
			$filename = $file['file'];

			$attachment = array(
				'post_mime_type' => $file['type'],
				'guid' => $file['url'],
				'post_parent' => $post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', basename( $filename ) ),
				'post_content' => ''
			);
		  
			$id = wp_insert_attachment( $attachment, $filename );
		  
			if ( ! is_wp_error( $id ) ) {			
				wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $filename ) );
				$temp[] = $id;  // save file's url in meta fields
		  
			} // End if
		  
		} // End foreach    
		
		if( count( $temp ) > 0 ) {
			update_post_meta( $post_id, $name, $temp );
		}
	}
  
	/**
	 * Save repeater File Field.
	 * @param string $post_id 
	 * @param string $field 
	 * @param string $old 
	 * @param string $new 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function save_field_file_repeater( $post_id, $field, $old, $new ) {
  
		$name = $field['id'];
		if ( empty( $_FILES[$name] ) ) {
			return;
		}
		
		$this->fix_file_array( $_FILES[$name] );
		
		foreach ( $_FILES[$name] as $position => $fileitem ) {		  
			$file = wp_handle_upload( $fileitem, array( 'test_form' => false ) );
		  
			if ( empty( $file['file'] ) ) {
				continue;
			}
			
			$filename = $file['file'];

			$attachment = array(
				'post_mime_type' => $file['type'],
				'guid' => $file['url'],
				'post_parent' => $post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', basename( $filename ) ),
				'post_content' => ''
			);
		  
			$id = wp_insert_attachment( $attachment, $filename, $post_id );
		  
			if ( ! is_wp_error( $id ) ) {
				wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $filename ) );
				return $id;  // return file's url in meta fields
			} // End if
		} // End foreach
	}
  
	/**
	 * Add missed values for meta box.
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function add_missed_values() {
    
		// Default values for meta box
		$this->_meta_box = array_merge( array( 'context' => 'normal', 'priority' => 'high', 'pages' => array( 'post' ) ), (array)$this->_meta_box );

		// Default values for fields
		foreach ( $this->_fields as &$field ) {
			$multiple = in_array( $field['type'], array( 'checkbox_list', 'file', 'image' ) );
			$std = $multiple ? array() : '';
			$format = 'date' == $field['type'] ? 'yy-mm-dd' : ( 'time' == $field['type'] ? 'hh:mm' : '' );
			$field = array_merge( array( 'multiple' => $multiple, 'std' => $std, 'desc' => '', 'format' => $format, 'validate_func' => '' ), $field );
    
		} // End foreach
    }

	/**
	* Check if field with $type exists.
	*
	* @param string $type 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	* @access public
	*/
	public function has_field( $type ) {
	
		//faster search in single dimention array.
		if( count( $this->field_types ) > 0) {
			return in_array( $type, $this->field_types );
		}

		//run once over all fields and store the types in a local array
		$temp = array();
		foreach( $this->_fields as $field ) {
			$temp[] = $field['type'];
			if( 'repeater' == $field['type']  || 'cond' == $field['type'] ) {
        		
				foreach( ( array )$field["fields"] as $repeater_field ) {
					$temp[] = $repeater_field["type"];  
				}
			}
		}

		//remove duplicates
		$this->field_types = array_unique( $temp );
		
		//call this function one more time now that we have an array of field types
		return $this->has_field( $type );
	}

	/**
	 * Check if current page is edit page.
	 *
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function is_edit_page() {
	
		global $pagenow;
		return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
	}
  
	/**
	 * 
	 * @param string $files 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function fix_file_array( &$files ) {
    
		$output = array();
    
		foreach ( $files as $key => $list ) {
			foreach ( $list as $index => $value ) {
				$output[$index][$key] = $value;
			}
		}
    
		return $files = $output;
  
	}

	/**
	 * Get proper JQuery UI version.
	 *
	 * Used in order to not conflict with WP Admin Scripts.
	 *
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function get_jqueryui_ver() {
    
		global $wp_version;
    
		if ( version_compare( $wp_version, '3.1', '>=' ) ) {
			return '1.8.10';
		}
    
		return '1.7.3';
  
	}
  
	/**
	 * Add Field to meta box (generic function)	 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $args mixed|array
	 */
	public function addField($id,$args){
	
		$new_field = array('id'=> $id,'std' => '','desc' => '','style' =>'');
		$new_field = array_merge($new_field, $args);
		$this->_fields[] = $new_field;
	}
  
	/**
	 * Add Text Field to meta box
	 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $args mixed|array
	 *		'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'style' =>   // custom style for field, string optional
	 *    	'validate_func' => // validate function, string optional
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default) 
	 */
	public function addText($id,$args,$repeater=false){
		
		
		$new_field = array( 'type' => 'text','id'=> $id,'std' => '','desc' => '','class' =>'regular-text','style' =>'','name' => 'Text Field' );
		$new_field = array_merge( $new_field, $args );
   		
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
  
	/**
	 * Add Hidden Field to meta box
	 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $args mixed|array
	 * 		'name' => // field name/label string optional
	 *  	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'style' =>   // custom style for field, string optional
	 *    	'validate_func' => // validate function, string optional
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default) 
	 */
	public function addHidden( $id, $args, $repeater=false ) {
    
		$new_field = array( 'type' => 'hidden', 'id'=> $id,'std' => '', 'desc' => '', 'style' =>'', 'name' => 'Text Field' );
		$new_field = array_merge( $new_field, $args );
    
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
  
	/**
	 * Add Paragraph to meta box
	 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $value  paragraph html
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default) 
	 */
	public function addParagraph( $id, $args, $repeater=false ) {

		$new_field = array( 'type' => 'paragraph', 'id'=> $id, 'value' => '' );
		$new_field = array_merge( $new_field, $args );
    
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
    
	/**
	 * Add Checkbox Field to meta box	 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $args mixed|array
	 * 		'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'validate_func' => // validate function, string optional
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default) 
	 */
	public function addCheckbox( $id, $args, $repeater=false ) {
   		
		$new_field = array( 'type' => 'checkbox', 'id'=> $id, 'std' => '', 'desc' => '', 'style' =>'', 'name' => 'Checkbox Field' );
		$new_field = array_merge( $new_field, $args );
    
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}

	/**
	 * Add CheckboxList Field to meta box
	 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $options (array)  array of key => value pairs for select options
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'validate_func' => // validate function, string optional
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default)
	 *  
	 * @return : remember to call: $checkbox_list = get_post_meta(get_the_ID(), 'meta_name', false); 
	 * which means the last param as false to get the values in an array
	 */
	public function addCheckboxList( $id, $options, $args, $repeater=false ) {
    
		$new_field = array( 'type' => 'checkbox_list', 'id'=> $id,'std' => '', 'desc' => '', 'style' =>'', 'name' => 'Checkbox List Field', 'options' =>$options,'multiple' => true );
		$new_field = array_merge( $new_field, $args );
    
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
	
	/**
	 * Add Multiplae textbox Field to meta box	 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $options (array)  array of key => value pairs for select options
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'validate_func' => // validate function, string optional
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default)
	 *  
	 * @return : remember to call: $checkbox_list = get_post_meta(get_the_ID(), 'meta_name', false); 
	 * which means the last param as false to get the values in an array
	 */
	public function addMultipleTextbox( $id, $options, $args, $repeater=false ) {
    
		$new_field = array( 'type' => 'multiple_textbox', 'id'=> $id,'std' => '', 'desc' => '', 'style' =>'', 'name' => 'Multiple textbox field', 'options' =>$options,'multiple' => true );
		
		$new_field = array_merge( $new_field, $args );    
		
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
	
  /**
	 * Add Textarea Field to meta box
	 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'style' =>   // custom style for field, string optional
	 *    	'validate_func' => // validate function, string optional
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default) 
	 */
	public function addCustomRepeater( $id, $options, $args, $repeater=false ) {
    
		$new_field = array( 'type'=>'custom_repeater', 'id'=>$id, 'std'=>'', 'desc'=>'', 'style'=>'', 'name'=>'Custom repeater field', 'options'=>$options, 'multiple' => true );
		
		$new_field = array_merge( $new_field, $args );    
		
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
	/**
	 * Add Textarea Field to meta box
	 
	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'style' =>   // custom style for field, string optional
	 *    	'validate_func' => // validate function, string optional
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default) 
	 */
	public function addTextarea( $id, $args, $repeater=false ) {
    
		$new_field = array( 'type' => 'textarea', 'id'=> $id, 'std' => '', 'desc' => '', 'style' =>'', 'name' => 'Textarea Field' );
		$new_field = array_merge( $new_field, $args );
    
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
  
	/**
	 * Add Select Field to meta box	 
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string field id, i.e. the meta key
	 * @param $options (array)  array of key => value pairs for select options  	
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, (array) optional
	 *    	'multiple' => // select multiple values, optional. Default is false.
	 *    	'validate_func' => // validate function, string optional
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default) 
	 */
	public function addSelect( $id, $options, $args, $repeater=false ) {
    
		$new_field = array( 'type' => 'select', 'id'=> $id, 'std' => array(), 'desc' => '', 'style' =>'', 'name' => 'Select Field', 'multiple' => false, 'options' => $options );
		$new_field = array_merge( $new_field, $args );
    
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}  
  
	/**
	 * Add Radio Field to meta box
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string field id, i.e. the meta key
	 * @param $options (array)  array of key => value pairs for radio options
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
 	 *    	'validate_func' => // validate function, string optional 
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default)
	 */
	public function addRadio( $id, $options, $args, $repeater=false ) {
    
		$new_field = array( 'type' => 'radio', 'id'=> $id, 'std' => array(), 'desc' => '', 'style' =>'', 'name' => 'Radio Field', 'options' => $options );
		$new_field = array_merge( $new_field, $args );
    
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}	
	  
	/**
	 * Add Image Field to meta box
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $args mixed|array
	 *	  	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'validate_func' => // validate function, string optional
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default) 
	 */
	public function addImage( $id, $args, $repeater=false ) {
    
		$new_field = array( 'type' => 'image', 'id'=> $id, 'desc' => '', 'std' => '', 'name' => 'Image Field' );
		$new_field = array_merge($new_field, $args);
    
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
  
	/**
	 * Add File Field to meta box
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'validate_func' => // validate function, string optional 
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default)
	 */
	public function addFile( $id, $args, $repeater=false ) {
		
		$new_field = array( 'type' => 'file', 'id'=> $id, 'desc' => '', 'name' => 'File Field' );
		$new_field = array_merge( $new_field, $args );
		
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}

	/**
	 * Add WYSIWYG Field to meta box
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'style' =>   // custom style for field, string optional Default 'width: 300px; height: 400px'
	 *    	'validate_func' => // validate function, string optional 
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default)
	 */
	public function addWysiwyg( $id, $args ,$repeater=false ) {
		
		$new_field = array( 'type' => 'wysiwyg', 'id'=> $id, 'std' => '', 'desc' => '', 'style' =>'width: 300px; height: 400px', 'name' => 'WYSIWYG Editor Field' );
		$new_field = array_merge( $new_field, $args );
		
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
  
	/**
	 * Add Taxonomy Field to meta box
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $options mixed|array options of taxonomy field
	 *    	'taxonomy' =>    // taxonomy name can be category,post_tag or any custom taxonomy default is category
	 *    	'type' =>  // how to show taxonomy? 'select' (default) or 'checkbox_list'
	 *    	'args' =>  // arguments to query taxonomy, see http://goo.gl/uAANN default ('hide_empty' => false)  
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'validate_func' => // validate function, string optional 
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default)
	 */
	public function addTaxonomy( $id, $options, $args, $repeater=false ) {
		
		$temp = array(
			'args' => array( 'hide_empty' => 0 ),
			'tax' => 'category',
			'type' => 'select'
		);
		
		$options = array_merge( $temp, $options );
		$new_field = array( 'type' => 'taxonomy', 'id'=> $id, 'desc' => '', 'name' => 'Taxonomy Field', 'options'=> $options );
		$new_field = array_merge( $new_field, $args );
		
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}

	/**
	 * Add posts Field to meta box
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $options mixed|array options of taxonomy field
	 *    	'post_type' =>    // post type name, 'post' (default) 'page' or any custom post type
	 *    	'type' =>  // how to show posts? 'select' (default) or 'checkbox_list'
	 *    	'args' =>  // arguments to query posts, see http://goo.gl/is0yK default ('posts_per_page' => -1)  
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'validate_func' => // validate function, string optional 
	 * @param $repeater bool  is this a field inside a repeatr? true|false(default)
	 */
	public function addPosts( $id, $options, $args, $repeater=false ) {
		
		$post_type = isset( $option['post_type'] )? $option['post_type']: ( isset( $args['post_type'] ) ? $args['post_type']: 'post' );
		$q = array( 'posts_per_page' => -1, 'post_type' => $post_type );
		$temp = array( 'post_type' =>$post_type, 'type'=>'select', 'args'=>$q );
		$options = array_merge( $temp, $options );
		$new_field = array( 'type' => 'posts', 'id'=> $id, 'desc' => '', 'name' => 'Posts Field', 'options'=> $options );
		$new_field = array_merge( $new_field, $args );
		
		if( false === $repeater ) {
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}
  
	/**
	 * Add repeater Field Block to meta box	 
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $id string  field id, i.e. the meta key
	 * @param $args mixed|array
	 *    	'name' => // field name/label string optional
	 *    	'desc' => // field description, string optional
	 *    	'std' => // default value, string optional
	 *    	'style' =>   // custom style for field, string optional
	 *    	'validate_func' => // validate function, string optional
	 *    	'fields' => //fields to repeater  
	 */
	public function addRepeaterBlock( $id, $args ) {
	
		$new_field = array( 'type' => 'repeater', 'id'=> $id, 'name' => 'Reapeater Field', 'fields' => array(), 'inline'=> false, 'sortable' => false );
		$new_field = array_merge( $new_field, $args );
		$this->_fields[] = $new_field;
	}  
	
	/**
	 *  Add Checkbox conditional Field to Page	
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 *  @access public
	 *  @param $id string  field id, i.e. the key
	 *  @param $args mixed|array
	 *    'name' => // field name/label string optional
	 *    'desc' => // field description, string optional
	 *    'std' => // default value, string optional
	 *    'validate_func' => // validate function, string optional
	 *    'fields' => list of fields to show conditionally.
	 *  @param $repeater bool  is this a field inside a repeatr? true|false(default) 
	*/
	public function addCondition( $id, $args, $repeater=false ) {
	
		$new_field = array( 'type' => 'cond', 'id'=> $id, 'std' => '', 'desc' => '', 'style' =>'', 'name' => 'Conditional Field', 'fields' => array() );
		$new_field = array_merge( $new_field, $args );
		
		if( false === $repeater ){
			$this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
  }
  
	/**
	 * Finish Declaration of Meta Box
	 
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 */
	public function Finish() {
		
		$this->add_missed_values();
	}
  
	/**
	 * Helper function to check for empty arrays
	 
 	 * 
	 * @package Offer Calc
 	 * @since 1.0.0
 	 * 
	 * @access public
	 * @param $args mixed|array
	 */
	public function is_array_empty( $array ) {
		
		if( !is_array( $array ) ) {
			return true;
		}
		
		foreach( $array as $a ) {
			if( is_array( $a ) ) {
				foreach( $a as $sub_a ) {
					if( !empty( $sub_a ) && $sub_a != '' ) {
						return false;
					}
				}
			} else {
				if( !empty( $a ) && $a != '' ) {
					return false;
				}
			}
		}
		
		return true;
	}
} // End Class

endif; // End Check Class Exists