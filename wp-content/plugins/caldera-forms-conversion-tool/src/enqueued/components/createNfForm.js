import PropTypes from 'prop-types';
import { Buffer } from 'buffer';

export const createNfForm = async ( props ) => {
	try {
		const { formJson, exportName, handleCreationProcess } = props;
		const data = ';base64,' + Buffer.from( formJson ).toString( 'base64' );

		const formData = {
			name: exportName + '.nff',
			content: data,
		};

		const postData = {
			action: 'nf_batch_process',
			batch_type: 'import_form',
			security: cf_conversion_tool_vars.nf_security_nonce,
			extraData: formData,
		};

		await jQuery.post( ajaxurl, postData ).then( ( response ) => {
			response = JSON.parse( response );
			handleCreationProcess( response );
		} );
	} catch ( e ) {
		console.log( e );
		return e;
	}
};

createNfForm.propTypes = {
	formJson: PropTypes.object.isRequired,
	exportName: PropTypes.string.isRequired,
	signal: PropTypes.object.isRequired,
	handleCreationProcess: PropTypes.func.isRequired,
};
