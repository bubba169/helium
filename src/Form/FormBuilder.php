<?php namespace Helium\Form;

use Helium\Form\Form;
use Helium\Support\Entity;
use Illuminate\Support\Arr;
use Helium\FieldTypes\FieldType;
use Illuminate\Support\Collection;
use Helium\FieldTypes\StringFieldType;
use Illuminate\Database\Eloquent\Model;
use Helium\FieldTypes\ReadOnlyTextFieldType;

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
        $fields = $this->entity->getFields();

        return app()->make(Form::class)
            ->setFields($this->buildFields($fields, $instance));
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
     * @return FieldType
     */
    protected function buildField(array $entityField) : FieldType
    {
        $field = app()->make($this->getFieldTypeClass($entityField))
            // Appl any default configuration
            ->mergeConfig(['attributes' => $this->getDefaultFieldAttributes($entityField)])
            // Override with any
            ->mergeConfig($entityField);

        $this->setFieldValue($field, $entityField);

        return $field;
    }

    /**
     * Sets a field's current value
     *
     * @param FieldType $field
     * @return void
     */
    protected function setFieldValue(FieldType $field, array $entityField) : void
    {
        $field->setConfig(
            'value',
            request()->old(
                $field->getName(),
                $this->instance->{$field->getName()} ?? Arr::get($entityField, 'default')
            )
        );
    }

    /**
     * Uses sensible defaults to build field configuration based on the database settings
     *
     * @param array $field The field description
     * @return string The type class name
     */
    protected function getFieldTypeClass(array $field) : string
    {
        if (in_array($field['name'], $this->readOnly)) {
            return ReadOnlyTextFieldType::class;
        }

        return config(
            'helium.database.type_map.' . $field['type'],
            StringFieldType::class
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
}
