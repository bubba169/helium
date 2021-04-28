<?php

namespace Helium\Config;

use Helium\Config\Entity;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;
use Helium\Support\IconHelper;
use Helium\Http\Requests\SaveEntityFormRequest;

class Button
{
    use HasConfig;

    protected Entity $entity;

    public function __construct(array $button, Entity $entity)
    {
        $this->entity = $entity;
        $this->mergeConfig($button);
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'action':
                return $this->slug;
            case 'label':
                return Str::title(str_humanise($this->slug));
            case 'view':
                return 'helium::partials.button';
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
                return IconHelper::iconFor($this->action);
        }

        return null;
    }
}
