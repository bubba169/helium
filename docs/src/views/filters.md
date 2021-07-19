

# Filters

Filters limit the entries shown in the list to those matching a set of criteria. Filters are fields and have all of the same configuration options available to them. Filters are only applied if they have a value set.

## Base Filter

The base filter class provides a simple free-form text field that filters one attribute on an entry. If only a slug is provided it will filter by that one attribute. THe table below shows the additional options and defaults provided by the base filter.

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | -- |
| slug<br>(required) | The filter slug used to identify the filter in the table config | string | - |
| filterHandler | The handler to use to apply this filter to the listing query. | string | "Helium\Handlers\Filter\DefaultFilterHandler" |
| valueHandler | The handler used to set the current value of the input | string | "Helium\Handler\Value\RequestValueHandler" |
| placeholder | The input placeholder | string | "Filter by XYZ" where XYZ is the filter slug, humanised and title cased. |
| value | The value to fill the input | string | "{request._slug_}" |

## Specialised Filters

The following filters are provided by Helium. To make use of any of these presets, set the value of the filter entry to the class name. Alternatively, if you want to add more configuration, set the class name as the `field` attribute.

### Boolean Filter

**Field Class:** `Helium\Config\Table\Filter\BooleanFilter`

The boolean filter uses a select with the options "Yes" and "No". This should be used rather than a checkbox as it also allows an unset state. A boolean filter handler is provided to translate the yes/no value to a true or false condition for the query.

### Belongs To Filter

**Field Class:** `Helium\Config\Table\Filter\BelongsToFilter`

The belongs to filter provides a list of possible values for a relationship that can be used to filter entries. As an example, if I have posts that are related to an author, I can filter by author. This filter works with any relationship type but can only filter by one value at a time. It is not possible to use the shorthand option to specify this type as it has required parameters.

Additional options are:

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | --- |
| relatedName<br>(required) | The string representation of the related entry to show as an option. This is a resolved string so can use values from the related entry e.g. "{entry.name}" | string | - |
| relationship | The name of the relationship on the listed entry model. | string | _slug_
