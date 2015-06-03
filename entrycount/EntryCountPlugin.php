<?php
namespace Craft;

/**
 * Entry Count Plugin
 */
class EntryCountPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Entry Count');
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'PutYourLightsOn (Ben Croker)';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.putyourlightson.net';
    }

    protected function defineSettings()
    {
        return array(
            'showCountOnEntryIndex' => array(AttributeType::Bool, 'default' => 0),
            'ignoreLoggedInUsers' => array(AttributeType::Bool, 'default' => 0),
            'ignoreIpAddresses' => array(AttributeType::Mixed, 'default' => '')
        );
    }

    public function getSettingsHtml()
    {
       return craft()->templates->render('entrycount/settings', array(
           'settings' => $this->getSettings()
       ));
    }

    public function hasCpSection()
    {
        return true;
    }

	// Hooks
	// =========================================================================

    public function modifyEntryTableAttributes(&$attributes, $source)
    {
        if ($this->getSettings()->showCountOnEntryIndex)
        {
            $attributes['count'] = Craft::t('Count');
        }
    }

    public function getEntryTableAttributeHtml($entry, $attribute)
    {
        if ($this->getSettings()->showCountOnEntryIndex AND $attribute == 'count')
        {
            return craft()->entryCount->getCount($entry->id)->count;
        }
    }

    public function addEntryActions($source)
    {
        if ($this->getSettings()->showCountOnEntryIndex)
        {
            return array('EntryCount_Reset');
        }
    }
}
