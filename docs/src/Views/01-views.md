# Overview

Views are essentially pages in the CMS. They are a window into your data. Helium provides a default configurable form and table view type but is not limited to just these types. Starting with a view handler and a template, you can extend Helium to display pretty much anything.

## Configuring Views
To add a view to an entity, add it to the `views` configuration option. A view can be specified as either class name, an existing view slug or a configuration array. Helium provides two default views, a table view and a form. 

## Routing 
Helium will automatically route to your views based on the key in the `views` array using the format `/admin/entities/[entity-slug]/[view-slug]/[entry-id]`. Consider the following for a `posts` entity:

```php
'views' => [
    '*' => [
        'base' => TableView::class,
        'columns' => [
            'title',
        ]
    ],
    'edit' => [
        'base' => FormView::class,
        'fields' => [
            'main' => [
                'title'
            ],
        ],
        'actions' => [
            'save' => SaveFormAction::class,
        ]
    ],
    'add' => 'edit',
]
```

The above would create three views. The first view, available at the default entity route `/admin/entities/posts`, would be a table view. The second would be a form available at `/admin/entities/posts/edit/[entry-id]`. The third would be another form that extends the second available at `/admin/entities/posts/add`.

## The Default View Handler

**Class:** `Halium\Handler\View\DefaultViewHandler`

Helium provides a default view handler which loads a template and 
