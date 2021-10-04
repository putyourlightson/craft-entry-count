<p align="center"><img width="200" src="src/icon.svg"></p>

# Entry Count Plugin for Craft CMS 3

The Entry Count Plugin was built specifically for training purposes and is used in the [Craft Plugin Development video course](https://mijingo.com/products/screencasts/craft-plugin-development/).

It allows you to count and display the number of times that an entry has been viewed in  [Craft CMS](https://craftcms.com/). The plugin is intended for educational purposes and can be freely used for simple use-cases. For anything more complex, use the [View Count](https://www.doublesecretagency.com/plugins/view-count) plugin.

## GraphQL Support

GraphQL support has been added in this branch and can be used as follows.

```gql
query EntriesOrderedByCountDesc {
  entries(orderBy: "count desc") {
    id
    count
  }
}
```

The entry count of an entry can be reset using a mutation.

```gql
mutation ResetEntryCount($entryId: ID!) {
  resetEntryCount(entryId: $entryId)
}
```

An entry ID must be provided via query variables.

```gql
{
  "entryId": 147
}
```

## Documentation

Learn more and read the documentation at [putyourlightson.com/plugins/entry-count »](https://putyourlightson.com/plugins/entry-count)

## License

This plugin is licensed for free under the MIT License.

## Requirements

Craft CMS 3.0.0 or later.

## Installation

To install the plugin, search for "Entry Count" in the Craft Plugin Store, or install manually using composer.

    composer require putyourlightson/craft-entry-count

*Plugin icon by [Made by Made from the Noun Project](https://thenounproject.com/made.somewhere/collection/counting/?i=864431)*

<small>Created by [PutYourLightsOn](https://putyourlightson.com/).</small>
