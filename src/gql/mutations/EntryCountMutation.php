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
     * Registers the `incrementEntryCount` mutation.
     *
     * Sample query:
     *
     * mutation ($entryId: ID!) {
     *   incrementEntryCount(entryId: $entryId)
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
            'incrementEntryCount' => [
                'name' => 'incrementEntryCount',
                'description' => 'Increment the entry count for an entry.',
                'args' => ['entryId' => Type::nonNull(Type::id())],
                'type' => Type::boolean(),
                'resolve' => function($source, array $arguments) {
                    EntryCount::$plugin->entryCount->increment($arguments['entryId']);
                    return true;
                },
            ]
        ];
    }
}
