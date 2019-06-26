<?php

namespace putyourlightson\entrycount\events;

use craft\events\CancelableEvent;

/**
 * Class Name
 */
class EntryCountEvent extends CancelableEvent
{
    // Properties
    // =========================================================================

    /**
     * @var int
     */
    public $entryId;

}
