# Listings

Listings are the starting point for a user interacting with your entities. The list view is a filterable table of entries. A list has built in functionality to filter and search and paginate. Actions can be added to the table and to each individual row.

## Configuration Options

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | -- |
| view | The template used to render the table | string | "helium::table" |
| title | The title for the table page | string | The plural, humanised entity name |
| listingHandler | The handler used to build the initial listing query | string | "Helium\Handler\DefaultListingHandler" |
| filters | An array of filter configs. | array | - |
| search | A search filter configuration. This field is shown separate to the rest of the filters at the top of the table. If a string is given it will be used as a class name for a filter configuration. | array\|string | - |
| columns | An array of column configs. | array | - |
| actions | An array of action configs. These appear as buttons on the table not specifically tied to any one row. | array | - |
| rowActions | An array of action configs. These appear on each row on the table and should be actions applied to a specific entry. | array | - |
| sort | An array of sorting options. The keys must be formatted as "attribute:direction" and the value is the name of the option as shown to the user. An example would be `["name:asc" => "Name (A-Z)"]`. | array | - |
| with | An array of relationships to load alongside the listing. This uses the usual Laravel conventions. This configuration option is used by the DefualtListingHandler and may be ignored if using a custom handler. | array | - |

## Listing Handlers

Helium provides a default listing handler that will fetch all entries from the entity model. It will apply the search, filtering, sorting and eager loading to the query. An alternative listing handler can be specified by setting the `listingHandler` option to a callable string.

To keep the default functionality it is recommended to extend `Helium\Handler\DefaultListingHandler`, and override the methods of the handler as required. Otherwise, some functionality will cease to work unless re-implemented in your custom handler.

Parameters available for dependency injection are:

| Name | Type | Description |
| -----| ---- | ----------- |
| entity | Helium\Config\Entity | The entity configuration to show the listing for |

The handler is expected to return a LengthAwarePaginator that will be used to show the results in the view. 

## An Example

The following example defines a Posts listing that uses the default view and listing handler. The `Post` model has a relationship named `author` that links it to a `User`.

```php
[
    'filters' => [
        // Give the option to filter entries by whether they are active.
        'active' => BooleanFilter::class,
        // Allows filtering by an author relationship. 
        // This relationship must be defined in the Post model.
        'author' => [
            'field' => BelongsToFilter::class,
            // List authors by name as options.
            'relatedName' => '{entry.name}',
        ]
    ],
    // Allow searching by title or author name
    'search' => [
        'columns' => [
            'title',
            'author.name',
        ]
    ],
    // Adds a title and author column. 
    // The author column will show the related author's name.
    'columns' => [
        'title',
        'author' => [
            'value' => '{entry.author.name}',
        ],
    ],
    // Eager load authors for better performance
    'with' => [
        'author',
    ],
    // Adds a link to an "add" form above the table
    'actions' => [
        'add',
    ],
    'rowActions' => [
        // Adds a link to an "edit" form on each row
        'edit',
        // Adds a delete action for each row
        'delete' => DeleteRowAction::class,
    ],
]
```
