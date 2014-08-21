/**
 * All Types Meta Box Class JS
 *
 * JS used for the custom metaboxes and other form items.
 *
 * Copyright 2011 Ohad Raz (admin@bainternet.info)
 * @since 1.0
 */

//var jQuery =jQuery.noConflict();
function update_repeater_fields(){
    
      
    /**
     * Datepicker Field.
     *
     * @since 1.0
     */
    jQuery('.wpd-mb-meta-date').each( function() {
      
      var jQuerythis  = jQuery(this),
          format = jQuerythis.attr('rel');
  
      jQuerythis.datepicker( { showButtonPanel: true, dateFormat: format } );
      
    });
  	jQuery('.wpd-mb-meta-datetime').each( function() {
      
      var jQuerythis  = jQuery(this),
          format = jQuerythis.attr('rel');
      jQuerythis.datetimepicker({ampm: true,dateFormat : format});//
      
    });
    /**
     * Timepicker Field.
     *
     * @since 1.0
     */
    jQuery('.wpd-mb-meta-time').each( function() {
      
      var jQuerythis   = jQuery(this),
          format   = jQuerythis.attr('rel'),
          aampm    = jQuerythis.attr('data-ampm');
      if ('true' == aampm)
        aampm = true;
      else
        aampm = false;

      jQuerythis.timepicker( { showSecond: true, timeFormat: format, ampm: aampm } );
      
    });
  
    /**
     * Colorpicker Field.
     *
     * @since 1.0
     */
    /*
    
    
    
    /**
     * Select Color Field.
     *
     * @since 1.0
     */
    jQuery('.wpd-mb-meta-color-select').click( function(){
      var jQuerythis = jQuery(this);
      var id = jQuerythis.attr('rel');
      jQuery(this).siblings('.wpd-mb-meta-color-picker').farbtastic("#" + id).toggle();
      return false;
    });
  
    /**
     * Add Files.
     *
     * @since 1.0
     */
    jQuery('.wpd-mb-meta-add-file').click( function() {
      var jQueryfirst = jQuery(this).parent().find('.file-input:first');
      jQueryfirst.clone().insertAfter(jQueryfirst).show();
      return false;
    });
  
    /**
     * Delete File.
     *
     * @since 1.0
     */
  	jQuery( document ).on('click', ".wpd-mb-meta-upload .wpd-mb-meta-delete-file", function(e) {
      
      var jQuerythis   = jQuery(this),
          jQueryparent = jQuerythis.parent(),
          data     = jQuerythis.attr('rel');
          
      jQuery.post( ajaxurl, { action: 'at_delete_file', data: data }, function(response) {
        response == '0' ? ( alert( 'File has been successfully deleted.' ), jQueryparent.remove() ) : alert( 'You do NOT have permission to delete this file.' );
      });
      
      return false;
    
    });
  
    /**
     * Reorder Images.
     *
     * @since 1.0
     */
    jQuery('.wpd-mb-meta-images').each( function() {
      
      var jQuerythis = jQuery(this), order, data;
      
      jQuerythis.sortable( {
        placeholder: 'ui-state-highlight',
        update: function (){
          order = jQuerythis.sortable('serialize');
          data   = order + '|' + jQuerythis.siblings('.wpd-mb-meta-images-data').val();
  
          jQuery.post(ajaxurl, {action: 'at_reorder_images', data: data}, function(response){
            response == '0' ? alert( 'Order saved!' ) : alert( "You don't have permission to reorder images." );
          });
        }
      });
      
    });
    
    /**
     * repeater sortable
     * @since 2.1
     */
    jQuery('.repeater-sortable').sortable();
	
	/**
     * enable select2
     */
    fancySelect();
  
  }
