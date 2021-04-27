<?php

namespace Helium\Config;

use Helium\Config\Entity;
use Helium\Http\Requests\SaveEntityFormRequest;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;

class Action
{
    use HasConfig;

    protected Entity $entity;

    public function __construct(array $action, Entity $entity)
    {
        $this->entity = $entity;
        $this->mergeConfig($action);
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'action':
                return $this->slug;
            case 'label':
                return Str::title(str_humanise($this->slug));
            case 'view':
                return 'helium::partials.action-button';
            case 'url':
                return str_replace(
                    '%id%',
                    '{entry.id}',
                    route('helium.entity.form', [
                        'form' => $this->action,
                        'type' => $this->entity->slug,
                        'id' => '%id%'
                    ])
                );
            case 'icon':
                switch ($this->action) {
                    case 'edit':
                        return 'fas fa-edit';
                    case 'save':
                        return 'fas fa-save';
                }
                break;
            case 'request':
                switch ($this->action) {
                    case 'save':
                        return SaveEntityFormRequest::class;
                }
                break;
        }

        return null;
    }
}
