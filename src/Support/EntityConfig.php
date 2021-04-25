<?php

namespace Helium\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Form\RelatedOptionsHandler;
use Helium\Table\DefaultListingHandler;
use Helium\Http\Requests\SaveEntityFormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityConfig
{
    protected const DEFAULT = [
        'table' => [
            'view' => 'helium::table',
            'filters' => [],
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

        $config['fields'] = array_normalise_keys($config['fields'], 'slug', 'type');

        $config = $this->normaliseTable($config);
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

        // Set a default base query
        if (!array_key_exists('handler', $config['table'])) {
            $config['table']['handler'] = DefaultListingHandler::class;
        }

        // Set the default search handler
        if (!empty($config['table']['search'])) {
            // Set the default search handler
            if (!array_key_exists('handler', $config['table']['search'])) {
                $config['table']['search']['handler'] = DefaultSearchHandler::class;
            }
            if (!array_key_exists('type', $config['table']['search'])) {
                $config['table']['search']['type'] = 'search';
            }
            if (!array_key_exists('slug', $config['table']['search'])) {
                $config['table']['search']['slug'] = 'search';
            }
            if (!array_key_exists('label', $config['table']['search'])) {
                $config['table']['search']['label'] = null;
            }
            $config['table']['search'] = $this->normaliseFilter($config['table']['search'], $config);
        }

        $config = $this->normaliseTableFilters($config);
        $config = $this->normaliseTableColumns($config);
        $config['table']['actions'] = $this->normaliseActions($config['table']['actions'], $config, true);

        return $config;
    }

    public function normaliseTableFilters(array $config) : array
    {
        $config['table']['filters'] = array_normalise_keys($config['table']['filters'], 'slug', 'column');
        foreach ($config['table']['filters'] as &$filter) {
            $filter = $this->normaliseFilter($filter, $config);
        }

        return $config;
    }

    public function normaliseFilter(array $filter, array $config): array
    {
        // Set the name form the slug
        if (!array_key_exists('name', $filter)) {
            $filter['name'] = $filter['slug'];
        }
        // Set the type to text if not set
        if (!array_key_exists('type', $filter)) {
            $filter['type'] = 'text';
        }
        // Look for the current value in the query string
        if (!array_key_exists('value', $filter)) {
            $filter['value'] = '{request.' . $filter['name'] . '}';
        }
        // Set the default handler if not otherwise set
        if (!array_key_exists('handler', $filter)) {
            $filter['handler'] = DefaultFilterHandler::class;
        }
        // Set the placeholder text
        if (!array_key_exists('placeholder', $filter)) {
            if (in_array($filter['type'], ['select', 'belongsTo', 'boolean'])) {
                $filter['placeholder'] = 'Select to filter... ';
            } elseif ($filter['type'] == 'search') {
                $filter['placeholder'] = 'Search';
            } else {
                $filter['placeholder'] = 'Filter By ' . Str::title(str_humanise($filter['slug']));
            }
        }
        // Boolean should use a select view to avoid confusion in the url data
        if (!array_key_exists('view', $filter) && $filter['type'] == 'boolean') {
            $filter['view'] = 'helium::form-fields.select';
            $filter['options'] = [
                'Yes' => 'yes',
                'No' => 'no',
            ];
        }
        // Normalise the field now the specifics have been set
        return $this->normaliseField($filter, $config);
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
    protected function normaliseField(array $field, array $config) : array
    {
        // Set the label to the humanised field name by default
        if (!array_key_exists('name', $field)) {
            $field['name'] = $field['slug'];
        }
        // Set the type to text by default
        if (!array_key_exists('type', $field)) {
            $field['type'] = 'text';
        }
        // Set the label to the humanised field name by default
        if ($field['type'] != 'hidden' && !array_key_exists('label', $field)) {
            $field['label'] = Str::title(str_humanise($field['slug']));
        }
        // Set the id to the field name by default
        if (!array_key_exists('id', $field)) {
            $field['id'] = $field['slug'];
        }
        // Set the column to the field name by default
        if (!array_key_exists('column', $field)) {
            $field['column'] = $field['slug'];
            if ($field['type'] == 'belongsTo') {
                $field['column'] .= '_id';
            }
        }
        // Set the value to resolve the column on the entry by default
        if (!array_key_exists('value', $field)) {
            $field['value'] = '{entry.' . $field['column'] . '}';
        }
        // If a field is required, add it to the validation rules
        if (!array_key_exists('rules', $field) && !empty($field['required'])) {
            $field['rules'] = 'required';
        }
        // Set autocomplete to off by default
        if (!array_key_exists('autocomplete', $field)) {
            if ($field['type'] == 'password') {
                $field['autocomplete'] = 'new-password';
            } else {
                $field['autocomplete'] = 'off';
            }
        }
        // Set attributes if not set
        if (!array_key_exists('attributes', $field)) {
            $field['attributes'] = [];
        }
        // Normalise the attributes. Datetime is a special case dealt with later
        if ($field['type'] != 'datetime') {
            $field['attributes'] = array_normalise_keys($field['attributes']);
        }
        // Normalise the attributes if set
        if (!array_key_exists('classes', $field)) {
            $field['classes'] = '';
        }

        // Specific field type setup
        switch ($field['type']) {
            case 'datetime':
                // Datetime is a special case that splits the attributes between two fields
                // Merge all of the attributes with two special keys for the date and time fields
                $all = Arr::except($field['attributes'], ['date', 'time']);
                $field['attributes'] = [
                    'date' => array_normalise_keys(array_merge($all, Arr::get($field['attributes'], 'date', []))),
                    'time' => array_normalise_keys(array_merge($all, Arr::get($field['attributes'], 'time', []))),
                ];
                if (is_string($field['classes'])) {
                    $field['classes'] = [
                        'date' => $field['classes'],
                        'time' => $field['classes']
                    ];
                }
                break;
            case 'password':
                // Password should never show the existing value
                $field['value'] = '';
                break;
            case 'belongsTo':
            case 'belongsToMany':
                if (!array_key_exists('options', $field)) {
                    $field['options'] = RelatedOptionsHandler::class;
                }
                if (!array_key_exists('related_id', $field)) {
                    $field['related_id'] = '{entry.id}';
                }
                if (!array_key_exists('relationship', $field)) {
                    $field['relationship'] = $field['slug'];
                }
                break;
            case 'search':
                if (!array_key_exists('prefix', $field)) {
                    $field['prefix'] = '<i class="fas fa-search" aria-hidden="true"></i>';
                }
                break;
        }

        // Set the view based on the type
        if (!array_key_exists('view', $field)) {
            $field['view'] = $this->defaultFieldView($field['type']);
        }

        return $field;
    }

    protected function normaliseForms(array $config): array
    {
        $default = $this->normaliseForm($config['forms']['*'], $config, false);
        unset($config['forms']['*']);

        $config['forms'] = array_normalise_keys($config['forms'], 'slug', null);
        foreach ($config['forms'] as &$form) {
            $form = $this->normaliseForm(array_merge($default, $form), $config, true);
        }

        return $config;
    }

    /**
     * Builds a table config. This will also normalise the data and fill in the blanks
     */
    protected function normaliseForm(array $form, array $config, bool $expand) : array
    {
        // Fill in the title
        if ($expand && !array_key_exists('title', $form)) {
            $form['title'] = Str::title(
                str_humanise($form['slug']) . ' ' .
                str_humanise(Str::camel($config['name']))
            );
        }

        $form['tabs'] = $this->normaliseFormTabs($form['tabs'], $config, $expand);
        $form['fields'] = $this->normaliseFormFields($form['fields'], $config, $expand);
        $form['actions'] = $this->normaliseActions($form['actions'], $config, $expand);

        return $form;
    }

    /**
     * Builds a table config. This will also normalise the data and fill in the blanks
     */
    protected function normaliseFormTabs(array $tabs, array $config, bool $expand) : array
    {
       // Normalise actions
        $tabs = array_normalise_keys($tabs, 'slug', 'label');

        if (!$expand) {
            return $tabs;
        }

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
    protected function normaliseFormFields(array $fields, array $config, bool $expand) : array
    {
        $formFields = [];
        // Normalise actions
        foreach ($fields as $tabName => &$tabFields) {
            $formFields[$tabName] = [];
            foreach (array_normalise_keys($tabFields, 'slug', 'type') as $fieldName => $field) {
                $formFields[$tabName][$fieldName] = array_merge($config['fields'][$fieldName], $field);

                if ($expand) {
                    $formFields[$tabName][$fieldName] = $this->normaliseField(
                        $formFields[$tabName][$fieldName],
                        $config
                    );
                }
            }
        }

        return $formFields;
    }

    /**
     * Normalises the actions and fills in the gaps with sensible defaults
     */
    protected function normaliseActions(array $actions, array $config, bool $expand) : array
    {
        // Normalise actions
        $actions = array_normalise_keys($actions, 'name', 'action');

        if (!$expand) {
            return $actions;
        }

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
            if (!$action['submit'] && !array_key_exists('url', $action)) {
                $action['url'] = str_replace(
                    '%id%',
                    '{entry.id}',
                    route(
                        'helium.entity.form',
                        [
                            'form' => $action['action'],
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
                        $action['handler'] = SaveEntityFormRequest::class;
                        break;
                }
            }
        }

        return $actions;
    }

    protected function defaultFieldView(string $type) : string
    {
        switch ($type) {
            case 'select':
            case 'belongsTo':
                return 'helium::form-fields.select';
            case 'belongsToMany':
            case 'multicheck':
                return 'helium::form-fields.multicheck';
            case 'radio':
                return 'helium::form-fields.radios';
            case 'checkbox':
                return 'helium::form-fields.checkbox';
            case 'textarea':
                return 'helium::form-fields.textarea';
            case 'datetime':
                return 'helium::form-fields.datetime';
        }

        return 'helium::form-fields.input';
    }
}
