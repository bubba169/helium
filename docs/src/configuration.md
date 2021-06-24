# Configuration

Helium is based around the idea that everything is configurable and modular. Handlers do all of the heavy lifting, twig views handle the rendering and the configuration specifies which handlers and views should be used. Configuration can often be given in short form which is expanded automatically with sensible defaults.

Using fields as an example, providing just the string "title" with no key will generate a text input with it's slug, name and id set to the "title". It will also be given a label by transforming the slug to the more human friendly format "Title". Everything required for that simple text field can be deduced form the slug. 

```php
'fields' => [
    'title',
],
```

Any of the configuration options can be set manually by providing an array of settings with the slug as the key. To override the label we can set it in the configuration array. All other configuration options will continue to be deduced as before and if a value is overridden that is used to deduce another, your overridden value will be used instead. For example, the name of the field in any validation messages is deduced from the field label; overriding the label will update both.

```php
'fields' => [
    'title' => [
        'label' => 'Item Title'
    ],
],
```

## Configurable Objects

Instead of repeating configurations for common patterns, we can instead pass the class of a configurable object. All configurable elements have a base configurable object that can be overridden using the config arrays. To make use of these we can either set the value to just the configurable object class path or we can pass the object as the `config` field and supply any other options alongside.

All configuration is done through laravel's config system.

# Handlers

Handlers usually take the form of invokable classes with a single purpose, although that purpose can sometimes involve calling other handlers. They are called through Laravel's service container allowing access to injected parameters. Often they will have access to special parameters and each of these will be detailed in the relavent section of the documentation.

An example of a handler is the 

