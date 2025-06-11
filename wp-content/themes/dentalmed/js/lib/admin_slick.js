jQuery(document).ready( function() {
	"use strict";
    function _hide ( i, el, child ) {
	
		var parent, input, label, t;
        if (!jQuery(el).is(':checked')) {
		    parent = jQuery(el).parents('.widgets-holder-wrap');
			input = jQuery(parent).find('input[name*="' + child + '"]');
			label = jQuery(parent).find('label[for*="' + child + '"]');
			jQuery(input).css( { 'display' : 'none' } );
            jQuery(label).css( { 'display' : 'none' } );			
		}	
	
	}
	
	function _handler(parent, child) {
	
        jQuery('.widgets-holder-wrap input[name$="' + parent + ']"]').on('change', function(e) {
	        if (jQuery(e.target).is(":checked")) {
		        jQuery('.widgets-holder-wrap input[name*="' + child + '"]').css( { 'display' : 'block' } );
				jQuery('.widgets-holder-wrap label[for*="' + child + '"]').css( { 'display' : 'block' } );
		    }
		    else {
		        jQuery('.widgets-holder-wrap input[name*="' + child + '"]').css( { 'display' : 'none' } );
				jQuery('.widgets-holder-wrap label[for*="' + child + '"]').css( { 'display' : 'none' } );
		    }
	    } );	
	
	}
	
	jQuery(document).on('widget-updated widget-added', function (e, widget) {
	    var input = jQuery(widget).find('input[name$="autoplay]"]');
	    _hide(e, input, 'autoplaySpeed');
		_handler('autoplay', 'autoplaySpeed');
	});

	_handler('autoplay', 'autoplaySpeed');
	
    jQuery('.widgets-holder-wrap input[name$="autoplay]"]').each(
	    function(i, el) {
            _hide(i, el, 'autoplaySpeed');
		}
	);	

} );