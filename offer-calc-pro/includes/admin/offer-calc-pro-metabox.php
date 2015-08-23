<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Autoresponder metabox
 *
 * This is the code for the autoresponder metabox.
 *
 * @package Offer Calc Pro
 * @since 1.0.0
 */

global $post_id;
$prefix = OFFER_CALC_PRO_META_PREFIX;

do_action( 'offer_calc_pro_before_table' );

?>
				
<table class="offer-calc-pro-autoresponder-table">
	<tbody>
		<tr>
			<td class="offer-calc-pro-autoresponder-valign">
				<label><strong><?php _e( 'Autoresponder', 'offercalc' ); ?></strong></label>
			</td>
			<td>
				<?php do_action( 'offer_calc_pro_img_show' ); ?>															
			</td>
		</tr>							
	</tbody>
</table>

<?php do_action( 'offer_calc_pro_after_table' ); ?>