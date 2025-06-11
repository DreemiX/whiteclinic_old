import {
	withGlobal,
	cf_conversion_tool_vars,
	download_nff_file_response,
} from './mockData';
import { callConversion } from '../components/';

describe( 'Call Conversion', () => {
	it( 'should return status code 200 and a defined body as response for download file action', () => {
		withGlobal(
			'cf_conversion_tool_vars',
			cf_conversion_tool_vars,
			async () => {
				// Mock API
				jest.spyOn( global, 'fetch' ).mockImplementation( () =>
					Promise.resolve( {
						json: () =>
							Promise.resolve( {
								status: 200,
								data: download_nff_file_response,
							} ),
					} )
				);

				const result = await callConversion();

				expect( result.status ).toBe( 200 );
				expect( result.data ).toBe( download_nff_file_response );
			}
		);
	} );

	it( 'should return status code 200 and a defined body as response for creat Ninja Forms form action', () => {
		withGlobal(
			'cf_conversion_tool_vars',
			cf_conversion_tool_vars,
			async () => {
				// Mock API
				jest.spyOn( global, 'fetch' ).mockImplementation( () =>
					Promise.resolve( {
						json: () =>
							Promise.resolve( {
								status: 200,
								data: create_nf_form_response,
							} ),
					} )
				);

				const result = await callConversion();

				expect( result.status ).toBe( 200 );
				expect( result.data ).toBe( create_nf_form_response );
			}
		);
	} );

	it( 'should catch error', () => {
		withGlobal(
			'cf_conversion_tool_vars',
			cf_conversion_tool_vars,
			async () => {
				// Mock API
				jest.spyOn( global, 'fetch' ).mockImplementation( () =>
					Promise.resolve( {
						json: () =>
							Promise.resolve( {
								status: 500,
							} ),
					} )
				);

				const result = await callConversion();

				expect( result.status ).toBe( 500 );
				expect( result.data ).not.toBeDefined();
			}
		);
	} );
} );
