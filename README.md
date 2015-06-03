#Craft Entry Count Plugin

The Entry Count Plugin was built specifically for training purposes and is used in the Craft Plugin Development video course.

It allows you to count the number of times that an entry has been viewed.

##Twig Tags

**count(entry.id)**

    {% set count = craft.entryCount.count(entry.id) %}

    Entry count: {{ count }}
    First count: {{ count.dateCreated }}
    Last count: {{ count.dateUpdated }}

**entries**

    {% set countedEntries = craft.entryCount.entries %}

    {% for entry in entries %}
        {% set count = craft.entryCount.count(entry.id) %}
        {{ entry.title }} ({{ count }} views)
    {% endfor %}

**increment(entry.id)**

    {% do craft.entryCount.increment(entry.id) %}
