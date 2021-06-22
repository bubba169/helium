# Columns

Columns are used to preview and identify entries as rows in the list. If the configuration given is a string then this will be used as the value option,

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | -- |
| slug<br>(required) | The slug is the identifier for this column in the table data. Most other option defaults are based on this value | string | - |
| label | The heading text that appears at the top of the column | string | The slug, humanised and in title case. |
| view | The view used to render a column cell | string | "helium::table-cell.text" |
| value | The value to show in each table cell. This is a resolved string using the entry for each row. | string | "{entry._slug_}" |