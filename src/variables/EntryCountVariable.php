<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\variables;

use craft\elements\db\EntryQuery;
use putyourlightson\entrycount\EntryCount;
use putyourlightson\entrycount\models\EntryCountModel;

class EntryCountVariable
{
    /**
     * Returns count
     */
    public function getCount(int $entryId): EntryCountModel
    {
        return EntryCount::$plugin->entryCount->getCount($entryId);
    }

    /**
     * Returns counted entries
     */
    public function getEntries(): EntryQuery
    {
        return EntryCount::$plugin->entryCount->getEntries();
    }

    /**
     * Increment count
     */
    public function increment(int $entryId): void
    {
        EntryCount::$plugin->entryCount->increment($entryId);
    }
}
