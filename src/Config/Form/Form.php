<?php

namespace Helium\Config\Form;

use Helium\Config\Entity;
use Helium\Config\Form\Tab;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;
use Helium\Config\Action;
use Helium\Config\Button;
use Helium\Config\Form\Field\Field;

class Form
{
    use HasConfig;

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

        $form['actions'] = array_normalise_keys(Arr::get($form, 'actions', []), 'slug', 'action');
        foreach ($form['actions'] as $action) {
            $this->actions[$action['slug']] = new Action($action, $entity);
        }

        $form['buttons'] = array_normalise_keys(Arr::get($form, 'buttons', []), 'slug', 'url');
        foreach ($form['buttons'] as $button) {
            $this->buttons[$button['slug']] = new Button($button, $entity);
        }
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'title':
                return Str::plural(str_humanise(Str::camel($this->entity->name)));
            case 'view':
                return 'helium::form';
            case 'messages':
                return [];
        }
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
     * Gets all field rules across all tabs in a single array
     */
    public function validationRules(): array
    {
        // Get an array of validation rules
        return array_filter(
            array_map(fn ($field) => $field->rules, $this->allFields())
        );
    }

    /**
     * Gets all field messages across all tabs in a single array
     */
    public function validationMessages(): array
    {
        // Get an array of validation messages
        return Arr::dot(
            array_filter(
                array_map(fn ($field) => $field->messages, $this->allFields())
            )
        );
    }
}
