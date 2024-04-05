<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Table;
use putyourlightson\entrycount\records\EntryCountRecord;

class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        if (!$this->db->tableExists(EntryCountRecord::tableName())) {
            $this->createTable(EntryCountRecord::tableName(), [
                'id' => $this->primaryKey(),
                'entryId' => $this->integer()->notNull(),
                'count' => $this->integer()->defaultValue(0)->notNull(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
            ]);

            $this->createIndex(null, EntryCountRecord::tableName(), 'entryId', true);

            $this->addForeignKey(null, EntryCountRecord::tableName(), 'entryId', Table::ELEMENTS, 'id', 'CASCADE');

            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropTableIfExists(EntryCountRecord::tableName());

        return true;
    }
}
