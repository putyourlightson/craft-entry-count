<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\gql\mutations;

use craft\gql\base\Mutation;
use GraphQL\Type\Definition\Type;
use putyourlightson\entrycount\EntryCount;

class EntryCountMutation extends Mutation
{
    /**
     * Registers the `resetCount` mutation.
     *
     * Sample query:
     *
     * mutation ($entryId: ID!) {
     *   resetEntryCount(entryId: $entryId)
     * }
     *
     * Query variables:
     * {
     *   "entryId": 247
     * }
     *
     */
    public static function getMutations(): array
    {
        return [
            'resetCount' => [
                'name' => 'resetEntryCount',
                'description' => 'Reset the entry count for an entry.',
                'args' => ['entryId' => Type::nonNull(Type::id())],
                'type' => Type::boolean(),
                'resolve' => function($source, array $arguments) {
                    EntryCount::$plugin->entryCount->reset($arguments['entryId']);
                    return true;
                },
            ]
        ];
    }
}
