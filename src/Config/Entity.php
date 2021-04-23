<?php

namespace Helium\Config;

use Exception;
use Helium\Config\Table\Table;
use Helium\Traits\HasConfig;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Entity
{
    use HasConfig;

    public string $slug;
    public string $model;
    public Table $table;

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