var Ed_array = Array;
jQuery(document).ready(function(jQuery) {

	/**
     * DateTimepicker Field.
     *
     * @since 1.0
     */
 	 
    jQuery('.wpd-mb-meta-datetime').each( function() {
      
      var jQuerythis  = jQuery(this),
          format = jQuerythis.attr('rel');
  		
      jQuerythis.datetimepicker({ampm: true,dateFormat : format});//,timeFormat:'hh:mm:ss',showSecond:true
      
    });
  /**
   *  conditinal fields
   *  @since 2.9.9
   */
  jQuery(".conditinal_control").click(function(){
    if(jQuery(this).is(':checked')){
      jQuery(this).next().show('fast');    
    }else{
      jQuery(this).next().hide('fast');    
    }
  });

  /**
   * enable select2
   * @since 2.9.8
   */
  fancySelect();

  /**
   * repeater sortable
   * @since 2.1
   */
  jQuery('.repeater-sortable').sortable(); 
  
  /**
   * repater Field
   * @since 1.1
   */
  //edit
  
 
  
  jQuery( document ).on('click', ".wpd-mb-meta-re-toggle", function() {
   //jQuery(this).prev().toggle('slow');
   jQuery(this).siblings("table .repeater-table").toggle('slow');
  });
  
  
  
  /**
   * Datepicker Field.
   *
   * @since 1.0
   */
  jQuery('.wpd-mb-meta-date').each( function() {
    
    var jQuerythis  = jQuery(this),
        format = jQuerythis.attr('rel');

    jQuerythis.datepicker( { showButtonPanel: true, dateFormat: format } );
    
  });

  /**
   * Timepicker Field.
   *
   * @since 1.0
   */
  jQuery('.wpd-mb-meta-time').each( function() {
    
    var jQuerythis   = jQuery(this),
          format   = jQuerythis.attr('rel'),
          aampm    = jQuerythis.attr('data-ampm');
      if ('true' == aampm)
        aampm = true;
      else
        aampm = false;

      jQuerythis.timepicker( { showSecond: true, timeFormat: format, ampm: aampm } );
    
  });

  /**
   * Colorpicker Field.
   *
   * @since 1.0
   * better handler for color picker with repeater fields support
   * which now works both when button is clicked and when field gains focus.
   */
  if (jQuery.farbtastic){//since WordPress 3.5
  	jQuery( document ).on('focus', ".wpd-mb-meta-color", function() {
      load_colorPicker(jQuery(this).next());
    });

    jQuery( document ).on('focusout', ".wpd-mb-meta-color", function() {
      hide_colorPicker(jQuery(this).next());
    });

    /**
     * Select Color Field.
     *
     * @since 1.0
     */
    jQuery( document ).on('click', ".wpd-mb-meta-color-select", function() {
      if (jQuery(this).next('div').css('display') == 'none')
        load_colorPicker(jQuery(this));
      else
        hide_colorPicker(jQuery(this));
    });

    function load_colorPicker(ele){
      colorPicker = jQuery(ele).next('div');
      input = jQuery(ele).prev('input');

      jQuery.farbtastic(jQuery(colorPicker), function(a) { jQuery(input).val(a).css('background', a); });

      colorPicker.show();
      //e.preventDefault();

      //jQuery(document).mousedown( function() { jQuery(colorPicker).hide(); });
    }

    function hide_colorPicker(ele){
      colorPicker = jQuery(ele).next('div');
      jQuery(colorPicker).hide();
    }
    //issue #15
    jQuery('.wpd-mb-meta-color').each(function(){
      var colo = jQuery(this).val();
      if (colo.length == 7)
        jQuery(this).css('background',colo);
    });
  }else{
    //jQuery('.wpd-mb-meta-color-iris').wpColorPicker();
  }
  
  /**
   * Add Files.
   *
   * @since 1.0
   */
  jQuery('.wpd-mb-meta-add-file').click( function() {
    var jQueryfirst = jQuery(this).parent().find('.file-input:first');
    jQueryfirst.clone().insertAfter(jQueryfirst).show();
    return false;
  });

  /**
   * Delete File.
   *
   * @since 1.0
   */
  jQuery( document ).on('click', ".wpd-mb-meta-upload .wpd-mb-meta-delete-file", function(e) {
    
    var jQuerythis   = jQuery(this),
        jQueryparent = jQuerythis.parent(),
        data = jQuerythis.attr('rel');
    
    var ind = jQuery(this).index()
    jQuery.post( ajaxurl, { action: 'atm_delete_file', data: data, tag_id: jQuery('#post_ID').val() }, function(response) {
      response == '0' ? ( alert( 'File has been successfully deleted.' ), jQueryparent.remove() ) : alert( 'You do NOT have permission to delete this file.' );
    });
    
    return false;
  
  });

    
  /**
   * Thickbox Upload
   *
   * @since 1.0
   */
  jQuery('.wpd-mb-meta-upload-button').click( function() {
    
    var imgfield,showfield;
	imgfield = jQuery(this).prev('input').attr('id');
	showfield = jQuery(this).parents('td').find('.wpd-mb-img-view');
	 
	if(typeof wp == "undefined" || WpdMbSettings.new_media_ui != '1' ){// check for media uploader
			
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    	
		window.original_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html) {
			
			if(imgfield)  {
				
				var mediaurl = jQuery('img',html).attr('src');
				jQuery('#'+imgfield).val(mediaurl);
				showfield.html('<img src="'+mediaurl+'" />');
				tb_remove();
				imgfield = '';
				
			} else {
				
				window.original_send_to_editor(html);
				
			}
		};
    	return false;
		
	      
	} else {
		
		var file_frame;
		//window.formfield = '';
		
		//new media uploader
		var button = jQuery(this);

		//window.formfield = jQuery(this).closest('.file-input-advanced');
	
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
			file_frame.open();
		  return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			frame: 'post',
			state: 'insert',
			//title: button.data( 'uploader_title' ),
			/*button: {
				text: button.data( 'uploader_button_text' ),
			},*/
			multiple: false  // Set to true to allow multiple files to be selected
		});

		file_frame.on( 'menu:render:default', function(view) {
	        // Store our views in an object.
	        var views = {};

	        // Unset default menu items
	        view.unset('library-separator');
	        view.unset('gallery');
	        view.unset('featured-image');
	        view.unset('embed');

	        // Initialize the views in our view object.
	        view.set(views);
	    });

		// When an image is selected, run a callback.
		file_frame.on( 'insert', function() {

			// Get selected size from media uploader
			var selected_size = jQuery('.attachment-display-settings .size').val();
			
			var selection = file_frame.state().get('selection');
			selection.each( function( attachment, index ) {
				attachment = attachment.toJSON();
				
				// Selected attachment url from media uploader
				var attachment_url = attachment.sizes[selected_size].url;
				
				if(index == 0){
					// place first attachment in field
					jQuery('#'+imgfield).val(attachment_url);
					showfield.html('<img src="'+attachment_url+'" />');
					
				} else{
					jQuery('#'+imgfield).val(attachment_url);
					showfield.html('<img src="'+attachment_url+'" />');
				}
			});
		});

		// Finally, open the modal
		file_frame.open();
		
	}
  });

    
  /**
   * Helper Function
   *
   * Get Query string value by name.
   *
   * @since 1.0
   */
  function get_query_var( name ) {

    var match = RegExp('[?&]' + name + '=([^&#]*)').exec(location.href);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
      
  }
  
  //new image upload field
  function wpd_mb_load_images_muploader(){
    jQuery(".mupload_img_holder").each(function(i,v){
      if (jQuery(this).next().next().val() != ''){
        if (!jQuery(this).children().size() > 0){
          jQuery(this).append('<img src="' + jQuery(this).next().next().val() + '" style="height: 150px;width: 150px;" />');
          jQuery(this).next().next().next().val("Delete Image");
          jQuery(this).next().next().next().removeClass('wpd-mb-meta-upload_image_button').addClass('wpd-mb-meta-delete_image_button');
        }
      }
    });
  }
  
  wpd_mb_load_images_muploader();
  //delete img button
  jQuery( document ).on('click', ".wpd-mb-meta-delete_image_button", function(e) {
  	jQuery(this).prev().val('');
  	jQuery(this).prev().prev().val('');
  	jQuery(this).prev().prev().prev().html('');
  	jQuery(this).val("Upload Image");
    jQuery(this).removeClass('wpd-mb-meta-delete_image_button').addClass('wpd-mb-meta-upload_image_button');
  });
  /*jQuery( document ).on('click', ".wpd-mb-meta-delete_image_button", function(e) {
    var field_id = jQuery(this).attr("rel");
    var at_id = jQuery(this).prev().prev();
    var at_src = jQuery(this).prev();
    var t_button = jQuery(this);
    data = {
        action: 'at_delete_mupload',
        _wpnonce: jQuery('#nonce-delete-mupload_' + field_id).val(),
        post_id: jQuery('#post_ID').val(),
        field_id: field_id,
        attachment_id: jQuery(at_id).val()
    };
  
    jQuery.getJSON(ajaxurl, data, function(response) {
      if ('success' == response.status){
        jQuery(t_button).val("Upload Image");
        jQuery(t_button).removeClass('wpd-mb-meta-delete_image_button').addClass('wpd-mb-meta-upload_image_button');
        //clear html values
        jQuery(at_id).val('');
        jQuery(at_src).val('');
        jQuery(at_id).prev().html('');
        wpd_mb_load_images_muploader();
      }else{
        alert(response.message);
      }
    });
  
    return false;
  });*/
  
  

  //upload button
    var formfield1;
    var formfield2;
  	jQuery( document ).on('click', ".wpd-mb-meta-upload_image_button", function(e) {
      /*formfield1 = jQuery(this).prev();
      formfield2 = jQuery(this).prev().prev();      
      tb_show('', 'media-upload.php?post_id='+ jQuery('#post_ID').val() + '&type=image&amp;TB_iframe=true');
      //store old send to editor function
      window.restore_send_to_editor = window.send_to_editor;
      //overwrite send to editor function
      window.send_to_editor = function(html) {
        imgurl = jQuery('img',html).attr('src');
        img_calsses = jQuery('img',html).attr('class').split(" ");
        att_id = '';
        jQuery.each(img_calsses,function(i,val){
          if (val.indexOf("wp-image") != -1){
            att_id = val.replace('wp-image-', "");
          }
        });

        jQuery(formfield2).val(att_id);
        jQuery(formfield1).val(imgurl);
        load_images_muploader();
        tb_remove();
        //restore old send to editor function
        window.send_to_editor = window.restore_send_to_editor;
      }
      return false;*/
            
		e.preventDefault();
		formfield1 = jQuery(this).prev();
		formfield2 = jQuery(this).prev().prev();
		var button = jQuery(this);
			
		if(typeof wp == "undefined" || WpdMbSettings.new_media_ui != '1' ){// check for media uploader//
			 
			  tb_show('', 'media-upload.php?post_id='+ jQuery('#post_ID').val() + '&type=image&amp;TB_iframe=true');
		      //store old send to editor function
		      window.restore_send_to_editor = window.send_to_editor;
		      //overwrite send to editor function
		      window.send_to_editor = function(html) {
		      	
		        imgurl = jQuery('img',html).attr('src');
		        
		        if(jQuery('img',html).attr('class')) {
		        	
			        img_calsses = jQuery('img',html).attr('class').split(" ");
			        att_id = '';
			        jQuery.each(img_calsses,function(i,val){
			          if (val.indexOf("wp-image") != -1){
			            att_id = val.replace('wp-image-', "");
			          }
			        });
			
			        jQuery(formfield2).val(att_id);
		        }
		        
		        jQuery(formfield1).val(imgurl);
		        wpd_mb_load_images_muploader();
		        tb_remove();
		        //restore old send to editor function
		        window.send_to_editor = window.restore_send_to_editor;
		      }
		      return false;
		      
		} else {
			
			
			var file_frame;
			
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
				file_frame.open();
			  return;
			}
	
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				title: button.data( 'uploader_title' ),
				button: {
					text: button.data( 'uploader_button_text' ),
				},
				multiple: true  // Set to true to allow multiple files to be selected
			});
	
			file_frame.on( 'menu:render:default', function(view) {
		        // Store our views in an object.
		        var views = {};
	
		        // Unset default menu items
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');
	
		        // Initialize the views in our view object.
		        view.set(views);
		    });
	
			// When an image is selected, run a callback.
			file_frame.on( 'insert', function() {
	
				// Get selected size from media uploader
				var selected_size = jQuery('.attachment-display-settings .size').val();
				
				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					
					// Selected attachment url from media uploader
					var attachment_url = attachment.sizes[selected_size].url;
					
					if(index == 0){
						// place first attachment in field
						jQuery(formfield2).val(attachment.id);
	        			jQuery(formfield1).val(attachment_url);
	        			wpd_mb_load_images_muploader();
					
					} else{
						
						jQuery(formfield2).val(attachment.id);
	        			jQuery(formfield1).val(attachment_url);
	        			wpd_mb_load_images_muploader();
					}
					
				});
			});
	
			// Finally, open the modal
			file_frame.open();
		}
		
    });
    
  //editor rezise fix
  jQuery(window).resize(function() {
    jQuery.each(Ed_array, function() {
      var ee = this;
      jQuery(ee.getScrollerElement()).width(100); // set this low enough
      width = jQuery(ee.getScrollerElement()).parent().width();
      jQuery(ee.getScrollerElement()).width(width); // set it to
      ee.refresh();
    });
  });
});

/**
 * Select 2 enable function
 * @since 2.9.8
 */
function fancySelect(){
  jQuery("select").each(function (){
    if(! jQuery(this).hasClass('no-fancy'))
      jQuery(this).chosen();
  });
}