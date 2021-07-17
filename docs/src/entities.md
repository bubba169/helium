# Entities

Entities describe the data you want to manage using Helium. Entities are based on an Eloquent model and describe views that can be used to manage the data. Entites can define the following:

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | -- |
| model<br>(required) | The class of the eloquent model this entity represents | string | - |
| displayColumn<br>(required) | The column on the entity model that can be used to visually represent the entry. Usually a title or name field. | string | - |
| keyColumn | The column on the entity model that can be used toidentify the entry. | string | "id" |
| views<br>(required) | An array of view configurations | array | - |
| fields<br> | An array of shared field configurations. These can be referenced and reused in any FormView. | array | [] |
| name | A homan friendly name for this Entity. Used to build default values in other configs | string | The model name |

## Views

A view in helium is essentially a screen in the CMS. It must extend the configurable `Helium\Config\View\View` base class. The view is defined by the handler. Helium provides a default view handler that loads an entry if an id is present in the url and then renders a template. The default parameters for the view class are:

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | -- |
| viewHandler | The handler called to process and render the view. | callable | "Helium\Handler\View\DefaultViewHandler" |
| template<br>(Required for the default view handler) | The path to the template used to render the view. | string | - |

### The View Handler
The only parameter recognised by the base class is the view handler. This is the code responsible for generating a response from the entity request. The response can be any response recognised by Laravel you like but will most often be a view generated from a twig template.

A view handler can receive the following parameters:

| Parameter | Description | Type |
| --- | --- | --- |
| view | The current view. | Helium\Config\View\View |
| id | The id (if provided) from the url | string|null |

The default view handler renders the template at the path set in the view options and passes the `view` parameter through to the template as a Twig variable. If an `id` is provided in the url, the `entry` will be fetched and passed to the twig template too, otherwise `entry` will be an empty model instance. 

### Defining Views
Views are defined as either a base class or an array. The keys of the views array represent the route slug. Take the following configuration as an example:

```php
//posts.php 
return [
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

#### The Default View (*)

**Route:** `/entries/posts`  
The default view is what is used when the route doesn't include a view name. It is defined in the view array with the key "*". 

#### Defining With a Base Class

**Route:** `/entries/posts/edit/1`  
Like with other configurations, the configuration can be defined by just a base class. This class will be used instead of the default `View` class when building the view. The class provided must extend from `Helium\Confg\View\View`.

#### Extending or Aliasing Another View

**Route:** `/entries/posts/add`  
View configurations allow extending another view by passing its key as the base class. Any options provided for the new view will be merged with the extended view.

#### Using a Template

**Route:** `/entries/posts/view/1`  
If all that's required is a template with no extra handler logic, a template path can be provided. This template will be rendered and the `view` amd `entry` will be accessible as Twig variables.
