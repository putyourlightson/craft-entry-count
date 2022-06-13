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
 * @property EntryQuery $entries
 */
class EntryCountService extends Component
{
    /**
     * @event Event
     */
    public const EVENT_AFTER_RESET_COUNT = 'afterResetCount';

    /**
     * Returns count
     */
    public function getCount(int $entryId): EntryCountModel
    {
        // Create new model
        $entryCountModel = new EntryCountModel();

        // Get record from DB
        $entryCountRecord = EntryCountRecord::find()
            ->where(['entryId' => $entryId])
            ->one();

        if ($entryCountRecord) {
            // Populate model from record
            $entryCountModel->setAttributes($entryCountRecord->getAttributes(), false);
        }

        return $entryCountModel;
    }

    /**
     * Returns counted entries
     */
    public function getEntries(): EntryQuery
    {
        // Get all records from DB ordered by count descending
        $entryCountRecords = EntryCountRecord::find()
            ->orderBy('count desc')
            ->all();

        // Get entry ids from records
        $entryIds = [];

        foreach ($entryCountRecords as $entryCountRecord) {
            $entryIds[] = $entryCountRecord->entryId;
        }

        // Return entry query
        return Entry::find()
            ->id($entryIds)
//            ->site('*')
            ->fixedOrder();
    }

    /**
     * Increment count
     */
    public function increment(int $entryId)
    {
        // Check if action should be ignored
        if ($this->_ignoreAction()) {
            return;
        }

        // Get record from DB
        $entryCountRecord = EntryCountRecord::find()
            ->where(['entryId' => $entryId])
            ->one();

        // If exists then increment count
        if ($entryCountRecord) {
            $entryCountRecord->setAttribute('count', $entryCountRecord->getAttribute('count') + 1);
        }

        // Otherwise create a new record
        else {
            $entryCountRecord = new EntryCountRecord();
            $entryCountRecord->setAttribute('entryId', $entryId);
            $entryCountRecord->setAttribute('count', 1);
        }

        // Save record in DB
        $entryCountRecord->save();
    }

    /**
     * Reset count
     */
    public function reset(int $entryId)
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

        // Log reset
        Craft::warning(Craft::t('entry-count', 'Entry count with entry ID {entryId} reset by {username}', [
            'entryId' => $entryId,
            'username' => Craft::$app->getUser()->getIdentity()->username,
        ]), 'EntryCount');


        // Fire a 'afterResetCount' event
        if ($this->hasEventHandlers(self::EVENT_AFTER_RESET_COUNT)) {
            $this->trigger(self::EVENT_AFTER_RESET_COUNT, new Event());
        }
    }

    /**
     * Check if action should be ignored
     */
    private function _ignoreAction(): bool
    {
        // Get plugin settings
        $settings = EntryCount::$plugin->settings;

        // Check if logged-in users should be ignored based on settings
        if ($settings->ignoreLoggedInUsers && !Craft::$app->getUser()->getIsGuest()) {
            return true;
        }

        // Check if IP address should be ignored based on settings
        $userIp = Craft::$app->getRequest()->getUserIP();
        $ignoreIpAddresses = explode("\n", $settings->ignoreIpAddresses);

        if ($settings->ignoreIpAddresses && in_array($userIp, $ignoreIpAddresses, true)) {
            return true;
        }

        return false;
    }
}
