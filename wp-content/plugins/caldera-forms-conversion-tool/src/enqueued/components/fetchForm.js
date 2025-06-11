import apiFetch from '@wordpress/api-fetch';
import PropTypes from 'prop-types';

export const fetchForm = async ( props ) => {
	apiFetch.use(
		apiFetch.createNonceMiddleware( cf_conversion_tool_vars.nonce )
	);

	try {
		return await apiFetch( {
			url:
				window.location.href +
				'&format=json&export-form=' +
				props.formID,
			signal: props.signal,
		} );
	} catch ( e ) {
		console.log( e );
	}
};

fetchForm.propTypes = {
	formID: PropTypes.string.isRequired,
	signal: PropTypes.object.isRequired,
};
