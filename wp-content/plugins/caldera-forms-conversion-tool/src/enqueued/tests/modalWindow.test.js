import { render, fireEvent, screen } from '@testing-library/react';
import { withGlobal, cf_conversion_tool_vars } from './mockData';
import { ModalWindow } from '../components/';

describe( 'ModalWindow', () => {
	it( 'Matches snapshot', () => {
		//Start render with global wrappers
		withGlobal( 'cf_conversion_tool_vars', cf_conversion_tool_vars, () => {
			const { container } = render( <ModalWindow formID="cf-1" form={ cf_conversion_tool_vars.forms["cf-1"] }/> );
			expect( container ).toMatchSnapshot();
		} );
	} );

	it( 'Opens modal with correct CF ID', () => {
		//Start render with global wrappers
		withGlobal( 'cf_conversion_tool_vars', cf_conversion_tool_vars, () => {
			const { container } = render( <ModalWindow formID="cf-2" form={ cf_conversion_tool_vars.forms["cf-2"] } /> );

			fireEvent.click( screen.getByText( 'Convert to Ninja Forms' ) );
			expect(
				screen.getByText( 'Convert form cf-2 into a Ninja Forms Form.' )
			).toBeDefined();
		} );
	} );
} );
