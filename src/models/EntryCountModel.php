<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\models;

use craft\base\Model;
use DateTime;

class EntryCountModel extends Model
{
    /**
     * @var int|null
     */
    public ?int $id = null;

    /**
     * @var int|null
     */
    public ?int $entryId = null;

    /**
     * @var int
     */
    public int $count = 0;

    /**
     * @var DateTime|null
     */
    public ?DateTime $dateCreated = null;

    /**
     * @var DateTime|null
     */
    public ?DateTime $dateUpdated = null;

    /**
     * Define what is returned when model is converted to string
     */
    public function __toString(): string
    {
        return (string)$this->count;
    }
}
