<?php

namespace Helium\Config;

use Exception;
use Illuminate\Support\Arr;
use Helium\Traits\HasConfig;
use Helium\Config\Form\Form;
use Helium\Config\Table\Table;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Entity
{
    use HasConfig;

    public string $slug;
    public string $model;
    public Table $table;
    public array $defaultForm;
    public array $fields = [];
    public array $forms = [];

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
        $this->defaultForm = Arr::get($config, 'forms.*', []);

        $config['forms'] = array_normalise_keys(
            Arr::except(Arr::get($config, 'forms', []), ['*']),
            'slug',
            null
        );
        foreach ($config['forms'] as $form) {
            $form = array_merge($this->defaultForm, $form);
            $this->forms[$form['slug']] = new Form($form, $this);
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
        }

        return null;
    }
}
