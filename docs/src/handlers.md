# Handlers

Handlers are set in configs as a callable. Anything that can be called through Laravels `app()->call()` can be given as a value. All of the handlers within Helium are invokable classes.

As an example, to modify options for a select field, we can specify an options handler. The handler is called through the service container so can use dependency injection. In this case, the handler will receive an entry and the field and must return an array of options. Whenever a handler is accepted, there will also be a handler parameters option to match. These can provide some arguments that are passed through the service container for dependency injection.

The example below is a simplified use case to give the option of all enabled entries from a model

```php
'fields' => [
    'author_id' => [
        'base' => SelectField::class,
        'optionsHandler' => ModelOptionsHandler::class,
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

Handlers are used throughout Helium to keep the core functionality as modular and replaceable as possible and to allow dynamic configuration. In most cases where a handler is expected, Helium will provide default handlers that can be extended.
