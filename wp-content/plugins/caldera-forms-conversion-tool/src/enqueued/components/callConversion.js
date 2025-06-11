import apiFetch from '@wordpress/api-fetch';
import PropTypes from 'prop-types';

export const callConversion = async ( props ) => {
	apiFetch.use(
		apiFetch.createNonceMiddleware( cf_conversion_tool_vars.rest_nonce )
	);

	try {
		return await apiFetch( {
			path: '/nf-formbuilder/convert-cf',
			method: 'POST',
			data: {
				form: props.cfForm,
			},
			signal: props.signal,
		} );
	} catch ( e ) {
		console.log( e );
	}
};

callConversion.propTypes = {
	cfForm: PropTypes.object.isRequired,
	signal: PropTypes.object.isRequired,
};
