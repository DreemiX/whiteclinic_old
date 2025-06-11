<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;
/**
 * Construct listselect field from CF select field
 */
class ListSelectConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /**
     * Parameters for constructing states options
     *
     * @var array
     */
    protected $states= [
			"AB"    =>  "Alberta",
			"BC"    =>  "British Columbia",
			"MB"    =>  "Manitoba",
			"NB"    =>  "New Brunswick",
			"NL"    =>  "Newfoundland and Labrador",
			"NS"    =>  "Nova Scotia",
			"NT"    =>  "Northwest Territories",
			"NU"    =>  "Nunavut",
			"ON"    =>  "Ontario",
			"PE"    =>  "Prince Edward Island",
			"QC"    =>  "Quebec",
			"SK"    =>  "Saskatchewan",
			"YT"    =>  "Yukon",
			"AL" =>  "Alabama",
			"AK" =>  "Alaska",
			"AZ" =>  "Arizona",
			"AR" =>  "Arkansas",
			"CA" =>  "California",
			"CO" =>  "Colorado",
			"CT" =>  "Connecticut",
			"DE" =>  "Delaware",
			"DC" =>  "District Of Columbia",
			"FL" =>  "Florida",
			"GA" =>  "Georgia",
			"HI" =>  "Hawaii",
			"ID" =>  "Idaho",
			"IL" =>  "Illinois",
			"IN" =>  "Indiana",
			"IA" =>  "Iowa",
			"KS" =>  "Kansas",
			"KY" =>  "Kentucky",
			"LA" =>  "Louisiana",
			"ME" =>  "Maine",
			"MD" =>  "Maryland",
			"MA" =>  "Massachusetts",
			"MI" =>  "Michigan",
			"MN" =>  "Minnesota",
			"MS" =>  "Mississippi",
			"MO" =>  "Missouri",
			"MT" =>  "Montana",
			"NE" =>  "Nebraska",
			"NV" =>  "Nevada",
			"NH" =>  "New Hampshire",
			"NJ" =>  "New Jersey",
			"NM" =>  "New Mexico",
			"NY" =>  "New York",
			"NC" =>  "North Carolina",
			"ND" =>  "North Dakota",
			"OH" =>  "Ohio",
			"OK" =>  "Oklahoma",
			"OR" =>  "Oregon",
			"PA" =>  "Pennsylvania",
			"RI" =>  "Rhode Island",
			"SC" =>  "South Carolina",
			"SD" =>  "South Dakota",
			"TN" =>  "Tennessee",
			"TX" =>  "Texas",
			"UT" =>  "Utah",
			"VT" =>  "Vermont",
			"VA" =>  "Virginia",
			"WA" =>  "Washington",
			"WV" =>  "West Virginia",
			"WI" =>  "Wisconsin",
			"WY" =>  "Wyoming"
    ];

    /** @inheritDoc */
    protected function setType(): void
    {
		//Check if we are getting a CF autocomplete field with multi section enabled otherwise default to listselect
		$this->constructedField['type'] = isset($this->cFField['config']['multi']) && 1 === $this->cFField['config']['multi'] ? 'listmultiselect' : 'listselect';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        if($this->cFField['type'] === "states"){
            $this->setStatesOptions();
        } else {
            $this->setOptions();
        }
        
    }

    /**
     * Set list select options
     */
    protected function setOptions( ): void
    {
        if(!isset($this->cFField['config']['option'])){
            return;
        }

        $options=[];

        foreach($this->cFField['config']['option'] as $opt => $option){
            $calc = isset($option['calc_value'])?(string)$option['calc_value']:'';
            $selected = isset($this->cFField['config']['default']) && $this->cFField['config']['default'] === $opt ? '1' : '';
            $newOption =[
                'label'     =>  $option['label'],
                'value'     =>  $option['value'],
                'calc'      =>  $calc,
                'selected'  =>  $selected
            ];

            $options[]=$newOption;
        }

        $this->constructedField['options']=$options;
    }
    /**
     * Set list of states of the CF States field
     */
    protected function setStatesOptions() 
    {
        foreach($this->states as $value => $label){
            $newOption =[
                'label' => $label,
                'value' => $value
            ];

            $options[]  =   $newOption;
        }

        $this->constructedField['options']=$options;
    }
}
