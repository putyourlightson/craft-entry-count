<?php
namespace Craft;

/**
 * Entry Count Service
 */
class EntryCountService extends BaseApplicationComponent
{
    /**
     * Returns count
     *
	 * @param int $entryId
	 *
	 * @return EntryCountModel
     */
    public function getCount($entryId)
    {
        // create new model
        $entryCountModel = new EntryCountModel();

        // get record from DB
        $entryCountRecord = EntryCountRecord::model()->findByAttributes(array('entryId' => $entryId));

        if ($entryCountRecord)
        {
            // populate model from record
            $entryCountModel = EntryCountModel::populateModel($entryCountRecord);
        }

        return $entryCountModel;
    }

    /**
     * Returns counted entries
     *
     * @return ElementCriteriaModel
     */
    public function getEntries()
    {
        // get all records from DB ordered by count descending
        $entryCountRecords = EntryCountRecord::model()->findAll(array(
            'order'=>'count desc'
        ));

        // get entry ids from records
        $entryIds = array();

        foreach ($entryCountRecords as $entryCountRecord)
        {
            $entryIds[] = $entryCountRecord->entryId;
        }

        // create criteria for entry element type
        $criteria = craft()->elements->getCriteria('Entry');

        // filter by entry ids
        $criteria->id = $entryIds;

        // enable fixed order
        $criteria->fixedOrder = true;

        return $criteria;
    }

    /**
     * Increment count
     *
	 * @param int $entryId
     */
    public function increment($entryId)
    {
        // check if action should be ignored
        if ($this->_ignoreAction())
        {
            return;
        }

        // get record from DB
        $entryCountRecord = EntryCountRecord::model()->findByAttributes(array('entryId' => $entryId));

        // if exists then increment count
        if ($entryCountRecord)
        {
            $entryCountRecord->setAttribute('count', $entryCountRecord->getAttribute('count') + 1);
        }

        // otherwise create a new record
        else
        {
            $entryCountRecord = new EntryCountRecord;
            $entryCountRecord->setAttribute('entryId', $entryId);
            $entryCountRecord->setAttribute('count', 1);
        }

        // save record in DB
        $entryCountRecord->save();
    }

    /**
     * Reset count
     *
	 * @param int $entryId
     */
    public function reset($entryId)
    {
        // get record from DB
        $entryCountRecord = EntryCountRecord::model()->findByAttributes(array('entryId' => $entryId));

        // if record exists then delete
        if ($entryCountRecord)
        {
            // delete record from DB
            $entryCountRecord->delete();
        }

        // log reset
        EntryCountPlugin::log(
            'Entry count with entry ID '.$entryId.' reset by '.craft()->userSession->getUser()->username,
            LogLevel::Info,
            true
        );

        // fire an onResetCount event
        $this->onResetCount(new Event($this, array('entryId' => $entryId)));
    }

    /**
     * On reset count
     *
     * @param Event $event
     */
    public function onResetCount($event)
    {
        $this->raiseEvent('onResetCount', $event);
    }

    // Helper methods
    // =========================================================================

    /**
     * Check if action should be ignored
     */
    private function _ignoreAction()
    {
        // get plugin settings
        $settings = craft()->plugins->getPlugin('entryCount')->getSettings();

        // check if logged in users should be ignored based on settings
        if ($settings->ignoreLoggedInUsers AND craft()->userSession->isLoggedIn())
        {
            return true;
        }

        // check if ip address should be ignored based on settings
        if ($settings->ignoreIpAddresses AND in_array(craft()->request->getIpAddress(), explode("\n", $settings->ignoreIpAddresses)))
        {
            return true;
        }
    }
}
