<?php

namespace Helium\Config\Form;

use Helium\Config\Entity;
use Helium\Config\Form\Tab;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;
use Helium\Config\Table\Action;
use Helium\Config\Form\Field\Field;

class Form
{
    use HasConfig;

    public $fields = [];
    public $tabs = [];
    public $actions = [];

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
                $this->fields[$tab][] = new $class($field, $entity);
            }
        }

        $form['tabs'] = array_normalise_keys(Arr::get($form, 'tabs', ['main' => 'Content']), 'slug', 'label');
        foreach ($form['tabs'] as $tab) {
            $this->tabs[] = new Tab($tab, $entity);
        }

        $form['actions'] = array_normalise_keys(Arr::get($form, 'actions', []), 'slug', 'action');
        foreach ($form['actions'] as $action) {
            $this->actions[] = new Action($action, $entity);
        }
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'title':
                return Str::plural(str_humanise(Str::camel($this->entity->name)));
            case 'view':
                return 'helium::form';
        }
    }
}
