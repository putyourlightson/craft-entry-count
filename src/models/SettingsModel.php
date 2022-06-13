<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\models;

use craft\base\Model;

class SettingsModel extends Model
{
    /**
     * @var bool
     */
    public bool $ignoreLoggedInUsers = false;

    /**
     * @var string
     */
    public string $ignoreIpAddresses = '';
}
