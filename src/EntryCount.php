<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount;

use Craft;
use craft\base\Plugin;
use craft\elements\db\EntryQuery;
use craft\elements\Entry;
use craft\events\DefineBehaviorsEvent;
use craft\events\DefineGqlTypeFieldsEvent;
use craft\events\PopulateElementEvent;
use craft\events\RegisterGqlMutationsEvent;
use craft\gql\TypeManager;
use craft\services\Gql;
use craft\web\twig\variables\CraftVariable;
use GraphQL\Type\Definition\Type;
use putyourlightson\entrycount\behaviors\EntryCountBehavior;
use putyourlightson\entrycount\gql\mutations\EntryCountMutation;
use putyourlightson\entrycount\models\SettingsModel;
use putyourlightson\entrycount\records\EntryCountRecord;
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

        $this->_registerVariable();
        $this->_registerEntryEvents();
        $this->_registerGraphQl();
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
    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('entry-count/settings', [
            'settings' => $this->getSettings()
        ]);
    }

    private function _registerVariable()
    {
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            /** @var CraftVariable $variable */
            $variable = $event->sender;
            $variable->set('entryCount', EntryCountVariable::class);
        });
    }

    private function _registerEntryEvents()
    {
        // Registers the entry count behavior on entries.
        Event::on(
            Entry::class,
            Entry::EVENT_DEFINE_BEHAVIORS,
            function (DefineBehaviorsEvent $event) {
                $event->behaviors['entryCount'] = EntryCountBehavior::class;
            }
        );

        // Joins the entry count table with entry queries.
        Event::on(
            EntryQuery::class,
            EntryQuery::EVENT_BEFORE_PREPARE,
            function (Event $event) {
                /** @var EntryQuery $entryQuery */
                $entryQuery = $event->sender;
                $entryQuery->addSelect('[[entrycount.count]]');
                $entryQuery->leftJoin(
                    ['entrycount' => EntryCountRecord::tableName()],
                    '[[elements.id]] = [[entrycount.entryId]]'
                );
            }
        );

        // Ensures the entry count value is an integer.
        Event::on(
            EntryQuery::class,
            EntryQuery::EVENT_AFTER_POPULATE_ELEMENT,
            function (PopulateElementEvent $event) {
                $entry = $event->element;
                $entry->count = (int)$event->row['count'];
            }
        );
    }

    private function _registerGraphQl()
    {
        // Registers GraphQL type fields.
        Event::on(TypeManager::class, TypeManager::EVENT_DEFINE_GQL_TYPE_FIELDS, function(DefineGqlTypeFieldsEvent $event) {
            if ($event->typeName == 'EntryInterface') {
                $event->fields['count'] = [
                    'name' => 'count',
                    'type' => Type::int(),
                    'resolve' => function ($source) {
                        return $source->count;
                    }
                ];
            }
        });

        // Registers GraphQL mutation definitions
        Event::on(Gql::class, Gql::EVENT_REGISTER_GQL_MUTATIONS, function(RegisterGqlMutationsEvent $event) {
            $event->mutations = array_merge(
                $event->mutations,
                EntryCountMutation::getMutations()
            );
        });
    }
}
