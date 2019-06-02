<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\models;

use craft\base\Model;
use craft\validators\DateTimeValidator;

/**
 * EntryCountModel
 */
class EntryCountModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var int|null Entry ID
     */
    public $entryId;

    /**
     * @var int Count
     */
    public $count = 0;

    /**
     * @var \DateTime|null Date created
     */
    public $dateCreated;

    /**
     * @var \DateTime|null Date updated
     */
    public $dateUpdated;

    // Public Methods
    // =========================================================================

    /**
     * Define what is returned when model is converted to string
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->count;
    }

    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [['entryId', 'count'], 'number'];
        $rules[] = [['dateCreated', 'dateUpdated'], DateTimeValidator::class];

        return $rules;
    }
}
