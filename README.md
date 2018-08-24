<p align="center"><img width="200" src="src/icon.svg"></p>

# Entry Count Plugin for Craft CMS 3

The Entry Count Plugin was built specifically for training purposes and is used in the [Craft Plugin Development video course](https://mijingo.com/products/screencasts/craft-plugin-development/).

It allows you to count and display the number of times that an entry has been viewed in  [Craft CMS](https://craftcms.com/).

## Requirements

Craft CMS 3.0.0 or later.

## Installation

To install the plugin, search for "Entry Count" in the Craft Plugin Store, or install manually using composer.

    composer require putyourlightson/craft-entry-count

## Twig Tags

**count(entry.id)**

    {% set count = craft.entryCount.count(entry.id) %}

    Entry count: {{ count }}
    First count: {{ count.dateCreated }}
    Last count: {{ count.dateUpdated }}

**entries**

    {% set entries = craft.entryCount.entries.all() %}

    {% for entry in entries %}
        {% set count = craft.entryCount.count(entry.id) %}
        {{ entry.title }} ({{ count }} views)
    {% endfor %}

**increment(entry.id)**

    {% do craft.entryCount.increment(entry.id) %}
    
*Plugin icon by [Made by Made from the Noun Project](https://thenounproject.com/made.somewhere/collection/counting/?i=864431)*
