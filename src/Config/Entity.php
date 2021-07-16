<?php

namespace Helium\Config;

use Exception;
use Illuminate\Support\Arr;
use Helium\Traits\HasConfig;
use Helium\Config\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Entity
{
    use HasConfig;

    public array $fields = [];
    public array $views = [];

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

        if (empty($config['displayColumn'])) {
            throw new Exception($this->slug . ' entity configuration does not specify a display column');
        }

        $this->slug = $type;
        $this->mergeConfig($config);

        // Fields are not expanded - they are cached here to use as a base for
        $this->fields = array_normalise_keys(Arr::get($config, 'fields', []), 'slug', 'base');

        $config['views'] = array_normalise_keys(Arr::get($config, 'views', []), 'slug', 'base');
        foreach ($config['views'] as $view) {
            $base = Arr::get($view, 'base', View::class);
            if (!empty($config['views'][$base])) {
                $view = array_merge($config['views'][$base], $view);
                $base = Arr::get($config['views'][$base], 'base', View::class);
            }
            $this->views[$view['slug']] = new $base($view, $this);
        }
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
            case 'keyColumn':
                return 'id';
        }

        return null;
    }
}
