<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\models;

use craft\base\Model;

/**
 * SettingsModel
 */
class SettingsModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var bool
     */
    public $ignoreLoggedInUsers = false;

    /**
     * @var string
     */
    public $ignoreIpAddresses;

    /**
     * @var array  
     */
    public $ignoreSections = [];
}
