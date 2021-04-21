<?php

namespace Helium\Config;

use Exception;
use Illuminate\Support\Arr;
use Helium\Config\Table\Table;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Entity
{
    public Table $table;
    public string $slug;
    public string $model;
    public string $name;

    /**
     * An Entity config object
     */
    public function __construct(string $type)
    {
        $config = config('helium.entities.' . $type);

        if (empty($config)) {
            throw new NotFoundHttpException();
        }

        $this->slug = $type;

        if (empty($config['model'])) {
            throw new Exception($this->slug . ' entity configuration does not specify a model');
        }

        $this->model = $config['model'];
        $this->name = Arr::get('name', $config, $this->defaultName());

        $this->table = new Table($config['table'], $this);
    }

    /**
     * Gets the default name for the current config
     *
     * @return string
     */
    protected function defaultName(): string
    {
        return class_basename($this->model);
    }
}
