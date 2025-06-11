import { render, createElement } from '@wordpress/element';
import { ModalWindow } from './components/';

jQuery( document ).ready( function () {
	//Loop through current forms on the CF left admin panel
	jQuery(
		'#caldera-forms-admin-page-left .form_entry_row .row-actions'
	).each( function () {
		/**
		 * Extract Form ID from parent element and remove prefix "form_row_"
		 */
		const formId = jQuery( this )
			.parents( '.form_entry_row' )
			.attr( 'id' )
			.replace( 'form_row_', '' );
		/**
		 * Display a link for each Form
		 */
		const span = '<span></span>';
		const spanClass = jQuery( span ).addClass( 'export-to-nf' );
		const linkToNF = jQuery( spanClass )
			.attr( 'data-formid', formId )
			.attr( 'id', 'CF-' + formId + '-toNF' );
		jQuery( this ).append( linkToNF );
		
		//Set Form props for each conversion element
		const forms = cf_conversion_tool_vars.cf_forms ? cf_conversion_tool_vars.cf_forms : false;
		const form = forms[formId] ? forms[formId] : false;

		/**
		 * Init components
		 */
		if ( document.getElementById( 'CF-' + formId + '-toNF' ) !== null ) {
			const modalWindow = createElement( ModalWindow, {
				key: formId,
				formID: formId,
				form: form
			} );
			render(
				modalWindow,
				document.getElementById( 'CF-' + formId + '-toNF' )
			);
		}
	} );
} );
