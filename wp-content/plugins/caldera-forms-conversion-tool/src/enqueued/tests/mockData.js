//Set global values before rendering a component using that function as a wrapper
export async function withGlobal( globalName, tempValue, handler = () => {} ) {
	const original = global.window[ globalName ];
	global.window[ globalName ] = tempValue;
	await handler();
	global.window[ globalName ] = original;
}

export const cf_conversion_tool_vars = {
	admin_url: 'http://localhost:8555/wp-admin/',
	rest_url: 'http://localhost:8555/wp-json/',
	nonce: '112233',
	rest_nonce: '223344',
	forms: {
		"cf-1" : {
			"id": "cf-1",
			"name": "cf-1-name"
		},
		"cf-2" : {
			"id": "cf-2",
			"name": "cf-2-name"
		}
	}
};

export const create_nf_form_response = {
	form: 30,
	message: 'Ninja Forms Form created sucessfully',
	action: 'create_form',
};

export const download_nff_file_response = {
	form:
		'{"settings":{"title":"CF form","conditions":[{"collapsed":"1","process":"1","connector":"all","when":[{"connector":"AND","key":"how_many","comparator":"equal","value":4,"type":"field","modelType":"when"},{"connector":"AND","key":"name_1","comparator":"equal","value":"cool","type":"field","modelType":"when"}],"then":[{"key":"name_4","trigger":"show_field","value":null,"type":"field","modelType":"then"}],"else":[{"key":"name_4","trigger":"hide_field","value":null,"type":"field","modelType":"else"}]}],"formContentData":[{"order":"0","type":"part","title":"Part Title","key":"ysbmuwve","formContentData":[{"cells":[{"order":"0","fields":["how_many","email_address"],"width":"50"},{"order":"1","fields":["name_1","textarea"],"width":"50"}],"order":"0"},{"cells":[{"order":"0","fields":["name_2"],"width":"25"},{"order":"1","fields":["name_3"],"width":"25"},{"order":"2","fields":["name_4"],"width":"50"}],"order":"1"},{"cells":[{"order":"0","fields":["submit"],"width":"100"}],"order":"2"}]}]},"fields":[{"label":"How Many?","type":"listradio","field_label":"How Many?","key":"how_many","field_key":"how_many","placeholder":"","default":"opt1447713","desc_text":"","personally_identifiable":"0","options":[{"label":1,"value":1,"calc":"1"},{"label":2,"value":2,"calc":"2"},{"label":3,"value":3,"calc":"3"},{"label":4,"value":4,"calc":"4"}]},{"label":"Name 1","type":"textbox","field_label":"Name 1","key":"name_1","field_key":"name_1","placeholder":"","default":"","desc_text":"","personally_identifiable":"0"},{"label":"Name 2","type":"textbox","field_label":"Name 2","key":"name_2","field_key":"name_2","placeholder":"","default":"","desc_text":"","personally_identifiable":"0"},{"label":"Name 3","type":"textbox","field_label":"Name 3","key":"name_3","field_key":"name_3","placeholder":"","default":"","desc_text":"","personally_identifiable":"0"},{"label":"Name 4","type":"textbox","field_label":"Name 4","key":"name_4","field_key":"name_4","placeholder":"","default":"","desc_text":"","personally_identifiable":"0"},{"label":"Submit","type":"submit","field_label":"Submit","key":"submit","field_key":"submit","placeholder":"","default":"","desc_text":"","personally_identifiable":"0"},{"label":"textarea","type":"textbox","field_label":"textarea","key":"textarea","field_key":"textarea","placeholder":"","default":"","desc_text":"","personally_identifiable":"0"},{"label":"Email Address","type":"textbox","field_label":"Email Address","key":"email_address","field_key":"email_address","placeholder":"","default":"","desc_text":"","personally_identifiable":"0"}],"actions":[{"active":"1","objectType":"Action","objectDomain":"actions","type":"email","label":"Email","to":"wifexyj@mailinator.com","email_subject":"focymucet@mailinator.net","email_message":"Ex aut qui quaerat e","from_name":"Garth Coffey","from_address":"syqawizabe@mailinator.com","reply_to":"Doloremque voluptatu","cc":"Minima quo id bland","bcc":"Dolorum id aperiam e","email_format":"html"},{"active":"1","objectType":"Action","objectDomain":"actions","type":"redirect","label":"Redirect","success_msg":"Eaque dicta distinct","redirect_url":"wordpress.org"},{"active":"1","objectType":"Action","objectDomain":"actions","type":"email","label":"Email","to":"maguzuna@mailinator.net","email_subject":"juxadami@mailinator.net","email_message":"Facere ut adipisicin.","from_name":"xiryp@mailinator.net","from_address":"kefunivo@mailinator.com","reply_to":"kinaz@mailinator.com","cc":"","bcc":"kilitew@mailinator.net","email_format":"html"}]}',
	message: 'Form translated sucessfully',
	action: 'download_file',
};

