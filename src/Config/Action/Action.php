<?php

namespace Helium\Config\Action;

use Helium\Config\Entity;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;
use Helium\Support\IconHelper;
use Illuminate\Support\Facades\Crypt;

class Action
{
    use HasConfig;

    public Entity $entity;

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
            case 'template':
                return 'helium::partials.button';
            case 'icon':
                return IconHelper::iconFor($this->action);
            case 'confirm':
                return false;
            case 'url':
                return route('helium.entity.action');
            case 'handlerParams':
                return [
                    'type' => $this->entity->slug,
                    'action' => $this->action,
                    'redirectUrl' => $this->redirectUrl,
                ];
            case 'method':
                return 'post';
            case 'redirectUrl':
                return route('helium.entity.view', ['type' => $this->entity->slug]);
        }

        return null;
    }

    /**
     * Gets all of the action data
     */
    public function getActionData(array $extraParams = []): string
    {
        return Crypt::encrypt([
            'handler' => $this->handler,
            'handlerParams' => array_merge(
                $this->handlerParams,
                $extraParams
            ),
        ]);
    }
}
