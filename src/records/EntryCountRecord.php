<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\records;

use craft\db\ActiveRecord;

/**
 * EntryCountRecord
 *
 * @property int $id
 * @property int $entryId
 * @property int $count
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
