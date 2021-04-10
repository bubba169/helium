<?php

namespace Helium\Support;

use Helium\Form\FormHandler;
use Helium\Form\RelatedOptionsHandler;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityConfig
{
    protected const DEFAULT = [
        'table' => [
            'view' => 'helium::table',
            'columns' => [],
            'actions' => [],
        ],
        'fields' => [],
        'forms' => [
            '*' => [
                'view' => 'helium::form',
                'slug' => '*',
                'tabs' => [],
                'fields' => [
                    'main' => []
                ],
                'actions' => [],
            ]

        ]
    ];

    /**
     * Builds a table config. This will also normalise the data and fill in the blanks
     */
    public function getConfig(string $entityName) : array
    {
        $config = config('helium.entities.' . $entityName);

        if (empty($config)) {
            throw new NotFoundHttpException();
        }

        $config = array_merge_deep(self::DEFAULT, $config);

        $config['slug'] = $entityName;
        if (!array_key_exists('name', $config)) {
            $config['name'] = class_basename($config['model']);
        }

        $config = $this->normaliseTable($config);
        $config = $this->normaliseFields($config);
        $config = $this->normaliseForms($config);

        return $config;
    }

    /**
     * Builds a table config. This will also normalise the data and fill in the blanks
     */
    protected function normaliseTable(array $config) : array
    {
        // Fill in the title
        if (!array_key_exists('title', $config['table'])) {
            $config['table']['title'] = Str::plural(str_humanise(Str::camel($config['name'])));
        }

        $config = $this->normaliseTableColumns($config);
        $config['table']['actions'] = $this->normaliseActions($config['table']['actions'], $config);

        return $config;
    }

    /**
     * Normalises the columns and fills in the gaps with sensible defaults
     */
    protected function normaliseTableColumns(array $config) : array
    {
        // Normalise columns
        $config['table']['columns'] = array_normalise_keys($config['table']['columns']);
        foreach ($config['table']['columns'] as &$column) {
            // Reolve the name as the value if not set
            if (!array_key_exists('value', $column)) {
                $column['value'] = '{entry.' . $column['name'] . '}';
            }
            // Try to build a label from the value
            if (!array_key_exists('label', $column)) {
                $column['label'] = Str::title(str_humanise($column['name']));
            }
            // Default to a plain text cell
            if (!array_key_exists('view', $column)) {
                $column['view'] = 'helium::table-cell.text';
            }
        }
        return $config;
    }

    /**
     * Builds a table config. This will also normalise the data and fill in the blanks
     */
    protected function normaliseFields(array $config) : array
    {
       // Normalise actions
        $config['fields'] = array_normalise_keys($config['fields'], 'name', 'type');
        foreach ($config['fields'] as &$field) {
            // Set the label to the humanised field name by default
            if (!array_key_exists('label', $field)) {
                $field['label'] = Str::title(str_humanise($field['name']));
            }
            // Set the type to text by default
            if (!array_key_exists('type', $field)) {
                $field['type'] = 'text';
            }
            // Set the id to the field name by default
            if (!array_key_exists('id', $field)) {
                $field['id'] = $field['name'];
            }
            // Set the column to the field name by default
            if (!array_key_exists('column', $field)) {
                $field['column'] = $field['name'];
            }
            // Set the value to resolve the column on the entry by default
            if (!array_key_exists('value', $field)) {
                $field['value'] = '{entry.' . $field['column'] . '}';
            }
            // Set the view based on the type
            if (!array_key_exists('view', $field)) {
                switch ($field['type']) {
                    case 'select':
                    case 'belongsTo':
                        $field['view'] = 'helium::form-fields.select';
                        break;
                    case 'belongsToMany':
                    case 'multicheck':
                        $field['view'] = 'helium::form-fields.multicheck';
                        break;
                    case 'radio':
                        $field['view'] = 'helium::form-fields.radios';
                        break;
                    case 'checkbox':
                        $field['view'] = 'helium::form-fields.checkbox';
                        break;
                    case 'textarea':
                        $field['view'] = 'helium::form-fields.textarea';
                        break;
                    case 'datetime':
                        $field['view'] = 'helium::form-fields.datetime';
                        break;
                    case 'password':
                        $field['view'] = 'helium::form-fields.password';
                        break;
                    default:
                        $field['view'] = 'helium::form-fields.input';
                }
            }

            if (in_array($field['type'], ['belongsTo', 'belongsToMany'])) {
                if (!array_key_exists('options', $field)) {
                    $field['options'] = RelatedOptionsHandler::class;
                }
                if (!array_key_exists('related_id', $field)) {
                    $field['related_id'] = 'id';
                }
                if (!array_key_exists('relationship', $field)) {
                    $field['relationship'] = $field['name'];
                }
            }

            if (array_key_exists('options', $field) &&
                is_string($field['options']) &&
                strpos($field['options'], '@') === false
            ) {
                $field['options'] .= '@handle';
            }
        }

        return $config;
    }

    protected function normaliseForms(array $config): array
    {
        $default = $this->normaliseForm($config['forms']['*'], $config);
        unset($config['forms']['*']);

        $config['forms'] = array_normalise_keys($config['forms'], 'slug', null);
        foreach ($config['forms'] as &$form) {
            $form = $this->normaliseForm(array_merge($default, $form), $config);
        }

        return $config;
    }

    /**
     * Builds a table config. This will also normalise the data and fill in the blanks
     */
    protected function normaliseForm(array $form, array $config) : array
    {
        // Fill in the title
        if (!array_key_exists('title', $form)) {
            $form['title'] = Str::title(
                str_humanise($form['slug']) . ' ' .
                str_humanise(Str::camel($config['name']))
            );
        }

        $form['tabs'] = $this->normaliseFormTabs($form['tabs'], $config);
        $form['fields'] = $this->normaliseFormFields($form['fields'], $config);
        $form['actions'] = $this->normaliseActions($form['actions'], $config);

        return $form;
    }

    /**
     * Builds a table config. This will also normalise the data and fill in the blanks
     */
    protected function normaliseFormTabs(array $tabs, array $config) : array
    {
       // Normalise actions
        $tabs = array_normalise_keys($tabs, 'slug', 'label');
        foreach ($tabs as &$tab) {
            if (!array_key_exists('label', $tab)) {
                $tab['label'] = str_humanise($tab['slug']);
            }
        }

        return $tabs;
    }

    /**
     * Builds a table config. This will also normalise the data and fill in the blanks
     */
    protected function normaliseFormFields(array $fields, array $config) : array
    {
        $formFields = [];
        // Normalise actions
        foreach ($fields as $tabName => &$tabFields) {
            $formFields[$tabName] = [];
            foreach (array_normalise_keys($tabFields, null, null) as $fieldName => $field) {
                $formFields[$tabName][$fieldName] = array_merge($config['fields'][$fieldName], $field);
            }
        }

        return $formFields;
    }

    /**
     * Normalises the actions and fills in the gaps with sensible defaults
     */
    protected function normaliseActions(array $actions, array $config) : array
    {
        // Normalise actions
        $actions = array_normalise_keys($actions, 'name', 'action');
        foreach ($actions as &$action) {
            // Get the action from the name if not set separately
            if (!array_key_exists('action', $action)) {
                $action['action'] = $action['name'];
            }
            // Try to build a label from the name if not set
            if (!array_key_exists('label', $action)) {
                $action['label'] = Str::title(str_humanise($action['name']));
            }

            // Default save to a submit type, others will not
            if (!array_key_exists('submit', $action)) {
                $action['submit'] = ($action['action'] == 'save');
            }

            // Set the default view to an action button
            if (!array_key_exists('view', $action)) {
                $action['view'] = 'helium::partials.action-button';
            }

            // Create a url.
            if (!$action['submit'] &&
                !array_key_exists('url', $action) &&
                Route::has('helium.entity.' . $action['action'])
            ) {
                $action['url'] = str_replace(
                    '%id%',
                    '{entry.id}',
                    route(
                        'helium.entity.' . $action['action'],
                        [
                            'type' => $config['slug'],
                            'id' => '%id%'
                        ]
                    )
                );
            }

            // Some preset icons
            if (!isset($action['iconClass'])) {
                switch ($action['action']) {
                    case 'save':
                        $action['iconClass'] = 'fas fa-save';
                        break;
                    case 'edit':
                        $action['iconClass'] = 'fas fa-edit';
                        break;
                }
            }

            // Some preset handlers for form submitting
            if ($action['submit'] && !isset($action['handler'])) {
                switch ($action['action']) {
                    case 'save':
                        $action['handler'] = FormHandler::class;
                        break;
                }
            }

            // If a class name is given but no function call handle
            if (array_key_exists('handler', $action) &&
                is_string($action['handler']) &&
                strpos($action['handler'], '@') === false
            ) {
                $action['handler'] .= '@handle';
            }
        }

        return $actions;
    }
}
