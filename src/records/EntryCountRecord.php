<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\records;

use craft\db\ActiveRecord;

/**
 * EntryCountRecord
 *
 * @property int         $id                         ID
 * @property int         $entryId                    Entry ID
 * @property int         $count                      Count
 */
class EntryCountRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

     /**
     * @inheritdoc
     *
     * @return string the table name
     */
    public static function tableName(): string
    {
        return '{{%entrycount}}';
    }
}
