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
 * @property-read EntryCountService $entryCount
 * @property-read SettingsModel $settings
 */
class EntryCount extends Plugin
{
    /**
     * @var EntryCount
     */
    public static EntryCount $plugin;

    /**
     * @inheritdoc
     */
    public static function config(): array
    {
        return [
            'components' => [
                'entryCount' => ['class' => EntryCountService::class],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public bool $hasCpSection = true;

    /**
     * @inheritdoc
     */
    public bool $hasCpSettings = true;

    /**
     * @inheritdoc
     */
    public string $schemaVersion = '2.0.0';

    /**
     * @inheritdoc
     */
    public string $minVersionRequired = '2.0.3';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        // Register variable
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            /** @var CraftVariable $variable */
            $variable = $event->sender;
            $variable->set('entryCount', EntryCountVariable::class);
        });
    }

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
    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('entry-count/settings', [
            'settings' => $this->getSettings(),
        ]);
    }
}
