# Views

A view in helium is essentially a route in the CMS related to an entity and is primarily defined by the view handler. Helium provides a default view handler that loads an entry if an id is present in the url and then renders a template. The default parameters for the view class are:

| Config Parameter | Description | Type | Default Value |
| --- | --- | --- | -- |
| base | The base class used for the view. Must extend `Helium\Config\View\View` | string | "Helium\Config\View\View" |
| viewHandler | The handler called to process and render the view. | callable | "Helium\Handler\View\DefaultViewHandler" |
| template<br>(Required for the default view handler) | The path to the template used to render the view. | string | - |

## The View Handler
The only parameter recognised by the default base class is the view handler. This is the code responsible for generating a response from the entity request. The response can be any response recognised by Laravel you like but will most often be a view generated from a twig template.

A view handler can receive the following parameters:

| Parameter | Description | Type |
| --- | --- | --- |
| view | The current view. | Helium\Config\View\View |
| id | The id (if provided) from the url | string|null |

The default view handler renders the template at the path set in the view options and passes the `view` parameter through to the template as a Twig variable. If an `id` is provided in the url, the `entry` will be fetched and passed to the twig template too, otherwise `entry` will be an empty model instance. 
