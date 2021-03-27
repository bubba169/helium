<?php

namespace Helium\Support;

use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityConfig
{
    protected const DEFAULT = [
        'table' => [
            'view' => 'helium::table'
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

        if (!isset($config['name'])) {
            $config['name'] = class_basename($config['model']);
        }

        $config = $this->normaliseTable($config);

        return $config;
    }

    /**
     * Builds a table config. This will also normalise the data and fill in the blanks
     */
    protected function normaliseTable(array $config) : array
    {
        $config['table']['columns'] = array_normalise_keys($config['table']['columns']);
        foreach ($config['table']['columns'] as &$column) {
            // Use the name as the value if not set
            if (!isset($column['value'])) {
                $column['value'] = $column['name'];
            }
            // Try to build a label from the value
            if (!isset($column['label'])) {
                $column['label'] = Str::title(str_humanize($column['value']));
            }
        }

        // Fill in the title
        if (!isset($config['table']['title'])) {
            $config['table']['title'] = Str::plural(str_humanize(Str::camel($config['name'])));
        }

        return $config;
    }
}
