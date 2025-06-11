import { Component } from '@wordpress/element';
import PropTypes from 'prop-types';
import { __ } from '@wordpress/i18n';
import { Button, Modal, Spinner } from '@wordpress/components';
import { fetchForm, callConversion, downloadAsNff, createNfForm } from './';

export class ModalWindow extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			modalOpen: false,
			spinnerOn: false,
			createForm: false,
			downloadFile: false,
			getCfFormStep: false,
			getNfFormTranslation: false,
			getNfFormCreationProcess: {},
			getFormResponse: {},
			fetchController: new AbortController(),
		};

		this.handleModal = this.handleModal.bind( this );
		this.conversionAction = this.conversionAction.bind( this );
		this.handleSpinner = this.handleSpinner.bind( this );
		this.handleGetCfFormStep = this.handleGetCfFormStep.bind( this );
		this.handleGetNfFormTranslation = this.handleGetNfFormTranslation.bind(
			this
		);
		this.handleGetNfFormCreationProcess = this.handleGetNfFormCreationProcess.bind(
			this
		);
		this.handleGetFormResponse = this.handleGetFormResponse.bind( this );
		this.cancelAction = this.cancelAction.bind( this );
		this.handleFetchController = this.handleFetchController.bind( this );
	}

	componentWillUnmount() {
		//Make sure we cancel fetch Job when Component gets umounted
		this.state.fetchController.abort();
	}
	//Modal state process
	handleModal() {
		//Refresh response when opening and closing the Modal
		this.handleGetFormResponse( {} );
		this.setState( { modalOpen: ! this.state.modalOpen } );
	}
	//Spinner state process
	handleSpinner( method ) {
		if ( typeof method === 'undefined' ) {
			this.setState( { spinnerOn: ! this.state.spinnerOn } );
		} else {
			let spinner, create, download;
			if ( method === 'off' ) {
				spinner = false;
				create = false;
				download = false;
			} else if ( method === 'create_form' ) {
				spinner = true;
				create = true;
				download = false;
			} else if ( method === 'download_file' ) {
				spinner = true;
				create = false;
				download = true;
			}

			this.setState( {
				spinnerOn: spinner,
				createForm: create,
				downloadFile: download,
			} );
		}
	}
	/**
	 * Sets data form first step of action when CF Form is being retrieved
	 * @param string data message to display during first step of process ( processing/done... )
	 */
	handleGetCfFormStep( data ) {
		this.setState( { getCfFormStep: data } );
	}
	/**
	 * Sets data form second step of action when CF Form is being translated
	 *  @param string data message to display during second step of process ( processing/done... )
	 */
	handleGetNfFormTranslation( data ) {
		this.setState( { getNfFormTranslation: data } );
	}
	/**
	 * Display Message, success  or error
	 * @param response is an object and needs to hold a message entry for it to be printed on the interface
	 */
	handleGetFormResponse( response ) {
		this.setState( { getFormResponse: response } );
	}
	/**
	 * Sets data from Third step step of action when NF Form is being created / downloaded
	 *  @param string data message to display during second step of process ( processing/done... )
	 */
	handleGetNfFormCreationProcess( data ) {
		this.setState( { getNfFormCreationProcess: data } );
	}
	/**
	 * Trigger fetch CF JSON and call translation to NFF
	 * @param string method is "download_file" or "create_form"
	 */
	async conversionAction( method ) {
		//Prepare Component's state
		this.handleGetFormResponse( {} );
		this.handleGetNfFormTranslation( false );
		this.handleGetCfFormStep( false );
		this.handleGetNfFormCreationProcess( {} );
		this.handleSpinner( method );
		//Start process
		try {
			//Start form fetch
			const action =
				method === 'download_file' ? 'download_file' : 'create_form';
			const fetchData = {
				formID: this.props.formID,
				signal: this.state.fetchController.signal,
			};
			this.handleGetCfFormStep(
				__( 'Processing', 'cf_conversion_tool' )
			);
			this.handleGetNfFormTranslation(
				__( 'Waiting for CF Data', 'cf_conversion_tool' )
			);
			this.handleGetNfFormCreationProcess( {
				message: __( 'Waiting for NFF Data', 'cf_conversion_tool' ),
			} );
			//Get CF Form
			const formJson = await fetchForm( fetchData );
			//Display Status
			if ( typeof formJson !== 'undefined' ) {
				this.handleGetCfFormStep( __( 'Done', 'cf_conversion_tool' ) );
				this.handleGetNfFormTranslation(
					__( 'Processing', 'cf_conversion_tool' )
				);
			}
			//Start translation
			const conversionData = {
				cfForm: formJson,
				signal: this.state.fetchController.signal,
			};
			const formConversion = await callConversion( conversionData );

			//Display new Status for Form translation
			if ( typeof formConversion !== 'undefined' ) {
				this.handleGetNfFormTranslation(
					__( 'Done', 'cf_conversion_tool' )
				);
				this.handleGetNfFormCreationProcess( {
					message: __( 'Processing', 'cf_conversion_tool' ),
				} );
				const nffForm = JSON.parse( formConversion.form );
				let result = {
					message: __( 'Unknown Error', 'cf_conversion_tool' ),
				};
				//Trigger File download if it is the chosen method
				if ( action === 'download_file' ) {
					//Trigger Download alert
					const download = await downloadAsNff( {
						formJson: formConversion.form,
						exportName: nffForm.settings.title,
					} );
					//Set result data
					if ( download === 'success' ) {
						result = formConversion;
						result.message = __(
							'Form converted successfully',
							'cf_conversion_tool'
						);
					}
				} else if ( action === 'create_form' ) {
					//Start Form creation with batch import process
					do {
						await createNfForm( {
							formJson: formConversion.form,
							exportName: nffForm.settings.title,
							signal: this.state.fetchController.signal,
							handleCreationProcess: this
								.handleGetNfFormCreationProcess,
						} );
					} while (
						! this.state.getNfFormCreationProcess.batch_complete
					);
					//Set result data
					if ( this.state.getNfFormCreationProcess.batch_complete ) {
						result = this.state.getNfFormCreationProcess;
						result.message = __(
							'Ninja Forms Form created sucessfully',
							'cf_conversion_tool'
						);
						result.action = action;
						result.form = this.state.getNfFormCreationProcess.form_id;
					}
				}

				//Display Success Message
				this.handleGetFormResponse( result );
			}
		} catch ( error ) {
			//Display Errors
			console.log( 'error' + JSON.stringify( error ) );
			this.handleGetFormResponse( error );
		} finally {
			//Stop Spinner and refresh Fetch abortController
			this.handleSpinner( 'off' );
			this.handleFetchController();
		}
	}

	/**
	 * Cancel Fetch Jobs, spinners and reload state
	 */
	cancelAction() {
		this.state.fetchController.abort();
		this.handleGetFormResponse( {} );
		this.handleGetCfFormStep( false );
		this.handleGetNfFormTranslation( false );
		this.handleSpinner( 'off' );
		this.handleFetchController();
	}
	//Refresh Fetch abortController
	handleFetchController() {
		this.setState( { fetchController: new AbortController() } );
	}

	render() {
		const {
			props,
			state,
			handleModal,
			conversionAction,
			cancelAction,
		} = this;
		const {
			modalOpen,
			spinnerOn,
			createForm,
			downloadFile,
			getFormResponse,
			getCfFormStep,
			getNfFormTranslation,
			getNfFormCreationProcess,
		} = state;
		const { formID, form } = props;

		const formText = !form.name ? formID : form.name;
		return (
			<>
				{ ' ' }
				|
				<Button
					isTertiary
					className="cf-converter-modal-action"
					onClick={ handleModal }
					text={ __(
						'Convert to Ninja Forms',
						'cf_conversion_tool'
					) }
				/>
				{ modalOpen && (
					<Modal
						className="cf-converter-modal"
						title={
							__( 'Convert form ', 'cf_conversion_tool' ) +
							formText +
							__(
								' into a Ninja Forms Form.',
								'cf_conversion_tool'
							)
						}
						onRequestClose={ handleModal }
						shouldCloseOnClickOutside={ false }
					>
						<p>
							{ __(
								'To convert this form to a Ninja Forms form, please select ',
								'cf_conversion_tool'
							) }
							<i>{ __(
								'Convert to Ninja Forms',
								'cf_conversion_tool'
							) }</i>.
						</p>
						<p>
							{ __(
								'To generate an export of this form as a Ninja Forms form export, please select Download as .nff File.',
								'cf_conversion_tool'
							) }{ ' ' }
						</p>
						<div className="cf-converter-buttons">
							<Button
								isPrimary
								onClick={ () =>
									conversionAction( 'create_form' )
								}
								isBusy={ createForm }
								disabled={ downloadFile }
								showTooltip="true"
								label={ __(
									'Make sure Ninja Forms is installed \r\n and activated in order to use\r\n the create Form option.',
									'cf_conversion_tool'
								) }
								text={ __(
									'Convert to Ninja Forms',
									'cf_conversion_tool'
								) }
							/>
							<Button
								isPrimary
								onClick={ () =>
									conversionAction( 'download_file' )
								}
								isBusy={ downloadFile }
								disabled={ createForm }
								text={ __(
									'Download as .nff File',
									'cf_conversion_tool'
								) }
							/>
							<Button
								isSecondary
								onClick={ handleModal }
								text={ __( 'Cancel', 'cf_conversion_tool' ) }
							/>
						</div>
						<div className="cf-converter-response">
							{ spinnerOn && (
								<div>
									<p>
										{ __(
											'Please hold, this could take a while',
											'cf_conversion_tool'
										) }
									</p>
									<Spinner />
									<p>
										{ __(
											'1 - Get CF JSON data: ',
											'cf_conversion_tool'
										) }
										{ getCfFormStep
											? getCfFormStep
											: __(
													'Not triggered',
													'cf_conversion_tool'
											  ) }
									</p>
									<p>
										{ __(
											'2 - Translate to .NFF data: ',
											'cf_conversion_tool'
										) }
										{ getNfFormTranslation
											? getNfFormTranslation
											: __(
													'Not Triggered',
													'cf_conversion_tool'
											  ) }
									</p>
									<p>
										{ createForm &&
											__(
												'3 - Create Form: ',
												'cf_conversion_tool'
											) }
										{ downloadFile &&
											__(
												'3 - Download File: ',
												'cf_conversion_tool'
											) }
										{ getNfFormCreationProcess
											? getNfFormCreationProcess.message
											: __(
													'Not Triggered',
													'cf_conversion_tool'
											  ) }
									</p>
								</div>
							) }
							{ getFormResponse && (
								<div>
									<p>{ getFormResponse.message }</p>
									{ getFormResponse.action ===
										'create_form' && (
										<a
											href={
												cf_conversion_tool_vars.admin_url +
												'?page=ninja-forms&form_id=' +
												getFormResponse.form
											}
											title={ __(
												'Visit new Ninja Forms edit Page',
												'cf_conversion_tool'
											) }
										>
											{ __(
												'Go to Ninja Forms Edit page',
												'cf_conversion_tool'
											) }
										</a>
									) }
								</div>
							) }
							<br />
							{ getFormResponse.code === 'fetch_error' &&
								__(
									'Please refresh the page to initiate new Form conversion.',
									'cf_conversion_tool'
								) }
						</div>
					</Modal>
				) }
				{ spinnerOn && ! modalOpen && (
					<Modal
						className="cf-converter-alert-modal"
						title={ __(
							'Do you want to abort form conversion?',
							'cf_conversion_tool'
						) }
						onRequestClose={ handleModal }
					>
						<div className="cf-converter-alert-buttons">
							<Button
								isDestructive
								className="cf-converter-cancel-action-button"
								text={ __( 'Yes', 'cf_conversion_tool' ) }
								onClick={ cancelAction }
							/>
							<Button
								isSecondary
								onClick={ handleModal }
								text={ __( 'No', 'cf_conversion_tool' ) }
							/>
						</div>
					</Modal>
				) }
			</>
		);
	}
}

ModalWindow.propTypes = {
	formID: PropTypes.string.isRequired,
	form: PropTypes.object,
};
