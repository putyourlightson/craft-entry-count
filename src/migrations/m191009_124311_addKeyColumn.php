<?php

namespace putyourlightson\entrycount\migrations;

use Craft;
use craft\db\Migration;

/**
 * m191009_124311_addKeyColumn migration.
 */
class m191009_124311_addKeyColumn extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (!$this->db->columnExists('{{%entrycount}}', 'key')) {
            $this->addColumn('{{%entrycount}}', 'key', $this->string()->after('entryId'));
        } 
 
        $this->dropForeignKey('entrycount_entryId_fk', '{{%entrycount}}');             
        $this->dropIndex('entrycount_entryId_unq_idx', '{{%entrycount}}');
	}
	
    /**
     * @inheritdoc
     */
    public function safeDown()
    {        
        if ($this->db->columnExists('{{%entrycount}}', 'key')) {
	        $this->dropColumn('{{%entrycount}}', 'key');	        
	    }

        $this->createIndex(null, '{{%entrycount}}', 'entryId', true);
        $this->addForeignKey(null, '{{%entrycount}}', 'entryId', '{{%elements}}', 'id', 'CASCADE');
        
        return true;
    }
}
