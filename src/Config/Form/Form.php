<?php

namespace Helium\Config\Form;

use Helium\Config\Entity;
use Helium\Traits\HasUrl;
use Helium\Config\Form\Tab;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;
use Helium\Config\Form\Field\Field;
use Helium\Config\Form\Action\FormAction;

class Form
{
    use HasConfig;
    use HasUrl;

    public $fields = [];
    public $tabs = [];
    public $actions = [];
    public $buttons = [];

    protected Entity $entity;

    /**
     * Builds a table config
     *
     * @param string|array $config
     */
    public function __construct($form, Entity $entity)
    {
        // If table is a string use it to call a class to get the initial table config
        if (is_string($form)) {
            $form = app()->call($form, ['entity' => $entity]);
        }

        // Set the current config
        $this->entity = $entity;
        $this->mergeConfig($form);

        foreach (Arr::get($form, 'fields', []) as $tab => $fields) {
            $fields = array_normalise_keys($fields, 'slug', 'field');
            foreach ($fields as $field) {
                $field = array_merge($entity->fields[$field['slug']], $field);
                $class = Arr::get($field, 'field', Field::class);
                $this->fields[$tab][$field['slug']] = new $class($field, $entity);
            }
        }

        $form['tabs'] = array_normalise_keys(Arr::get($form, 'tabs', ['main' => 'Content']), 'slug', 'label');
        foreach ($form['tabs'] as $tab) {
            $this->tabs[$tab['slug']] = new Tab($tab, $entity);
        }

        $form['actions'] = array_normalise_keys(Arr::get($form, 'actions', []), 'slug', 'button');
        foreach ($form['actions'] as $action) {
            $class = Arr::get($action, 'button', FormAction::class);
            $this->actions[$action['slug']] = new $class($action, $this, $entity);
        }
    }

    /**
     * @inheritDoc
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'title':
                return Str::plural(str_humanise(Str::camel($this->entity->name)));
            case 'view':
                return 'helium::form';
            case 'messages':
                return [];
            case 'route':
                return 'helium.entity.action';
            case 'routeParams':
                return [];
        }
    }

    /**
     * Gets a field by its slug. Can be dot notation to find nested fields
     */
    public function getField(array $path) : ?Field
    {
        $field = Arr::get($this->allFields(), array_shift($path), null);

        // No more steps return this field
        if (empty($path) || empty($field)) {
            return $field;
        }

        // Otherwise return a sub-field
        return $field->getField($path);
    }

    /**
     * Gets all field across all tabs in a single array
     */
    public function allFields(): array
    {
        // Merge all of the tabs
        return call_user_func_array('array_merge', $this->fields);
    }

    /**
     * Gets all field rules for validation
     */
    public function validationRules(?string $tab = null): array
    {
        $fields = $tab ? Arr::get($this->fields, $tab, []) : $this->allFields();

        return array_filter(
            call_user_func_array(
                'array_merge',
                array_map(fn ($field) => $field->getValidationRules(), $fields)
            )
        );
    }

    /**
     * Gets all field messages across all tabs in a single array
     */
    public function validationMessages(?string $tab = null): array
    {
        $fields = $tab ? Arr::get($this->fields, $tab, []) : $this->allFields();

        return Arr::dot(
            array_filter(
                call_user_func_array(
                    'array_merge',
                    array_map(fn ($field) => $field->getValidationMessages(), $fields)
                )
            )
        );
    }

    /**
     * Gets all field messages across all tabs in a single array
     */
    public function validationAttributes(?string $tab = null): array
    {
        $fields = $tab ? Arr::get($this->fields, $tab, []) : $this->allFields();

        return Arr::dot(
            array_filter(
                call_user_func_array(
                    'array_merge',
                    array_map(fn ($field) => $field->getValidationAttributes(), $fields)
                )
            )
        );
    }

    /**
     * Gets the scripts from all fields in the form
     */
    public function getScripts() : array
    {
        return array_filter(
            call_user_func_array(
                'array_merge',
                array_map(fn ($field) => $field->getScripts(), $this->allFields())
            )
        );
    }

    /**
     * Gets the styles for all fields in the form
     */
    public function getStyles() : array
    {
        return array_filter(
            call_user_func_array(
                'array_merge',
                array_map(fn ($field) => $field->getStyles(), $this->allFields())
            )
        );
    }
}
