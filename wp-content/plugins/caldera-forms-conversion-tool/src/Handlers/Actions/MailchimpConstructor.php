<?php

namespace NinjaForms\CfConversionTool\Handlers\Actions;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\AbstractActionConstructor;
/**
 * Construct email action from CF mailer
 */
class MailchimpConstructor extends AbstractActionConstructor implements ActionConstructor
{
    /**
     * Id of currently mapped Mailchimp list
     *
     * @var string
     */
    protected $connectedListId = '';

    /**
     * Mailchimp audience definition
     *
     * @var array
     */
    protected $audienceDefinition = [];

    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedAction['type'] = 'mailchimp';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        if (isset($this->cFProcessor['audience'])) {
            $this->audienceDefinition = $this->cFProcessor['audience'];
        }

        $this->constructedAction['label'] = 'Mailchimp';

        $this->saveApiKey();

        if (isset($this->cFProcessor['config']['listId']) && '' !== $this->cFProcessor['config']['listId']) {
            $this->connectedListId = $this->cFProcessor['config']['listId'];

            $this->constructedAction['newsletter_list'] = $this->connectedListId;

            $this->mapMailchimpFields();

            $this->mapInterestToggleSettings();

            $this->mapTags();
        }

        $this->constructedAction = $this->magicMergeTagLookup->convertMagicTagArray($this->constructedAction);
    }

    /**
     * Add MergeFields and email address
     *
     * @return void
     */
    protected function mapMailchimpFields(): void
    {
        if (
            isset($this->cFProcessor['config']['email_address']) &&
            '' !== $this->cFProcessor['config']['email_address'] &&
            isset($this->fieldSlugLookups[$this->cFProcessor['config']['email_address']])
        ) {
            $this->constructedAction[$this->connectedListId . '_email_address'] = $this->magicMergeTagLookup->wrapFieldMergeTag($this->fieldSlugLookups[$this->cFProcessor['config']['email_address']]);
        }

        if (isset($this->cFProcessor['config']['mergeFields'])) {
            foreach ($this->cFProcessor['config']['mergeFields'] as $mergeField => $fieldId) {
                if ('' !== $fieldId && isset($this->fieldSlugLookups[$fieldId])) {
                    $this->constructedAction[$this->connectedListId . '_' . $mergeField] = $this->magicMergeTagLookup->wrapFieldMergeTag($this->fieldSlugLookups[$fieldId]);
                }
            }
        }
    }

    /**
     * Construct an array of manually set interest ids for NF Mailchimp map
     *
     * NF Mailchimp has toggles for manually set interests; this method is given
     * the interest Id and constructs each set interest into the action.
     *
     * @return void
     */
    protected function mapInterestToggleSettings(): void
    {
        if (!isset($this->cFProcessor['config']['interests']) || !\is_array($this->cFProcessor['config']['interests'])) {
            return;
        }

        $interestIdCollection = $this->cFProcessor['config']['interests'];

        foreach ($interestIdCollection as $interestIds) {

            if (isset($interestIds['interests']) && \is_array($interestIds['interests'])) {

                foreach ($interestIds['interests'] as $interest) {

                    if (isset($this->audienceDefinition['interests']['interests'][$interest])) {
                        $key =  $this->connectedListId . '_group_'
                            . $this->audienceDefinition['interests']['interests'][$interest]['id'] . '_'
                            . $this->audienceDefinition['interests']['interests'][$interest]['name'];

                        $this->constructedAction[$key] = 1;
                    }
                }
            }
        }
    }

    /**
     * Construct imploded list of mapped tags
     * 
     * CF only allows pre-selected map values from checkbox list so we don't
     * need to account for magic merge tags - we have the tag/segment ids
     * @return void 
     */
    protected function mapTags(): void
    {
        if (
            !isset($this->cFProcessor['config']['segments'])
            || !\is_array($this->cFProcessor['config']['segments'])
        ) {
            return;
        }

        $segmentCollection = $this->cFProcessor['config']['segments'];
        if (
            !isset($this->cFProcessor['config']['segments'])
            || !\is_array($this->audienceDefinition['tags']['segments'])
        ) {
            return;
        }
        $tagKey = $this->connectedListId . '_tags';
        $tagCollectionByName = [];
        foreach ($this->audienceDefinition['tags']['segments'] as $segmentDefinition) {
            if (\in_array($segmentDefinition['id'], $segmentCollection)) {
                $tagCollectionByName[] = $segmentDefinition['name'];
            }
        }
        $this->constructedAction[$tagKey] = implode(',', $tagCollectionByName);
    }


    /**
     * Save the API key in NF plugin settings
     *
     * @return void
     */
    protected function saveApiKey(): void
    {
        if (
            isset($this->cFProcessor['config']['apiKey']) &&
            '' !== $this->cFProcessor['config']['apiKey']
        ) {

            Ninja_Forms()->update_setting('ninja_forms_mc_api', $this->cFProcessor['config']['apiKey']);
        }
    }
}
