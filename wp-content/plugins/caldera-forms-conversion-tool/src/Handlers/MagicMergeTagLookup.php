<?php

namespace NinjaForms\CfConversionTool\Handlers;

use NinjaForms\CfConversionTool\Contracts\MagicMergeTagLookup as ContractsMagicMergeTagLookup;
/**
 * Converts CF magic tags to NF merge tags
 */
class MagicMergeTagLookup implements ContractsMagicMergeTagLookup
{

    /**
     * CF field slug / NF field key
     * 
     * Indexed array of the field slugs/keys 
     *
     * @var array
     */
    public $slugKey = [];

    /**
     * Array of search terms to replace
     *
     * @var array
     */
    public $searchArray = [];
    /**
     * Array of replacement values
     *
     * @var array
     */
    public $replaceArray = [];
    /**
     * Array of replacement values
     *
     * @var array
     */
    public $calcsArray = [];

    /**
     * Replace magic tag with field merge tag on single string
     *
     * @param string $incoming
     * @return string
     */
    public function convertMagicTagString(string $incoming): string
    {
        $outgoing =\str_replace($this->searchArray,$this->replaceArray,$incoming);
        
        return $outgoing;
    }

    /**
     * Replace magic tag with merge tag on a calculation string
     *
     * @param string $incoming
     * @return string
     */
    public function convertCalcString(string $incoming): string
    {
        $outgoing =\str_replace($this->searchArray,$this->calcsArray,$incoming);
        $outgoing =\str_replace($this->searchArray,$this->replaceArray,$outgoing);
        
        return $outgoing;
    }

    /**
     * Replace magic tag with field merge tag on an array of values
     *
     * @param array $incoming
     * @return string
     */
    public function convertMagicTagArray(array $incoming): array
    {
        $outgoing =\str_replace($this->searchArray,$this->replaceArray,$incoming);
        
        return $outgoing;
    }

    /**
     * Wrap string with magic tag prefix and suffix
     *
     * @param string $incoming
     * @return string
     */
    protected function wrapMagicTag(string $incoming): string
    {
        $outgoing = '%' . $incoming . '%';
        return $outgoing;
    }

    /**
     * Wrap string with field merge tag prefix and suffix
     *
     * @param string $incoming
     * @return string
     */
    public function wrapFieldMergeTag(string $incoming): string
    {
        $outgoing = '{field:' . $incoming . '}';
        return $outgoing;
    }

    /**
     * Wrap string with calc merge tag prefix and suffix
     *
     * @param string $incoming
     * @return string
     */
    public function wrapCalcMergeTag(string $incoming): string
    {
        $outgoing = '{calc:' . $incoming . '}';
        return $outgoing;
    }

    /**
     * Append collection of slugs, magic, and merge tags
     *
     * @param  string  $slugKey  Indexed array of the field slugs/keys
     * @param  boolean $isCalc  Whether or not this key is associated with a calculation
     *
     * @return  self
     */
    public function addSlugKey(string $slugKey, $isCalc = false): ContractsMagicMergeTagLookup
    {
        $this->slugKey[] = $slugKey;

        $this->searchArray[] = $this->wrapMagicTag($slugKey);
        $this->replaceArray[] = $this->wrapFieldMergeTag($slugKey);
        if( $isCalc ) {
            $this->calcsArray[] = $this->wrapCalcMergeTag($slugKey);
        } else {
            $this->calcsArray[] = $this->wrapMagicTag($slugKey);
        }

        return $this;
    }
}
