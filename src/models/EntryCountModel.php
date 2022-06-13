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
    public ?int $id;

    /**
     * @var int|null
     */
    public ?int $entryId;

    /**
     * @var int
     */
    public int $count = 0;

    /**
     * @var DateTime|null
     */
    public ?DateTime $dateCreated;

    /**
     * @var DateTime|null
     */
    public ?DateTime $dateUpdated;

    /**
     * Define what is returned when model is converted to string
     */
    public function __toString(): string
    {
        return (string)$this->count;
    }
}
