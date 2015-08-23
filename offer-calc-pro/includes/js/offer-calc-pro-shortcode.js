// JavaScript Document
jQuery(document).ready(function($) {

/*$( '#frm_offer_short' ).validate();
$( '#frm_offer_widget' ).validate();*/
// Start Shortcodes Click
(function() {
    tinymce.create( 'tinymce.plugins.offercalcshortcodes', {
    	
        init : function(ed, url) {        	
            ed.addButton( 'offercalcshortcodes', {
                title : 'Add Offer ShortCode',
                image : url+'/images/offer-calc-shortcode.png',
                onclick : function() {
                    
					jQuery( '.offer-calc-popup-overlay' ).fadeIn();
                    jQuery( '.offer-calc-popup-content' ).fadeIn();                                        
                    jQuery( '#offer_calc_shortcode' ).val('');
                    
 				}
            });
        },
        createControl : function(n, cm) {        	
            return null;
        },
    });
 
    tinymce.PluginManager.add('offercalcshortcodes', tinymce.plugins.offercalcshortcodes);
})();
	
	jQuery( document ).on('click', '.offer-calc-popup-close-button, .offer-calc-popup-overlay', function () {
		jQuery('.offer-calc-popup-overlay').fadeOut();
		jQuery('.offer-calc-popup-content').fadeOut();
	});
	
	jQuery( document ).on('click', '#offer_calc_insert_shortcode', function () {
		
		var shortcode = jQuery('#offer_calc_select_shortcode').val();
		var shortcodestr = '';	
		if(shortcode == '') {
			jQuery('.offer-calc-popup-error').fadeIn();
			return false;
		} else {
			jQuery('.offer-calc-popup-error').hide();
				
					shortcodestr += '[ofc_shortcode offer_slug="'+shortcode+'"]';				
										
				}
			 	
		 	 //send_to_editor(str);
	        tinymce.get('content').execCommand('mceInsertContent',false, shortcodestr);
	  		jQuery('.offer-calc-popup-overlay').fadeOut();
			jQuery('.offer-calc-popup-content').fadeOut();
	});

});