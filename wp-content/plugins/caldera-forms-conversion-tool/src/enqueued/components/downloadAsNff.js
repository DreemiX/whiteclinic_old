import PropTypes from 'prop-types';

export const downloadAsNff = async ( props ) => {
	let result;
	try {
		const { formJson, exportName } = props;

		const dataStr =
			'data:text/json;charset=utf-8,' + encodeURIComponent( formJson );
		const downloadAnchorNode = document.createElement( 'a' );
		downloadAnchorNode.setAttribute( 'href', dataStr );
		downloadAnchorNode.setAttribute( 'download', exportName + '.nff' );
		document.body.appendChild( downloadAnchorNode );
		downloadAnchorNode.click();
		downloadAnchorNode.remove();

		result = 'success';
	} catch ( e ) {
		result = e;
	}

	return result;
};

downloadAsNff.propTypes = {
	formJson: PropTypes.object.isRequired,
	exportName: PropTypes.string.isRequired,
};
