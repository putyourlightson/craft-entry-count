<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\services;

use Craft;
use craft\base\Component;
use craft\elements\db\EntryQuery;
use craft\elements\Entry;
use putyourlightson\entrycount\EntryCount;
use putyourlightson\entrycount\models\EntryCountModel;
use putyourlightson\entrycount\records\EntryCountRecord;
use yii\base\Event;

/**
 * EntryCountService
 *
 * @property EntryQuery $entries
 */
class EntryCountService extends Component
{
    /**
     * @event Event
     */
    const EVENT_AFTER_RESET_COUNT = 'afterResetCount';

    // Public Methods
    // =========================================================================

    /**
     * Returns count
     *
     * @param int $entryId
     *
     * @return EntryCountModel
     */
    public function getCount($entryId): EntryCountModel
    {
        // create new model
        $entryCountModel = new EntryCountModel();

        // get record from DB
        $entryCountRecord = EntryCountRecord::find()
            ->where(['entryId' => $entryId])
            ->one();

        if ($entryCountRecord) {
            // populate model from record
            $entryCountModel->setAttributes($entryCountRecord->getAttributes(), false);
        }

        return $entryCountModel;
    }

    /**
     * Returns counted entries
     *
     * @return EntryQuery
     */
    public function getEntries(): EntryQuery
    {
        // get all records from DB ordered by count descending
        $entryCountRecords = EntryCountRecord::find()
            ->orderBy('count desc')
            ->all();

        // get entry ids from records
        $entryIds = [];

        foreach ($entryCountRecords as $entryCountRecord) {
            /** @var EntryCountRecord $entryCountRecord */
            $entryIds[] = $entryCountRecord->entryId;
        }

        // return entry query
        return Entry::find()
            ->id($entryIds)
            ->fixedOrder(true);
    }

    /**
     * Increment count
     *
     * @param int $entryId
     */
    public function increment($entryId)
    {
        // check if action should be ignored
        if ($this->_ignoreAction()) {
            return;
        }

        // get record from DB
        $entryCountRecord = EntryCountRecord::find()
            ->where(['entryId' => $entryId])
            ->one();

        // if exists then increment count
        if ($entryCountRecord) {
            $entryCountRecord->setAttribute('count', $entryCountRecord->getAttribute('count') + 1);
        }

        // otherwise create a new record
        else {
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
        $entryCountRecord = EntryCountRecord::find()
            ->where(['entryId' => $entryId])
            ->one();

        // if record exists then delete
        if ($entryCountRecord) {
            // delete record from DB
            $entryCountRecord->delete();
        }

        // log reset
        Craft::warning(Craft::t('entry-count', 'Entry count with entry ID {entryId} reset by {username}', [
                'entryId' => $entryId,
                'username' => Craft::$app->getUser()->getIdentity()->username,
        ]), 'EntryCount');


        // Fire a 'afterResetCount' event
        if ($this->hasEventHandlers(self::EVENT_AFTER_RESET_COUNT)) {
            $this->trigger(self::EVENT_AFTER_RESET_COUNT, new Event([
                'entryId' => $entryId,
            ]));
        }
    }

    // Helper methods
    // =========================================================================

    /**
     * Check if action should be ignored
     *
     * @return bool
     */
    private function _ignoreAction(): bool
    {
        // get plugin settings
        $settings = EntryCount::$plugin->getSettings();

        // check if logged in users should be ignored based on settings
        if ($settings->ignoreLoggedInUsers AND !Craft::$app->getUser()->getIsGuest()) {
            return true;
        }

        // check if ip address should be ignored based on settings
        if ($settings->ignoreIpAddresses AND in_array(Craft::$app->getRequest()->getUserIP(), explode("\n", $settings->ignoreIpAddresses), true)) {
            return true;
        }

        return false;
    }
}
