<?php

namespace Helium\Config\View\Form;

use Helium\Config\Entity;
use Helium\Config\View\Form\Tab;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Config\View\Form\Field\Field;
use Helium\Config\View\Form\Action\FormAction;
use Helium\Config\View\View;

class FormView extends View
{
    public array $fields = [];
    public array $tabs = [];
    public array $actions = [];

    /**
     * Builds a table config
     */
    public function __construct(array $config, Entity $entity)
    {
        // Set the current config
        parent::__construct($config, $entity);

        $this->fields = $this->buildFields();
        $this->tabs = $this->buildTabs();
        $this->actions = $this->buildActions();
    }

    /**
     * @inheritDoc
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'title':
                return Str::plural(str_humanise(Str::camel($this->entity->name)));
            case 'template':
                return 'helium::form';
            case 'messages':
                return [];
            case 'url':
                return route('helium.entity.action');
            case 'method':
                return 'post';
            case 'fields':
            case 'actions':
                return [];
            case 'tabs':
                return ['main'];
        }

        return parent::getDefault($key);
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
        return call_user_func_array('array_merge', array_values($this->fields));
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
                array_values(
                    array_map(fn ($field) => $field->getValidationRules(), $fields)
                )
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
                    array_values(
                        array_map(fn ($field) => $field->getValidationMessages(), $fields)
                    )
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
                    array_values(
                        array_map(fn ($field) => $field->getValidationAttributes(), $fields)
                    )
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
                array_values(
                    array_map(fn ($field) => $field->getScripts(), $this->allFields())
                )
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
                array_values(
                    array_map(fn ($field) => $field->getStyles(), $this->allFields())
                )
            )
        );
    }

    /**
     * Build the fields from the config
     */
    protected function buildFields(): array
    {
        $config = $this->getConfig('fields');

        if ($this->fieldsHandler) {
            $config = app()->call($this->fieldsHandler, ['view' => $this, 'config' => $config]);
        }

        $fields = [];
        foreach ($config as $tab => $tabFields) {
            $tabFields = array_normalise_keys($tabFields, 'slug', 'base');
            foreach ($tabFields as $field) {
                $field = array_merge(Arr::get($this->entity->fields, $field['slug'], []), $field);
                $class = Arr::get($field, 'base', Field::class);
                $fields[$tab][$field['slug']] = new $class($field, $this->entity);
            }
        }

        return $fields;
    }

    /**
     * Build tabs from the config
     */
    protected function buildTabs(): array
    {
        $config = $this->getConfig('tabs');

        if ($this->tabsHandler) {
            $config = app()->call($this->tabsHandler, ['view' => $this, 'config' => $config]);
        }

        $tabs = [];
        $config = array_normalise_keys($config, 'slug', 'base');

        foreach ($config as $tab) {
            $class = Arr::get($tab, 'base', Tab::class);
            $tabs[$tab['slug']] = new $class($tab, $this->entity);
        }

        return $tabs;
    }

    /**
     * Build actions from the config
     */
    protected function buildActions(): array
    {
        $config = $this->getConfig('actions');

        if ($this->actionsHandler) {
            $config = app()->call($this->actionsHandler, ['view' => $this, 'config' => $config]);
        }

        $actions = [];
        $config = array_normalise_keys($config, 'slug', 'base');
        foreach ($config as $action) {
            $class = Arr::get($action, 'base', FormAction::class);
            $actions[$action['slug']] = new $class($action, $this, $this->entity);
        }

        return $actions;
    }
}
