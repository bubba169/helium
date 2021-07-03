<?php

namespace Helium\Config\Action;

use Helium\Config\Action\Action;

class ViewLinkAction extends Action
{
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'template':
                return 'helium::partials.link';
            case 'view':
                return $this->action;
            case 'url':
                return route('helium.entity.view', [
                    'type' => $this->entity->slug,
                    'view' => $this->view,
                    'id' => '%entry.id%',
                ]);
        }

        return parent::getDefault($key);
    }
}
