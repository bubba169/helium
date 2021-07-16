# Entities

Entities describe the data you want to manage using Helium. Entities are based on an Eloquent model and describe views that can be used to manage the data. Entites can define the following:

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | -- |
| model<br>(required) | The class of the eloquent model this entity represents | string | - |
| displayColumn<br>(required) | The column on the entity that can be used to visually represent the entry. Usually a title or name field. | string | - |
| views<br>(required) | An array of view configurations | array | - |
| fields<br> | An array of shared field configurations. These can be referenced and reused in any FormView. | array | [] |
| name | A name used to build default values in other configs | string | The model name |

## Views

Views are defined as an array with the keys representing the route. As an example with the following view configuration:

```php
'views' => [
    '*' => [
        'base' => tableView::class
]
