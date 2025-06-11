<?php

namespace NinjaForms\CfConversionTool\Contracts;

/**
 * Converts CF magic tags to NF merge tags
 */
interface MagicMergeTagLookup
{
    /**
     * 
     * Replace magic tag with field merge tag on single string
     *
     * @param string $incoming
     * @return string
     */
    public function convertMagicTagString(string $incoming): string;

    /**
     * Replace magic tag with field merge tag on an array of values
     *
     * @param array $incoming
     * @return string
     */
    public function convertMagicTagArray(array $incoming): array;

    /**
     * Append collection of slugs, magic, and merge tags
     *
     * @param  string  $slugKey  Indexed array of the field slugs/keys
     *
     * @return  self
     */
    public function addSlugKey(string $slugKey, $isCalc = false): MagicMergeTagLookup;

    /**
     * Wrap string with field merge tag prefix and suffix
     *
     * @param string $incoming
     * @return string
     */
    public function wrapFieldMergeTag(string $incoming): string;

    /**
     * Replace magic tag with merge tag on a calculation string
     *
     * @param string $incoming
     * @return string
     */
    public function convertCalcString(string $incoming): string;
}
