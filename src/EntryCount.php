<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount;

use Craft;
use craft\base\Plugin;
use craft\web\twig\variables\CraftVariable;
use putyourlightson\entrycount\models\SettingsModel;
use putyourlightson\entrycount\services\EntryCountService;
use putyourlightson\entrycount\variables\EntryCountVariable;
use yii\base\Event;

/**
 * EntryCount
 *
 * @property EntryCountService $entryCount
 */
class EntryCount extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var EntryCount
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        self::$plugin = $this;

        // Register services as components
        $this->setComponents([
            'entryCount' => EntryCountService::class,
        ]);

        // Register variable
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            /** @var CraftVariable $variable */
            $variable = $event->sender;
            $variable->set('entryCount', EntryCountVariable::class);
        });
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): SettingsModel
    {
        return new SettingsModel();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('entry-count/settings', [
            'settings' => $this->getSettings()
        ]);
    }
}
