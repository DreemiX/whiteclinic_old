<?php

namespace NinjaForms\CfConversionTool\Handlers;

use NinjaForms\CfConversionTool\NinjaFormsBuilder\Contracts\Processor;
use NinjaForms\CfConversionTool\Entities\FormTranslation;

/**
 * Translate a FormTranslation entity into a Ninja Form
 *
 */
class FormTranslator
{

	/**
	 * String construct of NF ready for import
	 *
	 * @var string
	 */
	protected $nffConstruct;


	public function setNffConstruct(string $nff): FormTranslator
	{
		$this->nffConstruct = $nff;

		return $this;
	}

	/**
	 * Get Translation or return $this
	 */
	public function getNffConstruct()
	{
		return !empty($this->nffConstruct ) ? $this->nffConstruct : $this;
	}
	
	/**
	 * Construct  a Ninja Form per set FormFields, Action Configuration, and title
	 * @param string $listId
	 * @param string $formTitle
	 * 
	 * @return string Form ID
	 */
	public function handle()
	{
		/*** Deprecated Function 
		 * It was replaced by an Ajax request to " action: 'nf_batch_process', batch_type: 'import_form'"
		 * ***/
		return Ninja_Forms()->form()->import_form($this->nffConstruct, true);
	}
}
