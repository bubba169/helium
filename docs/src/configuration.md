# Configuration

Helium is based around the idea that everything is configurable and modular. Handlers do all of the heavy lifting, twig templates handle the rendering and the configuration specifies which handlers and templates should be used. Configuration can often be given in short form which is expanded automatically with sensible defaults.

Using fields as an example, providing just the string "title" with no key will generate a text input with it's slug, name and id set to the "title". It will also be given a label by transforming the slug to the more human friendly format "Title". Everything required for that simple text field can be deduced form the slug. 

```php
'fields' => [
    'title',
],
```

![Title Field](img/title_field.png)

Any of the configuration options can be set manually by providing an array as a value with the slug as the key. To override the label we can set it in the configuration array. All other configuration options will continue to be deduced as before and if a value is overridden that is used to deduce another, your overridden value will be used in that deduction. For example, the any validation messages use the field label to build a message; overriding the label will update both.

```php
'fields' => [
    'title' => [
        'label' => 'Item Title'
    ],
],
```

![Title field with customised label](img/title_field_label.png)

## Configurable Objects

Instead of repeating common configurations, we can instead extend a configurable object. All configurable elements have a base class that takes a configuration array and provides sensible defaults. To make use of these we can either set the value to just the configurable object class name or we can pass the object as the `base` option and supply any other options alongside.

```php
'fields' => [
    'published' => DateTimeField::class,
    'type' => [
        'base' => SelectField::class,
        'options' => [
            'a' => 'Model A',
            'b' => 'Model B',
        ],
        'label' => 'Select a type',
    ]
],
```

![A date time field and a select field](img/field_types.png)

Any of the base config classes can be extended to provide extra or more dynamic configuration and some extended configurations are provided by default e.g. the Select field above. Anything provided by the extended options could be recreated using the base type by setting the handlers and templates. The extended options are there for convenience. 

When it comes to setting up something like forms or tables that have many configuration options, some may prefer to manage the configuration by providing new base classes for each view. This can help keep the config files tidy. As an example you might have:

```php
// In the Entity config
'views' => [
    'edit' => EditPostsForm::class,
],
```

```php
class EditPostsForm extends View
{
    public function __construct(array $config, Entity $entity)
    {
        $config = array_merge(
            [
                'fields' => [
                    'title',
                ]
            ],
            $config
        );

        parent::__construct($config, $entity);

        // Any further customisation.
    }
}
```
## Handlers

Handlers are passed to configs as a callable string. Any string that can be called through Laravels `app()->call()` can be given as a value. All of the handlers within Helium are invokable classes.

As an example, to provide options for a select field, instead of an array we can pass a handler. The handler is called through the service container so can use dependency injection and in this case must return an array of options. Whenever a handler is accepted, there will also be a handler parameters option to match. These can provide some arguments that are passed through the service container for dependency injection.

The example below is a simplified use case to give the option of all enabled entries from a model

```php
'fields' => [
    'author_id' => [
        'base' => SelectField::class,
        'options' => ModelOptionsHandler::class,
        'optionsHandlerParams' => [
            'model' => User::class,
            'displayField' => 'name',
        ],
    ]
],
```

```php
class ModelOptionsHandler
{
    public function invoke(string $model, string $displayField): array
    {
        return $model::where('enabled', true)->pluck($displayField, 'id');
    }
}
```

Handlers are used throughout Helium to keep the core functionality as modular and replacable as possible and to allow dynamic configuration. In most cases where a handler is expected, Helium will provide default handlers that can be extended.
