# Entry Count Plugin for Craft CMS

The Entry Count Plugin was built specifically for training purposes and is used in the [Craft Plugin Development video course](https://mijingo.com/products/screencasts/craft-plugin-development/).

It allows you to count and display the number of times that an entry has been viewed.

## Requirements

Craft CMS 3.0.0 or later.

## Installation

To install the plugin, search for "Entry Count" in the Craft Plugin Store, or install manually using composer.

    composer require putyourlightson/entry-count

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