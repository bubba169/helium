# Entities

Entities describe the data you want to manage using Helium. Entities are based on an Eloquent model and describe views that can be used to manage the data. Entites can define the following:

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | -- |
| model<br>(required) | The class of the eloquent model this entity represents | string | - |
| displayAttribute<br>(required) | The attribute on the entity model that can be used to visually represent the entry. Usually a title or name field. | string | - |
| views<br>(required) | An array of view configurations | array | - |
| keyAttribute | The attribute on the entity model that can be used to identify the entry. | string | "id" |
| fields<br> | An array of shared field configurations. These can be referenced and reused in any FormView. | array | [] |
| name | A human friendly name for this Entity. Used to build default values in other configs | string | The model name |

## Defining Views
Views are defined as either a base class or an array. The keys of the views array represent the route slug. The route takes the format `/entities/{type}/{view}/{id}` where `type` is the entity slug, `view` is the view slug and `id` is a string that's the primary key of an entry. Take the following configuration as an example:

```php
// posts entity configuration
[
    'model' => Post::class,
    'displayColumn' => 'title',
    'views' => [
        '*' => TableView::class,
        'edit' => EditPostsView::class,
        'add' => 'edit',
        'view' => ['template' => 'posts.view'],
    ],
];
```

### The Default View (*)

**Route:** `/entries/posts`  
The default view is what is used when the route doesn't include a view name. It is defined in the view array with the key "*". This will often be a listing.

### Defining With a Base Class

**Route:** `/entries/posts/edit/1`  
Like with other configurations, the view can be defined by just a base class. This class will be used instead of the default `View` class when building the view. The class provided must extend from `Helium\Confg\View\View`.

### Extending or Aliasing Another View

**Route:** `/entries/posts/add`  
View configurations allow extending another view from the same entity by passing its key as the base class. Any options provided for the extended view will be merged with those given for the original view.

### Using a Template

**Route:** `/entries/posts/view/1`  
If all that's required is a template with no extra handler logic, a template path can be provided. This template will be rendered and the `view` amd `entry` will be accessible as Twig variables.

