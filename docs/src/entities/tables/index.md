# The Table View

Table views are usually the starting point for a user interacting with your entities. Helium provides a configurable table view with provisions for filtering, search, sorting and row actions. 

## Configuration Options

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | -- |
| template | The template used to render the table | string | "helium::table" |
| title | The title for the table page | string | The plural, humanised entity name |
| listingHandler | The handler used to build the listing query. | string | "Helium\Handler\DefaultListingHandler" |
| filters | An array of filter configurations. | array | [] |
| filtersHandler | A handler for providing filter configuration. | string | - |
| search | A search filter configuration or a path to a base class that extends `Helium\Config\View\Table\Filters\SearchFilter`. | array\|string | - |
| searchHandler | A handler for providing search configuration. | string | - |
| columns | An array of column configurations. | array | [entity key attribute, entity display attribute] |
| columnsHandler | A handler for providing column configuration. | string | - |
| actions | An array of action configurations. These appear as buttons on the table not specifically tied to any one row. | array | [] |
| actionsHandler | A handler for providing actions configuration. | string | - |
| rowActions | An array of action configs. These appear on each row on the table and should be actions applied to a specific entry. | array | [] |
| rowActionsHandler | A handler for providing row actions. This handler is also provided the entry so can be used to make conditional adjustments to the row actions. | string | - |
| sort | An array of sorting options. The keys must be formatted as "attribute:direction" and the value is the name of the option as shown to the user. An example would be `["name:asc" => "Name (A-Z)"]`. | array | [] |
| with | An array of relationships to load alongside the listing. This uses the usual Laravel syntax. | array | - |

## Listing Handlers

Helium provides a default listing handler that will fetch all entries from the entity model. It will apply the search, filtering, sorting and eager loading to the query. An alternative listing handler can be specified by setting the `listingHandler` option to a callable string.

!!!note
    To avoid having to recreate the default functionality, it is recommended to extend `Helium\Handler\DefaultListingHandler` and override individual methods as required.

Parameters available for dependency injection are:

| Name | Type | Description |
| -----| ---- | ----------- |
| view | Helium\Config\View\View | The view configuration |

The handler is expected to return a `LengthAwarePaginator` that will be used to show the results in the view. 

# Columns

Columns are used to preview and identify entries as rows in the list. If the configuration given is a string then this will be used as the base class. Column classes should extend `Helium\Config\View\Table\Column`

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | -- |
| slug<br>(required) | The slug is the identifier for this column in the table data. Most other option defaults are based on this value | string | - |
| label | The heading text that appears at the top of the column | string | The slug, humanised and in title case. |
| template | The view used to render a column cell | string | "helium::table-cell.text" |
| value | The value to show in each table cell. This is a resolved string using the entry for each row. | string | "{entry._slug_}" |

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
