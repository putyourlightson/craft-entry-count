<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\migrations;

use Craft;
use craft\db\Migration;

/**
 * Entry Count Install Migration
 */
class Install extends Migration
{
    // Public Methods
    // =========================================================================

    /**
     * @return boolean
     */
    public function safeUp(): bool
    {
        if (!$this->db->tableExists('{{%entrycount}}')) {
            $this->createTable('{{%entrycount}}', [
                'id' => $this->primaryKey(),
                'entryId' => $this->integer()->notNull(),
                'key' => $this->string(),                
                'count' => $this->integer()->defaultValue(0)->notNull(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
            ]);

            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

    /**
     * @return boolean
     * @throws \Throwable
     */
    public function safeDown(): bool
    {
        $this->dropTableIfExists('{{%entrycount}}');

        return true;
    }
}
