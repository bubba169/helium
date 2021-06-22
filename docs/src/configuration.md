# Configuration

Helium is based around the idea that everything is configurable and modular. Handlers do all of the heavy lifting and the configuration specifies which handlers should be used. Configuration can often be given in short form which is expanded automatically with sensible defaults.

As an example with fields, to define a simple text field that is tied to the `title` attribute on a model, you can define that with just a string slug that matches the attribute name. If a field needs to be a different type, for example a HTML field, the field type can be specified as a string value. Some fields, such as relationship type fields, require more configuration and these can only be defined as field configuration arrays.

Configuration arrays will override any of the sensible defaults usually applied to any configurable object and new options can be added here that can be accessed as dynamic attributes in the templates and handlers.

An example of valid configurations for fields:

```php
'fields' => [
    // This will provide a text field labelled "Title" that on save will 
    // update the title attribute on the entity model.
    'title',
    // This will provide a TinyMCE field labelled "Body" that will update
    // the body attribute on the entity model.
    'body' => TinyField::class,
    // This provides a date type field with a label different to the attribute name
    'created_at' => [
        'field' => DateField::class,
        'label' => 'Date Created',
    ],
],
```

All configuration is done through laravel's config system.

# Handlers

Handlers usually take the form of invokable classes with a single purpose, although that purpose can sometimes involve calling other handlers. They are called through Laravel's service container allowing access to injected parameters. Often they will have access to special parameters and each of these will be detailed in the relavent section of the documentation.

An example of a handler is the 