export const cf_form = {
	ID: 'CF60a29620a11fe',
	_last_updated: 'Fri, 28 May 2021 12:30:19 +0000',
	cf_version: '1.9.4',
	name: 'CF form',
	scroll_top: 1,
	success: 'Form has been successfully submitted. Thank you.\t\t\t\t\t\t',
	db_support: 1,
	pinned: 0,
	hide_form: 1,
	check_honey: 1,
	avatar_field: '',
	form_ajax: 1,
	custom_callback: '',
	layout_grid: {
		fields: {
			fld_4429078: '1:1',
			fld_2236850: '1:1',
			fld_6243959: '1:2',
			fld_8759287: '1:2',
			fld_4488337: '2:1',
			fld_4368852: '2:2',
			fld_9871367: '2:3',
			fld_6417547: '3:1',
		},
		structure: '6:6|3:3:6|12',
	},
	fields: {
		fld_4429078: {
			ID: 'fld_4429078',
			type: 'radio',
			label: 'How Many?',
			slug: 'how_many',
			conditions: {
				type: '',
			},
			caption: '',
			config: {
				custom_class: '',
				default_option: '',
				auto_type: '',
				taxonomy: 'category',
				post_type: 'post',
				value_field: 'name',
				orderby_tax: 'name',
				orderby_post: 'name',
				order: 'ASC',
				default: 'opt1447713',
				option: {
					opt1447713: {
						calc_value: 1,
						value: 1,
						label: 1,
					},
					opt1960279: {
						calc_value: 2,
						value: 2,
						label: 2,
					},
					opt1579301: {
						calc_value: 3,
						value: 3,
						label: 3,
					},
					opt1144612: {
						calc_value: 4,
						value: 4,
						label: 4,
					},
				},
				email_identifier: 0,
				personally_identifying: 0,
			},
		},
		fld_6243959: {
			ID: 'fld_6243959',
			type: 'text',
			label: 'Name 1',
			slug: 'name_1',
			conditions: {
				type: '',
			},
			caption: '',
			config: {
				custom_class: '',
				placeholder: '',
				default: '',
				type_override: 'text',
				mask: '',
				email_identifier: 0,
				personally_identifying: 0,
			},
		},
		fld_4488337: {
			ID: 'fld_4488337',
			type: 'text',
			label: 'Name 2',
			slug: 'name_2',
			conditions: {
				type: '',
			},
			caption: '',
			config: {
				custom_class: '',
				placeholder: '',
				default: '',
				type_override: 'text',
				mask: '',
				email_identifier: 0,
				personally_identifying: 0,
			},
		},
		fld_4368852: {
			ID: 'fld_4368852',
			type: 'text',
			label: 'Name 3',
			slug: 'name_3',
			conditions: {
				type: '',
			},
			caption: '',
			config: {
				custom_class: '',
				placeholder: '',
				default: '',
				type_override: 'text',
				mask: '',
				email_identifier: 0,
				personally_identifying: 0,
			},
		},
		fld_9871367: {
			ID: 'fld_9871367',
			type: 'text',
			label: 'Name 4',
			slug: 'name_4',
			conditions: {
				type: 'con_zaqbi',
			},
			caption: '',
			config: {
				custom_class: '',
				placeholder: '',
				default: '',
				type_override: 'text',
				mask: '',
				email_identifier: 0,
				personally_identifying: 0,
			},
		},
		fld_6417547: {
			ID: 'fld_6417547',
			type: 'button',
			label: 'Submit',
			slug: 'submit',
			conditions: {
				type: '',
			},
			caption: '',
			config: {
				custom_class: '',
				type: 'submit',
				class: 'btn btn-default',
				target: '',
			},
		},
		fld_8759287: {
			ID: 'fld_8759287',
			type: 'paragraph',
			label: 'textarea',
			slug: 'textarea',
			conditions: {
				type: '',
			},
			caption: '',
			config: {
				custom_class: '',
				placeholder: '',
				rows: 4,
				default: '',
				email_identifier: 0,
				personally_identifying: 0,
			},
		},
		fld_2236850: {
			ID: 'fld_2236850',
			type: 'email',
			label: 'Email Address',
			slug: 'email_address',
			conditions: {
				type: '',
			},
			caption: '',
			config: {
				custom_class: '',
				placeholder: '',
				default: '',
				email_identifier: 0,
				personally_identifying: 0,
			},
		},
	},
	page_names: [ 'Page 1' ],
	mailer: {
		on_insert: 1,
		sender_name: 'xiryp@mailinator.net',
		sender_email: 'kefunivo@mailinator.com',
		reply_to: 'kinaz@mailinator.com',
		email_type: 'html',
		recipients: 'maguzuna@mailinator.net',
		bcc_to: 'kilitew@mailinator.net',
		email_subject: 'juxadami@mailinator.net',
		email_message: 'Facere ut adipisicin.',
	},
	processors: {
		fp_27135381: {
			ID: 'fp_27135381',
			runtimes: {
				insert: 1,
			},
			type: 'auto_responder',
			config: {
				sender_name: 'Garth Coffey',
				sender_email: 'syqawizabe@mailinator.com',
				reply_to: 'Doloremque voluptatu',
				cc: 'Minima quo id bland',
				bcc: 'Dolorum id aperiam e',
				subject: 'focymucet@mailinator.net',
				recipient_name: 'Flavia Avery',
				recipient_email: 'wifexyj@mailinator.com',
				message: 'Ex aut qui quaerat e',
			},
			conditions: {
				type: '',
				group: [],
			},
		},
		fp_23273715: {
			ID: 'fp_23273715',
			runtimes: {
				insert: 1,
			},
			type: 'form_redirect',
			config: {
				url: 'wordpress.org',
				message: 'Eaque dicta distinct',
			},
			conditions: {
				type: '',
				group: [],
			},
		},
	},
	settings: {
		responsive: {
			break_point: 'sm',
		},
	},
	conditional_groups: {
		conditions: {
			con_zaqbi: {
				id: 'con_zaqbi',
				type: 'show',
				name: 'Show 4',
				fields: {
					cl_prfjprge: 'fld_4429078',
					cl_mlfilazql: 'fld_6243959',
					cl_mtofbac: 'fld_4429078',
				},
				group: {
					rw_rlejtndi: {
						cl_prfjprge: {
							id: 'cl_prfjprge',
							field: 'fld_4429078',
							value: 'opt1144612',
							compare: 'is',
							parent: 'rw_rlejtndi',
						},
						cl_mlfilazql: {
							field: 'fld_6243959',
							value: 'cool',
							compare: 'is',
							parent: 'rw_rlejtndi',
							id: 'cl_mlfilazql',
						},
					},
					rw_uneydgogf: {
						cl_mtofbac: {
							id: 'cl_mtofbac',
							field: 'fld_4429078',
							value: 'opt1447713',
							compare: 'is',
							parent: 'rw_uneydgogf',
						},
					},
				},
			},
		},
	},
	privacy_exporter_enabled: false,
	version: '1.9.4',
	db_id: '50',
	type: 'primary',
};
