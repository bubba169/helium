# Lists

Lists are the starteing point for a user interacting with your entities. The list view is a filterable table of entries.

## Columns

Columns are used to preview and identify entries as rows in the list. Configuration options are:

Columns can either be defined as a single string slug or they can be expanded as an array to configure the other options.

| Config Parameter | Description | Default Value | Required |
| --- | --- | --- | -- |
| slug | The slug is the identifier for this column in the table data. Most other option defaults are based on this value | - | Yes |
| label | The heading text that appears at the top of the column | The slug, humanised and in title case. | No |
| view | The view used to render a column cell | "helium::table-cell.text" | No |
| value | The value to show in each table cell. This is a resolved string using the entry for each row. | "{entry.xyz}", where xyz is the column slug | No |

## Filters

Filters limit the entries shown in the list to those matching a set of criteria. Filters are fields and have all of the same configuration options available to them.

For a simple free text filter that filters one attribute on an entry, only a slug is required. Other options can be specified as required.

| Config Parameter | Description | Default Value | Required |
| --- | --- | --- | -- |
| slug | The filter slug used to identify the filter in the table config | - | Yes |
| filterHandler | The handler to use to apply this filter to the listing query. | Helium\Handlers\Filter\DefaultFilterHandler | No |
| valueHandler | The handler used to set the current value of the input | Helium\Handler\Value\RequestValueHandler | No |
| placeholder | The input placeholder | "Filter by xyz" where xyz is the filter slug, humanised and title cased. | No |
| value | The value to fill the input | {request.xyz} where xyz is the filter slug. | No



## Searching

Searching is a built in feature of lists and is a simple text search 