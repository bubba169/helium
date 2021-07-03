<?php

namespace Helium\Config\View;

use Helium\Config\Entity;
use Helium\Traits\HasConfig;
use Helium\Handler\View\DefaultViewHandler;

class View
{
    use HasConfig;

    public Entity $entity;

    /**
     * Construct
     */
    public function __construct(array $config, Entity $entity)
    {
        $this->entity = $entity;
        $this->mergeConfig($config);
    }

    /**
     * Get Defaults
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'viewHandler':
                return DefaultViewHandler::class;
            case 'viewHandlerParams':
                return [];
        }

        return null;
    }
}
