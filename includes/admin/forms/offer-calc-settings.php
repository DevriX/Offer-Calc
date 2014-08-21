<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Offer Calc settings
 * 
 * @package Offer Calc
 * @since 1.0.0
 */
	
$offer_lable = __( 'Migrate Offer', 'offercalc' );
$migrate_btn = __( 'Migrate', 'offercalc' );
$delete_btn  = __( 'Delete Old Data', 'offercalc' );
?>
<div class="wrap">
	<?php echo screen_icon( 'options-general' );?>
	<h2><?php _e( $offer_lable , 'offercalc' );?></h2>
	<!-- beginning of the offer meta box -->
	<div id="offer-calc-offer" class="post-box-container">
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
				<div id="offer" class="postbox">
					<!-- offer box title -->
					<h3 class="hndle">
						<?php _e( $offer_lable, 'offercalc' );?>
					</h3>
					<div class="inside">						
						<form action="" method="POST" id="offer-calc-add-edit-form">
							<?php if( get_option('ofc_version') < 1 ) { ?>
								<p class="description"><?php _e( 'Click the button to activate the new version. This will migrate your existing data to the new scheme.', 'offercalc' );?></p>
								<input type="submit" class="button-primary margin_button" name="offer_calc_form_migrate" id="offer_calc_form_migrate" value="<?php echo $migrate_btn;?>" />
							<?php } elseif( get_option('ofc_migration_done') == false ) { ?>
								<p class="description"><?php _e( 'This action will delete permanently the old tables from the database', 'offercalc' );?></p>
								<input type="submit" class="button-primary margin_button" name="offer_calc_form_delete" id="offer_calc_form_delete" value="<?php echo $delete_btn;?>" />
							<?php 
							}	
							?>	
						</form>						
					</div><!-- .inside -->
				</div><!-- #offer -->
			</div><!-- .meta-box-sortables ui-sortable -->	
		</div><!-- .metabox-holder -->
	</div><!-- #wps-offer-general -->
	<!-- end of the offer meta box -->
</div><!-- .wrap -->