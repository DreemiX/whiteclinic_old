import { withGlobal, cf_conversion_tool_vars, cf_form } from './mockData';
import { fetchForm } from '../components/';

describe( 'fetch CF Form', () => {
	it( 'should return status code 200 and a defined body as response', () => {
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
								data: cf_form,
							} ),
					} )
				);

				const result = await fetchForm();

				expect( result.status ).toBe( 200 );
				expect( result.data ).toBe( cf_form );
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

				const result = await fetchForm();

				expect( result.status ).toBe( 500 );
				expect( result.data ).not.toBeDefined();
			}
		);
	} );
} );
