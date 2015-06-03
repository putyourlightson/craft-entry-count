<?php
namespace Craft;

/**
 * Entry Count Variable
 */
class EntryCountVariable
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
        return craft()->entryCount->getCount($entryId);
    }

    /**
     * Returns counted entries
     *
     * @return ElementCriteriaModel
     */
    public function getEntries()
    {
        return craft()->entryCount->getEntries();
    }

    /**
     * Increment count
     *
	 * @param int $entryId
     */
    public function increment($entryId)
    {
        craft()->entryCount->increment($entryId);
    }

}
