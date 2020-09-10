<?php namespace Helium\Form;

use Helium\Form\Form;
use Helium\Support\Entity;
use Illuminate\Support\Arr;
use Helium\Form\Field\Field;
use Illuminate\Support\Collection;
use Helium\Form\Field\TextField;
use Illuminate\Database\Eloquent\Model;
use Helium\Form\Field\ReadOnlyTextField;

class FormBuilder
{
    /**
     * @var Entity
     */
    protected $entity = null;

    /**
     * @var Model
     */
    protected $instance = null;

    /**
     * An array of columns to skip when building the form
     *
     * @param array $skip
     */
    protected $skip = [];

    /**
     * Sets the layout of the form elements
     *
     * @var array
     */
    protected $sections = [];

    /**
     * Additional config for form fields. This is merged with the
     * entity configuration.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * These fields will be rendered as plain text instead of being editable
     *
     * @var array
     */
    protected $readOnly = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Construct
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Sets the instance to populate the form
     *
     * @param Model $instance
     * @return self
     */
    public function setInstance(?Model $instance) : self
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * Gets the current instance
     *
     * @return Model
     */
    public function getInstance() : Model
    {
        return $this->instance;
    }

    /**
     * Builds a form descriptor for the view
     *
     * @param Model|null $model
     * @return Form
     */
    public function getForm(?Model $instance = null) : Form
    {
        return app()->make(Form::class)
            ->setFields($this->buildFields($this->getFields(), $instance))
            ->setSections($this->getSections());
    }

    /**
     * Gets the field configuration from the entity and any custom
     * properties
     *
     * @return array
     */
    public function getFields() : array
    {
        return array_merge_deep(
            $this->entity->getFields(),
            $this->fields
        );
    }

    /**
     * Builds and populates the form fields based on the entity fields
     * and the instance
     *
     * @param array $entityFields
     * @return Collection
     */
    protected function buildFields(array $entityFields) : Collection
    {
        return collect($entityFields)->mapWithKeys(function ($entityField, $name) {
            // If in the skip list, return null to be filtered out later
            if (in_array($name, $this->skip)) {
                return [$name => null];
            }

            return [$name => $this->buildField($entityField)];
        })->filter();
    }

    /**
     * Builds a form field type from the descriptor
     *
     * @param array $field The field descriptor
     * @return Field
     */
    protected function buildField(array $entityField) : Field
    {
        $field = app()->make($this->getFieldClass($entityField))
            // Appl any default configuration
            ->mergeConfig([
                'name' => $entityField['name'],
                'attributes' => $this->getDefaultFieldAttributes($entityField)
            ]);

        // If options is a string it'll either be an entity type or a handler class
        if (is_string(Arr::get($entityField, 'options'))) {
            $field->setConfig('options', $this->buildFieldOptions($entityField));
        }

        $this->setFieldValue($field, $entityField);

        return $field;
    }

    /**
     * Sets a field's current value
     *
     * @param Field $field
     * @return void
     */
    protected function setFieldValue(Field $field, array $entityField) : void
    {
        $relationship = Arr::get($entityField, 'relationship');
        $name = Arr::get($entityField, 'name');
        $old = request()->old();

        $value = null;
        if (array_key_exists($name, $old)) {
            $value = $old[$name];
        } elseif ($relationship && $this->instance) {
            $value = $this->instance->{$relationship}->pluck('id');
        } elseif ($this->instance) {
            $value = $this->instance->{$name};
        }

        $field->setConfig('value', $value);
    }

    /**
     * Uses sensible defaults to build field configuration based on the database settings
     *
     * @param array $field The field description
     * @return string The type class name
     */
    protected function getFieldClass(array $field) : string
    {
        if (in_array($field['name'], $this->readOnly)) {
            return ReadOnlyTextField::class;
        }

        return config(
            'helium.field_types.' . $field['type'],
            TextField::class
        );
    }

    /**
     * Builds a default column config from the database settings
     *
     * @param array $field The field description
     * @return array
     */
    protected function getDefaultFieldAttributes(array $field) : array
    {
        $attributes = [];

        // Return early - no attributes for these
        if (in_array($field['name'], $this->readOnly)) {
            return $attributes;
        }

        // If the field is a string set its max length
        if (in_array($field['type'], ['string', 'text'])) {
            $attributes['maxlength'] = $field['length'];
        }

        // The id field should be hidden by default as this
        // shouldn't be changed on a form
        if ($field['name'] === 'id') {
            $attributes['type'] = 'hidden';
        // Booleans will display as checkboxes by default
        } elseif ($field['type'] === 'boolean') {
            $attributes['type'] = 'checkbox';
        // For string field types see if it can be refined
        } elseif ($field['type'] === 'string') {
            // If the field name contains "email" assume it's an email type
            if (strpos($field['name'], 'email') !== false) {
                $attributes['type'] = 'email';
                $attributes['inputmode'] = 'email';
            // If the field name contains "phone" assume it's a phone number
            } elseif ($field['name'] === 'phone') {
                $attributes['type'] = 'tel';
                $attributes['inputmode'] = 'tel';
            // If the field name contains "password" assume it's a password
            } elseif ($field['name'] === 'password') {
                $attributes['type'] = 'password';
            // If the field name contains "url" assume it's a url
            } elseif (strpos('url', $field['name']) !== false) {
                $attributes['inputmode'] = 'url';
                $attributes['type'] = 'text';
            } else {
                $attributes['type'] = 'text';
            }
        // For numeric types
        } elseif (in_array($field['type'], ['integer', 'bigint', 'smallint', 'decimal', 'float'])) {
            $attributes['inputmode'] = 'numeric';
            $attributes['type'] = 'number';
            if ($field['unsigned']) {
                $attributes['min'] = 0;
            }
            if ($field['precision'] > 0) {
                $attributes['step'] = pow(10, -$field['precision']);
            }
        // For everything else
        } else {
            $attributes['type'] = 'text';
        }

        return $attributes;
    }

    /**
     * Builds options for relationships
     *
     * @param array $entityField
     * @return array
     */
    protected function buildFieldOptions(array $entityField) : array
    {
        // If the string matches an entity slug get the options from the entity repository
        if ($entityClass = config('helium.entities.' . $entityField['options'])) {
            if (class_exists($entityClass)) {
                return app()->make($entityClass)->getRepository()->dropdownOptions();
            }
            return [];
        // If the string is a callable class call it and return the options
        } elseif (class_exists(explode('@', $entityField['options'])[0])) {
            return app()->call($entityField['options'], ['field' => $entityField]);
        // If the string is a colon separated list e.g. "1:Option 1;2:Option 2;"
        } elseif (str_contains($entityField['options'], ':')) {
            return array_reduce(
                explode(';', trim($entityField['options'], ';: ')),
                function ($options, $item) {
                    $parts = explode(':', $item);
                    $options[$parts[0]] = $parts[1];
                    return $options;
                },
                []
            );
        }

        // Just return the string as the only option
        return [$entityField['options']];
    }

    /**
     * Gets the form layout
     *
     * @return array
     */
    protected function getSections()
    {
        return $this->sections;
    }
}
