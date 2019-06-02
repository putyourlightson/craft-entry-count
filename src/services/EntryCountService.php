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
use putyourlightson\entrycount\events\EntryCountEvent;
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
    // Constants
    // =========================================================================

    /**
     * @event Event
     */
    const EVENT_AFTER_RESET_COUNT = 'afterResetCount';

    /**
     * @event Event
     */
    const EVENT_AFTER_RESET_ALL_COUNT = 'afterResetAllCount';

    /**
     * @event EntryCountEvent
     */
    const EVENT_BEFORE_INCREMENT_COUNT = 'beforeIncrementCount';

    /**
     * @event EntryCountEvent
     */
    const EVENT_AFTER_INCREMENT_COUNT = 'afterIncrementCount';

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
            $attributes = $entryCountRecord->getAttributes();

            // populate model from record
            $entryCountModel->setAttributes($attributes);
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

        if ($this->hasEventHandlers(self::EVENT_BEFORE_INCREMENT_COUNT)) {
            $this->trigger(self::EVENT_BEFORE_INCREMENT_COUNT, new EntryCountEvent([
                'entryId' => $entryId
            ]));
        }

        // get record from DB
        $entryCountRecord = EntryCountRecord::find()
            ->where(['entryId' => $entryId])
            ->one();

        // if exists then increment count
        if ($entryCountRecord === null) {
            $entryCountRecord = new EntryCountRecord();
            $entryCountRecord->entryId = $entryId;
        }

        $entryCountRecord->count = $entryCountRecord->count + 1;

        // save record in DB
        $entryCountRecord->save();

        if ($this->hasEventHandlers(self::EVENT_AFTER_INCREMENT_COUNT)) {
            $this->trigger(self::EVENT_AFTER_INCREMENT_COUNT, new EntryCountEvent([
                'entryId' => $entryId
            ]));
        }
    }

    /**
     * Reset count
     *
     * @param int $entryId
     */
    public function reset($entryId)
    {
        // Get record from DB
        $entryCountRecord = EntryCountRecord::find()
            ->where(['entryId' => $entryId])
            ->one();

        // If record exists then delete
        if ($entryCountRecord) {
            // Delete record from DB
            $entryCountRecord->delete();
        }

        // Log the reset
        Craft::info(Craft::t('entry-count', 'Entry count with entry ID {entryId} reset by {username}', [
            'entryId' => $entryId,
            'username' => Craft::$app->getUser()->getIdentity()->username,
        ]), 'EntryCount');


        // Fire an 'afterResetCount' event
        if ($this->hasEventHandlers(self::EVENT_AFTER_RESET_COUNT)) {
            $this->trigger(self::EVENT_AFTER_RESET_COUNT, new Event());
        }
    }

    /**
     * Reset all count
     */
    public function resetAll()
    {
        // Delete all records from DB
        EntryCountRecord::deleteAll();

        // Log the reset
        Craft::info(Craft::t('entry-count', 'All entry counts reset by {username}', [
            'username' => Craft::$app->getUser()->getIdentity()->username,
        ]), 'EntryCount');

        // Fire an 'afterResetAllCount' event
        if ($this->hasEventHandlers(self::EVENT_AFTER_RESET_ALL_COUNT)) {
            $this->trigger(self::EVENT_AFTER_RESET_ALL_COUNT, new Event());
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
