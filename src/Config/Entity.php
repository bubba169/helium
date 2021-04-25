<?php

namespace Helium\Config;

use Exception;
use Illuminate\Support\Arr;
use Helium\Traits\HasConfig;
use Helium\Config\Table\Table;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Entity
{
    use HasConfig;

    public string $slug;
    public string $model;
    public Table $table;
    public array $fields = [];

    /**
     * An Entity config object
     */
    public function __construct(string $type)
    {
        $config = config('helium.entities.' . $type);

        if (empty($config)) {
            throw new NotFoundHttpException();
        }

        if (empty($config['model'])) {
            throw new Exception($this->slug . ' entity configuration does not specify a model');
        }

        $this->slug = $type;
        $this->model = $config['model'];
        $this->table = new Table($config['table'], $this);

        // Fields are not expanded - they are cached here to use as a base for
        $this->fields = array_normalise_keys(Arr::get($config, 'fields', []), 'slug', 'field');
    }

    /**
     * Gets the default name for the current config
     *
     * @return string
     */
    protected function getDefault(string $key)
    {
        switch ($key) {
            case 'name':
                return class_basename($this->model);
        }

        return null;
    }
}
